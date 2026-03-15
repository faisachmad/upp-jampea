<?php

namespace Tests\Feature\Master;

use App\Models\Pelabuhan;
use App\Models\TipePelabuhan;
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

    /** @test */
    public function it_can_display_tipe_pelabuhan_index()
    {
        $tipes = TipePelabuhan::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index'));

        $response->assertOk();
        $response->assertViewIs('master.tipe-pelabuhan.index');
        $response->assertViewHas('tipes');

        foreach ($tipes as $tipe) {
            $response->assertSee($tipe->nama);
        }
    }

    /** @test */
    public function it_paginates_tipe_pelabuhan_with_default_per_page()
    {
        TipePelabuhan::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index'));

        $response->assertOk();
        $tipes = $response->viewData('tipes');
        $this->assertEquals(15, $tipes->perPage()); // Default is 15
        $this->assertCount(15, $tipes->items());
    }

    /** @test */
    public function it_paginates_tipe_pelabuhan_with_custom_per_page()
    {
        TipePelabuhan::factory()->count(30)->create();

        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index', ['per_page' => 25]));

        $response->assertOk();
        $tipes = $response->viewData('tipes');
        $this->assertEquals(25, $tipes->perPage());
        $this->assertCount(25, $tipes->items());
    }

    /** @test */
    public function it_validates_per_page_parameter()
    {
        TipePelabuhan::factory()->count(20)->create();

        // Invalid per_page should fall back to default (15)
        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index', ['per_page' => 999]));

        $response->assertOk();
        $tipes = $response->viewData('tipes');
        $this->assertEquals(15, $tipes->perPage());
    }

    /** @test */
    public function it_can_search_tipe_pelabuhan_by_nama()
    {
        $uniqueName = 'SEARCHABLE-'.uniqid();
        $searchable = TipePelabuhan::factory()->create(['nama' => $uniqueName]);
        TipePelabuhan::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index', ['search' => 'SEARCHABLE']));

        $response->assertOk();
        $response->assertSee($uniqueName);
    }

    /** @test */
    public function it_can_sort_tipe_pelabuhan_by_nama()
    {
        TipePelabuhan::factory()->create(['nama' => 'ZZZ']);
        TipePelabuhan::factory()->create(['nama' => 'AAA']);
        TipePelabuhan::factory()->create(['nama' => 'MMM']);

        // Sort ascending
        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index', ['sort' => 'nama', 'direction' => 'asc']));

        $response->assertOk();
        $tipes = $response->viewData('tipes');
        $this->assertEquals('AAA', $tipes->first()->nama);

        // Sort descending
        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index', ['sort' => 'nama', 'direction' => 'desc']));

        $response->assertOk();
        $tipes = $response->viewData('tipes');
        $this->assertEquals('ZZZ', $tipes->first()->nama);
    }

    /** @test */
    public function it_can_sort_by_pelabuhans_count()
    {
        $tipe1 = TipePelabuhan::factory()->create(['nama' => 'ZTipe1']);
        $tipe2 = TipePelabuhan::factory()->create(['nama' => 'ZTipe2']);
        $tipe3 = TipePelabuhan::factory()->create(['nama' => 'ZTipe3']);

        // Create different number of pelabuhans for each
        // Make sure to set both tipe and tipe_pelabuhan_id to avoid factory creating new types
        Pelabuhan::factory()->count(5)->create([
            'tipe_pelabuhan_id' => $tipe1->id,
            'tipe' => $tipe1->nama,
        ]);
        Pelabuhan::factory()->count(2)->create([
            'tipe_pelabuhan_id' => $tipe2->id,
            'tipe' => $tipe2->nama,
        ]);
        Pelabuhan::factory()->count(10)->create([
            'tipe_pelabuhan_id' => $tipe3->id,
            'tipe' => $tipe3->nama,
        ]);

        // Sort by count ascending - should show tipe2 (2) first, then tipe1 (5), then tipe3 (10)
        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index', [
                'sort' => 'pelabuhans_count',
                'direction' => 'asc',
                'per_page' => 50, // Ensure we get all records
            ]));

        $response->assertOk();
        $tipes = $response->viewData('tipes')->items();

        // Find our specific tipes in the result
        $foundTipe2 = collect($tipes)->firstWhere('id', $tipe2->id);
        $foundTipe1 = collect($tipes)->firstWhere('id', $tipe1->id);
        $foundTipe3 = collect($tipes)->firstWhere('id', $tipe3->id);

        $this->assertNotNull($foundTipe2);
        $this->assertNotNull($foundTipe1);
        $this->assertNotNull($foundTipe3);

        $this->assertEquals(2, $foundTipe2->pelabuhans_count);
        $this->assertEquals(5, $foundTipe1->pelabuhans_count);
        $this->assertEquals(10, $foundTipe3->pelabuhans_count);

        // Verify sort order: 2 should come before 5 should come before 10
        $tipeIds = collect($tipes)->pluck('id')->toArray();
        $pos2 = array_search($tipe2->id, $tipeIds);
        $pos1 = array_search($tipe1->id, $tipeIds);
        $pos3 = array_search($tipe3->id, $tipeIds);

        $this->assertLessThan($pos1, $pos2, 'Tipe with 2 pelabuhans should appear before tipe with 5');
        $this->assertLessThan($pos3, $pos1, 'Tipe with 5 pelabuhans should appear before tipe with 10');

        // Sort by count descending - should show tipe3 (10) first
        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index', [
                'sort' => 'pelabuhans_count',
                'direction' => 'desc',
                'per_page' => 50,
            ]));

        $response->assertOk();
        $tipes = $response->viewData('tipes')->items();
        $foundTipe3 = collect($tipes)->firstWhere('id', $tipe3->id);
        $this->assertEquals(10, $foundTipe3->pelabuhans_count);

        // Verify descending sort order
        $tipeIds = collect($tipes)->pluck('id')->toArray();
        $pos3 = array_search($tipe3->id, $tipeIds);
        $pos1 = array_search($tipe1->id, $tipeIds);
        $pos2 = array_search($tipe2->id, $tipeIds);

        $this->assertLessThan($pos1, $pos3, 'Tipe with 10 pelabuhans should appear before tipe with 5');
        $this->assertLessThan($pos2, $pos1, 'Tipe with 5 pelabuhans should appear before tipe with 2');
    }

    // Skipped: TipePelabuhan index view will be created later if needed
    // Currently, TipePelabuhan is managed via AJAX from Pelabuhan form

    /** @test */
    public function it_can_store_new_tipe_pelabuhan()
    {
        $data = [
            'nama' => 'TEST-TIPE-'.uniqid(),
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
            'nama' => 'TEST-AJAX-'.uniqid(),
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
        $existingName = 'UNIQUE-TEST-'.uniqid();
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
        $name1 = 'UNIQUE-1-'.uniqid();
        $name2 = 'UNIQUE-2-'.uniqid();

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
    public function it_can_search_tipe_pelabuhan_for_ajax_datatable()
    {
        TipePelabuhan::factory()->create([
            'nama' => 'Jampea Utama',
            'keterangan' => 'Tipe Jampea',
        ]);

        TipePelabuhan::factory()->create([
            'nama' => 'Makassar',
            'keterangan' => 'Tipe lain',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('master.tipe-pelabuhan.index', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search_custom' => 'Jampea',
            ]), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk();
        $rows = $response->json('data');

        $this->assertCount(1, $rows);
        $this->assertSame('Jampea Utama', $rows[0]['nama']);
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
