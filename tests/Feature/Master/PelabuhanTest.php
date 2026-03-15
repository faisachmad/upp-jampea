<?php

namespace Tests\Feature\Master;

use App\Models\Pelabuhan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelabuhanTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_pelabuhan_with_auto_generated_code()
    {
        $response = $this->actingAs($this->user)
            ->post(route('master.pelabuhan.store'), [
                'nama' => 'Pelabuhan Test',
                'tipe' => 'UPP',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('master.pelabuhan.index'));
        $this->assertDatabaseHas('pelabuhans', [
            'nama' => 'Pelabuhan Test',
            'tipe' => 'UPP',
        ]);

        $pelabuhan = Pelabuhan::where('nama', 'Pelabuhan Test')->first();
        $this->assertNotNull($pelabuhan->kode);
        $this->assertStringStartsWith('PLB-', $pelabuhan->kode);
    }

    public function test_can_create_pelabuhan_with_custom_type()
    {
        $response = $this->actingAs($this->user)
            ->post(route('master.pelabuhan.store'), [
                'nama' => 'Pelabuhan Custom',
                'tipe' => 'TIPE BARU',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('master.pelabuhan.index'));
        $this->assertDatabaseHas('pelabuhans', [
            'nama' => 'Pelabuhan Custom',
            'tipe' => 'TIPE BARU',
        ]);
    }

    public function test_can_update_pelabuhan()
    {
        $pelabuhan = Pelabuhan::create([
            'kode' => 'OLD-CODE',
            'nama' => 'Old Name',
            'tipe' => 'UPP',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('master.pelabuhan.update', $pelabuhan), [
                'nama' => 'New Name',
                'tipe' => 'POSKER',
                'is_active' => false,
            ]);

        $response->assertRedirect(route('master.pelabuhan.index'));
        
        $pelabuhan->refresh();
        $this->assertEquals('New Name', $pelabuhan->nama);
        $this->assertEquals('POSKER', $pelabuhan->tipe);
        $this->assertFalse($pelabuhan->is_active);
    }
}
