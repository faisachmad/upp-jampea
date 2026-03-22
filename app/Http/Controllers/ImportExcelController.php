<?php

namespace App\Http\Controllers;

use App\Services\ExcelImportService;
use Illuminate\Http\Request;

class ImportExcelController extends Controller
{
    public function __construct(private readonly ExcelImportService $excelImportService) {}

    public function index()
    {
        return view('import.excel');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|mimes:xlsx,xls',
        ]);

        $summary = $this->excelImportService->importUploadedFiles($validated['files']);

        return redirect()->route('import.excel.index')
            ->with('success', 'Import Excel selesai diproses.')
            ->with('import_summary', $summary);
    }
}
