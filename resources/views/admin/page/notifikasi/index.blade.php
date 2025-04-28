<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-red-600 to-orange-500 bg-clip-text text-transparent">
                    Manajemen Notifikasi
                </span>
            </h2>
            @can('role-admin')
                <button onclick="toggleModal('createModal')"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Notifikasi
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- SweetAlert Notifications -->
        @if (Session::has('message_insert'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ Session::get('message_insert') }}',
                    timer: 3000
                })
            </script>
        @endif

        @if (Session::has('message_delete'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: '{{ Session::get('message_delete') }}',
                    timer: 3000
                })
            </script>
        @endif

        @if (Session::has('error_message') || Session::has('error_mesaage'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ Session::get('error_message') ?? Session::get('error_mesaage') }}'
                })
            </script>
        @endif

        <!-- Daftar Notifikasi -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($notifikasis as $notifikasi)
                    <div
                        class="bg-gradient-to-br from-white to-red-50 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow relative group border border-red-100">
                        <!-- Badge Status -->
                        <div class="absolute top-3 right-3">
                            <span
                                class="px-3 py-1 rounded-full text-sm {{ in_array($notifikasi->id, $dibaca) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                {{ in_array($notifikasi->id, $dibaca) ? '✓ Dibaca' : '✗ Belum Dibaca' }}
                            </span>
                        </div>

                        <!-- Konten -->
                        <div class="space-y-3">
                            <h3 class="text-xl font-semibold text-red-600">{{ $notifikasi->judul }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $notifikasi->pesan }}</p>

                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $notifikasi->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                @can('role-admin')
                                    <!-- Actions -->
                                    <div
                                        class="flex items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="openEditModal({{ $notifikasi }})"
                                            class="text-red-600 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        <form id="deleteForm-{{ $notifikasi->id }}"
                                            action="{{ route('notifikasi.destroy', $notifikasi->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $notifikasi->id }}, '{{ $notifikasi->judul }}')"
                                                class="text-red-600 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl flex flex-col">
                <div class="p-6 border-b flex justify-between items-center bg-red-50 rounded-t-2xl">
                    <h3 class="text-xl font-bold text-red-600">Buat Notifikasi Baru</h3>
                    <button onclick="toggleModal('createModal')"
                        class="text-red-500 hover:text-red-700 text-2xl p-2 transition-transform hover:rotate-90">
                        ✕
                    </button>
                </div>
                <form action="{{ route('notifikasi.store') }}" method="POST" class="flex-1 flex flex-col">
                    @csrf
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Judul <span class="text-red-500">*</span>
                                <input type="text" name="judul" required placeholder="Judul Notifikasi"
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500">
                            </label>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Pesan <span class="text-red-500">*</span>
                                <textarea name="pesan" rows="4" required placeholder="Isi pesan notifikasi"
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500"></textarea>
                            </label>
                        </div>
                    </div>
                    <div class="p-6 border-t bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                        <button type="button" onclick="toggleModal('createModal')"
                            class="px-6 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Simpan Notifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl flex flex-col">
                <div class="p-6 border-b flex justify-between items-center bg-red-50 rounded-t-2xl">
                    <h3 class="text-xl font-bold text-red-600">Edit Notifikasi</h3>
                    <button onclick="toggleModal('editModal')"
                        class="text-red-500 hover:text-red-700 text-2xl p-2 transition-transform hover:rotate-90">
                        ✕
                    </button>
                </div>
                <form method="POST" id="editForm" class="flex-1 flex flex-col">
                    @csrf
                    @method('PUT')
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 modal-scroll">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Judul <span class="text-red-500">*</span>
                                <input type="text" id="edit_judul" name="judul" required
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500">
                            </label>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Pesan <span class="text-red-500">*</span>
                                <textarea id="edit_pesan" name="pesan" rows="4" required
                                    class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring-red-500"></textarea>
                            </label>
                        </div>
                    </div>
                    <div class="p-6 border-t bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                        <button type="button" onclick="toggleModal('editModal')"
                            class="px-6 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
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
        function openEditModal(notifikasi) {
            document.getElementById('edit_judul').value = notifikasi.judul;
            document.getElementById('edit_pesan').value = notifikasi.pesan;

            const form = document.getElementById('editForm');
            form.action = `/notifikasi/${notifikasi.id}`;
            toggleModal('editModal');
        }

        // Delete Confirmation
        function confirmDelete(id, title) {
            Swal.fire({
                title: `Hapus ${title}?`,
                text: "Anda tidak akan bisa mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`deleteForm-${id}`).submit();
                }
            });
        }
    </script>
</x-app-layout>
