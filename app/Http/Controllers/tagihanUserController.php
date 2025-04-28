<?php

namespace App\Http\Controllers;

use App\Models\pelanggan;
use App\Models\tagihan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class tagihanUserController extends Controller
{
    public function index()
    {
        // Logic to fetch and display the user's bills
        $search = request('search');
        $entries = request('entries', 10);
        $pelanggan = Pelanggan::where('user_id', Auth::id())->first();
        $tagihan = Tagihan::with(['pelanggan', 'pelanggan.user'])
        ->where('pelanggan_id', $pelanggan->id) // Tambahkan ini untuk filter tagihan pelanggan user login
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pelanggan.user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%$search%");
                })
                ->orWhere('bulan_tahun', 'like', "%$search%")
                ->orWhere('status_pembayaran', 'like', "%$search%")
                ->orWhere('jatuh_tempo', 'like', "%$search%");
            });
        })
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
