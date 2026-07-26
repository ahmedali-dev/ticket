{{-- Create Ticket — dedicated page with drag-and-drop image upload --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    Create Ticket
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Fill out the form below to submit a support ticket.
                </p>
            </div>
            <a
                href="{{ route('ticket.index') }}"
                class="inline-flex items-center gap-2 self-start rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div
        class="py-8"
        x-data="createTicketForm()"
        x-init="
            title = @js(old('title', ''));
            description = @js(old('description', ''));
            $nextTick(() => autoResize());
        "
        x-cloak
    >
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form
                    method="POST"
                    action="{{ route('ticket.store') }}"
                    enctype="multipart/form-data"
                    class="space-y-6 p-6 sm:p-8"
                    @submit="submit($event)"
                    novalidate
                >
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Ticket Title <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            x-model="title"
                            required
                            maxlength="255"
                            placeholder="Enter ticket title"
                            aria-required="true"
                            :aria-invalid="!!errors.title"
                            class="mt-1.5 block w-full rounded-xl border-gray-300 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-500 sm:text-sm"
                            @blur="errors.title = title.trim() ? '' : 'Please enter a ticket title.'"
                        />
                        <p x-show="errors.title" x-cloak class="mt-1.5 text-sm text-red-600 dark:text-red-400" x-text="errors.title" role="alert"></p>
                        @error('title')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            x-ref="description"
                            x-model="description"
                            @input="autoResize(); errors.description = description.trim() ? '' : errors.description"
                            @blur="errors.description = description.trim() ? '' : 'Please describe your issue.'"
                            required
                            rows="4"
                            maxlength="5000"
                            placeholder="Describe your issue..."
                            aria-required="true"
                            class="mt-1.5 block w-full resize-none overflow-hidden rounded-xl border-gray-300 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-500 sm:text-sm"
                        ></textarea>
                        <p x-show="errors.description" x-cloak class="mt-1.5 text-sm text-red-600 dark:text-red-400" x-text="errors.description" role="alert"></p>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image upload (optional) — status is never shown here --}}
                    <div>
                        <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Image <span class="font-normal text-gray-400">(optional)</span>
                        </span>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">JPG, JPEG, PNG, or WEBP · Max 5 MB</p>

                        <input
                            type="file"
                            name="image"
                            x-ref="fileInput"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="sr-only"
                            @change="onBrowse($event)"
                        />

                        {{-- Dropzone --}}
                        <div
                            x-show="!previewUrl"
                            role="button"
                            tabindex="0"
                            @click="$refs.fileInput.click()"
                            @keydown.enter.prevent="$refs.fileInput.click()"
                            @keydown.space.prevent="$refs.fileInput.click()"
                            @dragenter.prevent="dragOver = true"
                            @dragover.prevent="dragOver = true"
                            @dragleave.prevent="dragOver = false"
                            @drop.prevent="onDrop($event)"
                            :class="dragOver
                                ? 'border-indigo-500 bg-indigo-50 dark:border-indigo-400 dark:bg-indigo-950/40'
                                : 'border-gray-300 bg-gray-50 hover:border-indigo-400 hover:bg-indigo-50/50 dark:border-gray-600 dark:bg-gray-900/40 dark:hover:border-indigo-500 dark:hover:bg-indigo-950/30'"
                            class="mt-2 flex min-h-[11rem] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed px-4 py-8 text-center transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                        >
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-600">
                                <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Drag &amp; Drop an image here</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">or</p>
                            <p class="mt-1 text-sm font-semibold text-indigo-600 dark:text-indigo-400">Click to browse</p>
                        </div>

                        {{-- Preview --}}
                        <div
                            x-show="previewUrl"
                            x-cloak
                            class="mt-2 overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-900/50"
                        >
                            <div class="relative aspect-video bg-gray-100 dark:bg-gray-900">
                                <img :src="previewUrl" :alt="fileName" class="h-full w-full object-contain" />
                            </div>
                            <div class="flex items-center justify-between gap-3 px-4 py-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-100" x-text="fileName"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="fileSize"></p>
                                </div>
                                <button
                                    type="button"
                                    @click="clearFile()"
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Remove
                                </button>
                            </div>

                            {{-- Upload progress (async submit with image) --}}
                            <div x-show="showProgress" x-cloak class="border-t border-gray-200 px-4 py-3 dark:border-gray-700">
                                <div class="mb-1 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>Uploading…</span>
                                    <span x-text="uploadProgress + '%'"></span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div
                                        class="h-full rounded-full bg-indigo-600 transition-all duration-150"
                                        :style="`width: ${uploadProgress}%`"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <p x-show="errors.image" x-cloak class="mt-1.5 text-sm text-red-600 dark:text-red-400" x-text="errors.image" role="alert"></p>
                        @error('image')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('ticket.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            Cancel
                        </a>
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-indigo-500 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-offset-gray-800"
                        >
                            <svg
                                x-show="submitting"
                                x-cloak
                                class="h-4 w-4 animate-spin"
                                fill="none"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="submitting ? 'Submitting…' : 'Submit Ticket'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
