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
        class="flex-1 max-w-[1400px] w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col gap-8"
    >


        {{-- =====================================================
            PAGE HEADER
        ====================================================== --}}

        <section
            class="flex items-start justify-between gap-6 flex-wrap"
        >

            <div>

                <h1 class="text-2xl sm:text-3xl font-bold">
                    {{ __('training.courses') }}
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
                        ➕
                    </span>

                    {{ __('training.add_new_course') }}

                </a>

            @endif

        </section>


        {{-- =====================================================
            SEARCH
        ====================================================== --}}

        <form
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
                        🔍
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
                class="grid grid-cols-[repeat(auto-fill,minmax(280px,1fr))] gap-6"
            >


                @forelse ($trainings as $training)

                    {{-- =================================================
                        COURSE CARD
                    ================================================== --}}

                    <article
                        class="bg-white border border-slate-200 rounded-xl2 overflow-hidden flex flex-col hover:shadow-lift hover:-translate-y-1 hover:border-transparent transition-all"
                    >

                        <a
                            href="{{ route('training.show', $training) }}"
                        >

                            {{-- Course Image --}}
                            <div
                                class="relative aspect-video bg-gradient-to-br from-teal-100 to-violet-100 overflow-hidden"
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

                                    <img
                                        class="w-full h-full object-cover"
                                        src="data:image/png;base64,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"
                                        alt="{{ $training->title }}"
                                    >

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

                                        📘

                                        {{ $training->module->count() }}

                                        {{ __('training.modules') }}

                                    </span>


                                    <span class="flex items-center gap-1">

                                        🗓️

                                        {{ $training->created_at->format('y/m/d') }}

                                    </span>

                                </div>

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

                                    ✏️

                                    {{ __('training.edit') }}

                                </a>

                            </div>

                        @endif

                    </article>


                @empty

                    <div
                        class="m-auto my-3 text-xl"
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
