@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')
@php
    $summaryCards = [
        ['label' => 'Total Kunjungan', 'value' => number_format($dashboard['summary']['total_kunjungan']), 'color' => 'blue'],
        ['label' => 'Total GT', 'value' => number_format($dashboard['summary']['total_gt'], 2, ',', '.'), 'color' => 'teal'],
        ['label' => 'Penumpang', 'value' => number_format($dashboard['summary']['total_penumpang']), 'color' => 'amber'],
        ['label' => 'Muatan + Lanjutan', 'value' => number_format($dashboard['summary']['total_muatan'], 2, ',', '.'), 'color' => 'rose'],
    ];
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard Operasional</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan real-time dari data kunjungan, muatan, penumpang, dan distribusi jenis pelayaran.</p>
        </div>
        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <select name="bulan" class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                @for($month = 1; $month <= 12; $month++)
                    <option value="{{ $month }}" {{ $selectedMonth === $month ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                @endfor
            </select>
            <select name="tahun" class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                @for($year = now()->year; $year >= 2020; $year--)
                    <option value="{{ $year }}" {{ $selectedYear === $year ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
            </select>
            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Terapkan</button>
        </form>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($summaryCards as $card)
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $card['label'] }}</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Tren Kunjungan Bulanan</h2>
                    <p class="text-sm text-slate-500">Jumlah kunjungan kapal per bulan pada tahun {{ $selectedYear }}</p>
                </div>
                <a href="{{ route('laporan.rekap-operasional', ['tahun' => $selectedYear]) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat laporan</a>
            </div>
            <div class="mt-6 h-80"><canvas id="dashboardTrendChart"></canvas></div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Distribusi Jenis Pelayaran</h2>
            <p class="text-sm text-slate-500">Komposisi kunjungan berdasarkan kategori operasional</p>
            <div class="mt-6 h-80"><canvas id="dashboardDistributionChart"></canvas></div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">Status Kelengkapan Bulanan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-slate-500">Bulan</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-500">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-500">Jumlah Kunjungan</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-500">Total GT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($dashboard['status'] as $status)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $status['label'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $status['status'] === 'lengkap' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ strtoupper($status['status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($status['count']) }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($status['total_gt'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-sm">
            <h2 class="text-lg font-semibold">Aktivitas Terkini</h2>
            <p class="mt-1 text-sm text-slate-300">Lima entri kunjungan terbaru yang terekam di sistem.</p>
            <div class="mt-6 space-y-4">
                @forelse($dashboard['latest'] as $item)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="font-semibold">{{ $item['kapal'] }}</p>
                        <p class="mt-1 text-sm text-slate-300">{{ $item['pelabuhan'] }} • {{ $item['tanggal'] }}</p>
                        <p class="mt-2 inline-flex rounded-full bg-blue-500/20 px-3 py-1 text-xs font-semibold text-blue-100">{{ $item['jenis'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-300">Belum ada data kunjungan untuk periode yang dipilih.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trendContext = document.getElementById('dashboardTrendChart');
        const distributionContext = document.getElementById('dashboardDistributionChart');

        if (trendContext) {
            new Chart(trendContext, {
                type: 'line',
                data: {
                    labels: @json(collect($dashboard['trend'])->pluck('label')->all()),
                    datasets: [{
                        label: 'Jumlah kunjungan',
                        data: @json(collect($dashboard['trend'])->pluck('value')->all()),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.12)',
                        fill: true,
                        tension: 0.35,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                },
            });
        }

        if (distributionContext) {
            new Chart(distributionContext, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($dashboard['distribution'])),
                    datasets: [{
                        data: @json(array_values($dashboard['distribution'])),
                        backgroundColor: ['#2563eb', '#16a34a', '#f59e0b', '#9333ea', '#ef4444'],
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        }
    });
</script>
@endpush
