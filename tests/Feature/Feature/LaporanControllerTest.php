<?php

namespace Tests\Feature\Feature;

use App\Models\JenisPelayaran;
use App\Models\Kunjungan;
use App\Models\KunjunganMuatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelra_report_can_be_rendered_and_exported(): void
    {
        $user = User::factory()->create();
        $jenisPelayaran = JenisPelayaran::factory()->create([
            'kode' => 'PELRA',
            'nama' => 'Pelayaran Rakyat',
            'prefix' => 'P',
        ]);
        $kunjungan = Kunjungan::factory()->create([
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'tahun' => 2026,
            'bulan' => 3,
        ]);
        KunjunganMuatan::create([
            'kunjungan_id' => $kunjungan->id,
            'tipe' => 'BONGKAR',
            'jenis_barang' => 'General Cargo',
            'ton_m3' => 50,
            'jenis_hewan' => null,
            'jumlah_hewan' => 0,
        ]);

        $response = $this->actingAs($user)->get(route('laporan.pelra', ['tahun' => 2026]));

        $response->assertStatus(200);
        $response->assertSeeText('Laporan PELRA');
        $response->assertSeeText('Maret');

        $excelResponse = $this->actingAs($user)->get(route('laporan.pelra', ['tahun' => 2026, 'format' => 'excel']));

        $excelResponse->assertStatus(200);
        $excelResponse->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
