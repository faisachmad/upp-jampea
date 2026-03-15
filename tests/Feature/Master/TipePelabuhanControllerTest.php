<?php

namespace Tests\Feature\Master;

use App\Models\TipePelabuhan;
use App\Models\Pelabuhan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TipePelabuhanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // Skipped: TipePelabuhan index view will be created later if needed
    // Currently, TipePelabuhan is managed via AJAX from Pelabuhan form

    /** @test */
    public function it_can_store_new_tipe_pelabuhan()
    {
        $data = [
            'nama' => 'TEST-TIPE-' . uniqid(),
            'keterangan' => 'Unit Penyelenggara Pelabuhan',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('master.tipe-pelabuhan.store'), $data);

        $response->assertRedirect(route('master.tipe-pelabuhan.index'));
        $response->assertSessionHas('success', 'Tipe pelabuhan berhasil ditambahkan.');

        $this->assertDatabaseHas('tipe_pelabuhans', [
            'nama' => $data['nama'],
            'keterangan' => 'Unit Penyelenggara Pelabuhan',
        ]);
    }

    /** @test */
    public function it_can_store_tipe_pelabuhan_via_ajax()
    {
        $data = [
            'nama' => 'TEST-AJAX-' . uniqid(),
            'keterangan' => 'Pos Pengawasan Kepelabuanan',
        ];

        $response = $this->actingAs($this->user)
            ->postJson(route('master.tipe-pelabuhan.store'), $data);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Tipe pelabuhan berhasil ditambahkan.',
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['id', 'nama', 'keterangan'],
        ]);

        $this->assertDatabaseHas('tipe_pelabuhans', [
            'nama' => $data['nama'],
            'keterangan' => 'Pos Pengawasan Kepelabuanan',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->actingAs($this->user)
            ->post(route('master.tipe-pelabuhan.store'), []);

        $response->assertSessionHasErrors(['nama']);
    }

    /** @test */
    public function it_validates_unique_nama_when_storing()
    {
        $existingName = 'UNIQUE-TEST-' . uniqid();
        TipePelabuhan::factory()->create(['nama' => $existingName]);

        $response = $this->actingAs($this->user)
            ->post(route('master.tipe-pelabuhan.store'), [
                'nama' => $existingName,
                'keterangan' => 'Test',
            ]);

        $response->assertSessionHasErrors(['nama']);
    }

    /** @test */
    public function it_validates_max_length_for_nama()
    {
        $response = $this->actingAs($this->user)
            ->post(route('master.tipe-pelabuhan.store'), [
                'nama' => str_repeat('A', 51), // Exceeds 50 chars
                'keterangan' => 'Test',
            ]);

        $response->assertSessionHasErrors(['nama']);
    }

    /** @test */
    public function it_can_update_tipe_pelabuhan()
    {
        $tipe = TipePelabuhan::factory()->create([
            'nama' => 'OLD_NAME',
            'keterangan' => 'Old Description',
        ]);

        $data = [
            'nama' => 'NEW_NAME',
            'keterangan' => 'New Description',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('master.tipe-pelabuhan.update', $tipe), $data);

        $response->assertRedirect(route('master.tipe-pelabuhan.index'));
        $response->assertSessionHas('success', 'Tipe pelabuhan berhasil diperbarui.');

        $this->assertDatabaseHas('tipe_pelabuhans', [
            'id' => $tipe->id,
            'nama' => 'NEW_NAME',
            'keterangan' => 'New Description',
        ]);
    }

    /** @test */
    public function it_validates_unique_nama_when_updating_except_itself()
    {
        $name1 = 'UNIQUE-1-' . uniqid();
        $name2 = 'UNIQUE-2-' . uniqid();
        
        $tipe1 = TipePelabuhan::factory()->create(['nama' => $name1]);
        $tipe2 = TipePelabuhan::factory()->create(['nama' => $name2]);

        // Should fail: trying to use another tipe's name
        $response = $this->actingAs($this->user)
            ->put(route('master.tipe-pelabuhan.update', $tipe2), [
                'nama' => $name1,
                'keterangan' => 'Test',
            ]);

        $response->assertSessionHasErrors(['nama']);

        // Should pass: using its own name
        $response = $this->actingAs($this->user)
            ->put(route('master.tipe-pelabuhan.update', $tipe2), [
                'nama' => $name2,
                'keterangan' => 'Updated Description',
            ]);

        $response->assertRedirect(route('master.tipe-pelabuhan.index'));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_can_delete_unused_tipe_pelabuhan()
    {
        $tipe = TipePelabuhan::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('master.tipe-pelabuhan.destroy', $tipe));

        $response->assertRedirect(route('master.tipe-pelabuhan.index'));
        $response->assertSessionHas('success', 'Tipe pelabuhan berhasil dihapus.');

        $this->assertDatabaseMissing('tipe_pelabuhans', ['id' => $tipe->id]);
    }

    /** @test */
    public function it_cannot_delete_tipe_pelabuhan_that_is_in_use()
    {
        $tipe = TipePelabuhan::factory()->create();
        Pelabuhan::factory()->create(['tipe_pelabuhan_id' => $tipe->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('master.tipe-pelabuhan.destroy', $tipe));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tipe pelabuhan tidak dapat dihapus karena masih digunakan.');

        $this->assertDatabaseHas('tipe_pelabuhans', ['id' => $tipe->id]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_tipe_pelabuhan_routes()
    {
        $tipe = TipePelabuhan::factory()->create();

        $this->get(route('master.tipe-pelabuhan.index'))
            ->assertRedirect(route('login'));

        $this->post(route('master.tipe-pelabuhan.store'), [])
            ->assertRedirect(route('login'));

        $this->put(route('master.tipe-pelabuhan.update', $tipe), [])
            ->assertRedirect(route('login'));

        $this->delete(route('master.tipe-pelabuhan.destroy', $tipe))
            ->assertRedirect(route('login'));
    }
}
