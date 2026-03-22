<?php

namespace Tests\Feature\Feature;

use App\Models\JenisPelayaran;
use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_dynamic_summary(): void
    {
        $user = User::factory()->create();
        $jenisPelayaran = JenisPelayaran::factory()->create([
            'kode' => 'PELRA',
            'nama' => 'Pelayaran Rakyat',
            'prefix' => 'P',
        ]);

        Kunjungan::factory()->create([
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'tahun' => 2026,
            'bulan' => 3,
            'tgl_tiba' => '2026-03-10',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard', [
            'tahun' => 2026,
            'bulan' => 3,
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Dashboard Operasional');
        $response->assertSeeText('Total Kunjungan');
        $response->assertSeeText('1');
    }
}
