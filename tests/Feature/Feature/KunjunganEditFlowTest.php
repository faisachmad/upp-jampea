<?php

namespace Tests\Feature\Feature;

use App\Models\BarangB3;
use App\Models\JenisPelayaran;
use App\Models\Kapal;
use App\Models\Kunjungan;
use App\Models\Nakhoda;
use App\Models\Pelabuhan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KunjunganEditFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_kunjungan_can_be_edited_with_muatan_and_b3_rows(): void
    {
        $user = User::factory()->create();
        $pelabuhan = Pelabuhan::factory()->create();
        $pelabuhanAsal = Pelabuhan::factory()->create();
        $pelabuhanTujuan = Pelabuhan::factory()->create();
        $kapal = Kapal::factory()->create();
        $nakhoda = Nakhoda::factory()->create(['kapal_id' => $kapal->id]);
        $jenisPelayaran = JenisPelayaran::factory()->create([
            'kode' => 'DN',
            'nama' => 'Dalam Negeri',
            'prefix' => 'D',
        ]);
        $barangB3 = BarangB3::factory()->create();

        $kunjungan = Kunjungan::factory()->create([
            'pelabuhan_id' => $pelabuhan->id,
            'kapal_id' => $kapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'nakhoda_id' => $nakhoda->id,
            'pelabuhan_asal_id' => $pelabuhanAsal->id,
            'pelabuhan_tujuan_id' => $pelabuhanTujuan->id,
            'tahun' => 2026,
            'bulan' => 3,
        ]);

        $editResponse = $this->actingAs($user)->get(route('kunjungan.edit', $kunjungan));

        $editResponse->assertStatus(200);
        $editResponse->assertSeeText('Edit Kunjungan Kapal');

        $payload = [
            'pelabuhan_id' => $pelabuhan->id,
            'kapal_id' => $kapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'nakhoda_id' => $nakhoda->id,
            'bulan' => 3,
            'tahun' => 2026,
            'tgl_datang' => '2026-03-20',
            'jam_datang' => '10:00',
            'pelabuhan_asal_id' => $pelabuhanAsal->id,
            'status_muatan_tiba' => 'M',
            'tgl_tambat' => '2026-03-20',
            'jam_tambat' => '11:00',
            'no_spb_datang' => 'SPB-ARR-01',
            'tgl_tolak' => '2026-03-21',
            'jam_tolak' => '15:00',
            'pelabuhan_tujuan_id' => $pelabuhanTujuan->id,
            'status_muatan_tolak' => 'K',
            'no_spb_tolak' => 'SPB-DEP-01',
            'eta' => '2026-03-22',
            'pnp_datang_dewasa' => 12,
            'pnp_datang_anak' => 3,
            'pnp_tolak_dewasa' => 10,
            'pnp_tolak_anak' => 2,
            'kend_datang_gol1' => 4,
            'kend_datang_gol2' => 3,
            'kend_datang_gol3' => 2,
            'kend_datang_gol4a' => 1,
            'kend_datang_gol4b' => 1,
            'kend_datang_gol5' => 1,
            'kend_tolak_gol1' => 2,
            'kend_tolak_gol2' => 2,
            'kend_tolak_gol3' => 1,
            'kend_tolak_gol4a' => 1,
            'kend_tolak_gol4b' => 0,
            'kend_tolak_gol5' => 0,
            'lanjutan_jenis' => 'Semen',
            'lanjutan_ton' => 12.5,
            'lanjutan_mobil' => 1,
            'lanjutan_motor' => 2,
            'lanjutan_penumpang' => 5,
            'muatan' => [[
                'tipe' => 'BONGKAR',
                'jenis_barang' => 'Semen Curah',
                'ton_m3' => 30,
                'jenis_hewan' => null,
                'jumlah_hewan' => null,
            ]],
            'b3' => [[
                'barang_b3_id' => $barangB3->id,
                'jenis_kegiatan' => 'MUAT',
                'bentuk_muatan' => 'PADAT',
                'jumlah_ton' => 4,
                'jumlah_container' => 1,
                'kemasan' => 'Drum',
                'jumlah' => 5,
                'petugas' => 'Petugas Uji',
            ]],
        ];

        $response = $this->actingAs($user)->put(route('kunjungan.update', $kunjungan), $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('kunjungans', [
            'id' => $kunjungan->id,
            'penumpang_turun' => 15,
            'penumpang_naik' => 12,
            'motor_turun' => 4,
            'motor_naik' => 2,
            'mobil_turun' => 8,
            'mobil_naik' => 4,
            'status_muatan_tiba' => 'M',
            'status_muatan_tolak' => 'K',
        ]);
        $this->assertDatabaseHas('kunjungan_muatans', [
            'kunjungan_id' => $kunjungan->id,
            'jenis_barang' => 'Semen Curah',
        ]);
        $this->assertDatabaseHas('kunjungan_b3s', [
            'kunjungan_id' => $kunjungan->id,
            'petugas' => 'Petugas Uji',
        ]);
    }
}
