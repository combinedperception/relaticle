<x-guest-layout title="{{ __('Wrong Account') }}">
    <div class="flex min-h-[60vh] items-center justify-center">
        <div class="mx-auto max-w-md px-6 py-12 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('Wrong Account') }}
            </h1>

            <p class="mt-4 text-gray-600 dark:text-gray-400">
                {{ __('This invitation was sent to') }}
                <strong class="text-gray-900 dark:text-white">{{ $invitedEmail }}</strong>.
            </p>

            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __("You're currently signed in as") }}
                <strong class="text-gray-900 dark:text-white">{{ $currentEmail }}</strong>.
                {{ __('Log out and sign in with the invited email address to accept this invitation.') }}
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <form method="POST" action="{{ filament()->getLogoutUrl() }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        {{ __('Log Out') }}
                    </button>
                </form>

                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:hover:bg-gray-700">
                    {{ __('Go to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
