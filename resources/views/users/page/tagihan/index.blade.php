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

        .modal-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .modal-content {
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-[var(--primary-dark)]">
                <span class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-bg)] bg-clip-text text-transparent animate-pulse">
                    Tagihan
                </span>
            </h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[var(--light-gray)] to-white">
        <!-- Notifikasi -->
        @if (Session::has('message_insert'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ Session::get('message_insert') }}',
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

        <!-- Tabel Tagihan -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--primary-bg)] p-6" data-aos="fade-up">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h3 class="text-lg font-semibold text-[var(--primary-dark)]">Daftar Tagihan</h3>
            </div>

            <!-- Search & Entries -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('tagihan.index') }}" class="flex w-full sm:w-auto gap-2">
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
                            <th class="px-4 py-3">Bulan Tahun</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Status Pembayaran</th>
                            <th class="px-4 py-3">Jatuh Tempo</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--light-gray)]">
                        @forelse ($tagihan as $key => $t)
                            <tr class="hover:bg-[var(--light-gray)] transition-colors">
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">{{ $tagihan->firstItem() + $key }}</td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">
                                    {{ \Carbon\Carbon::createFromDate($t->tahun, $t->bulan, 1)->translatedFormat('F Y') }}
                                </td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $t->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-600' : ($t->status_pembayaran == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                        {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">{{ date('d-m-Y', strtotime($t->jatuh_tempo)) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($t->status_pembayaran == 'belum_dibayar')
                                        <button onclick="toggleModal('createModal', {{ $t->id }}, '{{ \Carbon\Carbon::createFromDate($t->tahun, $t->bulan, 1)->translatedFormat('F Y') }}', '{{ $t->harga }}')"
                                            class="px-4 py-2 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--primary-bg)]">
                                            Bayar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-[var(--primary-dark)]">Tidak ada data tagihan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="sm:hidden space-y-4">
                @forelse ($tagihan as $key => $t)
                    <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--light-gray)] p-4" data-aos="fade-up" data-aos-delay="{{ $key * 100 }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-[var(--primary-dark)]">
                                    {{ \Carbon\Carbon::createFromDate($t->tahun, $t->bulan, 1)->translatedFormat('F Y') }}
                                </h4>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs {{ $t->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-600' : ($t->status_pembayaran == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                            </span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-[var(--primary-dark)]">
                            <div><span class="font-medium">Harga:</span> Rp {{ number_format($t->harga, 0, ',', '.') }}</div>
                            <div><span class="font-medium">Jatuh Tempo:</span> {{ date('d-m-Y', strtotime($t->jatuh_tempo)) }}</div>
                        </div>
                        @if ($t->status_pembayaran == 'belum_dibayar')
                            <div class="mt-3">
                                <button onclick="toggleModal('createModal', {{ $t->id }}, '{{ \Carbon\Carbon::createFromDate($t->tahun, $t->bulan, 1)->translatedFormat('F Y') }}', '{{ $t->harga }}')"
                                    class="w-full px-4 py-2 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--primary-bg)]">
                                    Bayar
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--light-gray)] p-4 text-center text-[var(--primary-dark)]">
                        Tidak ada data tagihan.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('tagihan.index') }}" class="flex items-center space-x-2">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <label for="entries" class="text-sm text-[var(--primary-dark)]">Tampilkan:</label>
                    <select name="entries" onchange="this.form.submit()"
                        class="w-20 px-2 py-1 border border-[var(--light-gray)] rounded-md focus:ring-2 focus:ring-[var(--accent-red)] text-[var(--primary-dark)]">
                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
                {{ $tagihan->appends(['entries' => request('entries'), 'search' => request('search')])->links() }}
            </div>
        </div>

        <!-- Create Modal -->
        <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="modal-container">
                <div class="w-full max-w-2xl bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-[var(--primary-bg)] flex flex-col modal-content">
                    <!-- Header -->
                    <div class="p-6 border-b bg-[var(--light-gray)] rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--primary-dark)]" id="modal-title">Bayar Tagihan</h3>
                            <p class="text-sm text-[var(--primary-dark)]/70 mt-1">Upload bukti pembayaran (*)</p>
                        </div>
                        <button onclick="toggleModal('createModal')"
                            class="text-[var(--primary-dark)] hover:text-[var(--accent-red)] text-2xl p-2">
                            ✕
                        </button>
                    </div>
                    <form id="createForm" action="{{ route('pembayaran.store') }}" method="POST"
                        enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        <input type="hidden" name="tagihan_id" id="tagihan_id">
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            <!-- Harga -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Harga <span class="text-[var(--accent-red)]">*</span>
                                </label>
                                <input type="text" id="harga" readonly
                                    class="w-full p-2 rounded-lg border border-[var(--light-gray)] bg-[var(--light-gray)] text-[var(--primary-dark)]" />
                            </div>
                            <!-- Upload Bukti -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Upload Bukti Pembayaran <span class="text-[var(--accent-red)]">*</span>
                                </label>
                                <div class="flex items-center justify-center">
                                    <div class="bg-white p-6 rounded-xl shadow-lg">
                                        <div class="relative w-64 h-64">
                                            <div id="image-preview-create"
                                                class="w-full h-full bg-[var(--light-gray)] rounded-xl overflow-hidden flex items-center justify-center">
                                                <span class="text-[var(--primary-dark)]">No image selected</span>
                                            </div>
                                            <label for="image-input-create"
                                                class="absolute bottom-2 right-2 bg-white p-2 rounded-full shadow-lg cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--primary-dark)]"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </label>
                                            <input type="file" id="image-input-create" name="image"
                                                accept="image/*" required class="hidden">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="p-6 border-t bg-[var(--light-gray)] rounded-b-2xl flex justify-end space-x-3">
                            <button type="button" onclick="toggleModal('createModal')"
                                class="px-6 py-2 text-[var(--primary-dark)] hover:bg-[var(--light-gray)]/80 rounded-lg">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--primary-bg)] flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>Simpan Data
                            </button>
                        </div>
                    </form>
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

        function toggleModal(modalId, tagihanId = null, bulanTahun = null, harga = null) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
            if (tagihanId && bulanTahun && harga) {
                const tagihanInput = document.getElementById('tagihan_id');
                const modalTitle = document.getElementById('modal-title');
                const hargaInput = document.getElementById('harga');
                if (tagihanInput) {
                    tagihanInput.value = tagihanId;
                }
                if (modalTitle) {
                    modalTitle.textContent = `Bayar Tagihan ${bulanTahun}`;
                }
                if (hargaInput) {
                    hargaInput.value = `Rp ${parseInt(harga).toLocaleString('id-ID')}`;
                }
            }
        }

        document.getElementById('image-input-create').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview-create');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" />`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span class="text-[var(--primary-dark)]">No image selected</span>';
            }
        });
    </script>
</x-app-layout>