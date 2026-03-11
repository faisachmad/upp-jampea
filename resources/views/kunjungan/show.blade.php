@extends('layouts.app')

@section('title', 'Detail Kunjungan Kapal')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Kunjungan Kapal</h1>
            <p class="text-sm text-gray-600 mt-1">
                {{ $kunjungan->kapal->nama ?? '-' }} |
                {{ \Carbon\Carbon::parse($kunjungan->tgl_datang)->format('d F Y') }}
            </p>
        </div>
        <a href="{{ route('kunjungan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali ke Daftar
        </a>
    </div>

    <!-- Data Umum Kunjungan -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Informasi Umum</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Pelabuhan</label>
                <p class="mt-1 text-gray-900 font-medium">{{ $kunjungan->pelabuhan->nama ?? '-' }} ({{ $kunjungan->pelabuhan->kode ?? '-' }})</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Jenis Pelayaran</label>
                <p class="mt-1">
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $kunjungan->jenisPelayaran->nama ?? '-' }}
                    </span>
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Nama Kapal</label>
                <p class="mt-1 text-gray-900 font-medium">{{ $kunjungan->kapal->nama ?? '-' }}</p>
                <p class="text-sm text-gray-500">
                    {{ $kunjungan->kapal->jenis ?? '-' }} |
                    GT: {{ $kunjungan->kapal->gt ?? '-' }} |
                    Call Sign: {{ $kunjungan->kapal->call_sign ?? '-' }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Nakhoda</label>
                <p class="mt-1 text-gray-900 font-medium">{{ $kunjungan->nakhoda->nama ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Periode</label>
                <p class="mt-1 text-gray-900 font-medium">
                    {{ DateTime::createFromFormat('!m', $kunjungan->bulan)->format('F') }} {{ $kunjungan->tahun }}
                </p>
            </div>
        </div>
    </div>

    <!-- Data Kedatangan & Keberangkatan -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Kedatangan & Keberangkatan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kedatangan -->
            <div class="border-r pr-6">
                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Kedatangan
                </h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal & Jam</label>
                        <p class="mt-1 text-gray-900 font-medium">
                            {{ \Carbon\Carbon::parse($kunjungan->tgl_datang)->format('d F Y') }} |
                            {{ $kunjungan->jam_datang }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Dari Pelabuhan</label>
                        <p class="mt-1 text-gray-900">{{ $kunjungan->pelabuhanAsal->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">No. SPB Datang</label>
                        <p class="mt-1 text-gray-900">{{ $kunjungan->no_spb_datang ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Keberangkatan -->
            <div class="pl-6">
                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Keberangkatan
                </h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal & Jam</label>
                        <p class="mt-1 text-gray-900 font-medium">
                            {{ \Carbon\Carbon::parse($kunjungan->tgl_tolak)->format('d F Y') }} |
                            {{ $kunjungan->jam_tolak }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ke Pelabuhan</label>
                        <p class="mt-1 text-gray-900">{{ $kunjungan->pelabuhanTujuan->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">No. SPB Tolak</label>
                        <p class="mt-1 text-gray-900">{{ $kunjungan->no_spb_tolak ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Penumpang -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Data Penumpang</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <p class="text-sm text-gray-600">Datang - Dewasa</p>
                <p class="text-2xl font-bold text-green-700">{{ $kunjungan->pnp_datang_dewasa }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <p class="text-sm text-gray-600">Datang - Anak</p>
                <p class="text-2xl font-bold text-green-700">{{ $kunjungan->pnp_datang_anak }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                <p class="text-sm text-gray-600">Tolak - Dewasa</p>
                <p class="text-2xl font-bold text-red-700">{{ $kunjungan->pnp_tolak_dewasa }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                <p class="text-sm text-gray-600">Tolak - Anak</p>
                <p class="text-2xl font-bold text-red-700">{{ $kunjungan->pnp_tolak_anak }}</p>
            </div>
        </div>
    </div>

    <!-- Data Kendaraan -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Data Kendaraan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Golongan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Datang</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tolak</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Gol I (Motor)</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_datang_gol1 }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_tolak_gol1 }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Gol II (Sedan/Jeep)</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_datang_gol2 }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_tolak_gol2 }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Gol III (Minibus)</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_datang_gol3 }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_tolak_gol3 }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Gol IVA (Bus Kecil)</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_datang_gol4a }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_tolak_gol4a }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Gol IVB (Bus Besar)</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_datang_gol4b }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_tolak_gol4b }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Gol V (Truk)</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_datang_gol5 }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $kunjungan->kend_tolak_gol5 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Data Muatan -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Data Muatan</h3>

        @if($kunjungan->lanjutan_ton)
        <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <label class="block text-sm font-medium text-gray-700">Lanjutan Muatan</label>
            <p class="text-lg font-semibold text-blue-700">{{ number_format($kunjungan->lanjutan_ton, 2) }} Ton</p>
        </div>
        @endif

        @if($kunjungan->kunjunganMuatans->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ton/M³</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Hewan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Hewan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($kunjungan->kunjunganMuatans as $muatan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $muatan->tipe == 'BONGKAR' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $muatan->tipe }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $muatan->jenis_barang }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $muatan->ton_m3 ? number_format($muatan->ton_m3, 2) : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $muatan->jenis_hewan ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $muatan->jumlah_hewan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center py-8 text-gray-500">Tidak ada data muatan</p>
        @endif
    </div>

    <!-- Data B3 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Data Barang B3 (Berbahaya & Beracun)</h3>

        @if($kunjungan->kunjunganB3s->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang B3</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">UN Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bentuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ton</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Container</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kemasan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($kunjungan->kunjunganB3s as $b3)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $b3->barangB3->nama ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                {{ $b3->barangB3->un_number ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $b3->jenis_kegiatan == 'BONGKAR' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $b3->jenis_kegiatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $b3->bentuk_muatan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $b3->jumlah_ton ? number_format($b3->jumlah_ton, 2) : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $b3->jumlah_container ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $b3->kemasan ?? '-' }}
                            @if($b3->jumlah)
                            <span class="text-xs text-gray-500">({{ $b3->jumlah }} unit)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $b3->petugas ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center py-8 text-gray-500">Tidak ada muatan B3</p>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <a href="{{ route('kunjungan.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            ← Kembali ke Daftar
        </a>

        <form action="{{ route('kunjungan.destroy', $kunjungan) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    onclick="return confirm('Yakin ingin menghapus data kunjungan ini beserta semua muatan dan B3-nya?')">
                🗑️ Hapus Data Kunjungan
            </button>
        </form>
    </div>
</div>
@endsection
