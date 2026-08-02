@props(['trainings' => []])
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


    <div class="shell justify-center flex-wrap gap-4 !my-4">

        @foreach ($trainings as $training)
            <div class="p-3 bg-slate-50 shadow-md shadow-yellow-50/20 border-[1.5px] border-gray-300 rounded-md w-[350px]">
                <h1 class=" font-bold text-xl">{{ $training->title }}</h1>
                <small>{{ $training->created_at->format('Y/M/d') }}</small>
                <p class="py-1 px-3 border-[1px] w-[max-content] cursor-pointer rounded-xl mt-3 border-blue-200">
                    {{ $training->module->count() }} Chapter
                </p>
                <a href="{{ route('training.show', $training) }}"
                    class="flex items-center text-white cursor-pointer justify-center py-3 px-7 mt-5 bg-blue-600 rounded shadow-md">
                    View
                </a>
            </div>
        @endforeach

    </div>

    <div class="shell justify-center flex-wrap gap-4 !my-4">

        @if (auth()->user()->type === 'admin')
            <a class="flex justify-center items-center w-[340px] bg-blue-600 rounded-md text-3xl text-white h-[4rem]"
                href="{{ route('training.create') }}">+</a>
        @endif
    </div>
</x-app-layout>