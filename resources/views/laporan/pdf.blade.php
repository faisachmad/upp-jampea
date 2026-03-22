<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $report['title'] }}</title>
</head>
<body>
    @php
        use App\Support\LaporanTableLayout;

        $layout = LaporanTableLayout::resolve($report);
        $sheetTitle = LaporanTableLayout::sheetTitle($selectedPelabuhan?->nama ?? null);
        $theme = LaporanTableLayout::theme();
        $totals = $layout['type'] === 'structured'
            ? LaporanTableLayout::totals($report['rows'], $layout['total_keys'])
            : [];
    @endphp

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: {{ $theme['text'] }};
        }

        h1 {
            font-size: 17px;
            margin: 0 0 4px;
        }

        p {
            margin: 0 0 10px;
            color: {{ $theme['text_muted'] }};
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid {{ $theme['border'] }};
            padding: 5px;
            font-size: 9px;
            line-height: 1.2;
        }

        thead th {
            font-weight: bold;
            text-transform: uppercase;
        }

        .title-row {
            background: {{ $theme['surface_muted'] }};
            text-align: center;
        }

        .head-row {
            background: {{ $theme['surface_emphasis'] }};
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .total-row th,
        .total-row td {
            background: {{ $theme['surface_emphasis'] }};
            font-weight: bold;
        }
    </style>

    <h1>{{ $report['title'] }}</h1>
    <p>{{ $sheetTitle }} | Tahun {{ $filters['year'] }}</p>

    <table>
        @if($layout['type'] === 'structured')
            <thead>
                <tr>
                    <th colspan="{{ $layout['title_colspan'] }}" class="title-row">{{ $sheetTitle }}</th>
                </tr>
                @foreach($layout['header_rows'] as $headerRow)
                    <tr class="head-row">
                        @foreach($headerRow as $cell)
                            <th
                                @if(isset($cell['rowspan'])) rowspan="{{ $cell['rowspan'] }}" @endif
                                @if(isset($cell['colspan'])) colspan="{{ $cell['colspan'] }}" @endif
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
                            <td class="{{ ($column['align'] ?? 'center') === 'left' ? 'text-left' : 'text-center' }}">{{ LaporanTableLayout::displayValue($row, $column, $index) }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $layout['title_colspan'] }}" class="text-center">Belum ada data untuk filter yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
            @if(count($report['rows']) > 0)
                <tfoot>
                    <tr class="total-row">
                        <th colspan="{{ $layout['total_label_span'] }}" class="text-center">TOTAL</th>
                        @foreach($layout['total_keys'] as $totalKey)
                            <td class="text-center">{{ LaporanTableLayout::format($totals[$totalKey] ?? 0) }}</td>
                        @endforeach
                    </tr>
                </tfoot>
            @endif
        @else
            <thead>
                <tr>
                    <th colspan="{{ $layout['title_colspan'] }}" class="title-row">{{ $sheetTitle }}</th>
                </tr>
                <tr class="head-row">
                    @foreach($report['headers'] as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($report['rows'] as $row)
                    <tr>
                        @foreach(array_values($row) as $cell)
                            <td class="text-center">{{ LaporanTableLayout::format($cell) }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $layout['title_colspan'] }}" class="text-center">Belum ada data untuk filter yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        @endif
    </table>
</body>
</html>