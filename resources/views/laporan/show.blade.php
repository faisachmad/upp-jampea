@extends('layouts.app')

@section('title', $report['title'])

@section('content')
@php
    $reportLinks = [
        'PELRA' => route('laporan.pelra', $filters),
        'Perintis' => route('laporan.perintis', $filters),
        'Ferry' => route('laporan.ferry', $filters),
        'Dalam Negeri' => route('laporan.dalam-negeri', $filters),
        'Luar Negeri' => route('laporan.luar-negeri', $filters),
        'Rekap SPB' => route('laporan.rekap-spb', $filters),
        'Rekap Operasional' => route('laporan.rekap-operasional', $filters),
    ];
@endphp
<div class="space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $report['title'] }}</h1>
                <p class="mt-1 text-sm text-slate-500">Preview data rekap untuk kebutuhan verifikasi, ekspor PDF, dan ekspor Excel.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ request()->fullUrlWithQuery(['format' => 'pdf']) }}" class="rounded-xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">Unduh PDF</a>
                <a href="{{ request()->fullUrlWithQuery(['format' => 'excel']) }}" class="rounded-xl bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">Unduh Excel</a>
                <a href="{{ route('laporan.export-excel', $filters) }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Workbook Data Dukung</a>
            </div>
        </div>

        <form method="GET" class="mt-6 grid gap-3 md:grid-cols-[1fr_1fr_auto] xl:grid-cols-[1fr_1fr_1fr_auto]">
            <select onchange="window.location=this.value" class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach($reportLinks as $label => $url)
                    <option value="{{ $url }}" {{ request()->url() === $url ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="pelabuhan_id" class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Pelabuhan</option>
                @foreach($pelabuhans as $pelabuhan)
                    <option value="{{ $pelabuhan->id }}" @selected(($filters['pelabuhan_id'] ?? null) == $pelabuhan->id)>{{ $pelabuhan->nama }}</option>
                @endforeach
            </select>
            <select name="tahun" class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                @for($year = now()->year; $year >= 2020; $year--)
                    <option value="{{ $year }}" @selected($filters['year'] == $year)>{{ $year }}</option>
                @endfor
            </select>
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Tampilkan</button>
        </form>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Jumlah Baris</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ count($report['rows']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Periode</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $filters['year'] }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Mode</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ strtoupper(str_replace('-', ' ', $report['key'])) }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        @foreach($report['headers'] as $header)
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($report['rows'] as $row)
                        <tr>
                            @foreach(array_values($row) as $cell)
                                <td class="px-4 py-3 text-slate-700">{{ is_numeric($cell) ? number_format((float) $cell, 2, ',', '.') : $cell }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($report['headers']) }}" class="px-4 py-8 text-center text-slate-500">Belum ada data untuk filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
