<?php

namespace App\Http\Controllers;

use App\Models\pelanggans;
use App\Models\tagihans;
use App\Models\User;
use Illuminate\Http\Request;

class laporanController extends Controller
{
    public function index()
    {
        return view('admin.page.laporan.index');
    }
    public function store(Request $request)
    {
        try {
            $tahun = $request->input('tahun');

            $pelanggan = pelanggans::with('user')->get();

            $tagihan = tagihans::where('tahun', $tahun)
                ->get()
                ->groupBy('pelanggan_id');
            return view('admin.page.laporan.printLaporan', compact('tagihan', 'pelanggan', 'tahun'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
