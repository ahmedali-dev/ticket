@props(['training' => [], 'module' => []])


<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    Training Center
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Courses Found tech you how to use system
                </p>
            </div>

        </div>
    </x-slot>

    @if(auth()->user()->type == 'admin')
        <dialog id="my-dialog"
                class="hidden open:flex m-auto border-0 rounded-xl p-4 open:fixed open:inset-0 w-[500px] shadow-md shadow-blue-300 border-2 border-gray-300 flex-col items-center justify-center">
            <form class="w-full p-4" id="training-form" method="dialog">
                @csrf
                <h1 class="text-xl">New Models</h1>

                <div id="form-error" class="hidden mt-3 mb-1 px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm"></div>

                <div class="flex flex-col gap-3 my-4">
                    <label for="title">Module Title</label>
                    <input class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="title" id="title"
                           placeholder="Enter the Module Title" value="" type="text"/>
                </div>

                <div class="flex flex-col gap-3 my-6">
                    <label
                        class="flex flex-col w-full bg-gray-200 cursor-pointer h-[100px] rounded-lg items-center justify-center"
                        for="media">
                        <span>Module Title</span>
                        <span>PDF, MP4, MP3</span>
                    </label>

                    <div id="preview"></div>

                    <!-- Upload progress -->

                    <div id="progress-wrap" class="hidden">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span id="progress-label">Uploading…</span>
                            <span id="progress-percent">0%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-blue-600 rounded-full transition-all duration-150"
                                 style="width:0%"></div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-400 mt-1">
                            <span id="progress-speed">0 KB/s</span>
                            <span id="progress-eta"></span>
                        </div>
                    </div>

                    <input hidden name="media" id="media" type="file" accept=".pdf,.mp4,.mp3">
                </div>

                <div class="w-full" id="preview-item">
                    <iframe id="pdf-preview" class="w-full h-full rounded-lg border border-slate-200" hidden></iframe>
                    <video id="video-preview" class="w-full max-h-[500px] rounded-lg border border-slate-200" playsinline
                           hidden></video>
                </div>

                <div class="flex items-center justify-around gap-2 my-4">
                    <button commandfor="my-dialog" command="close" id="close" type="button"
                            class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 rounded-md text-md h-[3rem] bg-black text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        Close
                    </button>

                    <button id="submit" type="submit"
                            class="relative flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem] disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg id="submit-spinner" class="hidden animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="submit-label">Submit</span>
                    </button>
                </div>
            </form>
        </dialog>

        <script>
            const media = document.getElementById('media')
            const pdf_preview = document.getElementById('pdf-preview');
            const preview_item = document.getElementById('preview-item')
            const video_preview = document.getElementById('video-preview');
            const preview = document.getElementById('preview')
            const training_id = {{ $training->id }};

            const dialog = document.getElementById('my-dialog');
            const form = document.querySelector('#training-form');
            const errorBox = document.getElementById('form-error');

            const progressWrap = document.getElementById('progress-wrap');
            const progressBar = document.getElementById('progress-bar');
            const progressPercent = document.getElementById('progress-percent');
            const progressLabel = document.getElementById('progress-label');
            const progressSpeed = document.getElementById('progress-speed');
            const progressEta = document.getElementById('progress-eta');
            const submitBtn = document.getElementById('submit');
            const submitSpinner = document.getElementById('submit-spinner');
            const submitLabel = document.getElementById('submit-label');
            const closeBtn = document.getElementById('close');

            function showError(message) {
                errorBox.textContent = message;
                errorBox.classList.remove('hidden');
            }

            function clearError() {
                errorBox.textContent = '';
                errorBox.classList.add('hidden');
            }

            function setLoading(isLoading) {
                submitBtn.disabled = isLoading;
                closeBtn.disabled = isLoading;
                submitSpinner.classList.toggle('hidden', !isLoading);
                submitLabel.textContent = isLoading ? 'Uploading…' : 'Submit';
                progressWrap.classList.toggle('hidden', !isLoading);
                if (!isLoading) {
                    progressBar.style.width = '0%';
                    progressPercent.textContent = '0%';
                    progressSpeed.textContent = '0 KB/s';
                    progressEta.textContent = '';
                }
            }

            function formatSpeed(bytesPerSec) {
                if (bytesPerSec >= 1024 * 1024) {
                    return (bytesPerSec / (1024 * 1024)).toFixed(1) + ' MB/s';
                }
                return (bytesPerSec / 1024).toFixed(0) + ' KB/s';
            }

            function formatEta(seconds) {
                if (!isFinite(seconds) || seconds < 0) return '';
                if (seconds < 1) return 'almost done';
                if (seconds < 60) return Math.ceil(seconds) + 's left';
                const mins = Math.floor(seconds / 60);
                const secs = Math.ceil(seconds % 60);
                return `${mins}m ${secs}s left`;
            }

            function previewItem(filename, type) {
                return `<div class="flex items-center justify-start mt-3 bg-gray-200 p-3 rounded-xl gap-3">
                    ${type === "application/pdf" ? `<svg class="w-7" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <defs><style>.cls-1 { fill: #ff402f; }</style></defs>
            <g id="xxx-word">
                <path class="cls-1" d="M325,105H250a5,5,0,0,1-5-5V25a5,5,0,0,1,10,0V95h70a5,5,0,0,1,0,10Z"/>
                <path class="cls-1" d="M325,154.83a5,5,0,0,1-5-5V102.07L247.93,30H100A20,20,0,0,0,80,50v98.17a5,5,0,0,1-10,0V50a30,30,0,0,1,30-30H250a5,5,0,0,1,3.54,1.46l75,75A5,5,0,0,1,330,100v49.83A5,5,0,0,1,325,154.83Z"/>
                <path class="cls-1" d="M300,380H100a30,30,0,0,1-30-30V275a5,5,0,0,1,10,0v75a20,20,0,0,0,20,20H300a20,20,0,0,0,20-20V275a5,5,0,0,1,10,0v75A30,30,0,0,1,300,380Z"/>
                <path class="cls-1" d="M275,280H125a5,5,0,0,1,0-10H275a5,5,0,0,1,0,10Z"/>
                <path class="cls-1" d="M200,330H125a5,5,0,0,1,0-10h75a5,5,0,0,1,0,10Z"/>
                <path class="cls-1" d="M325,280H75a30,30,0,0,1-30-30V173.17a30,30,0,0,1,30-30h.2l250,1.66a30.09,30.09,0,0,1,29.81,30V250A30,30,0,0,1,325,280ZM75,153.17a20,20,0,0,0-20,20V250a20,20,0,0,0,20,20H325a20,20,0,0,0,20-20V174.83a20.06,20.06,0,0,0-19.88-20l-250-1.66Z"/>
                <path class="cls-1" d="M145,236h-9.61V182.68h21.84q9.34,0,13.85,4.71a16.37,16.37,0,0,1-.37,22.95,17.49,17.49,0,0,1-12.38,4.53H145Zm0-29.37h11.37q4.45,0,6.8-2.19a7.58,7.58,0,0,0,2.34-5.82,8,8,0,0,0-2.17-5.62q-2.17-2.34-7.83-2.34H145Z"/>
                <path class="cls-1" d="M183,236V182.68H202.7q10.9,0,17.5,7.71t6.6,19q0,11.33-6.8,18.95T200.55,236Zm9.88-7.85h8a14.36,14.36,0,0,0,10.94-4.84q4.49-4.84,4.49-14.41a21.91,21.91,0,0,0-3.93-13.22,12.22,12.22,0,0,0-10.37-5.41h-9.14Z"/>
                <path class="cls-1" d="M245.59,236H235.7V182.68h33.71v8.24H245.59v14.57h18.75v8H245.59Z"/>
            </g>
        </svg>` : ''}
                    <span id="filename">${filename}</span>
                    <span class="flex-1"></span>
                    <span id="remove" class="cursor-pointer" onclick="remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    </span>
                </div>`
            }

            function remove() {
                preview.innerHTML = "";
                video_preview.hidden = true;
                pdf_preview.hidden = true;
                preview_item.classList.remove('h-[30rem]')
                media.value = '';
                clearError();
            }

            function pdf(file, pdf_preview) {
                if (!file || file.type !== "application/pdf") {
                    pdf_preview.hidden = true;
                    pdf_preview.src = '';
                    return;
                }
                const pdfUrl = URL.createObjectURL(file);
                pdf_preview.src = pdfUrl;
                pdf_preview.hidden = false;
                preview_item.classList.add('h-[30rem]')
            }

            function video(file, video_preview) {
                if (!file.type.startsWith('video/')) {
                    video_preview.removeAttribute('src');
                    return;
                }
                const videoUrl = URL.createObjectURL(file);
                video_preview.src = videoUrl;
                video_preview.hidden = false;
                preview_item.classList.add('h-[30rem]')
                video_preview.onloadeddata = () => URL.revokeObjectURL(videoUrl);
            }

            media.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;

                clearError();
                preview.innerHTML = previewItem(file.name, file.type);

                video_preview.hidden = true;
                pdf_preview.hidden = true;
                video(file, video_preview);
                pdf(file, pdf_preview);
            });

            // Reset the form/preview whenever the dialog closes
            dialog.addEventListener('close', () => {
                form.reset();
                remove();
                setLoading(false);
                clearError();
            });

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                clearError();

                const title = document.querySelector('#title');

                if (title.value.trim().length <= 0) {
                    showError('Please enter a module title.');
                    title.focus();
                    return;
                }

                if (media.files.length <= 0) {
                    showError('Please choose a file to upload.');
                    return;
                }

                if (!training_id) {
                    showError('Missing training id.');
                    return;
                }

                const formData = new FormData();
                formData.append('title', title.value);
                formData.append('media', media.files[0]);
                formData.append('training_id', training_id);

                setLoading(true);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('module.store') }}', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                xhr.setRequestHeader('Accept', 'application/json');

                let lastLoaded = 0;
                let lastTime = null;
                let smoothedSpeed = 0; // bytes/sec, exponential moving average

                xhr.upload.addEventListener('progress', (e) => {
                    if (!e.lengthComputable) return;

                    const now = performance.now();
                    const pct = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = pct + '%';
                    progressPercent.textContent = pct + '%';
                    progressLabel.textContent = pct < 100 ? 'Uploading…' : 'Processing…';

                    if (lastTime !== null) {
                        const deltaBytes = e.loaded - lastLoaded;
                        const deltaTime = (now - lastTime) / 1000; // seconds

                        if (deltaTime > 0) {
                            const instantSpeed = deltaBytes / deltaTime;
                            // smooth it so the number doesn't jitter wildly
                            smoothedSpeed = smoothedSpeed === 0
                                ? instantSpeed
                                : smoothedSpeed * 0.7 + instantSpeed * 0.3;

                            progressSpeed.textContent = formatSpeed(smoothedSpeed) + ' avg';

                            const remainingBytes = e.total - e.loaded;
                            const etaSeconds = smoothedSpeed > 0 ? remainingBytes / smoothedSpeed : Infinity;
                            progressEta.textContent = pct < 100 ? formatEta(etaSeconds) : '';
                        }
                    }

                    lastLoaded = e.loaded;
                    lastTime = now;
                });

                xhr.onload = () => {
                    let data;
                    try {
                        data = JSON.parse(xhr.responseText);
                    } catch {
                        data = null;
                    }

                    if (xhr.status >= 200 && xhr.status < 300) {
                        window.location.reload();
                        return;
                    }

                    setLoading(false);
                    const message = data?.message || 'Something went wrong while uploading. Please try again.';
                    showError(message);
                };

                xhr.onerror = () => {
                    setLoading(false);
                    showError('Network error — please check your connection and try again.');
                };
                lastLoaded = 0;
                lastTime = null;
                smoothedSpeed = 0;

                xhr.send(formData);

            });
        </script>
    @endif

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- ============ SIDEBAR ============ -->
    <div class="shell">

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-scroll">
                <div class="course-card">
                    <div class="course-card-top">

                        <div>
                            <div class="course-card-title">{{ $training->title }}</div>
                            <div class="course-card-sub">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="sidebar-search">
                    <div class="search-box">
                        <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input id="moduleSearch" type="text" data-en-ph="Search modules..."
                               data-ar-ph="ابحث في الوحدات..." placeholder="Search modules...">
                    </div>
                </div>

                <nav id="sectionsWrap">
                    @foreach ($training->module as $m)

                        <div class="section open">
                            <a
                                class="section-head"
                                href="{{ route('training.module_show', [
                                    'training' => $training,
                                    'module' => $m,
                                ]) }}"
                            >

                                <div class="section-head-text">
                                    <div class="section-title">{{ $m->title }}</div>
                                </div>

                            </a>

                        </div>
                    @endforeach

                   @if(auth()->user()->type == 'admin')
                            <button id="add_new" command="show-modal" commandfor="my-dialog"
                                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]">
                                Add New Module
                            </button>
                   @endif



                </nav>
            </div>


        </aside>


        <main class="main">


            @if(!empty($module))
                <!-- Video player -->
                <div class="player" id="player">

                    {{-- resources/views/training/show.blade.php --}}

                    @if ($module->media && \Illuminate\Support\Str::contains($module->media->type, 'pdf'))
                        <iframe id="pdf-preview" class="w-full h-[40rem] rounded-lg border border-slate-200"
                                src="{{ Storage::url($module->media->path) }}"></iframe>
                    @else
                        <div>
                            <video class="rounded-lg" id="my-video" controls src="{{ Storage::url($module->media->path) }}"></video>
                        </div>
                    @endif

                </div>

                <!-- Lesson header -->
                <div class="lesson-head">
                    <div>
                        <h1 class="lesson-title" id="lessonTitle">{{$module->title}}</h1>
                    </div>

                </div>


                <!-- Hint card -->
                @if (!\Illuminate\Support\Str::contains($module->media->type, 'pdf'))
                    <div class="chapters-card">
                        <div class="chapters-head">
                            <div class="chapters-title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="7" height="6" rx="1.5"></rect>
                                    <path d="M13 6h8M13 10h8"></path>
                                    <rect x="3" y="14" width="7" height="6" rx="1.5"></rect>
                                    <path d="M13 15h8M13 19h8"></path>
                                </svg>
                                <span data-en="Chapters" data-ar="فصول الفيديو">Chapters</span>
                            </div>
                            <span class="chapters-count" id="chaptersCount">3 chapters</span>
                        </div>
                        <div id="chaptersList">

                            @forelse($module->chapters as $chapter)
                                <!-- Example chapter row markup, per chapter from DB -->
                                <button class="chapter-row" data-start="{{ $chapter->date }}" data-chapter-id="{{ $chapter->id }}">
                                    <div class="chapter-thumb">
                                        <img class="chapter-thumb-img w-full h-full object-cover rounded-md" alt="">
                                        <span class="chapter-time-badge">{{ $chapter->date }}</span>
                                    </div>
                                    <div class="chapter-row-body">
                                        <div class="chapter-row-title">{{ $chapter->title }}</div>
                                    </div>
                                </button>

                                <script>

                                    function parseDurationToSeconds(dateStr) {
                                        if (!dateStr) return 0;
                                        const parts = dateStr.split(':').map(Number);
                                        if (parts.some(isNaN)) return 0;

                                        if (parts.length === 2) {
                                            const [minutes, seconds] = parts;
                                            return minutes * 60 + seconds;
                                        }
                                        if (parts.length === 3) {
                                            const [hours, minutes, seconds] = parts;
                                            return hours * 3600 + minutes * 60 + seconds;
                                        }
                                        return 0;
                                    }

                                    function generatePosterAt(videoUrl, seekSeconds) {
                                        return new Promise((resolve, reject) => {
                                            const video = document.createElement('video');
                                            video.preload = 'metadata';
                                            video.muted = true;
                                            video.playsInline = true;
                                            video.crossOrigin = 'anonymous'; // needed if video is served from another origin/CDN
                                            video.src = videoUrl;

                                            video.addEventListener('loadedmetadata', () => {
                                                const target = Math.min(seekSeconds, Math.max(video.duration - 0.1, 0));
                                                video.currentTime = target;
                                            });

                                            video.addEventListener('seeked', () => {
                                                const canvas = document.createElement('canvas');
                                                canvas.width = video.videoWidth;
                                                canvas.height = video.videoHeight;

                                                const ctx = canvas.getContext('2d');
                                                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                                                canvas.toBlob((blob) => {
                                                    resolve(URL.createObjectURL(blob));
                                                }, 'image/jpeg', 0.85);
                                            }, { once: true });

                                            video.addEventListener('error', () => {
                                                reject(new Error('Could not load video for poster generation.'));
                                            });
                                        });
                                    }

                                    async function loadChapterPosters(videoUrl) {
                                        const rows = document.querySelectorAll('.chapter-row');

                                        for (const row of rows) {
                                            const dateStr = row.dataset.start;          // e.g. "12:34"
                                            const img = row.querySelector('.chapter-thumb-img');
                                            const seconds = parseDurationToSeconds(dateStr);

                                            try {
                                                const posterUrl = await generatePosterAt(videoUrl, seconds);
                                                img.src = posterUrl;
                                            } catch (err) {
                                                console.error(`Poster failed for chapter ${row.dataset.chapterId}:`, err);
                                            }
                                        }
                                    }

                                    // Call once the module's main video URL is known, e.g.:
                                    loadChapterPosters('{{ Storage::url($module->media->path) }}');

                                    async function loadChapterPostersEfficient(videoUrl) {
                                        const rows = document.querySelectorAll('.chapter-row');
                                        if (rows.length === 0) return;

                                        const video = document.createElement('video');
                                        video.preload = 'auto';
                                        video.muted = true;
                                        video.playsInline = true;
                                        video.crossOrigin = 'anonymous';
                                        video.src = videoUrl;

                                        await new Promise((resolve, reject) => {
                                            video.addEventListener('loadedmetadata', resolve, { once: true });
                                            video.addEventListener('error', () => reject(new Error('Video failed to load.')), { once: true });
                                        });

                                        const canvas = document.createElement('canvas');
                                        canvas.width = video.videoWidth;
                                        canvas.height = video.videoHeight;
                                        const ctx = canvas.getContext('2d');

                                        for (const row of rows) {
                                            const dateStr = row.dataset.start;
                                            const img = row.querySelector('.chapter-thumb-img');
                                            const seconds = Math.min(parseDurationToSeconds(dateStr), video.duration - 0.1);

                                            await new Promise((resolve) => {
                                                video.addEventListener('seeked', function onSeeked() {
                                                    video.removeEventListener('seeked', onSeeked);
                                                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                                                    canvas.toBlob((blob) => {
                                                        img.src = URL.createObjectURL(blob);
                                                        resolve();
                                                    }, 'image/jpeg', 0.8);
                                                });
                                                video.currentTime = seconds;
                                            });
                                        }
                                    }
                                </script>
                            @empty
                                <div class="p-3 font-bold">
                                    no chapter found
                                </div>
                            @endforelse


                            @if(auth()->user()->type =='admin')
                                    <dialog id="chapter-dialog"
                                            class="hidden open:flex m-auto border-0 rounded-xl p-4 open:fixed open:inset-0 w-[500px] shadow-md shadow-blue-300 border-2 border-gray-300 flex-col items-center justify-center">
                                        <form class="w-full p-4" id="chapter-form" method="dialog">
                                            @csrf
                                            <h1 class="text-xl">New Chapter</h1>

                                            <div id="chapter-form-error"
                                                 class="hidden mt-3 mb-1 px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm"></div>

                                            <input type="hidden" name="module_id" id="chapter-module-id" value="">

                                            <div class="flex flex-col gap-3 my-4">
                                                <label for="chapter-title">Chapter Title</label>
                                                <input class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="title" id="chapter-title"
                                                       placeholder="Enter the Chapter Title" value="" type="text" />
                                            </div>

                                            <div class="flex flex-col gap-3 my-4">
                                                <label for="chapter-date">
                                                    Duration <span class="text-gray-400 font-normal">(optional)</span>
                                                </label>
                                                <input class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="date" id="chapter-date"
                                                       placeholder="e.g. 12:34" value="" type="text" pattern="^\d{1,3}:\d{1,2}$"
                                                       title="Format: minutes:seconds, e.g. 12:34" />
                                            </div>

                                            <div class="flex items-center justify-around gap-2">
                                                <button commandfor="chapter-dialog" command="close" id="chapter-close"
                                                        type="button"
                                                        class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 rounded-md text-md h-[3rem] bg-black text-white disabled:opacity-50 disabled:cursor-not-allowed">
                                                    Close
                                                </button>

                                                <button id="chapter-submit" type="submit"
                                                        class="relative flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem] disabled:opacity-60 disabled:cursor-not-allowed">
                                                    <svg id="chapter-submit-spinner" class="hidden animate-spin h-5 w-5 mr-2"
                                                         viewBox="0 0 24 24" fill="none">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                                stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                    </svg>
                                                    <span id="chapter-submit-label">Submit</span>
                                                </button>
                                            </div>
                                        </form>
                                    </dialog>

                                    <script>
                                        const chapterDialog = document.getElementById('chapter-dialog');
                                        const chapterForm = document.getElementById('chapter-form');
                                        const chapterModuleIdInput = document.getElementById('chapter-module-id');
                                        const chapterTitleInput = document.getElementById('chapter-title');
                                        const chapterErrorBox = document.getElementById('chapter-form-error');

                                        const chapterSubmitBtn = document.getElementById('chapter-submit');
                                        const chapterSubmitSpinner = document.getElementById('chapter-submit-spinner');
                                        const chapterSubmitLabel = document.getElementById('chapter-submit-label');
                                        const chapterCloseBtn = document.getElementById('chapter-close');

                                        function showChapterError(message) {
                                            chapterErrorBox.textContent = message;
                                            chapterErrorBox.classList.remove('hidden');
                                        }

                                        function clearChapterError() {
                                            chapterErrorBox.textContent = '';
                                            chapterErrorBox.classList.add('hidden');
                                        }

                                        function setChapterLoading(isLoading) {
                                            chapterSubmitBtn.disabled = isLoading;
                                            chapterCloseBtn.disabled = isLoading;
                                            chapterSubmitSpinner.classList.toggle('hidden', !isLoading);
                                            chapterSubmitLabel.textContent = isLoading ? 'Saving…' : 'Submit';
                                        }

                                        // Open dialog for the clicked module, passing its id in
                                        document.addEventListener('click', (e) => {
                                            const btn = e.target.closest('.add-chapter-btn');
                                            if (!btn) return;

                                            chapterModuleIdInput.value = btn.dataset.moduleId;
                                            chapterDialog.showModal();
                                        });

                                        chapterDialog.addEventListener('close', () => {
                                            chapterForm.reset();
                                            chapterModuleIdInput.value = '';
                                            setChapterLoading(false);
                                            clearChapterError();
                                        });

                                        function normalizeDuration(value) {
                                            const match = value.match(/^(\d{1,3}):(\d{1,2})$/);
                                            if (!match) return value;
                                            const [, minutes, seconds] = match;
                                            return `${minutes}:${seconds.padStart(2, '0')}`;
                                        }

                                        chapterForm.addEventListener('submit', async (e) => {
                                            e.preventDefault();
                                            clearChapterError();

                                            const title = chapterTitleInput.value.trim();
                                            const moduleId = chapterModuleIdInput.value;
                                            const rawDate = document.getElementById('chapter-date').value.trim();

                                            if (title.length <= 0) {
                                                showChapterError('Please enter a chapter title.');
                                                chapterTitleInput.focus();
                                                return;
                                            }

                                            if (!moduleId) {
                                                showChapterError('Missing module id.');
                                                return;
                                            }

                                            const payload = { title, module_id: moduleId };

                                            if (rawDate.length > 0) {
                                                const normalized = normalizeDuration(rawDate);
                                                if (!/^\d{1,3}:\d{2}$/.test(normalized)) {
                                                    showChapterError('Duration must look like minutes:seconds, e.g. 12:34 or 0:05.');
                                                    return;
                                                }
                                                payload.date = normalized;
                                            }

                                            setChapterLoading(true);

                                            try {
                                                const response = await fetch('{{ route('chapter.store') }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                        'Accept': 'application/json',
                                                    },
                                                    body: JSON.stringify(payload),
                                                });

                                                if (!response.ok) {
                                                    const error = await response.json().catch(() => null);
                                                    throw error;
                                                }

                                                window.location.reload();
                                            } catch (error) {
                                                setChapterLoading(false);
                                                const message = error?.message || 'Something went wrong. Please try again.';
                                                showChapterError(message);
                                            }
                                        });
                                    </script>
                                    <button
                                        type="button"
                                        class="add-chapter-btn flex items-center justify-center gap-2 w-[calc(100%-20px)] mx-auto my-2 px-4 h-[2.5rem] rounded-md text-sm text-blue-600 border-2 border-dashed border-blue-300 hover:bg-blue-50"
                                        data-module-id="{{ $module->id }}">
                                        + Add Chapter
                                    </button>

                                @endif

                        </div>
                    </div>
                @endif

            @else
                <div>no data found</div>
            @endif
        </main>
    </div>


    <script>
        function parseDurationToSeconds(dateStr) {
            if (!dateStr) return 0;
            const parts = dateStr.split(':').map(Number);
            if (parts.some(isNaN)) return 0;

            if (parts.length === 2) {
                const [minutes, seconds] = parts;
                return minutes * 60 + seconds;
            }
            if (parts.length === 3) {
                const [hours, minutes, seconds] = parts;
                return hours * 3600 + minutes * 60 + seconds;
            }
            return 0;
        }

        const myVideo = document.getElementById('my-video');

        function seekToChapter(row) {
            const seconds = parseDurationToSeconds(row.dataset.start);

            myVideo.currentTime = seconds;
            myVideo.play();

            document.querySelectorAll('.chapter-row').forEach(r => r.classList.remove('current'));
            row.classList.add('current');

            myVideo.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        document.querySelectorAll('.chapter-row').forEach(row => {
            row.addEventListener('click', () => seekToChapter(row));
        });

        myVideo.addEventListener('timeupdate', () => {
            const rows = Array.from(document.querySelectorAll('.chapter-row'));
            let activeRow = null;

            for (const row of rows) {
                const rowSeconds = parseDurationToSeconds(row.dataset.start);
                if (myVideo.currentTime >= rowSeconds) {
                    activeRow = row;
                }
            }

            if (activeRow) {
                rows.forEach(r => r.classList.remove('current'));
                activeRow.classList.add('current');
            }
        });
    </script>
</x-app-layout>
