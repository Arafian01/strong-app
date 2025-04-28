<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="max-w-md mx-auto p-6 bg-white/70 backdrop-blur-lg rounded-3xl shadow-2xl border border-gray-100"
        x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">

        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <svg class="w-20 h-20 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </div>

        <form method="POST" action="{{ route('login') }}" 
              x-data="{ loading: false }"
              @submit.prevent="loading = true; $el.submit()">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email"
                    class="block w-full px-4 py-3 bg-white/50 border-2 border-gray-200 rounded-lg 
                            focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-200 transition duration-200"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4 mb-6">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password"
                    class="block w-full px-4 py-3 bg-white/50 border-2 border-gray-200 rounded-lg 
                            focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-200 transition duration-200"
                    type="password" name="password" required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <div class="relative">
                        <input id="remember_me" type="checkbox" class="sr-only peer" name="remember">
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full 
                                   after:absolute after:top-0.5 after:left-0.5 after:bg-white after:w-5 after:h-5 
                                   after:rounded-full after:transition-all after:duration-300 peer-checked:bg-red-600">
                        </div>
                    </div>
                    <span class="ml-3 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-red-600 hover:underline hover:text-red-800 transition-colors duration-200">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button
                    class="w-full h-12 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 
                           shadow-lg transform transition-all duration-200 hover:scale-[1.02] active:scale-95
                           flex items-center justify-center text-base"> <!-- Perubahan di sini -->
                    <div class="flex items-center justify-center w-full">
                        <span x-show="!loading" class="font-medium">{{ __('Log in') }}</span>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white absolute" 
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>