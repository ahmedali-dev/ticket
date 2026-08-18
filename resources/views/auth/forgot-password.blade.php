<x-guest-layout>

    <div class="mb-4 text-sm text-gray-600">

        {{ __('auth.forgot_password_message') }}

    </div>


    {{-- Session Status --}}

    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />


    <form
        method="POST"
        action="{{ route('password.email') }}"
    >

        @csrf


        {{-- Email --}}

        <x-input
            label="auth.email"
            id="email"
            class="block mt-1 w-full"
            type="email"
            name="email"
            :value="old('email')"
            required
            autofocus
            autocomplete="username"
            :error="$errors->get('email')"
        />


        {{-- Submit --}}

        <div class="flex items-center justify-end mt-4">

            <x-primary-button
                class="w-full !text-center !p-3 flex items-center justify-center"
            >

                {{ __('auth.email_password_reset_link') }}

            </x-primary-button>

        </div>

    </form>

</x-guest-layout>
