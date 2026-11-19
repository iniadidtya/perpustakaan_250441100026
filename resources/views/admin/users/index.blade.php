@extends('layouts.app')

@section('title', 'Kelola Pengguna & Role')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Akun & Role Pengguna</h1>
            <p class="text-slate-600 mt-1">Halaman manajemen pengguna khusus Administrator untuk menetapkan hak akses (RBAC).</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pengguna Baru
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">Email</th>
                        <th class="p-4 text-center">Role / Hak Akses</th>
                        <th class="p-4 text-center">Terdaftar Sejak</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 text-slate-500 font-medium">{{ $users->firstItem() + $index }}</td>
                            <td class="p-4 font-semibold text-slate-900">{{ $u->name }}</td>
                            <td class="p-4 text-slate-600">{{ $u->email }}</td>
                            <td class="p-4 text-center">
                                @forelse($u->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : ($role->name === 'petugas' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-blue-100 text-blue-700 border border-blue-200') }}">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400 italic">Tanpa Role</span>
                                @endforelse
                            </td>
                            <td class="p-4 text-center text-slate-500 text-xs">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $u) }}" class="px-3 py-1 bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 rounded-lg text-xs font-semibold transition">
                                        Ubah Role
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">Tidak ada pengguna yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

