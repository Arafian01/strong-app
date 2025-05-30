<x-guest-layout>
    <div class="max-w-md mx-auto p-6 bg-[var(--light-gray)]/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-[var(--primary-blue)]"
        x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            <svg class="w-20 h-20 text-[var(--accent-red)] pulse-animation" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
            </svg>
            <span class="mt-2 text-2xl font-bold text-[var(--primary-dark)]">Stront.net</span>
        </div>

        <form method="POST" action="{{ route('register') }}" 
              x-data="{ loading: false }"
              @submit.prevent="loading = true; $el.submit()">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" 
                    class="block w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                           focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200"
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Alamat -->
            <div class="mt-4">
                <x-input-label for="alamat" :value="__('Alamat')" />
                <x-text-input id="alamat" 
                    class="block w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                           focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200"
                    type="text" 
                    name="alamat" 
                    :value="old('alamat')"
                    required />
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <!-- No. HP -->
            <div class="mt-4">
                <x-input-label for="telepon" :value="__('No. HP')" />
                <x-text-input id="telepon" 
                    class="block w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                           focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200"
                    type="number" 
                    name="telepon" 
                    :value="old('telepon')"
                    required />
                <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" 
                    class="block w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                           focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200"
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" 
                    class="block w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                           focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200"
                    type="password"
                    name="password"
                    required 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" 
                    class="block w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                           focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Paket -->
            <div class="mt-4 mb-6">
                <x-input-label for="paket" :value="__('Paket')" />
                <select class="js-example-placeholder-single js-states form-control w-full px-4 py-3 bg-[var(--light-gray)]/50 border-2 border-[var(--primary-blue)] rounded-lg 
                            focus:outline-none focus:border-[var(--accent-red)] focus:ring-2 focus:ring-[var(--primary-blue)]/50 transition duration-200" 
                        name="paket_id" 
                        id="paket"
                        data-placeholder="Pilih paket">
                    <option value="">Pilih...</option>
                    @foreach ($paket as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_paket }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-[var(--accent-red)] hover:underline hover:text-[var(--primary-blue)] transition-colors duration-200" 
                   href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button 
                    class="bg-[var(--accent-red)] hover:bg-[var(--primary-blue)] 
                           shadow-lg transform transition-all duration-200 hover:scale-[1.02] active:scale-95
                           h-12 px-8 flex items-center justify-center"
                    x-bind:disabled="loading">
                    <div class="flex items-center">
                        <span x-show="!loading" class="font-medium text-[var(--light-gray)]">{{ __('Register') }}</span>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-[var(--light-gray)] ml-2" 
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
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
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
</style>