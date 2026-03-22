<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KunjunganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pelabuhan_id' => 'required|exists:pelabuhans,id',
            'kapal_id' => 'required|exists:kapals,id',
            'jenis_pelayaran_id' => 'required|exists:jenis_pelayarans,id',
            'nakhoda_id' => 'nullable|exists:nakhodas,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2100',

            'tgl_datang' => 'required|date',
            'jam_datang' => 'required|date_format:H:i',
            'pelabuhan_asal_id' => 'required|exists:pelabuhans,id',
            'status_muatan_tiba' => 'nullable|in:M,K,ML',
            'tgl_tambat' => 'nullable|date|after_or_equal:tgl_datang',
            'jam_tambat' => 'nullable|date_format:H:i',
            'no_spb_datang' => 'nullable|string|max:50',

            'tgl_tolak' => 'required|date|after_or_equal:tgl_datang',
            'jam_tolak' => 'required|date_format:H:i',
            'pelabuhan_tujuan_id' => 'required|exists:pelabuhans,id',
            'status_muatan_tolak' => 'nullable|in:M,K,ML',
            'no_spb_tolak' => 'nullable|string|max:50',
            'eta' => 'nullable|date|after_or_equal:tgl_datang',

            'pnp_datang_dewasa' => 'nullable|integer|min:0',
            'pnp_datang_anak' => 'nullable|integer|min:0',
            'pnp_tolak_dewasa' => 'nullable|integer|min:0',
            'pnp_tolak_anak' => 'nullable|integer|min:0',

            'kend_datang_gol1' => 'nullable|integer|min:0',
            'kend_datang_gol2' => 'nullable|integer|min:0',
            'kend_datang_gol3' => 'nullable|integer|min:0',
            'kend_datang_gol4a' => 'nullable|integer|min:0',
            'kend_datang_gol4b' => 'nullable|integer|min:0',
            'kend_datang_gol5' => 'nullable|integer|min:0',
            'kend_tolak_gol1' => 'nullable|integer|min:0',
            'kend_tolak_gol2' => 'nullable|integer|min:0',
            'kend_tolak_gol3' => 'nullable|integer|min:0',
            'kend_tolak_gol4a' => 'nullable|integer|min:0',
            'kend_tolak_gol4b' => 'nullable|integer|min:0',
            'kend_tolak_gol5' => 'nullable|integer|min:0',

            'lanjutan_jenis' => 'nullable|string|max:100',
            'lanjutan_ton' => 'nullable|numeric|min:0',
            'lanjutan_mobil' => 'nullable|integer|min:0',
            'lanjutan_motor' => 'nullable|integer|min:0',
            'lanjutan_penumpang' => 'nullable|integer|min:0',

            'muatan' => 'nullable|array',
            'muatan.*.tipe' => 'required|in:BONGKAR,MUAT',
            'muatan.*.jenis_barang' => 'required|string|max:100',
            'muatan.*.ton_m3' => 'nullable|numeric|min:0',
            'muatan.*.jenis_hewan' => 'nullable|string|max:50',
            'muatan.*.jumlah_hewan' => 'nullable|integer|min:0',

            'b3' => 'nullable|array',
            'b3.*.barang_b3_id' => 'required|exists:barang_b3s,id',
            'b3.*.jenis_kegiatan' => 'required|in:BONGKAR,MUAT',
            'b3.*.bentuk_muatan' => 'required|in:CURAH,PADAT',
            'b3.*.jumlah_ton' => 'nullable|numeric|min:0',
            'b3.*.jumlah_container' => 'nullable|integer|min:0',
            'b3.*.kemasan' => 'nullable|string|max:50',
            'b3.*.jumlah' => 'nullable|integer|min:0',
            'b3.*.petugas' => 'nullable|string|max:100',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'muatan' => $this->filterRows($this->input('muatan', []), [
                'tipe', 'jenis_barang', 'ton_m3', 'jenis_hewan', 'jumlah_hewan',
            ]),
            'b3' => $this->filterRows($this->input('b3', []), [
                'barang_b3_id', 'jenis_kegiatan', 'bentuk_muatan', 'jumlah_ton', 'jumlah_container', 'kemasan', 'jumlah', 'petugas',
            ]),
        ]);
    }

    private function filterRows(mixed $rows, array $fields): array
    {
        if (! is_array($rows)) {
            return [];
        }

        return array_values(array_filter($rows, function ($row) use ($fields) {
            if (! is_array($row)) {
                return false;
            }

            foreach ($fields as $field) {
                $value = $row[$field] ?? null;
                if ($value !== null && $value !== '') {
                    return true;
                }
            }

            return false;
        }));
    }
}
