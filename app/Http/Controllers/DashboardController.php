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
    public function index(Request $request)
    {
        // Current month and year
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');

        // Selected year for income chart (default to current year)
        $selectedYear = $request->input('year', $currentYear);

        // 1. Jumlah pelanggan berdasarkan status
        $jumlahPelanggan = pelanggans::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 2. Jumlah tagihan bulan ini berdasarkan status pembayaran
        $jumlahTagihan = tagihans::where('bulan', $currentMonth)
            // ->whereMonth('', $currentMonth)
            ->select('status_pembayaran', DB::raw('count(*) as total'))
            ->groupBy('status_pembayaran')
            ->pluck('total', 'status_pembayaran');

        // 3. Jumlah penghasilan bulan ini (dari tagihan yang lunas)
        $totalPenghasilan = tagihans::whereYear('jatuh_tempo', $currentYear)
            ->whereMonth('jatuh_tempo', $currentMonth)
            ->where('status_pembayaran', 'lunas')
            ->sum('harga');

        // 4. Penghasilan per bulan untuk tahun yang dipilih
        $monthlyIncome = tagihans::where('status_pembayaran', 'lunas')
            ->where('tahun', $selectedYear)
            ->select(
                DB::raw('bulan as month'),
                DB::raw('SUM(harga) as total_income')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total_income', 'month')
            ->toArray();

        // Prepare income data for all 12 months (fill missing months with 0)
        $incomeData = array_fill(1, 12, 0);
        foreach ($monthlyIncome as $month => $income) {
            $incomeData[$month] = $income;
        }

        // Available years for the dropdown (e.g., from 2020 to next year)
        $years = range(2020, $currentYear + 1);

        return view('admin.page.dashboard.index', [
            'pelangganStatus' => $jumlahPelanggan,
            'tagihanStatus' => $jumlahTagihan,
            'totalPenghasilan' => $totalPenghasilan,
            'incomeData' => $incomeData,
            'selectedYear' => $selectedYear,
            'years' => $years,
        ]);
    }

    public function indexUser()
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }
}
