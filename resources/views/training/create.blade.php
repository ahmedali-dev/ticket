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
        MAIN
    ========================================================== --}}

    <main
        class="flex-1 items-center max-w-[1400px] w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col gap-8"
    >

        <div class="md:min-w-[545px]">


            {{-- =================================================
                BREADCRUMB
            ================================================== --}}

            <nav
                class="flex items-center gap-2 text-sm text-slate-500"
                aria-label="{{ __('training.course_list') }}"
            >

                <a
                    href="{{ route('training.index') }}"
                    class="hover:text-teal-700"
                >
                    {{ __('training.courses') }}
                </a>

                <span aria-hidden="true">
                    /
                </span>

                <span class="text-slate-900 font-semibold">
                    {{ __('training.add_new_course') }}
                </span>

            </nav>


            {{-- =================================================
                PAGE HEADER
            ================================================== --}}

            <section>

                <h1 class="text-2xl sm:text-3xl font-bold my-3">
                    {{ __('training.add_new_course') }}
                </h1>

                <p class="text-slate-500 text-sm mt-2 mb-5">
                    {{ __('training.create_course') }}
                </p>

            </section>


            {{-- =================================================
                FORM
            ================================================== --}}

            <form
                novalidate
                enctype="multipart/form-data"
                action="{{ route('training.store') }}"
                method="POST"
                class="bg-white border border-slate-200 rounded-xl2 p-5 sm:p-8 max-w-2xl flex flex-col gap-6"
            >

                @csrf


                {{-- =================================================
                    COURSE TITLE
                ================================================== --}}

                <div class="flex flex-col gap-2">

                    <label
                        for="new-title"
                        class="font-semibold text-[13.5px] text-slate-700"
                    >
                        {{ __('training.course_title') }}
                    </label>


                    <input
                        id="new-title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
                        placeholder="{{ __('training.course_title_placeholder') }}"
                        class="px-3.5 py-3 rounded-xl border border-slate-200 text-sm placeholder:text-slate-400 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition"
                    >


                    @error('title')

                    <small class="text-red-600 font-bold text-l">
                        {{ $message }}
                    </small>

                    @enderror

                </div>


                {{-- =================================================
                    PUBLISHED
                ================================================== --}}

                <div class="flex flex-col gap-2">

                    <label
                        class="font-semibold text-[13.5px] text-slate-700"
                    >
                        {{ __('training.course_published') }}
                    </label>


                    <input
                        type="hidden"
                        name="active"
                        value="{{ old('active', '1') }}"
                        id="active"
                    >


                    <div
                        class="w-full border-gray-400 flex p-2 gap-2"
                    >

                        {{-- Visible --}}
                        <div
                            id="visible"
                            role="button"
                            tabindex="0"
                            class="flex-1 border-2 border-blue-600 rounded-lg p-2 text-center cursor-pointer"
                        >
                            {{ __('training.visible') }}
                        </div>


                        {{-- Hidden --}}
                        <div
                            id="hidden"
                            role="button"
                            tabindex="0"
                            class="flex-1 border-2 border-blue-600 rounded-lg p-2 text-center cursor-pointer"
                        >
                            {{ __('training.hidden') }}
                        </div>

                    </div>


                    @error('active')

                    <small class="text-red-600 font-bold text-l">
                        {{ $message }}
                    </small>

                    @enderror

                </div>


                {{-- =================================================
                    THUMBNAIL
                ================================================== --}}

                <div class="flex flex-col gap-2">

                    <label
                        for="new-thumbnail"
                        class="font-semibold text-[13.5px] text-slate-700"
                    >
                        {{ __('training.course_thumbnail') }}
                    </label>


                    <label
                        for="new-thumbnail"
                        tabindex="0"
                        class="border-2 border-dashed border-slate-300 rounded-xl px-4 py-8 text-center flex flex-col items-center gap-2 text-slate-500 cursor-pointer hover:border-violet-500 hover:bg-violet-50 transition-colors focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-violet-500/20"
                    >

                        <span
                            class="text-2xl"
                            aria-hidden="true"
                        >
                            ☁️⬆️
                        </span>


                        <span class="font-semibold text-sm text-slate-700">

                            {{ __('training.drag_drop_thumbnail') }}

                        </span>


                        <span class="text-xs">

                            {{ __('training.or_click_to_browse') }}

                        </span>


                        <input
                            type="file"
                            name="image"
                            id="new-thumbnail"
                            accept=".png,.jpg,.jpeg,.webp"
                            class="sr-only"
                        >

                    </label>


                    <p
                        class="text-xs text-slate-500 bg-slate-100 rounded-lg p-3"
                    >

                        {{ __('training.accepted_formats') }}:

                        PNG, JPG, WEBP

                        &nbsp;·&nbsp;

                        {{ __('training.maximum_size') }}:

                        5MB

                    </p>


                    @error('image')

                    <small class="text-red-600 font-bold text-l">

                        {{ $message }}

                    </small>

                    @enderror

                </div>


                {{-- =================================================
                    IMAGE PREVIEW
                ================================================== --}}

                <div class="flex flex-col gap-2">

                    <span
                        class="font-semibold text-[13.5px] text-slate-700"
                    >
                        {{ __('training.image_preview') }}
                    </span>


                    <div
                        id="preview"
                        class="aspect-video rounded-xl bg-gradient-to-br from-teal-100 to-violet-100 border border-slate-200 flex items-center justify-center overflow-hidden"
                    >

                        <span class="text-slate-500 text-[13px]">

                            {{ __('training.thumbnail_preview') }}

                        </span>

                    </div>

                </div>


                {{-- =================================================
                    FORM ACTIONS
                ================================================== --}}

                <div
                    class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-slate-200"
                >

                    {{-- Create --}}
                    <button
                        type="submit"
                        class="rounded-xl bg-teal-700 text-white font-semibold text-sm px-5 py-3 shadow-soft hover:bg-teal-900 transition-colors"
                    >
                        {{ __('training.create_course') }}
                    </button>


                    {{-- Cancel --}}
                    <a
                        href="{{ route('training.index') }}"
                        class="rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm px-5 py-3 text-center hover:bg-slate-100 transition-colors"
                    >
                        {{ __('training.cancel') }}
                    </a>

                </div>

            </form>


            {{-- =================================================
                FOOTER
            ================================================== --}}

            <footer
                class="text-center text-slate-500 text-[13px] pt-4 pb-2"
            >
                {{ __('training.copyright') }}
            </footer>

        </div>

    </main>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script>

        document.addEventListener('DOMContentLoaded', () => {

            const active =
                document.querySelector('#active');

            const visible =
                document.querySelector('#visible');

            const hidden =
                document.querySelector('#hidden');

            const input =
                document.querySelector('#new-thumbnail');

            const preview =
                document.querySelector('#preview');


            /*
            |--------------------------------------------------------------------------
            | Translated Text
            |--------------------------------------------------------------------------
            */

            const thumbnailPreviewText =
                @json(__('training.thumbnail_preview'));


            /*
            |--------------------------------------------------------------------------
            | Active Course State
            |--------------------------------------------------------------------------
            */

            function setActive(value) {

                if (value === '1') {

                    hidden.classList.remove(
                        'bg-blue-600',
                        'text-white',
                        'shadow-md',
                        'shadow-blue-200'
                    );

                    visible.classList.add(
                        'bg-blue-600',
                        'text-white',
                        'shadow-md',
                        'shadow-blue-200'
                    );

                    active.value = '1';

                } else {

                    visible.classList.remove(
                        'bg-blue-600',
                        'text-white',
                        'shadow-md',
                        'shadow-blue-200'
                    );

                    hidden.classList.add(
                        'bg-blue-600',
                        'text-white',
                        'shadow-md',
                        'shadow-blue-200'
                    );

                    active.value = '0';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Initial State
            |--------------------------------------------------------------------------
            */

            setActive(
                active.value === '0'
                    ? '0'
                    : '1'
            );


            /*
            |--------------------------------------------------------------------------
            | Visible
            |--------------------------------------------------------------------------
            */

            visible.addEventListener(
                'click',
                () => {

                    setActive('1');

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Hidden
            |--------------------------------------------------------------------------
            */

            hidden.addEventListener(
                'click',
                () => {

                    setActive('0');

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Keyboard Support
            |--------------------------------------------------------------------------
            */

            visible.addEventListener(
                'keydown',
                event => {

                    if (
                        event.key === 'Enter' ||
                        event.key === ' '
                    ) {

                        event.preventDefault();

                        setActive('1');

                    }

                }
            );


            hidden.addEventListener(
                'keydown',
                event => {

                    if (
                        event.key === 'Enter' ||
                        event.key === ' '
                    ) {

                        event.preventDefault();

                        setActive('0');

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Image Preview
            |--------------------------------------------------------------------------
            */

            input.addEventListener(
                'change',
                event => {

                    const file =
                        event.target.files[0];


                    if (!file || !preview) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Validate Image
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !file.type.startsWith('image/')
                    ) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create Preview
                    |--------------------------------------------------------------------------
                    */

                    const img =
                        document.createElement('img');


                    img.src =
                        URL.createObjectURL(file);

                    img.className =
                        'w-full h-full object-cover';


                    img.alt =
                    @json(__('training.course_thumbnail'));


                    preview.innerHTML =
                        '';

                    preview.appendChild(img);

                }

            );

        });

    </script>

</x-app-layout>
