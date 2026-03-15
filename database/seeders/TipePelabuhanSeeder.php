<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipePelabuhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'nama' => 'UPP',
                'keterangan' => 'Unit Penyelenggara Pelabuhan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'POSKER',
                'keterangan' => 'Pos Pengawasan Kepelabuanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'WILKER',
                'keterangan' => 'Wilayah Kerja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'LUAR',
                'keterangan' => 'Pelabuhan Luar Wilayah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($types as $type) {
            DB::table('tipe_pelabuhans')->updateOrInsert(
                ['nama' => $type['nama']],
                $type
            );
        }
    }
}
