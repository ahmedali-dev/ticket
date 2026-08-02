<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    Add New Training Section
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Fill out the form below to submit a new section
                </p>
            </div>
            <a href="{{ route('training.index') }}"
                class="inline-flex items-center gap-2 self-start rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>


    <div class="shell column justify-center align-center">
        <form action="{{ route('training.store') }}" method="post">
            @csrf
            <div class="w-[400px] mt-4">

                <div class="grid gap-2">
                    <label for="">Title</label>
                    <input type="text" name="title" class="border-2 rounded-md pl-4 p-3 border-blue-400"
                        placeholder="Inter the title">
                    @error('title')
                        <small class="text-md font-bold text-red-600">{{ $message }}</small>
                    @enderror
                </div>

                <button
                    class="mt-6 border-2 border-blue-700 text-white bg-blue-800 rounded-xl w-full p-3 shadow-md shadow-blue-400/25 hover:bg-blue-500 hover:scale-105 hover:text-black transition-all">Submit</button>
            </div>
        </form>
    </div>

</x-app-layout>