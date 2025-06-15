<?php

namespace App\Http\Controllers;

use App\Models\pelanggans;
use App\Models\tagihans;
use App\Models\pakets;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TagihanController extends Controller
{
    public function index()
    {
        // try {
            $search = request('search');
            $entries = request('entries', 10);

            $tagihan = tagihans::with(['pelanggan', 'pelanggan.user'])
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        // Search by customer name
                        $q->whereHas('pelanggan.user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'ilike', "%$search%");
                        })
                        // Search by formatted bulan and tahun (e.g., "January 2025")
                        ->orWhereRaw("to_char(make_date(tahun, bulan, 1), 'FMMonth YYYY') ilike ?", ["%$search%"])
                        // Search by formatted status_pembayaran (e.g., "Belum Dibayar")
                        ->orWhereRaw("initcap(replace(status_pembayaran, '_', ' ')) ilike ?", ["%$search%"])
                        // Search by harga
                        ->orWhereRaw("harga::text ilike ?", ["%$search%"])
                        // Search by jatuh_tempo
                        ->orWhereRaw("to_char(jatuh_tempo, 'DD-MM-YYYY') ilike ?", ["%$search%"]);
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

            return view('admin.page.tagihan.index', [
                'tagihan' => $tagihan,
                'search' => $search,
                'entries' => $entries,
                'pelanggan' => pelanggans::select('id', 'user_id')->with('user:id,name')->where('status', 'aktif')->get(),
                'user' => []
            ]);
        // } catch (\Exception $e) {
        //     return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        // }
    }

    public function store(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'pelanggan_id' => 'required|exists:pelanggans,id',
                'bulan' => 'required|integer|between:1,12',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
                'status_pembayaran' => 'required|in:belum_dibayar,menunggu_verifikasi,lunas',
                'jatuh_tempo' => 'required|date',
            ]);

            // Check if tagihan already exists for pelanggan_id, bulan, and tahun
            $exists = tagihans::where('pelanggan_id', $request->input('pelanggan_id'))
                ->where('bulan', $request->input('bulan'))
                ->where('tahun', $request->input('tahun'))
                ->exists();

            if ($exists) {
                return back()->with('error_message', 'Tagihan untuk pelanggan dan periode ini sudah ada.')->withInput();
            }

            // Get harga from pelanggans->pakets->harga
            $pelanggan = pelanggans::with('paket')->findOrFail($request->input('pelanggan_id'));
            if (!$pelanggan->paket) {
                return back()->with('error_message', 'Pelanggan tidak memiliki paket yang valid.')->withInput();
            }
            $harga = $pelanggan->paket->harga;

            // Create new tagihan
            $data = [
                'pelanggan_id' => $request->input('pelanggan_id'),
                'bulan' => $request->input('bulan'),
                'tahun' => $request->input('tahun'),
                'harga' => $harga,
                'status_pembayaran' => $request->input('status_pembayaran'),
                'jatuh_tempo' => $request->input('jatuh_tempo'),
            ];

            tagihans::create($data);

            return back()->with('message_insert', 'Data Tagihan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            // Validate request
            $request->validate([
                'pelanggan_id' => 'required|exists:pelanggans,id',
                'bulan' => 'required|integer|between:1,12',
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
                'status_pembayaran' => 'required|in:belum_dibayar,menunggu_verifikasi,lunas',
                'jatuh_tempo' => 'required|date',
            ]);

            // Check if another tagihan exists for pelanggan_id, bulan, and tahun (excluding current tagihan)
            $exists = tagihans::where('pelanggan_id', $request->input('pelanggan_id'))
                ->where('bulan', $request->input('bulan'))
                ->where('tahun', $request->input('tahun'))
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return back()->with('message_error', 'Tagihan untuk pelanggan dan periode ini sudah ada.')->withInput();
            }

            // Get harga from pelanggans->pakets->harga
            $pelanggan = pelanggans::with('paket')->findOrFail($request->input('pelanggan_id'));
            if (!$pelanggan->paket) {
                return back()->with('message_error', 'Pelanggan tidak memiliki paket yang valid.')->withInput();
            }
            $harga = $pelanggan->paket->harga;

            // Update tagihan
            $data = [
                'pelanggan_id' => $request->input('pelanggan_id'),
                'bulan' => $request->input('bulan'),
                'tahun' => $request->input('tahun'),
                'harga' => $harga,
                'status_pembayaran' => $request->input('status_pembayaran'),
                'jatuh_tempo' => $request->input('jatuh_tempo'),
            ];

            $tagihan = tagihans::findOrFail($id);
            $tagihan->update($data);

            return back()->with('message_insert', 'Data Tagihan berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $tagihan = tagihans::findOrFail($id);
            $tagihan->delete();
            return back()->with('message_insert', 'Data Tagihan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }
}