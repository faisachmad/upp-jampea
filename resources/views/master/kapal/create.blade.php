<x-app-layout>
    <x-slot name="title">Tambah Kapal</x-slot>

    <div class="mb-6">
        <div class="mb-4">
            <a href="{{ route('master.kapal.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Kembali ke Daftar Kapal
            </a>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kapal Baru</h2>

        <!-- Form -->
        <form action="{{ route('master.kapal.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kapal -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kapal <span class="text-red-500">*</span></label>
                    <input type="text"
                           name="nama"
                           id="nama"
                           value="{{ old('nama') }}"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nama') border-red-500 @enderror">
                    @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kapal -->
                <div>
                    <label for="jenis_kapal_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kapal</label>
                    <x-searchable-select name="jenis_kapal_id" id="jenis_kapal_id" placeholder="-- Pilih Jenis Kapal --">
                        @foreach($jenisKapals as $jenisKapal)
                        <option value="{{ $jenisKapal->id }}" {{ old('jenis_kapal_id') == $jenisKapal->id ? 'selected' : '' }}>
                            {{ $jenisKapal->nama }} ({{ $jenisKapal->kode }})
                        </option>
                        @endforeach
                    </x-searchable-select>
                </div>

                <!-- GT -->
                <div>
                    <label for="gt" class="block text-sm font-medium text-gray-700 mb-1">GT (Gross Tonnage)</label>
                    <input type="number"
                           name="gt"
                           id="gt"
                           step="0.01"
                           value="{{ old('gt') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- DWT -->
                <div>
                    <label for="dwt" class="block text-sm font-medium text-gray-700 mb-1">DWT (Dead Weight Tonnage)</label>
                    <input type="number"
                           name="dwt"
                           id="dwt"
                           step="0.01"
                           value="{{ old('dwt') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Panjang -->
                <div>
                    <label for="panjang" class="block text-sm font-medium text-gray-700 mb-1">Panjang (m)</label>
                    <input type="number"
                           name="panjang"
                           id="panjang"
                           step="0.01"
                           value="{{ old('panjang') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tanda Selar -->
                <div>
                    <label for="tanda_selar" class="block text-sm font-medium text-gray-700 mb-1">Tanda Selar</label>
                    <input type="text"
                           name="tanda_selar"
                           id="tanda_selar"
                           value="{{ old('tanda_selar') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Call Sign -->
                <div>
                    <label for="call_sign" class="block text-sm font-medium text-gray-700 mb-1">Call Sign</label>
                    <input type="text"
                           name="call_sign"
                           id="call_sign"
                           value="{{ old('call_sign') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tempat Kedudukan -->
                <div>
                    <label for="tempat_kedudukan" class="block text-sm font-medium text-gray-700 mb-1">Tempat Kedudukan</label>
                    <input type="text"
                           name="tempat_kedudukan"
                           id="tempat_kedudukan"
                           value="{{ old('tempat_kedudukan') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Bendera -->
                <div>
                    <label for="bendera_id" class="block text-sm font-medium text-gray-700 mb-1">Bendera</label>
                    <x-searchable-select name="bendera_id" id="bendera_id" placeholder="-- Pilih Bendera --">
                        @foreach($benderas as $bendera)
                        <option value="{{ $bendera->id }}" {{ old('bendera_id', $benderas->where('kode', 'IDN')->first()->id ?? '') == $bendera->id ? 'selected' : '' }}>
                            {{ $bendera->nama_negara }} ({{ $bendera->kode }})
                        </option>
                        @endforeach
                    </x-searchable-select>
                </div>

                <!-- Pemilik/Agen -->
                <div class="md:col-span-2">
                    <label for="pemilik_agen" class="block text-sm font-medium text-gray-700 mb-1">Pemilik/Agen</label>
                    <input type="text"
                           name="pemilik_agen"
                           id="pemilik_agen"
                           value="{{ old('pemilik_agen') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <span class="ml-2 text-sm text-gray-700">Kapal Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('master.kapal.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
