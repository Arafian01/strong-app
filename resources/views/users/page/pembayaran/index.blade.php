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
                                    <th class="px-4 py-3 text-center">Bulan Tahun</th>
                                    <th class="px-4 py-3 text-center">Harga</th>
                                    <th class="px-4 py-3 text-center">Tgl Kirim</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Tgl Verifikasi</th>
                                    <th class="px-4 py-3 text-center">Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($pembayaran as $key => $p)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center">{{ $pembayaran->firstItem() + $key }}</td>
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
                                    <h4 class="font-semibold text-gray-800">Pembayaran untuk Bulan : {{ date('F Y', strtotime($p->tagihan->bulan_tahun)) }}
                                    </h4>
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
                        </div>
                    @endforeach
                    <div class="pt-2">
                        {{ $pembayaran->appends(request()->only(['search', 'entries']))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
