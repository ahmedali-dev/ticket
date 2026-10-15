@php
    $navItems = [
        ['route' => 'dashboard', 'label' => __('dashborad'), 'pattern' => 'dashboard', 'admin' => true],
        ['route' => 'users.index', 'label' => __('users'), 'pattern' => 'users.*', 'admin' => true],
        ['route' => 'company.index', 'label' => __('company'), 'pattern' => 'company.*', 'admin' => true],
        ['route' => 'ticket.index', 'label' => __('ticket'), 'pattern' => 'ticket.*', 'admin' => false],
        ['route' => 'training.index', 'label' => __('training'), 'pattern' => 'training.*', 'admin' => false],
        ['route' => 'whatsapp.index', 'label' => __('whatsapp.connection'), 'pattern' => 'whatsapp.*', 'admin' => true],
    ];

    $visibleNavItems = collect($navItems)->filter(
        fn (array $item) => ! $item['admin'] || auth()->user()->type === 'admin'
    );
@endphp

<!-- Mobile top bar -->
<header class="fixed inset-x-0 top-0 z-40 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 dark:border-gray-700 dark:bg-gray-800 lg:hidden">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-100" />
        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <button id="sidebar-toggle" type="button" class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200" aria-controls="app-sidebar" aria-expanded="false" aria-label="{{ __('Open navigation') }}">
        <svg id="sidebar-open-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg id="sidebar-close-icon" class="hidden h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</header>

<!-- Sidebar backdrop for mobile -->
<div id="sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-gray-900/50 lg:hidden" aria-hidden="true"></div>

