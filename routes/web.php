<?php

use App\Http\Controllers\API\LoginAPIController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\laporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\pembayaranUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\tagihanUserController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', [DashboardController::class, 'indexUser'])->name('dashboard')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi', [NotifikasiController::class, 'store'])->name('notifikasi.store');
    Route::post('/notifikasi/baca/{id}', [NotifikasiController::class, 'baca'])->name('notifikasi.baca');
    Route::put('/notifikasi/{id}', [NotifikasiController::class, 'update'])->name('notifikasi.update');
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    Route::resource('pembayaran', pembayaranUserController::class)->middleware(['auth']);
    Route::resource('tagihan', tagihanUserController::class)->middleware(['auth']);
});
Route::resource('error', ErrorController::class);
Route::resource('user', UserController::class)->middleware(['auth', RoleMiddleware::class]);;
Route::resource('paket', PaketController::class)->middleware(['auth', RoleMiddleware::class]);
Route::resource('pelanggan', PelangganController::class)->middleware(['auth', RoleMiddleware::class]);
Route::resource('tagihanAdmin', TagihanController::class)->middleware(['auth', RoleMiddleware::class]);
Route::resource('pembayaranAdmin', PembayaranController::class)->middleware(['auth', RoleMiddleware::class]);
Route::resource('dashboardAdmin', DashboardController::class)->middleware(['auth', RoleMiddleware::class]);
Route::resource('laporan', laporanController::class)->middleware(['auth', RoleMiddleware::class]);






require __DIR__ . '/auth.php';
