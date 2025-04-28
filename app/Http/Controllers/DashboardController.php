<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        // Sekarang bulan dan tahun
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $currentMonthYear = Carbon::now()->format('m-Y');

        // 1. Jumlah pelanggan berdasarkan status
        $jumlahPelanggan = Pelanggan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 2. Jumlah tagihan bulan ini berdasarkan status pembayaran
        $jumlahTagihan = Tagihan::whereYear('jatuh_tempo', $currentYear)
            ->whereMonth('jatuh_tempo', $currentMonth)
            ->select('status_pembayaran', DB::raw('count(*) as total'))
            ->groupBy('status_pembayaran')
            ->pluck('total', 'status_pembayaran');

        // 3. Jumlah penghasilan bulan ini (dari tagihan yang lunas)
        $totalPenghasilan = Tagihan::whereYear('jatuh_tempo', $currentYear)
            ->whereMonth('jatuh_tempo', $currentMonth)
            ->where('status_pembayaran', 'lunas')
            ->with('pelanggan.paket')
            ->get()
            ->sum(function ($tagihan) {
                return $tagihan->pelanggan->paket->harga ?? 0;
            });

        return view('dashboard', [
            'jumlahPelanggan' => $jumlahPelanggan,
            'jumlahTagihan' => $jumlahTagihan,
            'totalPenghasilan' => $totalPenghasilan,
        ]);
    }

}
