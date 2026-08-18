<x-guest-layout>

    {{-- Verification Message --}}

    <div class="mb-4 text-sm text-gray-600">

        {{ __('auth.verify_email_message') }}

    </div>


    {{-- Verification Link Sent --}}

    @if (session('status') === 'verification-link-sent')

        <div class="mb-4 font-medium text-sm text-green-600">

            {{ __('auth.verification_link_sent') }}

        </div>

    @endif


    {{-- Actions --}}

    <div class="mt-4 flex flex-col gap-4">


        {{-- Resend Verification Email --}}

        <form
            method="POST"
            action="{{ route('verification.send') }}"
            class="w-full"
        >

            @csrf

            <x-primary-button
                class="w-full !text-center !p-3 flex items-center justify-center"
            >

                {{ __('auth.resend_verification_email') }}

            </x-primary-button>

        </form>


        {{-- Logout --}}

        <form
            method="POST"
            action="{{ route('logout') }}"
            class="w-full"
        >

            @csrf

            <button
                type="submit"
                class="w-full underline font-bold text-md text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >

                {{ __('auth.logout') }}

            </button>

        </form>

    </div>

</x-guest-layout>
