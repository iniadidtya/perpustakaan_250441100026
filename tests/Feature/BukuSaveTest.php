<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class BukuSaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_a_book_from_the_web_form(): void
    {
        $kategori = Kategori::create(['nama' => 'Teknologi']);

        $user = User::factory()->create();
        $user->givePermissionTo(Permission::findOrCreate('kelola data buku', 'web'));

        $payload = [
            'kategori_id' => $kategori->id,
            'judul' => 'Laravel untuk Pemula',
            'penulis' => 'Rizki',
            'tahun_terbit' => 2026,
        ];

        $this->actingAs($user)
            ->post(route('buku.store'), $payload)
            ->assertRedirect(route('buku.index'));

        $this->assertDatabaseHas('bukus', $payload);
    }
}
