<?php

namespace Tests\Feature\Master;

use App\Models\Kapal;
use App\Models\Nakhoda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NakhodaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Kapal $kapal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->kapal = Kapal::factory()->create();
    }

    /** @test */
    public function user_can_view_nakhoda_index()
    {
        $response = $this->actingAs($this->user)->get(route('master.nakhoda.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.nakhoda.index');
        $response->assertViewHas('kapals');
    }

    /** @test */
    public function user_can_create_nakhoda()
    {
        $data = [
            'nama' => 'Kapten Test',
            'kapal_id' => $this->kapal->id,
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->user)->post(route('master.nakhoda.store'), $data);

        $response->assertRedirect(route('master.nakhoda.index'));
        $response->assertSessionHas('success', 'Data nakhoda berhasil ditambahkan.');

        $this->assertDatabaseHas('nakhodas', [
            'nama' => 'Kapten Test',
            'kapal_id' => $this->kapal->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function user_can_create_nakhoda_with_inactive_status()
    {
        $data = [
            'nama' => 'Kapten Inactive',
            'kapal_id' => $this->kapal->id,
            // is_active not sent (checkbox unchecked)
        ];

        $response = $this->actingAs($this->user)->post(route('master.nakhoda.store'), $data);

        $response->assertRedirect(route('master.nakhoda.index'));

        $this->assertDatabaseHas('nakhodas', [
            'nama' => 'Kapten Inactive',
            'is_active' => false,
        ]);
    }

    /** @test */
    public function user_can_update_nakhoda()
    {
        $nakhoda = Nakhoda::factory()->create([
            'nama' => 'Kapten Lama',
            'kapal_id' => $this->kapal->id,
        ]);

        $newKapal = Kapal::factory()->create();

        $data = [
            'nama' => 'Kapten Baru',
            'kapal_id' => $newKapal->id,
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->user)->put(route('master.nakhoda.update', $nakhoda), $data);

        $response->assertRedirect(route('master.nakhoda.index'));
        $response->assertSessionHas('success', 'Data nakhoda berhasil diperbarui.');

        $this->assertDatabaseHas('nakhodas', [
            'id' => $nakhoda->id,
            'nama' => 'Kapten Baru',
            'kapal_id' => $newKapal->id,
        ]);
    }

    /** @test */
    public function checkbox_unchecked_updates_is_active_to_false()
    {
        $nakhoda = Nakhoda::factory()->create([
            'nama' => 'Kapten Test',
            'kapal_id' => $this->kapal->id,
            'is_active' => true,
        ]);

        $data = [
            'nama' => 'Kapten Test',
            'kapal_id' => $this->kapal->id,
            // is_active not sent (checkbox unchecked)
        ];

        $response = $this->actingAs($this->user)->put(route('master.nakhoda.update', $nakhoda), $data);

        $response->assertRedirect(route('master.nakhoda.index'));

        $this->assertDatabaseHas('nakhodas', [
            'id' => $nakhoda->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function user_can_delete_nakhoda()
    {
        $nakhoda = Nakhoda::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('master.nakhoda.destroy', $nakhoda));

        $response->assertRedirect(route('master.nakhoda.index'));
        $response->assertSessionHas('success', 'Data nakhoda berhasil dihapus.');

        $this->assertDatabaseMissing('nakhodas', ['id' => $nakhoda->id]);
    }

    /** @test */
    public function validation_fails_when_required_fields_are_missing()
    {
        $response = $this->actingAs($this->user)->post(route('master.nakhoda.store'), []);

        $response->assertSessionHasErrors(['nama', 'kapal_id']);
    }

    /** @test */
    public function validation_fails_for_non_existent_kapal()
    {
        $data = [
            'nama' => 'Kapten Test',
            'kapal_id' => 99999, // Non-existent
        ];

        $response = $this->actingAs($this->user)->post(route('master.nakhoda.store'), $data);

        $response->assertSessionHasErrors(['kapal_id']);
    }

    /** @test */
    public function it_can_filter_and_search_nakhoda_for_ajax_datatable()
    {
        Nakhoda::factory()->create([
            'nama' => 'Kapten Jampea Aktif',
            'kapal_id' => $this->kapal->id,
            'is_active' => true,
        ]);

        Nakhoda::factory()->create([
            'nama' => 'Kapten Jampea Nonaktif',
            'kapal_id' => $this->kapal->id,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('master.nakhoda.index', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search_custom' => 'Jampea',
                'kapal_id' => $this->kapal->id,
                'status' => 'active',
            ]), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk();
        $rows = $response->json('data');

        $this->assertCount(1, $rows);
        $this->assertSame('Kapten Jampea Aktif', $rows[0]['nama']);
        $this->assertTrue((bool) $rows[0]['is_active']);
        $this->assertSame($this->kapal->id, $rows[0]['kapal_id']);
    }
}
