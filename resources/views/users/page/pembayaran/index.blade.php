<x-app-layout>
    <!-- Custom Scrollbar and Color Palette CSS -->
    <style>
        :root {
            --primary-dark: #041562;
            --primary-bg: #11468F;
            --accent-red: #DA1212;
            --light-gray: #EEEEEE;
        }

        .modal-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .modal-scroll::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 4px;
        }

        .modal-scroll::-webkit-scrollbar-thumb {
            background: var(--primary-bg);
            border-radius: 4px;
        }

        .modal-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--accent-red);
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

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-[var(--primary-dark)]">
                <span class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-bg)] bg-clip-text text-transparent animate-pulse">
                    Pembayaran
                </span>
            </h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[var(--light-gray)] to-white">
        <!-- Notifikasi -->
        @if (Session::has('message_success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ Session::get('message_success') }}',
                    timer: 3000,
                    confirmButtonColor: 'var(--accent-red)'
                });
            </script>
        @endif
        @if (Session::has('message_error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ Session::get('message_error') }}',
                    confirmButtonColor: 'var(--accent-red)'
                });
            </script>
        @endif

        <!-- Tabel Pembayaran -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--primary-bg)] p-6" data-aos="fade-up">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h3 class="text-lg font-semibold text-[var(--primary-dark)]">Data Pembayaran</h3>
            </div>

            <!-- Search & Entries -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('pembayaran.index') }}" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari bulan/tahun..."
                        class="w-full sm:w-64 px-4 py-2 rounded-lg border border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                    <button type="submit"
                        class="bg-[var(--accent-red)] text-[var(--light-gray)] px-4 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-colors">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-lg border border-[var(--light-gray)]">
                <table class="w-full table-auto text-sm">
                    <thead class="bg-[var(--light-gray)]">
                        <tr class="text-[var(--primary-dark)]">
                            <th class="px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3 text-center">Bulan Tahun</th>
                            <th class="px-4 py-3 text-center">Harga</th>
                            <th class="px-4 py-3 text-center">Tgl Kirim</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Tgl Verifikasi</th>
                            <th class="px-4 py-3 text-center">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--light-gray)]">
                        @forelse ($pembayaran as $key => $p)
                            <tr class="hover:bg-[var(--light-gray)] transition-colors">
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">{{ $pembayaran->firstItem() + $key }}</td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">
                                    {{ \Carbon\Carbon::createFromDate($p->tagihan->tahun, $p->tagihan->bulan, 1)->translatedFormat('F Y') }}
                                </td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">Rp {{ number_format($p->tagihan->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">{{ date('d-m-Y', strtotime($p->tanggal_kirim)) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $p->status_verifikasi == 'diterima' ? 'bg-green-100 text-green-600' : ($p->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                                        {{ ucfirst(str_replace('_', ' ', $p->status_verifikasi)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">
                                    {{ $p->tanggal_verifikasi ? date('d-m-Y', strtotime($p->tanggal_verifikasi)) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="openImageModal('{{ asset('pembayaran_images/' . $p->image) }}')"
                                        class="w-16 h-16 overflow-hidden rounded-lg">
                                        <img src="{{ asset('pembayaran_images/' . $p->image) }}" alt="Bukti"
                                            class="w-full h-full object-cover" />
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-center text-[var(--primary-dark)]">Tidak ada data pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="sm:hidden space-y-4">
                @forelse ($pembayaran as $key => $p)
                    <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--light-gray)] p-4" data-aos="fade-up" data-aos-delay="{{ $key * 100 }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-[var(--primary-dark)]">
                                    {{ \Carbon\Carbon::createFromDate($p->tagihan->tahun, $p->tagihan->bulan, 1)->translatedFormat('F Y') }}
                                </h4>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs {{ $p->status_verifikasi == 'diterima' ? 'bg-green-100 text-green-600' : ($p->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                                {{ ucfirst(str_replace('_', ' ', $p->status_verifikasi)) }}
                            </span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-[var(--primary-dark)]">
                            <div><span class="font-medium">Harga:</span> Rp {{ number_format($p->tagihan->harga, 0, ',', '.') }}</div>
                            <div><span class="font-medium">Tgl Kirim:</span> {{ date('d-m-Y', strtotime($p->tanggal_kirim)) }}</div>
                            <div><span class="font-medium">Tgl Verifikasi:</span> {{ $p->tanggal_verifikasi ? date('d-m-Y', strtotime($p->tanggal_verifikasi)) : '-' }}</div>
                        </div>
                        <div class="mt-3">
                            <button onclick="openImageModal('{{ asset('pembayaran_images/' . $p->image) }}')"
                                class="w-full h-32 overflow-hidden rounded-lg">
                                <img src="{{ asset('pembayaran_images/' . $p->image) }}" alt="Bukti"
                                    class="w-full h-full object-cover" />
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--light-gray)] p-4 text-center text-[var(--primary-dark)]">
                        Tidak ada data pembayaran.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('pembayaran.index') }}" class="flex items-center space-x-2">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <label for="entries" class="text-sm text-[var(--primary-dark)]">Tampilkan:</label>
                    <select name="entries" onchange="this.form.submit()"
                        class="w-20 px-2 py-1 border border-[var(--light-gray)] rounded-md focus:ring-2 focus:ring-[var(--accent-red)] text-[var(--primary-dark)]">
                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-[var(--primary-dark)]">data</span>
                </form>
                {{ $pembayaran->appends(['entries' => request('entries'), 'search' => request('search')])->links() }}
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-[var(--primary-bg)] p-4 max-w-3xl w-full">
                    <button onclick="closeImageModal()"
                        class="absolute top-2 right-2 text-[var(--primary-dark)] hover:text-[var(--accent-red)] text-2xl">
                        ✕
                    </button>
                    <img id="modalImage" src="" alt="Bukti Pembayaran" class="w-full h-auto max-h-[80vh] object-contain rounded-lg" />
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 120
        });

        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>