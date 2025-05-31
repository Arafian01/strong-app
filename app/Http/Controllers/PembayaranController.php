<?php

namespace App\Http\Controllers;

use App\Models\pakets;
use App\Models\pelanggans;
use App\Models\pembayarans;
use App\Models\tagihans;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        // try {
        $search = request('search');
        $entries = request('entries', 10);

        $pembayaran = pembayarans::with(['tagihan', 'tagihan.pelanggan.user'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    // Search by customer name
                    $q->whereHas('tagihan.pelanggan.user', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'ilike', "%$search%");
                    })
                        // Search by formatted bulan and tahun (e.g., "January 2025")
                        ->orWhereHas('tagihan', function ($subQuery) use ($search) {
                            $subQuery->whereRaw("to_char(to_date(bulan::text, 'MM'), 'FMMonth') || ' ' || tahun ilike ?", ["%$search%"])
                                ->orWhere('jatuh_tempo', 'ilike', "%$search%");
                        })
                        // Search by formatted status_verifikasi (e.g., "Menunggu Verifikasi")
                        ->orWhereRaw("initcap(replace(status_verifikasi, '_', ' ')) ilike ?", ["%$search%"]);
                });
            })
            ->orderByRaw("CASE status_verifikasi 
        WHEN 'menunggu_verifikasi' THEN 1
        WHEN 'diterima' THEN 2
        WHEN 'ditolak' THEN 3 
        ELSE 5 END")
            ->orderBy('tanggal_kirim', 'desc')
            ->paginate($entries);

        return view('admin.page.pembayaran.index', [
            'pembayaran' => $pembayaran,
            'search' => $search,
            'entries' => $entries,
            'pelanggan' => pelanggans::select('id', 'user_id')->with('user:id,name')->get(),
            'user' => [],
            'tagihan' => tagihans::select('id', 'pelanggan_id', 'bulan', 'tahun')->get(),
        ]);
        // } catch (\Exception $e) {
        //     return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        // }
    }

    public function store(Request $request)
    {
        try {
            $tanggal = null;
            if ($request->input('status_verifikasi') == 'diterima') {
                $tanggal = now();
            } elseif ($request->input('status_verifikasi') == 'ditolak') {
                $tanggal = now();
            } else {
                $tanggal = null;
            }


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
                'tanggal_kirim' => $request->input('tanggal_kirim'),
                'status_verifikasi' => $request->input('status_verifikasi'),
                'tanggal_verifikasi' => $tanggal,
            ];

            pembayarans::create($data);

            $tagihan = tagihans::findOrFail($request->input('tagihan_id'));
            $status = null;
            if ($request->input('status_verifikasi') == 'diterima') {
                $status = 'lunas';
            } elseif ($request->input('status_verifikasi') == 'ditolak') {
                $status = 'belum_dibayar';
            } elseif ($request->input('status_verifikasi') == 'menunggu_verifikasi') {
                $status = 'menunggu_verifikasi';
            }

            $tagihan->update([
                'status_pembayaran' => $status,
            ]);

            return back()->with('message_success', 'Data pembayaran Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, String $id)
    {
        try {
            $pembayaran = pembayarans::findOrFail($id);

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($pembayaran->image && file_exists(public_path('pembayaran_images/' . $pembayaran->image))) {
                    unlink(public_path('pembayaran_images/' . $pembayaran->image));
                }

                // Simpan gambar baru
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('pembayaran_images'), $imageName);
            } else {
                // Gunakan gambar lama jika tidak ada perubahan
                $imageName = $pembayaran->image;
            };

            $pembayaran->update([
                'user_id' => $request->input('user_id'),
                'tagihan_id' => $request->input('tagihan_id'),
                'image' => $imageName,
                'tanggal_kirim' => $request->input('tanggal_kirim'),
                'status_verifikasi' => $request->input('status_verifikasi'),
                'tanggal_verifikasi' => now(),
            ]);

            $tagihan = tagihans::findOrFail($request->input('tagihan_id'));
            $status = null;
            if ($request->input('status_verifikasi') == 'diterima') {
                $status = 'lunas';
            } elseif ($request->input('status_verifikasi') == 'ditolak') {
                $status = 'belum_dibayar';
            } else {
                $status = 'menunggu_verifikasi';
            }

            $tagihan->update([
                'status_pembayaran' => $status,
            ]);

            return back()->with('message_success', 'Data pembayaran Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $pembayaran = pembayarans::findOrFail($id);
        $tagihan   = tagihans::findOrFail($pembayaran->tagihan_id);

        if ($tagihan->status_pembayaran === 'lunas') {
            // Kembalikan error JSON dengan status 400
            return response()->json([
                'status'  => 'error',
                'message' => 'Pembayaran sudah lunas, tidak bisa dihapus'
            ], 400);
        }

        // Hapus record dan file
        $pembayaran->delete();
        if ($pembayaran->image && file_exists(public_path('pembayaran_images/' . $pembayaran->image))) {
            unlink(public_path('pembayaran_images/' . $pembayaran->image));
        }
        $tagihan->update(['status_pembayaran' => 'belum_dibayar']);

        // Kembalikan sukses JSON (status 200)
        return response()->json([
            'status'  => 'success',
            'message' => 'Pembayaran berhasil dihapus'
        ], 200);
    }
}
