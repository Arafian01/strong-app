<x-app-layout>
        <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-red-600 to-orange-500 bg-clip-text text-transparent">
                    Dashboard Pelanggan
                </span>
            </h2>
        </div>
    </x-slot>

    <!-- Custom Scrollbar CSS -->
    <style>
        .modal-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .modal-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .modal-scroll::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }
        .modal-scroll::-webkit-scrollbar-thumb:hover {
            background: #999;
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

    <div class="py-6 px-4 sm:px-6 lg:px-8">
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

        <!-- Dashboard Content -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
            <!-- Welcome Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Selamat Datang, {{ auth()->user()->name ?? 'Pelanggan' }}!</h3>
                <p class="text-sm text-gray-600">Berikut adalah ringkasan akun Anda.</p>
            </div>

            <!-- Account Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-gray-700">Paket Langganan</h4>
                    <p class="text-lg font-semibold text-red-600">{{ $pelanggan->paket->nama_paket }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-gray-700">Tagihan Belum Dibayar</h4>
                    <p class="text-lg font-semibold text-red-600">{{ $tagihanBelumDibayar }} Tagihan</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-gray-700">Total Pembayaran</h4>
                    <p class="text-lg font-semibold text-red-600">{{ $totalPembayaran }} Kali</p>
                </div>
            </div>

            <!-- Unpaid Bills -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tagihan Belum Dibayar</h3>
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="w-full table-auto text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-gray-700">
                                <th class="px-4 py-3 text-center">No</th>
                                <th class="px-4 py-3">Bulan Tahun</th>
                                <th class="px-4 py-3">Harga</th>
                                <th class="px-4 py-3">Jatuh Tempo</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($tagihan as $key => $t)
                                @if ($t->status_pembayaran == 'belum_dibayar')
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center">{{ $key + 1 }}</td>
                                        <td class="px-4 py-3 text-center">{{ date('F Y', strtotime($t->bulan_tahun)) }}</td>
                                        <td class="px-4 py-3 text-center">Rp {{ $t->pelanggan && $t->pelanggan->paket ? number_format($t->pelanggan->paket->harga, 0, ',', '.') : 'N/A' }}</td>
                                        <td class="px-4 py-3 text-center">{{ $t->jatuh_tempo }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <button onclick="toggleModal('createModal', {{ $t->id }}, '{{ date('F Y', strtotime($t->bulan_tahun)) }}', '{{ $t->pelanggan && $t->pelanggan->paket ? $t->pelanggan->paket->harga : 0 }}')"
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                Bayar
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-600">Tidak ada tagihan belum dibayar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Payments -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran Terakhir</h3>
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="w-full table-auto text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-gray-700">
                                <th class="px-4 py-3 text-center">No</th>
                                <th class="px-4 py-3">Bulan Tahun</th>
                                <th class="px-4 py-3">Harga</th>
                                <th class="px-4 py-3">Tanggal Kirim</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($pembayaran as $key => $p)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-center">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3 text-center">{{ $p->tagihan ? date('F Y', strtotime($p->tagihan->bulan_tahun)) : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-center">Rp {{ $p->tagihan && $p->tagihan->pelanggan && $p->tagihan->pelanggan->paket ? number_format($p->tagihan->pelanggan->paket->harga, 0, ',', '.') : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $p->tanggal_kirim }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 {{ $p->status_verifikasi == 'diterima' ? 'bg-green-100 text-green-600' : ($p->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }} rounded-full text-xs">
                                            {{ ucfirst(str_replace('_', ' ', $p->status_verifikasi)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-600">Tidak ada pembayaran terakhir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        {{-- <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="modal-container">
                <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl flex flex-col modal-content">
                    <!-- Header -->
                    <div class="p-6 border-b bg-red-50 rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-red-600" id="modal-title">Bayar Tagihan</h3>
                            <p class="text-sm text-red-400 mt-1">Upload bukti pembayaran (*)</p>
                        </div>
                        <button onclick="toggleModal('createModal')"
                            class="text-red-500 hover:text-red-700 text-2xl p-2">✕</button>
                    </div>
                    <form id="createForm" action="{{ route('pembayaran.store') }}" method="POST"
                        enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        <input type="hidden" name="tagihan_id" id="tagihan_id">
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            <!-- Harga -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Harga <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="harga" readonly
                                    class="w-full p-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-700" />
                            </div>
                            <!-- Upload Bukti -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Upload Bukti Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center justify-center">
                                    <div class="bg-white p-6 rounded-xl shadow-lg">
                                        <div class="relative w-64 h-64">
                                            <div id="image-preview-create"
                                                class="w-full h-full bg-gray-200 rounded-xl overflow-hidden flex items-center justify-center">
                                                <span class="text-gray-500">No image selected</span>
                                            </div>
                                            <label for="image-input-create"
                                                class="absolute bottom-2 right-2 bg-white p-2 rounded-full shadow-lg cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700"
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
                        <div class="p-6 border-t bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                            <button type="button" onclick="toggleModal('createModal')"
                                class="px-6 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                            <button type="submit"
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- JavaScript for Modal and Image Preview -->
    {{-- <script>
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
                preview.innerHTML = '<span class="text-gray-500">No image selected</span>';
            }
        });
    </script> --}}
</x-app-layout>