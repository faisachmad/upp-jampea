<?php

namespace Database\Factories;

use App\Models\Bendera;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bendera>
 */
class BenderaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => strtoupper($this->faker->unique()->countryCode()),
            'nama_negara' => $this->faker->unique()->country(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
