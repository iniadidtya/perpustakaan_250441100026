<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * [SOAL 4] Policy Method View untuk Menutup Celah IDOR (A4)
     * Admin & petugas berhak melihat semua data peminjaman.
     * Siswa hanya berhak melihat data transaksi peminjaman miliknya sendiri.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasAnyRole(['admin', 'petugas', 'Admin', 'Petugas'])) {
            return true;
        }

        return (int) $user->id === (int) $peminjaman->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Peminjaman $peminjaman): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peminjaman $peminjaman): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Peminjaman $peminjaman): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Peminjaman $peminjaman): bool
    {
        return false;
    }
}
