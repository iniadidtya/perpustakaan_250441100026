@extends('layouts.app')

@section('title', 'Kelola Sirkulasi Peminjaman & Pengembalian')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sirkulasi Peminjaman & Pengembalian</h1>
            <p class="text-slate-600 mt-1">Kelola permohonan peminjaman, persetujuan, dan pencatatan pengembalian buku.</p>
        </div>
    </div>

    <!-- Tabel Sirkulasi Peminjaman -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Daftar Seluruh Peminjaman Buku</h3>
            <span class="text-xs bg-slate-100 text-slate-600 px-3 py-1 rounded-full font-semibold">Total: {{ $peminjamans->total() }} Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Peminjam</th>
                        <th class="p-4">Judul Buku</th>
                        <th class="p-4">Tgl Pinjam</th>
                        <th class="p-4">Tgl Kembali</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi / Operasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($peminjamans as $index => $p)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 text-slate-500 font-medium">{{ $peminjamans->firstItem() + $index }}</td>
                            <td class="p-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $p->user?->name ?? ($p->anggota?->nama ?? 'Pengguna Umum') }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $p->user?->email ?? ($p->anggota?->email ?? '-') }}
                                </div>
                            </td>
                            <td class="p-4 font-medium text-slate-800">
                                {{ $p->buku?->judul ?? 'Buku Tidak Ditemukan' }}
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
                                <div class="inline-flex items-center gap-2">
                                    @if($p->status === 'diajukan')
                                        @can('memproses persetujuan peminjaman')
                                            <form action="{{ route('peminjaman.setujui', $p) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-xs font-semibold shadow-sm transition">
                                                    Setujui
                                                </button>
                                            </form>
                                        @endcan
                                    @endif

                                    @if($p->status !== 'dikembalikan')
                                        @can('mencatat pengembalian buku')
                                            <form action="{{ route('peminjaman.kembali', $p) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 rounded-lg text-xs font-semibold shadow-sm transition">
                                                    Catat Kembali
                                                </button>
                                            </form>
                                        @endcan
                                    @endif

                                    @can('kelola peminjaman')
                                        <form action="{{ route('peminjaman.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus data transaksi ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">Belum ada transaksi peminjaman buku.</td>
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

