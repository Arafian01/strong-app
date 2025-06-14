<?php

namespace App\Http\Controllers;

use App\Models\pakets;
use App\Models\pelanggans;
use App\Models\pembayarans;
use App\Models\tagihans;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pembayaranUserController extends Controller
{
    public function index()
    {
        try {
            $search = request('search');
            $entries = request('entries', 10);

            // Cari pelanggan berdasarkan user yang login
            $pelanggan = pelanggans::where('user_id', Auth::id())->first();

            // Ambil pembayaran yang hanya milik pelanggan login
            $pembayaran = pembayarans::with(['tagihan', 'tagihan.pelanggan'])
                ->whereHas('tagihan.pelanggan', function ($query) use ($pelanggan) {
                    $query->where('id', $pelanggan->id);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('metode_pembayaran', 'like', "%$search%")
                            ->orWhere('status', 'like', "%$search%")
                            ->orWhere('keterangan', 'like', "%$search%");
                    });
                })
                ->orderBy('tagihan_id', 'desc')
                ->paginate($entries);

            return view('users.page.pembayaran.index', [
                'pembayaran' => $pembayaran,
                'search' => $search,
                'entries' => $entries,
                'pelanggan' => $pelanggan,
                'user' => User::all(),
                'paket' => pakets::all(),
                'tagihan' => tagihans::all(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('pembayaran_images'), $imageName);
            } else {
                $imageName = null;
            };


            $data = [
                'user_id' => Auth::user()->id,
                'tagihan_id' => $request->input('tagihan_id'),
                'image' => $imageName,
                'tanggal_kirim' => now(),
                'status_verifikasi' => "menunggu_verifikasi",
            ];

            pembayarans::create($data);

            $tagihan = tagihans::findOrFail($request->input('tagihan_id'));

            $tagihan->update([
                'status_pembayaran' => "menunggu_verifikasi",
            ]);

            return redirect()->route('pembayaran.index')->with('message_success', 'Data pembayaran Berhasil Ditambahkan');
        // } catch (\Exception $e) {
        //     return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        // }
    }
}
