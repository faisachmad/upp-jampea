@extends('layouts.app')

@section('title', 'Edit Jenis Kapal')

@section('content')
<div class="mb-6">
    <div class="mb-4">
        <a href="{{ route('master.jenis-kapal.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Kembali ke Daftar Jenis Kapal
        </a>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Jenis Kapal</h2>

    <!-- Form -->
    <form action="{{ route('master.jenis-kapal.update', $jenisKapal) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Kode (Read-only) -->
            <div>
                <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">
                    Kode
                </label>
                <input type="text"
                       id="kode"
                       value="{{ $jenisKapal->kode }}"
                       readonly
                       disabled
                       class="w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm cursor-not-allowed">
                <p class="mt-1 text-xs text-gray-500">Kode akan diperbarui otomatis jika nama diubah</p>
            </div>

            <!-- Nama -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Jenis Kapal <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama"
                       id="nama"
                       value="{{ old('nama', $jenisKapal->nama) }}"
                       required
                       maxlength="100"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nama') border-red-500 @enderror">
                @error('nama')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                    Keterangan
                </label>
                <textarea name="keterangan"
                          id="keterangan"
                          rows="4"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $jenisKapal->keterangan) }}</textarea>
                @error('keterangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           {{ old('is_active', $jenisKapal->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <span class="ml-2 text-sm text-gray-700">Jenis Kapal Aktif</span>
                </label>
            </div>

            <!-- Info Usage -->
            @if($jenisKapal->kapals_count > 0)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Jenis kapal ini digunakan oleh {{ $jenisKapal->kapals_count }} kapal
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Perubahan akan mempengaruhi data kapal yang terkait.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Submit Buttons -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('master.jenis-kapal.index') }}"
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
