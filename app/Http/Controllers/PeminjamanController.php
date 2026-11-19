<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan seluruh data peminjaman untuk Admin & Petugas.
     */
    public function index(): View
    {
        $peminjamans = Peminjaman::with(['user', 'buku', 'anggota'])->latest()->paginate(10);
        $bukus = Buku::orderBy('judul')->get();

        return view('peminjaman.index', compact('peminjamans', 'bukus'));
    }

    /**
     * Tampilkan riwayat peminjaman milik pengguna yang sedang login (Siswa).
     */
    public function riwayatSaya(): View
    {
        $peminjamans = Peminjaman::where('user_id', auth()->id())
            ->with('buku')
            ->latest()
            ->paginate(10);

        return view('peminjaman.riwayat', compact('peminjamans'));
    }

    /**
     * Tampilkan detail riwayat peminjaman tertentu.
     * Hak akses diperiksa menggunakan Policy view (menggantikan pengecekan manual Modul 5).
     */
    public function showRiwayat(int $id): View
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        /* =========================================================================
         | [SOAL 4] MENGGANTIKAN PEMERIKSAAN KEPEMILIKAN MANUAL DENGAN POLICY
         | Menolak akses (403) jika user bukan pemilik dan bukan admin/petugas
         | ========================================================================= */
        Gate::authorize('view', $peminjaman);

        return view('peminjaman.detail_riwayat', compact('peminjaman'));
    }

    /* =========================================================================
     | [PERBAIKAN SOAL 4 / MENUTUP CELAH A4] OTORISASI DENGAN POLICY (IDOR FIX)
     | Memanggil Gate::authorize('view', $peminjaman) sebelum merender view
     | ========================================================================= */
    public function show(int $id): View
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])->findOrFail($id);

        Gate::authorize('view', $peminjaman);

        return view('peminjaman.show', compact('peminjaman'));
    }
    /* =========================================================================
     | AKHIR PERBAIKAN SOAL 4
     | ========================================================================= */

    /**
     * Ajukan peminjaman buku oleh Siswa/Petugas/Admin.
     */
    public function ajukan(Request $request): RedirectResponse
    {
        $request->validate([
            'buku_id' => ['required', 'exists:bukus,id'],
            'tanggal_pinjam' => ['nullable', 'date'],
        ]);

        $tanggalPinjam = $request->input('tanggal_pinjam', now()->toDateString());

        Peminjaman::create([
            'user_id' => auth()->id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $tanggalPinjam,
            'status' => 'dipinjam',
        ]);

        if (auth()->user()->hasRole('siswa')) {
            return redirect()->route('riwayat.index')->with('success', 'Buku berhasil dipinjam / diajukan!');
        }

        return redirect()->back()->with('success', 'Peminjaman berhasil dicatat.');
    }

    /**
     * Memproses persetujuan peminjaman (Admin & Petugas).
     */
    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->update([
            'status' => 'dipinjam',
        ]);

        return redirect()->back()->with('success', 'Peminjaman buku berhasil disetujui.');
    }

    /**
     * Mencatat pengembalian buku (Admin & Petugas).
     */
    public function kembali(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
            'status' => 'dikembalikan',
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dicatat telah dikembalikan.');
    }

    /**
     * Hapus data peminjaman (Admin & Petugas).
     */
    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->delete();

        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
