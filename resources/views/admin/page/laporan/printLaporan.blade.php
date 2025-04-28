<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {
            @media print {
                .page-break { page-break-after: always; }
                thead { display: table-header-group; }
            }
        }
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
    </style>
</head>
<body class="bg-gray-100 text-sm font-sans">

    <div class="bg-white p-6 mx-auto w-[297mm] min-h-[210mm]">
        <!-- Header -->
        <div class="flex items-center border-b-2 pb-4 mb-6">
            <div class="w-20 h-20 bg-contain bg-no-repeat bg-center" style="background-image: url('logo.png');"></div>
            <div class="flex-1 text-center text-2xl font-bold">Laporan Pembayaran</div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full border border-black text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-black p-2">NO</th>
                        <th class="border border-black p-2">Nama</th>
                        <th class="border border-black p-2">Januari</th>
                        <th class="border border-black p-2">Februari</th>
                        <th class="border border-black p-2">Maret</th>
                        <th class="border border-black p-2">April</th>
                        <th class="border border-black p-2">Mei</th>
                        <th class="border border-black p-2">Juni</th>
                        <th class="border border-black p-2">Juli</th>
                        <th class="border border-black p-2">Agustus</th>
                        <th class="border border-black p-2">September</th>
                        <th class="border border-black p-2">Oktober</th>
                        <th class="border border-black p-2">November</th>
                        <th class="border border-black p-2">Desember</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($pelanggan as $p)
                        <tr class="break-inside-avoid">
                            <td class="border border-black p-1">{{ $no++ }}</td>
                            <td class="border border-black p-1 text-left pl-2">{{ $p->user->name }}</td>

                            @for ($i = 1; $i <= 12; $i++)
                                @php
                                    $bulanFormat = $tahun . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                    $status = null;
                                    if (isset($tagihan[$p->id])) {
                                        $tagihanBulanIni = $tagihan[$p->id]->where('bulan_tahun', $bulanFormat)->first();
                                        if ($tagihanBulanIni) {
                                            $status = $tagihanBulanIni->status_pembayaran;
                                        }
                                    }
                                @endphp
                                <td class="border border-black p-1">
                                    @if ($status == 'lunas')
                                        ✔️
                                    @elseif ($status == 'menunggu_verifikasi')
                                        ⏳
                                    @elseif ($status == 'belum_dibayar')
                                        ❌
                                    @else
                                        -
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="flex justify-between mt-8">
            <div class="w-1/2">
                <p class="font-semibold">Keterangan:</p>
                <ul class="list-disc list-inside">
                    <li>✔️ : Sudah Lunas</li>
                    <li>⏳ : Menunggu Verifikasi</li>
                    <li>❌ : Belum Dibayar</li>
                    <li>- : Tidak Ada Data</li>
                </ul>
            </div>
            <div class="w-1/3 text-center">
                <p class="mb-16">Mengetahui,</p>
                <p>______________________</p>
                <p>Petugas</p>
            </div>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
