<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Modul5RbacTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $petugas;

    protected User $siswa;

    protected User $siswa2;

    protected Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        $this->kategori = Kategori::firstOrCreate(['nama' => 'Teknologi Informasi']);

        $this->admin = User::where('email', 'admin@perpustakaan.com')->firstOrFail();
        $this->petugas = User::where('email', 'petugas@perpustakaan.com')->firstOrFail();
        $this->siswa = User::where('email', 'siswa@perpustakaan.com')->firstOrFail();
        $this->siswa2 = User::where('email', 'siswa2@perpustakaan.com')->firstOrFail();
    }

    /**
     * Skenario 1: Belum login -> Membuka halaman dashboard admin -> Dialihkan ke halaman login
     */
    public function test_skenario_1_unauthenticated_user_redirected_to_login_when_accessing_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Skenario 2: Admin -> Membuka dashboard admin -> Dashboard admin dapat diakses
     */
    public function test_skenario_2_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Administrasi');
    }

    /**
     * Skenario 3: Admin -> Menambah, mengubah, dan menghapus data buku -> Operasi berhasil
     */
    public function test_skenario_3_admin_can_manage_books(): void
    {
        // 1. Tambah Buku
        $responseCreate = $this->actingAs($this->admin)->post('/buku', [
            'judul' => 'Buku Baru Admin',
            'penulis' => 'Penulis Admin',
            'tahun_terbit' => 2024,
            'kategori_id' => $this->kategori->id,
        ]);
        $responseCreate->assertRedirect('/buku');
        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Baru Admin']);

        $buku = Buku::where('judul', 'Buku Baru Admin')->firstOrFail();

        // 2. Ubah Buku
        $responseUpdate = $this->actingAs($this->admin)->put("/buku/{$buku->id}", [
            'judul' => 'Buku Admin Diperbarui',
            'penulis' => 'Penulis Admin Update',
            'tahun_terbit' => 2025,
            'kategori_id' => $this->kategori->id,
        ]);
        $responseUpdate->assertRedirect('/buku');
        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Admin Diperbarui']);

        // 3. Hapus Buku
        $responseDelete = $this->actingAs($this->admin)->delete("/buku/{$buku->id}");
        $responseDelete->assertRedirect('/buku');
        $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
    }

    /**
     * Skenario 4: Admin -> Mengelola akun dan role pengguna -> Operasi berhasil
     */
    public function test_skenario_4_admin_can_manage_users_and_roles(): void
    {
        // Buka daftar pengguna
        $this->actingAs($this->admin)->get('/admin/users')->assertOk();

        // Tambah pengguna baru
        $responseStore = $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'Pengguna Baru Test',
            'email' => 'newuser@perpustakaan.com',
            'password' => 'password123',
            'role' => 'petugas',
        ]);
        $responseStore->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', ['email' => 'newuser@perpustakaan.com']);

        $newUser = User::where('email', 'newuser@perpustakaan.com')->firstOrFail();
        $this->assertTrue($newUser->hasRole('petugas'));

        // Ubah role pengguna
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/users/{$newUser->id}", [
            'name' => 'Pengguna Baru Diubah',
            'email' => 'newuser@perpustakaan.com',
            'role' => 'siswa',
        ]);
        $responseUpdate->assertRedirect('/admin/users');
        $newUser->refresh();
        $this->assertTrue($newUser->hasRole('siswa'));

        // Hapus pengguna
        $responseDelete = $this->actingAs($this->admin)->delete("/admin/users/{$newUser->id}");
        $responseDelete->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }

    /**
     * Skenario 5: Admin -> Mengakses data peminjaman, pengembalian, dan laporan -> Data dapat diakses
     */
    public function test_skenario_5_admin_can_access_peminjaman_and_laporan(): void
    {
        $this->actingAs($this->admin)->get('/peminjaman')->assertOk();
        $this->actingAs($this->admin)->get('/laporan')->assertOk();
    }

    /**
     * Skenario 6: Petugas -> Membuka dashboard petugas -> Dashboard petugas dapat diakses
     */
    public function test_skenario_6_petugas_can_access_petugas_dashboard(): void
    {
        $response = $this->actingAs($this->petugas)->get('/petugas/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Operasional Petugas');
    }

    /**
     * Skenario 7: Petugas -> Mengelola data buku -> Operasi berhasil
     */
    public function test_skenario_7_petugas_can_manage_books(): void
    {
        // Tambah Buku oleh Petugas
        $responseCreate = $this->actingAs($this->petugas)->post('/buku', [
            'judul' => 'Buku Baru Petugas',
            'penulis' => 'Penulis Petugas',
            'tahun_terbit' => 2023,
            'kategori_id' => $this->kategori->id,
        ]);
        $responseCreate->assertRedirect('/buku');
        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Baru Petugas']);

        $buku = Buku::where('judul', 'Buku Baru Petugas')->firstOrFail();

        // Ubah Buku oleh Petugas
        $responseUpdate = $this->actingAs($this->petugas)->put("/buku/{$buku->id}", [
            'judul' => 'Buku Petugas Diperbarui',
            'penulis' => 'Penulis Petugas Update',
            'tahun_terbit' => 2024,
            'kategori_id' => $this->kategori->id,
        ]);
        $responseUpdate->assertRedirect('/buku');
        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Petugas Diperbarui']);

        // Hapus Buku oleh Petugas
        $responseDelete = $this->actingAs($this->petugas)->delete("/buku/{$buku->id}");
        $responseDelete->assertRedirect('/buku');
        $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
    }

    /**
     * Skenario 8: Petugas -> Memproses peminjaman dan pengembalian -> Operasi berhasil
     */
    public function test_skenario_8_petugas_can_process_peminjaman_and_pengembalian(): void
    {
        $buku = Buku::create([
            'judul' => 'Buku Contoh Sirkulasi',
            'penulis' => 'Penulis Contoh',
            'tahun_terbit' => 2022,
            'kategori_id' => $this->kategori->id,
        ]);

        $peminjaman = Peminjaman::create([
            'user_id' => $this->siswa->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'diajukan',
        ]);

        // 1. Petugas menyetujui peminjaman
        $responseApprove = $this->actingAs($this->petugas)->post("/peminjaman/{$peminjaman->id}/setujui");
        $responseApprove->assertSessionHas('success');
        $this->assertEquals('dipinjam', $peminjaman->fresh()->status);

        // 2. Petugas mencatat pengembalian buku
        $responseReturn = $this->actingAs($this->petugas)->post("/peminjaman/{$peminjaman->id}/kembali");
        $responseReturn->assertSessionHas('success');
        $this->assertEquals('dikembalikan', $peminjaman->fresh()->status);
        $this->assertNotNull($peminjaman->fresh()->tanggal_kembali);
    }

    /**
     * Skenario 9: Petugas -> Membuka halaman pengelolaan akun atau role pengguna -> Akses ditolak (403)
     */
    public function test_skenario_9_petugas_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->petugas)->get('/admin/users');

        $response->assertForbidden();
    }

    /**
     * Skenario 10: Siswa -> Melihat daftar dan detail buku -> Data buku dapat dilihat
     */
    public function test_skenario_10_siswa_can_view_book_list_and_details(): void
    {
        $buku = Buku::create([
            'judul' => 'Buku Belajar Web',
            'penulis' => 'Penulis Web',
            'tahun_terbit' => 2023,
            'kategori_id' => $this->kategori->id,
        ]);

        // Melihat daftar buku
        $responseList = $this->actingAs($this->siswa)->get('/buku');
        $responseList->assertOk();
        $responseList->assertSee('Buku Belajar Web');

        // Melihat detail buku
        $responseDetail = $this->actingAs($this->siswa)->get("/buku/{$buku->id}");
        $responseDetail->assertOk();
        $responseDetail->assertSee('Buku Belajar Web');
        $responseDetail->assertSee('Penulis Web');
    }

    /**
     * Skenario 11: Siswa -> Melihat dan mengubah profil sendiri -> Data profil dapat diakses dan diperbarui
     */
    public function test_skenario_11_siswa_can_view_and_update_own_profile(): void
    {
        // Melihat profil sendiri
        $responseView = $this->actingAs($this->siswa)->get('/profile');
        $responseView->assertOk();

        // Mengubah profil sendiri
        $responseUpdate = $this->actingAs($this->siswa)->patch('/profile', [
            'name' => 'Nama Siswa Baru',
            'email' => 'siswa_baru@perpustakaan.com',
        ]);
        $responseUpdate->assertSessionHasNoErrors();
        $this->siswa->refresh();
        $this->assertEquals('Nama Siswa Baru', $this->siswa->name);
        $this->assertEquals('siswa_baru@perpustakaan.com', $this->siswa->email);
    }

    /**
     * Skenario 12: Siswa -> Melihat riwayat peminjaman sendiri -> Riwayat milik sendiri dapat dilihat
     */
    public function test_skenario_12_siswa_can_view_own_borrowing_history(): void
    {
        $buku = Buku::create([
            'judul' => 'Buku Pinjaman Siswa',
            'penulis' => 'Penulis Pinjam',
            'tahun_terbit' => 2021,
            'kategori_id' => $this->kategori->id,
        ]);

        Peminjaman::create([
            'user_id' => $this->siswa->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'dipinjam',
        ]);

        $response = $this->actingAs($this->siswa)->get('/riwayat-peminjaman');

        $response->assertOk();
        $response->assertSee('Buku Pinjaman Siswa');
    }

    /**
     * Skenario 13: Siswa -> Membuka dashboard admin atau halaman pengelolaan buku -> Akses ditolak (403)
     */
    public function test_skenario_13_siswa_cannot_access_admin_dashboard_or_book_management(): void
    {
        // 1. Coba buka dashboard admin
        $responseAdmin = $this->actingAs($this->siswa)->get('/admin/dashboard');
        $responseAdmin->assertForbidden();

        // 2. Coba buka halaman tambah/kelola buku
        $responseCreateBuku = $this->actingAs($this->siswa)->get('/buku/create');
        $responseCreateBuku->assertForbidden();
    }

    /**
     * Skenario 14: Siswa -> Mencoba melihat riwayat peminjaman siswa lain -> Akses ditolak (403)
     */
    public function test_skenario_14_siswa_cannot_view_other_students_borrowing(): void
    {
        $buku = Buku::create([
            'judul' => 'Buku Rahasia Siswa 2',
            'penulis' => 'Penulis Rahasia',
            'tahun_terbit' => 2020,
            'kategori_id' => $this->kategori->id,
        ]);

        $peminjamanSiswa2 = Peminjaman::create([
            'user_id' => $this->siswa2->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'dipinjam',
        ]);

        // Siswa 1 mencoba melihat riwayat transaksi milik Siswa 2
        $response = $this->actingAs($this->siswa)->get("/riwayat-peminjaman/{$peminjamanSiswa2->id}");

        $response->assertForbidden();
    }

    /**
     * Skenario 15: Admin/Petugas/Siswa -> Melakukan logout lalu membuka halaman yang dilindungi -> Pengguna harus login kembali
     */
    public function test_skenario_15_logout_and_access_protected_pages_redirects_to_login(): void
    {
        $this->actingAs($this->siswa);

        // Lakukan logout
        $responseLogout = $this->post('/logout');
        $responseLogout->assertRedirect('/');

        $this->assertGuest();

        // Buka halaman yang dilindungi setelah logout
        $responseProtected = $this->get('/riwayat-peminjaman');
        $responseProtected->assertRedirect('/login');

        $responseProfile = $this->get('/profile');
        $responseProfile->assertRedirect('/login');
    }
}
