<?php

namespace App\Http\Controllers;

use App\Services\LaporanService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly LaporanService $laporanService) {}

    public function index(Request $request)
    {
        $year = (int) $request->integer('tahun', (int) now()->format('Y'));
        $month = (int) $request->integer('bulan', (int) now()->format('n'));

        return view('dashboard', [
            'dashboard' => $this->laporanService->buildDashboard($year, $month),
            'selectedYear' => $year,
            'selectedMonth' => $month,
        ]);
    }
}
