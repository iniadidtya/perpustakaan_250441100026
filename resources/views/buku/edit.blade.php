@extends('layouts.app')

@section('title', 'Ubah Data Buku')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('buku.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">&larr; Kembali ke Katalog Buku</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Ubah Data Buku</h1>
        <p class="text-slate-600 text-sm">Perbarui informasi katalog buku perpustakaan.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('buku.update', $buku->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="kategori_id" class="block text-sm font-semibold text-slate-700 mb-1">Kategori Buku <span class="text-red-500">*</span></label>
                    <select name="kategori_id" id="kategori_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ (old('kategori_id', $buku->kategori_id) == $kategori->id) ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="judul" class="block text-sm font-semibold text-slate-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $buku->judul) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="penulis" class="block text-sm font-semibold text-slate-700 mb-1">Nama Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $buku->penulis) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="tahun_terbit" class="block text-sm font-semibold text-slate-700 mb-1">Tahun Terbit <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" min="1900" max="{{ date('Y') }}" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('buku.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition">
                    Perbarui Buku
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

