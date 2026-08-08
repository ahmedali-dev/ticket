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


    <main class="flex-1 items-center  max-w-[1400px] w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col gap-8">

        <div class="md:min-w-[545px] ">
            <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Breadcrumb">
                <a href="index.html" class="hover:text-teal-700">Courses</a>
                <span aria-hidden="true">/</span>
                <span class="text-slate-900 font-semibold">Add New Course</span>
            </nav>

            <section>
                <h1 class="text-2xl sm:text-3xl font-bold my-3">Add New Course</h1>
                <p class="text-slate-500 text-sm mt-2 mb-5">Fill in the details below to publish a new course.</p>
            </section>

            <form novalidate
                  enctype="multipart/form-data"
                  action="{{route('training.store')}}"
                  method="post"
                  class="bg-white border border-slate-200 rounded-xl2 p-5 sm:p-8 max-w-2xl flex flex-col gap-6">
                @csrf
                <div class="flex flex-col gap-2">
                    <label for="new-title" class="font-semibold text-[13.5px] text-slate-700">Course Title</label>
                    <input id="new-title" name="title" type="text" placeholder="e.g. Introduction to Data Science"
                           class="px-3.5 py-3 rounded-xl border border-slate-200 text-sm placeholder:text-slate-400 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition">
                    @error('title')<small class="text-red-600 font-bold text-l">{{$message}}</small> @enderror
                </div>

                <div class="flex flex-col gap-2" i>
                    <label for="new-desc" class="font-semibold text-[13.5px] text-slate-700">Course Published</label>
                    <input type="hidden" name="active" value="false" id="active">
                    <div class="w-full border-gray-400  flex p-2 gap-2">
                        <div id="visible"
                             class="flex-1 border-2 border-blue-600 rounded-lg p-2 text-center cursor-pointer">
                            Visible
                        </div>
                        <div id="hidden"
                             class="flex-1 border-2 border-blue-600 rounded-lg p-2 text-center cursor-pointer">Hidden
                        </div>
                    </div>
                    @error('active')<small class="text-red-600 font-bold text-l">{{$message}}</small> @enderror

                </div>

                <div class="flex flex-col gap-2">
                    <label for="new-thumbnail" class="font-semibold text-[13.5px] text-slate-700">Course
                        Thumbnail</label>
                    <label for="new-thumbnail" tabindex="0"
                           class="border-2 border-dashed border-slate-300 rounded-xl px-4 py-8 text-center flex flex-col items-center gap-2 text-slate-500 cursor-pointer hover:border-violet-500 hover:bg-violet-50 transition-colors focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-violet-500/20">
                        <span class="text-2xl" aria-hidden="true">☁️⬆️</span>
                        <span class="font-semibold text-sm text-slate-700">Drag &amp; drop a thumbnail here</span>
                        <span class="text-xs">or click to browse your files</span>
                        <input type="file" name="image" id="new-thumbnail" accept=".png,.jpg,.jpeg,.webp"
                               class="sr-only">
                    </label>
                    <p class="text-xs text-slate-500 bg-slate-100 rounded-lg p-3">Accepted formats: PNG, JPG, WEBP
                        &nbsp;·&nbsp; Maximum size: 5MB</p>

                    @error('image')
                    <small class="text-red-600 font-bold text-l">{{$message}}
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <span class="font-semibold text-[13.5px] text-slate-700">Image Preview</span>
                    <div
                        id="preview"
                        class="aspect-video rounded-xl bg-gradient-to-br from-teal-100 to-violet-100 border border-slate-200 flex items-center justify-center">
                        <span class="text-slate-500 text-[13px]">Your thumbnail preview will appear here</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-slate-200">
                    <button type="submit"
                            class="rounded-xl bg-teal-700 text-white font-semibold text-sm px-5 py-3 shadow-soft hover:bg-teal-900 transition-colors">
                        Create Course
                    </button>
                    <a href="{{route('training.index')}}"
                       class="rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm px-5 py-3 text-center hover:bg-slate-100 transition-colors">Cancel</a>
                </div>
            </form>

            <footer class="text-center text-slate-500 text-[13px] pt-4 pb-2">&copy; 2026 Learning Platform. All rights
                reserved.
            </footer>
        </div>
    </main>

    <script>


        document.addEventListener('DOMContentLoaded', () => {
            const active = document.querySelector('#active');
            const visible = document.querySelector('#visible');
            const hidden = document.querySelector('#hidden');

            visible.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-200');
            active.value = '1';

            visible.addEventListener('click', () => {
                hidden.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-200');
                visible.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-200');

                active.value = '1';
            });

            hidden.addEventListener('click', () => {
                visible.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-200');
                hidden.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-200');

                active.value = '0';
            });


            const input = document.querySelector('input[type="file"]');
            const preview = document.querySelector('#preview');

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file || !preview) return;

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);

                preview.innerHTML = '';
                preview.appendChild(img);
            });
        });

    </script>

</x-app-layout>
