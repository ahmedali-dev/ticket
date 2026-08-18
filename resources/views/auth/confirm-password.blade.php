<x-guest-layout>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('auth.confirm_password_message') }}
    </div>


    <form
        method="POST"
        action="{{ route('password.confirm') }}"
    >

        @csrf


        {{-- Password --}}

        <x-input
            label="auth.password"
            id="password"
            class="block mt-1 w-full"
            type="password"
            name="password"
            required
            autofocus
            autocomplete="current-password"
            :error="$errors->get('password')"
        />


        {{-- Submit --}}

        <div class="flex justify-end mt-4">

            <x-primary-button
                class="w-full !text-center !p-3 flex items-center justify-center"
            >

                {{ __('auth.confirm') }}

            </x-primary-button>

        </div>

    </form>

</x-guest-layout>
