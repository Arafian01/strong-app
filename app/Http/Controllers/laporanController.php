<?php

namespace App\Http\Controllers;

use App\Models\pelanggan;
use App\Models\tagihan;
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
        $tahun = $request->input('bulan_tahun');

        $pelanggan = Pelanggan::with('user')->get();

        $tagihan = Tagihan::where('bulan_tahun', 'LIKE', "$tahun-%")
            ->get()
            ->groupBy('pelanggan_id');

        return view('admin.page.laporan.printLaporan', compact('tagihan', 'pelanggan', 'tahun'));
    }
}
