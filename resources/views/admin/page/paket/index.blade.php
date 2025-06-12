<x-app-layout>
    <x-slot name="header" class="z-[5]">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-[var(--primary-dark)]">
                <span class="bg-gradient-to-r from-[var(--accent-red)] to-[var(--primary-bg)] bg-clip-text text-transparent animate-pulse">
                    Manajemen Paket
                </span>
            </h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-[var(--primary-dark)]">{{ today()->format('d F Y') }}</span>
                <div class="w-10 h-10 bg-[var(--accent-red)] rounded-full flex items-center justify-center shadow-md">
                    <span class="text-[var(--light-gray)] text-lg font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Floating Button Mobile -->
    <button class="md:hidden fixed bottom-6 right-6 bg-[var(--accent-red)] text-[var(--light-gray)] p-4 rounded-full shadow-lg z-50 hover:bg-[var(--primary-bg)] transition-all transform hover:scale-110"
            onclick="toggleModal('createModal')">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[var(--light-gray)] to-white">
        <!-- Notifikasi -->
        @if(Session::has('message_insert'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ Session::get("message_insert") }}',
                timer: 3000
            })
        </script>
        @endif

        @if(Session::has('error_message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ Session::get("error_message") }}'
            })
        </script>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Desktop -->
            <div class="hidden md:block bg-white/95 backdrop-blur-lg rounded-xl p-8 shadow-lg border border-[var(--primary-bg)] transform transition-all duration-300 hover:shadow-2xl" data-aos="fade-right">
                <form action="{{ route('paket.store') }}" method="post" class="space-y-6">
                    @csrf
                    <div class="mb-4">
                        <h3 class="text-xl font-semibold text-[var(--primary-dark)]">Tambah Paket Baru</h3>
                    </div>
                    
                    <div>
                        <x-input-label for="nama" value="Nama Paket" class="text-[var(--primary-dark)] font-medium" />
                        <x-text-input 
                            id="nama" 
                            name="nama" 
                            type="text"
                            class="mt-1 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] rounded-lg"
                            placeholder="Contoh: Paket Internet 100Mbps"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label for="harga" value="Harga Paket" class="text-[var(--primary-dark)] font-medium" />
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--primary-dark)]">Rp</span>
                            <x-text-input 
                                id="harga" 
                                name="harga" 
                                type="number"
                                class="w-full pl-8 border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] rounded-lg"
                                placeholder="500000"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="deskripsi" value="Deskripsi" class="text-[var(--primary-dark)] font-medium" />
                        <textarea 
                            id="deskripsi" 
                            name="deskripsi" 
                            rows="4"
                            class="mt-1 w-full rounded-lg border-[var(--light-gray)] shadow-sm focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]"
                            placeholder="Masukkan deskripsi lengkap paket"
                            required
                        ></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-[var(--accent-red)] text-[var(--light-gray)] px-6 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-all transform hover:scale-105">
                            Simpan Paket
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Paket -->
            <div class="bg-white/95 backdrop-blur-lg rounded-xl p-8 shadow-lg border border-[var(--primary-bg)]" data-aos="fade-left">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-[var(--primary-dark)]">Daftar Paket</h3>
                    <span class="text-sm text-[var(--primary-dark)]">Total: {{ $paket->total() }} paket</span>
                </div>

                <form method="GET" action="{{ route('paket.index') }}" class="mb-6">
                    <div class="flex gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari nama, deskripsi, atau harga..." 
                               class="w-full px-4 py-2 rounded-lg border border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] text-[var(--primary-dark)]">
                        <button type="submit" class="bg-[var(--accent-red)] text-[var(--light-gray)] px-4 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-all">
                            Cari
                        </button>
                    </div>
                </form> 

                <div class="overflow-x-auto rounded-lg border border-[var(--light-gray)]">
                    <table class="w-full">
                        <thead class="bg-[var(--light-gray)]">
                            <tr>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-[var(--primary-dark)]">No</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-[var(--primary-dark)]">Nama Paket</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-[var(--primary-dark)]">Harga</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-[var(--primary-dark)]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--light-gray)]">
                            @foreach ($paket as $key => $p)
                            <tr class="hover:bg-[var(--light-gray)] transition-colors">
                                <td class="px-4 py-3 text-sm text-[var(--primary-dark)] text-center">
                                    {{ $paket->perPage() * ($paket->currentPage() - 1) + $key + 1 }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    <div class="font-medium text-[var(--primary-dark)]">{{ $p->nama_paket }}</div>
                                    <div class="text-sm text-[var(--primary-dark)]/70 mt-1">{{ Str::limit($p->deskripsi, 50) }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-[var(--primary-dark)] text-center">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 flex justify-center space-x-2">
                                    <button 
                                        data-id="{{ $p->id }}"
                                        data-nama="{{ $p->nama_paket }}"
                                        data-harga="{{ $p->harga }}"
                                        data-deskripsi="{{ $p->deskripsi }}"
                                        onclick="openEditModal(this)"
                                        class="px-3 py-1 bg-[var(--primary-bg)] text-[var(--light-gray)] rounded-md hover:bg-[var(--accent-red)] transition-colors text-sm">
                                        ✏️ Edit
                                    </button>
                                    <button 
                                        onclick="deletePaket('{{ $p->id }}','{{ $p->nama_paket }}')"
                                        class="px-3 py-1 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-md hover:bg-[var(--primary-dark)] transition-colors text-sm">
                                        🗑️ Hapus
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <!-- Entries Per Page -->
                    <form method="GET" action="{{ route('paket.index') }}" class="flex items-center space-x-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="page" value="{{ request('page', 1) }}">
                        <label for="entries" class="text-sm text-[var(--primary-dark)]">Show:</label>
                        <select name="entries" onchange="this.form.submit()"
                            class="w-20 px-2 py-1 border border-[var(--light-gray)] rounded-md focus:ring-2 focus:ring-[var(--accent-red)] text-[var(--primary-dark)]">
                            <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <label class="text-sm text-[var(--primary-dark)]">entries</label>
                    </form>

                    <!-- Pagination -->
                    <div class="flex items-center space-x-2">
                        {{ $paket->appends([
                            'entries' => request('entries'),
                            'search' => request('search')
                        ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal Mobile -->
    <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl border border-[var(--primary-bg)]">
                <div class="p-6 border-b border-[var(--light-gray)] flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-[var(--primary-dark)]">Tambah Paket Baru</h3>
                    <button onclick="toggleModal('createModal')" class="text-[var(--primary-dark)] hover:text-[var(--accent-red)]">
                        ✕
                    </button>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('paket.store') }}" method="post" class="space-y-6">
                        @csrf
                        <div>
                            <x-input-label for="nama" value="Nama Paket" class="text-[var(--primary-dark)] font-medium" />
                            <x-text-input 
                                id="nama" 
                                name="nama" 
                                type="text"
                                class="mt-1 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] rounded-lg"
                                placeholder="Contoh: Paket Internet 100Mbps"
                                required
                            />
                        </div>
                    
                        <div>
                            <x-input-label for="harga" value="Harga Paket" class="text-[var(--primary-dark)] font-medium" />
                            <div class="relative mt-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--primary-dark)]">Rp</span>
                                <x-text-input 
                                    id="harga" 
                                    name="harga" 
                                    type="number"
                                    class="w-full pl-8 border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] rounded-lg"
                                    placeholder="500000"
                                    required
                                />
                            </div>
                        </div>
                    
                        <div>
                            <x-input-label for="deskripsi" value="Deskripsi" class="text-[var(--primary-dark)] font-medium" />
                            <textarea 
                                id="deskripsi" 
                                name="deskripsi" 
                                rows="4"
                                class="mt-1 w-full rounded-lg border-[var(--light-gray)] shadow-sm focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]"
                                placeholder="Masukkan deskripsi lengkap paket"
                                required
                            ></textarea>
                        </div>
                    
                        <div class="flex justify-end">
                            <button type="submit" 
                                class="bg-[var(--accent-red)] text-[var(--light-gray)] px-6 py-2 rounded-lg hover:bg-[var(--primary-bg)] transition-all transform hover:scale-105">
                                Simpan Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl border border-[var(--primary-bg)]">
                <div class="p-6 border-b border-[var(--light-gray)] flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-[var(--primary-dark)]">Edit Paket</h3>
                    <button onclick="toggleModal('editModal')" class="text-[var(--primary-dark)] hover:text-[var(--accent-red)]">
                        ✕
                    </button>
                </div>
                
                <form method="POST" id="editForm" class="p-6 space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="edit_nama" value="Nama Paket" class="text-[var(--primary-dark)] font-medium" />
                        <x-text-input 
                            id="edit_nama" 
                            name="nama" 
                            type="text"
                            class="mt-1 w-full border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] rounded-lg"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label for="edit_harga" value="Harga Paket" class="text-[var(--primary-dark)] font-medium" />
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--primary-dark)]">Rp</span>
                            <x-text-input 
                                id="edit_harga" 
                                name="harga" 
                                type="number"
                                class="w-full pl-8 border-[var(--light-gray)] focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)] rounded-lg"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="edit_deskripsi" value="Deskripsi" class="text-[var(--primary-dark)] font-medium" />
                        <textarea 
                            id="edit_deskripsi" 
                            name="deskripsi" 
                            rows="4"
                            class="mt-1 w-full rounded-lg border-[var(--light-gray)] shadow-sm focus:border-[var(--accent-red)] focus:ring-[var(--accent-red)]"
                            required
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="toggleModal('editModal')"
                                class="px-4 py-2 text-[var(--primary-dark)] hover:bg-[var(--light-gray)] rounded-lg transition-all">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-[var(--accent-red)] text-[var(--light-gray)] rounded-lg hover:bg-[var(--primary-bg)] transition-all transform hover:scale-105">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Toggle Modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        // Edit Modal Handler
        function openEditModal(button) {
            const id = button.dataset.id;
            const nama = button.dataset.nama;
            const harga = button.dataset.harga;
            const deskripsi = button.dataset.deskripsi;

            // Set form values
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_deskripsi').value = deskripsi;

            // Update form action
            const form = document.getElementById('editForm');
            form.action = `/paket/${id}`;
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PUT';
            form.appendChild(method);

            toggleModal('editModal');
        }

        // Delete Handler
        async function deletePaket(id, nama) {
            const confirmed = await Swal.fire({
                title: `Hapus ${nama}?`,
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DA1212',
                cancelButtonColor: '#11468F',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            if (confirmed.isConfirmed) {
                try {
                    await fetch(`/paket/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    });
                    
                    Swal.fire('Terhapus!', 'Data paket telah dihapus.', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } catch (error) {
                    Swal.fire('Error!', 'Gagal menghapus data.', 'error');
                }
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

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }
    </style>
</x-app-layout>