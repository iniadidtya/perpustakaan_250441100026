@extends('layouts.app')

@section('title', 'Ubah Role Pengguna')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-purple-600 hover:underline font-semibold">&larr; Kembali ke Daftar Pengguna</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Ubah Data & Role Pengguna</h1>
        <p class="text-slate-600 text-sm">Perbarui informasi dan hak akses role untuk <strong>{{ $user->name }}</strong>.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Ganti Password (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm" placeholder="Biarkan kosong untuk mempertahankan kata sandi lama">
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">Penetapan Role / Hak Akses <span class="text-red-500">*</span></label>
                    <select name="role" id="role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (old('role', $user->roles->first()?->name) === $role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold shadow-sm transition">
                    Perbarui Pengguna & Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

