@extends('layouts.app')

@section('title', 'Import Excel Lama')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h1 class="text-2xl font-bold text-slate-900">Import Excel Lama</h1>
        <p class="mt-1 text-sm text-slate-500">Unggah file operasional, kunjungan kapal, SPB, atau B3 dari format Excel lama untuk dipetakan ke database SAPOJAM.</p>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('import.excel.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <label class="mb-2 block text-sm font-medium text-slate-700">Pilih satu atau lebih file Excel</label>
        <input type="file" name="files[]" multiple accept=".xlsx,.xls" class="block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700">
        @error('files')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('files.*')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            <p class="font-semibold text-slate-800">Catatan format yang didukung:</p>
            <ul class="mt-2 list-disc pl-5">
                <li>OPERASIONAL untuk data kunjungan detail dan muatan.</li>
                <li>LAP. Kunjungan Kapal untuk histori kunjungan dasar.</li>
                <li>LAP. SPB untuk nomor SPB dan ETA.</li>
                <li>File B3 untuk muatan barang berbahaya dan beracun.</li>
            </ul>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">Proses Import</button>
        </div>
    </form>

    @php($summary = session('import_summary'))
    @if($summary)
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Ringkasan Import</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Data Baru</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($summary['created']) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Data Update</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($summary['updated']) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Baris Muatan</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($summary['muatan_rows']) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Baris B3</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($summary['b3_rows']) }}</p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <p class="text-sm font-semibold text-slate-800">File Diproses</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($summary['processed_files'] as $fileName)
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $fileName }}</span>
                        @endforeach
                    </div>
                </div>

                @if(!empty($summary['warnings']))
                    <div>
                        <p class="text-sm font-semibold text-amber-700">Catatan Import</p>
                        <ul class="mt-2 list-disc pl-5 text-sm text-amber-700">
                            @foreach($summary['warnings'] as $warning)
                                <li>{{ $warning }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
