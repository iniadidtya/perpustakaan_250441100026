@extends('layouts.app')

@section('title', 'Tambah Pengguna & Role')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-purple-600 hover:underline font-semibold">&larr; Kembali ke Daftar Pengguna</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Tambah Pengguna Baru</h1>
        <p class="text-slate-600 text-sm">Buat akun baru dan tetapkan role hak aksesnya.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm" placeholder="Contoh: Budi Santoso">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm" placeholder="Contoh: user@perpustakaan.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi (Password) <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm" placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">Tetapkan Role / Hak Akses <span class="text-red-500">*</span></label>
                    <select name="role" id="role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
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
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

