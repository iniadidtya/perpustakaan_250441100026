@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 pb-4 border-b border-slate-200">
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 mb-2">
                Hak Akses: Siswa
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Peminjaman Buku</h1>
            <p class="text-slate-600 mt-1">Daftar buku yang Anda pinjam beserta status pengembalian terkini.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Jelajahi Katalog Buku
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">Judul Buku</th>
                        <th class="p-4">Penulis</th>
                        <th class="p-4">Tanggal Pinjam</th>
                        <th class="p-4">Tanggal Kembali</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($peminjamans as $index => $p)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 text-slate-500 font-medium">{{ $peminjamans->firstItem() + $index }}</td>
                            <td class="p-4 font-semibold text-slate-900">
                                {{ $p->buku?->judul ?? 'Buku' }}
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $p->buku?->penulis ?? '-' }}
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $p->tanggal_pinjam }}
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $p->tanggal_kembali ?? '-' }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $p->status === 'dikembalikan' ? 'bg-green-100 text-green-700 border border-green-200' : ($p->status === 'diajukan' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-amber-100 text-amber-700 border border-amber-200') }}">
                                    {{ $p->status ?? 'dipinjam' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('riwayat.show', $p->id) }}" class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-semibold transition">
                                    Detail Peminjaman
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">
                                Anda belum pernah meminjam buku.
                                <div class="mt-2">
                                    <a href="{{ route('buku.index') }}" class="text-blue-600 hover:underline font-semibold">Pilih buku sekarang &rarr;</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $peminjamans->links() }}
        </div>
    </div>
</div>
@endsection

