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

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- ============ SIDEBAR ============ -->
<div class="shell">

    <aside class="sidebar" id="sidebar">
    <div class="sidebar-scroll">
      <div class="course-card">
        <div class="course-card-top">
          <div class="progress-ring">
            <svg viewBox="0 0 58 58"><circle class="track" cx="29" cy="29" r="24"></circle><circle class="fill" id="ringFill" cx="29" cy="29" r="24" stroke-dasharray="150.8" stroke-dashoffset="131.2"></circle></svg>
            <div class="pct" id="ringPct">13%</div>
          </div>
          <div>
            <div class="course-card-title" data-en="UX/UI Design Foundations" data-ar="أساسيات تصميم واجهات وتجربة المستخدم">UX/UI Design Foundations</div>
            <div class="course-card-sub" data-en="Instructor: Laila Haddad" data-ar="المدرّبة: ليلى حداد">Instructor: Laila Haddad</div>
          </div>
        </div>
        <div class="course-card-progress-label">
          <span data-en="Course progress" data-ar="تقدّم الدورة">Course progress</span>
          <span id="progressText">1 / 8</span>
        </div>
        <div class="bar-track"><div class="bar-fill" id="progressBarFill" style="width: 13%;"></div></div>
      </div>

      <div class="sidebar-search">
        <div class="search-box">
          <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <input id="moduleSearch" type="text" data-en-ph="Search modules..." data-ar-ph="ابحث في الوحدات..." placeholder="Search modules...">
        </div>
      </div>

      <nav id="sectionsWrap"><div class="section open">
      <button class="section-head">
        <svg class="section-chevron icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        <div class="section-head-text">
          <div class="section-title">Design Fundamentals</div>
          <div class="section-count">3 modules</div>
        </div>
        <span class="section-badge">1/3</span>
      </button>
      <div class="module-list"><button class="module"><span class="module-status done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" width="11" height="11"><polyline points="20 6 9 17 4 12"></polyline></svg></span><div class="module-body"><div class="module-title">What is Visual Design?</div><div class="module-meta">8:12</div></div></button><button class="module active"><span class="module-status todo"></span><div class="module-body"><div class="module-title">Intro to Visual Hierarchy</div><div class="module-meta">21:40</div></div></button><button class="module"><span class="module-status todo"></span><div class="module-body"><div class="module-title">Grid Systems &amp; Alignment</div><div class="module-meta">10:05</div></div></button></div>
    </div><div class="section">
      <button class="section-head">
        <svg class="section-chevron icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        <div class="section-head-text">
          <div class="section-title">Color &amp; Typography</div>
          <div class="section-count">3 modules</div>
        </div>
        <span class="section-badge">0/3</span>
      </button>
      <div class="module-list"><button class="module"><span class="module-status todo"></span><div class="module-body"><div class="module-title">Color Theory Basics</div><div class="module-meta">9:30</div></div></button><button class="module locked" disabled=""><span class="module-status locked-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><rect x="5" y="11" width="14" height="9" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg></span><div class="module-body"><div class="module-title">Choosing Font Pairs</div><div class="module-meta">7:18</div></div></button><button class="module locked" disabled=""><span class="module-status locked-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><rect x="5" y="11" width="14" height="9" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg></span><div class="module-body"><div class="module-title">Accessible Contrast</div><div class="module-meta">6:44</div></div></button></div>
    </div><div class="section">
      <button class="section-head">
        <svg class="section-chevron icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        <div class="section-head-text">
          <div class="section-title">Prototyping</div>
          <div class="section-count">2 modules</div>
        </div>
        <span class="section-badge">0/2</span>
      </button>
      <div class="module-list"><button class="module locked" disabled=""><span class="module-status locked-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><rect x="5" y="11" width="14" height="9" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg></span><div class="module-body"><div class="module-title">From Wireframe to Prototype</div><div class="module-meta">14:02</div></div></button><button class="module locked" disabled=""><span class="module-status locked-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><rect x="5" y="11" width="14" height="9" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg></span><div class="module-body"><div class="module-title">Usability Testing 101</div><div class="module-meta">11:27</div></div></button></div>
    </div></nav>
    </div>

    <div class="sidebar-footer">
      <button class="btn btn-primary" id="continueBtn">
        <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="6 3 20 12 6 21 6 3"></polygon></svg>
        <span data-en="Continue Learning" data-ar="متابعة التعلّم">Continue Learning</span>
      </button>
    </div>
  </aside>



  <main class="main">
    <div class="breadcrumb" id="breadcrumb">
      <a href="#" data-en="Home" data-ar="الرئيسية">Home</a>
      <span class="sep">/</span>
      <a href="#" data-en="Design" data-ar="التصميم">Design</a>
      <span class="sep">/</span>
      <a href="#" data-en="UX/UI Design Foundations" data-ar="أساسيات تصميم واجهات وتجربة المستخدم">UX/UI Design Foundations</a>
      <span class="sep">/</span>
      <span class="current" id="breadcrumbCurrent">Intro to Visual Hierarchy</span>
    </div>

    <!-- Video player -->
    <div class="player" id="player">
      <div class="player-badge" data-en="HD" data-ar="جودة عالية">HD</div>
      <div class="chapter-label" id="chapterLabel">Introduction</div>
      <div class="player-poster">
        <button class="play-big" id="playBig" aria-label="Play video">
          <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg>
        </button>
      </div>
      <div class="player-controls">
        <div class="progress-row">
          <span class="time-label" id="timeCurrent">0:00</span>
          <div class="seek-wrap" id="seekWrap">
            <input type="range" class="seek" id="seek" min="0" max="100" value="0" step="0.1">
            <div class="seek-chapters" id="seekChapters"><div class="chapter-tick" style="inset-inline-start: 58%;"></div><div class="chapter-tick" style="inset-inline-start: 94%;"></div></div>
            <div class="seek-tooltip" id="seekTooltip" style="inset-inline-start: 14px;">
              <div class="seek-tooltip-thumb"><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg></div>
              <div class="seek-tooltip-time" id="seekTooltipTime">0:19</div>
              <div class="seek-tooltip-chapter" id="seekTooltipChapter">Introduction</div>
            </div>
          </div>
          <span class="time-label" id="timeDuration">21:40</span>
        </div>
        <div class="controls-row">
          <button class="ctl-btn" id="playBtn" aria-label="Play/Pause">
            <svg class="playIcon" viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg>
            <svg class="pauseIcon" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16" rx="1"></rect><rect x="14" y="4" width="4" height="16" rx="1"></rect></svg>
          </button>
          <div class="volume-wrap">
            <button class="ctl-btn" id="muteBtn" aria-label="Mute">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="4 9 8 9 12 5 12 19 8 15 4 15 4 9"></polygon><path d="M16 8.5a4 4 0 0 1 0 7"></path></svg>
            </button>
            <input type="range" class="volume-slider" id="volume" min="0" max="100" value="75">
          </div>
          <div class="spacer"></div>
          <select class="speed-select" id="speedSelect" aria-label="Playback speed">
            <option value="0.5">0.5x</option>
            <option value="0.75">0.75x</option>
            <option value="1" selected="">1x</option>
            <option value="1.25">1.25x</option>
            <option value="1.5">1.5x</option>
            <option value="2">2x</option>
          </select>
          <button class="ctl-btn" id="fullscreenBtn" aria-label="Fullscreen">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3M16 3h3a2 2 0 0 1 2 2v3M8 21H5a2 2 0 0 1-2-2v-3M16 21h3a2 2 0 0 0 2-2v-3"></path></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Lesson header -->
    <div class="lesson-head">
      <div>
        <h1 class="lesson-title" id="lessonTitle">Intro to Visual Hierarchy</h1>
        <p class="lesson-desc" id="lessonDesc">Learn how spacing, scale, and contrast guide the eye through a screen, and practice building a clear reading order for a mobile app layout.</p>
      </div>
      <div class="lesson-actions">
        <button class="btn btn-outline btn-sm" id="downloadBtn">
          <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12m0 0-4-4m4 4 4-4"></path><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"></path></svg>
          <span data-en="Resources" data-ar="الموارد">Resources</span>
        </button>
        <button class="btn btn-outline btn-sm" id="shareBtn">
          <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><path d="M8.6 10.5 15.4 6.5M8.6 13.5l6.8 4"></path></svg>
          <span data-en="Share" data-ar="مشاركة">Share</span>
        </button>
      </div>
    </div>

    <div class="info-row">
      <div class="info-pill">
        <span class="pill-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 3"></path></svg></span>
        <span><span class="pill-label" data-en="Duration" data-ar="المدة">Duration</span><b>21:40</b></span>
      </div>
      <div class="info-pill">
        <span class="pill-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20c1.2-3.6 4-5.5 7-5.5s5.8 1.9 7 5.5"></path></svg></span>
        <span><span class="pill-label" data-en="Instructor" data-ar="المدرّب">Instructor</span><b data-en="Laila Haddad" data-ar="ليلى حداد">Laila Haddad</b></span>
      </div>
      <div class="info-pill">
        <span class="pill-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="4" y="5" width="16" height="16" rx="2"></rect><path d="M8 3v4M16 3v4M4 10h16"></path></svg></span>
        <span><span class="pill-label" data-en="Published" data-ar="تاريخ النشر">Published</span><b data-en="June 14, 2026" data-ar="14 يونيو 2026">June 14, 2026</b></span>
      </div>
    </div>

    <!-- Hint card -->
    <div class="chapters-card">
      <div class="chapters-head">
        <div class="chapters-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="7" height="6" rx="1.5"></rect><path d="M13 6h8M13 10h8"></path><rect x="3" y="14" width="7" height="6" rx="1.5"></rect><path d="M13 15h8M13 19h8"></path></svg>
          <span data-en="Chapters" data-ar="فصول الفيديو">Chapters</span>
        </div>
        <span class="chapters-count" id="chaptersCount">3 chapters</span>
      </div>
      <div id="chaptersList">
    <button class="chapter-row current" data-start="0">
      <div class="chapter-thumb">
        <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg>
        <span class="chapter-time-badge">0:00</span>
      </div>
      <div class="chapter-row-body">
        <div class="chapter-row-title">Introduction</div>
        <div class="chapter-row-range">0:00 – 12:34</div>
      </div>
      <span class="chapter-row-play">
        <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg>
      </span>
    </button>
    <button class="chapter-row" data-start="754">
      <div class="chapter-thumb">
        <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg>
        <span class="chapter-time-badge">12:34</span>
      </div>
      <div class="chapter-row-body">
        <div class="chapter-row-title">Project Full Demo</div>
        <div class="chapter-row-range">12:34 – 20:22</div>
      </div>
      <span class="chapter-row-play">
        <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg>
      </span>
    </button>
    <button class="chapter-row" data-start="1222">
      <div class="chapter-thumb">
        <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg>
        <span class="chapter-time-badge">20:22</span>
      </div>
      <div class="chapter-row-body">
        <div class="chapter-row-title">More Demo</div>
        <div class="chapter-row-range">20:22 – 21:40</div>
      </div>
      <span class="chapter-row-play">
        <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="7 4 20 12 7 20 7 4"></polygon></svg>
      </span>
    </button></div>
    </div>

    <!-- Prev/Next -->
    <div class="nav-row">
      <button class="nav-card prev" id="prevBtn" style="visibility: visible;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6" id="prevArrow"></polyline></svg>
        <div class="nav-card-text">
          <div class="nav-card-label" data-en="Previous lesson" data-ar="الدرس السابق">Previous lesson</div>
          <div class="nav-card-title" id="prevTitle">What is Visual Design?</div>
        </div>
      </button>
      <button class="nav-card next" id="nextBtn" style="visibility: visible;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6" id="nextArrow"></polyline></svg>
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
          <textarea id="commentInput" data-en-ph="Ask a question or share a thought..." data-ar-ph="اطرح سؤالاً أو شارك فكرة..." placeholder="Ask a question or share a thought..."></textarea>
          <div class="comment-form-actions">
            <button class="btn btn-primary btn-sm" id="postCommentBtn" data-en="Post" data-ar="نشر">Post</button>
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
      <p class="comment-text">The 60-30-10 tip finally made hierarchy click for me. Using it on my portfolio redesign now.</p>
      <div class="comment-actions">
        <button class="comment-action like-btn " data-id="c1">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"></path></svg>
          <span>14</span>
        </button>
        <button class="comment-action reply-btn" data-id="c1">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg>
          <span data-en="Reply" data-ar="ردّ">Reply</span>
        </button>
      </div>

      <div class="reply-input-row" id="replyInput-c1">
        <div class="avatar" style="background:var(--primary); width:30px;height:30px;font-size:11px;">SA</div>
        <input type="text" data-en-ph="Write a reply..." data-ar-ph="اكتب ردًا..." placeholder="Write a reply..." id="replyText-c1">
        <button class="btn btn-primary btn-sm" data-reply-submit="c1" data-en="Reply" data-ar="ردّ">Reply</button>
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
      <p class="comment-text">Love hearing that — post a screenshot in the community tab if you'd like feedback!</p>
      <div class="comment-actions">
        <button class="comment-action like-btn " data-id="c1r1">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"></path></svg>
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
      <p class="comment-text">Could you cover how hierarchy changes on smaller screens in a future lesson?</p>
      <div class="comment-actions">
        <button class="comment-action like-btn " data-id="c2">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"></path></svg>
          <span>9</span>
        </button>
        <button class="comment-action reply-btn" data-id="c2">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg>
          <span data-en="Reply" data-ar="ردّ">Reply</span>
        </button>
      </div>

      <div class="reply-input-row" id="replyInput-c2">
        <div class="avatar" style="background:var(--primary); width:30px;height:30px;font-size:11px;">SA</div>
        <input type="text" data-en-ph="Write a reply..." data-ar-ph="اكتب ردًا..." placeholder="Write a reply..." id="replyText-c2">
        <button class="btn btn-primary btn-sm" data-reply-submit="c2" data-en="Reply" data-ar="ردّ">Reply</button>
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
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"></path></svg>
          <span>3</span>
        </button>
        <button class="comment-action reply-btn" data-id="c3">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg>
          <span data-en="Reply" data-ar="ردّ">Reply</span>
        </button>
      </div>

      <div class="reply-input-row" id="replyInput-c3">
        <div class="avatar" style="background:var(--primary); width:30px;height:30px;font-size:11px;">SA</div>
        <input type="text" data-en-ph="Write a reply..." data-ar-ph="اكتب ردًا..." placeholder="Write a reply..." id="replyText-c3">
        <button class="btn btn-primary btn-sm" data-reply-submit="c3" data-en="Reply" data-ar="ردّ">Reply</button>
      </div>


    </div>
  </div></div>
    </section>
  </main>
</div>


</x-app-layout>
