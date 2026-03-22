<?php

namespace App\Services;

use App\Support\LaporanTableLayout;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelTemplateExportService
{
    public function __construct(private readonly LaporanService $laporanService) {}

    public function downloadSingleReport(array $report, string $filename, ?string $selectedPelabuhanName = null)
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $this->fillSheet($sheet, $report, $selectedPelabuhanName);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function downloadDataDukungWorkbook(int $year, ?int $pelabuhanId = null, ?string $selectedPelabuhanName = null)
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

            $this->fillSheet($sheet, $report, $selectedPelabuhanName);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'data-dukung-'.$year.'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function fillSheet(Worksheet $sheet, array $report, ?string $selectedPelabuhanName = null): void
    {
        $layout = LaporanTableLayout::resolve($report);
        $sheetTitle = LaporanTableLayout::sheetTitle($selectedPelabuhanName);
        $columnCount = $layout['type'] === 'structured'
            ? $layout['title_colspan']
            : max(count($report['headers']), 1);
        $lastColumn = Coordinate::stringFromColumnIndex($columnCount);

        $sheet->setTitle(substr($report['title'], 0, 31));
        $sheet->setCellValue('A1', $report['title']);
        $sheet->mergeCells('A1:'.$lastColumn.'1');
        $sheet->setCellValue('A2', $sheetTitle);
        $sheet->mergeCells('A2:'.$lastColumn.'2');

        $sheet->getStyle('A1:'.$lastColumn.'1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle('A2:'.$lastColumn.'2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '374151']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        if ($layout['type'] === 'structured') {
            $this->fillStructuredSheet($sheet, $report, $layout, $sheetTitle, $lastColumn);
        } else {
            $this->fillGenericSheet($sheet, $report, $sheetTitle, $lastColumn);
        }

        foreach (range(1, $columnCount) as $columnIndex) {
            $sheet->getColumnDimensionByColumn($columnIndex)->setAutoSize(true);
        }
    }

    private function fillStructuredSheet(Worksheet $sheet, array $report, array $layout, string $sheetTitle, string $lastColumn): void
    {
        $sheet->setCellValue('A4', $sheetTitle);
        $sheet->mergeCells('A4:'.$lastColumn.'4');
        $sheet->getStyle('A4:'.$lastColumn.'4')->applyFromArray($this->titleRowStyle());

        $rowNumber = 5;
        $occupied = [];
        foreach ($layout['header_rows'] as $headerRow) {
            $columnIndex = 1;

            foreach ($headerRow as $cell) {
                while (isset($occupied[$rowNumber][$columnIndex])) {
                    $columnIndex++;
                }

                $startColumn = Coordinate::stringFromColumnIndex($columnIndex);
                $endColumnIndex = $columnIndex + (($cell['colspan'] ?? 1) - 1);
                $endColumn = Coordinate::stringFromColumnIndex($endColumnIndex);
                $endRow = $rowNumber + (($cell['rowspan'] ?? 1) - 1);
                $range = $startColumn.$rowNumber.':'.$endColumn.$endRow;

                for ($rowOffset = $rowNumber; $rowOffset <= $endRow; $rowOffset++) {
                    for ($columnOffset = $columnIndex; $columnOffset <= $endColumnIndex; $columnOffset++) {
                        $occupied[$rowOffset][$columnOffset] = true;
                    }
                }

                $sheet->setCellValue($startColumn.$rowNumber, $cell['label']);

                if ($startColumn !== $endColumn || $rowNumber !== $endRow) {
                    $sheet->mergeCells($range);
                }

                $sheet->getStyle($range)->applyFromArray($this->headerRowStyle());
                $columnIndex = $endColumnIndex + 1;
            }

            $rowNumber++;
        }

        $bodyStartRow = $rowNumber;

        if (count($report['rows']) === 0) {
            $sheet->setCellValue('A'.$bodyStartRow, 'Belum ada data untuk filter yang dipilih.');
            $sheet->mergeCells('A'.$bodyStartRow.':'.$lastColumn.$bodyStartRow);
            $sheet->getStyle('A'.$bodyStartRow.':'.$lastColumn.$bodyStartRow)->applyFromArray($this->bodyCellStyle(false));

            return;
        }

        foreach ($report['rows'] as $index => $row) {
            foreach ($layout['body_columns'] as $columnOffset => $column) {
                $cellCoordinate = Coordinate::stringFromColumnIndex($columnOffset + 1).($bodyStartRow + $index);
                $sheet->setCellValue($cellCoordinate, LaporanTableLayout::displayValue($row, $column, $index));
                $sheet->getStyle($cellCoordinate)->applyFromArray($this->bodyCellStyle(($column['align'] ?? 'center') === 'left'));
            }
        }

        $totalRow = $bodyStartRow + count($report['rows']);
        $labelEndColumn = Coordinate::stringFromColumnIndex($layout['total_label_span']);
        $sheet->setCellValue('A'.$totalRow, 'TOTAL');

        if ($layout['total_label_span'] > 1) {
            $sheet->mergeCells('A'.$totalRow.':'.$labelEndColumn.$totalRow);
        }

        $sheet->getStyle('A'.$totalRow.':'.$labelEndColumn.$totalRow)->applyFromArray($this->totalRowStyle());

        $totals = LaporanTableLayout::totals($report['rows'], $layout['total_keys']);
        foreach ($layout['total_keys'] as $index => $totalKey) {
            $cellCoordinate = Coordinate::stringFromColumnIndex($layout['total_label_span'] + $index + 1).$totalRow;
            $sheet->setCellValue($cellCoordinate, LaporanTableLayout::format($totals[$totalKey] ?? 0));
            $sheet->getStyle($cellCoordinate)->applyFromArray($this->totalRowStyle());
        }
    }

    private function fillGenericSheet(Worksheet $sheet, array $report, string $sheetTitle, string $lastColumn): void
    {
        $sheet->setCellValue('A4', $sheetTitle);
        $sheet->mergeCells('A4:'.$lastColumn.'4');
        $sheet->getStyle('A4:'.$lastColumn.'4')->applyFromArray($this->titleRowStyle());
        $sheet->fromArray($report['headers'], null, 'A5');
        $sheet->getStyle('A5:'.$lastColumn.'5')->applyFromArray($this->headerRowStyle());

        if (count($report['rows']) === 0) {
            $sheet->setCellValue('A6', 'Belum ada data untuk filter yang dipilih.');
            $sheet->mergeCells('A6:'.$lastColumn.'6');
            $sheet->getStyle('A6:'.$lastColumn.'6')->applyFromArray($this->bodyCellStyle(false));

            return;
        }

        $line = 6;
        foreach ($report['rows'] as $row) {
            foreach (array_values($row) as $index => $value) {
                $cellCoordinate = Coordinate::stringFromColumnIndex($index + 1).$line;
                $sheet->setCellValue($cellCoordinate, LaporanTableLayout::format($value));
            }

            $sheet->getStyle('A'.$line.':'.$lastColumn.$line)->applyFromArray($this->bodyCellStyle(false));
            $line++;
        }
    }

    private function titleRowStyle(): array
    {
        $theme = LaporanTableLayout::theme();

        return [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => ltrim($theme['surface_muted'], '#')],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => ltrim($theme['border'], '#')]],
            ],
        ];
    }

    private function headerRowStyle(): array
    {
        $theme = LaporanTableLayout::theme();

        return [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => ltrim($theme['surface_emphasis'], '#')],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => ltrim($theme['border'], '#')]],
            ],
        ];
    }

    private function bodyCellStyle(bool $leftAligned): array
    {
        $theme = LaporanTableLayout::theme();

        return [
            'alignment' => [
                'horizontal' => $leftAligned ? Alignment::HORIZONTAL_LEFT : Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => ltrim($theme['border'], '#')]],
            ],
        ];
    }

    private function totalRowStyle(): array
    {
        $theme = LaporanTableLayout::theme();

        return [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => ltrim($theme['surface_emphasis'], '#')],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => ltrim($theme['border'], '#')]],
            ],
        ];
    }
}
