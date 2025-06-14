<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="max-w-md mx-auto p-6 bg-[var(--light-gray)]/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-[var(--primary-blue)]"
        x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            <svg class="w-20 h-20 text-[var(--accent-red)] pulse-animation" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
            </svg>

            <span class="text-xl font-bold text-[var(--primary-dark)]">Strong<span class="text-[var(--accent-red)]">App</span></span>
        </div>

        <form method="POST" action="{{ route('login') }}" x-data="{ loading: false }"
            @submit.prevent="loading = true; $el.submit()">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email"
                    class="block w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                            focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4 mb-6">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password"
                    class="block w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                            focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200"
                    type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <div class="relative">
                        <input id="remember_me" type="checkbox" class="sr-only peer" name="remember">
                        <div
                            class="w-11 h-6 bg-[var(--primary-blue)] rounded-full peer peer-checked:after:translate-x-full 
                                   after:absolute after:top-0.5 after:left-0.5 after:bg-[var(--light-gray)] after:w-5 after:h-5 
                                   after:rounded-full after:transition-all after:duration-300 peer-checked:bg-[var(--accent-red)]">
                        </div>
                    </div>
                    <span class="ml-3 text-sm text-[var(--primary-dark)]">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-[var(--accent-red)] hover:underline hover:text-[var(--primary-blue)] transition-colors duration-200">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button
                    class="w-full h-12 bg-[var(--accent-red)] hover:bg-[var(--primary-blue)] 
                           shadow-lg transform transition-all duration-200 hover:scale-[1.02] active:scale-95
                           flex items-center justify-center text-base">
                    <div class="flex items-center justify-center w-full">
                        <span x-show="!loading" class="font-medium text-[var(--light-gray)]">{{ __('Log in') }}</span>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-[var(--light-gray)] absolute"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

<style>
    :root {
        --primary-dark: #041562;
        --primary-blue: #11468F;
        --accent-red: #DA1212;
        --light-gray: #EEEEEE;
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }
</style>
