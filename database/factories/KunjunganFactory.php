<?php

namespace Database\Factories;

use App\Models\JenisPelayaran;
use App\Models\Kapal;
use App\Models\Kunjungan;
use App\Models\Nakhoda;
use App\Models\Pelabuhan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kunjungan>
 */
class KunjunganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pnpDatangDewasa = fake()->numberBetween(0, 100);
        $pnpDatangAnak = fake()->numberBetween(0, 40);
        $pnpTolakDewasa = fake()->numberBetween(0, 100);
        $pnpTolakAnak = fake()->numberBetween(0, 40);

        $kendDatangGol1 = fake()->numberBetween(0, 20);
        $kendDatangGol2 = fake()->numberBetween(0, 10);
        $kendDatangGol3 = fake()->numberBetween(0, 10);
        $kendDatangGol4a = fake()->numberBetween(0, 10);
        $kendDatangGol4b = fake()->numberBetween(0, 10);
        $kendDatangGol5 = fake()->numberBetween(0, 10);
        $kendTolakGol1 = fake()->numberBetween(0, 20);
        $kendTolakGol2 = fake()->numberBetween(0, 10);
        $kendTolakGol3 = fake()->numberBetween(0, 10);
        $kendTolakGol4a = fake()->numberBetween(0, 10);
        $kendTolakGol4b = fake()->numberBetween(0, 10);
        $kendTolakGol5 = fake()->numberBetween(0, 10);

        return [
            'pelabuhan_id' => Pelabuhan::factory(),
            'kapal_id' => Kapal::factory(),
            'jenis_pelayaran_id' => JenisPelayaran::factory(),
            'nakhoda_id' => Nakhoda::factory(),
            'bulan' => fake()->numberBetween(1, 12),
            'tahun' => 2026,
            'tgl_tiba' => '2026-03-20',
            'jam_tiba' => '10:00:00',
            'pelabuhan_asal_id' => Pelabuhan::factory(),
            'status_muatan_tiba' => fake()->randomElement(['M', 'K', 'ML']),
            'tgl_tambat' => '2026-03-20',
            'jam_tambat' => '11:00:00',
            'tgl_berangkat' => '2026-03-21',
            'jam_berangkat' => '15:00:00',
            'pelabuhan_tujuan_id' => Pelabuhan::factory(),
            'status_muatan_tolak' => fake()->randomElement(['M', 'K', 'ML']),
            'no_spb_tiba' => 'SPB-TIBA-001',
            'no_spb_tolak' => 'SPB-TOLAK-001',
            'eta' => '2026-03-22',
            'pnp_datang_dewasa' => $pnpDatangDewasa,
            'pnp_datang_anak' => $pnpDatangAnak,
            'pnp_tolak_dewasa' => $pnpTolakDewasa,
            'pnp_tolak_anak' => $pnpTolakAnak,
            'penumpang_turun' => $pnpDatangDewasa + $pnpDatangAnak,
            'penumpang_naik' => $pnpTolakDewasa + $pnpTolakAnak,
            'kend_datang_gol1' => $kendDatangGol1,
            'kend_datang_gol2' => $kendDatangGol2,
            'kend_datang_gol3' => $kendDatangGol3,
            'kend_datang_gol4a' => $kendDatangGol4a,
            'kend_datang_gol4b' => $kendDatangGol4b,
            'kend_datang_gol5' => $kendDatangGol5,
            'kend_tolak_gol1' => $kendTolakGol1,
            'kend_tolak_gol2' => $kendTolakGol2,
            'kend_tolak_gol3' => $kendTolakGol3,
            'kend_tolak_gol4a' => $kendTolakGol4a,
            'kend_tolak_gol4b' => $kendTolakGol4b,
            'kend_tolak_gol5' => $kendTolakGol5,
            'motor_turun' => $kendDatangGol1,
            'motor_naik' => $kendTolakGol1,
            'mobil_turun' => $kendDatangGol2 + $kendDatangGol3 + $kendDatangGol4a + $kendDatangGol4b + $kendDatangGol5,
            'mobil_naik' => $kendTolakGol2 + $kendTolakGol3 + $kendTolakGol4a + $kendTolakGol4b + $kendTolakGol5,
            'lanjutan_jenis' => fake()->optional()->word(),
            'lanjutan_ton' => fake()->randomFloat(2, 0, 100),
            'lanjutan_mobil' => fake()->numberBetween(0, 10),
            'lanjutan_motor' => fake()->numberBetween(0, 10),
            'lanjutan_penumpang' => fake()->numberBetween(0, 100),
        ];
    }
}
