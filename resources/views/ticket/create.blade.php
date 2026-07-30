{{-- Create Ticket — dedicated page with drag-and-drop image upload --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    Create Ticket
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Fill out the form below to submit a support ticket.
                </p>
            </div>
            <a href="{{ route('ticket.index') }}"
                class="inline-flex items-center gap-2 self-start rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div
                class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="df-page" style="max-width:760px;">



                    <!-- Page Header -->
                    <h1 class="df-display" style="font-size:26px;font-weight:700;margin:0 0 4px;">
                        Create Ticket
                    </h1>

                    <p style="color:var(--text-muted);font-size:14px;margin:0 0 28px;">
                        Submit a new support request.
                    </p>

                    <!-- Form -->
                    <form method="post" action="{{ route('ticket.store') }}" enctype="multipart/form-data"
                        id="createForm" style="padding:26px;">
                        @csrf
                        <!-- Ticket Title -->
                        <div style="margin-bottom:22px;">
                            <label class="df-field-label">
                                Ticket Title <span class="df-req">*</span>
                            </label>

                            <input id="titleInput" class="df-input" type="text" style="width:100%;"
                                placeholder="Enter ticket title" name="title">

                            @error('title')
                                <div class="df-error-text" id="fileError">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Description -->
                        <div style="margin-bottom:22px;">
                            <label class="df-field-label">
                                Description <span class="df-req">*</span>
                            </label>

                            <textarea class="df-input w-full h-[10rem]" name="description" id=""></textarea>
                            {{-- <div name="description" id="editor"></div> --}}
                            @error('description')
                                <div class="df-error-text" id="fileError">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Attachments -->
                        <div style="margin-bottom:8px;">
                            <label class="df-field-label">
                                Attachments
                            </label>

                            <div class="df-dropzone" id="dropzone">

                                <div style="color:var(--primary);margin-bottom:8px;">
                                    📤
                                </div>

                                <div style="font-weight:600;font-size:14px;">
                                    Click to browse or drag and drop images
                                </div>

                                <div style="color:var(--text-muted);font-size:12.5px;margin-top:4px;">
                                    JPG, JPEG, PNG or WEBP · up to 5 MB each
                                </div>

                                <input id="fileInput" type="file" multiple
                                    accept=".jpg,.jpeg,.png,.webp,.pdf,.mp4,.mp3,.mov" style="display:none;"
                                    name="images[]">
                            </div>



                            <!-- Uploaded Images -->
                            <div class="df-gallery" id="gallery">
                                <!-- Uploaded image previews will appear here -->
                            </div>

                            @error('images')
                                <div class="df-error-text">
                                    {{ $message }}
                                </div>
                            @enderror

                            @foreach ($errors->get('images.*') as $messages)
                                @foreach ($messages as $message)
                                    <div class="df-error-text">
                                        {{ $message }}
                                    </div>
                                @endforeach
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <div style="display:flex;justify-content:flex-end;margin-top:26px;">
                            <button class="df-btn df-btn-primary" id="submitBtn" type="submit"
                                style="min-width:150px;justify-content:center;">
                                Submit Ticket
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>


    {{--
    <script>

        function addFiles(fileList) {
            const files = Array.from(fileList);
            let err = '';
            files.forEach(file => {
                if (!ACCEPTED_TYPES.includes(file.type)) { err = `"${file.name}" is not a supported format. Use JPG, PNG, or WEBP.`; return; }
                if (file.size > MAX_SIZE) { err = `"${file.name}" exceeds the 5 MB limit.`; return; }
                createFormImages.push({ id: uid(), file, url: URL.createObjectURL(file), name: file.name, size: file.size });
            });
        }

        function renderGallery() {
            const gallery = document.getElementById('gallery');
            gallery.innerHTML = createFormImages.map(img => `
    <div class="df-gallery-card">
      <img src="${img.url}" alt="${escapeHtml(img.name)}" class="df-gallery-thumb" />
      <button type="button" class="df-gallery-x" data-id="${img.id}" aria-label="Remove image">${ico('x', 13)}</button>
      <div class="df-gallery-meta">
        <div class="df-gallery-name" title="${escapeHtml(img.name)}">${escapeHtml(img.name)}</div>
        <div class="df-gallery-size">${formatBytes(img.size)}</div>
      </div>
    </div>`).join('');
            gallery.querySelectorAll('.df-gallery-x').forEach(btn => btn.addEventListener('click', () => {
                createFormImages = createFormImages.filter(i => i.id !== btn.getAttribute('data-id'));
                renderGallery();
            }));
        }

        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        dropzone.addEventListener('click', () => fileInput.click());
        dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('drag-over'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('drag-over'));
        dropzone.addEventListener('drop', e => { e.preventDefault(); dropzone.classList.remove('drag-over'); addFiles(e.dataTransfer.files); });
        fileInput.addEventListener('change', e => { addFiles(e.target.files); e.target.value = ''; });
        renderGallery();



    </script> --}}

    <script>
        function escapeHtml(s) { return s.replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])); }
        function formatBytes(b) { if (b < 1024) return b + ' B'; if (b < 1024 * 1024) return (b / 1024).toFixed(1) + ' KB'; return (b / (1024 * 1024)).toFixed(2) + ' MB'; }

        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');

        let galleryItems = [];

        function addFiles(files) {
            const dt = new DataTransfer();

            // Keep existing files
            // Array.from(fileInput.files).forEach(file => dt.items.add(file));

            // Add newly dropped/selected files
            Array.from(files).forEach(file => dt.items.add(file));

            // Update the input
            fileInput.files = dt.files;
            renderGallery();
        }
        function renderGallery() {
            const gallery = document.getElementById('gallery');

            gallery.innerHTML = Array.from(fileInput.files).map(img => `
        <div class="df-gallery-card">
            <img src="${URL.createObjectURL(img)}" alt="${escapeHtml(img.name)}" class="df-gallery-thumb" />
            <button type="button"
                    class="df-gallery-x"
                    data-id="${img.name}"
                    aria-label="Remove image">
                ×
            </button>
            <div class="df-gallery-meta">
                <div class="df-gallery-name" title="${escapeHtml(img.name)}">
                    ${escapeHtml(img.name)}
                </div>
                <div class="df-gallery-size">
                    ${formatBytes(img.size)}
                </div>
            </div>
        </div>
    `).join('');

            gallery.querySelectorAll('.df-gallery-x').forEach(btn => {
                btn.addEventListener('click', () => {
                    const dt = new DataTransfer();

                    const id = btn.dataset.id;
                    galleryItems = Array.from(fileInput.files).filter(item => {
                        if (item.name !== id) {
                            dt.items.add(item);
                            return;
                        }

                        return item;
                    });
                    fileInput.files = dt.files;
                    renderGallery();
                });
            });
        }

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', e => {
            e.preventDefault();
            dropzone.classList.add('drag-over');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('drag-over');
        });

        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.classList.remove('drag-over');
            addFiles(e.dataTransfer.files);

        });

        fileInput.addEventListener('change', e => {
            console.log(fileInput.files)
            addFiles(e.target.files);
        });

    </script>

</x-app-layout>