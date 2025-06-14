<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-[var(--primary-dark)]">
                <span class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-bg)] bg-clip-text text-transparent animate-pulse">
                    Halo, {{ Auth::user()->name }}
                </span>
            </h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[var(--light-gray)] to-white">
        <!-- Notifikasi -->
        @if (Session::has('message_insert'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ Session::get('message_insert') }}',
                    timer: 3000
                });
            </script>
        @endif
        @if (Session::has('message_error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ Session::get('message_error') }}'
                });
            </script>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)] transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" data-aos="zoom-in">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-[var(--accent-red)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4m6-6a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-[var(--primary-dark)]">Paket Langganan</p>
                        <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $pelanggan->paket->nama_paket ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)] transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" data-aos="zoom-in" data-aos-delay="100">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-[var(--accent-red)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-[var(--primary-dark)]">Tagihan Belum Dibayar</p>
                        <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $tagihanBelumDibayar ?? 0 }} Tagihan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)] transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" data-aos="zoom-in" data-aos-delay="200">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-[var(--accent-red)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-[var(--primary-dark)]">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-[var(--accent-red)]">{{ $totalPembayaran ?? 0 }} Kali</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Unpaid Bills -->
            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)]" data-aos="fade-up">
                <h3 class="text-lg font-semibold text-[var(--primary-dark)] mb-4">Tagihan Belum Dibayar</h3>
                <div class="overflow-x-auto rounded-lg border border-[var(--primary-bg)]">
                    <table class="w-full table-auto text-sm">
                        <thead class="bg-[var(--light-gray)]">
                            <tr class="text-[var(--primary-dark)]">
                                <th class="px-4 py-3 text-center">No</th>
                                <th class="px-4 py-3">Bulan Tahun</th>
                                <th class="px-4 py-3">Harga</th>
                                <th class="px-4 py-3">Jatuh Tempo</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--primary-bg)]">
                            @forelse ($tagihan as $key => $t)
                                @if ($t->status_pembayaran == 'belum_dibayar')
                                    <tr class="hover:bg-[var(--light-gray)] transition-colors">
                                        <td class="px-4 py-3 text-center">{{ $key + 1 }}</td>
                                        <td class="px-4 py-3 text-center">{{ date('F Y', strtotime($t->bulan . '-01-' . $t->tahun)) }}</td>
                                        <td class="px-4 py-3 text-center">Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-center">{{ date('d-m-Y', strtotime($t->jatuh_tempo)) }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <button onclick="toggleModal('createModal', {{ $t->id }}, '{{ date('F Y', strtotime($t->bulan . '-01-' . $t->tahun)) }}', '{{ $t->harga }}')"
                                                class="px-4 py-2 bg-[var(--accent-red)] text-white rounded-lg hover:bg-[var(--primary-dark)]">
                                                Bayar
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-[var(--primary-dark)]">Tidak ada tagihan belum dibayar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-lg border border-[var(--primary-bg)]" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-lg font-semibold text-[var(--primary-dark)] mb-4">Pembayaran Terakhir</h3>
                <div class="overflow-x-auto rounded-lg border border-[var(--primary-bg)]">
                    <table class="w-full table-auto text-sm">
                        <thead class="bg-[var(--light-gray)]">
                            <tr class="text-[var(--primary-dark)]">
                                <th class="px-4 py-3 text-center">No</th>
                                <th class="px-4 py-3">Bulan Tahun</th>
                                <th class="px-4 py-3">Harga</th>
                                <th class="px-4 py-3">Tanggal Kirim</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--primary-bg)]">
                            @forelse ($pembayaran as $key => $p)
                                <tr class="hover:bg-[var(--light-gray)] transition-colors">
                                    <td class="px-4 py-3 text-center">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3 text-center">{{ $p->tagihan ? date('F Y', strtotime($p->tagihan->bulan . '-01-' . $p->tagihan->tahun)) : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-center">Rp {{ number_format($p->tagihan->harga ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">{{ date('d-m-Y', strtotime($p->tanggal_kirim)) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 {{ $p->status_verifikasi == 'diterima' ? 'bg-green-100 text-green-600' : ($p->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }} rounded-full text-xs">
                                            {{ ucfirst(str_replace('_', ' ', $p->status_verifikasi)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-[var(--primary-dark)]">Tidak ada pembayaran terakhir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="modal-container">
                <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl flex flex-col modal-content">
                    <!-- Header -->
                    <div class="p-6 border-b bg-[var(--light-gray)] rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--accent-red)]" id="modal-title">Bayar Tagihan</h3>
                            <p class="text-sm text-[var(--primary-dark)] mt-1">Upload bukti pembayaran (*)</p>
                        </div>
                        <button onclick="toggleModal('createModal')"
                            class="text-[var(--accent-red)] hover:text-[var(--primary-dark)] text-2xl p-2">✕</button>
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
                                    class="w-full p-2 rounded-lg border border-[var(--primary-bg)] bg-[var(--light-gray)] text-[var(--primary-dark)]" />
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
                                class="px-6 py-2 text-[var(--primary-dark)] hover:bg-[var(--primary-bg)]/20 rounded-lg">Batal</button>
                            <button type="submit"
                                class="px-6 py-2 bg-[var(--accent-red)] text-white rounded-lg hover:bg-[var(--primary-dark)] flex items-center">
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
            duration: 1200,
            easing: 'ease-in-out',
            once: true,
            offset: 100
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
            background: var(--primary-dark);
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
</x-app-layout>