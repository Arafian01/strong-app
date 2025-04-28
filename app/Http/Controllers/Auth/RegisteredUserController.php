<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $pakets = Paket::all();
        return view('auth.register')->with('paket', $pakets);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction(); 
    
        try {
            $user = User::create([
                'name' => $request->name, 
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pelanggan',
            ]);
    
            Pelanggan::create([
                'user_id' => $user->id,
                'paket_id' => $request->paket_id,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'status' => 'nonaktif',
                'tanggal_langganan' => now(),
                'tanggal_aktif' => null, // ganti dengan kosong
            ]);
    
            DB::commit(); // Simpan data ke database
    
            event(new Registered($user));    
            return redirect()->route('login')->with('message_success', 'Registrasi berhasil. Silakan login.');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            return back()->withErrors(['error' => 'Terjadi kesalahan saat registrasi.' . $e->getMessage()]);
        }
    }
}
