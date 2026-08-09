<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    company
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Manage company and their users
                </p>
            </div>

            <button
                command="show-modal" commandfor="add-company-dialog"
                type="button"
                class="flex items-center justify-center gap-2 px-4 h-[2.75rem] bg-blue-600 rounded-md text-md text-white hover:bg-blue-700">
                + Add Company
            </button>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4">

        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Users</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($companies as $company)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            <a href="{{ route('company.show', $company) }}" class="hover:text-blue-600">
                                {{ $company->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $company->phone }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $company->users_count }}</td>
                        <td class="px-6 py-4">
                            @if ($company->status)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                        Active
                                    </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-200 text-gray-600 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                        Disabled
                                    </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('company.show', $company) }}"
                               class="px-3 py-1.5 rounded-md text-xs font-medium text-blue-600 border border-blue-200 hover:bg-blue-50">
                                View
                            </a>
                            <a href="{{ route('company.edit', $company) }}"
                               class="px-3 py-1.5 rounded-md text-xs font-medium text-gray-600 border border-gray-200 hover:bg-gray-50">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                            No company found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($companies, 'links'))
            <div class="mt-4">
                {{ $companies->links() }}
            </div>
        @endif
    </div>

    <!-- Add Company Dialog -->
    <dialog id="add-company-dialog"
            class="hidden open:flex m-auto border-0 rounded-xl p-4 open:fixed open:inset-0 w-[500px] shadow-md shadow-blue-300 border-2 border-gray-300 flex-col items-center justify-center">
        <form class="w-full p-4" method="POST" action="{{ route('company.store') }}">
            @csrf
            <h1 class="text-xl">Add Company</h1>

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
                <label for="name">Company Name</label>
                <input
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="name" id="name" placeholder="Enter company name" value="{{ old('name') }}" type="text">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="phone">Phone</label>
                <input
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="phone" id="phone" placeholder="Enter phone number" value="{{ old('phone') }}" type="number">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="status">Status</label>
                <select
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="status" id="status">
                    <option value="1" selected>Active</option>
                    <option value="0">Disabled</option>
                </select>
            </div>

            <div class="flex items-center justify-around gap-2">
                <button
                    commandfor="add-company-dialog" command="close"
                    type="button"
                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 rounded-md text-md h-[3rem] bg-black text-white">
                    Close
                </button>

                <button
                    type="submit"
                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]">
                    Submit
                </button>
            </div>
        </form>
    </dialog>

    @if ($errors->any() && old('name') !== null)
        <script>
            document.getElementById('add-company-dialog').showModal();
        </script>
    @endif

</x-app-layout>
