@extends('layouts.app')

@section('title', 'Input Kunjungan Kapal')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="kunjunganForm()">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Input Kunjungan Kapal</h1>
            <p class="text-sm text-gray-600 mt-1">Lengkapi semua data kunjungan kapal</p>
        </div>
        <a href="{{ route('kunjungan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>

    <!-- Error Summary -->
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <strong class="font-bold">Terdapat kesalahan!</strong>
        <ul class="mt-2 ml-4 list-disc">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('kunjungan.store') }}" method="POST">
        @csrf

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button type="button" @click="currentTab = 1"
                            class="px-6 py-3 text-sm font-medium border-b-2 transition-colors"
                            :class="currentTab === 1 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                        1. Data Kunjungan
                    </button>
                    <button type="button" @click="currentTab = 2"
                            class="px-6 py-3 text-sm font-medium border-b-2 transition-colors"
                            :class="currentTab === 2 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                        2. Kedatangan & Keberangkatan
                    </button>
                    <button type="button" @click="currentTab = 3"
                            class="px-6 py-3 text-sm font-medium border-b-2 transition-colors"
                            :class="currentTab === 3 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                        3. Penumpang & Kendaraan
                    </button>
                    <button type="button" @click="currentTab = 4"
                            class="px-6 py-3 text-sm font-medium border-b-2 transition-colors"
                            :class="currentTab === 4 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                        4. Data Muatan
                    </button>
                    <button type="button" @click="currentTab = 5"
                            class="px-6 py-3 text-sm font-medium border-b-2 transition-colors"
                            :class="currentTab === 5 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                        5. Barang B3
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab 1: Data Kunjungan -->
        <div x-show="currentTab === 1" class="bg-white rounded-lg shadow p-6 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Kunjungan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pelabuhan -->
                <div>
                    <label for="pelabuhan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pelabuhan <span class="text-red-500">*</span>
                    </label>
                    <select name="pelabuhan_id" id="pelabuhan_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('pelabuhan_id') border-red-500 @enderror">
                        <option value="">-- Pilih Pelabuhan --</option>
                        @foreach($pelabuhans as $pelabuhan)
                        <option value="{{ $pelabuhan->id }}" {{ old('pelabuhan_id') == $pelabuhan->id ? 'selected' : '' }}>
                            {{ $pelabuhan->nama }} ({{ $pelabuhan->kode }})
                        </option>
                        @endforeach
                    </select>
                    @error('pelabuhan_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Jenis Pelayaran -->
                <div>
                    <label for="jenis_pelayaran_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Pelayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_pelayaran_id" id="jenis_pelayaran_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('jenis_pelayaran_id') border-red-500 @enderror">
                        <option value="">-- Pilih Jenis Pelayaran --</option>
                        @foreach($jenisPelayarans as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_pelayaran_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('jenis_pelayaran_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Kapal (with autocomplete) -->
                <div>
                    <label for="kapal_search" class="block text-sm font-medium text-gray-700 mb-2">
                        Kapal <span class="text-red-500">*</span>
                    </label>
                    <div x-data="autocomplete('{{ route('api.kapal.search') }}', 'kapal_id')">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="search()"
                               @focus="showResults = true"
                               @click.away="showResults = false"
                               placeholder="Ketik nama kapal..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <input type="hidden" name="kapal_id" x-model="selectedId" required>

                        <div x-show="showResults && results.length > 0"
                             class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                            <template x-for="result in results" :key="result.id">
                                <div @click="selectItem(result)"
                                     class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    <div class="font-medium" x-text="result.nama"></div>
                                    <div class="text-xs text-gray-500">
                                        <span x-text="result.jenis"></span> - GT: <span x-text="result.gt"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    @error('kapal_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Nakhoda (with autocomplete) -->
                <div>
                    <label for="nakhoda_search" class="block text-sm font-medium text-gray-700 mb-2">
                        Nakhoda <span class="text-red-500">*</span>
                    </label>
                    <div x-data="autocompleteNakhoda()">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce="200"
                               placeholder="Ketik nama nakhoda atau buat baru..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <input type="hidden" name="nakhoda_id" x-model="nakhodaId" required>
                    </div>
                    @error('nakhoda_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">*Jika nakhoda belum terdaftar, tambahkan via Master Nakhoda</p>
                </div>

                <!-- Bulan -->
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">
                        Bulan <span class="text-red-500">*</span>
                    </label>
                    <select name="bulan" id="bulan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('bulan') border-red-500 @enderror">
                        <option value="">-- Pilih Bulan --</option>
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('bulan', date('n')) == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                    @error('bulan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Tahun -->
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun <span class="text-red-500">*</span>
                    </label>
                    <select name="tahun" id="tahun" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tahun') border-red-500 @enderror">
                        @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ old('tahun', date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    @error('tahun')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" @click="currentTab = 2"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Selanjutnya →
                </button>
            </div>
        </div>

        <!-- Tab 2: Kedatangan & Keberangkatan -->
        <div x-show="currentTab === 2" class="bg-white rounded-lg shadow p-6 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Kedatangan & Keberangkatan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Kedatangan -->
                <div class="space-y-4 border-r pr-6">
                    <h4 class="font-medium text-gray-900 mb-3">Kedatangan Kapal</h4>

                    <div>
                        <label for="tgl_datang" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Datang <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tgl_datang" id="tgl_datang" required value="{{ old('tgl_datang') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tgl_datang') border-red-500 @enderror">
                        @error('tgl_datang')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="jam_datang" class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Datang <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="jam_datang" id="jam_datang" required value="{{ old('jam_datang') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('jam_datang') border-red-500 @enderror">
                        @error('jam_datang')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pelabuhan Asal <span class="text-red-500">*</span>
                        </label>
                        <div x-data="autocomplete('{{ route('api.pelabuhan.search') }}', 'pelabuhan_asal_id')">
                            <input type="text"
                                   x-model="searchQuery"
                                   @input.debounce.300ms="search()"
                                   @focus="showResults = true"
                                   @click.away="showResults = false"
                                   placeholder="Ketik nama pelabuhan asal..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="pelabuhan_asal_id" x-model="selectedId" required>

                            <div x-show="showResults && results.length > 0"
                                 class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                                <template x-for="result in results" :key="result.id">
                                    <div @click="selectItem(result)"
                                         class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        <div class="font-medium" x-text="result.nama"></div>
                                        <div class="text-xs text-gray-500" x-text="result.kode + ' - ' + result.tipe"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('pelabuhan_asal_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="no_spb_datang" class="block text-sm font-medium text-gray-700 mb-2">
                            No. SPB Datang
                        </label>
                        <input type="text" name="no_spb_datang" id="no_spb_datang" value="{{ old('no_spb_datang') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Data Keberangkatan -->
                <div class="space-y-4 pl-6">
                    <h4 class="font-medium text-gray-900 mb-3">Keberangkatan Kapal</h4>

                    <div>
                        <label for="tgl_tolak" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Tolak <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tgl_tolak" id="tgl_tolak" required value="{{ old('tgl_tolak') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tgl_tolak') border-red-500 @enderror">
                        @error('tgl_tolak')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="jam_tolak" class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Tolak <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="jam_tolak" id="jam_tolak" required value="{{ old('jam_tolak') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('jam_tolak') border-red-500 @enderror">
                        @error('jam_tolak')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pelabuhan Tujuan <span class="text-red-500">*</span>
                        </label>
                        <div x-data="autocomplete('{{ route('api.pelabuhan.search') }}', 'pelabuhan_tujuan_id')">
                            <input type="text"
                                   x-model="searchQuery"
                                   @input.debounce.300ms="search()"
                                   @focus="showResults = true"
                                   @click.away="showResults = false"
                                   placeholder="Ketik nama pelabuhan tujuan..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="pelabuhan_tujuan_id" x-model="selectedId" required>

                            <div x-show="showResults && results.length > 0"
                                 class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                                <template x-for="result in results" :key="result.id">
                                    <div @click="selectItem(result)"
                                         class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        <div class="font-medium" x-text="result.nama"></div>
                                        <div class="text-xs text-gray-500" x-text="result.kode + ' - ' + result.tipe"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('pelabuhan_tujuan_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="no_spb_tolak" class="block text-sm font-medium text-gray-700 mb-2">
                            No. SPB Tolak
                        </label>
                        <input type="text" name="no_spb_tolak" id="no_spb_tolak" value="{{ old('no_spb_tolak') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <button type="button" @click="currentTab = 1"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Sebelumnya
                </button>
                <button type="button" @click="currentTab = 3"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Selanjutnya →
                </button>
            </div>
        </div>

        <!-- Tab 3: Penumpang & Kendaraan -->
        <div x-show="currentTab === 3" class="bg-white rounded-lg shadow p-6 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Penumpang & Kendaraan</h3>

            <!-- Data Penumpang -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 mb-3">Penumpang</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="pnp_datang_dewasa" class="block text-sm font-medium text-gray-700 mb-2">
                            Datang - Dewasa
                        </label>
                        <input type="number" name="pnp_datang_dewasa" id="pnp_datang_dewasa" value="{{ old('pnp_datang_dewasa', 0) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="pnp_datang_anak" class="block text-sm font-medium text-gray-700 mb-2">
                            Datang - Anak
                        </label>
                        <input type="number" name="pnp_datang_anak" id="pnp_datang_anak" value="{{ old('pnp_datang_anak', 0) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="pnp_tolak_dewasa" class="block text-sm font-medium text-gray-700 mb-2">
                            Tolak - Dewasa
                        </label>
                        <input type="number" name="pnp_tolak_dewasa" id="pnp_tolak_dewasa" value="{{ old('pnp_tolak_dewasa', 0) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="pnp_tolak_anak" class="block text-sm font-medium text-gray-700 mb-2">
                            Tolak - Anak
                        </label>
                        <input type="number" name="pnp_tolak_anak" id="pnp_tolak_anak" value="{{ old('pnp_tolak_anak', 0) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Data Kendaraan -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Kendaraan</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Golongan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datang</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tolak</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol I (Motor)</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_datang_gol1" value="{{ old('kend_datang_gol1', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_tolak_gol1" value="{{ old('kend_tolak_gol1', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol II (Sedan/Jeep)</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_datang_gol2" value="{{ old('kend_datang_gol2', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_tolak_gol2" value="{{ old('kend_tolak_gol2', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol III (Minibus)</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_datang_gol3" value="{{ old('kend_datang_gol3', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_tolak_gol3" value="{{ old('kend_tolak_gol3', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol IVA (Bus Kecil)</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_datang_gol4a" value="{{ old('kend_datang_gol4a', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_tolak_gol4a" value="{{ old('kend_tolak_gol4a', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol IVB (Bus Besar)</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_datang_gol4b" value="{{ old('kend_datang_gol4b', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_tolak_gol4b" value="{{ old('kend_tolak_gol4b', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol V (Truk)</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_datang_gol5" value="{{ old('kend_datang_gol5', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="kend_tolak_gol5" value="{{ old('kend_tolak_gol5', 0) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <button type="button" @click="currentTab = 2"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Sebelumnya
                </button>
                <button type="button" @click="currentTab = 4"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Selanjutnya →
                </button>
            </div>
        </div>

        <!-- Tab 4: Data Muatan -->
        <div x-show="currentTab === 4" class="bg-white rounded-lg shadow p-6 space-y-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Data Muatan</h3>
                <button type="button" @click="addMuatan()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    + Tambah Muatan
                </button>
            </div>

            <!-- Lanjutan Muatan -->
            <div class="mb-6">
                <label for="lanjutan_ton" class="block text-sm font-medium text-gray-700 mb-2">
                    Lanjutan Muatan (Ton)
                </label>
                <input type="number" name="lanjutan_ton" id="lanjutan_ton" value="{{ old('lanjutan_ton') }}" min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">Muatan yang hanya transit (tidak dibongkar)</p>
            </div>

            <!-- Dynamic Muatan Rows -->
            <div class="space-y-4">
                <template x-for="(muatan, index) in muatans" :key="index">
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-medium text-gray-900">Muatan #<span x-text="index + 1"></span></h4>
                            <button type="button" @click="removeMuatan(index)"
                                    class="text-red-600 hover:text-red-900 text-sm">
                                Hapus
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipe <span class="text-red-500">*</span>
                                </label>
                                <select :name="'muatan[' + index + '][tipe]'" x-model="muatan.tipe" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="BONGKAR">Bongkar</option>
                                    <option value="MUAT">Muat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Barang <span class="text-red-500">*</span>
                                </label>
                                <input type="text" :name="'muatan[' + index + '][jenis_barang]'" x-model="muatan.jenis_barang" required
                                       placeholder="Contoh: Beras, Semen, dll"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ton/M³
                                </label>
                                <input type="number" :name="'muatan[' + index + '][ton_m3]'" x-model="muatan.ton_m3" min="0" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Hewan (jika ada)
                                </label>
                                <input type="text" :name="'muatan[' + index + '][jenis_hewan]'" x-model="muatan.jenis_hewan"
                                       placeholder="Contoh: Sapi, Ayam, dll"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Hewan (Ekor)
                                </label>
                                <input type="number" :name="'muatan[' + index + '][jumlah_hewan]'" x-model="muatan.jumlah_hewan" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="muatans.length === 0" class="text-center py-8 text-gray-500">
                    Belum ada data muatan. Klik "Tambah Muatan" untuk menambahkan.
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <button type="button" @click="currentTab = 3"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Sebelumnya
                </button>
                <button type="button" @click="currentTab = 5"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Selanjutnya →
                </button>
            </div>
        </div>

        <!-- Tab 5: Barang B3 -->
        <div x-show="currentTab === 5" class="bg-white rounded-lg shadow p-6 space-y-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Data Barang B3 (Bahan Berbahaya & Beracun)</h3>
                <button type="button" @click="addB3()"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                    + Tambah B3
                </button>
            </div>

            <!-- Dynamic B3 Rows -->
            <div class="space-y-4">
                <template x-for="(b3, index) in b3s" :key="index">
                    <div class="border border-orange-200 rounded-lg p-4 bg-orange-50">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-medium text-gray-900">Barang B3 #<span x-text="index + 1"></span></h4>
                            <button type="button" @click="removeB3(index)"
                                    class="text-red-600 hover:text-red-900 text-sm">
                                Hapus
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Barang B3 <span class="text-red-500">*</span>
                                </label>
                                <select :name="'b3[' + index + '][barang_b3_id]'" x-model="b3.barang_b3_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Barang B3 --</option>
                                    @foreach(\App\Models\BarangB3::orderBy('nama')->get() as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama }} ({{ $barang->un_number }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <select :name="'b3[' + index + '][jenis_kegiatan]'" x-model="b3.jenis_kegiatan" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="BONGKAR">Bongkar</option>
                                    <option value="MUAT">Muat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bentuk Muatan <span class="text-red-500">*</span>
                                </label>
                                <select :name="'b3[' + index + '][bentuk_muatan]'" x-model="b3.bentuk_muatan" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="CURAH">Curah</option>
                                    <option value="PADAT">Padat/Kemasan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah (Ton)
                                </label>
                                <input type="number" :name="'b3[' + index + '][jumlah_ton]'" x-model="b3.jumlah_ton" min="0" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Container
                                </label>
                                <input type="number" :name="'b3[' + index + '][jumlah_container]'" x-model="b3.jumlah_container" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kemasan
                                </label>
                                <input type="text" :name="'b3[' + index + '][kemasan]'" x-model="b3.kemasan"
                                       placeholder="Contoh: Drum, Jeriken, dll"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Kemasan
                                </label>
                                <input type="number" :name="'b3[' + index + '][jumlah]'" x-model="b3.jumlah" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Petugas
                                </label>
                                <input type="text" :name="'b3[' + index + '][petugas]'" x-model="b3.petugas"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="b3s.length === 0" class="text-center py-8 text-gray-500">
                    Tidak ada muatan B3. Klik "Tambah B3" jika ada muatan berbahaya.
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <button type="button" @click="currentTab = 4"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Sebelumnya
                </button>
                <button type="submit"
                        class="px-8 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                    💾 Simpan Data Kunjungan
                </button>
            </div>
        </div>

    </form>
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
