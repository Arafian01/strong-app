<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\pelanggans;
use App\Models\tagihans;

class GenerateTagihan extends Command
{
    protected $signature = 'tagihan:generate'; // Nama perintah artisan
    protected $description = 'Membuat tagihan otomatis untuk pelanggan aktif';

    public function handle() {
        $tanggal = Carbon::now()->endOfMonth(); // Ambil tanggal akhir bulan
        // $bulanTahun = $tanggal->format('Y-m'); // Format YYYY-MM
        $jatuhTempo = Carbon::now()->addMonth()->startOfMonth()->addDays(4)->format('Y-m-d'); // Jatuh tempo tanggal 5 bulan depan

        $pelangganAktif = pelanggans::where('status', 'aktif')->get();

        foreach ($pelangganAktif as $pelanggan) {
            // Cek apakah tagihan sudah ada untuk bulan ini
            // $tagihanSudahAda = tagihans::where('pelanggan_id', $pelanggan->id)
            //     ->where('bulan_tahun', $bulanTahun)
            //     ->exists();
            $tagihanSudahAda = tagihans::where('pelanggan_id', $pelanggan->id)
                ->where('bulan', $tanggal->month)
                ->where('tahun', $tanggal->year)
                ->exists();

            if (!$tagihanSudahAda) {
                // Jika belum ada, buat tagihan baru
                tagihans::create([
                    'pelanggan_id' => $pelanggan->id,
                    'bulan' => $tanggal->month,
                    'tahun' => $tanggal->year,
                    'status_pembayaran' => 'belum_dibayar',
                    'harga' => $pelanggan->paket->harga, 
                    'jatuh_tempo' => $jatuhTempo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info("Tagihan untuk {$pelanggan->user->name} bulan berhasil dibuat.");
            } else {
                // Jika sudah ada, beri info di log/terminal
                $this->info("Tagihan untuk {$pelanggan->user->name} bulan sudah ada.");
            }
        }
    }
}
