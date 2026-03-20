<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelabuhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipeMap = DB::table('tipe_pelabuhans')->pluck('id', 'nama');

        $data = [
            // Pelabuhan Internal UPP
            ['kode' => 'IDPJA', 'nama' => 'Benteng Jampea', 'tipe' => 'UPP', 'tipe_pelabuhan_id' => $tipeMap['UPP'] ?? null],
            ['kode' => null, 'nama' => 'Posker Ujung', 'tipe' => 'POSKER', 'tipe_pelabuhan_id' => $tipeMap['POSKER'] ?? null],
            ['kode' => null, 'nama' => 'Wilker Kayuadi', 'tipe' => 'WILKER', 'tipe_pelabuhan_id' => $tipeMap['WILKER'] ?? null],
            ['kode' => null, 'nama' => 'Wilker Bonerate', 'tipe' => 'WILKER', 'tipe_pelabuhan_id' => $tipeMap['WILKER'] ?? null],
            ['kode' => null, 'nama' => 'Wilker Jinato', 'tipe' => 'WILKER', 'tipe_pelabuhan_id' => $tipeMap['WILKER'] ?? null],
            ['kode' => null, 'nama' => 'Wilker Kalaotoa', 'tipe' => 'WILKER', 'tipe_pelabuhan_id' => $tipeMap['WILKER'] ?? null],

            // Pelabuhan Luar (sering muncul di data Excel)
            ['kode' => null, 'nama' => 'Makassar', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => 'IDSLR', 'nama' => 'Selayar', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Bulukumba', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Bira', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Tanjung Perak', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Labuan Bajo', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Marapokot', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'REO', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Sorong', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => 'IDSQN', 'nama' => 'Sanana', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Bawean', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Bintuni', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Gorom', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Geser', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Badas', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Bima', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Bantaeng', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
            ['kode' => null, 'nama' => 'Bau-Bau', 'tipe' => 'LUAR', 'tipe_pelabuhan_id' => $tipeMap['LUAR'] ?? null],
        ];

        DB::table('pelabuhans')->insert($data);
    }
}
