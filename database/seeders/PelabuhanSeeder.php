<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelabuhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Pelabuhan Internal UPP
            ['kode' => 'IDPJA', 'nama' => 'Benteng Jampea', 'tipe' => 'UPP'],
            ['kode' => null, 'nama' => 'Posker Ujung', 'tipe' => 'POSKER'],
            ['kode' => null, 'nama' => 'Wilker Kayuadi', 'tipe' => 'WILKER'],
            ['kode' => null, 'nama' => 'Wilker Bonerate', 'tipe' => 'WILKER'],
            ['kode' => null, 'nama' => 'Wilker Jinato', 'tipe' => 'WILKER'],
            ['kode' => null, 'nama' => 'Wilker Kalaotoa', 'tipe' => 'WILKER'],

            // Pelabuhan Luar (sering muncul di data Excel)
            ['kode' => null, 'nama' => 'Makassar', 'tipe' => 'LUAR'],
            ['kode' => 'IDSLR', 'nama' => 'Selayar', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Bulukumba', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Bira', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Tanjung Perak', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Labuan Bajo', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Marapokot', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'REO', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Sorong', 'tipe' => 'LUAR'],
            ['kode' => 'IDSQN', 'nama' => 'Sanana', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Bawean', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Bintuni', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Gorom', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Geser', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Badas', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Bima', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Bantaeng', 'tipe' => 'LUAR'],
            ['kode' => null, 'nama' => 'Bau-Bau', 'tipe' => 'LUAR'],
        ];

        DB::table('pelabuhans')->insert($data);
    }
}
