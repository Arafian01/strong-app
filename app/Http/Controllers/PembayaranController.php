<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\pembayaran;
use App\Models\tagihan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        try {
            $pembayaran = pembayaran::paginate(5);
            $user = User::all();
            $paket = Paket::all();
            $pelanggan = Pelanggan::all();
            $tagihan = tagihan::all();

            return view('admin.page.pembayaran.index', compact('pembayaran', 'user', 'paket', 'pelanggan', 'tagihan'));
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // try {
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

        pembayaran::create($data);

        $tagihan = tagihan::findOrFail($request->input('tagihan_id'));
        $status = null;
        if ($request->input('status_verifikasi') == 'diterima') {
            $status = 'lunas';
        } elseif ($request->input('status_verifikasi') == 'ditolak') {
            $status = 'belum_dibayar';
        } elseif ($request->input('status_verifikasi') == 'menunggu verifikasi') {
            $status = 'menunggu_verifikasi';
        }

        $tagihan->update([
            'status_pembayaran' => $status,
        ]);

        return back()->with('message_success', 'Data pembayaran Berhasil Ditambahkan');
        // } catch (\Exception $e) {
        //     return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        // }
    }
    public function update(Request $request, String $id)
    {
        try {
            $pembayaran = pembayaran::findOrFail($id);

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

            $tagihan = tagihan::findOrFail($request->input('tagihan_id'));
            $status = null;
            if ($request->input('status_verifikasi') == 'diterima') {
                $status = 'lunas';
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
        $pembayaran = Pembayaran::findOrFail($id);
        $tagihan   = Tagihan::findOrFail($pembayaran->tagihan_id);

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
