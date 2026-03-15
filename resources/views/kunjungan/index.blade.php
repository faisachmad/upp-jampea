@extends('layouts.app')

@section('title', 'Data Kunjungan Kapal')

@section('content')
<div class="space-y-6" x-data="{ ...kunjunganForm() }">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Data Kunjungan Kapal</h1>
        <button x-on:click="$dispatch('open-modal', 'input-kunjungan-modal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Input Kunjungan Baru
        </button>
    </div>

    <!-- Success/Error Alert (Handled globally by x-sweet-alert) -->

    <!-- Filter Form -->
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="GET" action="{{ route('kunjungan.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pelabuhan</label>
                <select name="pelabuhan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Pelabuhan</option>
                    @foreach($pelabuhans as $pelabuhan)
                    <option value="{{ $pelabuhan->id }}" {{ request('pelabuhan_id') == $pelabuhan->id ? 'selected' : '' }}>
                        {{ $pelabuhan->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelayaran</label>
                <select name="jenis_pelayaran_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisPelayarans as $jenis)
                    <option value="{{ $jenis->id }}" {{ request('jenis_pelayaran_id') == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('kunjungan.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelabuhan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pelayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nakhoda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kunjungans as $kunjungan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($kunjungan->tgl_datang)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $kunjungan->pelabuhan->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $kunjungan->kapal->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $kunjungan->jenisPelayaran->kode ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $kunjungan->nakhoda->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="text-xs">
                                Dari: {{ $kunjungan->pelabuhanAsal->nama ?? '-' }}<br>
                                Ke: {{ $kunjungan->pelabuhanTujuan->nama ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                            <form action="{{ route('kunjungan.destroy', $kunjungan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="event.preventDefault(); confirmDelete(this.closest('form'), 'Yakin ingin menghapus data kunjungan ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
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
    <x-modal name="input-kunjungan-modal" :show="false" maxWidth="7xl" :closeable="false">
        <div class="bg-white">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
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
function kunjunganForm() {
    return {
        currentTab: 1,
        muatans: [],
        b3s: [],

        addMuatan() {
            this.muatans.push({
                tipe: '',
                jenis_barang: '',
                ton_m3: '',
                jenis_hewan: '',
                jumlah_hewan: ''
            });
        },

        removeMuatan(index) {
            this.muatans.splice(index, 1);
        },

        addB3() {
            this.b3s.push({
                barang_b3_id: '',
                jenis_kegiatan: '',
                bentuk_muatan: '',
                jumlah_ton: '',
                jumlah_container: '',
                kemasan: '',
                jumlah: '',
                petugas: ''
            });
        },

        removeB3(index) {
            this.b3s.splice(index, 1);
        }
    }
}

function autocomplete(url, fieldName) {
    return {
        searchQuery: '',
        results: [],
        showResults: false,
        selectedId: '',

        async search() {
            if (this.searchQuery.length < 2) {
                this.results = [];
                return;
            }

            try {
                const response = await fetch(`${url}?q=${encodeURIComponent(this.searchQuery)}`);
                this.results = await response.json();
            } catch (error) {
                console.error('Search error:', error);
            }
        },

        selectItem(item) {
            this.searchQuery = item.label || item.nama;
            this.selectedId = item.id;
            this.showResults = false;
        }
    }
}

function autocompleteNakhoda() {
    return {
        searchQuery: '',
        nakhodaId: ''
    }
}
</script>
@endpush
@endsection
