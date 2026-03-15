<?php

namespace Database\Factories;

use App\Models\Bendera;
use App\Models\JenisKapal;
use App\Models\Kapal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kapal>
 */
class KapalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => 'KM '.$this->faker->company(),
            'jenis_kapal_id' => JenisKapal::factory(),
            'gt' => $this->faker->randomFloat(2, 100, 10000),
            'dwt' => $this->faker->randomFloat(2, 50, 8000),
            'panjang' => $this->faker->randomFloat(2, 20, 200),
            'tanda_selar' => 'TS-'.$this->faker->unique()->numberBetween(1000, 9999),
            'call_sign' => strtoupper($this->faker->bothify('??###')),
            'tempat_kedudukan' => $this->faker->city(),
            'bendera_id' => Bendera::factory(),
            'pemilik_agen' => $this->faker->company(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
