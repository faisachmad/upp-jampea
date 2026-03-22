@extends('layouts.app')

@section('title', 'Edit Kunjungan Kapal')

@section('content')
@php
    $muatans = old('muatan', $kunjungan->muatans->map(fn ($muatan) => [
        'tipe' => $muatan->tipe,
        'jenis_barang' => $muatan->jenis_barang,
        'ton_m3' => $muatan->ton_m3,
        'jenis_hewan' => $muatan->jenis_hewan,
        'jumlah_hewan' => $muatan->jumlah_hewan,
    ])->all() ?: [['tipe' => 'BONGKAR']]);

    $b3Rows = old('b3', $kunjungan->b3s->map(fn ($b3) => [
        'barang_b3_id' => $b3->barang_b3_id,
        'jenis_kegiatan' => $b3->jenis_kegiatan,
        'bentuk_muatan' => $b3->bentuk_muatan,
        'jumlah_ton' => $b3->jumlah_ton,
        'jumlah_container' => $b3->jumlah_container,
        'kemasan' => $b3->kemasan,
        'jumlah' => $b3->jumlah,
        'petugas' => $b3->petugas,
    ])->all() ?: [['jenis_kegiatan' => 'BONGKAR']]);
@endphp
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-3 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Kunjungan Kapal</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui data operasional kapal, muatan, dan barang B3 tanpa membuat entri baru.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Detail</a>
            <a href="{{ route('kunjungan.index') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Kembali</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Perbaiki isian berikut sebelum menyimpan.</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('kunjungan.update', $kunjungan) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-6 xl:grid-cols-2">
            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Informasi Umum</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Pelabuhan Pencatat</label>
                        <select name="pelabuhan_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($pelabuhans as $pelabuhan)
                                <option value="{{ $pelabuhan->id }}" @selected(old('pelabuhan_id', $kunjungan->pelabuhan_id) == $pelabuhan->id)>{{ $pelabuhan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Jenis Pelayaran</label>
                        <select name="jenis_pelayaran_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($jenisPelayarans as $jenis)
                                <option value="{{ $jenis->id }}" @selected(old('jenis_pelayaran_id', $kunjungan->jenis_pelayaran_id) == $jenis->id)>{{ $jenis->kode }} - {{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Kapal</label>
                        <select name="kapal_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($kapals as $kapal)
                                <option value="{{ $kapal->id }}" @selected(old('kapal_id', $kunjungan->kapal_id) == $kapal->id)>{{ $kapal->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nakhoda</label>
                        <select name="nakhoda_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Nakhoda</option>
                            @foreach($nakhodas as $nakhoda)
                                <option value="{{ $nakhoda->id }}" @selected(old('nakhoda_id', $kunjungan->nakhoda_id) == $nakhoda->id)>{{ $nakhoda->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Bulan</label>
                        <select name="bulan" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @for($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}" @selected(old('bulan', $kunjungan->bulan) == $month)>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Tahun</label>
                        <input type="number" name="tahun" value="{{ old('tahun', $kunjungan->tahun) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Kedatangan dan Keberangkatan</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal Datang</label>
                        <input type="date" name="tgl_datang" value="{{ old('tgl_datang', optional($kunjungan->tgl_tiba)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Jam Datang</label>
                        <input type="time" name="jam_datang" value="{{ old('jam_datang', substr((string) $kunjungan->jam_tiba, 0, 5)) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Pelabuhan Asal</label>
                        <select name="pelabuhan_asal_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($pelabuhans as $pelabuhan)
                                <option value="{{ $pelabuhan->id }}" @selected(old('pelabuhan_asal_id', $kunjungan->pelabuhan_asal_id) == $pelabuhan->id)>{{ $pelabuhan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Status Muatan Tiba</label>
                        <select name="status_muatan_tiba" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Status</option>
                            @foreach(['M' => 'Muat', 'K' => 'Kosong', 'ML' => 'Muat Lanjutan'] as $key => $label)
                                <option value="{{ $key }}" @selected(old('status_muatan_tiba', $kunjungan->status_muatan_tiba) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal Tambat</label>
                        <input type="date" name="tgl_tambat" value="{{ old('tgl_tambat', optional($kunjungan->tgl_tambat)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Jam Tambat</label>
                        <input type="time" name="jam_tambat" value="{{ old('jam_tambat', $kunjungan->jam_tambat ? substr((string) $kunjungan->jam_tambat, 0, 5) : '') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal Tolak</label>
                        <input type="date" name="tgl_tolak" value="{{ old('tgl_tolak', optional($kunjungan->tgl_berangkat)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Jam Tolak</label>
                        <input type="time" name="jam_tolak" value="{{ old('jam_tolak', substr((string) $kunjungan->jam_berangkat, 0, 5)) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Pelabuhan Tujuan</label>
                        <select name="pelabuhan_tujuan_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($pelabuhans as $pelabuhan)
                                <option value="{{ $pelabuhan->id }}" @selected(old('pelabuhan_tujuan_id', $kunjungan->pelabuhan_tujuan_id) == $pelabuhan->id)>{{ $pelabuhan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Status Muatan Tolak</label>
                        <select name="status_muatan_tolak" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Status</option>
                            @foreach(['M' => 'Muatan', 'K' => 'Kosong', 'ML' => 'Muat Lanjutan'] as $key => $label)
                                <option value="{{ $key }}" @selected(old('status_muatan_tolak', $kunjungan->status_muatan_tolak) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">No. SPB Datang</label>
                        <input type="text" name="no_spb_datang" value="{{ old('no_spb_datang', $kunjungan->no_spb_tiba) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">No. SPB Tolak</label>
                        <input type="text" name="no_spb_tolak" value="{{ old('no_spb_tolak', $kunjungan->no_spb_tolak) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">ETA</label>
                        <input type="date" name="eta" value="{{ old('eta', optional($kunjungan->eta)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </section>
        </div>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Penumpang, Kendaraan, dan Lanjutan</h2>
            <div class="mt-5 grid gap-6 xl:grid-cols-3">
                <div class="space-y-4 rounded-2xl border border-slate-200 p-4">
                    <h3 class="font-semibold text-slate-800">Penumpang</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="mb-1 block text-sm text-slate-600">Datang Dewasa</label><input type="number" min="0" name="pnp_datang_dewasa" value="{{ old('pnp_datang_dewasa', $kunjungan->pnp_datang_dewasa) }}" class="w-full rounded-xl border-slate-200 text-sm"></div>
                        <div><label class="mb-1 block text-sm text-slate-600">Datang Anak</label><input type="number" min="0" name="pnp_datang_anak" value="{{ old('pnp_datang_anak', $kunjungan->pnp_datang_anak) }}" class="w-full rounded-xl border-slate-200 text-sm"></div>
                        <div><label class="mb-1 block text-sm text-slate-600">Tolak Dewasa</label><input type="number" min="0" name="pnp_tolak_dewasa" value="{{ old('pnp_tolak_dewasa', $kunjungan->pnp_tolak_dewasa) }}" class="w-full rounded-xl border-slate-200 text-sm"></div>
                        <div><label class="mb-1 block text-sm text-slate-600">Tolak Anak</label><input type="number" min="0" name="pnp_tolak_anak" value="{{ old('pnp_tolak_anak', $kunjungan->pnp_tolak_anak) }}" class="w-full rounded-xl border-slate-200 text-sm"></div>
                    </div>
                </div>
                <div class="space-y-4 rounded-2xl border border-slate-200 p-4 xl:col-span-2">
                    <h3 class="font-semibold text-slate-800">Kendaraan dan Lanjutan</h3>
                    <div class="grid gap-3 md:grid-cols-4">
                        @foreach([
                            'kend_datang_gol1' => 'Datang Gol 1',
                            'kend_datang_gol2' => 'Datang Gol 2',
                            'kend_datang_gol3' => 'Datang Gol 3',
                            'kend_datang_gol4a' => 'Datang Gol 4A',
                            'kend_datang_gol4b' => 'Datang Gol 4B',
                            'kend_datang_gol5' => 'Datang Gol 5',
                            'kend_tolak_gol1' => 'Tolak Gol 1',
                            'kend_tolak_gol2' => 'Tolak Gol 2',
                            'kend_tolak_gol3' => 'Tolak Gol 3',
                            'kend_tolak_gol4a' => 'Tolak Gol 4A',
                            'kend_tolak_gol4b' => 'Tolak Gol 4B',
                            'kend_tolak_gol5' => 'Tolak Gol 5',
                            'lanjutan_mobil' => 'Lanjutan Mobil',
                            'lanjutan_motor' => 'Lanjutan Motor',
                            'lanjutan_penumpang' => 'Lanjutan Penumpang',
                        ] as $field => $label)
                            <div>
                                <label class="mb-1 block text-sm text-slate-600">{{ $label }}</label>
                                <input type="number" min="0" name="{{ $field }}" value="{{ old($field, $kunjungan->{$field}) }}" class="w-full rounded-xl border-slate-200 text-sm">
                            </div>
                        @endforeach
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm text-slate-600">Jenis Lanjutan</label>
                            <input type="text" name="lanjutan_jenis" value="{{ old('lanjutan_jenis', $kunjungan->lanjutan_jenis) }}" class="w-full rounded-xl border-slate-200 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm text-slate-600">Ton Lanjutan</label>
                            <input type="number" step="0.01" min="0" name="lanjutan_ton" value="{{ old('lanjutan_ton', $kunjungan->lanjutan_ton) }}" class="w-full rounded-xl border-slate-200 text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Data Muatan</h2>
                <button type="button" id="add-muatan-row" class="rounded-xl bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">Tambah Baris Muatan</button>
            </div>
            <div id="muatan-wrapper" class="mt-5 space-y-4">
                @foreach($muatans as $index => $row)
                    <div class="grid gap-3 rounded-2xl border border-slate-200 p-4 md:grid-cols-5">
                        <select name="muatan[{{ $index }}][tipe]" class="rounded-xl border-slate-200 text-sm">
                            <option value="BONGKAR" @selected(($row['tipe'] ?? '') === 'BONGKAR')>BONGKAR</option>
                            <option value="MUAT" @selected(($row['tipe'] ?? '') === 'MUAT')>MUAT</option>
                        </select>
                        <input type="text" name="muatan[{{ $index }}][jenis_barang]" value="{{ $row['jenis_barang'] ?? '' }}" placeholder="Jenis barang" class="rounded-xl border-slate-200 text-sm">
                        <input type="number" step="0.01" min="0" name="muatan[{{ $index }}][ton_m3]" value="{{ $row['ton_m3'] ?? '' }}" placeholder="Ton / M3" class="rounded-xl border-slate-200 text-sm">
                        <input type="text" name="muatan[{{ $index }}][jenis_hewan]" value="{{ $row['jenis_hewan'] ?? '' }}" placeholder="Jenis hewan" class="rounded-xl border-slate-200 text-sm">
                        <div class="flex gap-3">
                            <input type="number" min="0" name="muatan[{{ $index }}][jumlah_hewan]" value="{{ $row['jumlah_hewan'] ?? '' }}" placeholder="Jml hewan" class="w-full rounded-xl border-slate-200 text-sm">
                            <button type="button" class="remove-row rounded-xl bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Barang B3</h2>
                <button type="button" id="add-b3-row" class="rounded-xl bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-100">Tambah Baris B3</button>
            </div>
            <div id="b3-wrapper" class="mt-5 space-y-4">
                @foreach($b3Rows as $index => $row)
                    <div class="grid gap-3 rounded-2xl border border-slate-200 p-4 md:grid-cols-4 xl:grid-cols-8">
                        <select name="b3[{{ $index }}][barang_b3_id]" class="rounded-xl border-slate-200 text-sm xl:col-span-2">
                            <option value="">Pilih barang B3</option>
                            @foreach($barangB3s as $barangB3)
                                <option value="{{ $barangB3->id }}" @selected(($row['barang_b3_id'] ?? '') == $barangB3->id)>{{ $barangB3->nama }}</option>
                            @endforeach
                        </select>
                        <select name="b3[{{ $index }}][jenis_kegiatan]" class="rounded-xl border-slate-200 text-sm">
                            <option value="BONGKAR" @selected(($row['jenis_kegiatan'] ?? '') === 'BONGKAR')>BONGKAR</option>
                            <option value="MUAT" @selected(($row['jenis_kegiatan'] ?? '') === 'MUAT')>MUAT</option>
                        </select>
                        <input type="text" name="b3[{{ $index }}][bentuk_muatan]" value="{{ $row['bentuk_muatan'] ?? '' }}" placeholder="Bentuk muatan" class="rounded-xl border-slate-200 text-sm">
                        <input type="number" step="0.01" min="0" name="b3[{{ $index }}][jumlah_ton]" value="{{ $row['jumlah_ton'] ?? '' }}" placeholder="Ton" class="rounded-xl border-slate-200 text-sm">
                        <input type="number" min="0" name="b3[{{ $index }}][jumlah_container]" value="{{ $row['jumlah_container'] ?? '' }}" placeholder="Container" class="rounded-xl border-slate-200 text-sm">
                        <input type="text" name="b3[{{ $index }}][kemasan]" value="{{ $row['kemasan'] ?? '' }}" placeholder="Kemasan" class="rounded-xl border-slate-200 text-sm">
                        <div class="flex gap-3 xl:col-span-2">
                            <input type="text" name="b3[{{ $index }}][petugas]" value="{{ $row['petugas'] ?? '' }}" placeholder="Petugas" class="w-full rounded-xl border-slate-200 text-sm">
                            <button type="button" class="remove-row rounded-xl bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                        </div>
                        <input type="number" min="0" name="b3[{{ $index }}][jumlah]" value="{{ $row['jumlah'] ?? '' }}" placeholder="Jumlah kemasan" class="rounded-xl border-slate-200 text-sm xl:col-span-2">
                    </div>
                @endforeach
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const muatanWrapper = document.getElementById('muatan-wrapper');
        const b3Wrapper = document.getElementById('b3-wrapper');
        const addMuatanRowButton = document.getElementById('add-muatan-row');
        const addB3RowButton = document.getElementById('add-b3-row');

        const attachRemoveEvents = (container) => {
            container.querySelectorAll('.remove-row').forEach((button) => {
                button.onclick = function () {
                    if (container.children.length > 1) {
                        button.closest('.border').remove();
                    }
                };
            });
        };

        addMuatanRowButton?.addEventListener('click', function () {
            const index = muatanWrapper.children.length;
            muatanWrapper.insertAdjacentHTML('beforeend', `
                <div class="grid gap-3 rounded-2xl border border-slate-200 p-4 md:grid-cols-5">
                    <select name="muatan[${index}][tipe]" class="rounded-xl border-slate-200 text-sm">
                        <option value="BONGKAR">BONGKAR</option>
                        <option value="MUAT">MUAT</option>
                    </select>
                    <input type="text" name="muatan[${index}][jenis_barang]" placeholder="Jenis barang" class="rounded-xl border-slate-200 text-sm">
                    <input type="number" step="0.01" min="0" name="muatan[${index}][ton_m3]" placeholder="Ton / M3" class="rounded-xl border-slate-200 text-sm">
                    <input type="text" name="muatan[${index}][jenis_hewan]" placeholder="Jenis hewan" class="rounded-xl border-slate-200 text-sm">
                    <div class="flex gap-3">
                        <input type="number" min="0" name="muatan[${index}][jumlah_hewan]" placeholder="Jml hewan" class="w-full rounded-xl border-slate-200 text-sm">
                        <button type="button" class="remove-row rounded-xl bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                    </div>
                </div>
            `);
            attachRemoveEvents(muatanWrapper);
        });

        addB3RowButton?.addEventListener('click', function () {
            const index = b3Wrapper.children.length;
            b3Wrapper.insertAdjacentHTML('beforeend', `
                <div class="grid gap-3 rounded-2xl border border-slate-200 p-4 md:grid-cols-4 xl:grid-cols-8">
                    <select name="b3[${index}][barang_b3_id]" class="rounded-xl border-slate-200 text-sm xl:col-span-2">
                        <option value="">Pilih barang B3</option>
                        @foreach($barangB3s as $barangB3)
                            <option value="{{ $barangB3->id }}">{{ $barangB3->nama }}</option>
                        @endforeach
                    </select>
                    <select name="b3[${index}][jenis_kegiatan]" class="rounded-xl border-slate-200 text-sm">
                        <option value="BONGKAR">BONGKAR</option>
                        <option value="MUAT">MUAT</option>
                    </select>
                    <input type="text" name="b3[${index}][bentuk_muatan]" placeholder="Bentuk muatan" class="rounded-xl border-slate-200 text-sm">
                    <input type="number" step="0.01" min="0" name="b3[${index}][jumlah_ton]" placeholder="Ton" class="rounded-xl border-slate-200 text-sm">
                    <input type="number" min="0" name="b3[${index}][jumlah_container]" placeholder="Container" class="rounded-xl border-slate-200 text-sm">
                    <input type="text" name="b3[${index}][kemasan]" placeholder="Kemasan" class="rounded-xl border-slate-200 text-sm">
                    <div class="flex gap-3 xl:col-span-2">
                        <input type="text" name="b3[${index}][petugas]" placeholder="Petugas" class="w-full rounded-xl border-slate-200 text-sm">
                        <button type="button" class="remove-row rounded-xl bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                    </div>
                    <input type="number" min="0" name="b3[${index}][jumlah]" placeholder="Jumlah kemasan" class="rounded-xl border-slate-200 text-sm xl:col-span-2">
                </div>
            `);
            attachRemoveEvents(b3Wrapper);
        });

        attachRemoveEvents(muatanWrapper);
        attachRemoveEvents(b3Wrapper);
    });
</script>
@endpush
