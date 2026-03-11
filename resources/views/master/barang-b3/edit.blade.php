@extends('layouts.app')

@section('title', 'Edit Barang B3')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Edit Barang B3</h1>
        <a href="{{ route('master.barang-b3.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('master.barang-b3.update', $barangB3) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           id="nama"
                           value="{{ old('nama', $barangB3->nama) }}"
                           required
                           maxlength="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror">
                    @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- UN Number -->
                <div>
                    <label for="un_number" class="block text-sm font-medium text-gray-700 mb-2">
                        UN Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="un_number"
                           id="un_number"
                           value="{{ old('un_number', $barangB3->un_number) }}"
                           required
                           maxlength="10"
                           placeholder="Contoh: UN1234"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('un_number') border-red-500 @enderror">
                    @error('un_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Kode klasifikasi UN untuk bahan berbahaya</p>
                </div>

                <!-- Kelas -->
                <div>
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="kelas"
                           id="kelas"
                           value="{{ old('kelas', $barangB3->kelas) }}"
                           required
                           maxlength="10"
                           placeholder="Contoh: 3, 6.1, 8"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kelas') border-red-500 @enderror">
                    @error('kelas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Kelas bahaya sesuai klasifikasi UN</p>
                </div>

                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori
                    </label>
                    <input type="text"
                           name="kategori"
                           id="kategori"
                           value="{{ old('kategori', $barangB3->kategori) }}"
                           maxlength="50"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-500 @enderror">
                    @error('kategori')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
                <a href="{{ route('master.barang-b3.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
