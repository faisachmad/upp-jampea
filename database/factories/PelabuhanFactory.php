<?php

namespace Database\Factories;

use App\Models\Pelabuhan;
use App\Models\TipePelabuhan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pelabuhan>
 */
class PelabuhanFactory extends Factory
{
    protected $model = Pelabuhan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipe = TipePelabuhan::factory()->create();

        return [
            'kode' => 'PLB-'.strtoupper($this->faker->unique()->bothify('???###')),
            'nama' => 'Pelabuhan '.$this->faker->city(),
            'tipe' => $tipe->nama,
            'tipe_pelabuhan_id' => $tipe->id,
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the pelabuhan is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the pelabuhan is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the pelabuhan is UPP type.
     */
    public function upp(): static
    {
        return $this->state(function (array $attributes) {
            $tipe = TipePelabuhan::factory()->upp()->create();

            return [
                'tipe' => $tipe->nama,
                'tipe_pelabuhan_id' => $tipe->id,
            ];
        });
    }

    /**
     * Indicate that the pelabuhan is LUAR type.
     */
    public function luar(): static
    {
        return $this->state(function (array $attributes) {
            $tipe = TipePelabuhan::factory()->luar()->create();

            return [
                'tipe' => $tipe->nama,
                'tipe_pelabuhan_id' => $tipe->id,
            ];
        });
    }
}
