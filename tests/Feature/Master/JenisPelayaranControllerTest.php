<?php

namespace Tests\Feature\Master;

use App\Models\JenisPelayaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisPelayaranControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_jenis_pelayaran_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('master.jenis-pelayaran.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function index_returns_datatables_json_for_ajax_request(): void
    {
        JenisPelayaran::factory()->create(['kode' => 'TESTAJ', 'nama' => 'Test Ajax', 'prefix' => 'T']);

        $response = $this->actingAs($this->user)
            ->get(route('master.jenis-pelayaran.index', ['draw' => 1, 'start' => 0, 'length' => 10]), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function user_can_create_jenis_pelayaran(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('master.jenis-pelayaran.store'), [
                'kode' => 'TESTX',
                'nama' => 'Test Jenis Pelayaran X',
                'prefix' => 'X',
            ]);

        $response->assertRedirect(route('master.jenis-pelayaran.index'));
        $this->assertDatabaseHas('jenis_pelayarans', ['kode' => 'TESTX', 'prefix' => 'X']);
    }

    /** @test */
    public function store_fails_with_duplicate_kode(): void
    {
        JenisPelayaran::factory()->create(['kode' => 'DUPKODE', 'prefix' => 'D']);

        $response = $this->actingAs($this->user)
            ->postJson(route('master.jenis-pelayaran.store'), [
                'kode' => 'DUPKODE',
                'nama' => 'Duplicate',
                'prefix' => 'D',
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function user_can_update_jenis_pelayaran(): void
    {
        $jp = JenisPelayaran::factory()->create(['kode' => 'UPDKD', 'nama' => 'Old Nama', 'prefix' => 'U']);

        $response = $this->actingAs($this->user)
            ->put(route('master.jenis-pelayaran.update', $jp), [
                'kode' => 'UPDKD',
                'nama' => 'New Nama Updated',
                'prefix' => 'U',
            ]);

        $response->assertRedirect(route('master.jenis-pelayaran.index'));
        $this->assertDatabaseHas('jenis_pelayarans', ['id' => $jp->id, 'nama' => 'New Nama Updated']);
    }

    /** @test */
    public function user_can_delete_unused_jenis_pelayaran(): void
    {
        $jp = JenisPelayaran::factory()->create(['kode' => 'DELKD', 'prefix' => 'D']);

        $response = $this->actingAs($this->user)
            ->delete(route('master.jenis-pelayaran.destroy', $jp));

        $response->assertRedirect();
        $this->assertDatabaseMissing('jenis_pelayarans', ['id' => $jp->id]);
    }
}
