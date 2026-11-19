@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Laporan Rekapitulasi Perpustakaan</h1>
            <p class="text-slate-600 mt-1">Laporan sirkulasi peminjaman, data koleksi, dan statistik aktivitas perpustakaan.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-medium text-sm rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Koleksi Buku</span>
            <div class="text-2xl font-black text-blue-600 mt-1">{{ $totalBuku }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Total Transaksi</span>
            <div class="text-2xl font-black text-purple-600 mt-1">{{ $totalPeminjaman }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Sedang Dipinjam</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $totalDipinjam }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Sudah Dikembalikan</span>
            <div class="text-2xl font-black text-green-600 mt-1">{{ $totalDikembalikan }}</div>
        </div>
    </div>

    <!-- Filter & Tabel -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h3 class="font-bold text-slate-800 text-base">Rincian Riwayat Transaksi</h3>

            <form method="GET" action="{{ route('laporan.index') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="text-xs font-semibold px-3 py-2 rounded-lg border border-slate-300 bg-slate-50">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 uppercase text-xs">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Peminjam</th>
                        <th class="p-4">Judul Buku</th>
                        <th class="p-4">Tgl Pinjam</th>
                        <th class="p-4">Tgl Kembali</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayatLaporan as $index => $r)
                        <tr>
                            <td class="p-4 text-slate-500 font-medium">{{ $riwayatLaporan->firstItem() + $index }}</td>
                            <td class="p-4 font-semibold text-slate-800">
                                {{ $r->user?->name ?? ($r->anggota?->nama ?? 'Pengguna') }}
                            </td>
                            <td class="p-4 text-slate-700">
                                {{ $r->buku?->judul ?? 'Buku' }}
                            </td>
                            <td class="p-4 text-slate-600">{{ $r->tanggal_pinjam }}</td>
                            <td class="p-4 text-slate-600">{{ $r->tanggal_kembali ?? '-' }}</td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $r->status === 'dikembalikan' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $r->status ?? 'dipinjam' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada riwayat transaksi yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $riwayatLaporan->links() }}
        </div>
    </div>
</div>
@endsection

