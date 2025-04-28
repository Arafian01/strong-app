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
        .gradient-red {
            background: linear-gradient(90deg, #EF4444 0%, #DC2626 100%);
        }
        
        .wave-animation {
            animation: wave 2s linear infinite;
        }
        
        @keyframes wave {
            0% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }
        
        .scale-hover:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
        
        .shadow-hover:hover {
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
        }
        
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: all 1.5s ease-out;
        }
        
        .aos-animate .reveal {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-50 font-inter">
    <!-- Navigation with Mobile Menu -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-lg shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="#" class="text-2xl font-bold text-red-600 flex items-center space-x-2">
                    <span>Stront.net</span>
                    <svg class="w-6 h-6 text-red-600 wave-animation" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </a>
                
                <!-- Mobile Menu -->
                <div class="lg:hidden">
                    <button id="menu-btn" class="text-gray-600 hover:text-red-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#beranda" class="text-gray-600 hover:text-red-600 transition duration-300">Beranda</a>
                    <a href="#fitur" class="text-gray-600 hover:text-red-600 transition duration-300">Fitur</a>
                    <a href="#harga" class="text-gray-600 hover:text-red-600 transition duration-300">Harga</a>
                    <a href="#faq" class="text-gray-600 hover:text-red-600 transition duration-300">FAQ</a>
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition duration-300">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 gradient-red text-white rounded-lg hover:shadow-lg transition duration-300">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white w-full py-4">
            <div class="container mx-auto px-4 space-y-4">
                <a href="#beranda" class="block text-gray-600 hover:text-red-600 transition duration-300">Beranda</a>
                <a href="#fitur" class="block text-gray-600 hover:text-red-600 transition duration-300">Fitur</a>
                <a href="#harga" class="block text-gray-600 hover:text-red-600 transition duration-300">Harga</a>
                <a href="#faq" class="block text-gray-600 hover:text-red-600 transition duration-300">FAQ</a>
                <div class="flex flex-col space-y-4 mt-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition duration-300">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 gradient-red text-white rounded-lg hover:shadow-lg transition duration-300">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Parallax -->
    <section id="beranda" class="pt-32 pb-24 bg-gradient-to-b from-red-50 to-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 text-center lg:text-left" data-aos="fade-right">
                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-6 leading-tight reveal">
                        Internet <span class="text-red-600">Cepat</span> & <br>
                        Stabil untuk <span class="wave-animation">Rumah Anda</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-xl reveal">
                        Nikmati streaming 4K, gaming tanpa lag, dan browsing super cepat dengan teknologi fiber optik terbaru
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start reveal">
                        <a href="#harga" class="gradient-red text-white px-6 py-3 rounded-lg font-medium hover:scale-105 transition duration-300 shadow-lg">
                            Pilih Paket
                        </a>
                        <a href="#" class="bg-white text-gray-900 px-6 py-3 rounded-lg font-medium border-2 border-gray-200 hover:border-red-300 hover:scale-105 transition duration-300">
                            Demo Kecepatan
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 mt-12 lg:mt-0" data-aos="fade-left">
                    <img src="https://cdn.pixabay.com/photo/2018/05/08/08/44/artificial-intelligence-3382507_1280.jpg"
                         class="w-full h-auto rounded-2xl shadow-2xl scale-hover transform transition duration-500" alt="Internet Rumah">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section with Animations -->
    <section id="fitur" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4 reveal" data-aos="zoom-in">Kenapa Memilih Kami?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto reveal" data-aos="zoom-in">Layanan premium untuk kebutuhan internet modern</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1" data-aos="flip-left">
                    <div class="w-12 h-12 gradient-red rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Kecepatan 1Gbps</h3>
                    <p class="text-gray-600">Fiber optik terbaru dengan latency ultra-rendah</p>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1" data-aos="flip-left" data-aos-delay="200">
                    <div class="w-12 h-12 gradient-red rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Garansi 99.9% Uptime</h3>
                    <p class="text-gray-600">Pemantauan 24/7 dengan sistem redundansi</p>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1" data-aos="flip-left" data-aos-delay="400">
                    <div class="w-12 h-12 gradient-red rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Dukungan Prioritas</h3>
                    <p class="text-gray-600">Tim ahli siap membantu 24/7 via chat/video call</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section with Flip Animation -->
    <section id="harga" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4 reveal" data-aos="zoom-in">Paket Internet Terbaik</h2>
                <p class="text-gray-600 reveal" data-aos="zoom-in">Pilih paket yang sesuai dengan kebutuhan Anda</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-md transform transition duration-500 hover:-translate-y-2 hover:shadow-2xl" data-aos="fade-up">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold mb-2">Basic</h3>
                        <div class="text-4xl font-bold text-red-600 mb-2">Rp199rb<span class="text-lg text-gray-500">/bln</span></div>
                        <p class="text-gray-600">Untuk penggunaan harian</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            50 Mbps
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Unlimited Quota
                        </li>
                    </ul>
                    <button class="w-full gradient-red text-white py-3 rounded-lg hover:scale-105 transition duration-300">
                        Pilih Paket
                    </button>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-xl transform transition duration-500 hover:-translate-y-2 hover:shadow-2xl scale-105" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center mb-6">
                        <div class="bg-red-600 text-white px-4 py-1 rounded-full mb-4">Paling Populer</div>
                        <h3 class="text-xl font-bold mb-2">Premium</h3>
                        <div class="text-4xl font-bold text-red-600 mb-2">Rp299rb<span class="text-lg text-gray-500">/bln</span></div>
                        <p class="text-gray-600">Untuk keluarga & streaming</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            200 Mbps
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Unlimited Quota
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            5 Perangkat
                        </li>
                    </ul>
                    <button class="w-full gradient-red text-white py-3 rounded-lg hover:scale-105 transition duration-300">
                        Pilih Paket
                    </button>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-md transform transition duration-500 hover:-translate-y-2 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold mb-2">Bisnis</h3>
                        <div class="text-4xl font-bold text-red-600 mb-2">Rp499rb<span class="text-lg text-gray-500">/bln</span></div>
                        <p class="text-gray-600">Untuk perusahaan & gamer</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            500 Mbps
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Unlimited Quota
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            10 Perangkat
                        </li>
                    </ul>
                    <button class="w-full gradient-red text-white py-3 rounded-lg hover:scale-105 transition duration-300">
                        Pilih Paket
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ with Accordion -->
    <section id="faq" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4 reveal" data-aos="zoom-in">Pertanyaan Umum</h2>
                <p class="text-gray-600 reveal" data-aos="zoom-in">Temukan jawaban cepat untuk pertanyaan Anda</p>
            </div>
            <div class="max-w-3xl mx-auto space-y-4">
                <div class="border rounded-2xl p-4" data-aos="fade-up">
                    <button class="w-full text-left focus:outline-none">
                        <div class="flex justify-between items-center">
                            <h4 class="font-semibold text-lg">Apa wilayah coverage Stront.net?</h4>
                            <svg class="w-6 h-6 text-red-600 transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <div class="mt-2 text-gray-600 overflow-hidden transition-all duration-300 max-h-0">
                            Kami melayani Jabodetabek, Bandung, Surabaya, dan akan terus memperluas area layanan.
                        </div>
                    </button>
                </div>
                <div class="border rounded-2xl p-4" data-aos="fade-up" data-aos-delay="200">
                    <button class="w-full text-left focus:outline-none">
                        <div class="flex justify-between items-center">
                            <h4 class="font-semibold text-lg">Bagaimana cara pemasangan?</h4>
                            <svg class="w-6 h-6 text-red-600 transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <div class="mt-2 text-gray-600 overflow-hidden transition-all duration-300 max-h-0">
                            Tim kami akan melakukan survey lokasi dan instalasi dalam 24-48 jam setelah konfirmasi pembayaran.
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="mb-8">
                    <h4 class="text-white font-semibold mb-4">Stront.net</h4>
                    <p class="text-sm">Internet rumah tercepat dengan teknologi fiber optik generasi terbaru</p>
                </div>
                <div class="mb-8">
                    <h4 class="text-white font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2">
                        <li><a href="#harga" class="hover:text-red-600 transition duration-300">Paket Internet</a></li>
                        <li><a href="#" class="hover:text-red-600 transition duration-300">Cek Cakupan Area</a></li>
                        <li><a href="#" class="hover:text-red-600 transition duration-300">Status Pemasangan</a></li>
                    </ul>
                </div>
                <div class="mb-8">
                    <h4 class="text-white font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2">
                        <li>WhatsApp: 0812-3456-7890</li>
                        <li>Email: support@stront.net</li>
                        <li>24/7 Technical Support</li>
                    </ul>
                </div>
                <div class="mb-8">
                    <h4 class="text-white font-semibold mb-4">Social Media</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"/></svg>
                        </a>
                        <a href="#" class="hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8">
                <p class="text-center text-sm">&copy; 2024 Stront.net. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 120
        });

        // Mobile Menu Toggle
        document.getElementById('menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
        });

        // FAQ Accordion
        document.querySelectorAll('#faq button').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.querySelector('.text-gray-600');
                const icon = button.querySelector('svg');
                
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.style.transform = 'rotate(180deg)';
                }
            });
        });
    </script>
</body>
</html>