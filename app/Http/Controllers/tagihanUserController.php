<?php

namespace App\Http\Controllers;

use App\Models\pelanggans;
use App\Models\tagihans;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class tagihanUserController extends Controller
{
    public function index()
    {
        $search = request('search');
        $entries = request('entries', 10);
        $pelanggan = pelanggans::where('user_id', Auth::id())->first();

        $tagihan = tagihans::with(['pelanggan', 'pelanggan.user'])
            ->where('pelanggan_id', $pelanggan ? $pelanggan->id : 0) // Fallback if pelanggan is null
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('pelanggan.user', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%$search%");
                    })
                        ->orWhereRaw("CONCAT(bulan, ' ', tahun) LIKE ?", ["%$search%"])
                        ->orWhere('status_pembayaran', 'like', "%$search%")
                        ->orWhere('jatuh_tempo', 'like', "%$search%");
                });
            })
            ->orderByRaw("CASE status_pembayaran 
                    WHEN 'belum_dibayar' THEN 1 
                    WHEN 'menunggu_verifikasi' THEN 2 
                    WHEN 'lunas' THEN 3 
                    ELSE 4 END")
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate($entries);

        return view('users.page.tagihan.index', [
            'tagihan' => $tagihan,
            'search' => $search,
            'entries' => $entries,
            'pelanggan' => $pelanggan,
            'user' => User::all()
        ]);
    }
}
