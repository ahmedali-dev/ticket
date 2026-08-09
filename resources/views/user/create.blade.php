@props(['companies' => []])

<x-app-layout>

    <div class="max-w-[500px] mx-auto my-9">
        <form class="w-full p-4" method="POST" action="{{ route('users.store') }}">
            @csrf
            <h1 class="text-xl">Add User</h1>

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
                <input
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="name" id="name" placeholder="Enter full name" value="{{ old('name') }}" type="text">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="email">Email</label>
                <input
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="email" id="email" placeholder="Enter email" value="{{ old('email') }}" type="email">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="phone">Phone</label>
                <input
                    type="number"
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="phone" id="phone" value="{{ old('phone') }}" placeholder="+966 5X XXX XXXX">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="password">Password</label>
                <input
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="password" id="password" placeholder="Enter password" type="password">
            </div>

            <div class="flex flex-col gap-3 my-4">
                <label for="status">Status</label>
                <select
                    class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                    name="status" id="status">
                    <option value="active" selected>Active</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>

            @if(!empty($companies))
                <div class="flex flex-col gap-3 my-4">
                    <label for="company">Company</label>
                    <select
                        class="rounded border-2 border-blue-400 px-3 py-3 w-full"
                        name="company_id" id="company">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex items-center justify-around gap-2">
                <button
                    type="submit"
                    class="flex justify-center items-center w-[calc(100%-20px)] mx-auto my-4 px-4 bg-blue-600 rounded-md text-md text-white h-[3rem]">
                    Submit
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
