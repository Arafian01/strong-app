<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pelanggans;
use App\Models\pembayarans;
use App\Models\tagihans;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Sekarang bulan dan tahun
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');

        // 1. Jumlah pelanggan berdasarkan status
        $jumlahPelanggan = pelanggans::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 2. Jumlah tagihan bulan ini berdasarkan status pembayaran
        $jumlahTagihan = tagihans::whereYear('jatuh_tempo', $currentYear)
            ->whereMonth('jatuh_tempo', $currentMonth)
            ->select('status_pembayaran', DB::raw('count(*) as total'))
            ->groupBy('status_pembayaran')
            ->pluck('total', 'status_pembayaran');

        // 3. Jumlah penghasilan bulan ini (dari tagihan yang lunas)
        $totalPenghasilan = tagihans::whereYear('jatuh_tempo', $currentYear)
            ->whereMonth('jatuh_tempo', $currentMonth)
            ->where('status_pembayaran', 'lunas')
            ->with('pelanggan.paket')
            ->get()
            ->sum(function ($tagihan) {
                return $tagihan->pelanggan && $tagihan->pelanggan->paket ? $tagihan->pelanggan->paket->harga : 0;
            });

        return view('admin.page.dashboard.index', [
            'jumlahPelanggan' => $jumlahPelanggan,
            'jumlahTagihan' => $jumlahTagihan,
            'totalPenghasilan' => $totalPenghasilan,
        ]);
    }

    public function indexUser()
    {
        $pelanggan = pelanggans::where('user_id', auth()->id())->with('paket')->first();

        $tagihan = $pelanggan
            ? tagihans::where('pelanggan_id', $pelanggan->id)
                ->with(['pelanggan.paket'])
                ->where('status_pembayaran', 'belum_dibayar')
                ->get()
            : collect();

        $pembayaran = pembayarans::where('user_id', auth()->id())
            ->with(['tagihan.pelanggan.paket'])
            ->latest()
            ->take(5)
            ->get();

        $tagihanBelumDibayar = $tagihan->count();
        $totalPembayaran = pembayarans::where('user_id', auth()->id())->count();

        return view('dashboard', compact('tagihan', 'pembayaran', 'tagihanBelumDibayar', 'totalPembayaran', 'pelanggan'));
    }
}