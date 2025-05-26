<x-app-layout>
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
        /* Ensure modal is centered and scrollable */
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
            <h2 class="text-2xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-red-600 to-orange-500 bg-clip-text text-transparent">
                    Tagihan
                </span>
            </h2>
        </div>
    </x-slot>

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

        <!-- Tabel Pelanggan -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Tagihan</h3>
            </div>

            <!-- Search & Entries -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('tagihan.index') }}" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama/email/alamat..."
                        class="w-full sm:w-64 px-4 py-2 rounded-lg border border-gray-200 focus:border-red-500 focus:ring-red-500" />
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Cari</button>
                </form>
                <form method="GET" action="{{ route('tagihan.index') }}" class="hidden md:flex items-center space-x-2">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <label for="entries" class="text-sm">Tampilkan:</label>
                    <select name="entries" onchange="this.form.submit()"
                        class="w-20 px-2 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm">data</span>
                </form>
            </div>

            <div class="py-6 px-4 sm:px-6 lg:px-8">
                <!-- Desktop Table -->
                <div class="hidden sm:block bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Tagihan</h3>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                        <table class="w-full table-auto text-sm">
                            <thead class="bg-gray-50">
                                <tr class="text-gray-700">
                                    <th class="px-4 py-3 text-center">No</th>
                                    <th class="px-4 py-3">Bulan Tahun</th>
                                    <th class="px-4 py-3">Harga</th>
                                    <th class="px-4 py-3">Status Pembayaran</th>
                                    <th class="px-4 py-3">Jatuh Tempo</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($tagihan as $key => $t)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center">{{ $tagihan->firstItem() + $key }}</td>
                                        <td class="px-4 py-3 text-center">{{ date('F Y', strtotime($t->bulan_tahun)) }}</td>
                                        <td class="px-4 py-3 text-center">Rp {{ number_format($t->pelanggan->paket->harga, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $t->jatuh_tempo }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($t->status_pembayaran == 'belum_dibayar')
                                                <button onclick="toggleModal('createModal', {{ $t->id }}, '{{ date('F Y', strtotime($t->bulan_tahun)) }}', '{{ $t->pelanggan->paket->harga }}')"
                                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                    Bayar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        {{ $tagihan->appends(['entries' => request('entries'), 'search' => request('search')])->links() }}
                    </div>
                </div>

                <!-- Create Modal -->
                <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
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
                                <div class="p-6 border-t bg-gray-50 rounded-b-2xl flex justify-end space-x gaflex justify-end space-x-3">
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
                </div>

                <!-- Mobile Card List -->
                <div class="sm:hidden space-y-4">
                    @foreach ($tagihan as $key => $t)
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-800">Tagihan Bulan {{ date('F Y', strtotime($t->bulan_tahun)) }}</h4>
                                </div>
                                <span
                                    class="px-2 py-1 {{ $t->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                                </span>
                            </div>
                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-700">
                                <div><span class="font-medium">Harga:</span> Rp {{ number_format($t->pelanggan->paket->harga, 0, ',', '.') }}</div>
                                <div><span class="font-medium">Jatuh Tempo:</span> {{ $t->jatuh_tempo }}</div>
                            </div>
                            @if ($t->status_pembayaran == 'belum_dibayar')
                                <div class="mt-2">
                                    <button onclick="toggleModal('createModal', {{ $t->id }}, '{{ date('F Y', strtotime($t->bulan_tahun)) }}', '{{ $t->pelanggan->paket->harga }}')"
                                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                        Bayar
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="pt-2">
                        {{ $tagihan->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript for Modal and Image Preview -->
        <script>
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
        </script>
</x-app-layout>