<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPelayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode' => 'PELRA', 'nama' => 'Pelayaran Rakyat', 'prefix' => 'A'],
            ['kode' => 'DALAM_NEGERI', 'nama' => 'Pelayaran Dalam Negeri', 'prefix' => 'B'],
            ['kode' => 'LUAR_NEGERI', 'nama' => 'Pelayaran Luar Negeri', 'prefix' => 'C'],
            ['kode' => 'PERINTIS', 'nama' => 'Perintis', 'prefix' => 'D'],
            ['kode' => 'FERRY_ASDP', 'nama' => 'Ferry ASDP', 'prefix' => 'E'],
            ['kode' => 'FERRY_DJPD', 'nama' => 'Ferry DJPD', 'prefix' => 'F'],
        ];

        DB::table('jenis_pelayarans')->insert($data);
    }
}
