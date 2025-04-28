<?php

namespace App\Http\Controllers;

use App\Models\pelanggan;
use App\Models\tagihan;
use App\Models\User;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        // try {

        // $tagihan = tagihan::paginate(5);
        // $user = User::all();
        // $pelanggan = pelanggan::all();
        // return view('admin.page.tagihan.index')->with([
        //     'tagihan' => $tagihan,
        //     'pelanggan' => $pelanggan,
        //     'user' => $user
        // ]);
        $search = request('search');
        $entries = request('entries', 10);
        $tagihan = tagihan::with(['pelanggan', 'pelanggan.user'])
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
        return view('admin.page.tagihan.index', [
            'tagihan' => $tagihan,
            'search' => $search,
            'entries' => $entries,
            'pelanggan' => Pelanggan::all(),
            'user' => User::all()
        ]);
        // } catch (\Exception $e) {
        //     return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        // }
    }

    public function store(Request $request)
    {
        try {
            $data = [
                'pelanggan_id' => $request->input('pelanggan_id'),
                'bulan_tahun' => $request->input('bulan_tahun'),
                'status_pembayaran' => $request->input('status_pembayaran'),
                'jatuh_tempo' => $request->input('jatuh_tempo'),
            ];

            tagihan::create($data);

            return back()->with('message_insert', 'Data Tagihan Sudah dihapus');
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

            $datas = tagihan::findOrFail($id);
            $datas->update($data);
            return back()->with('message_insert', 'Data Tagihan Sudah dihapus');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $data = tagihan::findOrFail($id);
            $data->delete();
            return back()->with('message_insert', 'Data Tagihan Sudah dihapus');
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }
}
