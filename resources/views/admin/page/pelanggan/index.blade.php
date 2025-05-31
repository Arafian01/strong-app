<x-app-layout>
    <!-- Custom Scrollbar CSS -->
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
                    Manajemen Pelanggan
                </span>
            </h2>
            <div class="hidden sm:flex items-center space-x-2">
                <span class="text-sm text-[var(--primary-dark)]">{{ today()->format('F Y') }}</span>
                <button onclick="toggleModal('createModal')"
                    class="bg-[var(--accent-red)] text-[var(--light-gray)] px-4 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-colors">
                    ➕ Tambah Pelanggan
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Floating Button (mobile only) -->
    <button onclick="toggleModal('createModal')"
        class="fixed bottom-4 right-4 w-14 h-14 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-full shadow-lg flex items-center justify-center hover:bg-[var(--primary-bg)] sm:hidden z-50">
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
                    confirmButtonColor: '#DA1212'
                });
            </script>
        @endif
        @if (Session::has('error_message'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ Session::get('error_message') }}',
                    confirmButtonColor: '#DA1212'
                });
            </script>
        @endif

        <!-- Tabel Pelanggan -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--primary-bg)] p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h3 class="text-lg font-semibold text-[var(--primary-dark)]">Daftar Pelanggan</h3>
                <span class="text-sm text-[var(--primary-dark)] mt-2 md:mt-0">Total: {{ $pelanggan->total() }} Data</span>
            </div>

            <!-- Search & Entries -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('pelanggan.index') }}" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama/email/alamat..."
                        class="w-full sm:w-64 px-4 py-2 rounded-lg border border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                    <button type="submit"
                        class="bg-[var(--accent-red)] text-[var(--light-gray)] px-4 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-colors">
                        Cari
                    </button>
                </form>

                <form method="GET" action="{{ route('pelanggan.index') }}" class="flex items-center space-x-2">
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
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-lg border border-[var(--light-gray)]">
                <table class="w-full table-auto text-sm">
                    <thead class="bg-[var(--light-gray)]">
                        <tr class="text-[var(--primary-dark)]">
                            <th class="px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3 text-center">Paket</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3">Telepon</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--light-gray)]">
                        @foreach ($pelanggan as $key => $p)
                            <tr class="hover:bg-[var(--light-gray)] transition-colors">
                                <td class="px-4 py-3 text-center text-[var(--primary-dark)]">{{ $pelanggan->firstItem() + $key }}</td>
                                <td class="px-4 py-3 text-[var(--primary-dark)]">{{ $p->user->name }}</td>
                                <td class="px-4 py-3 text-[var(--primary-dark)]">{{ $p->user->email }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-[var(--primary-bg)] text-[var(--light-gray)] rounded-full">
                                        {{ $p->paket->nama_paket }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 {{ $p->status == 'aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-[var(--primary-dark)]">{{ Str::limit($p->alamat, 30) }}</td>
                                <td class="px-4 py-3 text-[var(--primary-dark)]">{{ $p->telepon }}</td>
                                <td class="px-4 py-3 text-center space-x-1">
                                    <button onclick="openEditModal({{ $p }})"
                                        class="px-2 py-1 bg-[var(--primary-bg)] text-[var(--light-gray)] rounded-md text-xs hover:bg-[var(--accent-red)]">
                                        ✏️
                                    </button>
                                    <button onclick="deletePelanggan('{{ $p->id }}','{{ $p->user->name }}')"
                                        class="px-2 py-1 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-md text-xs hover:bg-[var(--primary-dark)]">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="sm:hidden space-y-4">
                @foreach ($pelanggan as $key => $p)
                    <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--light-gray)] p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-[var(--primary-dark)]">{{ $p->user->name }}</h4>
                                <p class="text-xs text-[var(--primary-dark)]/70">{{ $p->user->email }}</p>
                            </div>
                            <span class="px-2 py-1 {{ $p->status == 'aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full text-xs">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-[var(--primary-dark)]">
                            <div><span class="font-medium">Paket:</span> {{ $p->paket->nama_paket }}</div>
                            <div><span class="font-medium">Telepon:</span> {{ $p->telepon }}</div>
                            <div class="col-span-2"><span class="font-medium">Alamat:</span> {{ Str::limit($p->alamat, 50) }}</div>
                        </div>
                        <div class="mt-3 flex space-x-2 justify-end">
                            <button onclick="openEditModal({{ $p }})"
                                class="px-3 py-1 bg-[var(--primary-bg)] text-[var(--light-gray)] rounded-md text-xs hover:bg-[var(--accent-red)]">
                                ✏️ Edit
                            </button>
                            <button onclick="deletePelanggan('{{ $p->id }}','{{ $p->user->name }}')"
                                class="px-3 py-1 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-md text-xs hover:bg-[var(--primary-dark)]">
                                🗑️ Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex justify-end">
                {{ $pelanggan->appends(['entries' => request('entries'), 'search' => request('search')])->links() }}
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl max-h-[calc(100vh-4rem)] bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-[var(--primary-bg)] flex flex-col">
                <!-- Header -->
                <div class="p-6 border-b bg-[var(--light-gray)] rounded-t-2xl flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-[var(--primary-dark)]">Tambah Pelanggan Baru</h3>
                        <p class="text-sm text-[var(--primary-dark)]/70 mt-1">Isi semua bidang yang diperlukan (*)</p>
                    </div>
                    <button onclick="toggleModal('createModal')" class="text-[var(--primary-dark)] hover:text-[var(--accent-red)] text-2xl p-2">
                        ✕
                    </button>
                </div>
                <!-- Body with Scroll -->
                <form action="{{ route('pelanggan.store') }}" method="post" class="flex-1 flex flex-col overflow-hidden">
                    @csrf
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                        <!-- Baris 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Nama Lengkap <span class="text-[var(--accent-red)]">*</span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            👤
                                        </div>
                                        <x-text-input name="name" required placeholder="John Doe"
                                            class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                    </div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Email <span class="text-[var(--accent-red)]">*</span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            ✉️
                                        </div>
                                        <x-text-input type="email" name="email" required placeholder="john@example.com"
                                            class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                    </div>
                                </label>
                            </div>
                        </div>
                        <!-- Baris 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Password <span class="text-[var(--accent-red)]">*</span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            🔒
                                        </div>
                                        <x-text-input type="password" name="password" required
                                            class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                    </div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Paket <span class="text-[var(--accent-red)]">*</span>
                                    <select name="paket_id"
                                        class="w-full rounded-lg border-[var(--light-gray)] mt-1 focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]"
                                        required>
                                        <option value="">Pilih Paket</option>
                                        @foreach ($paket as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_paket }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- Baris 3 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                Alamat <span class="text-[var(--accent-red)]">*</span>
                                <textarea name="alamat" rows="2" placeholder="Jl. Contoh No. 123..." required
                                    class="w-full rounded-lg border-[var(--light-gray)] mt-1 focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] p-2 text-[var(--primary-dark)]"></textarea>
                            </label>
                        </div>
                        <!-- Baris 4 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Telepon <span class="text-[var(--accent-red)]">*</span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            📞
                                        </div>
                                        <x-text-input name="telepon" required placeholder="0812-3456-7890"
                                            class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                    </div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Status <span class="text-[var(--accent-red)]">*</span>
                                    <select name="status"
                                        class="w-full rounded-lg border-[var(--light-gray)] mt-1 focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]"
                                        required>
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                        <option value="isolir">Isolir</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- Baris 5 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                Tanggal Langganan <span class="text-[var(--accent-red)]">*</span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅
                                    </div>
                                    <x-text-input type="date" name="tanggal_langganan" value="{{ date('Y-m-d') }}" required
                                        class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                </div>
                            </label>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
            <div class="w-full max-w-2xl max-h-[calc(100vh-4rem)] bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-[var(--primary-bg)] flex flex-col">
                <!-- Header -->
                <div class="p-6 border-b bg-[var(--light-gray)] rounded-t-2xl flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-[var(--primary-dark)]">Edit Pelanggan</h3>
                        <p class="text-sm text-[var(--primary-dark)]/70 mt-1">Perbarui data pelanggan</p>
                    </div>
                    <button onclick="toggleModal('editModal')" class="text-[var(--primary-dark)] hover:text-[var(--accent-red)] text-2xl p-2">
                        ✕
                    </button>
                </div>
                <!-- Body with Scroll -->
                <form method="POST" id="editForm" class="flex-1 flex flex-col overflow-hidden">
                    @csrf
                    @method('PUT')
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                        <!-- Baris 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Nama Lengkap <span class="text-[var(--accent-red)]">*</span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            👤
                                        </div>
                                        <x-text-input id="edit_name" name="name" required
                                            class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                    </div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Email <span class="text-[var(--accent-red)]">*</span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            ✉️
                                        </div>
                                        <x-text-input id="edit_email" type="email" name="email" required
                                            class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                    </div>
                                </label>
                            </div>
                        </div>
                        <!-- Baris 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Password Baru (Opsional)
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            🔒
                                        </div>
                                        <x-text-input id="edit_password" type="password" name="password"
                                            class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                    </div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Paket <span class="text-[var(--accent-red)]">*</span>
                                    <select id="edit_paket_id" name="paket_id"
                                        class="w-full rounded-lg border-[var(--light-gray)] mt-1 focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]"
                                        required>
                                        @foreach ($paket as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_paket }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- Baris 3 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                Alamat <span class="text-[var(--accent-red)]">*</span>
                                <textarea id="edit_alamat" name="alamat" rows="2"
                                    class="w-full rounded-lg border-[var(--light-gray)] mt-1 focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] p-2 text-[var(--primary-dark)]"
                                    required></textarea>
                            </label>
                        </div>
                        <!-- Baris 4 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Telepon <span class="text-[var(--accent-red)]">*</span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            📞
                                        </div>
                                        <x-text-input id="edit_telepon" name="telepon" required
                                            class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                    </div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                    Status <span class="text-[var(--accent-red)]">*</span>
                                    <select id="edit_status" name="status"
                                        class="w-full rounded-lg border-[var(--light-gray)] mt-1 focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]"
                                        required>
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                        <option value="isolir">Isolir</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- Baris 5 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[var(--primary-dark)]">
                                Tanggal Langganan <span class="text-[var(--accent-red)]">*</span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅
                                    </div>
                                    <x-text-input id="edit_tanggal" type="date" name="tanggal_langganan" required
                                        class="pl-10 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]" />
                                </div>
                            </label>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle Modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        // Open Edit Modal
        function openEditModal(pelanggan) {
            document.getElementById('edit_name').value = pelanggan.user.name;
            document.getElementById('edit_email').value = pelanggan.user.email;
            document.getElementById('edit_paket_id').value = pelanggan.paket_id;
            document.getElementById('edit_alamat').value = pelanggan.alamat;
            document.getElementById('edit_telepon').value = pelanggan.telepon;
            document.getElementById('edit_status').value = pelanggan.status;
            document.getElementById('edit_tanggal').value = pelanggan.tanggal_langganan.split(' ')[0];

            const form = document.getElementById('editForm');
            form.action = `/pelanggan/${pelanggan.id}`;
            toggleModal('editModal');
        }

        // Delete Confirmation
        async function deletePelanggan(id, name) {
            const confirmed = await Swal.fire({
                title: `Hapus ${name}?`,
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DA1212',
                cancelButtonColor: '#11468F',
                confirmButtonText: 'Ya, Hapus!'
            });

            if (confirmed.isConfirmed) {
                try {
                    await fetch(`/pelanggan/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    });

                    Swal.fire('Terhapus!', 'Data pelanggan telah dihapus', 'success', {
                        confirmButtonColor: '#DA1212'
                    });
                    setTimeout(() => location.reload(), 1500);
                } catch (error) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus', 'error', {
                        confirmButtonColor: '#DA1212'
                    });
                }
            }
        }
    </script>

</x-app-layout>
