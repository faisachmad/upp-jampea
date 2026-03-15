<?php

namespace Tests\Feature\Master;

use App\Models\Bendera;
use App\Models\JenisKapal;
use App\Models\Kapal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KapalControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected JenisKapal $jenisKapal;

    protected Bendera $bendera;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->jenisKapal = JenisKapal::factory()->create();
        $this->bendera = Bendera::factory()->create();
    }

    /** @test */
    public function user_can_view_kapal_index()
    {
        $response = $this->actingAs($this->user)->get(route('master.kapal.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.kapal.index');
        $response->assertViewHas(['jenisKapals', 'benderas']);
    }

    /** @test */
    public function user_can_create_kapal_with_all_fields()
    {
        $data = [
            'nama' => 'KM Test Ship',
            'jenis_kapal_id' => $this->jenisKapal->id,
            'bendera_id' => $this->bendera->id,
            'gt' => 1000.50,
            'dwt' => 800.25,
            'panjang' => 50.75,
            'tanda_selar' => 'TS-001',
            'call_sign' => 'CALL123',
            'tempat_kedudukan' => 'Jakarta',
            'pemilik_agen' => 'PT Test Shipping',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->user)->post(route('master.kapal.store'), $data);

        $response->assertRedirect(route('master.kapal.index'));
        $response->assertSessionHas('success', 'Kapal berhasil ditambahkan.');

        $this->assertDatabaseHas('kapals', [
            'nama' => 'KM Test Ship',
            'gt' => 1000.50,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function user_can_create_kapal_with_minimal_fields()
    {
        $data = [
            'nama' => 'KM Minimal',
        ];

        $response = $this->actingAs($this->user)->post(route('master.kapal.store'), $data);

        $response->assertRedirect(route('master.kapal.index'));

        $this->assertDatabaseHas('kapals', [
            'nama' => 'KM Minimal',
            'jenis_kapal_id' => null,
            'gt' => null,
        ]);
    }

    /** @test */
    public function empty_numeric_fields_are_stored_as_null()
    {
        $data = [
            'nama' => 'KM Empty Numbers',
            'gt' => '',
            'dwt' => '',
            'panjang' => '',
        ];

        $response = $this->actingAs($this->user)->post(route('master.kapal.store'), $data);

        $kapal = Kapal::where('nama', 'KM Empty Numbers')->first();

        $this->assertNull($kapal->gt);
        $this->assertNull($kapal->dwt);
        $this->assertNull($kapal->panjang);
    }

    /** @test */
    public function user_can_update_kapal()
    {
        $kapal = Kapal::factory()->create([
            'nama' => 'KM Old Name',
            'gt' => 500,
            'is_active' => true,
        ]);

        $data = [
            'nama' => 'KM New Name',
            'gt' => 1500,
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->user)->put(route('master.kapal.update', $kapal), $data);

        $response->assertRedirect(route('master.kapal.index'));
        $response->assertSessionHas('success', 'Kapal berhasil diupdate.');

        $this->assertDatabaseHas('kapals', [
            'id' => $kapal->id,
            'nama' => 'KM New Name',
            'gt' => 1500,
        ]);
    }

    /** @test */
    public function checkbox_unchecked_updates_is_active_to_false()
    {
        $kapal = Kapal::factory()->create([
            'nama' => 'KM Test',
            'is_active' => true,
        ]);

        $data = [
            'nama' => 'KM Test',
            // is_active not sent (checkbox unchecked)
        ];

        $response = $this->actingAs($this->user)->put(route('master.kapal.update', $kapal), $data);

        $response->assertRedirect(route('master.kapal.index'));

        $this->assertDatabaseHas('kapals', [
            'id' => $kapal->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function user_can_delete_kapal()
    {
        $kapal = Kapal::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('master.kapal.destroy', $kapal));

        $response->assertRedirect(route('master.kapal.index'));
        $response->assertSessionHas('success', 'Kapal berhasil dihapus.');

        $this->assertDatabaseMissing('kapals', ['id' => $kapal->id]);
    }

    /** @test */
    public function validation_fails_when_nama_is_missing()
    {
        $response = $this->actingAs($this->user)->post(route('master.kapal.store'), []);

        $response->assertSessionHasErrors(['nama']);
    }

    /** @test */
    public function validation_fails_for_invalid_numeric_fields()
    {
        $data = [
            'nama' => 'KM Test',
            'gt' => -100, // Must be min:0
        ];

        $response = $this->actingAs($this->user)->post(route('master.kapal.store'), $data);

        $response->assertSessionHasErrors(['gt']);
    }

    /** @test */
    public function it_can_filter_and_search_kapal_for_ajax_datatable()
    {
        Kapal::factory()->create([
            'nama' => 'KM Jampea Aktif',
            'jenis_kapal_id' => $this->jenisKapal->id,
            'pemilik_agen' => 'Agen Jampea',
            'is_active' => true,
        ]);

        Kapal::factory()->create([
            'nama' => 'KM Jampea Nonaktif',
            'jenis_kapal_id' => $this->jenisKapal->id,
            'pemilik_agen' => 'Agen Jampea',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('master.kapal.index', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search_custom' => 'Jampea',
                'jenis_kapal_id' => $this->jenisKapal->id,
                'status' => 'active',
            ]), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk();
        $rows = $response->json('data');

        $this->assertCount(1, $rows);
        $this->assertSame('KM Jampea Aktif', $rows[0]['nama']);
        $this->assertTrue((bool) $rows[0]['is_active']);
        $this->assertSame($this->jenisKapal->id, $rows[0]['jenis_kapal_id']);
    }
}
