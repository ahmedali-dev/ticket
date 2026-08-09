@props(['training', 'media'])

<x-app-layout>

    <div class="max-w-[500px] mx-auto my-9">
        <form class="w-full p-4" method="POST" action="{{ route('training.update', $training) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h1 class="text-xl">Edit Training</h1>

            @if ($errors->any())
                <div class="mt-3 mb-1 px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col gap-3 my-4">
                <label for="title">Title</label>
                <input class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="title" id="title"
                    placeholder="Enter training title" value="{{ old('title', $training->title) }}" type="text">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="image">Image</label>


                @if(count($training->media))
                    <img class="rounded-lg max-h-48 object-cover" id="current-image" src="{{ Storage::url($media->path) }}"
                        alt="{{ $training->title }}">
                @endif

                <input class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="image" id="image" type="file"
                    accept="image/png, image/jpeg, image/jpg, image/gif, image/svg+xml" onchange="previewImage(event)">
                <img id="image-preview" class="hidden mt-2 rounded-lg max-h-48 object-cover" src="#" alt="Preview">

                @if($training->media)
                    <label class="flex items-center gap-2 text-sm text-red-600">
                        <input type="checkbox" name="remove_image" value="1">
                        Remove current image
                    </label>
                @endif
            </div>

            <div class="flex items-center gap-3 my-4">
                <input type="hidden" name="active" value="0">
                <input class="w-5 h-5 rounded border-2 border-blue-400" type="checkbox" name="active" id="active"
                    value="1" {{ old('active', $training->active) ? 'checked' : '' }}>
                <label for="active">Active</label>
            </div>

            <div class="flex items-center justify-around gap-2">
                <button type="submit"
                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]">
                    Update
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('image-preview');
            const current = document.getElementById('current-image');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                if (current) current.classList.add('hidden');
            } else {
                preview.classList.add('hidden');
                if (current) current.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>