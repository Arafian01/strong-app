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
                    Tagihan
                </span>
            </h2>
            <div class="hidden sm:flex items-center space-x-2">
                <span class="text-sm text-gray-500">{{ today()->format('F Y') }}</span>
                <button onclick="toggleModal('createModal')"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    ➕ Tambah Tagihan
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
                    text: '{{ Session::get('message_success') }}',
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
                <h3 class="text-lg font-semibold text-gray-800">Daftar Pelanggan</h3>
                <span class="text-sm text-gray-500 mt-2 md:mt-0">Total: {{ $tagihan->total() }} Data</span>
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

                <form method="GET" action="{{ route('tagihan.index') }}" class="flex items-center space-x-2">
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
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Pelanggan</h3>
                        <span class="text-sm text-gray-500">Total: {{ $tagihan->total() }} Data</span>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                        <table class="w-full table-auto text-sm">
                            <thead class="bg-gray-50">
                                <tr class="text-gray-700">
                                    <th class="px-4 py-3 text-center">No</th>
                                    <th class="px-4 py-3">Nama Pelanggan</th>
                                    <th class="px-4 py-3">Bulan Tahun</th>
                                    <th class="px-4 py-3">Status Pembayaran</th>
                                    <th class="px-4 py-3">Jatuh Tempo</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($tagihan as $key => $t)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center">{{ $tagihan->firstItem() + $key }}</td>
                                        <td class="px-4 py-3">{{ $t->pelanggan->user->name }}</td>
                                        <td class="px-4 py-3">{{ date('F Y', strtotime($t->bulan_tahun)) }}</td>
                                        <td class="px-4 py-3">
                                            {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                                        </td>
                                        <td class="px-4 py-3">{{ $t->jatuh_tempo }}</td>
                                        <td class="px-4 py-3 text-center space-x-1">
                                            <button onclick="openEditModal({{ json_encode($t) }})"
                                                class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded-md hover:bg-yellow-200 text-xs">
                                                ✏️
                                            </button>
                                            <button
                                                onclick="deleteTagihan('{{ $t->id }}','{{ $t->pelanggan->user->name }}')"
                                                class="px-2 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-xs">
                                                🗑️
                                            </button>
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

                <!-- Mobile Card List -->
                <div class="sm:hidden space-y-4">
                    @foreach ($tagihan as $key => $t)
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $t->pelanggan->user->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ date('F Y', strtotime($t->bulan_tahun)) }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 {{ $t->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                                </span>
                            </div>
                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-700">
                                <div><span class="font-medium">Jatuh Tempo:</span> {{ $t->jatuh_tempo }}</div>
                            </div>
                            <div class="mt-3 flex space-x-2 justify-end">
                                <button onclick="openEditModal({{ json_encode($t) }})"
                                    class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-md text-xs">
                                    ✏️ Edit
                                </button>
                                <button
                                    onclick="deleteTagihan('{{ $t->id }}','{{ $t->pelanggan->user->name }}')"
                                    class="px-3 py-1 bg-red-100 text-red-600 rounded-md text-xs">
                                    🗑️ Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                    <div class="pt-2">
                        {{ $tagihan->links() }}
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

                    <div class="p-6 border-b bg-red-50 rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-red-600">Tambah Pelanggan Baru</h3>
                            <p class="text-sm text-red-400 mt-1">Isi semua bidang yang diperlukan (*)</p>
                        </div>
                        <button onclick="toggleModal('createModal')"
                            class="text-red-500 hover:text-red-700 text-2xl p-2">✕</button>
                    </div>
                    <form action="{{ route('tagihan.store') }}" method="post"
                        class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Pelanggan <span
                                            class="text-red-500">*</span></label>
                                    <select name="pelanggan_id" required
                                        class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500">
                                        <option value="">👤 Pilih Pelanggan</option>
                                        @foreach ($pelanggan as $p)
                                            <option value="{{ $p->id }}">👤 {{ $p->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Bulan Tahun <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            📅</div>
                                        <x-text-input type="month" name="bulan_tahun" required
                                            class="w-full pl-10 p-2 rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500" />
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Status Pembayaran <span
                                            class="text-red-500">*</span></label>
                                    <select name="status_pembayaran" required
                                        class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500">
                                        <option value="belum_dibayar">Belum Dibayar</option>
                                        <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                        <option value="lunas">Lunas</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Jatuh Tempo <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            📅</div>
                                        <x-text-input type="date" name="jatuh_tempo" required
                                            class="w-full pl-10 p-2 rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500" />
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <h3 class="text-xl font-bold text-red-600">Edit Tagihan</h3>
                            <p class="text-sm text-red-400 mt-1">Perbarui data Tagihan</p>
                        </div>
                        <button onclick="toggleModal('editModal')"
                            class="text-red-500 hover:text-red-700 text-2xl p-2">✕</button>
                    </div>
                    <!-- Form -->
                    <form id="editForm" method="POST" class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        @method('PUT')
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Pelanggan <span
                                        class="text-red-500">*</span></label>
                                <select name="pelanggan_id" required
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500 mt-1">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach ($pelanggan as $p)
                                        <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Bulan Tahun <span
                                        class="text-red-500">*</span></label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅</div>
                                    <x-text-input id="edit_bulan_tahun" type="month" name="bulan_tahun" required
                                        class="w-full pl-10 p-2 rounded-lg border-gray-200  focus:border-red-500 focus:ring-red-500" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Status Pembayaran <span
                                        class="text-red-500">*</span></label>
                                <select id="edit_status_pembayaran" name="status_pembayaran" required
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500 ">
                                    <option value="belum_dibayar">Belum Dibayar</option>
                                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Jatuh Tempo <span
                                        class="text-red-500">*</span></label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅</div>
                                    <x-text-input id="edit_jatuh_tempo" type="date" name="jatuh_tempo" required
                                        class="w-full pl-10 p-2 rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500" />
                                </div>

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
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Toggle any modal by ID
            function toggleModal(modalId) {
                document.getElementById(modalId).classList.toggle('hidden');
            }

            // Open the Edit Tagihan modal and populate fields
            function openEditModal(tagihan) {
                const form = document.getElementById('editForm');
                const modal = document.getElementById('editModal');

                form.elements['pelanggan_id'].value = tagihan.pelanggan_id;
                form.elements['bulan_tahun'].value = tagihan.bulan_tahun;
                form.elements['status_pembayaran'].value = tagihan.status_pembayaran;
                form.elements['jatuh_tempo'].value = tagihan.jatuh_tempo;

                // Update action URL
                form.setAttribute('action', `/tagihan/${tagihan.id}`);

                // Show modal
                modal.classList.remove('hidden');
            }

            // Delete confirmation & request for a Tagihan
            async function deleteTagihan(id, name) {
                const result = await Swal.fire({
                    title: `Hapus tagihan ${name}?`,
                    text: "Data tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!'
                });

                if (!result.isConfirmed) return;

                try {
                    await fetch(`/tagihan/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    });
                    await Swal.fire('Terhapus!', 'Tagihan berhasil dihapus.', 'success');
                    setTimeout(() => location.reload(), 1200);
                } catch (err) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                    console.error(err);
                }
            }
        </script>

</x-app-layout>
