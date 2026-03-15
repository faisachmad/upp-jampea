<?php

namespace Database\Seeders;

use App\Models\JenisKapal;
use Illuminate\Database\Seeder;

class JenisKapalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisKapals = [
            [
                'kode' => 'KLM',
                'nama' => 'Kapal Layar Motor',
                'keterangan' => 'Kapal yang dilengkapi dengan layar dan motor',
            ],
            [
                'kode' => 'KM',
                'nama' => 'Kapal Motor',
                'keterangan' => 'Kapal yang digerakkan dengan motor',
            ],
            [
                'kode' => 'KMP',
                'nama' => 'Kapal Motor Penyeberangan',
                'keterangan' => 'Kapal motor yang digunakan untuk penyeberangan',
            ],
            [
                'kode' => 'MV',
                'nama' => 'Motor Vessel',
                'keterangan' => 'Kapal motor bermesin dengan bendera internasional',
            ],
        ];

        foreach ($jenisKapals as $jenisKapal) {
            JenisKapal::create($jenisKapal);
        }
    }
}
