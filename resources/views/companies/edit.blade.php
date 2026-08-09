<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Edit Company
            </h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ $company->name }}
            </p>
        </div>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto px-4">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('company.update', $company) }}">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-col gap-3 my-4">
                    <label for="name">Company Name</label>
                    <input
                        class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                        name="name" id="name" value="{{ old('name', $company->name) }}" type="text">
                </div>

                <div class="flex flex-col gap-3 my-4">
                    <label for="phone">Phone</label>
                    <input
                        class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                        name="phone" id="phone" value="{{ old('phone', $company->phone) }}" type="number">
                </div>

                <div class="flex flex-col gap-3 my-4">
                    <label for="status">Status</label>
                    <select
                        class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                        name="status" id="status">
                        <option value="1" @selected(old('status', $company->status) == 1)>Active</option>
                        <option value="0" @selected(old('status', $company->status) == 0)>Disabled</option>
                    </select>
                </div>

                <div class="flex items-center justify-around gap-2">
                    <a href="{{ route('company.index') }}"
                       class="flex justify-center items-center w-full mx-auto my-4 px-4 rounded-md text-md h-[3rem] bg-black text-white">
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="flex justify-center items-center w-full mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-app-layout>
