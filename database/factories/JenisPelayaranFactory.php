<?php

namespace Database\Factories;

use App\Models\JenisPelayaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JenisPelayaran>
 */
class JenisPelayaranFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => strtoupper($this->faker->unique()->lexify('??_???')),
            'nama' => $this->faker->words(3, true),
            'prefix' => strtoupper($this->faker->unique()->lexify('?')),
        ];
    }
}
