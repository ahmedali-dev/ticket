<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />


    <form method="POST" action="{{ route('login') }}">

        @csrf


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
            id="email"
            :error="$errors->get('email')"
        />


        <x-input

            label="auth.password"
            id="password"
            class="block mt-1 w-full"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            :error="$errors->get('password')"
        >
            @if (Route::has('password.request'))

                <a
                    class="block underline text-left w-full text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}"
                >
                    {{ __('auth.forgot_password') }}
                </a>

            @endif
        </x-input>


        <!-- Remember Me -->

        <div class="block mt-4">


            <label
                for="remember_me"
                class="inline-flex items-center"
            >


                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    name="remember"
                >

                <span class="ms-2 text-sm text-gray-600">
                    {{ __('auth.remember_me') }}
                </span>

            </label>

        </div>


        <!-- Actions -->

        <div class="mt-3 w-full !text-center !p-3 flex flex-col items-center justify-center gap-4">



            <x-primary-button class="">

                {{ __('auth.login_button') }}

            </x-primary-button>


            <a
                class="underline font-bold text-md text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('register') }}"
            >
                {{ __('auth.not_have_an_account') }}
            </a>
        </div>

    </form>

</x-guest-layout>
