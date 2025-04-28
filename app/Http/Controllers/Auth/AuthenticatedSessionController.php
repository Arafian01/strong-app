<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Pelanggan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    
        $user = Auth::user();
    
        // Jika user adalah pelanggan, cek statusnya
        if ($user->role === 'pelanggan') {
            $pelanggan = Pelanggan::where('user_id', $user->id)->first();
            
            if ($pelanggan && $pelanggan->status === 'nonaktif') {
                Auth::logout(); // Logout user
                return back()->withErrors(['email' => 'Akun belum diverifikasi.']);
            }
        }
    
        // Redirect ke dashboard setelah login berhasil
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
