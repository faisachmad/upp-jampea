<?php

namespace App\Support;

class LaporanTableLayout
{
    public static function resolve(array $report): array
    {
        return match ([$report['type'] ?? null, $report['key'] ?? null]) {
            ['monthly', 'pelra'] => self::pelra(),
            ['monthly', 'perintis'] => self::perintis(),
            ['monthly', 'ferry'] => self::ferry(),
            ['monthly', 'dalam-negeri'], ['monthly', 'luar-negeri'] => self::dalamDanLuarNegeri(),
            ['spb', 'rekap-spb'] => self::rekapSpb(),
            ['operasional', 'rekap-operasional'] => self::rekapOperasional(),
            default => self::generic($report),
        };
    }

    public static function sheetTitle(?string $selectedPelabuhanName): string
    {
        $pelabuhanName = $selectedPelabuhanName !== null && $selectedPelabuhanName !== ''
            ? strtoupper($selectedPelabuhanName)
            : 'SEMUA PELABUHAN';

        if (str_starts_with($pelabuhanName, 'PELABUHAN')) {
            return $pelabuhanName;
        }

        return 'PELABUHAN '.$pelabuhanName;
    }

    public static function theme(): array
    {
        return [
            'surface' => '#FFFFFF',
            'surface_muted' => '#F8FAFC',
            'surface_emphasis' => '#F3F4F6',
            'border' => '#D1D5DB',
            'text' => '#111827',
            'text_muted' => '#6B7280',
            'badge' => '#E2E8F0',
            'badge_text' => '#475569',
            'row_alt' => '#F9FAFB',
        ];
    }

    public static function totals(array $rows, array $keys): array
    {
        $totals = [];

        foreach ($keys as $key) {
            $totals[$key] = collect($rows)->sum($key);
        }

        return $totals;
    }

    public static function valueForColumn(array $row, array $column, int $index): mixed
    {
        if (($column['key'] ?? null) === 'no') {
            return $index + 1;
        }

        return $row[$column['key']] ?? null;
    }

    public static function displayValue(array $row, array $column, int $index): string
    {
        $value = self::valueForColumn($row, $column, $index);

        if (($column['uppercase'] ?? false) && is_string($value)) {
            $value = strtoupper($value);
        }

        return self::format($value);
    }

    public static function format(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        if (! is_numeric($value)) {
            return (string) $value;
        }

        $number = (float) $value;

        if (fmod($number, 1.0) === 0.0) {
            return number_format((int) $number, 0, ',', '.');
        }

        return rtrim(rtrim(number_format($number, 2, ',', '.'), '0'), ',');
    }

    private static function pelra(): array
    {
        return [
            'type' => 'structured',
            'title_colspan' => 13,
            'header_rows' => [
                [
                    ['label' => 'No', 'rowspan' => 3],
                    ['label' => 'Bulan', 'rowspan' => 3],
                    ['label' => 'Jumlah Kapal', 'rowspan' => 3],
                    ['label' => 'Isi Kotor', 'rowspan' => 3],
                    ['label' => 'Barang ( Ton / M3 )', 'colspan' => 4],
                    ['label' => 'Hewan', 'colspan' => 2],
                    ['label' => 'Penumpang', 'colspan' => 3],
                ],
                [
                    ['label' => 'Bongkar', 'colspan' => 2],
                    ['label' => 'Muat', 'colspan' => 2],
                    ['label' => 'Turun', 'rowspan' => 2],
                    ['label' => 'Naik', 'rowspan' => 2],
                    ['label' => 'Turun', 'rowspan' => 2],
                    ['label' => 'Naik', 'rowspan' => 2],
                    ['label' => 'Lanjutan', 'rowspan' => 2],
                ],
                [
                    ['label' => 'Cargo'],
                    ['label' => 'Curah'],
                    ['label' => 'Cargo'],
                    ['label' => 'Curah'],
                ],
            ],
            'body_columns' => [
                ['key' => 'no', 'align' => 'center'],
                ['key' => 'bulan', 'align' => 'left', 'uppercase' => true],
                ['key' => 'jumlah_kapal', 'align' => 'center'],
                ['key' => 'total_gt', 'align' => 'center'],
                ['key' => 'bongkar_cargo', 'align' => 'center'],
                ['key' => 'bongkar_curah', 'align' => 'center'],
                ['key' => 'muat_cargo', 'align' => 'center'],
                ['key' => 'muat_curah', 'align' => 'center'],
                ['key' => 'hewan_turun', 'align' => 'center'],
                ['key' => 'hewan_naik', 'align' => 'center'],
                ['key' => 'penumpang_turun', 'align' => 'center'],
                ['key' => 'penumpang_naik', 'align' => 'center'],
                ['key' => 'penumpang_lanjutan', 'align' => 'center'],
            ],
            'total_label_span' => 2,
            'total_keys' => [
                'jumlah_kapal',
                'total_gt',
                'bongkar_cargo',
                'bongkar_curah',
                'muat_cargo',
                'muat_curah',
                'hewan_turun',
                'hewan_naik',
                'penumpang_turun',
                'penumpang_naik',
                'penumpang_lanjutan',
            ],
        ];
    }

