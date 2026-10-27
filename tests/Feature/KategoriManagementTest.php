<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_unused_categories(): void
    {
        $admin = new User([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('kategori.store'), ['name' => 'Kebersihan'])
            ->assertRedirect(route('kategori.index'));

        $kategori = Kategori::where('name', 'Kebersihan')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('kategori.update', $kategori), ['name' => 'Fasilitas'])
            ->assertRedirect(route('kategori.index'));

        $this->assertDatabaseHas('categories', ['name' => 'Fasilitas']);
        $this->assertDatabaseMissing('categories', ['name' => 'Kebersihan']);

        $this->actingAs($admin)
            ->delete(route('kategori.destroy', $kategori))
            ->assertRedirect(route('kategori.index'));

        $this->assertDatabaseMissing('categories', ['id' => $kategori->id]);
    }

    public function test_renaming_category_updates_existing_complaints_and_used_category_cannot_be_deleted(): void
    {
        $admin = new User([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
        $kategori = Kategori::where('name', 'Pesanan')->firstOrFail();
        $pengaduan = Pengaduan::create(['kategori' => $kategori->name]);

        $this->actingAs($admin)
            ->put(route('kategori.update', $kategori), ['name' => 'Pesanan Online'])
            ->assertRedirect(route('kategori.index'));

        $this->assertDatabaseHas('pengaduans', [
            'id' => $pengaduan->id,
            'kategori' => 'Pesanan Online',
        ]);

        $this->actingAs($admin)
            ->delete(route('kategori.destroy', $kategori))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $kategori->id]);
    }

    public function test_customer_can_view_category_table_but_cannot_manage_categories(): void
    {
        $customer = new User([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);

        $this->actingAs($customer)
            ->get(route('kategori.index'))
            ->assertOk()
            ->assertSee('Tabel Kategori')
            ->assertSee('Pesanan')
            ->assertDontSee('Tambah kategori')
            ->assertDontSee('Kelola kategori');

        $this->actingAs($customer)
            ->get(route('pengaduan.create'))
            ->assertOk()
            ->assertSee('Pesanan')
            ->assertSee('Pembayaran')
            ->assertSee('Pengiriman');

        $this->actingAs($customer)
            ->post(route('kategori.store'), ['name' => 'Kategori ilegal'])
            ->assertForbidden();
    }

    public function test_last_category_cannot_be_deleted(): void
    {
        $admin = new User([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
        Kategori::query()->delete();
        $kategori = Kategori::create(['name' => 'Umum']);

        $this->actingAs($admin)
            ->delete(route('kategori.destroy', $kategori))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $kategori->id]);
    }
}
