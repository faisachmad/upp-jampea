@extends('layouts.app')

@section('title', $report['title'])

@section('content')
@php
    use App\Support\LaporanTableLayout;

    $reportLinks = [
        'PELRA' => route('laporan.pelra', $filters),
        'Perintis' => route('laporan.perintis', $filters),
        'Ferry' => route('laporan.ferry', $filters),
        'Dalam Negeri' => route('laporan.dalam-negeri', $filters),
        'Luar Negeri' => route('laporan.luar-negeri', $filters),
        'Rekap SPB' => route('laporan.rekap-spb', $filters),
        'Rekap Operasional' => route('laporan.rekap-operasional', $filters),
    ];

    $sheetTitle = LaporanTableLayout::sheetTitle($selectedPelabuhan?->nama);
    $layout = LaporanTableLayout::resolve($report);
    $theme = LaporanTableLayout::theme();
    $totals = $layout['type'] === 'structured'
        ? LaporanTableLayout::totals($report['rows'], $layout['total_keys'])
        : [];
    $reportMinWidth = $layout['min_width'] ?? '980px';
    $isDenseTable = (bool) ($layout['dense'] ?? false);
    $hasStickyFirstColumn = (bool) ($layout['sticky_first_column'] ?? false);
    $firstColumnWidth = $layout['first_column_width'] ?? '96px';
    $currentRoute = request()->route()?->getName();
    $resetUrl = $currentRoute ? route($currentRoute) : url()->current();
    $renderCellValue = static fn (array $row, array $column, int $index): string => LaporanTableLayout::displayValue($row, $column, $index);
@endphp

@push('styles')
    <style>
        .report-shell {
            background: {{ $theme['surface'] }};
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        .report-shell__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            background: {{ $theme['surface_muted'] }};
        }

        .report-shell__badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            background: {{ $theme['badge'] }};
            padding: 0.25rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: {{ $theme['badge_text'] }};
            text-transform: uppercase;
        }

        .report-sheet {
            width: 100%;
            min-width: var(--report-min-width);
            border-collapse: collapse;
            table-layout: fixed;
            background: {{ $theme['surface'] }};
        }

        .report-sheet th,
        .report-sheet td {
            border: 1px solid {{ $theme['border'] }};
            padding: 0.45rem 0.5rem;
            font-size: 0.72rem;
            line-height: 1.2;
        }

        .report-sheet thead th {
            font-weight: 700;
            text-transform: uppercase;
        }

        .report-sheet-title {
            background: {{ $theme['surface_muted'] }};
            color: {{ $theme['text'] }};
            font-size: 0.8rem;
            letter-spacing: 0.04em;
        }

        .report-sheet-head {
            background: {{ $theme['surface_emphasis'] }};
            color: {{ $theme['text'] }};
        }

        .report-sheet tbody tr:nth-child(even) {
            background: {{ $theme['row_alt'] }};
        }

        .report-sheet-total th,
        .report-sheet-total td {
            background: {{ $theme['surface_emphasis'] }};
            color: {{ $theme['text'] }};
            font-weight: 700;
        }

        .report-sheet-label {
            text-align: left;
            white-space: nowrap;
        }

        .report-sheet-number {
            text-align: center;
        }

        .report-sheet--dense th,
        .report-sheet--dense td {
            padding: 0.35rem 0.3rem;
            font-size: 0.68rem;
            line-height: 1.15;
        }

        .report-sheet--dense .report-sheet-label {
            white-space: normal;
        }

        .report-sheet--sticky-first tbody tr > *:first-child,
        .report-sheet--sticky-first tfoot tr > *:first-child {
            position: sticky;
            left: 0;
            z-index: 1;
            min-width: var(--report-first-column-width);
            width: var(--report-first-column-width);
            box-shadow: 1px 0 0 {{ $theme['border'] }};
        }

        .report-sheet--sticky-first tbody tr > *:first-child {
            background: {{ $theme['surface'] }};
            z-index: 2;
        }

        .report-sheet--sticky-first tbody tr:nth-child(even) > *:first-child {
            background: {{ $theme['row_alt'] }};
        }

        .report-sheet--sticky-first tfoot tr > *:first-child {
            background: {{ $theme['surface_emphasis'] }};
            z-index: 2;
        }
    </style>
@endpush

