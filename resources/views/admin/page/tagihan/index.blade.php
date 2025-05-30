<x-app-layout>
    <!-- Custom Scrollbar CSS -->
    <style>
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
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-[var(--primary-dark)]">
                <span
                    class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-bg)] bg-clip-text text-transparent">
                    Tagihan
                </span>
            </h2>
            <div class="hidden sm:flex items-center space-x-2">
                <span class="text-sm text-[var(--primary-dark)]">{{ today()->format('F Y') }}</span>
                <button onclick="toggleModal('createModal')"
                    class="bg-[var(--accent-red)] text-[var(--light-gray)] px-4 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-colors transform hover:scale-105">
                    ➕ Tambah
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-[var(--light-gray)]">
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

        <!-- Tabel Tagihan -->
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--primary-bg)] p-6"
            data-aos="fade-up">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h3 class="text-lg font-semibold text-[var(--primary-dark)]">Daftar Tagihan</h3>
                <span class="text-sm text-[var(--primary-dark)] mt-2 md:mt-0">Total: {{ $tagihan->total() }} Data</span>
            </div>

            <!-- Search & Entries -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
                <form method="GET" action="{{ route('tagihanAdmin.index') }}" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama/email/alamat..."
                        class="w-full sm:w-64 px-4 py-2 rounded-lg border border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]" />
                    <button type="submit"
                        class="bg-[var(--accent-red)] text-[var(--light-gray)] px-4 py-2 rounded-lg hover:bg-[var(--primary-bg)]">Cari</button>
                </form>

                <form method="GET" action="{{ route('tagihanAdmin.index') }}" class="flex items-center space-x-2">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <label for="entries" class="text-sm text-[var(--primary-dark)]">Tampilkan:</label>
                    <select name="entries" onchange="this.form.submit()"
                        class="w-20 px-2 py-1 border border-[var(--primary-bg)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--accent-red)]">
                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-[var(--primary-dark)]">data</span>
                </form>
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-lg border border-[var(--primary-bg)]">
                <table class="w-full table-auto text-sm">
                    <thead class="bg-[var(--light-gray)]">
                        <tr class="text-[var(--primary-dark)]">
                            <th class="px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3">Nama Pelanggan</th>
                            <th class="px-4 py-3">Bulan Tahun</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Status Pembayaran</th>
                            <th class="px-4 py-3">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--primary-bg)]">
                        @foreach ($tagihan as $key => $t)
                            <tr class="hover:bg-[var(--light-gray)] transition-colors">
                                <td class="px-4 py-3 text-center">{{ $tagihan->firstItem() + $key }}</td>
                                <td class="px-4 py-3">{{ $t->pelanggan->user->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    {{ \Carbon\Carbon::create()->month($t->bulan)->format('F') }} {{ $t->tahun }}
                                </td>
                                <td class="px-4 py-3 text-center">Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs {{ $t->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-600' : ($t->status_pembayaran == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                        {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $t->jatuh_tempo }}</td>
                                <td class="px-4 py-3 text-center space-x-1">
                                    <button onclick="openEditModal({{ json_encode($t) }})"
                                        class="px-2 py-1 bg-[var(--light-gray)] text-[var(--primary-bg)] rounded-md hover:bg-[var(--primary-bg)] hover:text-[var(--light-gray)] text-xs">
                                        ✏️
                                    </button>
                                    <button
                                        onclick="deleteTagihan('{{ $t->id }}','{{ $t->pelanggan->user->name }}')"
                                        class="px-2 py-1 bg-[var(--light-gray)] text-[var(--accent-red)] rounded-md hover:bg-[var(--accent-red)] hover:text-[var(--light-gray)] text-xs">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 flex justify-end">
                    {{ $tagihan->appends(['entries' => request('entries'), 'search' => request('search')])->links() }}
                </div>
            </div>

            <!-- Mobile Card List -->
            <div class="sm:hidden space-y-4">
                @foreach ($tagihan as $key => $t)
                    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-lg border border-[var(--primary-bg)] p-4"
                        data-aos="fade-up" data-aos-delay="{{ $key * 100 }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-[var(--primary-dark)]">{{ $t->pelanggan->user->name }}
                                </h4>
                                <p class="text-xs text-[var(--primary-dark)]">
                                    {{ \Carbon\Carbon::create()->month($t->bulan)->format('F') }} {{ $t->tahun }}
                                </p>
                            </div>
                            <span
                                class="px-2 py-1 rounded-full text-xs {{ $t->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-600' : ($t->status_pembayaran == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                            </span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-[var(--primary-dark)]">
                            <div><span class="font-medium">Harga:</span> Rp {{ number_format($t->harga, 0, ',', '.') }}
                            </div>
                            <div><span class="font-medium">Jatuh Tempo:</span> {{ $t->jatuh_tempo }}
                            </div>
                        </div>
                        <div class="mt-3 flex space-x-2 justify-end">
                            <button onclick="openEditModal({{ json_encode($t) }})"
                                class="px-3 py-1 bg-[var(--light-gray)] text-[var(--primary-bg)] rounded-md hover:bg-[var(--primary-bg)] hover:text-[var(--light-gray)] text-xs">
                                ✏️ Edit
                            </button>
                            <button onclick="deleteTagihan('{{ $t->id }}','{{ $t->pelanggan->user->name }}')"
                                class="px-3 py-1 bg-[var(--light-gray)] text-[var(--accent-red)] rounded-md hover:bg-[var(--accent-red)] hover:text-[var(--light-gray)] text-xs">
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

        <!-- Floating Button (mobile only) -->
        <button onclick="toggleModal('createModal')"
            class="fixed bottom-4 right-4 w-14 h-14 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-full shadow-lg flex items-center justify-center hover:bg-[var(--primary-bg)] sm:hidden transform hover:scale-110 transition-transform">
            ➕
        </button>

        <!-- Create Modal -->
        <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-2xl max-h-[calc(100vh-4rem)] bg-white rounded-2xl shadow-xl flex flex-col">
                    <div class="p-6 border-b bg-[var(--light-gray)] rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--accent-red)]">Tambah Tagihan Baru</h3>
                            <p class="text-sm text-[var(--primary-dark)] mt-1">Isi semua bidang yang diperlukan (*)</p>
                        </div>
                        <button onclick="toggleModal('createModal')"
                            class="text-[var(--accent-red)] hover:text-[var(--primary-bg)] text-2xl p-2">✕</button>
                    </div>
                    <form action="{{ route('tagihanAdmin.store') }}" method="post"
                        class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-[var(--primary-dark)]">Pelanggan <span
                                            class="text-[var(--accent-red)]">*</span></label>
                                    <select name="pelanggan_id" required
                                        class="w-full rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]">
                                        <option value="">👤 Pilih Pelanggan</option>
                                        @foreach ($pelanggan as $p)
                                            <option value="{{ $p->id }}">👤 {{ $p->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-[var(--primary-dark)]">Bulan <span
                                            class="text-[var(--accent-red)]">*</span></label>
                                    <select name="bulan" required
                                        class="w-full rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">
                                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-[var(--primary-dark)]">Tahun <span
                                            class="text-[var(--accent-red)]">*</span></label>
                                    <input type="number" name="tahun" required
                                        class="w-full p-2 rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]"
                                        value="{{ now()->year }}" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-[var(--primary-dark)]">Status
                                        Pembayaran <span class="text-[var(--accent-red)]">*</span></label>
                                    <select name="status_pembayaran" required
                                        class="w-full rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]">
                                        <option value="belum_dibayar">Belum Dibayar</option>
                                        <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                        <option value="lunas">Lunas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">Jatuh Tempo <span
                                        class="text-[var(--accent-red)]">*</span></label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅
                                    </div>
                                    <x-text-input type="date" name="jatuh_tempo" required
                                        class="w-full pl-10 p-2 rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]" />
                                </div>
                            </div>
                        </div>
                        <div class="p-6 border-t bg-[var(--light-gray)] rounded-b-2xl flex justify-end space-x-3">
                            <button type="button" onclick="toggleModal('createModal')"
                                class="px-6 py-2 text-[var(--primary-dark)] hover:bg-[var(--primary-bg)]/20 rounded-lg">Batal</button>
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

        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-2xl max-h-[calc(100vh-4rem)] bg-white rounded-2xl shadow-xl flex flex-col">
                    <div class="p-6 border-b bg-[var(--light-gray)] rounded-t-2xl flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-[var(--accent-red)]">Edit Tagihan</h3>
                            <p class="text-sm text-[var(--primary-dark)] mt-1">Perbarui data tagihan</p>
                        </div>
                        <button onclick="toggleModal('editModal')"
                            class="text-[var(--accent-red)] hover:text-[var(--primary-bg)] text-2xl p-2">✕</button>
                    </div>
                    <form id="editForm" method="POST" class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        @method('PUT')
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">Pelanggan <span
                                        class="text-[var(--accent-red)]">*</span></label>
                                <select id="edit_pelanggan_id" name="pelanggan_id" required
                                    class="w-full rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach ($pelanggan as $p)
                                        <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-[var(--primary-dark)]">Bulan <span
                                            class="text-[var(--accent-red)]">*</span></label>
                                    <select id="edit_bulan" name="bulan" required
                                        class="w-full rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">
                                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-[var(--primary-dark)]">Tahun <span
                                            class="text-[var(--accent-red)]">*</span></label>
                                    <input id="edit_tahun" type="number" name="tahun" required
                                        class="w-full p-2 rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">Status Pembayaran
                                    <span class="text-[var(--accent-red)]">*</span></label>
                                <select id="edit_status_pembayaran" name="status_pembayaran" required
                                    class="w-full rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]">
                                    <option value="belum_dibayar">Belum Dibayar</option>
                                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[var(--primary-dark)]">Jatuh Tempo <span
                                        class="text-[var(--accent-red)]">*</span></label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        📅
                                    </div>
                                    <x-text-input id="edit_jatuh_tempo" type="date" name="jatuh_tempo" required
                                        class="w-full pl-10 p-2 rounded-lg border-[var(--primary-bg)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]" />
                                </div>
                            </div>
                        </div>
                        <div class="p-6 border-t bg-[var(--light-gray)] rounded-b-2xl flex justify-end space-x-3">
                            <button type="button" onclick="toggleModal('editModal')"
                                class="px-6 py-2 text-[var(--primary-dark)] hover:bg-[var(--primary-bg)]/20 rounded-lg">Batal</button>
                            <button type="submit"
                                class="px-6 py-2 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--primary-bg)] flex items-center">
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

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 120
        });

        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        function openEditModal(tagihan) {
            const form = document.getElementById('editForm');
            form.action = `/tagihanAdmin/${tagihan.id}`;
            form.elements['pelanggan_id'].value = tagihan.pelanggan_id;
            form.elements['bulan'].value = tagihan.bulan;
            form.elements['tahun'].value = tagihan.tahun;
            form.elements['status_pembayaran'].value = tagihan.status_pembayaran;
            form.elements['jatuh_tempo'].value = tagihan.jatuh_tempo; // Format date for input
            document.getElementById('editModal').classList.remove('hidden');
        }

        async function deleteTagihan(id, name) {
            const result = await Swal.fire({
                title: `Hapus tagihan ${name}?`,
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--accent-red)',
                cancelButtonColor: 'var(--primary-bg)',
                confirmButtonText: 'Ya, Hapus!'
            });

            if (!result.isConfirmed) return;

            try {
                await fetch(`/tagihanAdmin/${id}`, {
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

    <style>
        :root {
            --primary-dark: #041562;
            --primary-bg: #11468F;
            --accent-red: #DA1212;
            --light-gray: #EEEEEE;
        }
    </style>
</x-app-layout>
