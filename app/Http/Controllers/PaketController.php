<?php

namespace App\Http\Controllers;

use App\Models\paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        try {
            $search = request('search');
            $entries = request('entries', 5);
    
            $paket = Paket::when($search, function($query) use ($search) {
                $query->where('nama_paket', 'like', "%$search%")
                      ->orWhere('deskripsi', 'like', "%$search%")
                      ->orWhere('harga', 'like', "%$search%");
            })
            ->paginate($entries)
            ->withQueryString();
    
            return view('admin.page.paket.index', [
                'paket' => $paket,
                'search' => $search,
                'entries' => $entries
            ]);
            
        } catch(\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
        
    }

    public function store(Request $request)
    {
        try {
            $data = [
                'nama_paket' => $request->input('nama'),
                'harga' => $request->input('harga'),
                'deskripsi' => $request->input('deskripsi'),
    
            ];
    
            paket::create($data);
    
            return redirect()
            ->route('paket.index')->with('message_insert', 'Data Paket Sudah ditambahkan ');
        } catch (\Exception $e) {
            return redirect()
            ->route('error.index')->with('error_message', 'terjadi kesalahan saat menambahkan data: ' . $e->getMessage());
        };
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = [
                'nama_paket' => $request->input('nama'),
                'harga' => $request->input('harga'),
                'deskripsi' => $request->input('deskripsi'),
            ];
    
            $datas = paket::findOrFail($id);
            $datas->update($data);
            return redirect()
            ->route('paket.index')->with('message_insert', 'Data Paket Berhasil diPerbarui ');
        } catch (\Exception $e) {
            return redirect()
            ->route('error.index')->with('error_message', 'terjadi kesalahan saat menambahkan data: ' . $e->getMessage());
        };       
    }

    public function destroy($id)
    {   
        try {
            $data = paket::findOrFail($id);
            $data->delete();
            return back()->with('message_delete', 'Data Paket Berhasil DiHapus ');
        } catch(\Exception $e){
            return back()->with('error_mesaage', 'Terjadi kesalahan saat melakukan delete data: ' . $e->getMessage());
        }
        
    }
}