    private static function perintis(): array
    {
        return [
            'type' => 'structured',
            'title_colspan' => 13,
            'header_rows' => [
                [
                    ['label' => 'No', 'rowspan' => 3],
                    ['label' => 'Bulan', 'rowspan' => 3],
                    ['label' => 'Jumlah Kapal', 'rowspan' => 3],
                    ['label' => 'Isi Kotor', 'rowspan' => 3],
                    ['label' => 'Barang ( Ton / M3 )', 'colspan' => 4],
                    ['label' => 'Penumpang', 'colspan' => 3],
                    ['label' => 'Motor', 'colspan' => 2],
                ],
                [
                    ['label' => 'Bongkar', 'colspan' => 2],
                    ['label' => 'Muat', 'colspan' => 2],
                    ['label' => 'Turun', 'rowspan' => 2],
                    ['label' => 'Naik', 'rowspan' => 2],
                    ['label' => 'Lanjutan', 'rowspan' => 2],
                    ['label' => 'Turun', 'rowspan' => 2],
                    ['label' => 'Naik', 'rowspan' => 2],
                ],
                [
                    ['label' => 'Cargo'],
                    ['label' => 'Curah'],
                    ['label' => 'Cargo'],
                    ['label' => 'Curah'],
                ],
            ],
            'body_columns' => [
                ['key' => 'no', 'align' => 'center'],
                ['key' => 'bulan', 'align' => 'left', 'uppercase' => true],
                ['key' => 'jumlah_kapal', 'align' => 'center'],
                ['key' => 'total_gt', 'align' => 'center'],
                ['key' => 'bongkar_cargo', 'align' => 'center'],
                ['key' => 'bongkar_curah', 'align' => 'center'],
                ['key' => 'muat_cargo', 'align' => 'center'],
                ['key' => 'muat_curah', 'align' => 'center'],
                ['key' => 'penumpang_turun', 'align' => 'center'],
                ['key' => 'penumpang_naik', 'align' => 'center'],
                ['key' => 'penumpang_lanjutan', 'align' => 'center'],
                ['key' => 'motor_turun', 'align' => 'center'],
                ['key' => 'motor_naik', 'align' => 'center'],
            ],
            'total_label_span' => 2,
            'total_keys' => [
                'jumlah_kapal',
                'total_gt',
                'bongkar_cargo',
                'bongkar_curah',
                'muat_cargo',
                'muat_curah',
                'penumpang_turun',
                'penumpang_naik',
                'penumpang_lanjutan',
                'motor_turun',
                'motor_naik',
            ],
        ];
    }

