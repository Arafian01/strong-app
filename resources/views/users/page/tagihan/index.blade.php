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
    <x-slot name="header md:flex">
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
                <div
                    class="hidden sm:block bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Tagihan</h3>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                        <table class="w-full table-auto text-sm">
                            <thead class="bg-gray-50">
                                <tr class="text-gray-700">
                                    <th class="px-4 py-3 text-center">No</th>
                                    <th class="px-4 py-3">Bulan Tahun</th>
                                    <th class="px-4 py-3">Status Pembayaran</th>
                                    <th class="px-4 py-3">Jatuh Tempo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($tagihan as $key => $t)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center">{{ $tagihan->firstItem() + $key }}</td>
                                        <td class="px-4 py-3 text-center">{{ date('F Y', strtotime($t->bulan_tahun)) }}</td>
                                        <td class="px-4 py-3 text-center">
                                            {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $t->jatuh_tempo }}</td>
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
                                    <h4 class="font-semibold text-gray-800">Tagihan Bulan {{ date('F Y', strtotime($t->bulan_tahun)) }}</h4>
                                </div>
                                <span
                                    class="px-2 py-1 {{ $t->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $t->status_pembayaran)) }}
                                </span>
                            </div>
                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-700">
                                <div><span class="font-medium">Jatuh Tempo:</span> {{ $t->jatuh_tempo }}</div>
                            </div>
                            
                        </div>
                    @endforeach
                    <div class="pt-2">
                        {{ $tagihan->links() }}
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
