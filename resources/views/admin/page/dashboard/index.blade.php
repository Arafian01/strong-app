<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-red-600 to-orange-500 bg-clip-text text-transparent">
                    Halo, {{ Auth::user()->name }}
                </span>
            </h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">{{ today()->format('d F Y') }}</span>
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-red-600 text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Tambahin di dalam halaman Blade dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Pelanggan Aktif -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Pelanggan Aktif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pelangganStatus['aktif'] ?? 0 }}</p>
            </div>

            <!-- Total Tagihan Lunas Bulan Ini -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Tagihan Lunas Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $tagihanStatus['lunas'] ?? 0 }}</p>
            </div>

            <!-- Total Penghasilan Bulan Ini -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Penghasilan Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-800">Rp 
                </p>
            </div>
        </div>

    </div>
</x-app-layout>
