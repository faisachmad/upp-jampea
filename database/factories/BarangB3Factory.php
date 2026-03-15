<?php

namespace Database\Factories;

use App\Models\BarangB3;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BarangB3>
 */
class BarangB3Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->words(3, true).' Chemical',
            'un_number' => 'UN'.$this->faker->numberBetween(1000, 9999),
            'kelas' => $this->faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', '9']),
            'kategori' => $this->faker->randomElement(['Cair', 'Padat', 'Gas', 'Korosif']),
        ];
    }
}
