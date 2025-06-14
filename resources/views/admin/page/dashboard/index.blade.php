<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-[var(--primary-dark)]">
                <span class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-bg)] bg-clip-text text-transparent animate-pulse">
                    Halo, {{ Auth::user()->name }}
                </span>
            </h2>
            {{-- <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-[var(--primary-dark)]">{{ today()->format('d F Y') }}</span>
                <div class="w-10 h-10 bg-[var(--accent-red)] rounded-full flex items-center justify-center shadow-md">
                    <span class="text-[var(--light-gray)] text-lg font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            </div> --}}
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[var(--light-gray)] to-white">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)] transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" data-aos="zoom-in">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-[var(--accent-red)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-[var(--primary-dark)]">Pelanggan Aktif</p>
                        <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $pelangganStatus['aktif'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)] transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" data-aos="zoom-in" data-aos-delay="100">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-[var(--accent-red)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-[var(--primary-dark)]">Tagihan Lunas Bulan Ini</p>
                        <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $tagihanStatus['lunas'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)] transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" data-aos="zoom-in" data-aos-delay="200">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-[var(--accent-red)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-[var(--primary-dark)]">Tagihan Menunggu Verifikasi</p>
                        <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $tagihanStatus['menunggu_verifikasi'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)] transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" data-aos="zoom-in" data-aos-delay="300">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-[var(--accent-red)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-[var(--primary-dark)]">Tagihan Belum Dibayar</p>
                        <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $tagihanStatus['belum_dibayar'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tagihan Status Chart -->
            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)]" data-aos="fade-up">
                <h3 class="text-lg font-semibold text-[var(--primary-dark)] mb-4">Statistik Tagihan Bulan Ini</h3>
                <canvas id="tagihanChart" class="w-full h-80"></canvas>
            </div>

            <!-- Monthly Income Chart -->
            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)]" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-[var(--primary-dark)]">Penghasilan Per Bulan ({{ $selectedYear }})</h3>
                    <form method="GET" action="{{ route('dashboardAdmin.index') }}" class="flex items-center space-x-2">
                        <select name="year" onchange="this.form.submit()"
                            class="px-3 py-2 rounded-lg border border-[var(--primary-bg)] text-sm text-[var(--primary-dark)] focus:ring-2 focus:ring-[var(--accent-red)]">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <canvas id="incomeChart" class="w-full h-80"></canvas>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        AOS.init({
            duration: 1200,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Tagihan Status Chart
        const tagihanCtx = document.getElementById('tagihanChart').getContext('2d');
        new Chart(tagihanCtx, {
            type: 'bar',
            data: {
                labels: ['Lunas', 'Menunggu Verifikasi', 'Belum Dibayar'],
                datasets: [{
                    label: 'Jumlah Tagihan',
                    data: [
                        {{ $tagihanStatus['lunas'] ?? 0 }},
                        {{ $tagihanStatus['menunggu_verifikasi'] ?? 0 }},
                        {{ $tagihanStatus['belum_dibayar'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(218, 18, 18, 0.7)', // --accent-red
                        'rgba(17, 70, 143, 0.7)', // --primary-bg
                        'rgba(4, 21, 98, 0.7)' // --primary-dark
                    ],
                    borderColor: [
                        'var(--accent-red)',
                        'var(--primary-bg)',
                        'var(--primary-dark)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'var(--primary-dark)',
                            font: { size: 12 }
                        },
                        grid: { color: 'rgba(238, 238, 238, 0.3)' }
                    },
                    x: {
                        ticks: {
                            color: 'var(--primary-dark)',
                            font: { size: 12 }
                        },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'var(--primary-dark)',
                            font: { size: 14 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'var(--primary-bg)',
                        titleColor: 'var(--light-gray)',
                        bodyColor: 'var(--light-gray)',
                        borderColor: 'var(--accent-red)',
                        borderWidth: 1
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Monthly Income Chart
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Penghasilan (Rp)',
                    data: @json(array_values($incomeData)),
                    fill: true,
                    backgroundColor: 'rgba(218, 18, 18, 0.2)',
                    borderColor: 'var(--accent-red)',
                    borderWidth: 3,
                    tension: 0.3,
                    pointBackgroundColor: 'var(--accent-red)',
                    pointBorderColor: 'var(--light-gray)',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'var(--primary-dark)',
                            font: { size: 12 },
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: { color: 'rgba(238, 238, 238, 0.3)' }
                    },
                    x: {
                        ticks: {
                            color: 'var(--primary-dark)',
                            font: { size: 12 }
                        },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'var(--primary-dark)',
                            font: { size: 14 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'var(--primary-bg)',
                        titleColor: 'var(--light-gray)',
                        bodyColor: 'var(--light-gray)',
                        borderColor: 'var(--accent-red)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
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
    </style>
</x-app-layout>