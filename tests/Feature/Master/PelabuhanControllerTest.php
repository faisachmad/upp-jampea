<?php

namespace Tests\Feature\Master;

use App\Models\Pelabuhan;
use App\Models\TipePelabuhan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelabuhanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected TipePelabuhan $tipeUpp;
    protected TipePelabuhan $tipeLuar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->tipeUpp = TipePelabuhan::factory()->upp()->create();
        $this->tipeLuar = TipePelabuhan::factory()->luar()->create();
    }

    /** @test */
    public function it_can_display_pelabuhan_index()
    {
        Pelabuhan::factory()->count(5)->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index'));

        $response->assertOk();
        $response->assertViewIs('master.pelabuhan.index');
        $response->assertViewHas(['pelabuhans', 'tipes']);
    }

    /** @test */
    public function it_paginates_pelabuhan_with_default_per_page()
    {
        Pelabuhan::factory()->count(20)->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index'));

        $response->assertOk();
        $this->assertEquals(15, $response->viewData('pelabuhans')->perPage());
        $this->assertEquals(15, $response->viewData('pelabuhans')->count());
    }

    /** @test */
    public function it_paginates_pelabuhan_with_custom_per_page()
    {
        Pelabuhan::factory()->count(30)->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);

        $perPageValues = [10, 15, 25, 50, 100];

        foreach ($perPageValues as $perPage) {
            $response = $this->actingAs($this->user)
                ->get(route('master.pelabuhan.index', ['per_page' => $perPage]));

            $response->assertOk();
            $this->assertEquals($perPage, $response->viewData('pelabuhans')->perPage(), "Failed for per_page={$perPage}");
        }
    }

    /** @test */
    public function it_validates_per_page_parameter()
    {
        Pelabuhan::factory()->count(20)->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);

        // Invalid per_page should fallback to default (15)
        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index', ['per_page' => 999]));

        $response->assertOk();
        $this->assertEquals(15, $response->viewData('pelabuhans')->perPage());
    }

    /** @test */
    public function it_can_search_pelabuhan_by_nama()
    {
        Pelabuhan::factory()->create([
            'nama' => 'Pelabuhan Jampea',
            'tipe_pelabuhan_id' => $this->tipeUpp->id,
        ]);
        Pelabuhan::factory()->create([
            'nama' => 'Pelabuhan Makassar',
            'tipe_pelabuhan_id' => $this->tipeUpp->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index', ['search' => 'Jampea']));

        $response->assertOk();
        $pelabuhans = $response->viewData('pelabuhans');
        $this->assertEquals(1, $pelabuhans->total());
        $this->assertEquals('Pelabuhan Jampea', $pelabuhans->first()->nama);
    }

    /** @test */
    public function it_can_search_pelabuhan_by_kode()
    {
        Pelabuhan::factory()->create([
            'kode' => 'PLB-001',
            'tipe_pelabuhan_id' => $this->tipeUpp->id,
        ]);
        Pelabuhan::factory()->create([
            'kode' => 'PLB-002',
            'tipe_pelabuhan_id' => $this->tipeUpp->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index', ['search' => 'PLB-001']));

        $response->assertOk();
        $pelabuhans = $response->viewData('pelabuhans');
        $this->assertEquals(1, $pelabuhans->total());
        $this->assertEquals('PLB-001', $pelabuhans->first()->kode);
    }

    /** @test */
    public function it_can_filter_pelabuhan_by_tipe()
    {
        Pelabuhan::factory()->count(3)->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);
        Pelabuhan::factory()->count(2)->create(['tipe_pelabuhan_id' => $this->tipeLuar->id]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index', ['tipe' => $this->tipeUpp->id]));

        $response->assertOk();
        $pelabuhans = $response->viewData('pelabuhans');
        $this->assertEquals(3, $pelabuhans->total());
    }

    /** @test */
    public function it_can_filter_pelabuhan_by_status_active()
    {
        Pelabuhan::factory()->count(3)->active()->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);
        Pelabuhan::factory()->count(2)->inactive()->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index', ['status' => 'active']));

        $response->assertOk();
        $pelabuhans = $response->viewData('pelabuhans');
        $this->assertEquals(3, $pelabuhans->total());
        $this->assertTrue($pelabuhans->every(fn($p) => $p->is_active === true));
    }

    /** @test */
    public function it_can_filter_pelabuhan_by_status_inactive()
    {
        Pelabuhan::factory()->count(3)->active()->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);
        Pelabuhan::factory()->count(2)->inactive()->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index', ['status' => 'inactive']));

        $response->assertOk();
        $pelabuhans = $response->viewData('pelabuhans');
        $this->assertEquals(2, $pelabuhans->total());
        $this->assertTrue($pelabuhans->every(fn($p) => $p->is_active === false));
    }

    /** @test */
    public function it_can_sort_pelabuhan_by_kode()
    {
        Pelabuhan::factory()->create(['kode' => 'PLB-003', 'tipe_pelabuhan_id' => $this->tipeUpp->id]);
        Pelabuhan::factory()->create(['kode' => 'PLB-001', 'tipe_pelabuhan_id' => $this->tipeUpp->id]);
        Pelabuhan::factory()->create(['kode' => 'PLB-002', 'tipe_pelabuhan_id' => $this->tipeUpp->id]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index', ['sort' => 'kode', 'direction' => 'asc']));

        $response->assertOk();
        $pelabuhans = $response->viewData('pelabuhans');
        $this->assertEquals('PLB-001', $pelabuhans->first()->kode);
    }

    /** @test */
    public function it_can_store_new_pelabuhan()
    {
        $data = [
            'nama' => 'Pelabuhan Test',
            'tipe_pelabuhan_id' => $this->tipeUpp->id,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('master.pelabuhan.store'), $data);

        $response->assertRedirect(route('master.pelabuhan.index'));
        $response->assertSessionHas('success', 'Data pelabuhan berhasil ditambahkan.');

        $this->assertDatabaseHas('pelabuhans', [
            'nama' => 'Pelabuhan Test',
            'tipe_pelabuhan_id' => $this->tipeUpp->id,
            'tipe' => 'UPP', // Should sync old tipe column
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing_pelabuhan()
    {
        $response = $this->actingAs($this->user)
            ->post(route('master.pelabuhan.store'), []);

        $response->assertSessionHasErrors(['nama', 'tipe_pelabuhan_id']);
    }

    /** @test */
    public function it_can_update_pelabuhan()
    {
        $pelabuhan = Pelabuhan::factory()->create([
            'nama' => 'Old Name',
            'tipe_pelabuhan_id' => $this->tipeUpp->id,
        ]);

        $data = [
            'nama' => 'New Name',
            'tipe_pelabuhan_id' => $this->tipeLuar->id,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('master.pelabuhan.update', $pelabuhan), $data);

        $response->assertRedirect(route('master.pelabuhan.index'));
        $response->assertSessionHas('success', 'Data pelabuhan berhasil diperbarui.');

        $this->assertDatabaseHas('pelabuhans', [
            'id' => $pelabuhan->id,
            'nama' => 'New Name',
            'tipe_pelabuhan_id' => $this->tipeLuar->id,
            'tipe' => 'LUAR',
        ]);
    }

    /** @test */
    public function it_can_delete_pelabuhan()
    {
        $pelabuhan = Pelabuhan::factory()->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('master.pelabuhan.destroy', $pelabuhan));

        $response->assertRedirect(route('master.pelabuhan.index'));
        $response->assertSessionHas('success', 'Data pelabuhan berhasil dihapus.');

        $this->assertDatabaseMissing('pelabuhans', ['id' => $pelabuhan->id]);
    }

    /** @test */
    public function it_combines_search_filter_and_pagination()
    {
        // Create pelabuhans with specific criteria
        Pelabuhan::factory()->count(15)->active()->create([
            'nama' => 'Pelabuhan Aktif',
            'tipe_pelabuhan_id' => $this->tipeUpp->id,
        ]);
        Pelabuhan::factory()->count(5)->inactive()->create([
            'nama' => 'Pelabuhan Non-Aktif',
            'tipe_pelabuhan_id' => $this->tipeLuar->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('master.pelabuhan.index', [
                'search' => 'Aktif',
                'tipe' => $this->tipeUpp->id,
                'status' => 'active',
                'per_page' => 10,
            ]));

        $response->assertOk();
        $pelabuhans = $response->viewData('pelabuhans');
        
        $this->assertEquals(10, $pelabuhans->perPage());
        $this->assertEquals(15, $pelabuhans->total());
        $this->assertTrue($pelabuhans->every(fn($p) => 
            str_contains($p->nama, 'Aktif') && 
            $p->is_active === true &&
            $p->tipe_pelabuhan_id === $this->tipeUpp->id
        ));
    }

    /** @test */
    public function unauthenticated_user_cannot_access_pelabuhan_routes()
    {
        $pelabuhan = Pelabuhan::factory()->create(['tipe_pelabuhan_id' => $this->tipeUpp->id]);

        $this->get(route('master.pelabuhan.index'))
            ->assertRedirect(route('login'));

        $this->post(route('master.pelabuhan.store'), [])
            ->assertRedirect(route('login'));

        $this->put(route('master.pelabuhan.update', $pelabuhan), [])
            ->assertRedirect(route('login'));

        $this->delete(route('master.pelabuhan.destroy', $pelabuhan))
            ->assertRedirect(route('login'));
    }
}
