@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-slate-200">
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 mb-2">
                Hak Akses: Administrator
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Administrasi</h1>
            <p class="text-slate-600 mt-1">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>. Kelola seluruh aspek perpustakaan dari sini.</p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Kelola Pengguna & Role
            </a>
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Lihat Laporan
            </a>
        </div>
    </div>

    <!-- Statistik Kartu Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                📚
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Koleksi Buku</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $totalBuku }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                👥
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Pengguna Terdaftar</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $totalUser }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                ⏳
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Peminjaman Sedang Berlangsung</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $peminjamanAktif }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center font-bold text-xl">
                📑
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Transaksi</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $totalPeminjaman }}</h3>
            </div>
        </div>
    </div>

    <!-- Quick Action & Peminjaman Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Transaksi Peminjaman Terbaru</h3>
                <a href="{{ route('peminjaman.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">Semua Transaksi &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="p-3">Peminjam</th>
                            <th class="p-3">Buku</th>
                            <th class="p-3">Tgl Pinjam</th>
                            <th class="p-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($peminjamanTerbaru as $p)
                            <tr>
                                <td class="p-3 font-medium text-slate-800">
                                    {{ $p->user?->name ?? ($p->anggota?->nama ?? 'Umum') }}
                                </td>
                                <td class="p-3 text-slate-600">
                                    {{ $p->buku?->judul ?? 'Buku Telah Dihapus' }}
                                </td>
                                <td class="p-3 text-slate-500">
                                    {{ $p->tanggal_pinjam }}
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $p->status === 'dikembalikan' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-slate-400">Belum ada transaksi peminjaman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Pintasan Fitur Admin</h3>
            <div class="space-y-3">
                <a href="{{ route('buku.index') }}" class="block p-3 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition">
                    <div class="font-semibold text-slate-800 text-sm">Kelola Katalog Buku</div>
                    <div class="text-xs text-slate-500 mt-0.5">Tambah, edit, dan hapus katalog buku perpustakaan.</div>
                </a>
                <a href="{{ route('admin.users.index') }}" class="block p-3 rounded-xl border border-slate-200 hover:border-purple-500 hover:bg-purple-50/50 transition">
                    <div class="font-semibold text-slate-800 text-sm">Kelola Akun & Role Pengguna</div>
                    <div class="text-xs text-slate-500 mt-0.5">Atur hak akses pengguna menjadi Admin, Petugas, atau Siswa.</div>
                </a>
                <a href="{{ route('peminjaman.index') }}" class="block p-3 rounded-xl border border-slate-200 hover:border-amber-500 hover:bg-amber-50/50 transition">
                    <div class="font-semibold text-slate-800 text-sm">Kelola Peminjaman & Pengembalian</div>
                    <div class="text-xs text-slate-500 mt-0.5">Catat sirkulasi buku dan pengembalian.</div>
                </a>
                <a href="{{ route('laporan.index') }}" class="block p-3 rounded-xl border border-slate-200 hover:border-green-500 hover:bg-green-50/50 transition">
                    <div class="font-semibold text-slate-800 text-sm">Laporan Perpustakaan</div>
                    <div class="text-xs text-slate-500 mt-0.5">Rekapitulasi sirkulasi dan statistik.</div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

