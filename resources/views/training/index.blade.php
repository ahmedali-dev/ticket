@props([
    'trainings' => [],
    'isAdmin' => false
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


    <div
        class="sidebar-backdrop"
        id="sidebarBackdrop"
    ></div>


    {{-- =========================================================
        MAIN
    ========================================================== --}}

    <main
        class="training-library flex-1 max-w-[1400px] w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col gap-8"
    >


        {{-- =====================================================
            PAGE HEADER
        ====================================================== --}}

        <section
            class="training-library-heading flex items-start justify-between gap-6 flex-wrap"
        >

            <div>

                <h1 class="text-2xl sm:text-3xl font-bold">
                    {{ __('training.courses') }}
                    <span class="training-library-count">{{ $trainings->count() }}</span>
                </h1>

                <p class="text-slate-500 text-sm mt-2">
                    {{ __('training.manage_courses') }}
                </p>

            </div>


            {{-- Add Course --}}
            @if ($isAdmin)

                <a
                    href="{{ route('training.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-teal-700 text-white font-semibold text-sm px-5 py-3 shadow-soft hover:bg-teal-900 hover:shadow-lift hover:-translate-y-0.5 transition-all w-full sm:w-auto justify-center"
                >

                    <span aria-hidden="true">
                        +
                    </span>

                    {{ __('training.add_new_course') }}

                </a>

            @endif

        </section>


        {{-- =====================================================
            SEARCH
        ====================================================== --}}

        <form
            class="training-library-search"
            action="{{ route('training.index') }}"
            method="GET"
        >

            <section
                class="flex flex-col sm:flex-row gap-4 sm:items-center"
                aria-label="{{ __('training.search_and_filter_courses') }}"
            >

                {{-- Search Input --}}
                <div class="relative flex-1 min-w-[220px]">

                    <span
                        class="absolute start-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"
                        aria-hidden="true"
                    >
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4 4"/></svg>
                    </span>


                    <label
                        for="course-search"
                        class="sr-only"
                    >
                        {{ __('training.search') }}
                    </label>


                    <input
                        type="search"
                        id="course-search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="{{ __('training.search_courses') }}"
                        class="w-full ps-10 pe-4 py-3 rounded-xl border border-slate-200 bg-white text-sm placeholder:text-slate-400 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition"
                    />

                </div>


                {{-- Search Button --}}
                <div>

                    <button
                        class="w-[max-content] rounded-lg shadow-lg shadow-blue-200 p-2 bg-blue-600"
                        type="submit"
                        aria-label="{{ __('training.search') }}"
                        title="{{ __('training.search') }}"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#ffffff"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >

                            <path d="m21 21-4.34-4.34" />

                            <circle
                                cx="11"
                                cy="11"
                                r="8"
                            />

                        </svg>

                    </button>

                </div>

            </section>

        </form>


        {{-- =====================================================
            COURSE GRID
        ====================================================== --}}

        <section
            aria-label="{{ __('training.course_list') }}"
        >

            <div
                class="training-library-grid"
            >


                @forelse ($trainings as $training)

                    {{-- =================================================
                        COURSE CARD
                    ================================================== --}}

                    <article
                        class="training-library-card"
                    >

                        <a
                            href="{{ route('training.show', $training) }}"
                        >

                            {{-- Course Image --}}
                            <div
                                class="training-library-cover relative aspect-video bg-gradient-to-br from-teal-100 to-violet-100 overflow-hidden"
                            >

                                {{-- Status --}}
                                <span
                                    class="absolute top-3 start-3 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide text-white bg-teal-700/90 {{ $training->active ? '' : '!bg-amber-600/90' }}"
                                >

                                    {{ $training->active
                                        ? __('training.published')
                                        : __('training.hidden')
                                    }}

                                </span>


                                {{-- Training Image --}}
                                @if (isset($training->media[0]->path))

                                    <img
                                        class="w-full h-full object-cover"
                                        src="{{ Storage::url($training->media[0]->path) }}"
                                        alt="{{ $training->title }}"
                                    >

                                @else

                                    <div class="training-library-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="m2 9 10-5 10 5-10 5L2 9ZM6 11v6c3 3 9 3 12 0v-6M22 9v7"/></svg></div>

                                @endif

                            </div>


                            {{-- =================================================
                                COURSE CONTENT
                            ================================================== --}}

                            <div
                                class="p-5 flex flex-col gap-3 flex-1"
                            >

                                <h3
                                    class="text-[17px] font-bold font-display"
                                >
                                    {{ $training->title }}
                                </h3>


                                {{-- Course Meta --}}
                                <div
                                    class="flex items-center gap-4 text-slate-500 text-xs mt-auto pt-2 border-t border-dashed border-slate-200"
                                >

                                    <span class="flex items-center gap-1">

                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="m10 8 6 4-6 4V8Z"/></svg>

                                        {{ $training->module->count() }}

                                        {{ __('training.modules') }}

                                    </span>


                                    <span class="flex items-center gap-1">

                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 11h18"/></svg>

                                        {{ $training->created_at->format('M d, Y') }}

                                    </span>

                                </div>

                                <span class="training-library-open">{{ __('training.open_course') }} <span aria-hidden="true">↗</span></span>

                            </div>

                        </a>


                        {{-- =================================================
                            ADMIN EDIT
                        ================================================== --}}

                        @if (auth()->user()->type == 'admin')

                            <div class="px-5 pb-5">

                                <a
                                    href="{{ route('training.edit', $training) }}"
                                    class="flex items-center justify-center gap-2 w-full rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm py-2.5 hover:bg-slate-100 hover:border-slate-300 transition-colors"
                                >

                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m15 5 4 4M4 20l4-1L20 7a2.8 2.8 0 0 0-4-4L4 15v5Z"/></svg>

                                    {{ __('training.edit') }}

                                </a>

                            </div>

                        @endif

                    </article>


                @empty

                    <div
                        class="training-library-empty"
                    >
                        {{ __('training.no_data_found') }}
                    </div>

                @endforelse

            </div>

        </section>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

    </main>

</x-app-layout>
