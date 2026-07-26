/**
 * Create Ticket form — auto-resize textarea, drag-and-drop image, submit UX.
 */
export default function createTicketForm() {
    const MAX_BYTES = 5 * 1024 * 1024;
    const ALLOWED = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    return {
        title: '',
        description: '',
        file: null,
        previewUrl: null,
        fileName: '',
        fileSize: '',
        dragOver: false,
        submitting: false,
        uploadProgress: 0,
        showProgress: false,
        progressTimer: null,
        errors: {
            title: '',
            description: '',
            image: '',
        },

        init() {
            this.$nextTick(() => this.autoResize());
        },

        autoResize() {
            const el = this.$refs.description;
            if (!el) return;
            el.style.height = 'auto';
            el.style.height = `${Math.max(el.scrollHeight, 120)}px`;
        },

        formatBytes(bytes) {
            if (bytes < 1024) return `${bytes} B`;
            if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
            return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
        },

        onDrop(event) {
            this.dragOver = false;
            const file = event.dataTransfer?.files?.[0];
            if (file) this.setFile(file);
        },

        onBrowse(event) {
            const file = event.target?.files?.[0];
            if (file) this.setFile(file);
            event.target.value = '';
        },

        setFile(file) {
            this.errors.image = '';

            if (!ALLOWED.includes(file.type)) {
                this.errors.image = 'Supported formats: JPG, JPEG, PNG, WEBP.';
                this.clearFile();
                return;
            }

            if (file.size > MAX_BYTES) {
                this.errors.image = 'The image must not be larger than 5 MB.';
                this.clearFile();
                return;
            }

            if (this.previewUrl) URL.revokeObjectURL(this.previewUrl);

            this.file = file;
            this.fileName = file.name;
            this.fileSize = this.formatBytes(file.size);
            this.previewUrl = URL.createObjectURL(file);

            const input = this.$refs.fileInput;
            if (input) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
            }
        },

        clearFile() {
            if (this.previewUrl) URL.revokeObjectURL(this.previewUrl);
            this.file = null;
            this.previewUrl = null;
            this.fileName = '';
            this.fileSize = '';
            this.showProgress = false;
            this.uploadProgress = 0;
            if (this.progressTimer) {
                clearInterval(this.progressTimer);
                this.progressTimer = null;
            }
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },

        validate() {
            this.errors.title = this.title.trim() ? '' : 'Please enter a ticket title.';
            this.errors.description = this.description.trim() ? '' : 'Please describe your issue.';
            return !this.errors.title && !this.errors.description && !this.errors.image;
        },

        /**
         * Client-side checks, then allow the native multipart POST so Laravel
         * validation / redirects / flash messages work reliably.
         * When an image is attached, show an upload progress indicator.
         */
        submit(event) {
            if (this.submitting) {
                event.preventDefault();
                return;
            }

            if (!this.validate()) {
                event.preventDefault();
                return;
            }

            this.submitting = true;

            if (this.file) {
                this.showProgress = true;
                this.uploadProgress = 8;
                this.progressTimer = setInterval(() => {
                    if (this.uploadProgress < 90) {
                        this.uploadProgress += Math.max(2, Math.round((90 - this.uploadProgress) * 0.12));
                    }
                }, 120);
            }
            // Native form submit continues (do not preventDefault)
        },
    };
}
