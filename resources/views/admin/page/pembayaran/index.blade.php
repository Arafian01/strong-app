<x-app-layout>
    <!-- Custom Scrollbar CSS -->
    <style>
        /* Custom Scrollbar */
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
    </style>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-red-600 to-orange-500 bg-clip-text text-transparent">
                    Pembayaran
                </span>
            </h2>
            <div class="hidden sm:flex items-center space-x-2">
                <span class="text-sm text-gray-500">{{ today()->format('F Y') }}</span>
                <button onclick="toggleModal('createModal')"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    ➕ Tambah Pembayaran
                </button>
            </div>
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

        <!-- Tabel Pembayaran -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Data Pembayaran</h3>
                <span class="text-sm text-gray-500 mt-2 md:mt-0">Total: {{ $pembayaran->total() }} Data</span>
            </div>

            <!-- Search & Entries -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('pembayaran.index') }}" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama/bulan..."
                        class="w-full sm:w-64 px-4 py-2 rounded-lg border border-gray-200 focus:border-red-500 focus:ring-red-500" />
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Cari</button>
                </form>
                <form method="GET" action="{{ route('pembayaran.index') }}" class="flex items-center space-x-2">
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
                <div
                    class="hidden sm:block bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Pembayaran</h3>
                        <span class="text-sm text-gray-500">Total: {{ $pembayaran->total() }} Data</span>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                        <table class="w-full table-auto text-sm">
                            <thead class="bg-gray-50">
                                <tr class="text-gray-700">
                                    <th class="px-4 py-3 text-center">No</th>
                                    <th class="px-4 py-3">Nama Pelanggan</th>
                                    <th class="px-4 py-3">Bulan Tahun</th>
                                    <th class="px-4 py-3 text-center">Harga</th>
                                    <th class="px-4 py-3 text-center">Tgl Kirim</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Tgl Verifikasi</th>
                                    <th class="px-4 py-3">Bukti</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($pembayaran as $key => $p)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center">{{ $pembayaran->firstItem() + $key }}</td>
                                        <td class="px-4 py-3">{{ $p->tagihan->pelanggan->user->name }}</td>
                                        <td class="px-4 py-3">{{ date('F Y', strtotime($p->tagihan->bulan_tahun)) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">Rp
                                            {{ number_format($p->tagihan->pelanggan->paket->harga, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-center">{{ $p->tanggal_kirim }}</td>
                                        <td class="px-4 py-3 text-center">
                                            {{ ucfirst(str_replace('_', ' ', $p->status_verifikasi)) }}</td>
                                        <td class="px-4 py-3 text-center">{{ $p->tanggal_verifikasi }}</td>
                                        <td class="px-4 py-3">
                                            <img src="{{ asset('pembayaran_images/' . $p->image) }}" alt="Bukti"
                                                class="w-16 h-16 object-cover rounded" />
                                        </td>
                                        <td class="px-4 py-3 text-center space-x-1">
                                            <button onclick="openEditModal({{ json_encode($p) }})"
                                                class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded-md hover:bg-yellow-200 text-xs">✏️</button>
                                            <button
                                                onclick="deletePembayaran('{{ $p->id }}','{{ $p->tagihan->pelanggan->user->name }}')"
                                                class="px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">🗑️</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        {{ $pembayaran->appends(['entries' => request('entries'), 'search' => request('search')])->links() }}
                    </div>
                </div>

                <!-- Mobile Card List -->
                <div class="sm:hidden space-y-4">
                    @foreach ($pembayaran as $key => $p)
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $p->tagihan->pelanggan->user->name }}
                                    </h4>
                                    <p class="text-xs text-gray-500">
                                        {{ date('F Y', strtotime($p->tagihan->bulan_tahun)) }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 {{ $p->status_verifikasi == 'diterima' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full text-xs">{{ ucfirst(str_replace('_', ' ', $p->status_verifikasi)) }}</span>
                            </div>
                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-700">
                                <div><span class="font-medium">Harga:</span> Rp
                                    {{ number_format($p->tagihan->pelanggan->paket->harga, 0, ',', '.') }}</div>
                                <div><span class="font-medium">Tgl Kirim:</span> {{ $p->tanggal_kirim }}</div>
                                <div><span class="font-medium">Tgl Verifikasi:</span> {{ $p->tanggal_verifikasi }}
                                </div>
                            </div>
                            <div class="mt-2">
                                <img src="{{ asset('pembayaran_images/' . $p->image) }}" alt="Bukti"
                                    class="w-full h-32 object-cover rounded" />
                            </div>
                            <div class="mt-3 flex justify-end space-x-2">
                                <button onclick="openEditModal({{ json_encode($p) }})"
                                    class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-md text-xs">✏️ Edit</button>
                                <button
                                    onclick="deletePembayaran('{{ $p->id }}','{{ $p->tagihan->pelanggan->user->name }}')"
                                    class="px-3 py-1 bg-red-100 text-red-600 rounded-md text-xs">🗑️ Hapus</button>
                            </div>
                        </div>
                    @endforeach
                    <div class="pt-2">
                        {{ $pembayaran->appends(request()->only(['search', 'entries']))->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Button (mobile only) -->
        <button onclick="toggleModal('createModal')"
            class="fixed bottom-4 right-4 w-14 h-14 bg-red-600 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-red-700 sm:hidden">
            ➕
        </button>

        <!-- Create Modal -->
        <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-2xl max-h-[calc(100vh-4rem)] bg-white rounded-2xl shadow-xl flex flex-col">
                    <!-- Header -->
                    <div class="p-6 border-b bg-red-50 rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-red-600">Tambah Pembayaran</h3>
                            <p class="text-sm text-red-400 mt-1">Isi semua bidang yang diperlukan (*)</p>
                        </div>
                        <button onclick="toggleModal('createModal')"
                            class="text-red-500 hover:text-red-700 text-2xl p-2">✕</button>
                    </div>
                    <form id="createForm" action="{{ route('pembayaran.store') }}" method="POST"
                        enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            {{-- Upload Bukti --}}
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
                                                <!-- Camera Icon -->
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
                                                accept="image/*" class="hidden">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Pilih Tagihan --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Tagihan <span class="text-red-500">*</span>
                                </label>
                                <select name="tagihan_id" required
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500">
                                    <option value="">👤 Pilih Tagihan...</option>
                                    @foreach ($tagihan as $t)
                                        @if ($t->status_pembayaran == 'belum_dibayar' || $t->status_pembayaran == 'ditolak')
                                            <option value="{{ $t->id }}">
                                                {{ $t->pelanggan->user->name }},
                                                {{ date('F Y', strtotime($t->bulan_tahun)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            {{-- Tanggal Kirim --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Tanggal Kirim <span class="text-red-500">*</span>
                                </label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅</div>
                                    <x-text-input type="date" name="tanggal_kirim" value="{{ date('Y-m-d') }}"
                                        required
                                        class="w-full pl-10 p-2 rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500" />
                                </div>
                            </div>
                            {{-- Status Verifikasi --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Status Verifikasi <span class="text-red-500">*</span>
                                </label>
                                <select name="status_verifikasi" required
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500">
                                    <option value="">Pilih Status...</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="menunggu verifikasi">Menunggu Verifikasi</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
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
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-2xl max-h-[calc(100vh-4rem)] bg-white rounded-2xl shadow-xl flex flex-col">
                    <!-- Header -->
                    <div class="p-6 border-b bg-red-50 rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-red-600">Edit Pembayaran</h3>
                            <p class="text-sm text-red-400 mt-1">Perbarui data pembayaran</p>
                        </div>
                        <button onclick="toggleModal('editModal')"
                            class="text-red-500 hover:text-red-700 text-2xl p-2">✕</button>
                    </div>
                    <form id="editForm" method="POST" class="flex-1 flex flex-col overflow-hidden"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            {{-- Upload & Preview --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                                <div class="relative w-64 h-64 mb-2">
                                    <div id="image-preview-edit"
                                        class="w-full h-full bg-gray-200 rounded-xl overflow-hidden flex items-center justify-center">
                                        <span class="text-gray-500">No image</span>
                                    </div>
                                    <label for="image-input-edit"
                                        class="absolute bottom-2 right-2 bg-white p-2 rounded-full shadow-lg cursor-pointer">
                                        <!-- Camera Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </label>
                                    <input type="file" id="image-input-edit" name="image" accept="image/*"
                                        class="hidden">
                                </div>
                            </div>
                            {{-- Tagihan --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Tagihan <span
                                        class="text-red-500">*</span></label>
                                <select name="tagihan_id" id="edit_tagihan_id" required
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500">
                                    <option value="">Pilih Tagihan...</option>
                                    @foreach ($tagihan as $t)
                                        <option value="{{ $t->id }}">
                                            {{ $t->pelanggan->user->name }},
                                            {{ date('F Y', strtotime($t->bulan_tahun)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Tanggal Kirim --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Tanggal Kirim <span
                                        class="text-red-500">*</span></label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅</div>
                                    <x-text-input type="date" id="edit_tanggal_kirim" name="tanggal_kirim"
                                        required
                                        class="w-full pl-10 p-2 rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500" />
                                </div>
                            </div>
                            {{-- Status Verifikasi --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Status Verifikasi <span
                                        class="text-red-500">*</span></label>
                                <select id="edit_status_verifikasi" name="status_verifikasi" required
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500">
                                    <option value="">Pilih Status...</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="menunggu verifikasi">Menunggu Verifikasi</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="p-6 border-t bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                            <button type="button" onclick="toggleModal('editModal')"
                                class="px-6 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                            <button type="submit"
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle show/hide modal by ID
        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        // Preview image on Create
        document.getElementById('image-input-create').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview-create');
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span class="text-gray-500">No image selected</span>';
            }
        });

        // Preview image on Edit
        document.getElementById('image-input-edit').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview-edit');
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span class="text-gray-500">No image</span>';
            }
        });

        // Open Edit Modal and populate fields
        function openEditModal(pembayaran) {
            const form = document.getElementById('editForm');
            // Set form action
            form.setAttribute('action', `/pembayaran/${pembayaran.id}`);
            // Populate values

            console.log(pembayaran.tagihan_id);

            form.elements['tagihan_id'].value = pembayaran.tagihan_id;
            form.elements['tanggal_kirim'].value = pembayaran.tanggal_kirim;
            form.elements['status_verifikasi'].value = pembayaran.status_verifikasi;
            // Set existing image preview
            const imgPrev = document.getElementById('image-preview-edit');
            imgPrev.innerHTML =
                `<img src="/pembayaran_images/${pembayaran.image}" class="w-full h-full object-cover rounded-lg" alt="Bukti">`;
            // Show modal
            toggleModal('editModal');
        }

        // Delete handler with SweetAlert
        async function deletePembayaran(id, name) {
            const result = await Swal.fire({
                title: `Hapus pembayaran ${name}?`,
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            });
            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`/pembayaran/${id}`, {
                    method: 'DELETE', // langsung pakai DELETE jika rutenya Route::delete
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // penting untuk FormRequest
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                // Periksa status
                const data = await response.json();
                if (!response.ok) {
                    // Tampilkan error
                    await Swal.fire('Gagal!', data.message, 'error');
                    return;
                }

                // Tampilkan sukses
                await Swal.fire('Terhapus!', data.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } catch (err) {
                // Kesalahan jaringan
                await Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                console.error(err);
            }
        }
    </script>
</x-app-layout>
