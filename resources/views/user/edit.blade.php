@props(['companies' => [], 'user'])

<x-app-layout>

    <div class="max-w-[500px] mx-auto my-9">
        <form class="w-full p-4" method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            <h1 class="text-xl">Edit User</h1>

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
                <label for="name">Name</label>
                <input class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="name" id="name"
                    placeholder="Enter full name" value="{{ old('name', $user->name) }}" type="text">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="email">Email</label>
                <input class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="email" id="email"
                    placeholder="Enter email" value="{{ old('email', $user->email) }}" type="email">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="phone">Phone</label>
                <input type="text" class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="phone" id="phone"
                    value="{{ old('phone', $user->phone) }}" placeholder="+966 5X XXX XXXX" pattern="[0-9]{9}"
                    maxlength="9" inputmode="numeric">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="password">Password</label>
                <input class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="password" id="password"
                    placeholder="Leave blank to keep current password" type="password">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="status">Status</label>
                <select class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="status" id="status">
                    <option value="active" {{ old('status', $user->status ? 'active' : 'disabled') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="disabled" {{ old('status', $user->status ? 'active' : 'disabled') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="type">Type</label>
                <select class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="type" id="type">
                    <option value="user" {{ old('type', $user->type) == 'user' ? 'selected' : '' }}>user</option>
                    <option value="admin" {{ old('type', $user->type) == 'admin' ? 'selected' : '' }}>admin</option>
                </select>
            </div>

            @if(!empty($companies))
                <div class="flex flex-col gap-3 my-4">
                    <label for="company">Company</label>
                    <select class="rounded border-2 border-blue-400 px-3 py-3 w-full" name="company_id" id="company">
                        <option value="">-- None --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <a href="{{ route('company.index') }}" class="text-blue-500 capitalize">add new company ?</a>

            <div class="flex items-center justify-around gap-2">
                <button type="submit"
                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>