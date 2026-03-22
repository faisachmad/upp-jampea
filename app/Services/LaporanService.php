<?php

namespace App\Services;

use App\Models\Kunjungan;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class LaporanService
{
    private const MONTHS = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function buildDashboard(int $year, int $month): array
    {
        $allRecords = $this->baseQuery($year)->get();
        $currentMonthRecords = $allRecords->where('bulan', $month);

        $trend = collect(range(1, 12))->map(fn (int $monthNumber) => [
            'label' => self::MONTHS[$monthNumber],
            'value' => $allRecords->where('bulan', $monthNumber)->count(),
        ])->values();

        $distribution = [
            'PELRA' => $allRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'pelra'))->count(),
            'PERINTIS' => $allRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'perintis'))->count(),
            'FERRY' => $allRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'ferry'))->count(),
            'DN' => $allRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'dalam-negeri'))->count(),
            'LN' => $allRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'luar-negeri'))->count(),
        ];

        $status = collect(range(1, 12))->map(function (int $monthNumber) use ($allRecords) {
            $records = $allRecords->where('bulan', $monthNumber);

            return [
                'label' => self::MONTHS[$monthNumber],
                'count' => $records->count(),
                'total_gt' => (float) $records->sum(fn (Kunjungan $kunjungan) => (float) ($kunjungan->kapal->gt ?? 0)),
                'status' => $records->isEmpty() ? 'pending' : 'lengkap',
            ];
        })->values();

        return [
            'summary' => [
                'total_kunjungan' => $currentMonthRecords->count(),
                'total_gt' => (float) $currentMonthRecords->sum(fn (Kunjungan $kunjungan) => (float) ($kunjungan->kapal->gt ?? 0)),
                'total_penumpang' => (int) $currentMonthRecords->sum(fn (Kunjungan $kunjungan) => $kunjungan->penumpang_turun + $kunjungan->penumpang_naik + $kunjungan->lanjutan_penumpang),
                'total_muatan' => (float) $this->sumMuatan($currentMonthRecords) + (float) $currentMonthRecords->sum('lanjutan_ton'),
            ],
            'trend' => $trend,
            'distribution' => $distribution,
            'status' => $status,
            'latest' => $allRecords->sortByDesc('tgl_tiba')->take(5)->values()->map(function (Kunjungan $kunjungan) {
                return [
                    'kapal' => $kunjungan->kapal->nama ?? '-',
                    'pelabuhan' => $kunjungan->pelabuhan->nama ?? '-',
                    'tanggal' => optional($kunjungan->tgl_tiba)->format('d M Y') ?? '-',
                    'jenis' => strtoupper((string) ($kunjungan->jenisPelayaran->kode ?? '-')),
                ];
            })->all(),
        ];
    }

    public function buildMonthlyReport(string $reportKey, int $year, ?int $pelabuhanId = null): array
    {
        $records = $this->baseQuery($year, $pelabuhanId)
            ->get()
            ->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, $reportKey))
            ->values();

        $rows = collect(range(1, 12))->map(function (int $monthNumber) use ($records) {
            $monthRecords = $records->where('bulan', $monthNumber);

            return [
                'bulan' => self::MONTHS[$monthNumber],
                'jumlah_kapal' => $monthRecords->count(),
                'total_gt' => (float) $monthRecords->sum(fn (Kunjungan $kunjungan) => (float) ($kunjungan->kapal->gt ?? 0)),
                'bongkar_cargo' => $this->sumMuatanByType($monthRecords, 'BONGKAR', false),
                'bongkar_curah' => $this->sumMuatanByType($monthRecords, 'BONGKAR', true),
                'muat_cargo' => $this->sumMuatanByType($monthRecords, 'MUAT', false),
                'muat_curah' => $this->sumMuatanByType($monthRecords, 'MUAT', true),
                'hewan_turun' => (int) $this->sumHewanByType($monthRecords, 'BONGKAR'),
                'hewan_naik' => (int) $this->sumHewanByType($monthRecords, 'MUAT'),
                'penumpang_turun' => (int) $monthRecords->sum('penumpang_turun'),
                'penumpang_naik' => (int) $monthRecords->sum('penumpang_naik'),
                'penumpang_lanjutan' => (int) $monthRecords->sum('lanjutan_penumpang'),
                'motor_turun' => (int) $monthRecords->sum('motor_turun'),
                'motor_naik' => (int) $monthRecords->sum('motor_naik'),
                'mobil_turun' => (int) $monthRecords->sum('mobil_turun'),
                'mobil_naik' => (int) $monthRecords->sum('mobil_naik'),
            ];
        })->all();

        return [
            'key' => $reportKey,
            'title' => $this->reportTitle($reportKey),
            'type' => 'monthly',
            'headers' => $this->headersForMonthlyReport($reportKey),
            'rows' => $rows,
        ];
    }

    public function buildRekapSpb(int $year, ?int $pelabuhanId = null): array
    {
        $records = $this->baseQuery($year, $pelabuhanId)->get()
            ->filter(fn (Kunjungan $kunjungan) => filled($kunjungan->no_spb_tolak) || filled($kunjungan->no_spb_tiba))
            ->values();

        $rows = collect(range(1, 12))->map(function (int $monthNumber) use ($records) {
            $monthRecords = $records->where('bulan', $monthNumber);

            $ppk27 = $monthRecords->filter(fn (Kunjungan $kunjungan) => (float) ($kunjungan->kapal->gt ?? 0) > 500 && strtoupper((string) optional($kunjungan->kapal->jenisKapal)->kode) !== 'KLM')->count();
            $ppk29 = $monthRecords->filter(fn (Kunjungan $kunjungan) => (float) ($kunjungan->kapal->gt ?? 0) <= 500 && strtoupper((string) optional($kunjungan->kapal->jenisKapal)->kode) !== 'KLM')->count();
            $klm = $monthRecords->filter(fn (Kunjungan $kunjungan) => strtoupper((string) optional($kunjungan->kapal->jenisKapal)->kode) === 'KLM')->count();
            $kl = $monthRecords->filter(fn (Kunjungan $kunjungan) => strtoupper((string) optional($kunjungan->kapal->jenisKapal)->kode) === 'KL')->count();
            $pi = $monthRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'perintis'))->count();

            return [
                'bulan' => self::MONTHS[$monthNumber],
                'ppk_27' => $ppk27,
                'ppk_29' => $ppk29,
                'klm' => $klm,
                'kl' => $kl,
                'pi' => $pi,
                'rusak_batal' => 0,
                'jumlah' => $ppk27 + $ppk29 + $klm + $kl + $pi,
            ];
        })->all();

        return [
            'key' => 'rekap-spb',
            'title' => 'Rekap Pengeluaran SPB',
            'type' => 'spb',
            'headers' => ['Bulan', 'PPK.27', 'PPK.29', 'KLM', 'KL', 'PI', 'Rusak/Batal', 'Jumlah'],
            'rows' => $rows,
        ];
    }

    public function buildRekapOperasional(int $year, ?int $pelabuhanId = null): array
    {
        $records = $this->baseQuery($year, $pelabuhanId)->get();

        $rows = collect(range(1, 12))->map(function (int $monthNumber) use ($records) {
            $monthRecords = $records->where('bulan', $monthNumber);

            return [
                'bulan' => self::MONTHS[$monthNumber],
                'luar_negeri' => $monthRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'luar-negeri'))->count(),
                'dalam_negeri' => $monthRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'dalam-negeri'))->count(),
                'pelra' => $monthRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'pelra'))->count(),
                'perintis' => $monthRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'perintis'))->count(),
                'ferry' => $monthRecords->filter(fn (Kunjungan $kunjungan) => $this->reportKeyMatches($kunjungan, 'ferry'))->count(),
                'jumlah_kapal' => $monthRecords->count(),
                'total_gt' => (float) $monthRecords->sum(fn (Kunjungan $kunjungan) => (float) ($kunjungan->kapal->gt ?? 0)),
                'bongkar_cargo' => $this->sumMuatanByType($monthRecords, 'BONGKAR', false),
                'bongkar_curah' => $this->sumMuatanByType($monthRecords, 'BONGKAR', true),
                'hewan_turun' => (int) $this->sumHewanByType($monthRecords, 'BONGKAR'),
                'motor_turun' => (int) $monthRecords->sum('motor_turun'),
                'mobil_turun' => (int) $monthRecords->sum('mobil_turun'),
                'muat_cargo' => $this->sumMuatanByType($monthRecords, 'MUAT', false),
                'muat_curah' => $this->sumMuatanByType($monthRecords, 'MUAT', true),
                'hewan_naik' => (int) $this->sumHewanByType($monthRecords, 'MUAT'),
                'motor_naik' => (int) $monthRecords->sum('motor_naik'),
                'mobil_naik' => (int) $monthRecords->sum('mobil_naik'),
                'penumpang_turun' => (int) $monthRecords->sum('penumpang_turun'),
                'penumpang_naik' => (int) $monthRecords->sum('penumpang_naik'),
                'lanjutan_penumpang' => (int) $monthRecords->sum('lanjutan_penumpang'),
            ];
        })->all();

        return [
            'key' => 'rekap-operasional',
            'title' => 'Rekap Operasional',
            'type' => 'operasional',
            'headers' => ['Bulan', 'LN', 'DN', 'PELRA', 'Perintis', 'Ferry', 'Jml Kapal', 'GT', 'Bongkar Cargo', 'Bongkar Curah', 'Hewan Turun', 'Motor Turun', 'Mobil Turun', 'Muat Cargo', 'Muat Curah', 'Hewan Naik', 'Motor Naik', 'Mobil Naik', 'Penumpang Turun', 'Penumpang Naik', 'Penumpang Lanjutan'],
            'rows' => $rows,
        ];
    }

    private function baseQuery(int $year, ?int $pelabuhanId = null)
    {
        $query = Kunjungan::query()
            ->with(['kapal.jenisKapal', 'pelabuhan', 'jenisPelayaran', 'muatans'])
            ->where('tahun', $year);

        if ($pelabuhanId !== null) {
            $query->where('pelabuhan_id', $pelabuhanId);
        }

        return $query;
    }

    private function reportKeyMatches(Kunjungan $kunjungan, string $reportKey): bool
    {
        $kode = strtoupper((string) ($kunjungan->jenisPelayaran->kode ?? ''));

        return match ($reportKey) {
            'pelra' => $kode === 'PELRA',
            'perintis' => $kode === 'PERINTIS',
            'ferry' => str_contains($kode, 'FERRY'),
            'dalam-negeri' => $kode === 'DN',
            'luar-negeri' => $kode === 'LN',
            default => false,
        };
    }

    private function reportTitle(string $reportKey): string
    {
        return match ($reportKey) {
            'pelra' => 'Laporan PELRA',
            'perintis' => 'Laporan Perintis',
            'ferry' => 'Laporan Ferry',
            'dalam-negeri' => 'Laporan Angkutan Laut Dalam Negeri',
            'luar-negeri' => 'Laporan Angkutan Laut Luar Negeri',
            default => 'Laporan SAPOJAM',
        };
    }

    private function headersForMonthlyReport(string $reportKey): array
    {
        if ($reportKey === 'ferry') {
            return ['Bulan', 'Jumlah Kapal', 'Total GT', 'Motor Turun', 'Motor Naik', 'Mobil Turun', 'Mobil Naik', 'Penumpang Turun', 'Penumpang Naik', 'Penumpang Lanjutan'];
        }

        return ['Bulan', 'Jumlah Kapal', 'Total GT', 'Bongkar Cargo', 'Bongkar Curah', 'Muat Cargo', 'Muat Curah', 'Hewan Turun', 'Hewan Naik', 'Penumpang Turun', 'Penumpang Naik', 'Penumpang Lanjutan'];
    }

    private function sumMuatan(EloquentCollection|Collection $records): float
    {
        return (float) $records->sum(function (Kunjungan $kunjungan) {
            return $kunjungan->muatans->sum(fn ($muatan) => (float) ($muatan->ton_m3 ?? 0));
        });
    }

    private function sumMuatanByType(EloquentCollection|Collection $records, string $type, bool $curah): float
    {
        return (float) $records->sum(function (Kunjungan $kunjungan) use ($type, $curah) {
            return $kunjungan->muatans
                ->where('tipe', $type)
                ->filter(fn ($muatan) => $curah ? $this->isCurah($muatan->jenis_barang) : ! $this->isCurah($muatan->jenis_barang))
                ->sum(fn ($muatan) => (float) ($muatan->ton_m3 ?? 0));
        });
    }

    private function sumHewanByType(EloquentCollection|Collection $records, string $type): int
    {
        return (int) $records->sum(function (Kunjungan $kunjungan) use ($type) {
            return $kunjungan->muatans
                ->where('tipe', $type)
                ->sum(fn ($muatan) => (int) ($muatan->jumlah_hewan ?? 0));
        });
    }

    private function isCurah(?string $jenisBarang): bool
    {
        if ($jenisBarang === null) {
            return false;
        }

        return str_contains(strtoupper($jenisBarang), 'CURAH');
    }
}
