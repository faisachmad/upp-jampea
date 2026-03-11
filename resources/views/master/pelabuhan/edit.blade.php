@extends('layouts.app')

@section('title', 'Edit Pelabuhan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Edit Pelabuhan</h1>
        <a href="{{ route('master.pelabuhan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('master.pelabuhan.update', $pelabuhan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Kode -->
                <div>
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Pelabuhan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="kode"
                           id="kode"
                           value="{{ old('kode', $pelabuhan->kode) }}"
                           required
                           maxlength="10"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kode') border-red-500 @enderror">
                    @error('kode')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pelabuhan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           id="nama"
                           value="{{ old('nama', $pelabuhan->nama) }}"
                           required
                           maxlength="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror">
                    @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe -->
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Pelabuhan <span class="text-red-500">*</span>
                    </label>
                    <select name="tipe"
                            id="tipe"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tipe') border-red-500 @enderror">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="UPP" {{ old('tipe', $pelabuhan->tipe) == 'UPP' ? 'selected' : '' }}>UPP (Unit Penyelenggara Pelabuhan)</option>
                        <option value="POSKER" {{ old('tipe', $pelabuhan->tipe) == 'POSKER' ? 'selected' : '' }}>POSKER (Pos Pengawasan Kepelabuanan)</option>
                        <option value="WILKER" {{ old('tipe', $pelabuhan->tipe) == 'WILKER' ? 'selected' : '' }}>WILKER (Wilayah Kerja)</option>
                        <option value="LUAR" {{ old('tipe', $pelabuhan->tipe) == 'LUAR' ? 'selected' : '' }}>LUAR (Pelabuhan Luar Wilayah)</option>
                    </select>
                    @error('tipe')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $pelabuhan->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
                <a href="{{ route('master.pelabuhan.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
