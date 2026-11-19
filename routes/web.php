<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama & Katalog Publik
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{id}', [BukuController::class, 'show'])->whereNumber('id')->name('buku.show');

// Rute yang Dilindungi Autentikasi (auth)
Route::middleware('auth')->group(function () {

    /* =========================================================================
     | [PERBAIKAN SOAL 2 (b) / CELAH A2] ROUTE PENCARIAN BUKU DENGAN PARAMETER BINDING
     | Route GET /buku-cari di balik auth dengan method cari() ber-parameter binding
     | ========================================================================= */
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');
    /* =========================================================================
     | AKHIR PERBAIKAN SOAL 2 (b)
     | ========================================================================= */

    /* =========================================================================
     | [PERBAIKAN SOAL 2 (c) / CELAH A3] PENGHAPUSAN ROUTE GET A3 UNTUK CSRF
     | Route GET /buku/{buku}/hapus telah DIHAPUS.
     | Penghapusan buku kini hanya menggunakan route DELETE /buku/{id} dengan form @csrf.
     | ========================================================================= */

    /* =========================================================================
     | [CELAH A4] IDOR: DETAIL PEMINJAMAN TANPA CEK HAK MILIK PENGGUNA
     | Route GET /peminjaman/{id} di balik auth
     | ========================================================================= */
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
        ->whereNumber('id')
        ->name('peminjaman.show');
    /* =========================================================================
     | AKHIR [CELAH A4]
     | ========================================================================= */

    // Pengalihan Dashboard Terpusat
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna (Siswa, Petugas, Admin)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /* =========================================================================
     | [SOAL 3] PUSATKAN ATURAN PENGELOLAAN BUKU PADA GATE kelola-buku
     | Menggantikan pemeriksaan role/permission dengan middleware can:kelola-buku
     | ========================================================================= */
    Route::middleware('can:kelola-buku')->group(function () {
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->whereNumber('id')->name('buku.edit');
        Route::put('/buku/{id}', [BukuController::class, 'update'])->whereNumber('id')->name('buku.update');
        Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->whereNumber('id')->name('buku.destroy');
    });
    /* =========================================================================
     | AKHIR [SOAL 3]
     | ========================================================================= */

    // Peminjaman Buku: Pengajuan & Riwayat Mandiri (Admin, Petugas, Siswa)
    Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'ajukan'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('riwayat.index');
    Route::get('/riwayat-peminjaman/{id}', [PeminjamanController::class, 'showRiwayat'])
        ->whereNumber('id')
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('riwayat.show');

    // Pengelolaan Peminjaman & Pengembalian (Admin & Petugas)
    Route::middleware('permission:kelola peminjaman')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
            ->middleware('permission:memproses persetujuan peminjaman')
            ->name('peminjaman.setujui');
        Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])
            ->middleware('permission:mencatat pengembalian buku')
            ->name('peminjaman.kembali');
        Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])
            ->name('peminjaman.destroy');
    });

    // Laporan Perpustakaan (Admin & Petugas)
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');

    // Dashboard Admin & Manajemen Pengguna (Admin Saja)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->middleware('permission:akses dashboard admin')
            ->name('dashboard');

        Route::middleware('permission:kelola data pengguna')->group(function () {
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
            Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        });

        Route::middleware('permission:kelola role pengguna')->group(function () {
            Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        });
    });

    // Dashboard Petugas (Petugas Saja)
    Route::prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])
            ->middleware('permission:akses dashboard petugas')
            ->name('dashboard');
    });
});

require __DIR__.'/auth.php';
