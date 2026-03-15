<?php

namespace Database\Seeders;

use App\Models\Bendera;
use App\Models\JenisKapal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KapalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get jenis kapal IDs by kode
        $jenisKapalMap = JenisKapal::pluck('id', 'kode')->toArray();

        // Get Indonesia bendera ID (default for all ships)
        $benderaIndonesia = Bendera::where('kode', 'IDN')->first();

        $data = [
            ['nama' => 'PESONA BAHARI', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 161, 'pemilik_agen' => 'PT. BATANA BAHARI', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'CATUR PUTRA', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 213, 'pemilik_agen' => 'CARLA', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'NEW SELSABIEL', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 36, 'pemilik_agen' => 'H. SYAMSUL BAHRI', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'ANDIN JAYA', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 125, 'pemilik_agen' => 'SARIADIN', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'REZKY AQILA', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 22, 'pemilik_agen' => 'H. SAPPARA', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'CITRA BAHARI', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 162, 'pemilik_agen' => 'PT. BATANA BAHARI', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'AL KAUTSAR 3', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 104, 'pemilik_agen' => 'BAU LINDA', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'NURUL SALSA 01', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 78, 'pemilik_agen' => 'BASRIADI', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'SABUK NUSANTARA 85', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 2097, 'pemilik_agen' => 'PT.PELNI', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'SULTAN HASANUDDIN', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 1257, 'pemilik_agen' => 'BLU PIP MAKASSAR', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'SABUK NUSANTARA 49', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 2090, 'pemilik_agen' => 'PT.KUAT', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'MITRA DONGGALA', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 655, 'pemilik_agen' => 'PT. MITRA ABADI WISESA', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'MITRA ABADI II', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 612, 'pemilik_agen' => 'PT. MITRA ABADI WISESA', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'KAISEI MARU I', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 672, 'pemilik_agen' => 'PT. PELNUS SERAM', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'SANGKE PALANGGA', 'jenis_kapal_id' => $jenisKapalMap['KMP'], 'gt' => 560, 'pemilik_agen' => 'PT. ASDP', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'TAKABONERATE', 'jenis_kapal_id' => $jenisKapalMap['KMP'], 'gt' => 842, 'pemilik_agen' => 'DIREKTORAT JENDERAL PERHUBUNGAN DARAT', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'CORAL GEOGRAPHER', 'jenis_kapal_id' => $jenisKapalMap['MV'], 'gt' => 5602, 'pemilik_agen' => 'PT. BAHARI EKA NUSANTARA', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'HARAPAN MULIA', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 165, 'pemilik_agen' => 'H. MUHLIS', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'CITRA MAKMUR', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 250, 'pemilik_agen' => 'PT. WAKATOBI MARITIM SUKSES', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'MANDIRI UTAMA 01', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 17, 'pemilik_agen' => 'SITTI HUMRAH, S.PD.I', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'RAODATUL JANAH', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 11, 'pemilik_agen' => 'SYAMSUL RIZAL', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'HARAPAN KITA', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 118, 'pemilik_agen' => 'PT. GARUDA INDAH PERMAI', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'AISYA PUTRI', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 22, 'pemilik_agen' => 'SAENONG', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'ANDI ANSAR', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 19, 'pemilik_agen' => 'ANDI AKBAR', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'AHLIANA INDAH', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 16, 'pemilik_agen' => 'RUSDIANTO', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'JUSMA JAYA 02', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 21, 'pemilik_agen' => 'MUH.YAMIN', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'SINAR PERAHU II', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 146, 'pemilik_agen' => 'PT. SINAR PERAHU', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'PUTRA PERMATA', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 98, 'pemilik_agen' => 'H. MUHLIS', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'CAHAYA MENTARI', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 84, 'pemilik_agen' => 'RAHMAN', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'MUTIARA LAUT', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 73, 'pemilik_agen' => 'PT. MUTIARA BAHARI', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'BINTANG SELATAN', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 450, 'pemilik_agen' => 'PT. BINTANG LAUT', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'KARYA UTAMA', 'jenis_kapal_id' => $jenisKapalMap['KM'], 'gt' => 387, 'pemilik_agen' => 'CV. KARYA MANDIRI', 'bendera_id' => $benderaIndonesia->id],
            ['nama' => 'BAROKAH JAYA', 'jenis_kapal_id' => $jenisKapalMap['KLM'], 'gt' => 55, 'pemilik_agen' => 'H. BAHARUDDIN', 'bendera_id' => $benderaIndonesia->id],
        ];

        DB::table('kapals')->insert($data);
    }
}
