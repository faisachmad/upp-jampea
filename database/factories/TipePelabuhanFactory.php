<?php

namespace Database\Factories;

use App\Models\TipePelabuhan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipePelabuhan>
 */
class TipePelabuhanFactory extends Factory
{
    protected $model = TipePelabuhan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            ['nama' => 'UPP', 'keterangan' => 'Unit Penyelenggara Pelabuhan'],
            ['nama' => 'POSKER', 'keterangan' => 'Pos Pengawasan Kepelabuanan'],
            ['nama' => 'WILKER', 'keterangan' => 'Wilayah Kerja'],
            ['nama' => 'LUAR', 'keterangan' => 'Pelabuhan Luar Wilayah'],
        ];

        $type = $this->faker->randomElement($types);

        return [
            'nama' => $type['nama'] . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'keterangan' => $type['keterangan'],
        ];
    }

    /**
     * Indicate that the tipe pelabuhan is UPP.
     */
    public function upp(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama' => 'UPP-' . uniqid(),
            'keterangan' => 'Unit Penyelenggara Pelabuhan',
        ]);
    }

    /**
     * Indicate that the tipe pelabuhan is POSKER.
     */
    public function posker(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama' => 'POSKER-' . uniqid(),
            'keterangan' => 'Pos Pengawasan Kepelabuanan',
        ]);
    }

    /**
     * Indicate that the tipe pelabuhan is WILKER.
     */
    public function wilker(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama' => 'WILKER-' . uniqid(),
            'keterangan' => 'Wilayah Kerja',
        ]);
    }

    /**
     * Indicate that the tipe pelabuhan is LUAR.
     */
    public function luar(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama' => 'LUAR-' . uniqid(),
            'keterangan' => 'Pelabuhan Luar Wilayah',
        ]);
    }
}
