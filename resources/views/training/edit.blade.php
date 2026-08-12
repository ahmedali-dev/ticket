@props([
    'training',
    'media'
])

<x-app-layout>

    <div class="max-w-[500px] mx-auto my-9">

        <form
            class="w-full p-4"
            method="POST"
            action="{{ route('training.update', $training) }}"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')


            {{-- =====================================================
                TITLE
            ====================================================== --}}

            <h1 class="text-xl font-bold">
                {{ __('training.edit_training') }}
            </h1>


            {{-- =====================================================
                VALIDATION ERRORS
            ====================================================== --}}

            @if ($errors->any())

                <div
                    class="mt-3 mb-1 px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm"
                >

                    <ul class="list-disc list-inside">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =====================================================
                TRAINING TITLE
            ====================================================== --}}

            <div class="flex flex-col gap-3 my-4">

                <label
                    for="title"
                    class="font-semibold"
                >
                    {{ __('training.title') }}
                </label>


                <input
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="title"
                    id="title"
                    placeholder="{{ __('training.enter_training_title') }}"
                    value="{{ old('title', $training->title) }}"
                    type="text"
                >

            </div>


            {{-- =====================================================
                IMAGE
            ====================================================== --}}

            <div class="flex flex-col gap-3 my-4">

                <label
                    for="image"
                    class="font-semibold"
                >
                    {{ __('training.image') }}
                </label>


                {{-- Current Image --}}
                @if ($training->media->count())

                    <img
                        class="rounded-lg max-h-48 object-cover"
                        id="current-image"
                        src="{{ Storage::url($media->path) }}"
                        alt="{{ $training->title }}"
                    >

                @endif


                {{-- New Image --}}
                <input
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="image"
                    id="image"
                    type="file"
                    accept="image/png,image/jpeg,image/jpg,image/gif,image/svg+xml"
                    onchange="previewImage(event)"
                >


                {{-- Preview --}}
                <img
                    id="image-preview"
                    class="hidden mt-2 rounded-lg max-h-48 object-cover"
                    src="#"
                    alt="{{ __('training.preview') }}"
                >


                {{-- Remove Current Image --}}
                @if ($training->media->count())

                    <label
                        class="flex items-center gap-2 text-sm text-red-600 cursor-pointer"
                    >

                        <input
                            type="checkbox"
                            name="remove_image"
                            value="1"
                            {{ old('remove_image') ? 'checked' : '' }}
                        >

                        {{ __('training.remove_current_image') }}

                    </label>

                @endif

            </div>


            {{-- =====================================================
                ACTIVE
            ====================================================== --}}

            <div class="flex items-center gap-3 my-4">

                {{-- False value when unchecked --}}
                <input
                    type="hidden"
                    name="active"
                    value="0"
                >


                <input
                    class="w-5 h-5 rounded border-2 border-blue-400"
                    type="checkbox"
                    name="active"
                    id="active"
                    value="1"
                    {{ old('active', $training->active) ? 'checked' : '' }}
                >


                <label
                    for="active"
                    class="font-semibold cursor-pointer"
                >
                    {{ __('training.active') }}
                </label>

            </div>


            {{-- =====================================================
                UPDATE BUTTON
            ====================================================== --}}

            <div class="flex items-center justify-around gap-2">

                <button
                    type="submit"
                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 hover:bg-blue-700 rounded-md text-md text-white h-[3rem] transition-colors"
                >
                    {{ __('training.update') }}
                </button>

            </div>

        </form>

    </div>


    {{-- =========================================================
        IMAGE PREVIEW JAVASCRIPT
    ========================================================== --}}

    <script>

        function previewImage(event) {

            const preview =
                document.getElementById(
                    'image-preview'
                );

            const current =
                document.getElementById(
                    'current-image'
                );

            const file =
                event.target.files[0];


            /*
            |--------------------------------------------------------------------------
            | New Image Selected
            |--------------------------------------------------------------------------
            */

            if (file) {

                preview.src =
                    URL.createObjectURL(file);

                preview.classList.remove(
                    'hidden'
                );


                if (current) {

                    current.classList.add(
                        'hidden'
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | No Image Selected
            |--------------------------------------------------------------------------
            */

            else {

                preview.classList.add(
                    'hidden'
                );


                if (current) {

                    current.classList.remove(
                        'hidden'
                    );

                }

            }

        }

    </script>

</x-app-layout>
