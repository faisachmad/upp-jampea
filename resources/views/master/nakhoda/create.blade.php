@extends('layouts.app')

@section('title', 'Tambah Nakhoda')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Nakhoda</h1>
        <a href="{{ route('master.nakhoda.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('master.nakhoda.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Nakhoda <span class="text-red-500">*</span>
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

                <!-- Kapal -->
                <div>
                    <label for="kapal_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kapal <span class="text-red-500">*</span>
                    </label>
                    <x-searchable-select name="kapal_id" id="kapal_id" required placeholder="-- Pilih Kapal --">
                        @foreach($kapals as $kapal)
                        <option value="{{ $kapal->id }}" {{ old('kapal_id') == $kapal->id ? 'selected' : '' }}>
                            {{ $kapal->nama }}
                        </option>
                        @endforeach
                    </x-searchable-select>
                    @error('kapal_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-900">Status Aktif</span>
                        <span class="text-xs text-gray-500">Aktifkan untuk menampilkan data nakhoda ini</span>
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
                <a href="{{ route('master.nakhoda.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
