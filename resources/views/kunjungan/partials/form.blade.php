<form action="{{ route('kunjungan.store') }}" method="POST">
    @csrf

    <!-- Error Summary -->
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <strong class="font-bold">Terdapat kesalahan!</strong>
        <ul class="mt-2 ml-4 list-disc">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tab Navigation -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                    <button type="button" @click="currentTab = 1"
                        class="px-4 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap"
                        :class="currentTab === 1 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    1. Data Kunjungan
                </button>
                <button type="button" @click="currentTab = 2"
                        class="px-4 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap"
                        :class="currentTab === 2 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    2. Kedatangan & Keberangkatan
                </button>
                <button type="button" @click="currentTab = 3"
                        class="px-4 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap"
                        :class="currentTab === 3 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    3. Penumpang & Kendaraan
                </button>
                <button type="button" @click="currentTab = 4"
                        class="px-4 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap"
                        :class="currentTab === 4 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    4. Data Muatan
                </button>
                <button type="button" @click="currentTab = 5"
                        class="px-4 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap"
                        :class="currentTab === 5 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    5. Barang B3
                </button>
            </nav>
        </div>
    </div>

    <div x-show="currentTab === 1" class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 space-y-4">
        <h3 class="text-sm font-bold text-gray-900 mb-4 border-b pb-1.5">Data Kunjungan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pelabuhan -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="pelabuhan_id" class="block text-sm font-medium text-gray-700">
                        Pelabuhan Pencatat <span class="text-red-500">*</span>
                    </label>
                    <button type="button" onclick="openQuickAddPelabuhan()"
                            class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah
                    </button>
                </div>
                <select name="pelabuhan_id" id="pelabuhan_id" required
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('pelabuhan_id') border-red-500 @enderror">
                    <option value="">-- Pilih Pelabuhan --</option>
                    @foreach($pelabuhans as $pelabuhan)
                    <option value="{{ $pelabuhan->id }}" {{ old('pelabuhan_id') == $pelabuhan->id ? 'selected' : '' }}>
                        {{ $pelabuhan->nama }} ({{ $pelabuhan->kode ?? '-' }})
                    </option>
                    @endforeach
                </select>
                @error('pelabuhan_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Pelayaran -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="jenis_pelayaran_id" class="block text-sm font-medium text-gray-700">
                        Jenis Pelayaran <span class="text-red-500">*</span>
                    </label>
                    <a href="{{ route('master.jenis-pelayaran.index') }}" target="_blank"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">Kelola →</a>
                </div>
                <select name="jenis_pelayaran_id" id="jenis_pelayaran_id" required
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('jenis_pelayaran_id') border-red-500 @enderror">
                    <option value="">-- Pilih Jenis Pelayaran --</option>
                    @foreach($jenisPelayarans as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_pelayaran_id') == $jenis->id ? 'selected' : '' }}>
                        [{{ $jenis->prefix }}] {{ $jenis->nama }}
                    </option>
                    @endforeach
                </select>
                @error('jenis_pelayaran_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kapal (with autocomplete) -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">
                        Kapal <span class="text-red-500">*</span>
                    </label>
                    <a href="{{ route('master.kapal.create') }}" target="_blank"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Kapal
                    </a>
                </div>
                <div class="relative" x-data="autocomplete('{{ route('api.kapal.search') }}', 'kapal_id')">
                    <input type="text"
                           x-model="searchQuery"
                           @input.debounce.300ms="search()"
                           @focus="showResults = true"
                           @click.away="showResults = false"
                           placeholder="Ketik nama kapal..."
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <input type="hidden" name="kapal_id" x-model="selectedId" required>
                    <div x-show="showResults && results.length > 0"
                         class="absolute z-20 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                        <template x-for="result in results" :key="result.id">
                            <div @click="selectItem(result)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer">
                                <div class="text-sm font-medium text-gray-900" x-text="result.nama"></div>
                                <div class="text-xs text-gray-500">
                                    <span x-text="result.jenis"></span> &mdash; GT: <span x-text="result.gt"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                @error('kapal_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Nakhoda (fixed autocomplete API) -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">
                        Nakhoda <span class="text-red-500">*</span>
                    </label>
                    <button type="button" onclick="openQuickAddNakhoda()"
                            class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Nakhoda
                    </button>
                </div>
                <div class="relative" x-data="autocompleteNakhoda()">
                    <input type="text"
                           id="nakhoda_search_input"
                           x-model="searchQuery"
                           @input.debounce.300ms="search()"
                           @focus="showResults = true"
                           @click.away="showResults = false"
                           placeholder="Ketik nama nakhoda..."
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <input type="hidden" name="nakhoda_id" id="nakhoda_id" x-model="nakhodaId" required>
                    <div x-show="showResults && results.length > 0"
                         class="absolute z-20 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                        <template x-for="result in results" :key="result.id">
                            <div @click="selectNakhoda(result)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer">
                                <div class="text-sm font-medium text-gray-900" x-text="result.nama"></div>
                                <div class="text-xs text-gray-500" x-text="result.kapal_nama ? 'Kapal: ' + result.kapal_nama : 'Tanpa kapal'"></div>
                            </div>
                        </template>
                    </div>
                </div>
                @error('nakhoda_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Bulan -->
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">
                    Bulan <span class="text-red-500">*</span>
                </label>
                <select name="bulan" id="bulan" required
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('bulan') border-red-500 @enderror">
                    <option value="">-- Pilih Bulan --</option>
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ old('bulan', date('n')) == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                    @endfor
                </select>
                @error('bulan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">
                    Tahun <span class="text-red-500">*</span>
                </label>
                <select name="tahun" id="tahun" required
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('tahun') border-red-500 @enderror">
                    @for($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{ $year }}" {{ old('tahun', date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
                @error('tahun')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" @click="currentTab = 2"
                    class="px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">
                Selanjutnya →
            </button>
        </div>
    </div>

    <!-- Tab  2: Keda

tangan & Keberangkatan -->
    <div x-show="currentTab === 2" class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 space-y-4">
        <h3 class="text-sm font-bold text-gray-900 mb-4 border-b pb-1.5">Data Kedatangan & Keberangkatan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Kedatangan -->
            <div class="space-y-4 md:border-r md:pr-6">
                <h4 class="font-medium text-gray-900 mb-3">Kedatangan Kapal</h4>

                <div>
                    <label for="tgl_datang" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Datang <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_datang" id="tgl_datang" required value="{{ old('tgl_datang') }}"
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('tgl_datang') border-red-500 @enderror">
                    @error('tgl_datang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jam_datang" class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Datang <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_datang" id="jam_datang" required value="{{ old('jam_datang') }}"
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('jam_datang') border-red-500 @enderror">
                    @error('jam_datang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Pelabuhan Asal <span class="text-red-500">*</span>
                    </label>
                    <div x-data="autocomplete('{{ route('api.pelabuhan.search') }}', 'pelabuhan_asal_id')">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="search()"
                               @focus="showResults = true"
                               @click.away="showResults = false"
                               placeholder="Ketik nama pelabuhan asal..."
                               class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
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
                    @error('pelabuhan_asal_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_spb_datang" class="block text-sm font-medium text-gray-700 mb-1">
                        No. SPB Datang
                    </label>
                    <input type="text" name="no_spb_datang" id="no_spb_datang" value="{{ old('no_spb_datang') }}"
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Data Keberangkatan -->
            <div class="space-y-4 md:pl-6">
                <h4 class="font-medium text-gray-900 mb-3">Keberangkatan Kapal</h4>

                <div>
                    <label for="tgl_tolak" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Tolak <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_tolak" id="tgl_tolak" required value="{{ old('tgl_tolak') }}"
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('tgl_tolak') border-red-500 @enderror">
                    @error('tgl_tolak')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jam_tolak" class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Tolak <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_tolak" id="jam_tolak" required value="{{ old('jam_tolak') }}"
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('jam_tolak') border-red-500 @enderror">
                    @error('jam_tolak')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Pelabuhan Tujuan <span class="text-red-500">*</span>
                    </label>
                    <div x-data="autocomplete('{{ route('api.pelabuhan.search') }}', 'pelabuhan_tujuan_id')">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="search()"
                               @focus="showResults = true"
                               @click.away="showResults = false"
                               placeholder="Ketik nama pelabuhan tujuan..."
                               class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
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
                    @error('pelabuhan_tujuan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_spb_tolak" class="block text-sm font-medium text-gray-700 mb-1">
                        No. SPB Tolak
                    </label>
                    <input type="text" name="no_spb_tolak" id="no_spb_tolak" value="{{ old('no_spb_tolak') }}"
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex justify-between pt-4">
            <button type="button" @click="currentTab = 1"
                    class="px-3 py-1.5 text-xs font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                ← Sebelumnya
            </button>
            <button type="button" @click="currentTab = 3"
                    class="px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">
                Selanjutnya →
            </button>
        </div>
    </div>

    <!-- Tab 3: Penumpang & Kendaraan -->
    <div x-show="currentTab === 3" class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 space-y-4">
        <h3 class="text-sm font-bold text-gray-900 mb-4 border-b pb-1.5">Data Penumpang & Kendaraan</h3>

        <!-- Data Penumpang -->
        <div class="mb-6">
            <h4 class="font-medium text-gray-900 mb-3">Penumpang</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label for="pnp_datang_dewasa" class="block text-sm font-medium text-gray-700 mb-1">
                        Datang - Dewasa
                    </label>
                    <input type="number" name="pnp_datang_dewasa" id="pnp_datang_dewasa" value="{{ old('pnp_datang_dewasa', 0) }}" min="0"
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="pnp_datang_anak" class="block text-sm font-medium text-gray-700 mb-1">
                        Datang - Anak
                    </label>
                    <input type="number" name="pnp_datang_anak" id="pnp_datang_anak" value="{{ old('pnp_datang_anak', 0) }}" min="0"
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="pnp_tolak_dewasa" class="block text-sm font-medium text-gray-700 mb-1">
                        Tolak - Dewasa
                    </label>
                    <input type="number" name="pnp_tolak_dewasa" id="pnp_tolak_dewasa" value="{{ old('pnp_tolak_dewasa', 0) }}" min="0"
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="pnp_tolak_anak" class="block text-sm font-medium text-gray-700 mb-1">
                        Tolak - Anak
                    </label>
                    <input type="number" name="pnp_tolak_anak" id="pnp_tolak_anak" value="{{ old('pnp_tolak_anak', 0) }}" min="0"
                           class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
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
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_tolak_gol1" value="{{ old('kend_tolak_gol1', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol II (Sedan/Jeep)</td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_datang_gol2" value="{{ old('kend_datang_gol2', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_tolak_gol2" value="{{ old('kend_tolak_gol2', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol III (Minibus)</td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_datang_gol3" value="{{ old('kend_datang_gol3', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_tolak_gol3" value="{{ old('kend_tolak_gol3', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol IVA (Bus Kecil)</td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_datang_gol4a" value="{{ old('kend_datang_gol4a', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_tolak_gol4a" value="{{ old('kend_tolak_gol4a', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol IVB (Bus Besar)</td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_datang_gol4b" value="{{ old('kend_datang_gol4b', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_tolak_gol4b" value="{{ old('kend_tolak_gol4b', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Gol V (Truk)</td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_datang_gol5" value="{{ old('kend_datang_gol5', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="kend_tolak_gol5" value="{{ old('kend_tolak_gol5', 0) }}" min="0"
                                       class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-between pt-4">
            <button type="button" @click="currentTab = 2"
                    class="px-3 py-1.5 text-xs font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                ← Sebelumnya
            </button>
            <button type="button" @click="currentTab = 4"
                    class="px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">
                Selanjutnya →
            </button>
        </div>
    </div>

    <div x-show="currentTab === 4" class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 space-y-4">
        <div class="flex justify-between items-center mb-4 border-b pb-1.5">
            <h3 class="text-sm font-bold text-gray-900">Data Muatan</h3>
            <button type="button" @click="addMuatan()"
                    class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700 transition-all">
                + Tambah Muatan
            </button>
        </div>

        <!-- Lanjutan Muatan -->
        <div class="mb-6">
            <label for="lanjutan_ton" class="block text-sm font-medium text-gray-700 mb-1">
                Lanjutan Muatan (Ton)
            </label>
            <input type="number" name="lanjutan_ton" id="lanjutan_ton" value="{{ old('lanjutan_ton') }}" min="0" step="0.01"
                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            <p class="mt-1 text-xs text-gray-500">Muatan yang hanya transit (tidak dibongkar)</p>
        </div>

        <!-- Dynamic Muatan Rows -->
        <div class="space-y-4">
            <template x-for="(muatan, index) in muatans" :key="index">
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium text-gray-900">Muatan #<span x-text="index + 1"></span></h4>
                        <button type="button" @click="removeMuatan(index)"
                                class="text-red-600 hover:text-red-900 text-xs">
                            Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tipe <span class="text-red-500">*</span>
                            </label>
                            <select :name="'muatan[' + index + '][tipe]'" x-model="muatan.tipe" required
                                    class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                <option value="BONGKAR">Bongkar</option>
                                <option value="MUAT">Muat</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" :name="'muatan[' + index + '][jenis_barang]'" x-model="muatan.jenis_barang" required
                                   placeholder="Contoh: Beras, Semen, dll"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ton/M³
                            </label>
                            <input type="number" :name="'muatan[' + index + '][ton_m3]'" x-model="muatan.ton_m3" min="0" step="0.01"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Hewan (jika ada)
                            </label>
                            <input type="text" :name="'muatan[' + index + '][jenis_hewan]'" x-model="muatan.jenis_hewan"
                                   placeholder="Contoh: Sapi, Ayam, dll"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Hewan (Ekor)
                            </label>
                            <input type="number" :name="'muatan[' + index + '][jumlah_hewan]'" x-model="muatan.jumlah_hewan" min="0"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
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
                    class="px-3 py-1.5 text-xs font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                ← Sebelumnya
            </button>
            <button type="button" @click="currentTab = 5"
                    class="px-3 py-1.5 text-xs font-medium rounded-md transition-all">
                Selanjutnya →
            </button>
        </div>
    </div>

    <div x-show="currentTab === 5" class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 space-y-4">
        <div class="flex justify-between items-center mb-4 border-b pb-1.5">
            <h3 class="text-sm font-bold text-gray-900">Data Barang B3 (Bahan Berbahaya & Beracun)</h3>
            <div class="flex items-center gap-3">
                <button type="button" onclick="openQuickAddB3()"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Jenis B3 Baru
                </button>
                <button type="button" @click="addB3()"
                        class="px-3 py-1.5 bg-orange-600 text-white text-xs font-medium rounded-md hover:bg-orange-700 transition-all">
                    + Tambah B3
                </button>
            </div>
        </div>

        <!-- Dynamic B3 Rows -->
        <div class="space-y-4">
            <template x-for="(b3, index) in b3s" :key="index">
                <div class="border border-orange-200 rounded-lg p-4 bg-orange-50">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium text-gray-900">Barang B3 #<span x-text="index + 1"></span></h4>
                        <button type="button" @click="removeB3(index)"
                                class="text-red-600 hover:text-red-900 text-xs">
                            Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Barang B3 <span class="text-red-500">*</span>
                            </label>
                            <select :name="'b3[' + index + '][barang_b3_id]'" x-model="b3.barang_b3_id" required
                                    class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Barang B3 --</option>
                                @foreach(\App\Models\BarangB3::orderBy('nama')->get() as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama }} ({{ $barang->un_number }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <select :name="'b3[' + index + '][jenis_kegiatan]'" x-model="b3.jenis_kegiatan" required
                                    class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                <option value="BONGKAR">Bongkar</option>
                                <option value="MUAT">Muat</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Bentuk Muatan <span class="text-red-500">*</span>
                            </label>
                            <select :name="'b3[' + index + '][bentuk_muatan]'" x-model="b3.bentuk_muatan" required
                                    class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                <option value="CURAH">Curah</option>
                                <option value="PADAT">Padat/Kemasan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah (Ton)
                            </label>
                            <input type="number" :name="'b3[' + index + '][jumlah_ton]'" x-model="b3.jumlah_ton" min="0" step="0.01"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Container
                            </label>
                            <input type="number" :name="'b3[' + index + '][jumlah_container]'" x-model="b3.jumlah_container" min="0"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kemasan
                            </label>
                            <input type="text" :name="'b3[' + index + '][kemasan]'" x-model="b3.kemasan"
                                   placeholder="Contoh: Drum, Jeriken, dll"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Kemasan
                            </label>
                            <input type="number" :name="'b3[' + index + '][jumlah]'" x-model="b3.jumlah" min="0"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Petugas
                            </label>
                            <input type="text" :name="'b3[' + index + '][petugas]'" x-model="b3.petugas"
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
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
                    class="px-3 py-1.5 text-xs font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                ← Sebelumnya
            </button>
            <button type="submit"
                    class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-md hover:bg-green-700 transition-all shadow-sm">
                💾 Simpan Data Kunjungan
            </button>
        </div>
    </div>

</form>

{{-- ===== QUICK-ADD MODALS ===== --}}

{{-- Modal: Quick Add Pelabuhan --}}
<div id="modal-quick-pelabuhan" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-bold text-gray-900">Tambah Pelabuhan Baru</h3>
            <button type="button" onclick="closeQuickAddPelabuhan()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="form-quick-pelabuhan" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                <input type="text" name="kode" maxlength="10" required placeholder="Mis: JMP"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="nama" required placeholder="Nama pelabuhan"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
            <input type="hidden" name="tipe_pelabuhan_id" value="1">
            <input type="hidden" name="is_active" value="1">
            <div id="error-quick-pelabuhan" class="text-xs text-red-600 hidden"></div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeQuickAddPelabuhan()" class="px-3 py-1.5 text-xs bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Quick Add Nakhoda --}}
<div id="modal-quick-nakhoda" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-bold text-gray-900">Tambah Nakhoda Baru</h3>
            <button type="button" onclick="closeQuickAddNakhoda()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="form-quick-nakhoda" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Nakhoda <span class="text-red-500">*</span></label>
                <input type="text" name="nama" required placeholder="Nama lengkap nakhoda"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
            <input type="hidden" name="is_active" value="1">
            <div id="error-quick-nakhoda" class="text-xs text-red-600 hidden"></div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeQuickAddNakhoda()" class="px-3 py-1.5 text-xs bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Quick Add Barang B3 --}}
<div id="modal-quick-b3" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-bold text-gray-900">Tambah Barang B3 Baru</h3>
            <button type="button" onclick="closeQuickAddB3()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="form-quick-b3" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                <input type="text" name="nama" required placeholder="Nama barang B3"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">UN Number</label>
                <input type="text" name="un_number" placeholder="Mis: UN1203"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
            <div id="error-quick-b3" class="text-xs text-red-600 hidden"></div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeQuickAddB3()" class="px-3 py-1.5 text-xs bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-3 py-1.5 text-xs bg-orange-600 text-white rounded-md hover:bg-orange-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
// ===== AUTOCOMPLETE NAKHODA (using API) =====
function autocompleteNakhoda() {
    return {
        searchQuery: '',
        nakhodaId: '',
        results: [],
        showResults: false,
        async search() {
            if (this.searchQuery.length < 2) { this.results = []; return; }
            try {
                const r = await fetch(`{{ route('api.nakhoda.search') }}?q=${encodeURIComponent(this.searchQuery)}`);
                this.results = await r.json();
                this.showResults = true;
            } catch(e) { this.results = []; }
        },
        selectNakhoda(item) {
            this.searchQuery = item.nama;
            this.nakhodaId = item.id;
            this.showResults = false;
        }
    };
}

// ===== QUICK ADD PELABUHAN =====
function openQuickAddPelabuhan() { document.getElementById('modal-quick-pelabuhan').classList.remove('hidden'); }
function closeQuickAddPelabuhan() { document.getElementById('modal-quick-pelabuhan').classList.add('hidden'); }

document.getElementById('form-quick-pelabuhan').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    const errEl = document.getElementById('error-quick-pelabuhan');
    errEl.classList.add('hidden');
    try {
        const fd = new FormData(this);
        const res = await fetch('{{ route('master.pelabuhan.store') }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd
        });
        const data = await res.json();
        if (data.success) {
            const sel = document.getElementById('pelabuhan_id');
            const opt = new Option(data.data.nama + ' (' + (data.data.kode ?? '-') + ')', data.data.id, true, true);
            sel.add(opt);
            this.reset();
            closeQuickAddPelabuhan();
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message ?? 'Gagal menyimpan.');
            errEl.textContent = msgs;
            errEl.classList.remove('hidden');
        }
    } catch(e) { errEl.textContent = 'Terjadi kesalahan.'; errEl.classList.remove('hidden'); }
    btn.disabled = false;
    btn.textContent = 'Simpan';
});

// ===== QUICK ADD NAKHODA =====
function openQuickAddNakhoda() { document.getElementById('modal-quick-nakhoda').classList.remove('hidden'); }
function closeQuickAddNakhoda() { document.getElementById('modal-quick-nakhoda').classList.add('hidden'); }

document.getElementById('form-quick-nakhoda').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    const errEl = document.getElementById('error-quick-nakhoda');
    errEl.classList.add('hidden');
    try {
        const fd = new FormData(this);
        const res = await fetch('{{ route('master.nakhoda.store') }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd
        });
        const data = await res.json();
        if (data.success) {
            // Set the hidden nakhoda_id and visible search input
            document.getElementById('nakhoda_id').value = data.data.id;
            document.getElementById('nakhoda_search_input').value = data.data.nama;
            this.reset();
            closeQuickAddNakhoda();
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message ?? 'Gagal menyimpan.');
            errEl.textContent = msgs;
            errEl.classList.remove('hidden');
        }
    } catch(e) { errEl.textContent = 'Terjadi kesalahan.'; errEl.classList.remove('hidden'); }
    btn.disabled = false;
    btn.textContent = 'Simpan';
});

// ===== QUICK ADD BARANG B3 =====
function openQuickAddB3() { document.getElementById('modal-quick-b3').classList.remove('hidden'); }
function closeQuickAddB3() { document.getElementById('modal-quick-b3').classList.add('hidden'); }

document.getElementById('form-quick-b3').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    const errEl = document.getElementById('error-quick-b3');
    errEl.classList.add('hidden');
    try {
        const fd = new FormData(this);
        const res = await fetch('{{ route('master.barang-b3.store') }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd
        });
        const data = await res.json();
        if (data.success) {
            // Add new option to all B3 selects on the page
            document.querySelectorAll('select[name$="[barang_b3_id]"]').forEach(sel => {
                const opt = new Option(data.data.nama + ' (' + (data.data.un_number ?? '-') + ')', data.data.id);
                sel.add(opt);
            });
            this.reset();
            closeQuickAddB3();
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message ?? 'Gagal menyimpan.');
            errEl.textContent = msgs;
            errEl.classList.remove('hidden');
        }
    } catch(e) { errEl.textContent = 'Terjadi kesalahan.'; errEl.classList.remove('hidden'); }
    btn.disabled = false;
    btn.textContent = 'Simpan';
});
</script>