    private static function ferry(): array
    {
        return [
            'type' => 'structured',
            'title_colspan' => 11,
            'header_rows' => [
                [
                    ['label' => 'No', 'rowspan' => 3],
                    ['label' => 'Bulan', 'rowspan' => 3],
                    ['label' => 'Jumlah Kapal', 'rowspan' => 3],
                    ['label' => 'Isi Kotor', 'rowspan' => 3],
                    ['label' => 'Kendaraan', 'colspan' => 4],
                    ['label' => 'Penumpang', 'colspan' => 3],
                ],
                [
                    ['label' => 'Turun', 'colspan' => 2],
                    ['label' => 'Naik', 'colspan' => 2],
                    ['label' => 'Turun', 'rowspan' => 2],
                    ['label' => 'Naik', 'rowspan' => 2],
                    ['label' => 'Lanjutan', 'rowspan' => 2],
                ],
                [
                    ['label' => 'Mobil'],
                    ['label' => 'Motor'],
                    ['label' => 'Mobil'],
                    ['label' => 'Motor'],
                ],
            ],
            'body_columns' => [
                ['key' => 'no', 'align' => 'center'],
                ['key' => 'bulan', 'align' => 'left', 'uppercase' => true],
                ['key' => 'jumlah_kapal', 'align' => 'center'],
                ['key' => 'total_gt', 'align' => 'center'],
                ['key' => 'mobil_turun', 'align' => 'center'],
                ['key' => 'motor_turun', 'align' => 'center'],
                ['key' => 'mobil_naik', 'align' => 'center'],
                ['key' => 'motor_naik', 'align' => 'center'],
                ['key' => 'penumpang_turun', 'align' => 'center'],
                ['key' => 'penumpang_naik', 'align' => 'center'],
                ['key' => 'penumpang_lanjutan', 'align' => 'center'],
            ],
            'total_label_span' => 2,
            'total_keys' => [
                'jumlah_kapal',
                'total_gt',
                'mobil_turun',
                'motor_turun',
                'mobil_naik',
                'motor_naik',
                'penumpang_turun',
                'penumpang_naik',
                'penumpang_lanjutan',
            ],
        ];
    }

    private static function dalamDanLuarNegeri(): array
    {
        return [
            'type' => 'structured',
            'title_colspan' => 11,
            'header_rows' => [
                [
                    ['label' => 'No', 'rowspan' => 3],
                    ['label' => 'Bulan', 'rowspan' => 3],
                    ['label' => 'Jumlah Kapal', 'rowspan' => 3],
                    ['label' => 'Isi Kotor', 'rowspan' => 3],
                    ['label' => 'Barang ( Ton / M3 )', 'colspan' => 4],
                    ['label' => 'Penumpang', 'colspan' => 3],
                ],
                [
                    ['label' => 'Bongkar', 'colspan' => 2],
                    ['label' => 'Muat', 'colspan' => 2],
                    ['label' => 'Turun', 'rowspan' => 2],
                    ['label' => 'Naik', 'rowspan' => 2],
                    ['label' => 'Lanjutan', 'rowspan' => 2],
                ],
                [
                    ['label' => 'Cargo'],
                    ['label' => 'Curah'],
                    ['label' => 'Cargo'],
                    ['label' => 'Curah'],
                ],
            ],
            'body_columns' => [
                ['key' => 'no', 'align' => 'center'],
                ['key' => 'bulan', 'align' => 'left', 'uppercase' => true],
                ['key' => 'jumlah_kapal', 'align' => 'center'],
                ['key' => 'total_gt', 'align' => 'center'],
                ['key' => 'bongkar_cargo', 'align' => 'center'],
                ['key' => 'bongkar_curah', 'align' => 'center'],
                ['key' => 'muat_cargo', 'align' => 'center'],
                ['key' => 'muat_curah', 'align' => 'center'],
                ['key' => 'penumpang_turun', 'align' => 'center'],
                ['key' => 'penumpang_naik', 'align' => 'center'],
                ['key' => 'penumpang_lanjutan', 'align' => 'center'],
            ],
            'total_label_span' => 2,
            'total_keys' => [
                'jumlah_kapal',
                'total_gt',
                'bongkar_cargo',
                'bongkar_curah',
                'muat_cargo',
                'muat_curah',
                'penumpang_turun',
                'penumpang_naik',
                'penumpang_lanjutan',
            ],
        ];
    }

    private static function rekapSpb(): array
    {
        return [
            'type' => 'structured',
            'title_colspan' => 9,
            'min_width' => '940px',
            'header_rows' => [
                [
                    ['label' => 'No', 'rowspan' => 2],
                    ['label' => 'Bulan', 'rowspan' => 2],
                    ['label' => 'Jenis / Ukuran Kapal', 'colspan' => 5],
                    ['label' => 'Rusak / Batal', 'rowspan' => 2],
                    ['label' => 'Jumlah', 'rowspan' => 2],
                ],
                [
                    ['label' => 'PPK.27'],
                    ['label' => 'PPK.29'],
                    ['label' => 'KLM'],
                    ['label' => 'KL'],
                    ['label' => 'PI'],
                ],
            ],
            'body_columns' => [
                ['key' => 'no', 'align' => 'center'],
                ['key' => 'bulan', 'align' => 'left', 'uppercase' => true],
                ['key' => 'ppk_27', 'align' => 'center'],
                ['key' => 'ppk_29', 'align' => 'center'],
                ['key' => 'klm', 'align' => 'center'],
                ['key' => 'kl', 'align' => 'center'],
                ['key' => 'pi', 'align' => 'center'],
                ['key' => 'rusak_batal', 'align' => 'center'],
                ['key' => 'jumlah', 'align' => 'center'],
            ],
            'total_label_span' => 2,
            'total_keys' => ['ppk_27', 'ppk_29', 'klm', 'kl', 'pi', 'rusak_batal', 'jumlah'],
        ];
    }

