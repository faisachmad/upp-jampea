<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelTemplateExportService
{
    public function __construct(private readonly LaporanService $laporanService) {}

    public function downloadSingleReport(array $report, string $filename)
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $this->fillSheet($sheet, $report['title'], $report['headers'], $report['rows']);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function downloadDataDukungWorkbook(int $year, ?int $pelabuhanId = null)
    {
        $reports = [
            $this->laporanService->buildRekapSpb($year, $pelabuhanId),
            $this->laporanService->buildMonthlyReport('pelra', $year, $pelabuhanId),
            $this->laporanService->buildMonthlyReport('perintis', $year, $pelabuhanId),
            $this->laporanService->buildMonthlyReport('ferry', $year, $pelabuhanId),
            $this->laporanService->buildMonthlyReport('dalam-negeri', $year, $pelabuhanId),
            $this->laporanService->buildMonthlyReport('luar-negeri', $year, $pelabuhanId),
            $this->laporanService->buildRekapOperasional($year, $pelabuhanId),
        ];

        $spreadsheet = new Spreadsheet;

        foreach ($reports as $index => $report) {
            $sheet = $index === 0
                ? $spreadsheet->getActiveSheet()
                : $spreadsheet->createSheet($index);

            $this->fillSheet($sheet, $report['title'], $report['headers'], $report['rows']);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'data-dukung-'.$year.'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function fillSheet(Worksheet $sheet, string $title, array $headers, array $rows): void
    {
        $sheet->setTitle(substr($title, 0, 31));
        $sheet->setCellValue('A1', $title);
        $lastColumn = Coordinate::stringFromColumnIndex(max(count($headers), 1));
        $sheet->mergeCells('A1:'.$lastColumn.'1');
        $sheet->fromArray($headers, null, 'A3');

        $line = 4;
        foreach ($rows as $row) {
            $sheet->fromArray(array_values($row), null, 'A'.$line);
            $line++;
        }

        foreach (range(1, count($headers)) as $columnIndex) {
            $sheet->getColumnDimensionByColumn($columnIndex)->setAutoSize(true);
        }
    }
}
