<?php

namespace Tests\Feature\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ImportExcelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_excel_page_is_accessible(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('import.excel.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Import Excel Lama');
    }

    public function test_legacy_kunjungan_excel_can_be_imported(): void
    {
        $user = User::factory()->create();
        $tempPath = tempnam(sys_get_temp_dir(), 'sapojam-import-');

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Lap. Kunjungan Kapal');
        $sheet->setCellValue('A9', 1);
        $sheet->setCellValue('B9', 'KM Test Import');
        $sheet->setCellValue('C9', 'KLM');
        $sheet->setCellValue('D9', 123.45);
        $sheet->setCellValue('E9', 'TS-001');
        $sheet->setCellValue('F9', 'Jampea');
        $sheet->setCellValue('G9', 'Kapten Import');
        $sheet->setCellValue('H9', 46097);
        $sheet->setCellValue('I9', 'Makassar');
        $sheet->setCellValue('J9', 'M');
        $sheet->setCellValue('K9', 46098);
        $sheet->setCellValue('L9', 'Bira');
        $sheet->setCellValue('M9', 'K');

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile(
            $tempPath,
            'Januari 2026.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($user)->post(route('import.excel.store'), [
            'files' => [$uploadedFile],
        ]);

        $response->assertRedirect(route('import.excel.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('kapals', ['nama' => 'KM Test Import']);
        $this->assertDatabaseHas('kunjungans', ['tahun' => 2026]);
    }
}
