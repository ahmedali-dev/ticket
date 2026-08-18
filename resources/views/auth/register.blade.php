<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">

        @csrf


        {{-- =====================================================
            Name
        ====================================================== --}}

        <x-input
            label="auth.name"
            id="name"
            class="block mt-1 w-full"
            type="text"
            name="name"
            :value="old('name')"
            required
            autofocus
            autocomplete="name"
            :error="$errors->get('name')"
        />


        {{-- =====================================================
            Email
        ====================================================== --}}

        <x-input
            label="auth.email"
            id="email"
            class="block mt-2 w-full"
            type="email"
            name="email"
            :value="old('email')"
            required
            autocomplete="username"
            :error="$errors->get('email')"
        />


        {{-- =====================================================
            Password
        ====================================================== --}}

        <x-input
            label="auth.password"
            id="password"
            class="block mt-2 w-full"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            :error="$errors->get('password')"
        />


        {{-- =====================================================
            Confirm Password
        ====================================================== --}}

        <x-input
            label="auth.confirm_password"
            id="password_confirmation"
            class="block mt-2 w-full"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            :error="$errors->get('password_confirmation')"
        />


        {{-- =====================================================
            Actions
        ====================================================== --}}

        <div
            class="flex items-center justify-end mt-2 flex-col gap-4"
        >

            {{-- Register --}}
            <x-primary-button
                class="w-full !text-center !p-3 flex items-center justify-center"
            >

                {{ __('auth.register_button') }}

            </x-primary-button>


            {{-- Login --}}
            <a
                class="underline font-bold text-md text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}"
            >

                {{ __('auth.already_registered') }}

            </a>

        </div>

    </form>

</x-guest-layout>
