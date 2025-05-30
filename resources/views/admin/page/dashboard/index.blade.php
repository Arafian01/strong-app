<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-[var(--primary-dark)]">
                <span class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-blue)] bg-clip-text text-transparent pulse-animation">
                    Halo, {{ Auth::user()->name }}
                </span>
            </h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-[var(--primary-dark)]">{{ today()->format('d F Y') }}</span>
                <div class="w-8 h-8 bg-[var(--light-gray)] rounded-full flex items-center justify-center">
                    <span class="text-[var(--accent-red)] text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-[var(--light-gray)]">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Pelanggan Aktif -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl p-6 shadow-lg border border-[var(--primary-blue)] transform transition duration-300 hover:scale-105 hover:shadow-2xl" data-aos="fade-up">
                <p class="text-sm text-[var(--primary-dark)] mb-1">Pelanggan Aktif</p>
                <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $pelangganStatus['aktif'] ?? 0 }}</p>
            </div>

            <!-- Total Tagihan Lunas Bulan Ini -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl p-6 shadow-lg border border-[var(--primary-blue)] transform transition duration-300 hover:scale-105 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="200">
                <p class="text-sm text-[var(--primary-dark)] mb-1">Tagihan Lunas Bulan Ini</p>
                <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $tagihanStatus['lunas'] ?? 0 }}</p>
            </div>

            <!-- Total Tagihan Belum Diverifikasi -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl p-6 shadow-lg border border-[var(--primary-blue)] transform transition duration-300 hover:scale-105 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="400">
                <p class="text-sm text-[var(--primary-dark)] mb-1">Tagihan Belum Diverifikasi</p>
                <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $tagihanStatus['belum_diverifikasi'] ?? 0 }}</p>
            </div>

            <!-- Total Tagihan Belum Dibayar -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl p-6 shadow-lg border border-[var(--primary-blue)] transform transition duration-300 hover:scale-105 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="600">
                <p class="text-sm text-[var(--primary-dark)] mb-1">Tagihan Belum Dibayar</p>
                <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $tagihanStatus['belum_dibayar'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl p-6 shadow-lg border border-[var(--primary-blue)]" data-aos="fade-up" data-aos-delay="800">
            <h3 class="text-lg font-semibold text-[var(--primary-dark)] mb-4">Statistik Tagihan Bulan Ini</h3>
            <canvas id="tagihanChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 120
        });

        // Chart.js for Tagihan Status
        const ctx = document.getElementById('tagihanChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Lunas', 'Belum Diverifikasi', 'Belum Dibayar'],
                datasets: [{
                    label: 'Jumlah Tagihan',
                    data: [
                        {{ $tagihanStatus['lunas'] ?? 0 }},
                        {{ $tagihanStatus['belum_diverifikasi'] ?? 0 }},
                        {{ $tagihanStatus['belum_dibayar'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'var(--accent-red)',
                        'var(--primary-blue)',
                        'var(--primary-dark)'
                    ],
                    borderColor: [
                        'var(--accent-red)',
                        'var(--primary-blue)',
                        'var(--primary-dark)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'var(--primary-dark)'
                        }
                    },
                    x: {
                        ticks: {
                            color: 'var(--primary-dark)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'var(--primary-dark)'
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    </script>

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
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</x-app-layout>