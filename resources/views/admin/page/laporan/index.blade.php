<x-app-layout>
    <style>
        :root {
            --primary-dark: #041562;
            --primary-bg: #11468F;
            --accent-red: #DA1212;
            --light-gray: #EEEEEE;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        [x-dropdown-content] {
            z-index: 50;
        }
    </style>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-[var(--primary-dark)]">
                <span class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-bg)] bg-clip-text text-transparent animate-pulse">
                    Laporan Pembayaran
                </span>
            </h2>
            <div class="hidden sm:flex items-center space-x-2">
                <span class="text-sm text-[var(--primary-dark)]">{{ today()->format('F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 backdrop-blur-sm overflow-hidden shadow-xl border border-[var(--light-gray)] sm:rounded-2xl">
                <div class="p-6 text-[var(--primary-dark)]">
                    <div class="p-4 bg-[var(--light-gray)] mb-2 rounded-xl font-bold">
                        <div class="flex items-center justify-between">
                            <div class="w-full text-[var(--primary-dark)]">
                                Pembayaran
                            </div>
                        </div>
                    </div>
                    <div>
                        <form class="w-full mx-auto my-5" method="POST" action="{{ route('laporan.store') }}">
                            @csrf
                            <div class="flex gap-5 my-5">
                                <div class="mb-5 w-full">
                                    <label for="tahun"
                                        class="block mb-2 text-sm font-medium text-[var(--primary-dark)]">Tahun</label>
                                    <select id="tahun" name="tahun" required
                                        class="bg-white border border-[var(--light-gray)] text-[var(--primary-dark)] text-sm rounded-lg focus:ring-[var(--primary-bg)] focus:border-[var(--primary-bg)] block w-full p-2.5">
                                        <option value="" disabled selected>Pilih Tahun</option>
                                        @for ($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit"
                                    class="bg-[var(--accent-red)] text-[var(--light-gray)] hover:bg-[var(--primary-bg)] focus:ring-4 focus:outline-none focus:ring-[var(--primary-bg)] font-medium rounded-lg text-sm w-full sm:w-auto px-12 py-2.5 text-center transition-colors transform hover:scale-105">
                                    Print
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('tahun').addEventListener('change', function() {
            const tahun = this.value;
            console.log('Tahun yang dipilih:', tahun);
        });
    </script>
</x-app-layout>