<!-- Application sidebar -->
<aside id="app-sidebar" class="fixed inset-y-0 start-0 z-50 flex w-72 {{ app()->getLocale() === 'ar' ? 'translate-x-full' : '-translate-x-full' }} flex-col border-e border-gray-200 bg-white transition-transform duration-200 dark:border-gray-700 dark:bg-gray-800 lg:translate-x-0" aria-label="{{ __('Main navigation') }}">
    <div class="flex h-16 shrink-0 items-center border-b border-gray-200 px-6 dark:border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-100" />
            <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ config('app.name', 'Laravel') }}</span>
        </a>
    </div>

    <button id="sidebar-collapse-toggle" type="button" class="sidebar-collapse-toggle" aria-controls="app-sidebar" aria-expanded="true"
        data-collapse-label="{{ app()->getLocale() === 'ar' ? 'تصغير القائمة الجانبية' : 'Collapse sidebar' }}"
        data-expand-label="{{ app()->getLocale() === 'ar' ? 'توسيع القائمة الجانبية' : 'Expand sidebar' }}"
        aria-label="{{ app()->getLocale() === 'ar' ? 'تصغير القائمة الجانبية' : 'Collapse sidebar' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 4v16m7-12-4 4 4 4"/></svg>
    </button>

    <nav class="flex-1 space-y-1 overflow-y-auto p-4">
        <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            {{ __('Navigation') }}
        </p>

        @foreach ($visibleNavItems as $item)
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($item['pattern']) ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    @switch($item['route'])
                        @case('dashboard')
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                            @break
                        @case('users.index')
                            <circle cx="9" cy="8" r="3"/><path d="M3 21v-2a6 6 0 0 1 12 0v2M16 5a3 3 0 0 1 0 6M21 21v-2a6 6 0 0 0-4-5.65"/>
                            @break
                        @case('company.index')
                            <rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 7h1m4 0h1M9 11h1m4 0h1M10 21v-6h4v6"/>
                            @break
                        @case('ticket.index')
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 0 0-4V7Z"/><path d="M15 5v2m0 4v2m0 4v2"/>
                            @break
                        @case('training.index')
                            <path d="m2 9 10-5 10 5-10 5L2 9ZM6 11v6c3 3 9 3 12 0v-6M22 9v7"/>
                            @break
                        @case('whatsapp.index')
                            <path d="M21 11.5a9 9 0 0 1-13.4 7.9L3 21l1.6-4.6A9 9 0 1 1 21 11.5Z"/><path d="M8 8c0 4 4 8 8 8l1-2-3-1-1 1-3-3 1-1-1-3-2 1Z"/>
                            @break
                    @endswitch
                </svg>
                <span class="sidebar-link-label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="shrink-0 border-t border-gray-200 p-4 dark:border-gray-700">
        <div class="mb-3 flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                {{ str(Auth::user()->name)->substr(0, 1)->upper() }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-medium text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</p>
                <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="space-y-1">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg>
                <span class="sidebar-link-label">{{ __('Profile') }}</span>
            </a>

            <div class="flex items-center gap-2 px-3 py-2 text-xs text-gray-500 dark:text-gray-400">
                <span>{{ __('Language') }}:</span>
                <a href="{{ route('lang.switch', 'ar') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">🇸🇦 {{ __('Arabic') }}</a>
                <span aria-hidden="true">·</span>
                <a href="{{ route('lang.switch', 'en') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">🇬🇧 {{ __('English') }}</a>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full rounded-lg px-3 py-2 text-start text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                    <svg class="h-5 w-5 shrink-0 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    <span class="sidebar-link-label">{{ __('Log Out') }}</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
    (() => {
        const toggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('app-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const openIcon = document.getElementById('sidebar-open-icon');
        const closeIcon = document.getElementById('sidebar-close-icon');

        if (!toggle || !sidebar || !backdrop) return;

        const isRtl = document.documentElement.lang.startsWith('ar');

        const collapseToggle = document.getElementById('sidebar-collapse-toggle');
        const hoverQuery = window.matchMedia('(min-width: 1024px) and (hover: hover) and (pointer: fine)');
        let pointerInside = false;
        const updatePeek = () => {
            const peek = document.documentElement.classList.contains('sidebar-collapsed') && hoverQuery.matches &&
                (pointerInside || sidebar.contains(document.activeElement));
            document.documentElement.classList.toggle('sidebar-peeking', peek);
        };
        const setCollapsed = collapsed => {
            document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
            document.documentElement.classList.remove('sidebar-peeking');
            collapseToggle.setAttribute('aria-expanded', String(!collapsed));
            const label = collapsed ? collapseToggle.dataset.expandLabel : collapseToggle.dataset.collapseLabel;
            collapseToggle.setAttribute('aria-label', label);
            collapseToggle.title = label;
        };
        try { setCollapsed(localStorage.getItem('ticket.sidebarCollapsed') === 'true'); } catch { setCollapsed(false); }
        collapseToggle.addEventListener('click', () => {
            const collapsed = !document.documentElement.classList.contains('sidebar-collapsed');
            setCollapsed(collapsed);
            try { localStorage.setItem('ticket.sidebarCollapsed', String(collapsed)); } catch {}
        });
        sidebar.addEventListener('mouseenter', () => { pointerInside = true; updatePeek(); });
        sidebar.addEventListener('mouseleave', () => { pointerInside = false; updatePeek(); });
        sidebar.addEventListener('focusin', updatePeek);
        sidebar.addEventListener('focusout', () => requestAnimationFrame(updatePeek));
        hoverQuery.addEventListener('change', updatePeek);
        sidebar.querySelectorAll('a, button[type="submit"]').forEach(link => {
            if (!link.title) link.title = link.textContent.trim();
        });

        const setOpen = (open) => {
            sidebar.classList.toggle('-translate-x-full', !open && !isRtl);
            sidebar.classList.toggle('translate-x-full', !open && isRtl);
            sidebar.classList.toggle('translate-x-0', open);
            backdrop.classList.toggle('hidden', !open);
            openIcon.classList.toggle('hidden', open);
            closeIcon.classList.toggle('hidden', !open);
            toggle.setAttribute('aria-expanded', String(open));
            document.body.classList.toggle('overflow-hidden', open);
        };

        toggle.addEventListener('click', () => {
            setOpen(toggle.getAttribute('aria-expanded') !== 'true');
        });

        backdrop.addEventListener('click', () => setOpen(false));

        sidebar.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => setOpen(false));
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) setOpen(false);
        });
    })();
</script>