    private static function rekapOperasional(): array
    {
        return [
            'type' => 'structured',
            'title_colspan' => 22,
            'min_width' => '1480px',
            'dense' => true,
            'sticky_first_column' => true,
            'first_column_width' => '118px',
            'header_rows' => [
                [
                    ['label' => 'Bulan', 'rowspan' => 2],
                    ['label' => 'Jenis Pelayaran', 'colspan' => 5],
                    ['label' => 'Kapal', 'colspan' => 2],
                    ['label' => 'Bongkar', 'colspan' => 5],
                    ['label' => 'Muat', 'colspan' => 5],
                    ['label' => 'Penumpang', 'colspan' => 3],
                ],
                [
                    ['label' => 'LN'],
                    ['label' => 'DN'],
                    ['label' => 'PELRA'],
                    ['label' => 'Perintis'],
                    ['label' => 'Ferry'],
                    ['label' => 'Jml Kapal'],
                    ['label' => 'GT'],
                    ['label' => 'Cargo'],
                    ['label' => 'Curah'],
                    ['label' => 'Hewan'],
                    ['label' => 'Motor'],
                    ['label' => 'Mobil'],
                    ['label' => 'Cargo'],
                    ['label' => 'Curah'],
                    ['label' => 'Hewan'],
                    ['label' => 'Motor'],
                    ['label' => 'Mobil'],
                    ['label' => 'Turun'],
                    ['label' => 'Naik'],
                    ['label' => 'Lanjutan'],
                ],
            ],
            'body_columns' => [
                ['key' => 'bulan', 'align' => 'left', 'uppercase' => true],
                ['key' => 'luar_negeri', 'align' => 'center'],
                ['key' => 'dalam_negeri', 'align' => 'center'],
                ['key' => 'pelra', 'align' => 'center'],
                ['key' => 'perintis', 'align' => 'center'],
                ['key' => 'ferry', 'align' => 'center'],
                ['key' => 'jumlah_kapal', 'align' => 'center'],
                ['key' => 'total_gt', 'align' => 'center'],
                ['key' => 'bongkar_cargo', 'align' => 'center'],
                ['key' => 'bongkar_curah', 'align' => 'center'],
                ['key' => 'hewan_turun', 'align' => 'center'],
                ['key' => 'motor_turun', 'align' => 'center'],
                ['key' => 'mobil_turun', 'align' => 'center'],
                ['key' => 'muat_cargo', 'align' => 'center'],
                ['key' => 'muat_curah', 'align' => 'center'],
                ['key' => 'hewan_naik', 'align' => 'center'],
                ['key' => 'motor_naik', 'align' => 'center'],
                ['key' => 'mobil_naik', 'align' => 'center'],
                ['key' => 'penumpang_turun', 'align' => 'center'],
                ['key' => 'penumpang_naik', 'align' => 'center'],
                ['key' => 'lanjutan_penumpang', 'align' => 'center'],
            ],
            'total_label_span' => 1,
            'total_keys' => [
                'luar_negeri',
                'dalam_negeri',
                'pelra',
                'perintis',
                'ferry',
                'jumlah_kapal',
                'total_gt',
                'bongkar_cargo',
                'bongkar_curah',
                'hewan_turun',
                'motor_turun',
                'mobil_turun',
                'muat_cargo',
                'muat_curah',
                'hewan_naik',
                'motor_naik',
                'mobil_naik',
                'penumpang_turun',
                'penumpang_naik',
                'lanjutan_penumpang',
            ],
        ];
    }

    private static function generic(array $report): array
    {
        return [
            'type' => 'generic',
            'headers' => $report['headers'] ?? [],
            'title_colspan' => max(count($report['headers'] ?? []), 1),
        ];
    }
}
