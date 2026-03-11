<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KapalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'PESONA BAHARI', 'jenis' => 'KLM', 'gt' => 161, 'pemilik_agen' => 'PT. BATANA BAHARI'],
            ['nama' => 'CATUR PUTRA', 'jenis' => 'KLM', 'gt' => 213, 'pemilik_agen' => 'CARLA'],
            ['nama' => 'NEW SELSABIEL', 'jenis' => 'KLM', 'gt' => 36, 'pemilik_agen' => 'H. SYAMSUL BAHRI'],
            ['nama' => 'ANDIN JAYA', 'jenis' => 'KLM', 'gt' => 125, 'pemilik_agen' => 'SARIADIN'],
            ['nama' => 'REZKY AQILA', 'jenis' => 'KLM', 'gt' => 22, 'pemilik_agen' => 'H. SAPPARA'],
            ['nama' => 'CITRA BAHARI', 'jenis' => 'KLM', 'gt' => 162, 'pemilik_agen' => 'PT. BATANA BAHARI'],
            ['nama' => 'AL KAUTSAR 3', 'jenis' => 'KM', 'gt' => 104, 'pemilik_agen' => 'BAU LINDA'],
            ['nama' => 'NURUL SALSA 01', 'jenis' => 'KM', 'gt' => 78, 'pemilik_agen' => 'BASRIADI'],
            ['nama' => 'SABUK NUSANTARA 85', 'jenis' => 'KM', 'gt' => 2097, 'pemilik_agen' => 'PT.PELNI'],
            ['nama' => 'SULTAN HASANUDDIN', 'jenis' => 'KM', 'gt' => 1257, 'pemilik_agen' => 'BLU PIP MAKASSAR'],
            ['nama' => 'SABUK NUSANTARA 49', 'jenis' => 'KM', 'gt' => 2090, 'pemilik_agen' => 'PT.KUAT'],
            ['nama' => 'MITRA DONGGALA', 'jenis' => 'KM', 'gt' => 655, 'pemilik_agen' => 'PT. MITRA ABADI WISESA'],
            ['nama' => 'MITRA ABADI II', 'jenis' => 'KM', 'gt' => 612, 'pemilik_agen' => 'PT. MITRA ABADI WISESA'],
            ['nama' => 'KAISEI MARU I', 'jenis' => 'KM', 'gt' => 672, 'pemilik_agen' => 'PT. PELNUS SERAM'],
            ['nama' => 'SANGKE PALANGGA', 'jenis' => 'KMP', 'gt' => 560, 'pemilik_agen' => 'PT. ASDP'],
            ['nama' => 'TAKABONERATE', 'jenis' => 'KMP', 'gt' => 842, 'pemilik_agen' => 'DIREKTORAT JENDERAL PERHUBUNGAN DARAT'],
            ['nama' => 'CORAL GEOGRAPHER', 'jenis' => 'MV', 'gt' => 5602, 'pemilik_agen' => 'PT. BAHARI EKA NUSANTARA'],
            ['nama' => 'HARAPAN MULIA', 'jenis' => 'KLM', 'gt' => 165, 'pemilik_agen' => 'H. MUHLIS'],
            ['nama' => 'CITRA MAKMUR', 'jenis' => 'KLM', 'gt' => 250, 'pemilik_agen' => 'PT. WAKATOBI MARITIM SUKSES'],
            ['nama' => 'MANDIRI UTAMA 01', 'jenis' => 'KLM', 'gt' => 17, 'pemilik_agen' => 'SITTI HUMRAH, S.PD.I'],
            ['nama' => 'RAODATUL JANAH', 'jenis' => 'KLM', 'gt' => 11, 'pemilik_agen' => 'SYAMSUL RIZAL'],
            ['nama' => 'HARAPAN KITA', 'jenis' => 'KLM', 'gt' => 118, 'pemilik_agen' => 'PT. GARUDA INDAH PERMAI'],
            ['nama' => 'AISYA PUTRI', 'jenis' => 'KLM', 'gt' => 22, 'pemilik_agen' => 'SAENONG'],
            ['nama' => 'ANDI ANSAR', 'jenis' => 'KLM', 'gt' => 19, 'pemilik_agen' => 'ANDI AKBAR'],
            ['nama' => 'AHLIANA INDAH', 'jenis' => 'KLM', 'gt' => 16, 'pemilik_agen' => 'RUSDIANTO'],
            ['nama' => 'JUSMA JAYA 02', 'jenis' => 'KLM', 'gt' => 21, 'pemilik_agen' => 'MUH.YAMIN'],
            ['nama' => 'SINAR PERAHU II', 'jenis' => 'KLM', 'gt' => 146, 'pemilik_agen' => 'PT. SINAR PERAHU'],
            ['nama' => 'PUTRA PERMATA', 'jenis' => 'KLM', 'gt' => 98, 'pemilik_agen' => 'H. MUHLIS'],
            ['nama' => 'CAHAYA MENTARI', 'jenis' => 'KLM', 'gt' => 84, 'pemilik_agen' => 'RAHMAN'],
            ['nama' => 'MUTIARA LAUT', 'jenis' => 'KLM', 'gt' => 73, 'pemilik_agen' => 'PT. MUTIARA BAHARI'],
            ['nama' => 'BINTANG SELATAN', 'jenis' => 'KM', 'gt' => 450, 'pemilik_agen' => 'PT. BINTANG LAUT'],
            ['nama' => 'KARYA UTAMA', 'jenis' => 'KM', 'gt' => 387, 'pemilik_agen' => 'CV. KARYA MANDIRI'],
            ['nama' => 'BAROKAH JAYA', 'jenis' => 'KLM', 'gt' => 55, 'pemilik_agen' => 'H. BAHARUDDIN'],
        ];

        DB::table('kapals')->insert($data);
    }
}
