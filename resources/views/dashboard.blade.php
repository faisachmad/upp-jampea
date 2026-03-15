<x-app-layout>
    @section('title', 'Dashboard Monitoring')

    @section('content')
    <div class="space-y-8 animate-fade-in">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Monitoring Dashboard</h2>
                <p class="text-slate-500 mt-1">Selamat datang kembali, Sistem Informasi Pelaporan Pelabuhan Laut Jampea.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <span class="w-2 h-2 mr-2 rounded-full bg-blue-600 animate-pulse"></span>
                    Live Data
                </span>
                <div class="px-4 py-2 bg-white rounded-lg shadow-sm border border-slate-200 text-sm font-medium text-slate-700">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>

        <!-- Summary Cards Group -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card: Total Kunjungan -->
            <div class="glass relative overflow-hidden group rounded-2xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-20 h-20 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="p-3 w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Kunjungan</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-slate-800">2,482</h3>
                        <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-0.5 rounded-full">+12%</span>
                    </div>
                </div>
            </div>

            <!-- Card: Kapal GT -->
            <div class="glass relative overflow-hidden group rounded-2xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-20 h-20 text-teal-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="p-3 w-12 h-12 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Gross Tonnage (GT)</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-slate-800">45.2k</h3>
                        <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">Stable</span>
                    </div>
                </div>
            </div>

            <!-- Card: Penumpang -->
            <div class="glass relative overflow-hidden group rounded-2xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-20 h-20 text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="p-3 w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Penumpang</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-slate-800">8,912</h3>
                        <span class="text-xs font-medium text-red-600 bg-red-100 px-2 py-0.5 rounded-full">-3.4%</span>
                    </div>
                </div>
            </div>

            <!-- Card: Muatan Barang -->
            <div class="glass relative overflow-hidden group rounded-2xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-20 h-20 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="p-3 w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Muatan (Ton)</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-slate-800">12.5k</h3>
                        <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-0.5 rounded-full">+5.1%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Line Chart: Vessel Traffic -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Trafik Kunjungan Kapal</h3>
                        <p class="text-sm text-slate-500">Statistik bulanan tahun 2026</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <span class="text-sm font-medium text-slate-600 uppercase">2026</span>
                    </div>
                </div>
                <div id="vesselChart" class="w-full h-80"></div>
            </div>

            <!-- Pie Chart: Vessel Types -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Jenis Pelayaran</h3>
                        <p class="text-sm text-slate-500">Distribusi berdasarkan kategori</p>
                    </div>
                    <select class="bg-slate-50 border-none text-sm font-medium text-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Semua Kunjungan</option>
                        <option>Dalam Negeri</option>
                        <option>Luar Negeri</option>
                    </select>
                </div>
                <div id="typeChart" class="w-full h-80 flex items-center justify-center"></div>
            </div>
        </div>

        <!-- Recent Activity & Status Table -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Data Input Status -->
            <div class="xl:col-span-2 bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-xl font-bold text-slate-800">Status Kelengkapan Data</h3>
                    <button class="text-blue-600 hover:text-blue-700 font-medium text-sm transition-colors">Lihat Semua</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Bulan</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Status</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Trafik</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach(['Januari', 'Februari', 'Maret', 'April'] as $bulan)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5 border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs">
                                            {{ substr($bulan, 0, 3) }}
                                        </div>
                                        <span class="font-semibold text-slate-700">{{ $bulan }} 2026</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 border-b border-slate-100">
                                    @if($loop->first || $loop->iteration == 2)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 mr-2 rounded-full bg-green-500"></span>
                                            Lengkap
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 mr-2 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 border-b border-slate-100">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700">{{ rand(100, 500) }} Kapal</span>
                                        <span class="text-xs text-slate-400">Total GT: {{ rand(1000, 5000) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 border-b border-slate-100 text-right">
                                    <a href="#" class="inline-flex items-center gap-2 text-slate-400 hover:text-blue-600 transition-colors font-medium">
                                        <span class="text-sm">Manage</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Stats/Notifications -->
            <div class="bg-gradient-to-br from-blue-900 to-indigo-900 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-400/30 transition-all duration-700"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold">Aktivitas Terbaru</h3>
                        <p class="text-blue-200 text-sm mt-1">Update sistem & operasional</p>
                    </div>
                    
                    <div class="space-y-6 flex-grow">
                        <div class="flex gap-4">
                            <div class="shrink-0 w-2 mt-2 h-2 rounded-full bg-blue-400 ring-4 ring-blue-400/20"></div>
                            <div>
                                <p class="text-sm font-medium leading-none">Kapal KN. Alpheios Sandar</p>
                                <p class="text-xs text-blue-300 mt-1">10 Menit yang lalu</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="shrink-0 w-2 mt-2 h-2 rounded-full bg-emerald-400 ring-4 ring-emerald-400/20"></div>
                            <div>
                                <p class="text-sm font-medium leading-none">Laporan Maret 2026 Disetujui</p>
                                <p class="text-xs text-blue-300 mt-1">2 Jam yang lalu</p>
                            </div>
                        </div>
                        <div class="flex gap-4 opacity-70">
                            <div class="shrink-0 w-2 mt-2 h-2 rounded-full bg-slate-400 ring-4 ring-slate-400/20"></div>
                            <div>
                                <p class="text-sm font-medium leading-none">Update Master Data Pelabuhan</p>
                                <p class="text-xs text-blue-300 mt-1">Kemarin</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-white/10">
                        <div class="bg-white/10 rounded-2xl p-4 flex items-center justify-between backdrop-blur-md">
                            <div>
                                <p class="text-xs text-blue-200 uppercase tracking-widest font-bold">Kapasitas Port</p>
                                <p class="text-lg font-bold">78% Terisi</p>
                            </div>
                            <div class="w-12 h-12 rounded-full border-4 border-emerald-400 flex items-center justify-center text-[10px] font-bold">
                                HIGH
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Line Chart initialization
            const vesselOptions = {
                series: [{
                    name: 'Kunjungan Kapal',
                    data: [120, 150, 130, 180, 210, 175, 190, 220, 250, 210, 180, 230]
                }],
                chart: {
                    height: 320,
                    type: 'area',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    sparkline: { enabled: false }
                },
                colors: ['#3b82f6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100, 100, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) { return val.toFixed(0) }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                tooltip: {
                    x: { show: false },
                    theme: 'light'
                }
            };

            const vesselChart = new ApexCharts(document.querySelector("#vesselChart"), vesselOptions);
            vesselChart.render();

            // Pie Chart initialization
            const typeOptions = {
                series: [44, 55, 13, 33],
                chart: {
                    type: 'donut',
                    height: 320
                },
                labels: ['Local', 'Inter-island', 'Liner', 'Tramper'],
                colors: ['#3b82f6', '#10b981', '#6366f1', '#f59e0b'],
                legend: {
                    position: 'bottom',
                    fontFamily: 'inherit',
                    labels: { colors: '#64748b' }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '14px', color: '#64748b', offsetY: -10 },
                                value: { show: true, fontSize: '24px', fontWeight: '800', color: '#1e293b', offsetY: 16 },
                                total: {
                                    show: true,
                                    label: 'Total Kunjungan',
                                    color: '#64748b',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: { show: false },
                dataLabels: { enabled: false }
            };

            const typeChart = new ApexCharts(document.querySelector("#typeChart"), typeOptions);
            typeChart.render();
        });
    </script>
    @endpush
    @endsection
</x-app-layout>
