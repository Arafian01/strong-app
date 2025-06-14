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
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-[var(--primary-dark)]">
                <span
                    class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-bg)] bg-clip-text text-transparent animate-pulse">
                    Manajemen Pembayaran
                </span>
            </h2>
        </div>
    </x-slot>

    <!-- Floating Button (mobile only) -->
    <button onclick="toggleModal('createModal')"
        class="fixed bottom-4 right-4 w-14 h-14 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-full shadow-lg flex items-center justify-center hover:bg-[var(--primary-bg)] sm:hidden z-50 transform hover:scale-110 transition-transform">
        ➕
    </button>

    <div class="py-6 px-4 sm:px-6 lg:px-8 pt-16 bg-gradient-to-b from-[var(--light-gray)] to-white">
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

        <!-- Tabel Pembayaran -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--primary-bg)] p-6"
            data-aos="fade-up">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h3 class="text-lg font-semibold text-[var(--primary-dark)]">Daftar Pembayaran</h3>
                {{-- <span class="text-sm text-[var(--primary-dark)] mt-2 md:mt-0">Total: {{ $pembayaran->total() }}
                    Data</span> --}}
            </div>

            <!-- Search & Entries -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('pembayaranAdmin.index') }}" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama/bulan..."
                        class="w-full sm:w-64 px-4 py-2 rounded-lg border border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                    <button type="submit"
                        class="bg-[var(--accent-red)] text-[var(--light-gray)] px-4 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-colors">
                        Cari
                    </button>
                </form>
                <div class="hidden sm:flex items-center space-x-2">
                    <span class="text-sm text-[var(--primary-dark)]">{{ today()->format('F Y') }}</span>
                    <button onclick="toggleModal('createModal')"
                        class="bg-[var(--accent-red)] text-[var(--light-gray)] px-4 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-colors transform hover:scale-105">
                        + Tambah Pembayaran
                    </button>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-lg border border-[var(--light-gray)]">
                <table class="w-full table-auto text-sm">
                    <thead class="bg-[var(--light-gray)]">
                        <tr class="text-[var(--primary-dark)]">
                            <th class="px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3">Nama Pelanggan</th>
                            <th class="px-4 py-3 text-center">Bulan Tahun</th>
                            <th class="px-4 py-3 text-center">Harga</th>
                            <th class="px-4 py-3 text-center">Tgl Kirim</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Tgl Verifikasi</th>
                            <th class="px-4 py-3">Bukti</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--light-gray)]">
                        @foreach ($pembayaran as $key => $p)
                            <tr class="hover:bg-[var(--light-gray)] transition-colors">
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">
                                    {{ $pembayaran->firstItem() + $key }}</td>
                                <td class="px-4 py-3 text-[var(--primary-dark)]">
                                    {{ $p->tagihan->pelanggan->user->name }}</td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">
                                    {{ \Carbon\Carbon::create()->month($p->tagihan->bulan)->format('F') }}
                                    {{ $p->tagihan->tahun }}
                                </td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">Rp
                                    {{ number_format($p->tagihan->pelanggan->paket->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">{{ $p->tanggal_kirim }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs {{ $p->status_verifikasi == 'diterima' ? 'bg-green-100 text-green-600' : ($p->status_verifikasi == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                        {{ ucfirst(str_replace('_', ' ', $p->status_verifikasi)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">
                                    {{ $p->tanggal_verifikasi ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <img src="{{ asset('pembayaran_images/' . $p->image) }}" alt="Bukti"
                                        class="w-16 h-16 object-cover rounded" />
                                </td>
                                <td class="px-4 py-3 text-center space-x-1">
                                    <button onclick="openEditModal({{ json_encode($p) }})"
                                        class="px-2 py-1 bg-[var(--primary-bg)] text-[var(--light-gray)] rounded-md text-xs hover:bg-[var(--accent-red)]">
                                        ✏️
                                    </button>
                                    <button
                                        onclick="deletePembayaran('{{ $p->id }}','{{ $p->tagihan->pelanggan->user->name }}')"
                                        class="px-2 py-1 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-md text-xs hover:bg-[var(--primary-dark)]">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="mt-4 flex justify-end">
                    {{ $pembayaran->appends(['entries' => request('entries'), 'search' => request('search')])->links() }}
                </div> --}}
            </div>

            <!-- Mobile Card List -->
            <div class="sm:hidden space-y-4">
                @foreach ($pembayaran as $key => $p)
                    <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--light-gray)] p-4"
                        data-aos="fade-up" data-aos-delay="{{ $key * 100 }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-[var(--primary-dark)]">
                                    {{ $p->tagihan->pelanggan->user->name }}</h4>
                                <p class="text-xs text-[var(--primary-dark)]/70">
                                    {{ \Carbon\Carbon::create()->month($p->tagihan->bulan)->format('F') }}
                                    {{ $p->tagihan->tahun }}
                                </p>
                            </div>
                            <span
                                class="px-2 py-1 rounded-full text-xs {{ $p->status_verifikasi == 'diterima' ? 'bg-green-100 text-green-600' : ($p->status_verifikasi == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                {{ ucfirst(str_replace('_', ' ', $p->status_verifikasi)) }}
                            </span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-[var(--primary-dark)]">
                            <div><span class="font-medium">Harga:</span> Rp
                                {{ number_format($p->tagihan->pelanggan->paket->harga, 0, ',', '.') }}</div>
                            <div><span class="font-medium">Tgl Kirim:</span> {{ $p->tanggal_kirim }}</div>
                            <div><span class="font-medium">Tgl Verifikasi:</span> {{ $p->tanggal_verifikasi ?? '-' }}
                            </div>
                        </div>
                        <div class="mt-2">
                            <img src="{{ asset('pembayaran_images/' . $p->image) }}" alt="Bukti"
                                class="w-full h-32 object-cover rounded" />
                        </div>
                        <div class="mt-3 flex space-x-2 justify-end">
                            <button onclick="openEditModal({{ json_encode($p) }})"
                                class="px-3 py-1 bg-[var(--primary-bg)] text-[var(--light-gray)] rounded-md text-xs hover:bg-[var(--accent-red)]">
                                ✏️ Edit
                            </button>
                            <button
                                onclick="deletePembayaran('{{ $p->id }}','{{ $p->tagihan->pelanggan->user->name }}')"
                                class="px-3 py-1 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-md text-xs hover:bg-[var(--primary-dark)]">
                                🗑️ Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
                {{-- <div class="pt-2">
                    {{ $pembayaran->appends(['entries' => request('entries'), 'search' => request('search')])->links() }}
                </div> --}}

            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('tagihanAdmin.index') }}" class="flex items-center space-x-2">
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

        <!-- Create Modal -->
        <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div
                    class="w-full max-w-2xl max-h-[calc(100vh-4rem)] bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-[var(--primary-bg)] flex flex-col">
                    <!-- Header -->
                    <div class="p-6 border-b bg-[var(--light-gray)] rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--primary-dark)]">Tambah Pembayaran Baru</h3>
                            <p class="text-sm text-[var(--primary-dark)]/70 mt-1">Isi semua bidang yang diperlukan (*)
                            </p>
                        </div>
                        <button onclick="toggleModal('createModal')"
                            class="text-[var(--primary-dark)] hover:text-[var(--accent-red)] text-2xl p-2">
                            ✕
                        </button>
                    </div>
                    <!-- Body with Scroll -->
                    <form id="createForm" action="{{ route('pembayaranAdmin.store') }}" method="POST"
                        enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
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
                                                <span class="text-[var(--primary-dark)]/70">No image selected</span>
                                            </div>
                                            <label for="image-input-create"
                                                class="absolute bottom-2 right-2 bg-white p-2 rounded-full shadow-lg cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-6 w-6 text-[var(--primary-dark)]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
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
                            <!-- Pilih Tagihan -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Tagihan <span class="text-[var(--accent-red)]">*</span>
                                </label>
                                <select name="tagihan_id" required
                                    class="w-full rounded-lg border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]">
                                    <option value="">Pilih Tagihan...</option>
                                    @foreach ($tagihan as $t)
                                        @if ($t->status_pembayaran == 'belum_dibayar' || $t->status_pembayaran == 'ditolak')
                                            <option value="{{ $t->id }}">
                                                {{ $t->pelanggan->user->name }},
                                                {{ \Carbon\Carbon::create()->month($t->bulan)->format('F') }}
                                                {{ $t->tahun }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tanggal Kirim -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Tanggal Kirim <span class="text-[var(--accent-red)]">*</span>
                                </label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅
                                    </div>
                                    <x-text-input type="date" name="tanggal_kirim" value="{{ date('Y-m-d') }}"
                                        required
                                        class="pl-10 w-full p-2 rounded-lg border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                </div>
                            </div>
                            <!-- Status Verifikasi -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Status Verifikasi <span class="text-[var(--accent-red)]">*</span>
                                </label>
                                <select name="status_verifikasi" required
                                    class="w-full rounded-lg border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]">
                                    <option value="">Pilih Status...</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <!-- Footer (sticky) -->
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
                                </svg>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div
                    class="w-full max-w-2xl max-h-[calc(100vh-4rem)] bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-[var(--primary-bg)] flex flex-col">
                    <!-- Header -->
                    <div class="p-6 border-b bg-[var(--light-gray)] rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--primary-dark)]">Edit Pembayaran</h3>
                            <p class="text-sm text-[var(--primary-dark)]/70 mt-1">Perbarui data pembayaran</p>
                        </div>
                        <button onclick="toggleModal('editModal')"
                            class="text-[var(--primary-dark)] hover:text-[var(--accent-red)] text-2xl p-2">
                            ✕
                        </button>
                    </div>
                    <!-- Body with Scroll -->
                    <form id="editForm" method="POST" enctype="multipart/form-data"
                        class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        @method('PUT')
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            <!-- Upload & Preview -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Bukti Pembayaran
                                </label>
                                <div class="flex items-center justify-center">
                                    <div class="bg-white p-6 rounded-xl shadow-lg">
                                        <div class="relative w-64 h-64">
                                            <div id="image-preview-edit"
                                                class="w-full h-full bg-[var(--light-gray)] rounded-xl overflow-hidden flex items-center justify-center">
                                                <span class="text-[var(--primary-dark)]/70">No image</span>
                                            </div>
                                            <label for="image-input-edit"
                                                class="absolute bottom-2 right-2 bg-white p-2 rounded-full shadow-lg cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-6 w-6 text-[var(--primary-dark)]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </label>
                                            <input type="file" id="image-input-edit" name="image"
                                                accept="image/*" class="hidden">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tagihan -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Tagihan <span class="text-[var(--accent-red)]">*</span>
                                </label>
                                <select name="tagihan_id" id="edit_tagihan_id" required
                                    class="w-full rounded-lg border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]">
                                    <option value="">Pilih Tagihan...</option>
                                    @foreach ($tagihan as $t)
                                        <option value="{{ $t->id }}">
                                            {{ $t->pelanggan->user->name }},
                                            {{ \Carbon\Carbon::create()->month($t->bulan)->format('F') }}
                                            {{ $t->tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tanggal Kirim -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Tanggal Kirim <span class="text-[var(--accent-red)]">*</span>
                                </label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅
                                    </div>
                                    <x-text-input type="date" id="edit_tanggal_kirim" name="tanggal_kirim"
                                        required
                                        class="pl-10 w-full p-2 rounded-lg border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                </div>
                            </div>
                            <!-- Status Verifikasi -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Status Verifikasi <span class="text-[var(--accent-red)]">*</span>
                                </label>
                                <select id="edit_status_verifikasi" name="status_verifikasi" required
                                    class="w-full rounded-lg border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]">
                                    <option value="">Pilih Status...</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <!-- Footer (sticky) -->
                        <div class="p-6 border-t bg-[var(--light-gray)] rounded-b-2xl flex justify-end space-x-3">
                            <button type="button" onclick="toggleModal('editModal')"
                                class="px-6 py-2 text-[var(--primary-dark)] hover:bg-[var(--light-gray)]/80 rounded-lg">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--primary-bg)] flex items-center">
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
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 120
        });

        // Toggle modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        // Preview image on Create
        document.getElementById('image-input-create').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview-create');
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="w-full h-full object-cover rounded-xl" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span class="text-[var(--primary-dark)]/70">No image selected</span>';
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
                        `<img src="${e.target.result}" class="w-full h-full object-cover rounded-xl" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span class="text-[var(--primary-dark)]/70">No image</span>';
            }
        });

        // Open Edit Modal
        function openEditModal(pembayaran) {
            const form = document.getElementById('editForm');
            form.action = `/pembayaranAdmin/${pembayaran.id}`;
            document.getElementById('edit_tagihan_id').value = pembayaran.tagihan_id;
            document.getElementById('edit_tanggal_kirim').value = pembayaran.tanggal_kirim;
            document.getElementById('edit_status_verifikasi').value = pembayaran.status_verifikasi;
            const imgPrev = document.getElementById('image-preview-edit');
            imgPrev.innerHTML =
                `<img src="{{ asset('pembayaran_images') }}/${pembayaran.image}" class="w-full h-full object-cover rounded-xl" alt="Bukti">`;
            toggleModal('editModal');
        }

        // Delete Pembayaran
        async function deletePembayaran(id, name) {
            const confirmed = await Swal.fire({
                title: `Hapus pembayaran ${name}?`,
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--accent-red)',
                cancelButtonColor: 'var(--primary-bg)',
                confirmButtonText: 'Ya, Hapus!'
            });

            if (confirmed.isConfirmed) {
                try {
                    const response = await fetch(`/pembayaranAdmin/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    });

                    if (!response.ok) {
                        const data = await response.json();
                        throw new Error(data.message || 'Gagal menghapus');
                    }

                    Swal.fire('Terhapus!', 'Pembayaran berhasil dihapus', 'success', {
                        confirmButtonColor: 'var(--accent-red)'
                    });
                    setTimeout(() => location.reload(), 1500);
                } catch (error) {
                    Swal.fire('Gagal!', error.message || 'Terjadi kesalahan saat menghapus', 'error', {
                        confirmButtonColor: 'var(--accent-red)'
                    });
                }
            }
        }
    </script>
</x-app-layout>
