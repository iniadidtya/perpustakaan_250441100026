@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($buku != null)
        <div class="mb-6">
            <a href="{{ route('buku.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">&larr; Kembali ke Daftar Buku</a>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-2">Detail Buku: {{ $buku['judul'] }}</h1>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500 font-medium text-sm">ID Buku</span>
                    <span class="font-bold text-slate-800 text-sm">#{{ $buku['id'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500 font-medium text-sm">Judul Buku</span>
                    <span class="font-bold text-slate-900 text-base">{{ $buku['judul'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500 font-medium text-sm">Penulis</span>
                    <span class="font-semibold text-slate-800 text-sm">{{ $buku['penulis'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500 font-medium text-sm">Tahun Terbit</span>
                    <span class="font-semibold text-slate-800 text-sm">{{ $buku['tahun_terbit'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500 font-medium text-sm">Kategori</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $buku['kategori'] }}
                    </span>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                @can('mengajukan peminjaman buku')
                    <form action="{{ route('peminjaman.ajukan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $buku['id'] }}">
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                            Pinjam Buku Ini Sekarang
                        </button>
                    </form>
                @endcan

                {{-- [SOAL 3] Tombol Ubah Buku dilindungi Gate @can('kelola-buku') --}}
                @can('kelola-buku')
                    <a href="{{ route('buku.edit', $buku['id']) }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                        Ubah Data Buku
                    </a>
                @endcan

                <a href="{{ route('buku.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
                    Kembali
                </a>
            </div>
        </div>
    @else
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
            <h2 class="text-xl font-bold text-red-700">Error: Buku Tidak Ditemukan!</h2>
            <p class="text-slate-600 text-sm mt-1">Maaf, buku yang Anda cari tidak ada di sistem kami.</p>
            <div class="mt-4">
                <a href="{{ route('buku.index') }}" class="px-4 py-2 bg-slate-700 text-white rounded-lg text-sm">Kembali</a>
            </div>
        </div>
    @endif
</div>
@endsection
