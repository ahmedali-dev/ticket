{{-- Create Ticket --}}
<x-app-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('ticket.create_ticket') }}
                </h2>

                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('ticket.create_ticket_description') }}
                </p>

            </div>


            {{-- Back Button --}}
            <a
                href="{{ route('ticket.index') }}"
                class="inline-flex items-center gap-2 self-start rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >

                <svg
                    class="h-4 w-4 rtl:rotate-180"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                {{ __('ticket.back') }}

            </a>

        </div>

    </x-slot>


    {{-- =========================================================
        PAGE
    ========================================================== --}}

    <div class="ticket-create-page py-8">

        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">

            <div class="ticket-create-card overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div
                    class="df-page ticket-create-content"
                    style="max-width:760px;"
                >


                    {{-- =================================================
                        PAGE HEADER
                    ================================================== --}}

                    <h1
                        class="df-display"
                        style="
                            font-size:26px;
                            font-weight:700;
                            margin:0 0 4px;
                        "
                    >
                        {{ __('ticket.create_ticket') }}
                    </h1>


                    <p
                        style="
                            color:var(--text-muted);
                            font-size:14px;
                            margin:0 0 28px;
                        "
                    >
                        {{ __('ticket.submit_new_support_request') }}
                    </p>


                    {{-- =================================================
                        FORM
                    ================================================== --}}

                    <form
                        method="POST"
                        action="{{ route('ticket.store') }}"
                        enctype="multipart/form-data"
                        id="createForm"
                        class="ticket-create-form"
                    >

                        @csrf


                        {{-- =================================================
                            Ticket Title
                        ================================================== --}}

                        <div class="ticket-form-field" style="margin-bottom:22px;">

                            <label class="df-field-label">

                                {{ __('ticket.ticket_title') }}

                                <span class="df-req">
                                    *
                                </span>

                            </label>


                            <input
                                id="titleInput"
                                class="df-input"
                                type="text"
                                name="title"
                                style="width:100%;"
                                placeholder="{{ __('ticket.enter_ticket_title') }}"
                                value="{{ old('title') }}"
                            />


                            @error('title')

                            <div class="df-error-text">

                                {{ $message }}

                            </div>

                            @enderror

                        </div>


                        {{-- =================================================
                            Description
                        ================================================== --}}

                        <div class="ticket-form-field" style="margin-bottom:22px;">

                            <label class="df-field-label">

                                {{ __('ticket.description') }}

                                <span class="df-req">
                                    *
                                </span>

                            </label>


                            <textarea
                                class="df-input ticket-description-input w-full h-[10rem]"
                                name="description"
                                id="description"
                            >{{ old('description') }}</textarea>


                            @error('description')

                            <div class="df-error-text">

                                {{ $message }}

                            </div>

                            @enderror

                        </div>


                        {{-- =================================================
                            Attachments
                        ================================================== --}}

                        <div class="ticket-form-field" style="margin-bottom:8px;">

                            <label class="df-field-label">

                                {{ __('ticket.attachments') }}

                            </label>


                            <div
                                class="df-dropzone"
                                id="dropzone"
                            >

                                <div class="ticket-upload-icon">
                                    📤
                                </div>


                                <div class="ticket-upload-title">
                                    {{ __('ticket.click_to_browse_or_drag') }}
                                </div>


                                <div class="ticket-upload-help">
                                    {{ __('ticket.allowed_file_types') }}
                                </div>


                                <input
                                    id="fileInput"
                                    type="file"
                                    multiple
                                    accept=".jpg,.jpeg,.png,.webp,.pdf,.mp4,.mp3,.mov"
                                    style="display:none;"
                                    name="images[]"
                                />

                            </div>


                            {{-- Uploaded Images --}}
                            <div
                                class="df-gallery"
                                id="gallery"
                            >
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


                        {{-- =================================================
                            Submit
                        ================================================== --}}

                        <div class="ticket-submit-row">

                            <button
                                class="df-btn df-btn-primary"
                                id="submitBtn"
                                type="submit"
                                style="
                                    min-width:150px;
                                    justify-content:center;
                                "
                            >
                                {{ __('ticket.submit_ticket') }}
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | Escape HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(s) {

            return s.replace(
                /[&<>"']/g,
                c => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[c])
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Format File Size
        |--------------------------------------------------------------------------
        */

        function formatBytes(b) {

            if (b < 1024) {

                return b + ' B';

            }

            if (b < 1024 * 1024) {

                return (
                    b / 1024
                ).toFixed(1) + ' KB';

            }

            return (
                b / (1024 * 1024)
            ).toFixed(2) + ' MB';

        }


        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const dropzone =
            document.getElementById('dropzone');

        const fileInput =
            document.getElementById('fileInput');

        const gallery =
            document.getElementById('gallery');

        let selectedFiles = [];
        let previewUrls = [];

        function syncFiles() {
            const transfer = new DataTransfer();
            selectedFiles.forEach(file => transfer.items.add(file));
            fileInput.files = transfer.files;
        }


        /*
        |--------------------------------------------------------------------------
        | Add Files
        |--------------------------------------------------------------------------
        */

        function addFiles(files) {

            const dt = new DataTransfer();


            // Keep existing files
            Array
                .from(selectedFiles)
                .forEach(file => {

                    dt.items.add(file);

                });


            // Add new files
            Array
                .from(files)
                .forEach(file => {

                    dt.items.add(file);

                });


            // Update input
            fileInput.files = dt.files;

            selectedFiles = Array.from(dt.files);


            renderGallery();

        }


        /*
        |--------------------------------------------------------------------------
        | Render Gallery
        |--------------------------------------------------------------------------
        */

        function renderGallery() {

            previewUrls.forEach(url => URL.revokeObjectURL(url));
            previewUrls = [];

            gallery.innerHTML =
                Array
                    .from(selectedFiles)
                    .map((file, index) => {
                        const url = URL.createObjectURL(file);
                        previewUrls.push(url);
                        let preview;
                        if (file.type.startsWith('image/')) {
                            preview = `<img src="${url}" alt="${escapeHtml(file.name)}" class="df-gallery-thumb" />`;
                        } else if (file.type.startsWith('video/')) {
                            preview = `<video src="${url}" controls preload="metadata" class="df-gallery-thumb"></video>`;
                        } else if (file.type.startsWith('audio/')) {
                            preview = `<audio src="${url}" controls preload="metadata" class="ticket-file-audio"></audio>`;
                        } else {
                            preview = `<a href="${url}" target="_blank" rel="noopener" class="ticket-file-preview">${escapeHtml(file.name.split('.').pop().toUpperCase())}</a>`;
                        }
                        return `

                        <div class="df-gallery-card">

                            ${preview}

                            <button
                                type="button"
                                class="df-gallery-x"
                                data-index="${index}"
                                aria-label="{{ __('ticket.remove_image') }}"
                            >
                                ×
                            </button>

                            <div class="df-gallery-meta">

                                <div
                                    class="df-gallery-name"
                                    title="${escapeHtml(file.name)}"
                                >
                                    ${escapeHtml(file.name)}
                                </div>

                                <div class="df-gallery-size">
                                    ${formatBytes(file.size)}
                                </div>

                            </div>

                        </div>

                    `;
                    })
                    .join('');


            /*
            |--------------------------------------------------------------------------
            | Remove File
            |--------------------------------------------------------------------------
            */

            gallery
                .querySelectorAll('.df-gallery-x')
                .forEach(button => {

                    button.addEventListener('click', () => {

                        selectedFiles.splice(Number(button.dataset.index), 1);
                        syncFiles();


                        renderGallery();

                    });

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Click To Browse
        |--------------------------------------------------------------------------
        */

        dropzone.addEventListener(
            'click',
            event => {
                if (event.target !== fileInput) fileInput.click();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Drag Over
        |--------------------------------------------------------------------------
        */

        dropzone.addEventListener(
            'dragover',
            event => {

                event.preventDefault();

                dropzone.classList.add('drag-over');

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Drag Leave
        |--------------------------------------------------------------------------
        */

        dropzone.addEventListener(
            'dragleave',
            () => {

                dropzone.classList.remove(
                    'drag-over'
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Drop
        |--------------------------------------------------------------------------
        */

        dropzone.addEventListener(
            'drop',
            event => {

                event.preventDefault();

                dropzone.classList.remove(
                    'drag-over'
                );

                addFiles(
                    event.dataTransfer.files
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | File Input Change
        |--------------------------------------------------------------------------
        */

        fileInput.addEventListener(
            'change',
            event => {

                addFiles(
                    event.target.files
                );

            }
        );

        window.addEventListener('pagehide', () => {
            previewUrls.forEach(url => URL.revokeObjectURL(url));
        });


        /*
        |--------------------------------------------------------------------------
        | Initial Gallery
        |--------------------------------------------------------------------------
        */

        renderGallery();

    </script>

</x-app-layout>
