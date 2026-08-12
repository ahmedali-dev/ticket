<nav class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">

    <!-- Main Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center h-16">

            <!-- Logo + Desktop Navigation -->
            <div class="flex items-center">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo
                            class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-100"
                        />
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex items-center gap-2 ms-10">

                    @if (auth()->user()->type === 'admin')

                        <!-- Dashboard -->
                        <a
                            href="{{ route('dashboard') }}"
                            class="nav-link px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard')
                                ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white'
                            }}"
                        >
                            {{ __('dashborad') }}
                        </a>

                        <!-- Users -->
                        <a
                            href="{{ route('users.index') }}"
                            class="nav-link px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('users.*')
                                ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white'
                            }}"
                        >
                            {{ __('users') }}
                        </a>

                        <!-- Company -->
                        <a
                            href="{{ route('company.index') }}"
                            class="nav-link px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('company.*')
                                ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white'
                            }}"
                        >
                            {{ __('company') }}
                        </a>

                    @endif


                    <!-- Ticket -->
                    <a
                        href="{{ route('ticket.index') }}"
                        class="nav-link px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('ticket.*')
                            ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white'
                        }}"
                    >
                        {{ __('ticket') }}
                    </a>


                    <!-- Training -->
                    <a
                        href="{{ route('training.index') }}"
                        class="nav-link px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('training.*')
                            ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white'
                        }}"
                    >
                        {{ __('training') }}
                    </a>

                </div>

            </div>


            <!-- Desktop User Menu -->
            <div class="hidden sm:flex items-center">

                <div class="relative">

                    <!-- User Button -->
                    <button
                        id="user-menu-button"
                        type="button"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg
                        text-sm font-medium text-gray-600
                        hover:bg-gray-100 hover:text-gray-900
                        dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white
                        transition"
                    >

                        <div>
                            {{ Auth::user()->name }}
                        </div>

                        <svg
                            id="user-menu-arrow"
                            class="w-4 h-4 transition-transform duration-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    <!-- User Dropdown -->
                    <div
                        id="user-menu"
                        class="hidden absolute end-0 mt-2 w-48
                        bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700
                        rounded-lg shadow-lg
                        overflow-hidden z-50"
                    >

                        <!-- Profile -->
                        <a
                            href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm
                            text-gray-700 dark:text-gray-300
                            hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            {{ __('Profile') }}
                        </a>


                        <!-- Language -->
                        <div class="border-t border-gray-100 dark:border-gray-700">

                            <div class="px-4 pt-3 pb-1 text-xs text-gray-400">
                                {{ __('Language') }}
                            </div>

                            <a
                                href="{{ route('lang.switch', 'ar') }}"
                                class="flex items-center gap-2 px-4 py-2 text-sm
                                text-gray-700 dark:text-gray-300
                                hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                🇸🇦
                                {{ __('Arabic') }}
                            </a>

                            <a
                                href="{{ route('lang.switch', 'en') }}"
                                class="flex items-center gap-2 px-4 py-2 text-sm
                                text-gray-700 dark:text-gray-300
                                hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                🇬🇧
                                {{ __('English') }}
                            </a>

                        </div>


                        <!-- Logout -->
                        <div class="border-t border-gray-100 dark:border-gray-700">

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="w-full text-start px-4 py-2 text-sm
                                    text-red-600 dark:text-red-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700"
                                >
                                    {{ __('Log Out') }}
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Mobile Menu Button -->
            <button
                id="mobile-menu-button"
                type="button"
                class="sm:hidden inline-flex items-center justify-center
                p-2 rounded-lg
                text-gray-500 hover:text-gray-700
                hover:bg-gray-100
                dark:text-gray-400 dark:hover:text-gray-200
                dark:hover:bg-gray-700
                focus:outline-none transition"
                aria-expanded="false"
            >

                <!-- Hamburger -->
                <svg
                    id="hamburger-icon"
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>

                <!-- Close -->
                <svg
                    id="close-icon"
                    class="hidden w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>

            </button>

        </div>

    </div>


    <!-- Mobile Navigation -->
    <div
        id="mobile-menu"
        class="hidden sm:hidden border-t border-gray-100 dark:border-gray-700"
    >

        <div class="px-4 pt-3 pb-4 space-y-1">

            @if (auth()->user()->type === 'admin')

                <!-- Dashboard -->
                <a
                    href="{{ route('dashboard') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium
                    {{ request()->routeIs('dashboard')
                        ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
                    }}"
                >
                    {{ __('dashborad') }}
                </a>


                <!-- Users -->
                <a
                    href="{{ route('users.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium
                    {{ request()->routeIs('users.*')
                        ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
                    }}"
                >
                    {{ __('users') }}
                </a>


                <!-- Company -->
                <a
                    href="{{ route('company.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium
                    {{ request()->routeIs('company.*')
                        ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
                    }}"
                >
                    {{ __('company') }}
                </a>

            @endif


            <!-- Ticket -->
            <a
                href="{{ route('ticket.index') }}"
                class="block px-4 py-2 rounded-lg text-sm font-medium
                {{ request()->routeIs('ticket.*')
                    ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                    : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
                }}"
            >
                {{ __('ticket') }}
            </a>


            <!-- Training -->
            <a
                href="{{ route('training.index') }}"
                class="block px-4 py-2 rounded-lg text-sm font-medium
                {{ request()->routeIs('training.*')
                    ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400'
                    : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
                }}"
            >
                {{ __('training') }}
            </a>

        </div>


        <!-- Mobile User Information -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4">

            <div class="mb-4">

                <div class="font-medium text-base text-gray-800 dark:text-gray-100">
                    {{ Auth::user()->name }}
                </div>

                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">
                    {{ Auth::user()->email }}
                </div>

            </div>


            <div class="space-y-1">

                <!-- Profile -->
                <a
                    href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 rounded-lg text-sm
                    text-gray-600 hover:bg-gray-100
                    dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    {{ __('Profile') }}
                </a>


                <!-- Arabic -->
                <a
                    href="{{ route('lang.switch', 'ar') }}"
                    class="block px-4 py-2 rounded-lg text-sm
                    text-gray-600 hover:bg-gray-100
                    dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    🇸🇦 {{ __('Arabic') }}
                </a>


                <!-- English -->
                <a
                    href="{{ route('lang.switch', 'en') }}"
                    class="block px-4 py-2 rounded-lg text-sm
                    text-gray-600 hover:bg-gray-100
                    dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    🇬🇧 {{ __('English') }}
                </a>


                <!-- Logout -->
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="w-full text-start px-4 py-2 rounded-lg text-sm
                        text-red-600 dark:text-red-400
                        hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                        {{ __('Log Out') }}
                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>


