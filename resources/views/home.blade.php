@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center max-w-3xl mx-auto">
        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-700 mb-4 shadow-xs">
            Sistem Perpustakaan Digital
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-4">
            Selamat Datang di <span class="text-blue-600">Perpus Kampus</span>
        </h1>
        <p class="text-lg text-slate-600 mb-8 leading-relaxed">
            Platform perpustakaan digital terintegrasi dengan autentikasi Laravel Breeze dan Role-Based Access Control (RBAC) menggunakan Spatie Laravel Permission.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('buku.index') }}" class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                Jelajahi Katalog Buku
            </a>

            @guest
                <a href="{{ route('login') }}" class="px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-xl border border-slate-300 shadow-xs transition">
                    Masuk ke Akun
                </a>
                <a href="{{ route('register') }}" class="px-6 py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                    Daftar Sebagai Siswa
                </a>
            @else
                @role('admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                        Buka Dashboard Admin
                    </a>
                @endrole

                @role('petugas')
                    <a href="{{ route('petugas.dashboard') }}" class="px-6 py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                        Buka Dashboard Petugas
                    </a>
                @endrole

                @role('siswa')
                    <a href="{{ route('riwayat.index') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                        Riwayat Peminjaman Saya
                    </a>
                @endrole
            @endguest
        </div>
    </div>

    <!-- 3 Role RBAC Features Card Info -->
    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xl mb-4">
                👑
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Role: Admin</h3>
            <p class="text-sm text-slate-600 mb-4">Memiliki kendali penuh: mengelola akun pengguna & penetapan role, katalog buku, sirkulasi peminjaman, serta laporan perpustakaan.</p>
            <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-full">Kredensial: admin@perpustakaan.com</span>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xl mb-4">
                💼
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Role: Petugas</h3>
            <p class="text-sm text-slate-600 mb-4">Mengelola operasional harian: mengelola data buku, memproses persetujuan peminjaman, mencatat pengembalian, dan melihat laporan.</p>
            <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full">Kredensial: petugas@perpustakaan.com</span>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl mb-4">
                🎓
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Role: Siswa</h3>
            <p class="text-sm text-slate-600 mb-4">Melihat katalog buku, meminjam buku, mengecek riwayat peminjaman mandiri, serta melihat dan memperbarui profil pribadi.</p>
            <span class="text-xs font-semibold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full">Kredensial: siswa@perpustakaan.com</span>
        </div>
    </div>
</div>
@endsection