<div class="space-y-6">
    <div class="bg-white p-4 lg:p-5 rounded-xl shadow-sm border border-gray-100 space-y-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $report['title'] }}</h1>
                <p class="mt-1 text-sm text-gray-500">Laporan operasional dengan tabel yang seragam dengan tampilan master data aplikasi.</p>
            </div>
            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ request()->fullUrlWithQuery(['format' => 'pdf']) }}" data-download-request="true" class="px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-md border border-red-200 hover:bg-red-100 transition-all">Unduh PDF</a>
                <a href="{{ request()->fullUrlWithQuery(['format' => 'excel']) }}" data-download-request="true" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-md border border-emerald-200 hover:bg-emerald-100 transition-all">Unduh Excel</a>
                <a href="{{ route('laporan.export-excel', $filters) }}" data-download-request="true" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-all">Workbook Data Dukung</a>
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-[1.1fr_1fr_0.7fr_auto] gap-3">
            <div class="w-full">
                <select onchange="window.location=this.value" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @foreach($reportLinks as $label => $url)
                        <option value="{{ $url }}" {{ request()->fullUrl() === $url ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <select name="pelabuhan_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">Semua Pelabuhan</option>
                    @foreach($pelabuhans as $pelabuhan)
                        <option value="{{ $pelabuhan->id }}" @selected(($filters['pelabuhan_id'] ?? null) == $pelabuhan->id)>{{ $pelabuhan->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <select name="tahun" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @for($year = now()->year; $year >= 2020; $year--)
                        <option value="{{ $year }}" @selected($filters['year'] == $year)>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-full sm:col-span-2 xl:col-span-1 flex justify-end">
                <div class="inline-flex shadow-sm rounded-md w-full sm:w-auto" role="group">
                    <button type="submit" class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-l-md hover:bg-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500 transition-all">
                        Tampilkan
                    </button>
                    <a href="{{ $resetUrl }}" class="flex-1 sm:flex-none flex items-center justify-center px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-10 focus:ring-2 focus:ring-blue-500 transition-all">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="report-shell">
        <div class="report-shell__meta">
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ $sheetTitle }}</p>
                <p class="text-xs text-gray-500">Periode {{ $filters['year'] }} • Tampilan laporan detail</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="report-shell__badge">{{ strtoupper(str_replace('-', ' ', $report['key'])) }}</span>
                <span class="report-shell__badge">{{ $filters['year'] }}</span>
            </div>
        </div>

        <div class="overflow-x-auto p-4">
            <table class="report-sheet {{ $isDenseTable ? 'report-sheet--dense' : '' }} {{ $hasStickyFirstColumn ? 'report-sheet--sticky-first' : '' }} text-slate-900" style="--report-min-width: {{ $reportMinWidth }}; --report-first-column-width: {{ $firstColumnWidth }};">
                @if($layout['type'] === 'structured')
                    <thead>
                        <tr>
                            <th colspan="{{ $layout['title_colspan'] }}" class="report-sheet-title report-sheet-number">{{ $sheetTitle }}</th>
                        </tr>
                        @foreach($layout['header_rows'] as $headerRow)
                            <tr class="report-sheet-head">
                                @foreach($headerRow as $cell)
                                    <th
                                        @if(isset($cell['rowspan'])) rowspan="{{ $cell['rowspan'] }}" @endif
                                        @if(isset($cell['colspan'])) colspan="{{ $cell['colspan'] }}" @endif
                                        class="report-sheet-number"
                                    >
                                        {{ $cell['label'] }}
                                    </th>
                                @endforeach
                            </tr>
                        @endforeach
                    </thead>
                    <tbody>
                        @forelse($report['rows'] as $index => $row)
                            <tr>
                                @foreach($layout['body_columns'] as $column)
                                    <td class="{{ ($column['align'] ?? 'center') === 'left' ? 'report-sheet-label' : 'report-sheet-number' }}">{{ $renderCellValue($row, $column, $index) }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $layout['title_colspan'] }}" class="px-4 py-8 text-center text-slate-500">Belum ada data untuk filter yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($report['rows']) > 0)
                        <tfoot>
                            <tr class="report-sheet-total">
                                <th colspan="{{ $layout['total_label_span'] }}" class="report-sheet-number">TOTAL</th>
                                @foreach($layout['total_keys'] as $totalKey)
                                    <td class="report-sheet-number">{{ LaporanTableLayout::format($totals[$totalKey] ?? 0) }}</td>
                                @endforeach
                            </tr>
                        </tfoot>
                    @endif
                @else
                    <thead>
                        <tr>
                            <th colspan="{{ $layout['title_colspan'] }}" class="report-sheet-title report-sheet-number">{{ $sheetTitle }}</th>
                        </tr>
                        <tr class="report-sheet-head">
                            @foreach($report['headers'] as $header)
                                <th class="report-sheet-number">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report['rows'] as $row)
                            <tr>
                                @foreach(array_values($row) as $cell)
                                    <td class="report-sheet-number">{{ LaporanTableLayout::format($cell) }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $layout['title_colspan'] }}" class="px-4 py-8 text-center text-slate-500">Belum ada data untuk filter yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection