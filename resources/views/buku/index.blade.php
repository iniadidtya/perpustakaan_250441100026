@extends('layouts.app')

@section('title', 'Katalog & Manajemen Buku')

@section('content')
<div style="max-width: 1200px; margin: 24px auto; padding: 0 16px;">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0; font-size: 1.75rem; color: #0f172a; font-weight: 700;">Katalog & Koleksi Buku</h2>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 0.95rem;">
                {{-- [SOAL 3] Menggunakan Gate @can('kelola-buku') --}}
                @can('kelola-buku')
                    Kelola data buku (CRUD Real-time & API Resource).
                @else
                    Jelajahi koleksi buku perpustakaan digital kami.
                @endcan
            </p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            {{-- [SOAL 3] Tombol Tambah Buku dilindungi Gate @can('kelola-buku') --}}
            @can('kelola-buku')
                <a href="{{ route('buku.create') }}" class="btn-top" style="background: linear-gradient(135deg, #16a34a, #15803d); color: white;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Buku (Form Web)
                </a>
            @endcan
            <button type="button" onclick="loadBuku()" class="btn-top" style="background: linear-gradient(135deg, #0284c7, #0369a1); color: white;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Segarkan Data
            </button>
        </div>
    </div>

    <!-- Kotak Pesan Notifikasi (Sukses / Error) -->
    <div id="pesan-notifikasi" style="display: none; margin-bottom: 24px; padding: 14px 18px; border-radius: 12px; font-weight: 500; font-size: 0.95rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: all 0.3s ease;"></div>

    <!-- Grid: Form Input & Tabel Data -->
    <div style="display: grid; grid-template-columns: @can('kelola-buku') 360px 1fr @else 1fr @endcan; gap: 24px; align-items: start;">
        
        @can('kelola-buku')
        <!-- Kolom Form (Create / Update Khusus Admin & Petugas) -->
        <div style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 16px; padding: 24px; box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #eff6ff; color: #2563eb;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </span>
                <h3 id="form-title" style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #0f172a;">Tambah Buku Baru</h3>
            </div>

            <form id="form-buku">
                <input type="hidden" id="buku_id" name="buku_id" value="">

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label for="kategori_id" style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Kategori Buku <span style="color: #ef4444;">*</span></label>
                        <select id="kategori_id" name="kategori_id" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 0.92rem; color: #1e293b; background-color: #fff; outline: none;">
                            <option value="">-- Pilih Kategori --</option>
                            @php
                                $kategoris = \App\Models\Kategori::orderBy('nama')->get();
                            @endphp
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }} (ID: {{ $kategori->id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="judul" style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Judul Buku <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="judul" name="judul" required placeholder="Contoh: Belajar Laravel" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 0.92rem; color: #1e293b; outline: none;">
                    </div>

                    <div>
                        <label for="penulis" style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Nama Penulis <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="penulis" name="penulis" required placeholder="Contoh: Penulis Buku" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 0.92rem; color: #1e293b; outline: none;">
                    </div>

                    <div>
                        <label for="tahun_terbit" style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Tahun Terbit <span style="color: #ef4444;">*</span></label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" min="1900" max="{{ date('Y') }}" required placeholder="Contoh: {{ date('Y') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 0.92rem; color: #1e293b; outline: none;">
                    </div>
                </div>

                <div style="margin-top: 22px; display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" id="btn-submit" class="btn-rounded" style="width: 100%; padding: 0.75rem 1.25rem; font-size: 0.95rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; box-shadow: 0 4px 8px -1px rgba(37, 99, 235, 0.35);">
                        Simpan Buku
                    </button>
                    <button type="button" id="btn-batal" onclick="batalEdit()" class="btn-rounded" style="display: none; width: 100%; padding: 0.65rem 1.25rem; font-size: 0.9rem; font-weight: 600; background: #e2e8f0; color: #475569;">
                        Batal
                    </button>
                </div>
            </form>
        </div>
        @endcan

        <!-- Kolom Tabel Data Buku -->
        <div style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 16px; padding: 24px; box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05); overflow: hidden;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #0f172a;">Daftar Koleksi Buku</h3>
                <span id="badge-total" style="font-size: 0.85rem; padding: 4px 12px; background: #f1f5f9; color: #475569; border-radius: 9999px; font-weight: 600;">
                    Total: {{ count($buku) }} Buku
                </span>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 12px 14px; text-align: center; width: 50px;">No</th>
                            <th style="padding: 12px 14px;">Kategori</th>
                            <th style="padding: 12px 14px;">Judul Buku</th>
                            <th style="padding: 12px 14px;">Penulis</th>
                            <th style="padding: 12px 14px; text-align: center;">Tahun</th>
                            <th style="padding: 12px 14px; text-align: center;">Dipinjam</th>
                            <th style="padding: 12px 14px; text-align: center; width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-buku">
                        @forelse($buku as $item)
                            @php
                                $bukuId = is_array($item) ? $item['id'] : $item->id;
                                $bukuJudul = is_array($item) ? $item['judul'] : $item->judul;
                                $bukuPenulis = is_array($item) ? $item['penulis'] : $item->penulis;
                                $bukuTahun = is_array($item) ? $item['tahun_terbit'] : $item->tahun_terbit;
                                $bukuKategori = is_array($item) ? ($item['kategori'] ?? 'Tanpa Kategori') : ($item->kategori?->nama ?? 'Tanpa Kategori');
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px 14px; text-align: center; color: #64748b;">{{ $loop->iteration }}</td>
                                <td style="padding: 12px 14px;"><span class="badge-kategori">{{ $bukuKategori }}</span></td>
                                <td style="padding: 12px 14px; font-weight: 600; color: #0f172a;">
                                    {{-- ========================================================================= --}}
                                    {{-- [PERBAIKAN SOAL 2 (a) / CELAH A1] MENCEGAH XSS DENGAN BLADE ESCAPING      --}}
                                    {{-- Menggunakan sintaks {{ }} agar teks di-escape otomatis (HTML entities)    --}}
                                    {{-- ========================================================================= --}}
                                    <a href="{{ route('buku.show', $bukuId) }}" style="color: #2563eb; text-decoration: none;">
                                        {{ $bukuJudul }}
                                    </a>
                                    {{-- ========================================================================= --}}
                                    {{-- AKHIR PERBAIKAN SOAL 2 (a)                                                --}}
                                    {{-- ========================================================================= --}}
                                </td>
                                <td style="padding: 12px 14px; color: #475569;">{{ $bukuPenulis }}</td>
                                <td style="padding: 12px 14px; text-align: center; color: #475569;">{{ $bukuTahun }}</td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    <span style="display: inline-block; padding: 2px 10px; border-radius: 9999px; background: #e0f2fe; color: #0369a1; font-size: 0.8rem; font-weight: 600;">
                                        0 kali
                                    </span>
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                        {{-- [SOAL 3] Tombol Ubah & Hapus dilindungi Gate @can('kelola-buku') --}}
                                        @can('kelola-buku')
                                            <button type="button" class="btn-action btn-ubah" onclick="editBuku({{ $bukuId }})">
                                                Ubah
                                            </button>
                                            {{-- ========================================================================= --}}
                                            {{-- [PERBAIKAN SOAL 2 (c) / CELAH A3] FORM DELETE DENGAN CSRF & METHOD DELETE --}}
                                            {{-- Menggunakan form dengan @csrf dan @method('DELETE')                      --}}
                                            {{-- ========================================================================= --}}
                                            <form action="{{ route('buku.destroy', $bukuId) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-hapus">
                                                    Hapus
                                                </button>
                                            </form>
                                            {{-- ========================================================================= --}}
                                            {{-- AKHIR PERBAIKAN SOAL 2 (c)                                                --}}
                                            {{-- ========================================================================= --}}
                                        @endcan

                                        @can('mengajukan peminjaman buku')
                                            @cannot('kelola-buku')
                                                <form action="{{ route('peminjaman.ajukan') }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="buku_id" value="{{ $bukuId }}">
                                                    <button type="submit" class="btn-action btn-pinjam">
                                                        Pinjam
                                                    </button>
                                                </form>
                                            @endcannot
                                        @endcan

                                        <a href="{{ route('buku.show', $bukuId) }}" class="btn-action btn-detail">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 28px; color: #64748b;">
                                    Belum ada data buku dalam katalog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
    /* Styling tombol rounded di bagian atas */
    .btn-top {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.65rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 12px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        transition: all 0.25s ease;
    }
    .btn-top:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* Styling tombol rounded di formulir */
    .btn-rounded {
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .btn-rounded:hover {
        transform: translateY(-1px);
    }

    /* Styling interaktif input & tabel */
    #form-buku input:focus, #form-buku select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
    }
    .badge-kategori {
        display: inline-block;
        padding: 4px 12px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Tombol Aksi di Tabel: dibuat rounded, melengkung halus dan modern */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 6px 14px;
        font-size: 0.82rem;
        font-weight: 600;
        border-radius: 12px; /* Melengkung rapi (rounded) */
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }
    .btn-action:hover {
        transform: translateY(-1.5px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
    }
    .btn-action:active {
        transform: translateY(0);
    }

    /* Warna varian tombol aksi */
    .btn-ubah {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }
    .btn-ubah:hover {
        background: #fde68a;
    }

    .btn-hapus {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }
    .btn-hapus:hover {
        background: #fecaca;
    }

    .btn-pinjam {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }
    .btn-pinjam:hover {
        background: #dbeafe;
    }

    .btn-detail {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }
    .btn-detail:hover {
        background: #e2e8f0;
    }
</style>

<script>
    const BISA_KELOLA = {{ auth()->check() && auth()->user()->can('kelola-buku') ? 'true' : 'false' }};
    const BISA_PINJAM = {{ auth()->check() && auth()->user()->can('mengajukan peminjaman buku') ? 'true' : 'false' }};
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    const API_HEADERS = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
    };

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function tampilkanPesan(pesan, tipe = 'sukses') {
        const notif = document.getElementById('pesan-notifikasi');
        if (!notif) return;
        notif.style.display = 'block';
        if (tipe === 'sukses') {
            notif.style.backgroundColor = '#ecfdf5';
            notif.style.color = '#065f46';
            notif.style.border = '1px solid #a7f3d0';
        } else {
            notif.style.backgroundColor = '#fef2f2';
            notif.style.color = '#991b1b';
            notif.style.border = '1px solid #fecaca';
        }
        notif.innerHTML = pesan;
        setTimeout(() => {
            notif.style.display = 'none';
        }, 5000);
    }

    function bersihkanForm() {
        const form = document.getElementById('form-buku');
        if (!form) return;
        form.reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('form-title').innerText = 'Tambah Buku Baru';
        document.getElementById('btn-submit').innerText = 'Simpan Buku';
        document.getElementById('btn-batal').style.display = 'none';
    }

    function batalEdit() {
        bersihkanForm();
        tampilkanPesan('Mode edit dibatalkan.', 'sukses');
    }

    async function loadBuku() {
        const tbody = document.getElementById('tabel-buku');
        const badgeTotal = document.getElementById('badge-total');

        try {
            const response = await fetch('/api/buku', {
                method: 'GET',
                headers: API_HEADERS
            });

            if (!response.ok) {
                throw new Error(`Gagal memuat API buku (Status: ${response.status})`);
            }

            const jsonResponse = await response.json();
            const dataBuku = jsonResponse.data || [];

            badgeTotal.innerText = `Total: ${dataBuku.length} Buku`;

            if (dataBuku.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 28px; color: #64748b;">
                            Tidak ada koleksi buku yang tersedia.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';
            dataBuku.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.style.borderBottom = '1px solid #f1f5f9';

                let aksiHtml = '';
                if (BISA_KELOLA) {
                    aksiHtml += `
                        <button type="button" class="btn-action btn-ubah" onclick="editBuku(${item.id})">Ubah</button>
                        <button type="button" class="btn-action btn-hapus" onclick="hapusBuku(${item.id})">Hapus</button>
                    `;
                } else if (BISA_PINJAM) {
                    aksiHtml += `
                        <form action="{{ route('peminjaman.ajukan') }}" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                            <input type="hidden" name="buku_id" value="${item.id}">
                            <button type="submit" class="btn-action btn-pinjam">Pinjam</button>
                        </form>
                    `;
                }

                aksiHtml += `
                    <a href="/buku/${item.id}" class="btn-action btn-detail">Detail</a>
                `;

                tr.innerHTML = `
                    <td style="padding: 12px 14px; text-align: center; color: #64748b;">${index + 1}</td>
                    <td style="padding: 12px 14px;">
                        <span class="badge-kategori">${escapeHtml(item.kategori_buku || (item.kategori && item.kategori.nama) || 'Tanpa Kategori')}</span>
                    </td>
                    <td style="padding: 12px 14px; font-weight: 600; color: #0f172a;">
                        <a href="/buku/${item.id}" style="color: #2563eb; text-decoration: none;">
                            ${escapeHtml(item.judul_buku || item.judul || '-')}
                        </a>
                    </td>
                    <td style="padding: 12px 14px; color: #475569;">
                        ${escapeHtml(item.penulis_buku || item.penulis || '-')}
                    </td>
                    <td style="padding: 12px 14px; text-align: center; color: #475569;">
                        ${item.tahun_publikasi || item.tahun_terbit || '-'}
                    </td>
                    <td style="padding: 12px 14px; text-align: center;">
                        <span style="display: inline-block; padding: 2px 10px; border-radius: 9999px; background: #e0f2fe; color: #0369a1; font-size: 0.8rem; font-weight: 600;">
                            ${item.jumlah_peminjaman ?? 0} kali
                        </span>
                    </td>
                    <td style="padding: 12px 14px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                            ${aksiHtml}
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });

        } catch (error) {
            console.warn('API sync warning:', error);
        }
    }

    @can('kelola-buku')
    const formBuku = document.getElementById('form-buku');
    if (formBuku) {
        formBuku.addEventListener('submit', async function (e) {
            e.preventDefault();

            const bukuId = document.getElementById('buku_id').value;
            const kategoriId = document.getElementById('kategori_id').value;
            const judul = document.getElementById('judul').value;
            const penulis = document.getElementById('penulis').value;
            const tahunTerbit = document.getElementById('tahun_terbit').value;

            const payload = {
                kategori_id: parseInt(kategoriId, 10),
                judul: judul.trim(),
                penulis: penulis.trim(),
                tahun_terbit: parseInt(tahunTerbit, 10)
            };

            const isEdit = Boolean(bukuId);
            const url = isEdit ? `/api/buku/${bukuId}` : '/api/buku';
            const method = isEdit ? 'PUT' : 'POST';

            const btnSubmit = document.getElementById('btn-submit');
            const teksAsli = btnSubmit.innerText;
            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Menyimpan...';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: API_HEADERS,
                    body: JSON.stringify(payload)
                });

                if (response.status === 422) {
                    const errorData = await response.json();
                    let daftarPesan = '<strong>Kesalahan validasi data:</strong><ul style="margin: 4px 0 0 16px;">';
                    if (errorData.errors) {
                        for (const field in errorData.errors) {
                            errorData.errors[field].forEach(msg => {
                                daftarPesan += `<li>${escapeHtml(msg)}</li>`;
                            });
                        }
                    }
                    daftarPesan += '</ul>';
                    tampilkanPesan(daftarPesan, 'error');
                    return;
                }

                if (!response.ok) {
                    const errData = await response.json().catch(() => ({}));
                    throw new Error(errData.message || `Gagal menyimpan data (Status: ${response.status})`);
                }

                bersihkanForm();
                tampilkanPesan(isEdit ? 'Buku berhasil diperbarui!' : 'Buku berhasil ditambahkan!', 'sukses');
                await loadBuku();

            } catch (error) {
                tampilkanPesan(error.message || 'Terjadi kegagalan.', 'error');
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerText = teksAsli;
            }
        });
    }

    async function editBuku(id) {
        try {
            const response = await fetch(`/api/buku/${id}`, {
                method: 'GET',
                headers: API_HEADERS
            });

            if (!response.ok) throw new Error('Buku gagal dimuat');

            const result = await response.json();
            const buku = result.data ? result.data : result;

            document.getElementById('buku_id').value = buku.id;
            document.getElementById('kategori_id').value = buku.kategori_id;
            document.getElementById('judul').value = buku.judul_buku || buku.judul || '';
            document.getElementById('penulis').value = buku.penulis_buku || buku.penulis || '';
            document.getElementById('tahun_terbit').value = buku.tahun_publikasi || buku.tahun_terbit || '';

            document.getElementById('form-title').innerText = `Ubah Buku (ID: ${buku.id})`;
            document.getElementById('btn-submit').innerText = 'Perbarui Buku';
            document.getElementById('btn-batal').style.display = 'inline-block';
            document.getElementById('judul').focus();
        } catch (error) {
            tampilkanPesan(error.message, 'error');
        }
    }

    async function hapusBuku(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;

        try {
            const response = await fetch(`/api/buku/${id}`, {
                method: 'DELETE',
                headers: API_HEADERS
            });

            if (!response.ok) throw new Error('Gagal menghapus buku');

            if (document.getElementById('buku_id')?.value == id) {
                bersihkanForm();
            }

            tampilkanPesan('Buku berhasil dihapus!', 'sukses');
            await loadBuku();
        } catch (error) {
            tampilkanPesan(error.message, 'error');
        }
    }
    @endcan

    document.addEventListener('DOMContentLoaded', () => {
        // loadBuku(); // Dinonaktifkan otomatis agar hasil render Blade {!! $bukuJudul !!} aktif untuk pembuktian Celah A1
    });
</script>
@endsection
