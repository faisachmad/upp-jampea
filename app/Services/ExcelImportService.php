<?php

namespace App\Services;

use App\Models\BarangB3;
use App\Models\Bendera;
use App\Models\JenisKapal;
use App\Models\JenisPelayaran;
use App\Models\Kapal;
use App\Models\Kunjungan;
use App\Models\KunjunganB3;
use App\Models\KunjunganMuatan;
use App\Models\Nakhoda;
use App\Models\Pelabuhan;
use App\Models\TipePelabuhan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelImportService
{
    public function importUploadedFiles(array $files): array
    {
        $summary = [
            'processed_files' => [],
            'created' => 0,
            'updated' => 0,
            'muatan_rows' => 0,
            'b3_rows' => 0,
            'warnings' => [],
        ];

        DB::transaction(function () use ($files, &$summary) {
            foreach ($files as $file) {
                $result = $this->importFile($file);
                $summary['processed_files'][] = $file->getClientOriginalName();
                $summary['created'] += $result['created'];
                $summary['updated'] += $result['updated'];
                $summary['muatan_rows'] += $result['muatan_rows'];
                $summary['b3_rows'] += $result['b3_rows'];
                $summary['warnings'] = [...$summary['warnings'], ...$result['warnings']];
            }
        });

        return $summary;
    }

    private function importFile(UploadedFile $file): array
    {
        $name = strtoupper($file->getClientOriginalName());

        return match (true) {
            str_contains($name, 'OPERASIONAL') => $this->importOperasional($file),
            str_contains($name, 'SPB') => $this->importSpb($file),
            str_contains($name, ' BB') || str_contains($name, 'BARANG') => $this->importB3($file),
            str_contains($name, 'DATA DUKUNG') => [
                'created' => 0,
                'updated' => 0,
                'muatan_rows' => 0,
                'b3_rows' => 0,
                'warnings' => ['File data dukung dilewati karena merupakan template laporan, bukan sumber transaksi.'],
            ],
            default => $this->importKunjungan($file),
        };
    }

    private function importKunjungan(UploadedFile $file): array
    {
        $sheet = IOFactory::load($file->getRealPath())->getSheetByName('Lap. Kunjungan Kapal');
        $rows = $sheet?->toArray(null, false, false, false) ?? [];
        $currentPelabuhan = $this->firstOrCreatePelabuhan('UPP Jampea', 'UPP');
        $result = ['created' => 0, 'updated' => 0, 'muatan_rows' => 0, 'b3_rows' => 0, 'warnings' => []];

        foreach ($rows as $index => $row) {
            if ($index < 8 || ! is_numeric($row[0] ?? null)) {
                continue;
            }

            $kapal = $this->firstOrCreateKapal(trim((string) ($row[1] ?? 'Kapal Tidak Dikenal')), [
                'gt' => $row[3] ?: null,
                'tanda_selar' => $row[4] ?: null,
                'tempat_kedudukan' => $row[5] ?: null,
                'jenis_kapal_id' => $this->firstOrCreateJenisKapal((string) ($row[2] ?? 'KLM'))->id,
            ]);
            $nakhoda = $this->firstOrCreateNakhoda(trim((string) ($row[6] ?? 'Nakhoda Tidak Dikenal')), $kapal->id);
            $asal = $this->firstOrCreatePelabuhan(trim((string) ($row[8] ?? 'Pelabuhan Asal')), 'LUAR');
            $tujuan = $this->firstOrCreatePelabuhan(trim((string) ($row[11] ?? 'Pelabuhan Tujuan')), 'LUAR');
            $jenisPelayaran = $this->firstOrCreateJenisPelayaran(strtoupper((string) ($row[2] ?? 'PELRA')) === 'KLM' ? 'PELRA' : 'DN');

            $attributes = [
                'pelabuhan_id' => $currentPelabuhan->id,
                'kapal_id' => $kapal->id,
                'tgl_tiba' => $this->excelDate($row[7]),
                'tgl_berangkat' => $this->excelDate($row[10]),
            ];

            $kunjungan = Kunjungan::firstOrNew($attributes);
            $isNew = ! $kunjungan->exists;
            $kunjungan->fill([
                'jenis_pelayaran_id' => $jenisPelayaran->id,
                'nakhoda_id' => $nakhoda->id,
                'bulan' => (int) date('n', strtotime((string) $attributes['tgl_tiba'])),
                'tahun' => (int) date('Y', strtotime((string) $attributes['tgl_tiba'])),
                'jam_tiba' => '00:00:00',
                'pelabuhan_asal_id' => $asal->id,
                'status_muatan_tiba' => $this->normalizeStatus($row[9] ?? null),
                'jam_berangkat' => '00:00:00',
                'pelabuhan_tujuan_id' => $tujuan->id,
                'status_muatan_tolak' => $this->normalizeStatus($row[12] ?? null),
            ]);
            $kunjungan->save();
            $isNew ? $result['created']++ : $result['updated']++;
        }

        return $result;
    }

    private function importOperasional(UploadedFile $file): array
    {
        $sheet = IOFactory::load($file->getRealPath())->getSheetByName('INPUT');
        $rows = $sheet?->toArray(null, false, false, false) ?? [];
        $currentPelabuhan = null;
        $currentJenisPelayaran = null;
        $currentKunjungan = null;
        $result = ['created' => 0, 'updated' => 0, 'muatan_rows' => 0, 'b3_rows' => 0, 'warnings' => []];

        foreach ($rows as $index => $row) {
            $firstCell = trim((string) ($row[0] ?? ''));
            $secondCell = trim((string) ($row[1] ?? ''));

            if (str_starts_with(strtoupper($firstCell), 'PELABUHAN ')) {
                $currentPelabuhan = $this->firstOrCreatePelabuhan($firstCell, 'UPP');

                continue;
            }

            if ($secondCell !== '' && in_array(strtoupper($secondCell), ['PELAYARAN RAKYAT', 'PERINTIS', 'ANGKUTAN PENYEBRANGAN', 'ANGKUTAN LAUT DALAM NEGERI', 'ANGKUTAN LAUT LUAR NEGERI'], true)) {
                $currentJenisPelayaran = $this->mapOperationalJenisPelayaran($secondCell);

                continue;
            }

            if (is_numeric($firstCell)) {
                $kapal = $this->firstOrCreateKapal(trim((string) ($row[1] ?? 'Kapal Tidak Dikenal')), [
                    'bendera_id' => $this->firstOrCreateBendera((string) ($row[2] ?? 'RI'))->id,
                    'pemilik_agen' => $row[3] ?: null,
                    'panjang' => $row[4] ?: null,
                    'gt' => $row[5] ?: null,
                    'dwt' => $row[6] ?: null,
                    'jenis_kapal_id' => $this->firstOrCreateJenisKapal((string) ($row[12] ?? 'KLM'))->id,
                ]);
                $asal = $this->firstOrCreatePelabuhan((string) ($row[9] ?? 'Pelabuhan Asal'), 'LUAR');
                $tujuan = $this->firstOrCreatePelabuhan((string) ($row[15] ?? 'Pelabuhan Tujuan'), 'LUAR');
                $jenis = $this->firstOrCreateJenisPelayaran($currentJenisPelayaran ?? 'DN');

                $attributes = [
                    'pelabuhan_id' => $currentPelabuhan?->id ?? $this->firstOrCreatePelabuhan('Pelabuhan Jampea', 'UPP')->id,
                    'kapal_id' => $kapal->id,
                    'tgl_tiba' => $this->excelDate($row[7]),
                ];

                $currentKunjungan = Kunjungan::firstOrNew($attributes);
                $isNew = ! $currentKunjungan->exists;
                $currentKunjungan->fill([
                    'jenis_pelayaran_id' => $jenis->id,
                    'nakhoda_id' => null,
                    'bulan' => (int) date('n', strtotime((string) $attributes['tgl_tiba'])),
                    'tahun' => (int) date('Y', strtotime((string) $attributes['tgl_tiba'])),
                    'jam_tiba' => $this->normalizeTime($row[8]),
                    'pelabuhan_asal_id' => $asal->id,
                    'tgl_tambat' => $this->excelDate($row[10]),
                    'jam_tambat' => $this->normalizeTime($row[11]),
                    'tgl_berangkat' => $this->excelDate($row[13]),
                    'jam_berangkat' => $this->normalizeTime($row[14]),
                    'pelabuhan_tujuan_id' => $tujuan->id,
                    'penumpang_turun' => (int) ($row[28] ?: 0),
                    'penumpang_naik' => (int) ($row[29] ?: 0),
                    'pnp_datang_dewasa' => (int) ($row[28] ?: 0),
                    'pnp_tolak_dewasa' => (int) ($row[29] ?: 0),
                    'mobil_turun' => (int) ($row[24] ?: 0),
                    'mobil_naik' => (int) ($row[25] ?: 0),
                    'motor_turun' => (int) ($row[26] ?: 0),
                    'motor_naik' => (int) ($row[27] ?: 0),
                    'kend_datang_gol2' => (int) ($row[24] ?: 0),
                    'kend_tolak_gol2' => (int) ($row[25] ?: 0),
                    'kend_datang_gol1' => (int) ($row[26] ?: 0),
                    'kend_tolak_gol1' => (int) ($row[27] ?: 0),
                    'lanjutan_jenis' => $row[30] ?: null,
                    'lanjutan_ton' => (float) ($row[31] ?: 0),
                    'lanjutan_mobil' => (int) ($row[32] ?: 0),
                    'lanjutan_motor' => (int) ($row[33] ?: 0),
                    'lanjutan_penumpang' => (int) ($row[34] ?: 0),
                ]);
                $currentKunjungan->save();
                $currentKunjungan->muatans()->delete();
                $isNew ? $result['created']++ : $result['updated']++;

                $result['muatan_rows'] += $this->appendOperationalMuatan($currentKunjungan, $row);

                continue;
            }

            if ($currentKunjungan instanceof Kunjungan && $this->hasOperationalMuatanData($row)) {
                $result['muatan_rows'] += $this->appendOperationalMuatan($currentKunjungan, $row);
            }
        }

        return $result;
    }

    private function importSpb(UploadedFile $file): array
    {
        $sheet = IOFactory::load($file->getRealPath())->getSheetByName('LAP. SPB');
        $rows = $sheet?->toArray(null, false, false, false) ?? [];
        $result = ['created' => 0, 'updated' => 0, 'muatan_rows' => 0, 'b3_rows' => 0, 'warnings' => []];

        foreach ($rows as $index => $row) {
            if ($index < 10 || trim((string) ($row[1] ?? '')) === '' || str_contains((string) ($row[1] ?? ''), 'PELABUHAN')) {
                continue;
            }

            $namaKapal = trim(preg_replace('/^[A-Z\.\s]+/u', '', (string) ($row[1] ?? '')) ?: (string) ($row[1] ?? 'Kapal Tidak Dikenal'));
            $kapal = $this->firstOrCreateKapal($namaKapal, [
                'call_sign' => $row[2] ?: null,
                'gt' => $row[3] ?: null,
                'bendera_id' => $this->firstOrCreateBendera((string) ($row[4] ?? 'INDONESIA'))->id,
            ]);
            $asal = $this->firstOrCreatePelabuhan((string) ($row[6] ?? 'Pelabuhan Asal'), 'LUAR');
            $tujuan = $this->firstOrCreatePelabuhan((string) ($row[9] ?? 'Pelabuhan Tujuan'), 'LUAR');

            $kunjungan = Kunjungan::firstOrNew([
                'kapal_id' => $kapal->id,
                'tgl_tiba' => $this->excelDate($row[5]),
                'tgl_berangkat' => $this->excelDate($row[8]),
            ]);
            $isNew = ! $kunjungan->exists;
            $kunjungan->fill([
                'pelabuhan_id' => $this->firstOrCreatePelabuhan('Pelabuhan Jampea', 'UPP')->id,
                'jenis_pelayaran_id' => $this->firstOrCreateJenisPelayaran('DN')->id,
                'bulan' => (int) date('n', strtotime((string) $this->excelDate($row[5]))),
                'tahun' => (int) date('Y', strtotime((string) $this->excelDate($row[5]))),
                'jam_tiba' => '00:00:00',
                'jam_berangkat' => '00:00:00',
                'pelabuhan_asal_id' => $asal->id,
                'pelabuhan_tujuan_id' => $tujuan->id,
                'no_spb_tiba' => $row[7] ?: null,
                'no_spb_tolak' => $row[10] ?: null,
                'eta' => $this->excelDate($row[11]),
            ]);
            $kunjungan->save();
            $isNew ? $result['created']++ : $result['updated']++;
        }

        return $result;
    }

    private function importB3(UploadedFile $file): array
    {
        $sheet = IOFactory::load($file->getRealPath())->getSheetByName('1. Januari 2026');
        $rows = $sheet?->toArray(null, false, false, false) ?? [];
        $result = ['created' => 0, 'updated' => 0, 'muatan_rows' => 0, 'b3_rows' => 0, 'warnings' => []];

        foreach ($rows as $index => $row) {
            if ($index < 11 || trim((string) ($row[1] ?? '')) === '') {
                continue;
            }

            $kapal = $this->firstOrCreateKapal((string) ($row[1] ?? 'Kapal Tidak Dikenal'), [
                'gt' => $row[3] ?: null,
                'bendera_id' => $this->firstOrCreateBendera((string) ($row[4] ?? 'INDONESIA'))->id,
            ]);
            $kunjungan = Kunjungan::query()
                ->where('kapal_id', $kapal->id)
                ->whereDate('tgl_tiba', $this->excelDate($row[0]))
                ->first();

            if (! $kunjungan) {
                $kunjungan = Kunjungan::create([
                    'pelabuhan_id' => $this->firstOrCreatePelabuhan('Pelabuhan Jampea', 'UPP')->id,
                    'kapal_id' => $kapal->id,
                    'jenis_pelayaran_id' => $this->firstOrCreateJenisPelayaran('DN')->id,
                    'bulan' => (int) date('n', strtotime((string) $this->excelDate($row[0]))),
                    'tahun' => (int) date('Y', strtotime((string) $this->excelDate($row[0]))),
                    'tgl_tiba' => $this->excelDate($row[0]),
                    'jam_tiba' => '00:00:00',
                    'pelabuhan_asal_id' => $this->firstOrCreatePelabuhan('Pelabuhan Asal', 'LUAR')->id,
                    'tgl_berangkat' => $this->excelDate($row[0]),
                    'jam_berangkat' => '00:00:00',
                    'pelabuhan_tujuan_id' => $this->firstOrCreatePelabuhan('Pelabuhan Tujuan', 'LUAR')->id,
                ]);
                $result['created']++;
            }

            $barangB3 = BarangB3::firstOrCreate(
                ['un_number' => 'UN'.trim((string) ($row[13] ?? '0000'))],
                [
                    'nama' => trim((string) ($row[12] ?? 'Barang B3')),
                    'kelas' => trim((string) ($row[14] ?? '-')),
                    'kategori' => trim((string) ($row[15] ?? '-')),
                ]
            );

            KunjunganB3::create([
                'kunjungan_id' => $kunjungan->id,
                'barang_b3_id' => $barangB3->id,
                'jenis_kegiatan' => strtoupper(trim((string) ($row[5] ?? 'BONGKAR'))),
                'bentuk_muatan' => strtoupper(trim((string) ($row[6] ?? 'PADAT'))),
                'jumlah_ton' => $row[9] ?: null,
                'jumlah_container' => $row[10] ?: null,
                'petugas' => $row[11] ?: null,
                'kemasan' => $row[16] ?: null,
                'jumlah' => $row[17] ?: null,
            ]);
            $result['b3_rows']++;
        }

        return $result;
    }

    private function appendOperationalMuatan(Kunjungan $kunjungan, array $row): int
    {
        $count = 0;
        $this->appendMuatanRow($kunjungan, 'BONGKAR', $row[16] ?? null, $row[17] ?? null, $row[18] ?? null, $row[19] ?? null, $count);
        $this->appendMuatanRow($kunjungan, 'MUAT', $row[20] ?? null, $row[21] ?? null, $row[22] ?? null, $row[23] ?? null, $count);

        return $count;
    }

    private function appendMuatanRow(Kunjungan $kunjungan, string $type, mixed $jenisBarang, mixed $tonM3, mixed $jenisHewan, mixed $jumlahHewan, int &$count): void
    {
        if (($jenisBarang === null || $jenisBarang === '-') && ($tonM3 === null || $tonM3 === '-') && ($jenisHewan === null || $jenisHewan === '-')) {
            return;
        }

        KunjunganMuatan::create([
            'kunjungan_id' => $kunjungan->id,
            'tipe' => $type,
            'jenis_barang' => trim((string) ($jenisBarang ?: ($jenisHewan ?: 'Muatan'))),
            'ton_m3' => is_numeric($tonM3) ? (float) $tonM3 : null,
            'jenis_hewan' => $jenisHewan && $jenisHewan !== '-' ? (string) $jenisHewan : null,
            'jumlah_hewan' => is_numeric($jumlahHewan) ? (int) $jumlahHewan : null,
        ]);
        $count++;
    }

    private function hasOperationalMuatanData(array $row): bool
    {
        foreach ([16, 17, 18, 19, 20, 21, 22, 23] as $column) {
            $value = $row[$column] ?? null;
            if ($value !== null && $value !== '' && $value !== '-') {
                return true;
            }
        }

        return false;
    }

    private function mapOperationalJenisPelayaran(string $label): string
    {
        return match (strtoupper(trim($label))) {
            'PELAYARAN RAKYAT' => 'PELRA',
            'PERINTIS' => 'PERINTIS',
            'ANGKUTAN PENYEBRANGAN' => 'FERRY-ASDP',
            'ANGKUTAN LAUT DALAM NEGERI' => 'DN',
            'ANGKUTAN LAUT LUAR NEGERI' => 'LN',
            default => 'DN',
        };
    }

    private function firstOrCreatePelabuhan(string $name, string $type): Pelabuhan
    {
        $name = trim($name) ?: 'Pelabuhan Tidak Dikenal';
        $tipePelabuhanId = TipePelabuhan::query()
            ->where('nama', $type)
            ->value('id');

        return Pelabuhan::firstOrCreate(
            ['nama' => $name],
            ['tipe' => $type, 'is_active' => true, 'tipe_pelabuhan_id' => $tipePelabuhanId]
        );
    }

    private function firstOrCreateKapal(string $name, array $attributes = []): Kapal
    {
        $name = trim($name) ?: 'Kapal Tidak Dikenal';
        $kapal = Kapal::firstOrCreate(['nama' => $name], array_merge(['is_active' => true], array_filter($attributes, fn ($value) => $value !== null && $value !== '')));

        $kapal->fill(array_filter($attributes, fn ($value) => $value !== null && $value !== ''))->save();

        return $kapal;
    }

    private function firstOrCreateNakhoda(string $name, ?int $kapalId): Nakhoda
    {
        $name = trim($name) ?: 'Nakhoda Tidak Dikenal';

        return Nakhoda::firstOrCreate(['nama' => $name], ['kapal_id' => $kapalId, 'is_active' => true]);
    }

    private function firstOrCreateJenisKapal(string $code): JenisKapal
    {
        $code = strtoupper(trim($code ?: 'KLM'));

        return JenisKapal::firstOrCreate(['kode' => $code], ['nama' => $code, 'is_active' => true]);
    }

    private function firstOrCreateJenisPelayaran(string $code): JenisPelayaran
    {
        $code = strtoupper(trim($code ?: 'DN'));
        $prefix = substr($code, 0, 1);

        return JenisPelayaran::firstOrCreate(['kode' => $code], ['nama' => $code, 'prefix' => $prefix]);
    }

    private function firstOrCreateBendera(string $name): Bendera
    {
        $name = trim($name) ?: 'INDONESIA';
        $code = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name) ?: 'IND', 0, 3));

        return Bendera::firstOrCreate(['kode' => $code], ['nama_negara' => strtoupper($name), 'is_active' => true]);
    }

    private function excelDate(mixed $value): ?string
    {
        if ($value === null || $value === '' || $value === '-') {
            return null;
        }

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }

        $timestamp = strtotime((string) $value);

        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }

    private function normalizeTime(mixed $value): string
    {
        if ($value === null || $value === '' || $value === '-') {
            return '00:00:00';
        }

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject((float) $value)->format('H:i:s');
        }

        $timestamp = strtotime((string) $value);

        return $timestamp ? date('H:i:s', $timestamp) : '00:00:00';
    }

    private function normalizeStatus(mixed $value): ?string
    {
        $status = strtoupper(trim((string) $value));

        return in_array($status, ['M', 'K', 'ML'], true) ? $status : null;
    }
}
