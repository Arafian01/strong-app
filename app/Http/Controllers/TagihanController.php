<?php

namespace App\Http\Controllers;

use App\Models\pelanggans;
use App\Models\tagihans;
use App\Models\User;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        try {
            $search = request('search');
            $entries = request('entries', 10);

            $tagihan = tagihans::with(['pelanggan', 'pelanggan.user'])
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        // Search by customer name
                        $q->whereHas('pelanggan.user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'ilike', "%$search%");
                        })
                        // Search by formatted bulan_tahun (e.g., "January 2025")
                        ->orWhereRaw("to_char(to_date(bulan_tahun, 'YYYY-MM'), 'FMMonth YYYY') ilike ?", ["%$search%"])
                        // Search by formatted status_pembayaran (e.g., "Belum Dibayar")
                        ->orWhereRaw("initcap(replace(status_pembayaran, '_', ' ')) ilike ?", ["%$search%"])
                        // Search by jatuh_tempo
                        ->orWhere('jatuh_tempo', 'ilike', "%$search%");
                    });
                })
                ->orderByRaw("CASE status_pembayaran 
                    WHEN 'belum_dibayar' THEN 1 
                    WHEN 'menunggu_verifikasi' THEN 2 
                    WHEN 'lunas' THEN 3 
                    WHEN 'ditolak' THEN 4 
                    ELSE 5 END")
                ->orderBy('bulan_tahun', 'desc')
                ->paginate($entries);

            return view('admin.page.tagihan.index', [
                'tagihan' => $tagihan,
                'search' => $search,
                'entries' => $entries,
                'pelanggan' => pelanggans::select('id', 'user_id')->with('user:id,name')->get(),
                'user' => []
            ]);
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Check if tagihan already exists for pelanggan_id and bulan_tahun
            $exists = tagihans::where('pelanggan_id', $request->input('pelanggan_id'))
                ->where('bulan_tahun', $request->input('bulan_tahun'))
                ->exists();

            if ($exists) {
                return back()->with('message_error', 'Tagihan untuk bulan ini sudah ada.')->withInput();
            }

            // Create new tagihan
            $data = [
                'pelanggan_id' => $request->input('pelanggan_id'),
                'bulan_tahun' => $request->input('bulan_tahun'),
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
            $data = [
                'pelanggan_id' => $request->input('pelanggan_id'),
                'bulan_tahun' => $request->input('bulan_tahun'),
                'status_pembayaran' => $request->input('status_pembayaran'),
                'jatuh_tempo' => $request->input('jatuh_tempo'),
            ];

            $datas = tagihans::findOrFail($id);
            $datas->update($data);
            return back()->with('message_insert', 'Data Tagihan Sudah diubah');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $data = tagihans::findOrFail($id);
            $data->delete();
            return back()->with('message_insert', 'Data Tagihan Sudah dihapus');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }
}
