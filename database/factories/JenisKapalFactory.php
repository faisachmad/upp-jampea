<?php

namespace Database\Factories;

use App\Models\JenisKapal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JenisKapal>
 */
class JenisKapalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = $this->faker->randomElement(['Kapal Penumpang', 'Kapal Kargo', 'Kapal Tanker', 'Kapal Layar Motor', 'Kapal Ikan']);

        // Generate kode from nama
        $words = explode(' ', $nama);
        $kode = '';
        foreach ($words as $word) {
            if (strlen($word) > 0) {
                $kode .= strtoupper($word[0]);
            }
        }
        $kode = substr($kode, 0, 10).$this->faker->unique()->numberBetween(1, 999);

        return [
            'kode' => $kode,
            'nama' => $nama,
            'keterangan' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
