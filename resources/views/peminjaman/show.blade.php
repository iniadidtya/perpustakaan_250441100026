@extends('layouts.app')

@section('title', 'Detail Peminjaman (Celah A4 - IDOR)')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- ========================================================================= --}}
    {{-- [CELAH A4] VIEW DETAIL PEMINJAMAN --}}
    {{-- Menampilkan detail peminjaman tanpa filter kepemilikan siswa --}}
    {{-- ========================================================================= --}}
    <div class="mb-6">
        <a href="{{ route('riwayat.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">&larr; Kembali ke Riwayat Peminjaman</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Detail Transaksi Peminjaman</h1>
        <p class="text-slate-600 text-sm">Informasi transaksi peminjaman buku perpustakaan.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <div class="space-y-4">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <span class="text-slate-500 text-sm font-medium">Nomor Transaksi:</span>
                <span class="font-bold text-slate-800 text-sm">#TRX-{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <span class="text-slate-500 text-sm font-medium">Nama Peminjam / Akun:</span>
                <span class="font-bold text-blue-600 text-sm">{{ $peminjaman->user?->name ?? 'User ID: ' . $peminjaman->user_id }} ({{ $peminjaman->user?->email ?? '-' }})</span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <span class="text-slate-500 text-sm font-medium">Judul Buku:</span>
                <span class="font-bold text-slate-900 text-base text-right">{{ $peminjaman->buku?->judul ?? 'Buku' }}</span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <span class="text-slate-500 text-sm font-medium">Penulis:</span>
                <span class="text-slate-700 text-sm font-semibold">{{ $peminjaman->buku?->penulis ?? '-' }}</span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <span class="text-slate-500 text-sm font-medium">Tanggal Pinjam:</span>
                <span class="text-slate-800 text-sm font-semibold">{{ $peminjaman->tanggal_pinjam }}</span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <span class="text-slate-500 text-sm font-medium">Tanggal Kembali:</span>
                <span class="text-slate-800 text-sm font-semibold">{{ $peminjaman->tanggal_kembali ?? 'Belum Dikembalikan' }}</span>
            </div>

            <div class="flex justify-between items-center pt-2">
                <span class="text-slate-500 text-sm font-medium">Status Peminjaman:</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ $peminjaman->status === 'dikembalikan' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                    {{ $peminjaman->status ?? 'dipinjam' }}
                </span>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <a href="{{ route('buku.show', $peminjaman->buku_id) }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Lihat Informasi Buku di Katalog
            </a>
        </div>
    </div>
    {{-- ========================================================================= --}}
    {{-- AKHIR [CELAH A4] --}}
    {{-- ========================================================================= --}}
</div>
@endsection
