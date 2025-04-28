<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-red-600 to-orange-500 bg-clip-text text-transparent">
                    Manajemen Paket
                </span>
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">{{ today()->format('d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <!-- Floating Button Mobile -->
    <button class="md:hidden fixed bottom-6 right-6 bg-red-600 text-white p-4 rounded-full shadow-lg z-50 hover:bg-red-700 transition-all"
            onclick="toggleModal('createModal')">
        ➕ Tambah Paket
    </button>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form Desktop -->
            <div class="hidden md:block bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
                <form action="{{ route('paket.store') }}" method="post" class="space-y-6">
                    @csrf
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Tambah Paket Baru</h3>
                    </div>
                    
                    <div>
                        <x-input-label for="nama" value="Nama Paket" />
                        <x-text-input 
                            id="nama" 
                            name="nama" 
                            type="text"
                            class="mt-1 w-full"
                            placeholder="Contoh: Paket Internet 100Mbps"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label for="harga" value="Harga Paket" />
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <x-text-input 
                                id="harga" 
                                name="harga" 
                                type="number"
                                class="w-full pl-8"
                                placeholder="500000"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="deskripsi" value="Deskripsi" />
                        <textarea 
                            id="deskripsi" 
                            name="deskripsi" 
                            rows="3"
                            class="mt-1 w-full rounded-lg border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Masukkan deskripsi lengkap paket"
                            required
                        ></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-gradient-to-r from-red-600 to-orange-500 text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
                            Simpan Paket
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Paket -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Paket</h3>
                    <span class="text-sm text-gray-500">Total: {{ $paket->total() }} paket</span>
                </div>

                <form method="GET" action="{{ route('paket.index') }}" class="mb-4">
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan nama/deskripsi/harga..." 
                               class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-red-500 focus:ring-red-500">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            Cari
                        </button>
                    </div>
                </form> 

                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="w-full" >
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">No</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Nama Paket</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Harga</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($paket as $key => $p)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $paket->perPage() * ($paket->currentPage() - 1) + $key + 1 }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-800 text-left">{{ $p->nama_paket }}</div>
                                    <div class="text-sm text-gray-500 mt-1">{{ $p->deskripsi }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-5 flex justify-center ">
                                    <div class="space-x-2">
                                        <button 
                                            data-id="{{ $p->id }}"
                                            data-nama="{{ $p->nama_paket }}"
                                            data-harga="{{ $p->harga }}"
                                            data-deskripsi="{{ $p->deskripsi }}"
                                            onclick="openEditModal(this)"
                                            class="px-3 py-1 bg-orange-100 text-orange-600 rounded-md hover:bg-orange-200 transition-colors text-sm">
                                            ✏️ Edit
                                        </button>
                                        <button 
                                            onclick="deletePaket('{{ $p->id }}','{{ $p->nama_paket }}')"
                                            class="px-3 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors text-sm">
                                            🗑️ Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <!-- Entries Per Page -->
                    <form method="GET" action="{{ route('paket.index') }}" class="flex items-center space-x-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="page" value="{{ request('page', 1) }}">
                        <label for="entries" class="text-sm">Show:</label>
                        <select name="entries" onchange="this.form.submit()"
                            class="w-20 px-2 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <label class="text-sm">entries</label>
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

    <!-- Create Modal Mobile -->
    <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Tambah Paket Baru</h3>
                    <button onclick="toggleModal('createModal')" class="text-gray-500 hover:text-gray-700">
                        ✕
                    </button>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('paket.store') }}" method="post" class="space-y-6">
                        @csrf
                        <div>
                            <x-input-label for="nama" value="Nama Paket" />
                            <x-text-input 
                                id="nama" 
                                name="nama" 
                                type="text"
                                class="mt-1 w-full"
                                placeholder="Contoh: Paket Internet 100Mbps"
                                required
                            />
                        </div>
                    
                        <div>
                            <x-input-label for="harga" value="Harga Paket" />
                            <div class="relative mt-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <x-text-input 
                                    id="harga" 
                                    name="harga" 
                                    type="number"
                                    class="w-full pl-8"
                                    placeholder="500000"
                                    required
                                />
                            </div>
                        </div>
                    
                        <div>
                            <x-input-label for="deskripsi" value="Deskripsi" />
                            <textarea 
                                id="deskripsi" 
                                name="deskripsi" 
                                rows="3"
                                class="mt-1 w-full rounded-lg border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500"
                                placeholder="Masukkan deskripsi lengkap paket"
                                required
                            ></textarea>
                        </div>
                    
                        <div class="flex justify-end">
                            <button type="submit" 
                                class="bg-gradient-to-r from-red-600 to-orange-500 text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
                                Simpan Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Edit Paket</h3>
                    <button onclick="toggleModal('editModal')" class="text-gray-500 hover:text-gray-700">
                        ✕
                    </button>
                </div>
                
                <form method="POST" id="editForm" class="p-6 space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="edit_nama" value="Nama Paket" />
                        <x-text-input 
                            id="edit_nama" 
                            name="nama" 
                            type="text"
                            class="mt-1 w-full"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label for="edit_harga" value="Harga Paket" />
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <x-text-input 
                                id="edit_harga" 
                                name="harga" 
                                type="number"
                                class="w-full pl-8"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="edit_deskripsi" value="Deskripsi" />
                        <textarea 
                            id="edit_deskripsi" 
                            name="deskripsi" 
                            rows="3"
                            class="mt-1 w-full rounded-lg border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500"
                            required
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="toggleModal('editModal')"
                                class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Pencarian
        function searchTable() {
            // Ambil input pencarian
            let input = document.getElementById("searchInput");
            let filter = input.value.toLowerCase();
            
            // Ambil tabel dan baris
            let table = document.getElementById("packageTable");
            let rows = table.getElementsByTagName("tr");

            // Loop melalui semua baris
            for (let i = 1; i < rows.length; i++) { // Mulai dari 1 untuk melewati header
                let cells = rows[i].getElementsByTagName("td");
                let showRow = false;
                
                // Cek setiap kolom yang ingin dicari
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        let text = cells[j].textContent.toLowerCase() || cells[j].innerText.toLowerCase();
                        
                        // Handle kolom harga khusus
                        if (j === 2) { // Kolom harga (indeks 2)
                            text = text.replace(/[^0-9]/g, ''); // Hapus karakter non-numeric
                        }
                        
                        if (text.indexOf(filter) > -1) {
                            showRow = true;
                            break;
                        }
                    }
                }
                
                // Tampilkan/sembunyikan baris
                rows[i].style.display = showRow ? "" : "none";
            }
        }
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
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
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
</x-app-layout>