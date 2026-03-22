<?php

namespace Tests\Feature\Feature;

use App\Models\JenisKapal;
use App\Models\JenisPelayaran;
use App\Models\Kapal;
use App\Models\Kunjungan;
use App\Models\KunjunganMuatan;
use App\Models\Pelabuhan;
use App\Models\User;
use App\Services\LaporanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class LaporanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelra_report_can_be_rendered_and_exported(): void
    {
        $user = User::factory()->create();
        $pelabuhan = Pelabuhan::factory()->upp()->active()->create([
            'nama' => 'Jampea',
        ]);
        $jenisPelayaran = JenisPelayaran::factory()->create([
            'kode' => 'PELRA',
            'nama' => 'Pelayaran Rakyat',
            'prefix' => 'P',
        ]);
        $kapal = Kapal::factory()->create([
            'gt' => 959,
        ]);
        $kunjungan = Kunjungan::factory()->create([
            'pelabuhan_id' => $pelabuhan->id,
            'kapal_id' => $kapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'tahun' => 2026,
            'bulan' => 3,
            'penumpang_turun' => 23,
            'penumpang_naik' => 5,
            'lanjutan_penumpang' => 0,
        ]);
        KunjunganMuatan::create([
            'kunjungan_id' => $kunjungan->id,
            'tipe' => 'BONGKAR',
            'jenis_barang' => 'General Cargo',
            'ton_m3' => 50,
            'jenis_hewan' => null,
            'jumlah_hewan' => 0,
        ]);
        KunjunganMuatan::create([
            'kunjungan_id' => $kunjungan->id,
            'tipe' => 'MUAT',
            'jenis_barang' => 'General Cargo',
            'ton_m3' => 42,
            'jenis_hewan' => null,
            'jumlah_hewan' => 0,
        ]);

        $response = $this->actingAs($user)->get(route('laporan.pelra', [
            'tahun' => 2026,
            'pelabuhan_id' => $pelabuhan->id,
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Laporan PELRA');
        $response->assertSeeText('PELABUHAN JAMPEA');
        $response->assertSeeText('Barang');
        $response->assertSeeText('Penumpang');
        $response->assertSeeText('TOTAL');
        $response->assertSeeText('MARET');

        $excelResponse = $this->actingAs($user)->get(route('laporan.pelra', [
            'tahun' => 2026,
            'pelabuhan_id' => $pelabuhan->id,
            'format' => 'excel',
        ]));

        $excelResponse->assertStatus(200);
        $excelResponse->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $excelFile = tempnam(sys_get_temp_dir(), 'pelra-report');
        file_put_contents($excelFile, $excelResponse->streamedContent());

        $spreadsheet = IOFactory::load($excelFile);
        $sheet = $spreadsheet->getActiveSheet();

        $this->assertSame('PELABUHAN JAMPEA', $sheet->getCell('A2')->getValue());
        $this->assertSame('PELABUHAN JAMPEA', $sheet->getCell('A4')->getValue());
        $this->assertSame('Barang ( Ton / M3 )', $sheet->getCell('E5')->getValue());
        $this->assertSame('Penumpang', $sheet->getCell('K5')->getValue());
        $this->assertSame('TOTAL', $sheet->getCell('A20')->getValue());

        @unlink($excelFile);
    }

    public function test_perintis_report_uses_penumpang_and_motor_header_composition(): void
    {
        $user = User::factory()->create();
        $pelabuhan = Pelabuhan::factory()->upp()->active()->create([
            'nama' => 'Jampea',
        ]);
        $jenisPelayaran = JenisPelayaran::factory()->create([
            'kode' => 'PERINTIS',
            'nama' => 'Perintis',
            'prefix' => 'R',
        ]);
        $kapal = Kapal::factory()->create([
            'gt' => 1200,
        ]);

        Kunjungan::factory()->create([
            'pelabuhan_id' => $pelabuhan->id,
            'kapal_id' => $kapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'tahun' => 2026,
            'bulan' => 2,
            'penumpang_turun' => 15,
            'penumpang_naik' => 8,
            'lanjutan_penumpang' => 2,
            'motor_turun' => 4,
            'motor_naik' => 3,
        ]);

        $response = $this->actingAs($user)->get(route('laporan.perintis', [
            'tahun' => 2026,
            'pelabuhan_id' => $pelabuhan->id,
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Laporan Perintis');
        $response->assertSeeText('PELABUHAN JAMPEA');
        $response->assertSeeText('Penumpang');
        $response->assertSeeText('Motor');
        $response->assertDontSeeText('Hewan');
        $response->assertSeeText('TOTAL');
        $response->assertSeeText('FEBRUARI');
    }

    public function test_ferry_report_uses_kendaraan_and_penumpang_header_composition(): void
    {
        $user = User::factory()->create();
        $pelabuhan = Pelabuhan::factory()->upp()->active()->create([
            'nama' => 'Jampea',
        ]);
        $jenisPelayaran = JenisPelayaran::factory()->create([
            'kode' => 'FERRY',
            'nama' => 'Ferry',
            'prefix' => 'F',
        ]);
        $kapal = Kapal::factory()->create([
            'gt' => 9254,
        ]);

        Kunjungan::factory()->create([
            'pelabuhan_id' => $pelabuhan->id,
            'kapal_id' => $kapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'tahun' => 2026,
            'bulan' => 1,
            'mobil_turun' => 17,
            'motor_turun' => 102,
            'mobil_naik' => 16,
            'motor_naik' => 44,
            'penumpang_turun' => 526,
            'penumpang_naik' => 199,
            'lanjutan_penumpang' => 339,
        ]);

        $response = $this->actingAs($user)->get(route('laporan.ferry', [
            'tahun' => 2026,
            'pelabuhan_id' => $pelabuhan->id,
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Laporan Ferry');
        $response->assertSeeText('PELABUHAN JAMPEA');
        $response->assertSeeText('Kendaraan');
        $response->assertSeeText('Mobil');
        $response->assertSeeText('Motor');
        $response->assertSeeText('Penumpang');
        $response->assertDontSeeText('Hewan');
        $response->assertSeeText('TOTAL');
        $response->assertSeeText('JANUARI');
    }

    public function test_pdf_view_uses_shared_structured_layout_for_ferry_report(): void
    {
        $pelabuhan = Pelabuhan::factory()->upp()->active()->create([
            'nama' => 'Jampea',
        ]);

        $report = app(LaporanService::class)->buildMonthlyReport('ferry', 2026, $pelabuhan->id);

        $html = view('laporan.pdf', [
            'report' => $report,
            'filters' => ['year' => 2026, 'pelabuhan_id' => $pelabuhan->id],
            'selectedPelabuhan' => $pelabuhan,
        ])->render();

        $this->assertStringContainsString('PELABUHAN JAMPEA', $html);
        $this->assertStringContainsString('Kendaraan', $html);
        $this->assertStringContainsString('Mobil', $html);
        $this->assertStringContainsString('Penumpang', $html);
        $this->assertStringContainsString('TOTAL', $html);
    }

    public function test_dalam_negeri_and_luar_negeri_reports_use_same_barang_and_penumpang_header_composition(): void
    {
        $user = User::factory()->create();
        $pelabuhan = Pelabuhan::factory()->upp()->active()->create([
            'nama' => 'Jampea',
        ]);
        $kapal = Kapal::factory()->create([
            'gt' => 1939,
        ]);

        $cases = [
            'dalam-negeri' => ['kode' => 'DN', 'title' => 'Laporan Angkutan Laut Dalam Negeri'],
            'luar-negeri' => ['kode' => 'LN', 'title' => 'Laporan Angkutan Laut Luar Negeri'],
        ];

        foreach ($cases as $routeName => $case) {
            $jenisPelayaran = JenisPelayaran::factory()->create([
                'kode' => $case['kode'],
                'nama' => $case['title'],
                'prefix' => str($case['kode'])->substr(0, 1)->upper()->value(),
            ]);

            Kunjungan::factory()->create([
                'pelabuhan_id' => $pelabuhan->id,
                'kapal_id' => $kapal->id,
                'jenis_pelayaran_id' => $jenisPelayaran->id,
                'tahun' => 2026,
                'bulan' => 1,
                'penumpang_turun' => 8,
                'penumpang_naik' => 6,
                'lanjutan_penumpang' => 2,
            ]);

            $response = $this->actingAs($user)->get(route('laporan.'.$routeName, [
                'tahun' => 2026,
                'pelabuhan_id' => $pelabuhan->id,
            ]));

            $response->assertStatus(200);
            $response->assertSeeText($case['title']);
            $response->assertSeeText('Barang');
            $response->assertSeeText('Penumpang');
            $response->assertDontSeeText('Hewan');
            $response->assertDontSeeText('Motor');
            $response->assertSeeText('TOTAL');

            $excelResponse = $this->actingAs($user)->get(route('laporan.'.$routeName, [
                'tahun' => 2026,
                'pelabuhan_id' => $pelabuhan->id,
                'format' => 'excel',
            ]));

            $excelResponse->assertStatus(200);

            $excelFile = tempnam(sys_get_temp_dir(), $routeName);
            file_put_contents($excelFile, $excelResponse->streamedContent());

            $spreadsheet = IOFactory::load($excelFile);
            $sheet = $spreadsheet->getActiveSheet();

            $this->assertSame('Barang ( Ton / M3 )', $sheet->getCell('E5')->getValue());
            $this->assertSame('Penumpang', $sheet->getCell('I5')->getValue());
            $this->assertSame('TOTAL', $sheet->getCell('A20')->getValue());

            @unlink($excelFile);
        }
    }

    public function test_rekap_spb_report_uses_grouped_jenis_ukuran_kapal_header_composition(): void
    {
        $user = User::factory()->create();
        $pelabuhan = Pelabuhan::factory()->upp()->active()->create([
            'nama' => 'Jampea',
        ]);

        $jenisKapalKlm = JenisKapal::factory()->create([
            'kode' => 'KLM',
            'nama' => 'Kapal Layar Motor',
        ]);

        $ppk27Kapal = Kapal::factory()->create([
            'gt' => 700,
        ]);
        $ppk29Kapal = Kapal::factory()->create([
            'gt' => 300,
        ]);
        $klmKapal = Kapal::factory()->create([
            'gt' => 120,
            'jenis_kapal_id' => $jenisKapalKlm->id,
        ]);

        $jenisPelayaran = JenisPelayaran::factory()->create([
            'kode' => 'PERINTIS',
            'nama' => 'Perintis',
            'prefix' => 'R',
        ]);

        Kunjungan::factory()->create([
            'pelabuhan_id' => $pelabuhan->id,
            'kapal_id' => $ppk27Kapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'tahun' => 2026,
            'bulan' => 1,
            'no_spb_tolak' => 'SPB-001',
        ]);

        Kunjungan::factory()->create([
            'pelabuhan_id' => $pelabuhan->id,
            'kapal_id' => $ppk29Kapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'tahun' => 2026,
            'bulan' => 1,
            'no_spb_tiba' => 'SPB-002',
        ]);

        Kunjungan::factory()->create([
            'pelabuhan_id' => $pelabuhan->id,
            'kapal_id' => $klmKapal->id,
            'jenis_pelayaran_id' => $jenisPelayaran->id,
            'tahun' => 2026,
            'bulan' => 1,
            'no_spb_tiba' => 'SPB-003',
        ]);

        $response = $this->actingAs($user)->get(route('laporan.rekap-spb', [
            'tahun' => 2026,
            'pelabuhan_id' => $pelabuhan->id,
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Rekap Pengeluaran SPB');
        $response->assertSeeText('Jenis / Ukuran Kapal');
        $response->assertSeeText('PPK.27');
        $response->assertSeeText('PPK.29');
        $response->assertSeeText('KLM');
        $response->assertSeeText('Rusak / Batal');
        $response->assertSeeText('Jumlah');
        $response->assertSeeText('TOTAL');

        $excelResponse = $this->actingAs($user)->get(route('laporan.rekap-spb', [
            'tahun' => 2026,
            'pelabuhan_id' => $pelabuhan->id,
            'format' => 'excel',
        ]));

        $excelResponse->assertStatus(200);

        $excelFile = tempnam(sys_get_temp_dir(), 'rekap-spb');
        file_put_contents($excelFile, $excelResponse->streamedContent());

        $spreadsheet = IOFactory::load($excelFile);
        $sheet = $spreadsheet->getActiveSheet();

        $this->assertSame('Jenis / Ukuran Kapal', $sheet->getCell('C5')->getValue());
        $this->assertSame('PPK.27', $sheet->getCell('C6')->getValue());
        $this->assertSame('Rusak / Batal', $sheet->getCell('H5')->getValue());
        $this->assertSame('TOTAL', $sheet->getCell('A19')->getValue());

        @unlink($excelFile);
    }

    public function test_rekap_operasional_report_uses_grouped_header_composition_and_dense_table_layout(): void
    {
        $user = User::factory()->create();
        $pelabuhan = Pelabuhan::factory()->upp()->active()->create([
            'nama' => 'Jampea',
        ]);
        $kapal = Kapal::factory()->create([
            'gt' => 1800,
        ]);

        $jenisPelayaran = [
            JenisPelayaran::factory()->create(['kode' => 'LN', 'nama' => 'Luar Negeri', 'prefix' => 'L']),
            JenisPelayaran::factory()->create(['kode' => 'DN', 'nama' => 'Dalam Negeri', 'prefix' => 'D']),
            JenisPelayaran::factory()->create(['kode' => 'PELRA', 'nama' => 'Pelayaran Rakyat', 'prefix' => 'P']),
            JenisPelayaran::factory()->create(['kode' => 'PERINTIS', 'nama' => 'Perintis', 'prefix' => 'R']),
            JenisPelayaran::factory()->create(['kode' => 'FERRY', 'nama' => 'Ferry', 'prefix' => 'F']),
        ];

        foreach ($jenisPelayaran as $index => $jenis) {
            Kunjungan::factory()->create([
                'pelabuhan_id' => $pelabuhan->id,
                'kapal_id' => $kapal->id,
                'jenis_pelayaran_id' => $jenis->id,
                'tahun' => 2026,
                'bulan' => 1,
                'penumpang_turun' => 10 + $index,
                'penumpang_naik' => 5 + $index,
                'lanjutan_penumpang' => 1,
                'motor_turun' => 2,
                'motor_naik' => 1,
                'mobil_turun' => 1,
                'mobil_naik' => 1,
            ]);
        }

        $response = $this->actingAs($user)->get(route('laporan.rekap-operasional', [
            'tahun' => 2026,
            'pelabuhan_id' => $pelabuhan->id,
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Rekap Operasional');
        $response->assertSeeText('Jenis Pelayaran');
        $response->assertSeeText('Kapal');
        $response->assertSeeText('Bongkar');
        $response->assertSeeText('Muat');
        $response->assertSeeText('Penumpang');
        $response->assertSee('report-sheet--dense', false);
        $response->assertSee('report-sheet--sticky-first', false);
        $response->assertSee('--report-first-column-width: 118px;', false);
        $response->assertSeeText('TOTAL');

        $excelResponse = $this->actingAs($user)->get(route('laporan.rekap-operasional', [
            'tahun' => 2026,
            'pelabuhan_id' => $pelabuhan->id,
            'format' => 'excel',
        ]));

        $excelResponse->assertStatus(200);

        $excelFile = tempnam(sys_get_temp_dir(), 'rekap-operasional');
        file_put_contents($excelFile, $excelResponse->streamedContent());

        $spreadsheet = IOFactory::load($excelFile);
        $sheet = $spreadsheet->getActiveSheet();

        $this->assertSame('Jenis Pelayaran', $sheet->getCell('B5')->getValue());
        $this->assertSame('Kapal', $sheet->getCell('G5')->getValue());
        $this->assertSame('Bongkar', $sheet->getCell('I5')->getValue());
        $this->assertSame('Muat', $sheet->getCell('N5')->getValue());
        $this->assertSame('Penumpang', $sheet->getCell('S5')->getValue());

        @unlink($excelFile);
    }
}
