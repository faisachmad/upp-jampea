<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangB3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'BBM', 'un_number' => 1203, 'kelas' => '3', 'kategori' => 'CAIRAN MUDAH TERBAKAR'],
            ['nama' => 'ELPIJI', 'un_number' => 1075, 'kelas' => '2.1', 'kategori' => 'GAS MUDAH TERBAKAR'],
            ['nama' => 'KOPRA', 'un_number' => 1363, 'kelas' => '4.1', 'kategori' => 'BAHAN PADAT MUDAH TERBAKAR'],
            ['nama' => 'ARANG', 'un_number' => 1361, 'kelas' => '4.2', 'kategori' => 'BAHAN PADAT MUDAH TERBAKAR'],
            ['nama' => 'PUPUK', 'un_number' => 3107, 'kelas' => '5.2', 'kategori' => 'PEROKSIDA ORGANIK'],
            ['nama' => 'JAMBU MENTE', 'un_number' => 1325, 'kelas' => '4.1', 'kategori' => 'BAHAN PADAT MUDAH TERBAKAR'],
        ];

        DB::table('barang_b3s')->insert($data);
    }
}
