<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ $company->name }}
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Company details and users
                </p>
            </div>

            <a href="{{ route('company.edit', $company) }}"
               class="flex items-center justify-center gap-2 px-4 h-[2.75rem] bg-blue-600 rounded-md text-md text-white hover:bg-blue-700">
                Edit Company
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4">

        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Company info card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <div class="text-xs uppercase text-gray-400 mb-1">Name</div>
                <div class="font-medium text-gray-800">{{ $company->name }}</div>
            </div>
            <div>
                <div class="text-xs uppercase text-gray-400 mb-1">Phone</div>
                <div class="font-medium text-gray-800">{{ $company->phone }}</div>
            </div>
            <div>
                <div class="text-xs uppercase text-gray-400 mb-1">Status</div>
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
            </div>
        </div>

        <!-- Users table -->
        <h3 class="font-semibold text-lg text-gray-800 mb-3">Users</h3>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-medium">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if ($user->status)
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-gray-400">
                            No users belong to this company yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($users, 'links'))
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
