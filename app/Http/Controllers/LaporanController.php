<?php

namespace App\Http\Controllers;

use App\Models\Pelabuhan;
use App\Services\ExcelTemplateExportService;
use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    public function __construct(
        private readonly LaporanService $laporanService,
        private readonly ExcelTemplateExportService $excelTemplateExportService,
    ) {}

    public function index(Request $request)
    {
        return redirect()->route('laporan.pelra', $request->query());
    }

    public function pelra(Request $request)
    {
        return $this->renderMonthlyReport($request, 'pelra');
    }

    public function perintis(Request $request)
    {
        return $this->renderMonthlyReport($request, 'perintis');
    }

    public function ferry(Request $request)
    {
        return $this->renderMonthlyReport($request, 'ferry');
    }

    public function dalamNegeri(Request $request)
    {
        return $this->renderMonthlyReport($request, 'dalam-negeri');
    }

    public function luarNegeri(Request $request)
    {
        return $this->renderMonthlyReport($request, 'luar-negeri');
    }

    public function rekapSpb(Request $request)
    {
        $filters = $this->filters($request);
        $report = $this->laporanService->buildRekapSpb($filters['year'], $filters['pelabuhan_id']);

        return $this->respondForReport($request, 'rekap-spb', $report, $filters);
    }

    public function rekapOperasional(Request $request)
    {
        $filters = $this->filters($request);
        $report = $this->laporanService->buildRekapOperasional($filters['year'], $filters['pelabuhan_id']);

        return $this->respondForReport($request, 'rekap-operasional', $report, $filters);
    }

    public function exportDataDukung(Request $request)
    {
        $filters = $this->filters($request);
        $selectedPelabuhan = $this->selectedPelabuhan($filters['pelabuhan_id']);

        return $this->excelTemplateExportService->downloadDataDukungWorkbook(
            $filters['year'],
            $filters['pelabuhan_id'],
            $selectedPelabuhan?->nama,
        );
    }

    private function renderMonthlyReport(Request $request, string $reportKey)
    {
        $filters = $this->filters($request);
        $report = $this->laporanService->buildMonthlyReport($reportKey, $filters['year'], $filters['pelabuhan_id']);

        return $this->respondForReport($request, $reportKey, $report, $filters);
    }

    private function respondForReport(Request $request, string $reportKey, array $report, array $filters)
    {
        $format = strtolower((string) $request->query('format', 'html'));
        $selectedPelabuhan = $this->selectedPelabuhan($filters['pelabuhan_id']);

        if ($format === 'excel') {
            return $this->excelTemplateExportService->downloadSingleReport(
                $report,
                Str::slug($report['title']).'-'.$filters['year'].'.xlsx',
                $selectedPelabuhan?->nama,
            );
        }

        if ($format === 'pdf') {
            return Pdf::loadView('laporan.pdf', [
                'report' => $report,
                'filters' => $filters,
                'selectedPelabuhan' => $selectedPelabuhan,
            ])->setPaper('a4', 'landscape')->download(Str::slug($report['title']).'-'.$filters['year'].'.pdf');
        }

        return view('laporan.show', [
            'report' => $report,
            'filters' => $filters,
            'pelabuhans' => Pelabuhan::internal()->active()->orderBy('nama')->get(),
            'selectedPelabuhan' => $selectedPelabuhan,
        ]);
    }

    private function selectedPelabuhan(?int $pelabuhanId): ?Pelabuhan
    {
        if ($pelabuhanId === null) {
            return null;
        }

        return Pelabuhan::query()->find($pelabuhanId);
    }

    private function filters(Request $request): array
    {
        return [
            'year' => (int) $request->integer('tahun', (int) now()->format('Y')),
            'pelabuhan_id' => $request->filled('pelabuhan_id') ? (int) $request->integer('pelabuhan_id') : null,
        ];
    }
}
