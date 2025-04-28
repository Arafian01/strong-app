<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\pelanggan;
use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        try {
            $search = request('search');
            $entries = request('entries', 10);

            $pelanggan = Pelanggan::with(['user', 'paket'])
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->whereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        })
                            ->orWhere('alamat', 'like', "%$search%")
                            ->orWhere('telepon', 'like', "%$search%")
                            ->orWhere('status', 'like', "%$search%");
                    });
                })
                ->paginate($entries);

            return view('admin.page.pelanggan.index', [
                'pelanggan' => $pelanggan,
                'paket' => Paket::all(),
                'search' => $search,
                'entries' => $entries
            ]);
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $datauser = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role' => 'pelanggan',
            ]);

            $status = $request->input('status');
            $tanggal_aktif = null;
            if ($status == 'aktif') {
                $tanggal_aktif = now()->format('Y-m-d');
            } elseif ($status == 'nonaktif') {
                $tanggal_aktif = null;
            } elseif ($status == 'isolir') {
                $tanggal_aktif = null;
            } else {
                $tanggal_aktif = null;
            }

            Pelanggan::create([
                'user_id' => $datauser->id,
                'paket_id' => $request->input('paket_id'),
                'alamat' => $request->input('alamat'),
                'telepon' => $request->input('telepon'),
                'status' => $status,
                'tanggal_aktif' => $tanggal_aktif,
                'tanggal_langganan' => $request->input('tanggal_langganan'),
            ]);

            return redirect()
                ->route('pelanggan.index')->with('message_insert', 'Data pelanggan Sudah ditambahkan ');
        } catch (\Exception $e) {
            return redirect()
                ->route('error.index')->with('error_message', 'terjadi kesalahan saat menambahkan data: ' . $e->getMessage());
        };
    }
    public function update(Request $request, String $id)
    {

        // try {
            $pelanggan = Pelanggan::findOrFail($id);

            $status = $request->input('status');
            $tanggal_aktif = null;
            if ($status == 'aktif') {
                $tanggal_aktif = now()->format('Y-m-d');
            } elseif ($status == 'nonaktif') {
                $tanggal_aktif = null;
            } elseif ($status == 'isolir') {
                $tanggal_aktif = null;
            } else {
                $tanggal_aktif = null;
            }

            $pelanggan->update([
                'paket_id' => $request->input('paket_id'),
                'alamat' => $request->input('alamat'),
                'telepon' => $request->input('telepon'),
                'status' => $status,
                'tanggal_aktif' => $tanggal_aktif,
                'tanggal_langganan' => $request->input('tanggal_langganan'),
            ]);

            $user = User::where('id', $pelanggan->user_id)->first();

            if ($request->input('password') == "") {
                $datapassword = $user->password;
            } else {
                $datapassword = $request->input('password');
            };

            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $datapassword,
                'role' => 'pelanggan',

            ]);

            return redirect()
                ->route('pelanggan.index')->with('message_insert', 'Data pelanggan Berhasil diPerbarui ');
        // } catch (\Exception $e) {
        //     return redirect()
        //         ->route('error.index')->with('error_message', 'terjadi kesalahan saat menambahkan data: ' . $e->getMessage());
        // };
    }
    public function destroy($id)
    {
        try {
            $data = pelanggan::findOrFail($id);
            $datauser = User::findOrFail($data->user_id);
            $datauser->delete();
            $data->delete();
            return back()->with('message_success', 'Pelanggan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error_mesaage', 'Terjadi kesalahan saat melakukan delete data: ' . $e->getMessage());
        }
    }
}
