<?php

namespace Database\Factories;

use App\Models\Kapal;
use App\Models\Nakhoda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Nakhoda>
 */
class NakhodaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => 'Kapten '.$this->faker->name(),
            'kapal_id' => Kapal::factory(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
