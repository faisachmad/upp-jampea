<?php

namespace Tests\Feature\Master;

use App\Models\JenisKapal;
use App\Models\Kapal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisKapalControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_jenis_kapal_index()
    {
        $response = $this->actingAs($this->user)->get(route('master.jenis-kapal.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.jenis-kapal.index');
    }

    /** @test */
    public function user_can_create_jenis_kapal_with_active_status()
    {
        $data = [
            'nama' => 'Kapal Layar Motor',
            'keterangan' => 'Jenis kapal dengan layar dan motor',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->user)->post(route('master.jenis-kapal.store'), $data);

        $response->assertRedirect(route('master.jenis-kapal.index'));
        $response->assertSessionHas('success', 'Jenis kapal berhasil ditambahkan.');

        $this->assertDatabaseHas('jenis_kapals', [
            'nama' => 'Kapal Layar Motor',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function user_can_create_jenis_kapal_with_inactive_status_when_checkbox_not_checked()
    {
        $data = [
            'nama' => 'Kapal Kargo',
            'keterangan' => 'Kapal untuk mengangkut kargo',
            // is_active not sent (checkbox unchecked)
        ];

        $response = $this->actingAs($this->user)->post(route('master.jenis-kapal.store'), $data);

        $response->assertRedirect(route('master.jenis-kapal.index'));

        $this->assertDatabaseHas('jenis_kapals', [
            'nama' => 'Kapal Kargo',
            'is_active' => false,
        ]);
    }

    /** @test */
    public function user_can_update_jenis_kapal()
    {
        $jenisKapal = JenisKapal::factory()->create([
            'nama' => 'Kapal Lama',
            'is_active' => true,
        ]);

        $data = [
            'nama' => 'Kapal Baru',
            'keterangan' => 'Updated description',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->user)->put(route('master.jenis-kapal.update', $jenisKapal), $data);

        $response->assertRedirect(route('master.jenis-kapal.index'));
        $response->assertSessionHas('success', 'Jenis kapal berhasil diupdate.');

        $this->assertDatabaseHas('jenis_kapals', [
            'id' => $jenisKapal->id,
            'nama' => 'Kapal Baru',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function checkbox_unchecked_updates_is_active_to_false()
    {
        $jenisKapal = JenisKapal::factory()->create([
            'nama' => 'Test Kapal',
            'is_active' => true,
        ]);

        $data = [
            'nama' => 'Test Kapal',
            'keterangan' => 'Test',
            // is_active not sent (checkbox unchecked)
        ];

        $response = $this->actingAs($this->user)->put(route('master.jenis-kapal.update', $jenisKapal), $data);

        $response->assertRedirect(route('master.jenis-kapal.index'));

        $this->assertDatabaseHas('jenis_kapals', [
            'id' => $jenisKapal->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function user_cannot_delete_jenis_kapal_with_existing_kapals()
    {
        $jenisKapal = JenisKapal::factory()->create();
        Kapal::factory()->create(['jenis_kapal_id' => $jenisKapal->id]);

        $response = $this->actingAs($this->user)->delete(route('master.jenis-kapal.destroy', $jenisKapal));

        $response->assertRedirect(route('master.jenis-kapal.index'));
        $response->assertSessionHas('error', 'Jenis kapal tidak dapat dihapus karena masih digunakan.');

        $this->assertDatabaseHas('jenis_kapals', ['id' => $jenisKapal->id]);
    }

    /** @test */
    public function user_can_delete_jenis_kapal_without_kapals()
    {
        $jenisKapal = JenisKapal::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('master.jenis-kapal.destroy', $jenisKapal));

        $response->assertRedirect(route('master.jenis-kapal.index'));
        $response->assertSessionHas('success', 'Jenis kapal berhasil dihapus.');

        $this->assertDatabaseMissing('jenis_kapals', ['id' => $jenisKapal->id]);
    }

    /** @test */
    public function kode_is_auto_generated_from_nama()
    {
        $data = [
            'nama' => 'Kapal Layar Motor',
            'keterangan' => 'Test',
        ];

        $response = $this->actingAs($this->user)->post(route('master.jenis-kapal.store'), $data);

        $jenisKapal = JenisKapal::where('nama', 'Kapal Layar Motor')->first();

        $this->assertNotNull($jenisKapal->kode);
        $this->assertEquals('KLM', $jenisKapal->kode);
    }

    /** @test */
    public function it_can_filter_and_search_jenis_kapal_for_ajax_datatable()
    {
        JenisKapal::factory()->create([
            'nama' => 'Kapal Jampea Aktif',
            'is_active' => true,
        ]);

        JenisKapal::factory()->create([
            'nama' => 'Kapal Jampea Nonaktif',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('master.jenis-kapal.index', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search_custom' => 'Jampea',
                'status' => 'active',
            ]), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk();
        $rows = $response->json('data');

        $this->assertCount(1, $rows);
        $this->assertSame('Kapal Jampea Aktif', $rows[0]['nama']);
        $this->assertTrue((bool) $rows[0]['is_active']);
    }
}
