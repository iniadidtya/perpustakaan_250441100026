<?php

namespace App\Providers;

use App\Models\Peminjaman;
use App\Models\User;
use App\Policies\PeminjamanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* =========================================================================
         | [SOAL 3] DEFINISI GATE kelola-buku
         | Hanya mengizinkan role admin dan petugas untuk mengelola buku
         | ========================================================================= */
        Gate::define('kelola-buku', function (User $user): bool {
            return $user->hasAnyRole(['admin', 'petugas', 'Petugas', 'Admin']);
        });
        /* =========================================================================
         | AKHIR DEFINISI GATE [SOAL 3]
         | ========================================================================= */

        /* =========================================================================
         | [SOAL 4] REGISTRASI POLICY PeminjamanPolicy
         | Mengatur otorisasi akses transaksi peminjaman (mencegah IDOR)
         | ========================================================================= */
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);
        /* =========================================================================
         | AKHIR REGISTRASI POLICY [SOAL 4]
         | ========================================================================= */
    }
}
