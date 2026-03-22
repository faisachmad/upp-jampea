<?php

namespace Tests\Feature;

use App\Models\JenisPelayaran;
use App\Models\Kapal;
use App\Models\Kunjungan;
use App\Models\Nakhoda;
use App\Models\Pelabuhan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KunjunganControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_it_can_display_kunjungan_index()
    {
        $kunjungan = Kunjungan::create([
            'pelabuhan_id' => Pelabuhan::factory()->create()->id,
            'kapal_id' => Kapal::factory()->create()->id,
            'jenis_pelayaran_id' => JenisPelayaran::factory()->create()->id,
            'nakhoda_id' => Nakhoda::factory()->create()->id,
            'bulan' => 3,
            'tahun' => 2026,
            'tgl_tiba' => '2026-03-20',
            'jam_tiba' => '10:00:00',
            'pelabuhan_asal_id' => Pelabuhan::factory()->create()->id,
            'tgl_berangkat' => '2026-03-21',
            'jam_berangkat' => '15:00:00',
            'pelabuhan_tujuan_id' => Pelabuhan::factory()->create()->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('kunjungan.index'));

        $response->assertStatus(200);
        $response->assertViewHas('kunjungans');
    }

    public function test_it_can_show_kunjungan_using_encrypted_id()
    {
        $kunjungan = Kunjungan::create([
            'pelabuhan_id' => Pelabuhan::factory()->create()->id,
            'kapal_id' => Kapal::factory()->create()->id,
            'jenis_pelayaran_id' => JenisPelayaran::factory()->create()->id,
            'nakhoda_id' => Nakhoda::factory()->create()->id,
            'bulan' => 3,
            'tahun' => 2026,
            'tgl_tiba' => '2026-03-20',
            'jam_tiba' => '10:00:00',
            'pelabuhan_asal_id' => Pelabuhan::factory()->create()->id,
            'tgl_berangkat' => '2026-03-21',
            'jam_berangkat' => '15:00:00',
            'pelabuhan_tujuan_id' => Pelabuhan::factory()->create()->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('kunjungan.show', $kunjungan));

        $response->assertStatus(200);
        $response->assertViewHas('kunjungan');
    }

    public function test_it_can_store_kunjungan()
    {
        $pelabuhanPencatat = Pelabuhan::factory()->create();
        $kapal = Kapal::factory()->create();
        $jenisPelayaran = JenisPelayaran::factory()->create();
        $nakhoda = Nakhoda::factory()->create();
        $pelabuhanAsal = Pelabuhan::factory()->create();
        $pelabuhanTujuan = Pelabuhan::factory()->create();

        $data = [
            'pelabuhan_id' => $pelabuhanPencatat->id,
            'kapal_id' => $kapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'nakhoda_id' => $nakhoda->id,
            'bulan' => 3,
            'tahun' => 2026,
            'tgl_datang' => '2026-03-20',
            'jam_datang' => '10:00',
            'pelabuhan_asal_id' => $pelabuhanAsal->id,
            'tgl_tolak' => '2026-03-21',
            'jam_tolak' => '15:00',
            'pelabuhan_tujuan_id' => $pelabuhanTujuan->id,
            
            // Penumpang
            'pnp_datang_dewasa' => 10,
            'pnp_datang_anak' => 2,
            'pnp_tolak_dewasa' => 5,
            'pnp_tolak_anak' => 1,
            
            // Kendaraan (total mobil akan jadi 3 + 4 + 0 + 0 + 0 = 7)
            'kend_datang_gol1' => 5,
            'kend_datang_gol2' => 3,
            'kend_datang_gol3' => 4,
            
            'kend_tolak_gol1' => 2,
            'kend_tolak_gol2' => 1,
            'kend_tolak_gol3' => 1,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('kunjungan.store'), $data);
        if (session('error')) {
            dump(session('error'));
        }
        $response->assertSessionHasNoErrors();

        $response->assertRedirect(route('kunjungan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('kunjungans', [
            'pelabuhan_id' => $pelabuhanPencatat->id,
            'kapal_id' => $kapal->id,
            'tgl_tiba' => '2026-03-20 00:00:00',
            'jam_tiba' => '10:00', 
            'tgl_berangkat' => '2026-03-21 00:00:00',
            'jam_berangkat' => '15:00',
            'penumpang_turun' => 12, // 10 + 2
            'penumpang_naik' => 6, // 5 + 1
            'motor_turun' => 5,
            'motor_naik' => 2,
            'mobil_turun' => 7, // 3 + 4
            'mobil_naik' => 2, // 1 + 1
        ]);
    }
}
