<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $report['title'] }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }
        p {
            margin: 0 0 12px;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #f8fafc;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>{{ $report['title'] }}</h1>
    <p>Tahun: {{ $filters['year'] }} @if(!empty($filters['pelabuhan_id'])) | Pelabuhan terfilter @endif</p>

    <table>
        <thead>
            <tr>
                @foreach($report['headers'] as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($report['rows'] as $row)
                <tr>
                    @foreach(array_values($row) as $cell)
                        <td>{{ is_numeric($cell) ? number_format((float) $cell, 2, ',', '.') : $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
