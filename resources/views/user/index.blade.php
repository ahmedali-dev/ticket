<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    Users
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Manage user accounts and access
                </p>
            </div>

            <a href="{{route('users.create')}}" type="button"
                class="flex items-center justify-center gap-2 px-4 h-[2.75rem] bg-blue-600 rounded-md text-md text-white hover:bg-blue-700">
                + Add User
            </a>
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
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Company</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                        <th class="px-6 py-3">Edit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-medium">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $user->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $user->company->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->status)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-200 text-gray-600 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                        Disabled
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    @if ($user->status)
                                        <button type="submit"
                                            class="px-3 py-1.5 rounded-md text-xs font-medium text-red-600 border border-red-200 hover:bg-red-50">
                                            Disable
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="px-3 py-1.5 rounded-md text-xs font-medium text-green-600 border border-green-200 hover:bg-green-50">
                                            Enable
                                        </button>
                                    @endif
                                </form>
                            </td>

                            <td class="px-6 py-4">
                                <a href="{{ route('users.edit', $user) }}"
                                    class="px-3 py-1.5 rounded-md text-xs font-medium text-blue-600 border border-blue-200 hover:bg-blue-50">edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                No users found.
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