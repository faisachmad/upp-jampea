@extends('layouts.app')

@section('title', 'Tambah Pelabuhan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Pelabuhan</h1>
        <a href="{{ route('master.pelabuhan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('master.pelabuhan.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Kode -->
                <div>
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Pelabuhan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="kode"
                           id="kode"
                           value="{{ old('kode') }}"
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
                           value="{{ old('nama') }}"
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
                    <select name="tipe_pelabuhan_id"
                            id="tipe_pelabuhan_id"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tipe_pelabuhan_id') border-red-500 @enderror">
                        <option value="">-- Pilih Tipe --</option>
                        @foreach($tipes as $tipe)
                            <option value="{{ $tipe->id }}" {{ old('tipe_pelabuhan_id') == $tipe->id ? 'selected' : '' }}>{{ $tipe->nama }} ({{ $tipe->keterangan }})</option>
                        @endforeach
                    </select>
                    @error('tipe')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-900">Status Aktif</span>
                        <span class="text-xs text-gray-500">Aktifkan untuk menampilkan data ini</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
                <a href="{{ route('master.pelabuhan.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
