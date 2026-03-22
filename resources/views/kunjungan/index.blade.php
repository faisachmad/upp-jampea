@extends('layouts.app')

@section('title', 'Data Kunjungan Kapal')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    #kunjungan-table thead th {
        background-color: #f9fafb;
        color: #374151;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    #kunjungan-table tbody td {
        padding: 0.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.75rem;
    }

    #kunjungan-table tbody tr:hover {
        background-color: #eff6ff !important;
        transition: background-color 0.2s;
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="{ ...kunjunganForm() }">
    <!-- Search, Filter & Action Card -->
    <div class="bg-white p-4 lg:p-5 rounded-xl shadow-sm border border-gray-100 mb-6 space-y-4">
        <div class="flex justify-between items-center sm:justify-end">
            <!-- Title only visible on mobile nicely, but button always accessible -->
            <button x-on:click="$dispatch('open-modal', 'input-kunjungan-modal')" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Input Kunjungan
            </button>
        </div>

        <form method="GET" action="{{ route('kunjungan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-3">
            <div class="w-full">
                <select name="bulan" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="w-full">
                <select name="tahun" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Tahun</option>
                    @for($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-full sm:col-span-2 md:col-span-1 lg:col-span-2 xl:col-span-2 relative z-50">
                <select name="pelabuhan_id" class="searchable-select w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Semua Pelabuhan</option>
                    @foreach($pelabuhans as $pelabuhan)
                    <option value="{{ $pelabuhan->id }}" {{ request('pelabuhan_id') == $pelabuhan->id ? 'selected' : '' }}>
                        {{ $pelabuhan->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:col-span-2 md:col-span-2 lg:col-span-1">
                <select name="jenis_pelayaran_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisPelayarans as $jenis)
                    <option value="{{ $jenis->id }}" {{ request('jenis_pelayaran_id') == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:col-span-2 md:col-span-3 lg:col-span-5 xl:col-span-1 flex justify-end items-end md:mt-0 xl:mt-0">
                <div class="inline-flex shadow-sm rounded-md w-full sm:w-auto" role="group">
                    <button type="submit" class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-l-md hover:bg-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500 transition-all">
                        Cari
                    </button>
                    <a href="{{ route('kunjungan.index') }}" class="flex-1 sm:flex-none flex items-center justify-center px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-10 focus:ring-2 focus:ring-blue-500 transition-all">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
        <div class="overflow-visible">
            <table id="kunjungan-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left">Tanggal</th>
                        <th class="text-left">Pelabuhan</th>
                        <th class="text-left">Kapal</th>
                        <th class="text-left">Jenis Pelayaran</th>
                        <th class="text-left">Nakhoda</th>
                        <th class="text-left">Rute</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kunjungans as $kunjungan)
                    <tr>
                        <td class="whitespace-nowrap font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($kunjungan->tgl_tiba)->format('d M Y') }}
                        </td>
                        <td class="whitespace-nowrap">
                            {{ $kunjungan->pelabuhan->nama ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap font-bold text-blue-800">
                            {{ $kunjungan->kapal->nama ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap">
                            <span class="px-2 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $kunjungan->jenisPelayaran->kode ?? '-' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap">
                            {{ $kunjungan->nakhoda->nama ?? '-' }}
                        </td>
                        <td class="text-gray-500">
                            <div class="text-[10px] leading-tight">
                                <span class="text-blue-600 font-medium">Dari:</span> {{ $kunjungan->pelabuhanAsal->nama ?? '-' }}<br>
                                <span class="text-green-600 font-medium">Ke:</span> {{ $kunjungan->pelabuhanTujuan->nama ?? '-' }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('kunjungan.edit', $kunjungan) }}"
                                   class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[10px] font-medium hover:bg-amber-100 transition-all">
                                    Edit
                                </a>
                                <a href="{{ route('kunjungan.show', $kunjungan) }}" 
                                   class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-600 border border-blue-200 rounded text-[10px] font-medium hover:bg-blue-100 transition-all">
                                    Detail
                                </a>
                                <form action="{{ route('kunjungan.destroy', $kunjungan) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 py-1 bg-red-50 text-red-600 border border-red-200 rounded text-[10px] font-medium hover:bg-red-100 transition-all"
                                            onclick="event.preventDefault(); confirmDelete(this.closest('form'), 'Yakin ingin menghapus data kunjungan ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                            Tidak ada data kunjungan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $kunjungans->links() }}
    </div>

    <!-- Modal Form Input Kunjungan -->
    <x-modal name="input-kunjungan-modal" :show="false" maxWidth="7xl" :closeable="false" :overflowVisible="true">
        <div class="bg-white rounded-lg sm:rounded-xl">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white rounded-t-lg sm:rounded-t-xl border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Input Kunjungan Kapal</h2>
                    <p class="text-sm text-gray-600 mt-1">Lengkapi semua data kunjungan kapal</p>
                </div>
                <button x-on:click="$dispatch('close-modal', 'input-kunjungan-modal'); currentTab = 1" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                @include('kunjungan.partials.form')
            </div>
        </div>
    </x-modal>
</div>

@push('scripts')
<script>
</script>
@endpush
@endsection