<!-- Navigation JavaScript -->
<script>

    // ==========================================
    // Mobile Menu
    // ==========================================

    const mobileMenuButton =
        document.getElementById('mobile-menu-button');

    const mobileMenu =
        document.getElementById('mobile-menu');

    const hamburgerIcon =
        document.getElementById('hamburger-icon');

    const closeIcon =
        document.getElementById('close-icon');


    mobileMenuButton.addEventListener('click', () => {

        const isOpen =
            !mobileMenu.classList.contains('hidden');

        mobileMenu.classList.toggle('hidden');

        hamburgerIcon.classList.toggle('hidden');

        closeIcon.classList.toggle('hidden');

        mobileMenuButton.setAttribute(
            'aria-expanded',
            !isOpen
        );

    });


    // ==========================================
    // User Dropdown
    // ==========================================

    const userMenuButton =
        document.getElementById('user-menu-button');

    const userMenu =
        document.getElementById('user-menu');

    const userMenuArrow =
        document.getElementById('user-menu-arrow');


    if (userMenuButton) {

        userMenuButton.addEventListener('click', (event) => {

            event.stopPropagation();

            userMenu.classList.toggle('hidden');

            userMenuArrow.classList.toggle('rotate-180');

        });

    }


    // ==========================================
    // Close Dropdown When Clicking Outside
    // ==========================================

    document.addEventListener('click', (event) => {

        if (
            userMenu &&
            userMenuButton &&
            !userMenu.contains(event.target) &&
            !userMenuButton.contains(event.target)
        ) {

            userMenu.classList.add('hidden');

            userMenuArrow.classList.remove('rotate-180');

        }

    });


    // ==========================================
    // Close Mobile Menu When Clicking Link
    // ==========================================

    document
        .querySelectorAll('#mobile-menu a')
        .forEach(link => {

            link.addEventListener('click', () => {

                mobileMenu.classList.add('hidden');

                hamburgerIcon.classList.remove('hidden');

                closeIcon.classList.add('hidden');

                mobileMenuButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            });

        });


    // ==========================================
    // Close Mobile Menu On Resize
    // ==========================================

    window.addEventListener('resize', () => {

        if (window.innerWidth >= 640) {

            mobileMenu.classList.add('hidden');

            hamburgerIcon.classList.remove('hidden');

            closeIcon.classList.add('hidden');

            mobileMenuButton.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    });

</script>
