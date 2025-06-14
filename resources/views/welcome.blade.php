<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stront.net - WiFi Cepat & Stabil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #041562;
            --primary-blue: #11468F;
            --accent-red: #DA1212;
            --light-gray: #EEEEEE;
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        }

        .wave-animation {
            animation: wave 1.5s ease-in-out infinite;
        }

        @keyframes wave {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .scale-hover:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease;
        }

        .shadow-hover:hover {
            box-shadow: 0 10px 20px rgba(4, 21, 98, 0.2);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .fade-in {
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .nav-scrolled {
            background-color: var(--primary-dark);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-[var(--light-gray)] font-inter">
    <!-- Navigation -->
    <nav id="navbar"
        class="fixed w-full z-50 bg-[var(--primary-dark)]/90 backdrop-blur-lg shadow-sm transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="#" class="text-2xl font-bold text-[var(--light-gray)] flex items-center space-x-2">
                    <svg class="w-8 h-8 text-[var(--accent-red)] pulse-animation" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                    </svg>
                    <span class="text-xl font-bold text-[var(--primary-white)]">Strong<span
                            class="text-[var(--accent-red)]">App</span></span>
                </a>
                <div class="lg:hidden">
                    <button id="menu-btn"
                        class="text-[var(--light-gray)] hover:text-[var(--accent-red)] focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="#beranda"
                        class="text-[var(--light-gray)] hover:text-[var(--accent-red)] transition duration-300">Beranda</a>
                    <a href="#fitur"
                        class="text-[var(--light-gray)] hover:text-[var(--accent-red)] transition duration-300">Fitur</a>
                    <a href="#harga"
                        class="text-[var(--light-gray)] hover:text-[var(--accent-red)] transition duration-300">Harga</a>
                    <a href="#faq"
                        class="text-[var(--light-gray)] hover:text-[var(--accent-red)] transition duration-300">FAQ</a>
                    <div class="flex space-x-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-[var(--light-gray)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--accent-red)] hover:text-[var(--light-gray)] hover:border-[var(--accent-red)] transition duration-300">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--primary-blue)] transition duration-300 shadow-hover">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden lg:hidden bg-[var(--primary-dark)]/90 w-full py-4">
            <div class="max-w-6xl mx-auto px-4 space-y-4">
                <a href="#beranda"
                    class="block text-[var(--light-gray)] hover:text-[var(--accent-red)] transition duration-300">Beranda</a>
                <a href="#fitur"
                    class="block text-[var(--light-gray)] hover:text-[var(--accent-red)] transition duration-300">Fitur</a>
                <a href="#harga"
                    class="block text-[var(--light-gray)] hover:text-[var(--accent-red)] transition duration-300">Harga</a>
                <a href="#faq"
                    class="block text-[var(--light-gray)] hover:text-[var(--accent-red)] transition duration-300">FAQ</a>
                <div class="flex flex-col space-y-4 mt-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 border border-[var(--light-gray)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--accent-red)] hover:text-[var(--light-gray)] hover:border-[var(--accent-red)] transition duration-300">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--primary-blue)] transition duration-300">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="pt-32 pb-24 gradient-bg">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-[var(--light-gray)] mb-6 leading-tight" data-aos="fade-up">
                Internet <span class="text-[var(--accent-red)] pulse-animation">Super Cepat</span> untuk Rumah Anda
            </h1>
            <p class="text-lg text-[var(--light-gray)] mb-8 max-w-2xl mx-auto fade-in" data-aos="fade-up"
                data-aos-delay="200">
                Rasakan pengalaman streaming 4K, gaming tanpa lag, dan browsing mulus dengan teknologi fiber optik
                terdepan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                <a href="#harga"
                    class="bg-[var(--accent-red)] text-[var(--light-gray)] px-6 py-3 rounded-lg font-medium hover:bg-[var(--primary-blue)] transition duration-300 shadow-lg scale-hover">
                    Pilih Paket
                </a>
                <a href="#"
                    class="border-2 border-[var(--light-gray)] text-[var(--light-gray)] px-6 py-3 rounded-lg font-medium hover:border-[var(--accent-red)] hover:text-[var(--accent-red)] transition duration-300 scale-hover">
                    Coba Kecepatan
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-[var(--primary-dark)] mb-4" data-aos="zoom-in">Mengapa Strong.net?
                </h2>
                <p class="text-[var(--primary-dark)]/70 max-w-2xl mx-auto" data-aos="zoom-in">Solusi internet premium
                    untuk kebutuhan modern Anda.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-6 bg-[var(--light-gray)] rounded-2xl shadow-md shadow-hover" data-aos="fade-up">
                    <div class="w-12 h-12 bg-[var(--accent-red)] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[var(--light-gray)]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-[var(--primary-dark)]">Kecepatan Hingga 1Gbps</h3>
                    <p class="text-[var(--primary-dark)]/70">Nikmati koneksi ultra-cepat dengan latensi rendah.</p>
                </div>
                <div class="p-6 bg-[var(--light-gray)] rounded-2xl shadow-md shadow-hover" data-aos="fade-up"
                    data-aos-delay="200">
                    <div class="w-12 h-12 bg-[var(--accent-red)] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[var(--light-gray)]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-[var(--primary-dark)]">Uptime 99.9%</h3>
                    <p class="text-[var(--primary-dark)]/70">Koneksi stabil dengan sistem redundansi canggih.</p>
                </div>
                <div class="p-6 bg-[var(--light-gray)] rounded-2xl shadow-md shadow-hover" data-aos="fade-up"
                    data-aos-delay="400">
                    <div class="w-12 h-12 bg-[var(--accent-red)] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[var(--light-gray)]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-[var(--primary-dark)]">Dukungan 24/7</h3>
                    <p class="text-[var(--primary-dark)]/70">Tim ahli siap membantu kapan saja melalui chat atau call.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="py-16 bg-[var(--light-gray)]">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-[var(--primary-dark)] mb-4" data-aos="zoom-in">Pilih Paket Internet
                    Anda</h2>
                <p class="text-[var(--primary-dark)]/70 max-w-2xl mx-auto" data-aos="zoom-in">Paket fleksibel untuk
                    setiap kebutuhan.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-lg shadow-hover" data-aos="fade-up">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold mb-2 text-[var(--primary-dark)]">Basic</h3>
                        <div class="text-4xl font-bold text-[var(--accent-red)] mb-2">Rp199.000<span
                                class="text-lg text-[var(--primary-dark)]">/bulan</span></div>
                        <p class="text-[var(--primary-dark)]/70">Ideal untuk penggunaan sehari-hari.</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-[var(--accent-red)] mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Kecepatan 50 Mbps
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-[var(--accent-red)] mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Kuota Tanpa Batas
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="w-full block bg-[var(--accent-red)] text-[var(--light-gray)] py-3 rounded-lg hover:bg-[var(--primary-blue)] transition duration-300 scale-hover text-center">
                        Pilih Paket
                    </a>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-xl scale-105 shadow-hover relative" data-aos="fade-up"
                    data-aos-delay="200">
                    <span
                        class="absolute top-4 right-4 bg-[var(--accent-red)] text-[var(--light-gray)] px-3 py-1 rounded-full text-sm">Populer</span>
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold mb-2 text-[var(--primary-dark)]">Premium</h3>
                        <div class="text-4xl font-bold text-[var(--accent-red)] mb-2">Rp299.000<span
                                class="text-lg text-[var(--primary-dark)]">/bulan</span></div>
                        <p class="text-[var(--primary-dark)]/70">Sempurna untuk keluarga dan streaming.</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-[var(--accent-red)] mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Kecepatan 200 Mbps
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-[var(--accent-red)] mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Kuota Tanpa Batas
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-[var(--accent-red)] mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            5 Perangkat
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="w-full block bg-[var(--accent-red)] text-[var(--light-gray)] py-3 rounded-lg hover:bg-[var(--primary-blue)] transition duration-300 scale-hover text-center">
                        Pilih Paket
                    </a>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-lg shadow-hover" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold mb-2 text-[var(--primary-dark)]">Bisnis</h3>
                        <div class="text-4xl font-bold text-[var(--accent-red)] mb-2">Rp499.000<span
                                class="text-lg text-[var(--primary-dark)]">/bulan</span></div>
                        <p class="text-[var(--primary-dark)]/70">Dirancang untuk perusahaan dan gamer.</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-[var(--accent-red)] mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Kecepatan 500 Mbps
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-[var(--accent-red)] mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 10">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Kuota Tanpa Batas
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-[var(--accent-red)] mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            10 Perangkat
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="w-full block bg-[var(--accent-red)] text-[var(--light-gray)] py-3 rounded-lg hover:bg-[var(--primary-blue)] transition duration-300 scale-hover text-center">
                        Pilih Paket
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-[var(--primary-dark)] mb-4" data-aos="zoom-in">Pertanyaan Umum</h2>
                <p class="text-[var(--primary-dark)]/70 max-w-2xl mx-auto" data-aos="zoom-in">Jawaban atas pertanyaan
                    Anda tentang layanan kami.</p>
            </div>
            <div class="space-y-4">
                <div class="border border-[var(--light-gray)] rounded-2xl p-6 bg-[var(--light-gray)]"
                    data-aos="fade-up">
                    <button class="w-full text-left focus:outline-none flex justify-between items-center"
                        onclick="toggleFAQ(this)">
                        <h4 class="font-semibold text-lg text-[var(--primary-dark)]">Apa wilayah cakupan Strong.net?
                        </h4>
                        <svg class="w-6 h-6 text-[var(--accent-red)] transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="mt-2 text-[var(--primary-dark)]/70 hidden">
                        Kami melayani Jabodetabek, Bandung, Surabaya, dan akan terus memperluas cakupan area layanan.
                    </div>
                </div>
                <div class="border border-[var(--light-gray)] rounded-2xl p-6 bg-[var(--light-gray)]"
                    data-aos="fade-up" data-aos-delay="200">
                    <button class="w-full text-left focus:outline-none flex justify-between items-center"
                        onclick="toggleFAQ(this)">
                        <h4 class="font-semibold text-lg text-[var(--primary-dark)]">Bagaimana proses pemasangan?</h4>
                        <svg class="w-6 h-6 text-[var(--accent-red)] transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="mt-2 text-[var(--primary-dark)]/70 hidden">
                        Tim kami akan melakukan survei lokasi dan pemasangan dalam 24-48 jam setelah konfirmasi
                        pembayaran.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[var(--primary-dark)] text-[var(--light-gray)] py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-6">
                <div>
                    <h4
                        class="text-xl font-bold mb-4 bg-gradient-to-r from-[var(--accent-red)] to-[var(--light-gray)] bg-clip-text text-transparent">
                        Strong.net</h4>
                    <p class="text-sm text-[var(--light-gray)]/70">Internet rumah tercepat dengan teknologi fiber optik
                        terbaru.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-[var(--light-gray)]">Layanan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#harga" class="hover:text-[var(--accent-red)] transition duration-300">Paket
                                Internet</a></li>
                        <li><a href="#" class="hover:text-[var(--accent-red)] transition duration-300">Cek
                                Cakupan Area</a></li>
                        <li><a href="#" class="hover:text-[var(--accent-red)] transition duration-300">Status
                                Pemasangan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-[var(--light-gray)]">Kontak</h4>
                    <ul class="space-y-2 text-sm text-[var(--light-gray)]/70">
                        <li>WhatsApp: 0812-3456-7890</li>
                        <li>Email: support@strong.net</li>
                        <li>Dukungan Teknis 24/7</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-[var(--light-gray)]">Media Sosial</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-[var(--accent-red)] transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-[var(--accent-red)] transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-[var(--accent-red)] transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-[var(--light-gray)]/20 mt-8 pt-8">
                <p class="text-center text-sm text-[var(--light-gray)]/70">© 2025 Strong.net. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 120
        });

        // Toggle mobile menu
        document.getElementById('menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
        });

        // Toggle FAQ accordion
        function toggleFAQ(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('svg');
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('nav-scrolled');
            } else {
                navbar.classList.remove('nav-scrolled');
            }
        });
    </script>
</body>

</html>
