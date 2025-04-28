<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    @php
        use App\Models\Notifikasi;
        use App\Models\Status_baca;

        $user = Auth::user();
        $notifikasiBaru = Notifikasi::whereNotIn(
            'id',
            Status_baca::where('user_id', $user->id)->pluck('notifikasi_id'),
        )->count();
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-xl font-bold text-gray-800">Strong<span class="text-red-600">App</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover:text-red-600 transition-colors">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Master Dropdown -->
                    @can('role-admin')
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center space-x-1 px-3 py-2 text-sm font-medium text-gray-600 hover:text-red-600 transition-colors">
                                    <span class="{ 'text-red-600': request() -> routeIs('role.index', 'user.index', 'paket.index') }">Master</span>
                                    <svg class="w-4 h-4 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content" class="mt-2 py-2 bg-white rounded-lg shadow-xl border border-gray-100">
                                <x-dropdown-link :href="route('paket.index')" class="hover:bg-red-50 px-4 py-2">
                                    <span
                                        class="{{ request()->routeIs('paket.index') ? 'text-red-600 font-semibold' : 'text-gray-700' }}">Paket</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('pelanggan.index')" class="hover:bg-red-50 px-4 py-2">
                                    <span
                                        class="{{ request()->routeIs('pelanggan.index') ? 'text-red-600 font-semibold' : 'text-gray-700' }}">Pelanggan</span>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @endcan

                    <x-nav-link :href="route('tagihan.index')" :active="request()->routeIs('tagihan.index')" class="hover:text-red-600 transition-colors">
                        {{ __('Tagihan') }}
                    </x-nav-link>

                    <x-nav-link :href="route('pembayaran.index')" :active="request()->routeIs('pembayaran.index')" class="hover:text-red-600 transition-colors">
                        {{ __('Pembayaran') }}
                    </x-nav-link>

                    {{-- saya ingin rolenya admin dan owner untuk mengelola laporan--}}
                    @can('access-laporan')
                    <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')" class="hover:text-red-600 transition-colors">
                        {{ __('Laporan') }}
                    </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications -->
                <a href="{{ route('notifikasi.index') }}"
                    class="relative p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if ($notifikasiBaru > 0)
                        <span
                            class="absolute top-0 right-0 bg-red-600 text-white text-xs px-2 py-1 rounded-full shadow">{{ $notifikasiBaru }}</span>
                    @endif
                </a>

                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-1 group">
                            <div
                                class="bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-2 rounded-full text-sm font-medium shadow-sm">
                                {{ Str::limit(Auth::user()->name, 15) }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content" class="mt-2 py-2 bg-white rounded-lg shadow-xl border border-gray-100">
                        <x-dropdown-link :href="route('profile.edit')" class="px-4 py-2 hover:bg-red-50">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="px-4 py-2 hover:bg-red-50"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="h-6 w-6 text-gray-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white/95 backdrop-blur-sm">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-4">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('paket.index')" :active="request()->routeIs('paket.index')" class="px-4">
                {{ __('Paket') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pelanggan.index')" :active="request()->routeIs('pelanggan.index')" class="px-4">
                {{ __('Pelanggan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tagihan.index')" :active="request()->routeIs('tagihan.index')" class="px-4">
                {{ __('Tagihan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pembayaran.index')" :active="request()->routeIs('pembayaran.index')" class="px-4">
                {{ __('Pembayaran') }}
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Profile -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 space-y-1">
                <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="px-4">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="px-4"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
