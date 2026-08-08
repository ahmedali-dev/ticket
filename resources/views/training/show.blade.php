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

    <dialog id="my-dialog"
            class="m-auto border-0 rounded-xl p-4 open:fixed open:inset-0 w-[500px] shadow-md shadow-blue-300 border-2 border-gray-300 ">
        <form class="w-full p-4" id="training-form" method="dialog">
            @csrf
            <h1 class="text-xl">New Models</h1>
            <div class="flex flex-col gap-3 my-4">
                <label for="title">Module Title</label>
                <input
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="title" id="title" placeholder="Enter the Module Title" value="" type="text"/>
            </div>

            <div class="flex flex-col gap-3 my-6">
                <label
                    class="flex flex-col w-full bg-gray-200 cursor-pointer h-[100px] rounded-lg  items-center justify-center"
                    for="media">

                    <span>Module Title</span>
                    <span>PDF, MP4, MP3</span>
                </label>

                <div id="preview">

                </div>

                <input
                    hidden
                    name="media"
                    id="media"
                    type="file"
                    accept=".pdf,.mp4,.mp3"
                >
            </div>

            <div class="w-full" id="preview-item">
                <iframe
                    id="pdf-preview"
                    class="w-full h-full rounded-lg border border-slate-200"
                    hidden
                >

                </iframe>

                <video
                    id="video-preview"
                    class="w-full max-h-[500px] rounded-lg border border-slate-200"
                    controls
                    hidden
                ></video>
            </div>

            <div class="flex items-center justify-around gap-2">

                <button
                    commandfor="my-dialog" command="close"
                    id="close"
                    type="button"
                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 rounded-md text-md h-[3rem] bg-black text-white">
                    Close
                </button>

                <button
                    id="submit"
                    type="submit"
                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]">
                    Submit
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
        const training_id = {{$training->id}};

        function previewItem(filename, type) {
            return `<div class="flex items-center justify-start mt-3 bg-gray-200 p-3 rounded-xl gap-3">

                        ${type === "application/pdf" ? `<svg class="w-7" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">

                <defs>

                    <style>.cls-1 {
                        fill: #ff402f;
                    }</style>

                </defs>

                <title/>

                <g id="xxx-word">

                    <path class="cls-1"
                          d="M325,105H250a5,5,0,0,1-5-5V25a5,5,0,0,1,10,0V95h70a5,5,0,0,1,0,10Z"/>

                    <path class="cls-1"
                          d="M325,154.83a5,5,0,0,1-5-5V102.07L247.93,30H100A20,20,0,0,0,80,50v98.17a5,5,0,0,1-10,0V50a30,30,0,0,1,30-30H250a5,5,0,0,1,3.54,1.46l75,75A5,5,0,0,1,330,100v49.83A5,5,0,0,1,325,154.83Z"/>

                    <path class="cls-1"
                          d="M300,380H100a30,30,0,0,1-30-30V275a5,5,0,0,1,10,0v75a20,20,0,0,0,20,20H300a20,20,0,0,0,20-20V275a5,5,0,0,1,10,0v75A30,30,0,0,1,300,380Z"/>

                    <path class="cls-1" d="M275,280H125a5,5,0,0,1,0-10H275a5,5,0,0,1,0,10Z"/>

                    <path class="cls-1" d="M200,330H125a5,5,0,0,1,0-10h75a5,5,0,0,1,0,10Z"/>

                    <path class="cls-1"
                          d="M325,280H75a30,30,0,0,1-30-30V173.17a30,30,0,0,1,30-30h.2l250,1.66a30.09,30.09,0,0,1,29.81,30V250A30,30,0,0,1,325,280ZM75,153.17a20,20,0,0,0-20,20V250a20,20,0,0,0,20,20H325a20,20,0,0,0,20-20V174.83a20.06,20.06,0,0,0-19.88-20l-250-1.66Z"/>

                    <path class="cls-1"
                          d="M145,236h-9.61V182.68h21.84q9.34,0,13.85,4.71a16.37,16.37,0,0,1-.37,22.95,17.49,17.49,0,0,1-12.38,4.53H145Zm0-29.37h11.37q4.45,0,6.8-2.19a7.58,7.58,0,0,0,2.34-5.82,8,8,0,0,0-2.17-5.62q-2.17-2.34-7.83-2.34H145Z"/>

                    <path class="cls-1"
                          d="M183,236V182.68H202.7q10.9,0,17.5,7.71t6.6,19q0,11.33-6.8,18.95T200.55,236Zm9.88-7.85h8a14.36,14.36,0,0,0,10.94-4.84q4.49-4.84,4.49-14.41a21.91,21.91,0,0,0-3.93-13.22,12.22,12.22,0,0,0-10.37-5.41h-9.14Z"/>

                    <path class="cls-1"
                          d="M245.59,236H235.7V182.68h33.71v8.24H245.59v14.57h18.75v8H245.59Z"/>

                </g>

            </svg>` : ''}
                        <span id="filename">
                        ${filename}
                        </span>
                        <span class="flex-1"></span>
                        <span id="remove" class="cursor-pointer" onclick="remove()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                            </svg>
                        </span>


                    </div>`
        }

        function remove() {
            preview.innerHTML = "";

            video_preview.hidden = true;
            pdf_preview.hidden = true;
            preview_item.classList.remove('h-[30rem]')
            media.value = []
        }

        function pdf(file, pdf_preview) {
            if (!file) {
                pdf_preview.hidden = true;
                pdf_preview.src = '';
                return;
            }
            console.log(file.type)
            if (file.type !== "application/pdf") {
                preview_item.classList.remove('h-[30rem]')
                // media.value = '';
                return;
            }


            const pdfUrl = URL.createObjectURL(file);
            console.log('pef url ==> ', pdfUrl);
            pdf_preview.src = pdfUrl;
            pdf_preview.hidden = false;
            preview_item.classList.add('h-[30rem]')
        }


        function video(file, video_preview) {
            if (!file.type.startsWith('video/')) {

                video_preview.value = '';
                preview_item.classList.remove('h-[3rem]')
                return;
            }

            const videoUrl = URL.createObjectURL(file);

            video_preview.src = videoUrl;
            video_preview.hidden = false;
            preview_item.classList.add('h-[30rem]')

            video_preview.onloadeddata = () => {
                URL.revokeObjectURL(videoUrl);
            };

        }

        media.addEventListener('change', (e) => {
            const file = e.target.files[0];
            console.log(file)
            preview.innerHTML = previewItem(file.name, file.type)

            video_preview.hidden = true;
            pdf_preview.hidden = true;
            video(file, video_preview);
            pdf(file, pdf_preview)


        })

        const form = document.querySelector('#training-form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();

            const title = document.querySelector('#title');
            if (title.value.length <= 0) {
                console.log('no title')
                return;
            }

            formData.append('title', title.value);

            console.log(media.files)
            if (media.files.length <= 0) {
                console.log('no file')
                return;
            }

            formData.append('media', media.files[0]);

            if (training_id == '') {
                console.log('no trining id')
                return;
            }

            formData.append('training_id', training_id);
            try {
                const response = await window.axios.post('{{route('module.store')}}', formData);
                console.log(response.data);
            } catch (error) {
                console.error(error.response?.data || error);
            }
        });
    </script>


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
                               data-ar-ph="ابحث في الوحدات..."
                               placeholder="Search modules...">
                    </div>
                </div>

                <nav id="sectionsWrap">
                    @foreach ($training->module as $module)
                        <div class="section open">
                            <a class="section-head" href="{{route('training.show', $training)}}">

                                <div class="section-head-text">
                                    <div class="section-title">{{ $module->title }}</div>
                                </div>

                            </a>

                        </div>
                    @endforeach

                    <button
                        id="add_new"
                        command="show-modal" commandfor="my-dialog"
                        class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]">
                        Add New Module
                    </button>


                    <div class="section">
                        <button class="section-head">
                            <svg class="section-chevron icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            <div class="section-head-text">
                                <div class="section-title">Color &amp; Typography</div>
                                <div class="section-count">3 modules</div>
                            </div>
                            <span class="section-badge">0/3</span>
                        </button>
                        <div class="module-list">
                            <button class="module"><span class="module-status todo"></span>
                                <div class="module-body">
                                    <div class="module-title">Color Theory Basics</div>
                                    <div class="module-meta">9:30</div>
                                </div>
                            </button>
                            <button class="module locked" disabled=""><span class="module-status locked-icon"><svg
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round" width="12" height="12">
                    <rect x="5" y="11" width="14" height="9" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                  </svg></span>
                                <div class="module-body">
                                    <div class="module-title">Choosing Font Pairs</div>
                                    <div class="module-meta">7:18</div>
                                </div>
                            </button>
                            <button class="module locked" disabled=""><span class="module-status locked-icon"><svg
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round" width="12" height="12">
                    <rect x="5" y="11" width="14" height="9" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                  </svg></span>
                                <div class="module-body">
                                    <div class="module-title">Accessible Contrast</div>
                                    <div class="module-meta">6:44</div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="section">
                        <button class="section-head">
                            <svg class="section-chevron icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            <div class="section-head-text">
                                <div class="section-title">Prototyping</div>
                                <div class="section-count">2 modules</div>
                            </div>
                            <span class="section-badge">0/2</span>
                        </button>
                        <div class="module-list">
                            <button class="module locked" disabled=""><span
                                    class="module-status locked-icon"><svg viewBox="0 0 24 24" fill="none"
                                                                           stroke="currentColor"
                                                                           stroke-width="2" stroke-linecap="round"
                                                                           stroke-linejoin="round" width="12"
                                                                           height="12">
                    <rect x="5" y="11" width="14" height="9" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                  </svg></span>
                                <div class="module-body">
                                    <div class="module-title">From Wireframe to Prototype</div>
                                    <div class="module-meta">14:02</div>
                                </div>
                            </button>
                            <button class="module locked" disabled=""><span class="module-status locked-icon"><svg
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round" width="12" height="12">
                    <rect x="5" y="11" width="14" height="9" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                  </svg></span>
                                <div class="module-body">
                                    <div class="module-title">Usability Testing 101</div>
                                    <div class="module-meta">11:27</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="sidebar-footer">
                <button class="btn btn-primary" id="continueBtn">
                    <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="6 3 20 12 6 21 6 3"></polygon>
                    </svg>
                    <span data-en="Continue Learning" data-ar="متابعة التعلّم">Continue Learning</span>
                </button>
            </div>
        </aside>


        <main class="main">


            <!-- Video player -->
            <div class="player" id="player">

                @if (\Illuminate\Support\Str::contains($module->media->type, 'pdf'))
                    <iframe
                        id="pdf-preview"
                        class="w-full h-full rounded-lg border border-slate-200"
                        src="{{Storage::url($module->media->path)}}"
                    ></iframe>
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
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
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
                        <button class="chapter-row current" data-start="0">
                            <div class="chapter-thumb">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="7 4 20 12 7 20 7 4"></polygon>
                                </svg>
                                <span class="chapter-time-badge">0:00</span>
                            </div>
                            <div class="chapter-row-body">
                                <div class="chapter-row-title">Introduction</div>
                                <div class="chapter-row-range">0:00 – 12:34</div>
                            </div>
                            <span class="chapter-row-play">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <polygon points="7 4 20 12 7 20 7 4"></polygon>
              </svg>
            </span>
                        </button>
                        <button class="chapter-row" data-start="754">
                            <div class="chapter-thumb">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="7 4 20 12 7 20 7 4"></polygon>
                                </svg>
                                <span class="chapter-time-badge">12:34</span>
                            </div>
                            <div class="chapter-row-body">
                                <div class="chapter-row-title">Project Full Demo</div>
                                <div class="chapter-row-range">12:34 – 20:22</div>
                            </div>
                            <span class="chapter-row-play">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <polygon points="7 4 20 12 7 20 7 4"></polygon>
              </svg>
            </span>
                        </button>
                        <button class="chapter-row" data-start="1222">
                            <div class="chapter-thumb">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="7 4 20 12 7 20 7 4"></polygon>
                                </svg>
                                <span class="chapter-time-badge">20:22</span>
                            </div>
                            <div class="chapter-row-body">
                                <div class="chapter-row-title">More Demo</div>
                                <div class="chapter-row-range">20:22 – 21:40</div>
                            </div>
                            <span class="chapter-row-play">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <polygon points="7 4 20 12 7 20 7 4"></polygon>
              </svg>
            </span>
                        </button>
                    </div>
                </div>
            @endif
            <!-- Prev/Next -->
            <div class="nav-row">
                <button class="nav-card prev" id="prevBtn" style="visibility: visible;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6" id="prevArrow"></polyline>
                    </svg>
                    <div class="nav-card-text">
                        <div class="nav-card-label" data-en="Previous lesson" data-ar="الدرس السابق">Previous lesson
                        </div>
                        <div class="nav-card-title" id="prevTitle">What is Visual Design?</div>
                    </div>
                </button>
                <button class="nav-card next" id="nextBtn" style="visibility: visible;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6" id="nextArrow"></polyline>
                    </svg>
                    <div class="nav-card-text">
                        <div class="nav-card-label" data-en="Next lesson" data-ar="الدرس التالي">Next lesson</div>
                        <div class="nav-card-title" id="nextTitle">Grid Systems &amp; Alignment</div>
                    </div>
                </button>
            </div>

            <!-- Comments -->
            <section class="comments">
                <div class="comments-head">
                    <h2 class="comments-title" data-en="Discussion" data-ar="النقاش">Discussion</h2>
                    <select class="sort-select" id="sortSelect">
                        <option value="newest" data-en="Newest" data-ar="الأحدث">Newest</option>
                        <option value="oldest" data-en="Oldest" data-ar="الأقدم">Oldest</option>
                        <option value="liked" data-en="Most Liked" data-ar="الأكثر إعجابًا">Most Liked</option>
                    </select>
                </div>

                <div class="comment-form">
                    <div class="avatar" style="background:var(--primary)">SA</div>
                    <div style="flex:1">
            <textarea id="commentInput" data-en-ph="Ask a question or share a thought..."
                      data-ar-ph="اطرح سؤالاً أو شارك فكرة..."
                      placeholder="Ask a question or share a thought..."></textarea>
                        <div class="comment-form-actions">
                            <button class="btn btn-primary btn-sm" id="postCommentBtn" data-en="Post" data-ar="نشر">
                                Post
                            </button>
                        </div>
                    </div>
                </div>

                <div id="commentsList">
                    <div class="comment" data-id="c1">
                        <div class="avatar" style="background:#0F6E66">OF</div>
                        <div class="comment-body">
                            <div class="comment-headrow">
                                <span class="comment-name">Omar Fadel</span>
                                <span class="comment-time">2h ago</span>
                            </div>
                            <p class="comment-text">The 60-30-10 tip finally made hierarchy click for me. Using it on my
                                portfolio
                                redesign now.</p>
                            <div class="comment-actions">
                                <button class="comment-action like-btn " data-id="c1">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path
                                            d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z">
                                        </path>
                                    </svg>
                                    <span>14</span>
                                </button>
                                <button class="comment-action reply-btn" data-id="c1">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <polyline points="9 17 4 12 9 7"></polyline>
                                        <path d="M20 18v-2a4 4 0 0 0-4-4H4"></path>
                                    </svg>
                                    <span data-en="Reply" data-ar="ردّ">Reply</span>
                                </button>
                            </div>

                            <div class="reply-input-row" id="replyInput-c1">
                                <div class="avatar"
                                     style="background:var(--primary); width:30px;height:30px;font-size:11px;">SA
                                </div>
                                <input type="text" data-en-ph="Write a reply..." data-ar-ph="اكتب ردًا..."
                                       placeholder="Write a reply..." id="replyText-c1">
                                <button class="btn btn-primary btn-sm" data-reply-submit="c1" data-en="Reply"
                                        data-ar="ردّ">Reply
                                </button>
                            </div>

                            <button class="reply-toggle" data-toggle="c1">
                                <span data-en="View 1 reply" data-ar="عرض 1 ردود">View 1 reply</span>
                            </button>
                            <div class="replies" id="replies-c1">

                                <div class="comment" data-id="c1r1">
                                    <div class="avatar" style="background:#D4972F">LH</div>
                                    <div class="comment-body">
                                        <div class="comment-headrow">
                                            <span class="comment-name">Laila Haddad</span>
                                            <span class="comment-time">1h ago</span>
                                        </div>
                                        <p class="comment-text">Love hearing that — post a screenshot in the community
                                            tab if you'd like
                                            feedback!</p>
                                        <div class="comment-actions">
                                            <button class="comment-action like-btn " data-id="c1r1">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z">
                                                    </path>
                                                </svg>
                                                <span>5</span>
                                            </button>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="comment" data-id="c2">
                        <div class="avatar" style="background:#7C5CFF">SA2</div>
                        <div class="comment-body">
                            <div class="comment-headrow">
                                <span class="comment-name">Sara Al-Otaibi</span>
                                <span class="comment-time">1d ago</span>
                            </div>
                            <p class="comment-text">Could you cover how hierarchy changes on smaller screens in a future
                                lesson?</p>
                            <div class="comment-actions">
                                <button class="comment-action like-btn " data-id="c2">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path
                                            d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z">
                                        </path>
                                    </svg>
                                    <span>9</span>
                                </button>
                                <button class="comment-action reply-btn" data-id="c2">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <polyline points="9 17 4 12 9 7"></polyline>
                                        <path d="M20 18v-2a4 4 0 0 0-4-4H4"></path>
                                    </svg>
                                    <span data-en="Reply" data-ar="ردّ">Reply</span>
                                </button>
                            </div>

                            <div class="reply-input-row" id="replyInput-c2">
                                <div class="avatar"
                                     style="background:var(--primary); width:30px;height:30px;font-size:11px;">SA
                                </div>
                                <input type="text" data-en-ph="Write a reply..." data-ar-ph="اكتب ردًا..."
                                       placeholder="Write a reply..." id="replyText-c2">
                                <button class="btn btn-primary btn-sm" data-reply-submit="c2" data-en="Reply"
                                        data-ar="ردّ">Reply
                                </button>
                            </div>


                        </div>
                    </div>
                    <div class="comment" data-id="c3">
                        <div class="avatar" style="background:#1E8E5A">YN</div>
                        <div class="comment-body">
                            <div class="comment-headrow">
                                <span class="comment-name">Yousef Nasser</span>
                                <span class="comment-time">2d ago</span>
                            </div>
                            <p class="comment-text">Clear and to the point. The mobile app example really helped.</p>
                            <div class="comment-actions">
                                <button class="comment-action like-btn " data-id="c3">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path
                                            d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z">
                                        </path>
                                    </svg>
                                    <span>3</span>
                                </button>
                                <button class="comment-action reply-btn" data-id="c3">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <polyline points="9 17 4 12 9 7"></polyline>
                                        <path d="M20 18v-2a4 4 0 0 0-4-4H4"></path>
                                    </svg>
                                    <span data-en="Reply" data-ar="ردّ">Reply</span>
                                </button>
                            </div>

                            <div class="reply-input-row" id="replyInput-c3">
                                <div class="avatar"
                                     style="background:var(--primary); width:30px;height:30px;font-size:11px;">SA
                                </div>
                                <input type="text" data-en-ph="Write a reply..." data-ar-ph="اكتب ردًا..."
                                       placeholder="Write a reply..." id="replyText-c3">
                                <button class="btn btn-primary btn-sm" data-reply-submit="c3" data-en="Reply"
                                        data-ar="ردّ">Reply
                                </button>
                            </div>


                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>


    <script>
        // const close = document.querySelector('#close')
        // const dialog = document.querySelector('dialog');
        // close.addEventListener('click', ()=>{
        //     dialog.close();
        // })
    </script>
</x-app-layout>
