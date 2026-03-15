<?php

namespace Tests\Feature\Master;

use App\Models\BarangB3;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarangB3ControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_barang_b3_index()
    {
        $response = $this->actingAs($this->user)->get(route('master.barang-b3.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.barang-b3.index');
    }

    /** @test */
    public function user_can_create_barang_b3()
    {
        $data = [
            'nama' => 'Bahan Kimia Test',
            'un_number' => 'UN1234',
            'kelas' => '3',
            'kategori' => 'Cair',
        ];

        $response = $this->actingAs($this->user)->post(route('master.barang-b3.store'), $data);

        $response->assertRedirect(route('master.barang-b3.index'));
        $response->assertSessionHas('success', 'Data barang B3 berhasil ditambahkan.');

        $this->assertDatabaseHas('barang_b3s', [
            'nama' => 'Bahan Kimia Test',
            'un_number' => 'UN1234',
            'kelas' => '3',
        ]);
    }

    /** @test */
    public function user_can_update_barang_b3()
    {
        $barangB3 = BarangB3::factory()->create([
            'nama' => 'Bahan Lama',
            'un_number' => 'UN0001',
            'kelas' => '1',
        ]);

        $data = [
            'nama' => 'Bahan Baru',
            'un_number' => 'UN9999',
            'kelas' => '8',
            'kategori' => 'Padat',
        ];

        $response = $this->actingAs($this->user)->put(route('master.barang-b3.update', $barangB3), $data);

        $response->assertRedirect(route('master.barang-b3.index'));
        $response->assertSessionHas('success', 'Data barang B3 berhasil diperbarui.');

        $this->assertDatabaseHas('barang_b3s', [
            'id' => $barangB3->id,
            'nama' => 'Bahan Baru',
            'un_number' => 'UN9999',
            'kelas' => '8',
        ]);
    }

    /** @test */
    public function user_can_delete_barang_b3()
    {
        $barangB3 = BarangB3::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('master.barang-b3.destroy', $barangB3));

        $response->assertRedirect(route('master.barang-b3.index'));
        $response->assertSessionHas('success', 'Data barang B3 berhasil dihapus.');

        $this->assertDatabaseMissing('barang_b3s', ['id' => $barangB3->id]);
    }

    /** @test */
    public function validation_fails_when_required_fields_are_missing()
    {
        $response = $this->actingAs($this->user)->post(route('master.barang-b3.store'), []);

        $response->assertSessionHasErrors(['nama', 'un_number', 'kelas']);
    }

    /** @test */
    public function validation_fails_when_fields_exceed_max_length()
    {
        $data = [
            'nama' => str_repeat('a', 101), // Max 100
            'un_number' => str_repeat('1', 11), // Max 10
            'kelas' => str_repeat('1', 11), // Max 10
        ];

        $response = $this->actingAs($this->user)->post(route('master.barang-b3.store'), $data);

        $response->assertSessionHasErrors(['nama', 'un_number', 'kelas']);
    }

    /** @test */
    public function it_can_filter_and_search_barang_b3_for_ajax_datatable()
    {
        BarangB3::factory()->create([
            'nama' => 'Kimia A',
            'un_number' => 'UN1111',
            'kelas' => '3',
        ]);

        BarangB3::factory()->create([
            'nama' => 'Kimia B',
            'un_number' => 'UN2222',
            'kelas' => '8',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('master.barang-b3.index', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search_custom' => 'Kimia',
                'kelas' => '3',
            ]), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk();
        $rows = $response->json('data');

        $this->assertCount(1, $rows);
        $this->assertSame('Kimia A', $rows[0]['nama']);
        $this->assertSame('3', $rows[0]['kelas']);
    }
}
