<x-guest-layout>

    <form
        method="POST"
        action="{{ route('password.store') }}"
    >

        @csrf


        {{-- Password Reset Token --}}

        <input
            type="hidden"
            name="token"
            value="{{ $request->route('token') }}"
        >


        {{-- Email --}}

        <x-input
            label="auth.email"
            id="email"
            class="block mt-1 w-full"
            type="email"
            name="email"
            :value="old('email', $request->email)"
            required
            autofocus
            autocomplete="username"
            :error="$errors->get('email')"
        />


        {{-- Password --}}

        <x-input
            label="auth.password"
            id="password"
            class="block mt-4 w-full"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            :error="$errors->get('password')"
        />


        {{-- Confirm Password --}}

        <x-input
            label="auth.confirm_password"
            id="password_confirmation"
            class="block mt-4 w-full"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            :error="$errors->get('password_confirmation')"
        />


        {{-- Submit --}}

        <div class="flex items-center justify-end mt-4">

            <x-primary-button
                class="w-full !text-center !p-3 flex items-center justify-center"
            >

                {{ __('auth.reset_password_button') }}

            </x-primary-button>

        </div>

    </form>

</x-guest-layout>
