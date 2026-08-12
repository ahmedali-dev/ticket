@props([
    'training' => [],
    'module' => []
])

<x-app-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('training.training_center') }}
                </h2>

                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('training.training_description') }}
                </p>

            </div>

        </div>

    </x-slot>


    {{-- =========================================================
        ADD MODULE DIALOG
    ========================================================== --}}

    @if (auth()->user()->type == 'admin')

        <dialog
            id="my-dialog"
            class="hidden open:flex m-auto border-0 rounded-xl p-4 open:fixed open:inset-0 w-[500px] max-w-[calc(100%-2rem)] shadow-md shadow-blue-300 border-2 border-gray-300 flex-col items-center justify-center"
        >

            <form
                class="w-full p-4"
                id="training-form"
                method="dialog"
            >

                @csrf


                {{-- Dialog title --}}
                <h1 class="text-xl font-bold">
                    {{ __('training.new_module') }}
                </h1>


                {{-- Error --}}
                <div
                    id="form-error"
                    class="hidden mt-3 mb-1 px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm"
                ></div>


                {{-- =================================================
                    MODULE TITLE
                ================================================== --}}

                <div class="flex flex-col gap-3 my-4">

                    <label
                        for="title"
                        class="font-semibold"
                    >
                        {{ __('training.module_title') }}
                    </label>

                    <input
                        class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                        name="title"
                        id="title"
                        placeholder="{{ __('training.enter_module_title') }}"
                        value=""
                        type="text"
                    >

                </div>


                {{-- =================================================
                    MEDIA UPLOAD
                ================================================== --}}

                <div class="flex flex-col gap-3 my-6">

                    <label
                        class="flex flex-col w-full bg-gray-200 cursor-pointer h-[100px] rounded-lg items-center justify-center"
                        for="media"
                    >

                        <span class="font-semibold">
                            {{ __('training.module_title') }}
                        </span>

                        <span class="text-sm text-gray-500">
                            {{ __('training.pdf_mp4_mp3') }}
                        </span>

                    </label>


                    <div id="preview"></div>


                    {{-- Upload progress --}}
                    <div
                        id="progress-wrap"
                        class="hidden"
                    >

                        <div
                            class="flex items-center justify-between text-xs text-gray-500 mb-1"
                        >

                            <span id="progress-label">
                                {{ __('training.uploading') }}
                            </span>

                            <span id="progress-percent">
                                0%
                            </span>

                        </div>


                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">

                            <div
                                id="progress-bar"
                                class="h-full bg-blue-600 rounded-full transition-all duration-150"
                                style="width:0%"
                            ></div>

                        </div>


                        <div
                            class="flex items-center justify-between text-xs text-gray-400 mt-1"
                        >

                            <span id="progress-speed">
                                0 KB/s
                            </span>

                            <span id="progress-eta"></span>

                        </div>

                    </div>


                    <input
                        hidden
                        name="media"
                        id="media"
                        type="file"
                        accept=".pdf,.mp4,.mp3"
                    >

                </div>


                {{-- =================================================
                    MEDIA PREVIEW
                ================================================== --}}

                <div
                    class="w-full"
                    id="preview-item"
                >

                    <iframe
                        id="pdf-preview"
                        class="w-full h-full rounded-lg border border-slate-200"
                        hidden
                    ></iframe>


                    <video
                        id="video-preview"
                        class="w-full max-h-[500px] rounded-lg border border-slate-200"
                        playsinline
                        controls
                        hidden
                    ></video>

                </div>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}

                <div class="flex items-center justify-around gap-2 my-4">

                    <button
                        commandfor="my-dialog"
                        command="close"
                        id="close"
                        type="button"
                        class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 rounded-md text-md h-[3rem] bg-black text-white disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ __('training.close') }}
                    </button>


                    <button
                        id="submit"
                        type="submit"
                        class="relative flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem] disabled:opacity-60 disabled:cursor-not-allowed"
                    >

                        <svg
                            id="submit-spinner"
                            class="hidden animate-spin h-5 w-5 me-2"
                            viewBox="0 0 24 24"
                            fill="none"
                        >

                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                            ></path>

                        </svg>


                        <span id="submit-label">
                            {{ __('training.submit') }}
                        </span>

                    </button>

                </div>

            </form>

        </dialog>


        {{-- =========================================================
            MODULE UPLOAD JAVASCRIPT
        ========================================================== --}}

        <script>

            const media =
                document.getElementById('media');

            const pdfPreview =
                document.getElementById('pdf-preview');

            const preview_item =
                document.getElementById('preview-item');

            const videoPreview =
                document.getElementById('video-preview');

            const preview =
                document.getElementById('preview');

            const trainingId =
                {{ $training->id }};


            const dialog =
                document.getElementById('my-dialog');

            const form =
                document.querySelector('#training-form');

            const errorBox =
                document.getElementById('form-error');


            const progressWrap =
                document.getElementById('progress-wrap');

            const progressBar =
                document.getElementById('progress-bar');

            const progressPercent =
                document.getElementById('progress-percent');

            const progressLabel =
                document.getElementById('progress-label');

            const progressSpeed =
                document.getElementById('progress-speed');

            const progressEta =
                document.getElementById('progress-eta');


            const submitBtn =
                document.getElementById('submit');

            const submitSpinner =
                document.getElementById('submit-spinner');

            const submitLabel =
                document.getElementById('submit-label');

            const closeBtn =
                document.getElementById('close');


            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            function showError(message) {

                errorBox.textContent = message;

                errorBox.classList.remove('hidden');

            }


            function clearError() {

                errorBox.textContent = '';

                errorBox.classList.add('hidden');

            }


            /*
            |--------------------------------------------------------------------------
            | Loading
            |--------------------------------------------------------------------------
            */

            function setLoading(isLoading) {

                submitBtn.disabled =
                    isLoading;

                closeBtn.disabled =
                    isLoading;

                submitSpinner.classList.toggle(
                    'hidden',
                    !isLoading
                );


                submitLabel.textContent =
                    isLoading
                        ? @json(__('training.uploading'))
                        : @json(__('training.submit'));


                progressWrap.classList.toggle(
                    'hidden',
                    !isLoading
                );


                if (!isLoading) {

                    progressBar.style.width =
                        '0%';

                    progressPercent.textContent =
                        '0%';

                    progressSpeed.textContent =
                        '0 KB/s';

                    progressEta.textContent =
                        '';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Speed
            |--------------------------------------------------------------------------
            */

            function formatSpeed(bytesPerSec) {

                if (bytesPerSec >= 1024 * 1024) {

                    return (
                        bytesPerSec /
                        (1024 * 1024)
                    ).toFixed(1) + ' MB/s';

                }


                return (
                    bytesPerSec /
                    1024
                ).toFixed(0) + ' KB/s';

            }


            /*
            |--------------------------------------------------------------------------
            | ETA
            |--------------------------------------------------------------------------
            */

            function formatEta(seconds) {

                if (
                    !isFinite(seconds) ||
                    seconds < 0
                ) {
                    return '';
                }


                if (seconds < 1) {

                    return @json(__('training.almost_done'));

                }


                if (seconds < 60) {

                    return (
                        Math.ceil(seconds) +
                        's ' +
                        @json(__('training.left'))
                    );

                }


                const mins =
                    Math.floor(seconds / 60);

                const secs =
                    Math.ceil(seconds % 60);


                return (
                    `${mins}m ${secs}s ` +
                    @json(__('training.left'))
                );

            }


            /*
            |--------------------------------------------------------------------------
            | File Preview
            |--------------------------------------------------------------------------
            */

            function previewItem(
                filename,
                type
            ) {

                const isPdf =
                    type === 'application/pdf';


                return `
                    <div
                        class="flex items-center justify-start mt-3 bg-gray-200 p-3 rounded-xl gap-3"
                    >

                        ${
                    isPdf
                        ? `
                                    <svg
                                        class="w-7"
                                        viewBox="0 0 400 400"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >

                                        <defs>
                                            <style>
                                                .cls-1 {
                                                    fill: #ff402f;
                                                }
                                            </style>
                                        </defs>

                                        <g>

                                            <path
                                                class="cls-1"
                                                d="M325,105H250a5,5,0,0,1-5-5V25a5,5,0,0,1,10,0V95h70a5,5,0,0,1,0,10Z"
                                            />

                                            <path
                                                class="cls-1"
                                                d="M325,154.83a5,5,0,0,1-5-5V102.07L247.93,30H100A20,20,0,0,0,80,50v98.17a5,5,0,0,1-10,0V50a30,30,0,0,1,30-30H250a5,5,0,0,1,3.54,1.46l75,75A5,5,0,0,1,330,100v49.83A5,5,0,0,1,325,154.83Z"
                                            />

                                            <path
                                                class="cls-1"
                                                d="M300,380H100a30,30,0,0,1-30-30V275a5,5,0,0,1,10,0v75a20,20,0,0,0,20,20H300a20,20,0,0,0,20-20V275a5,5,0,0,1,10,0v75A30,30,0,0,1,300,380Z"
                                            />

                                            <path
                                                class="cls-1"
                                                d="M275,280H125a5,5,0,0,1,0-10H275a5,5,0,0,1,0,10Z"
                                            />

                                            <path
                                                class="cls-1"
                                                d="M200,330H125a5,5,0,0,1,0-10h75a5,5,0,0,1,0,10Z"
                                            />

                                            <path
                                                class="cls-1"
                                                d="M325,280H75a30,30,0,0,1-30-30V173.17a30,30,0,0,1,30-30h.2l250,1.66a30.09,30.09,0,0,1,29.81,30V250A30,30,0,0,1,325,280ZM75,153.17a20,20,0,0,0-20,20V250a20,20,0,0,0,20,20H325a20,20,0,0,0,20-20V174.83a20.06,20.06,0,0,0-19.88-20l-250-1.66Z"
                                            />

                                        </g>

                                    </svg>
                                `
                        : ''
                }


                        <span>
                            ${filename}
                        </span>


                        <span class="flex-1"></span>


                        <span
                            class="cursor-pointer"
                            onclick="removeModuleFile()"
                            title="{{ __('training.delete') }}"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >

                                <path d="M18 6 6 18"/>

                                <path d="m6 6 12 12"/>

                            </svg>

                        </span>

                    </div>
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Remove File
            |--------------------------------------------------------------------------
            */

            function removeModuleFile() {

                preview.innerHTML = '';

                videoPreview.hidden =
                    true;

                pdfPreview.hidden =
                    true;

                previewItem.classList.remove(
                    'h-[30rem]'
                );

                media.value = '';

                clearError();

            }


            /*
            |--------------------------------------------------------------------------
            | PDF Preview
            |--------------------------------------------------------------------------
            */

            function previewPdf(
                file
            ) {

                if (
                    !file ||
                    file.type !== 'application/pdf'
                ) {

                    pdfPreview.hidden =
                        true;

                    pdfPreview.src =
                        '';

                    return;

                }


                const pdfUrl =
                    URL.createObjectURL(file);


                pdfPreview.src =
                    pdfUrl;

                pdfPreview.hidden =
                    false;


                previewItem.classList.add(
                    'h-[30rem]'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Video Preview
            |--------------------------------------------------------------------------
            */

            function previewVideo(
                file
            ) {

                if (
                    !file ||
                    !file.type.startsWith('video/')
                ) {

                    videoPreview.removeAttribute(
                        'src'
                    );

                    return;

                }


                const videoUrl =
                    URL.createObjectURL(file);


                videoPreview.src =
                    videoUrl;

                videoPreview.hidden =
                    false;


                previewItem.classList.add(
                    'h-[30rem]'
                );


                videoPreview.onloadeddata =
                    () => {

                        URL.revokeObjectURL(
                            videoUrl
                        );

                    };

            }


            /*
            |--------------------------------------------------------------------------
            | File Changed
            |--------------------------------------------------------------------------
            */

            media.addEventListener(
                'change',
                event => {

                    const file =
                        event.target.files[0];


                    if (!file) {
                        return;
                    }


                    clearError();


                    preview.innerHTML =
                        previewItem(
                            file.name,
                            file.type
                        );


                    videoPreview.hidden =
                        true;

                    pdfPreview.hidden =
                        true;


                    previewVideo(file);

                    previewPdf(file);

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Dialog Close
            |--------------------------------------------------------------------------
            */

            dialog.addEventListener(
                'close',
                () => {

                    form.reset();

                    removeModuleFile();

                    setLoading(false);

                    clearError();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Submit Module
            |--------------------------------------------------------------------------
            */

            form.addEventListener(
                'submit',
                event => {

                    event.preventDefault();

                    clearError();


                    const title =
                        document.querySelector(
                            '#title'
                        );


                    if (
                        title.value.trim().length <= 0
                    ) {

                        showError(
                            @json(__('training.module_title_required'))
                        );

                        title.focus();

                        return;

                    }


                    if (
                        media.files.length <= 0
                    ) {

                        showError(
                            @json(__('training.file_required'))
                        );

                        return;

                    }


                    if (!trainingId) {

                        showError(
                            @json(__('training.missing_training_id'))
                        );

                        return;

                    }


                    const formData =
                        new FormData();


                    formData.append(
                        'title',
                        title.value
                    );

                    formData.append(
                        'media',
                        media.files[0]
                    );

                    formData.append(
                        'training_id',
                        trainingId
                    );


                    setLoading(true);


                    const xhr =
                        new XMLHttpRequest();


                    xhr.open(
                        'POST',
                        '{{ route('module.store') }}',
                        true
                    );


                    xhr.setRequestHeader(
                        'X-CSRF-TOKEN',
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .getAttribute(
                                'content'
                            )
                    );


                    xhr.setRequestHeader(
                        'Accept',
                        'application/json'
                    );


                    let lastLoaded =
                        0;

                    let lastTime =
                        null;

                    let smoothedSpeed =
                        0;


                    /*
                    |--------------------------------------------------------------------------
                    | Upload Progress
                    |--------------------------------------------------------------------------
                    */

                    xhr.upload.addEventListener(
                        'progress',
                        event => {

                            if (
                                !event.lengthComputable
                            ) {
                                return;
                            }


                            const now =
                                performance.now();


                            const percent =
                                Math.round(
                                    (
                                        event.loaded /
                                        event.total
                                    ) * 100
                                );


                            progressBar.style.width =
                                percent + '%';


                            progressPercent.textContent =
                                percent + '%';


                            progressLabel.textContent =
                                percent < 100
                                    ? @json(__('training.uploading'))
                                    : @json(__('training.processing'));


                            if (
                                lastTime !== null
                            ) {

                                const deltaBytes =
                                    event.loaded -
                                    lastLoaded;


                                const deltaTime =
                                    (
                                        now -
                                        lastTime
                                    ) / 1000;


                                if (
                                    deltaTime > 0
                                ) {

                                    const instantSpeed =
                                        deltaBytes /
                                        deltaTime;


                                    smoothedSpeed =
                                        smoothedSpeed === 0
                                            ? instantSpeed
                                            : (
                                                smoothedSpeed * 0.7
                                                +
                                                instantSpeed * 0.3
                                            );


                                    progressSpeed.textContent =
                                        formatSpeed(
                                            smoothedSpeed
                                        )
                                        +
                                        ' '
                                        +
                                    @json(__('training.avg'));


                                    const remainingBytes =
                                        event.total -
                                        event.loaded;


                                    const etaSeconds =
                                        smoothedSpeed > 0
                                            ? remainingBytes /
                                            smoothedSpeed
                                            : Infinity;


                                    progressEta.textContent =
                                        percent < 100
                                            ? formatEta(
                                                etaSeconds
                                            )
                                            : '';

                                }

                            }


                            lastLoaded =
                                event.loaded;

                            lastTime =
                                now;

                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Request Complete
                    |--------------------------------------------------------------------------
                    */

                    xhr.onload =
                        () => {

                            let data;


                            try {

                                data =
                                    JSON.parse(
                                        xhr.responseText
                                    );

                            } catch {

                                data =
                                    null;

                            }


                            if (
                                xhr.status >= 200 &&
                                xhr.status < 300
                            ) {

                                window.location.reload();

                                return;

                            }


                            setLoading(false);


                            const message =
                                    data?.message ||
                                @json(__('training.something_went_wrong_uploading'));


                            showError(message);

                        };


                    /*
                    |--------------------------------------------------------------------------
                    | Network Error
                    |--------------------------------------------------------------------------
                    */

                    xhr.onerror =
                        () => {

                            setLoading(false);


                            showError(
                                @json(__('training.network_error'))
                            );

                        };


                    lastLoaded =
                        0;

                    lastTime =
                        null;

                    smoothedSpeed =
                        0;


                    xhr.send(
                        formData
                    );

                }
            );

        </script>

    @endif


    {{-- =========================================================
        SIDEBAR BACKDROP
    ========================================================== --}}

    <div
        class="sidebar-backdrop"
        id="sidebarBackdrop"
    ></div>


    {{-- =========================================================
        MAIN SHELL
    ========================================================== --}}

    <div class="shell">


        {{-- =====================================================
            SIDEBAR
        ====================================================== --}}

        <aside
            class="sidebar"
            id="sidebar"
        >

            <div class="sidebar-scroll">


                {{-- =================================================
                    COURSE CARD
                ================================================== --}}

                <div class="course-card">

                    <div class="course-card-top">

                        <div>

                            <div class="course-card-title">
                                {{ $training->title }}
                            </div>

                            <div class="course-card-sub"></div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    MODULE SEARCH
                ================================================== --}}

                <div class="sidebar-search">

                    <div class="search-box">

                        <svg
                            class="icon-sm"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <circle
                                cx="11"
                                cy="11"
                                r="7"
                            ></circle>

                            <line
                                x1="21"
                                y1="21"
                                x2="16.65"
                                y2="16.65"
                            ></line>

                        </svg>


                        <input
                            id="moduleSearch"
                            type="text"
                            placeholder="{{ __('training.search_modules') }}"
                        >

                    </div>

                </div>


                {{-- =================================================
                    MODULES
                ================================================== --}}

                <nav id="sectionsWrap">

                    @foreach ($training->module as $m)

                        <div
                            class="section mx-auto my-3 w-[calc(100%-10px)] gap-2 rounded-lg flex items-center !border-0 px-3 py-1"
                        >

                            {{-- Module --}}
                            <a
                                class="section-head flex-1 rounded-lg {{ request()->routeIs('training.module_show')
                                ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                                :""
                            }}"
                                href="{{ route('training.module_show', [
                                    'training' => $training,
                                    'module' => $m,
                                ]) }}"


                            >

                                <div class="section-head-text border-none">

                                    <div class="section-title">
                                        {{ $m->title }}
                                    </div>

                                </div>

                            </a>


                            {{-- Admin Actions --}}
                            @if (auth()->user()->type == 'admin')

                                {{-- Edit --}}
                                <a
                                    href="{{ route('training.edit', $training) }}"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-blue-600 text-blue-600 bg-blue-600/10 hover:bg-blue-600 hover:text-white transition-colors"
                                    title="{{ __('training.edit') }}"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        />

                                    </svg>

                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route('training.destroy', $training) }}"
                                    method="POST"
                                    onsubmit="return confirm(@js(__('training.delete_training_confirmation')))"
                                >

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        title="{{ __('training.delete') }}"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-red-600 text-red-600 bg-red-600/10 hover:bg-red-600 hover:text-white transition-colors"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />

                                        </svg>

                                    </button>

                                </form>

                            @endif

                        </div>

                    @endforeach


                    {{-- Add Module --}}
                    @if (auth()->user()->type == 'admin')

                        <button
                            id="add_new"
                            command="show-modal"
                            commandfor="my-dialog"
                            class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]"
                        >
                            {{ __('training.add_new_module') }}
                        </button>

                    @endif

                </nav>

            </div>

        </aside>


        {{-- =====================================================
            MAIN CONTENT
        ====================================================== --}}

        <main class="main">


            @if (!empty($module))

                {{-- =================================================
                    VIDEO / PDF PLAYER
                ================================================== --}}

                <div
                    class="player"
                    id="player"
                >

                    @if (
                        $module->media &&
                        \Illuminate\Support\Str::contains(
                            $module->media->type,
                            'pdf'
                        )
                    )

                        <iframe
                            id="pdf-preview"
                            class="w-full h-[40rem] rounded-lg border border-slate-200"
                            src="{{ Storage::url($module->media->path) }}"
                        ></iframe>

                    @else

                        <div>

                            <video
                                class="rounded-lg"
                                id="my-video"
                                controls
                                src="{{ Storage::url($module->media->path) }}"
                            ></video>

                        </div>

                    @endif

                </div>


                {{-- =================================================
                    LESSON HEADER
                ================================================== --}}

                <div class="lesson-head">

                    <div>

                        <h1
                            class="lesson-title"
                            id="lessonTitle"
                        >
                            {{ $module->title }}
                        </h1>

                    </div>

                </div>


                {{-- =================================================
                    CHAPTERS
                ================================================== --}}

                @if (
                    $module->media &&
                    !\Illuminate\Support\Str::contains(
                        $module->media->type,
                        'pdf'
                    )
                )

                    <div class="chapters-card">


                        {{-- Chapters Header --}}
                        <div class="chapters-head">

                            <div class="chapters-title">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >

                                    <rect
                                        x="3"
                                        y="5"
                                        width="7"
                                        height="6"
                                        rx="1.5"
                                    ></rect>

                                    <path d="M13 6h8M13 10h8"></path>

                                    <rect
                                        x="3"
                                        y="14"
                                        width="7"
                                        height="6"
                                        rx="1.5"
                                    ></rect>

                                    <path d="M13 15h8M13 19h8"></path>

                                </svg>


                                <span>
                                    {{ __('training.video_chapters') }}
                                </span>

                            </div>


                            <span
                                class="chapters-count"
                                id="chaptersCount"
                            >
                                {{ $module->chapters->count() }}
                                {{ __('training.chapters') }}
                            </span>

                        </div>


                        {{-- =================================================
                            CHAPTER LIST
                        ================================================== --}}

                        <div id="chaptersList">


                            @forelse ($module->chapters as $chapter)

                                <button
                                    type="button"
                                    class="chapter-row"
                                    data-start="{{ $chapter->date }}"
                                    data-chapter-id="{{ $chapter->id }}"
                                >

                                    <div class="chapter-thumb">

                                        <img
                                            class="chapter-thumb-img w-full h-full object-cover rounded-md"
                                            alt="{{ $chapter->title }}"
                                        >

                                        <span class="chapter-time-badge">
                                            {{ $chapter->date }}
                                        </span>

                                    </div>


                                    <div class="chapter-row-body">

                                        <div class="chapter-row-title">
                                            {{ $chapter->title }}
                                        </div>

                                    </div>

                                </button>

                            @empty

                                <div class="p-3 font-bold">
                                    {{ __('training.no_chapter_found') }}
                                </div>

                            @endforelse


                            {{-- =================================================
                                CHAPTER DIALOG
                            ================================================== --}}

                            @if (auth()->user()->type == 'admin')

                                <dialog
                                    id="chapter-dialog"
                                    class="hidden open:flex m-auto border-0 rounded-xl p-4 open:fixed open:inset-0 w-[500px] max-w-[calc(100%-2rem)] shadow-md shadow-blue-300 border-2 border-gray-300 flex-col items-center justify-center"
                                >

                                    <form
                                        class="w-full p-4"
                                        id="chapter-form"
                                        method="dialog"
                                    >

                                        @csrf


                                        <h1 class="text-xl font-bold">
                                            {{ __('training.new_chapter') }}
                                        </h1>


                                        {{-- Error --}}
                                        <div
                                            id="chapter-form-error"
                                            class="hidden mt-3 mb-1 px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm"
                                        ></div>


                                        {{-- Module ID --}}
                                        <input
                                            type="hidden"
                                            name="module_id"
                                            id="chapter-module-id"
                                            value=""
                                        >


                                        {{-- =================================================
                                            CHAPTER TITLE
                                        ================================================== --}}

                                        <div class="flex flex-col gap-3 my-4">

                                            <label
                                                for="chapter-title"
                                                class="font-semibold"
                                            >
                                                {{ __('training.chapter_title') }}
                                            </label>


                                            <input
                                                class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                                                name="title"
                                                id="chapter-title"
                                                placeholder="{{ __('training.enter_chapter_title') }}"
                                                value=""
                                                type="text"
                                            >

                                        </div>


                                        {{-- =================================================
                                            DURATION
                                        ================================================== --}}

                                        <div class="flex flex-col gap-3 my-4">

                                            <label
                                                for="chapter-date"
                                                class="font-semibold"
                                            >

                                                {{ __('training.duration') }}

                                                <span class="text-gray-400 font-normal">

                                                    ({{ __('training.optional') }})

                                                </span>

                                            </label>


                                            <input
                                                class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                                                name="date"
                                                id="chapter-date"
                                                placeholder="{{ __('training.duration_format') }}"
                                                value=""
                                                type="text"
                                                pattern="^\d{1,3}:\d{1,2}$"
                                                title="{{ __('training.duration_format_help') }}"
                                            >

                                        </div>


                                        {{-- =================================================
                                            ACTIONS
                                        ================================================== --}}

                                        <div class="flex items-center justify-around gap-2">

                                            <button
                                                commandfor="chapter-dialog"
                                                command="close"
                                                id="chapter-close"
                                                type="button"
                                                class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 rounded-md text-md h-[3rem] bg-black text-white disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                {{ __('training.close') }}
                                            </button>


                                            <button
                                                id="chapter-submit"
                                                type="submit"
                                                class="relative flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem] disabled:opacity-60 disabled:cursor-not-allowed"
                                            >

                                                <svg
                                                    id="chapter-submit-spinner"
                                                    class="hidden animate-spin h-5 w-5 me-2"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                >

                                                    <circle
                                                        class="opacity-25"
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                        stroke="currentColor"
                                                        stroke-width="4"
                                                    ></circle>

                                                    <path
                                                        class="opacity-75"
                                                        fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                                    ></path>

                                                </svg>


                                                <span id="chapter-submit-label">
                                                    {{ __('training.submit') }}
                                                </span>

                                            </button>

                                        </div>

                                    </form>

                                </dialog>


                                {{-- =================================================
                                    CHAPTER JAVASCRIPT
                                ================================================== --}}

                                <script>

                                    const chapterDialog =
                                        document.getElementById(
                                            'chapter-dialog'
                                        );


                                    const chapterForm =
                                        document.getElementById(
                                            'chapter-form'
                                        );


                                    const chapterModuleIdInput =
                                        document.getElementById(
                                            'chapter-module-id'
                                        );


                                    const chapterTitleInput =
                                        document.getElementById(
                                            'chapter-title'
                                        );


                                    const chapterDateInput =
                                        document.getElementById(
                                            'chapter-date'
                                        );


                                    const chapterErrorBox =
                                        document.getElementById(
                                            'chapter-form-error'
                                        );


                                    const chapterSubmitBtn =
                                        document.getElementById(
                                            'chapter-submit'
                                        );


                                    const chapterSubmitSpinner =
                                        document.getElementById(
                                            'chapter-submit-spinner'
                                        );


                                    const chapterSubmitLabel =
                                        document.getElementById(
                                            'chapter-submit-label'
                                        );


                                    const chapterCloseBtn =
                                        document.getElementById(
                                            'chapter-close'
                                        );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Error
                                    |--------------------------------------------------------------------------
                                    */

                                    function showChapterError(
                                        message
                                    ) {

                                        chapterErrorBox.textContent =
                                            message;

                                        chapterErrorBox.classList.remove(
                                            'hidden'
                                        );

                                    }


                                    function clearChapterError() {

                                        chapterErrorBox.textContent =
                                            '';

                                        chapterErrorBox.classList.add(
                                            'hidden'
                                        );

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Loading
                                    |--------------------------------------------------------------------------
                                    */

                                    function setChapterLoading(
                                        isLoading
                                    ) {

                                        chapterSubmitBtn.disabled =
                                            isLoading;

                                        chapterCloseBtn.disabled =
                                            isLoading;


                                        chapterSubmitSpinner.classList.toggle(
                                            'hidden',
                                            !isLoading
                                        );


                                        chapterSubmitLabel.textContent =
                                            isLoading
                                                ? @json(__('training.saving'))
                                                : @json(__('training.submit'));

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Open Chapter Dialog
                                    |--------------------------------------------------------------------------
                                    */

                                    document.addEventListener(
                                        'click',
                                        event => {

                                            const btn =
                                                event.target.closest(
                                                    '.add-chapter-btn'
                                                );


                                            if (!btn) {
                                                return;
                                            }


                                            chapterModuleIdInput.value =
                                                btn.dataset.moduleId;


                                            chapterDialog.showModal();

                                        }
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Dialog Close
                                    |--------------------------------------------------------------------------
                                    */

                                    chapterDialog.addEventListener(
                                        'close',
                                        () => {

                                            chapterForm.reset();

                                            chapterModuleIdInput.value =
                                                '';

                                            setChapterLoading(
                                                false
                                            );

                                            clearChapterError();

                                        }
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Normalize Duration
                                    |--------------------------------------------------------------------------
                                    */

                                    function normalizeDuration(
                                        value
                                    ) {

                                        const match =
                                            value.match(
                                                /^(\d{1,3}):(\d{1,2})$/
                                            );


                                        if (!match) {
                                            return value;
                                        }


                                        const [
                                            ,
                                            minutes,
                                            seconds
                                        ] = match;


                                        return (
                                            `${minutes}:${seconds.padStart(2, '0')}`
                                        );

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Submit Chapter
                                    |--------------------------------------------------------------------------
                                    */

                                    chapterForm.addEventListener(
                                        'submit',
                                        async event => {

                                            event.preventDefault();

                                            clearChapterError();


                                            const title =
                                                chapterTitleInput
                                                    .value
                                                    .trim();


                                            const moduleId =
                                                chapterModuleIdInput
                                                    .value;


                                            const rawDate =
                                                chapterDateInput
                                                    .value
                                                    .trim();


                                            /*
                                            |--------------------------------------------------
                                            | Title Validation
                                            |--------------------------------------------------
                                            */

                                            if (
                                                title.length <= 0
                                            ) {

                                                showChapterError(
                                                    @json(__('training.please_enter_chapter_title'))
                                                );

                                                chapterTitleInput.focus();

                                                return;

                                            }


                                            /*
                                            |--------------------------------------------------
                                            | Module Validation
                                            |--------------------------------------------------
                                            */

                                            if (!moduleId) {

                                                showChapterError(
                                                    @json(__('training.missing_module_id'))
                                                );

                                                return;

                                            }


                                            const payload = {

                                                title,

                                                module_id:
                                                moduleId

                                            };


                                            /*
                                            |--------------------------------------------------
                                            | Duration
                                            |--------------------------------------------------
                                            */

                                            if (
                                                rawDate.length > 0
                                            ) {

                                                const normalized =
                                                    normalizeDuration(
                                                        rawDate
                                                    );


                                                if (
                                                    !/^\d{1,3}:\d{2}$/
                                                        .test(
                                                            normalized
                                                        )
                                                ) {

                                                    showChapterError(
                                                        @json(__('training.duration_invalid'))
                                                    );

                                                    return;

                                                }


                                                payload.date =
                                                    normalized;

                                            }


                                            setChapterLoading(
                                                true
                                            );


                                            try {

                                                const response =
                                                    await fetch(
                                                        '{{ route('chapter.store') }}',
                                                        {
                                                            method: 'POST',

                                                            headers: {

                                                                'Content-Type':
                                                                    'application/json',

                                                                'X-CSRF-TOKEN':
                                                                    document
                                                                        .querySelector(
                                                                            'meta[name="csrf-token"]'
                                                                        )
                                                                        .getAttribute(
                                                                            'content'
                                                                        ),

                                                                'Accept':
                                                                    'application/json'

                                                            },

                                                            body:
                                                                JSON.stringify(
                                                                    payload
                                                                )

                                                        }
                                                    );


                                                if (
                                                    !response.ok
                                                ) {

                                                    const error =
                                                        await response
                                                            .json()
                                                            .catch(
                                                                () => null
                                                            );


                                                    throw error;

                                                }


                                                window.location.reload();

                                            } catch (error) {

                                                setChapterLoading(
                                                    false
                                                );


                                                const message =
                                                        error?.message ||
                                                    @json(__('training.something_went_wrong'));


                                                showChapterError(
                                                    message
                                                );

                                            }

                                        }
                                    );

                                </script>


                                {{-- =================================================
                                    ADD CHAPTER BUTTON
                                ================================================== --}}

                                <button
                                    type="button"
                                    class="add-chapter-btn flex items-center justify-center gap-2 w-[calc(100%-20px)] mx-auto my-2 px-4 h-[2.5rem] rounded-md text-sm text-blue-600 border-2 border-dashed border-blue-300 hover:bg-blue-50"
                                    data-module-id="{{ $module->id }}"
                                >

                                    +

                                    {{ __('training.add_chapter') }}

                                </button>

                            @endif

                        </div>

                    </div>

                @endif

            @else

                {{-- =================================================
                    NO DATA
                ================================================== --}}

                <div>
                    {{ __('training.no_data_found') }}
                </div>

            @endif

        </main>

    </div>


    {{-- =========================================================
        VIDEO / CHAPTER JAVASCRIPT
    ========================================================== --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | Duration Parser
        |--------------------------------------------------------------------------
        */

        function parseDurationToSeconds(
            dateStr
        ) {

            if (!dateStr) {
                return 0;
            }


            const parts =
                dateStr
                    .split(':')
                    .map(Number);


            if (
                parts.some(
                    isNaN
                )
            ) {

                return 0;

            }


            if (
                parts.length === 2
            ) {

                const [
                    minutes,
                    seconds
                ] = parts;


                return (
                    minutes * 60 +
                    seconds
                );

            }


            if (
                parts.length === 3
            ) {

                const [
                    hours,
                    minutes,
                    seconds
                ] = parts;


                return (
                    hours * 3600 +
                    minutes * 60 +
                    seconds
                );

            }


            return 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Main Video
        |--------------------------------------------------------------------------
        */

        const myVideo =
            document.getElementById(
                'my-video'
            );


        /*
        |--------------------------------------------------------------------------
        | Seek To Chapter
        |--------------------------------------------------------------------------
        */

        function seekToChapter(
            row
        ) {

            if (!myVideo) {
                return;
            }


            const seconds =
                parseDurationToSeconds(
                    row.dataset.start
                );


            myVideo.currentTime =
                seconds;


            myVideo.play()
                .catch(
                    () => {
                    }
                );


            document
                .querySelectorAll(
                    '.chapter-row'
                )
                .forEach(
                    currentRow => {

                        currentRow.classList.remove(
                            'current'
                        );

                    }
                );


            row.classList.add(
                'current'
            );


            myVideo.scrollIntoView(
                {
                    behavior: 'smooth',

                    block: 'start'

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Chapter Click
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.chapter-row'
            )
            .forEach(
                row => {

                    row.addEventListener(
                        'click',
                        () => {

                            seekToChapter(
                                row
                            );

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Active Chapter During Playback
        |--------------------------------------------------------------------------
        */

        if (myVideo) {

            myVideo.addEventListener(
                'timeupdate',
                () => {

                    const rows =
                        Array.from(
                            document.querySelectorAll(
                                '.chapter-row'
                            )
                        );


                    let activeRow =
                        null;


                    for (
                        const row of rows
                        ) {

                        const rowSeconds =
                            parseDurationToSeconds(
                                row.dataset.start
                            );


                        if (
                            myVideo.currentTime >=
                            rowSeconds
                        ) {

                            activeRow =
                                row;

                        }

                    }


                    if (activeRow) {

                        rows.forEach(
                            row => {

                                row.classList.remove(
                                    'current'
                                );

                            }
                        );


                        activeRow.classList.add(
                            'current'
                        );

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Generate Poster
        |--------------------------------------------------------------------------
        */

        function generatePosterAt(
            videoUrl,
            seekSeconds
        ) {

            return new Promise(
                (
                    resolve,
                    reject
                ) => {

                    const video =
                        document.createElement(
                            'video'
                        );


                    video.preload =
                        'metadata';

                    video.muted =
                        true;

                    video.playsInline =
                        true;

                    video.crossOrigin =
                        'anonymous';

                    video.src =
                        videoUrl;


                    video.addEventListener(
                        'loadedmetadata',
                        () => {

                            const target =
                                Math.min(
                                    seekSeconds,
                                    Math.max(
                                        video.duration - 0.1,
                                        0
                                    )
                                );


                            video.currentTime =
                                target;

                        }
                    );


                    video.addEventListener(
                        'seeked',
                        () => {

                            const canvas =
                                document.createElement(
                                    'canvas'
                                );


                            canvas.width =
                                video.videoWidth;

                            canvas.height =
                                video.videoHeight;


                            const ctx =
                                canvas.getContext(
                                    '2d'
                                );


                            ctx.drawImage(
                                video,
                                0,
                                0,
                                canvas.width,
                                canvas.height
                            );


                            canvas.toBlob(
                                blob => {

                                    if (!blob) {

                                        reject(
                                            new Error(
                                                @json(__('training.could_not_load_video'))
                                            )
                                        );

                                        return;

                                    }


                                    resolve(
                                        URL.createObjectURL(
                                            blob
                                        )
                                    );

                                },
                                'image/jpeg',
                                0.85
                            );

                        },
                        {
                            once: true
                        }
                    );


                    video.addEventListener(
                        'error',
                        () => {

                            reject(
                                new Error(
                                    @json(__('training.could_not_load_video'))
                                )
                            );

                        }
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Load Chapter Posters
        |--------------------------------------------------------------------------
        */

        async function loadChapterPosters(
            videoUrl
        ) {

            const rows =
                document.querySelectorAll(
                    '.chapter-row'
                );


            if (
                rows.length === 0
            ) {

                return;

            }


            for (
                const row of rows
                ) {

                const dateStr =
                    row.dataset.start;


                const img =
                    row.querySelector(
                        '.chapter-thumb-img'
                    );


                const seconds =
                    parseDurationToSeconds(
                        dateStr
                    );


                try {

                    const posterUrl =
                        await generatePosterAt(
                            videoUrl,
                            seconds
                        );


                    img.src =
                        posterUrl;

                } catch (error) {

                    console.error(
                        `Poster failed for chapter ${row.dataset.chapterId}:`,
                        error
                    );

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Load Posters When Video Exists
        |--------------------------------------------------------------------------
        */

        @if (
            !empty($module) &&
            $module->media &&
            !\Illuminate\Support\Str::contains(
                $module->media->type,
                'pdf'
            )
        )

        loadChapterPosters(
            @json(Storage::url($module->media->path))
        );

        @endif


        /*
        |--------------------------------------------------------------------------
        | Module Search
        |--------------------------------------------------------------------------
        */

        const moduleSearch =
            document.getElementById(
                'moduleSearch'
            );


        if (moduleSearch) {

            moduleSearch.addEventListener(
                'input',
                event => {

                    const search =
                        event.target.value
                            .trim()
                            .toLowerCase();


                    document
                        .querySelectorAll(
                            '#sectionsWrap .section'
                        )
                        .forEach(
                            section => {

                                const title =
                                    section
                                        .querySelector(
                                            '.section-title'
                                        )
                                        ?.textContent
                                        .trim()
                                        .toLowerCase() || '';


                                section.style.display =
                                    title.includes(
                                        search
                                    )
                                        ? ''
                                        : 'none';

                            }
                        );

                }
            );

        }

    </script>

</x-app-layout>
