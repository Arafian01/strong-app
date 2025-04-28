<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Tagihan;

class GenerateTagihan extends Command
{
    protected $signature = 'tagihan:generate'; // Nama perintah artisan
    protected $description = 'Membuat tagihan otomatis untuk pelanggan aktif';

    public function handle() {
        $tanggal = Carbon::now()->endOfMonth(); // Ambil tanggal akhir bulan
        $bulanTahun = $tanggal->format('Y-m'); // Format YYYY-MM
        $jatuhTempo = Carbon::now()->addMonth()->startOfMonth()->addDays(4)->format('Y-m-d'); // Jatuh tempo tanggal 5 bulan depan

        $pelangganAktif = Pelanggan::where('status', 'aktif')->get();

        foreach ($pelangganAktif as $pelanggan) {
            // Cek apakah tagihan sudah ada untuk bulan ini
            $tagihanSudahAda = Tagihan::where('pelanggan_id', $pelanggan->id)
                ->where('bulan_tahun', $bulanTahun)
                ->exists();

            if (!$tagihanSudahAda) {
                // Jika belum ada, buat tagihan baru
                Tagihan::create([
                    'pelanggan_id' => $pelanggan->id,
                    'bulan_tahun' => $bulanTahun,
                    'status_pembayaran' => 'belum_dibayar',
                    'jatuh_tempo' => $jatuhTempo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info("Tagihan untuk {$pelanggan->user->name} bulan {$bulanTahun} berhasil dibuat.");
            } else {
                // Jika sudah ada, beri info di log/terminal
                $this->info("Tagihan untuk {$pelanggan->user->name} bulan {$bulanTahun} sudah ada.");
            }
        }
    }
}
