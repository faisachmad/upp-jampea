@extends('layouts.app')

@section('title', 'Edit Nakhoda')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Edit Nakhoda</h1>
        <a href="{{ route('master.nakhoda.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('master.nakhoda.update', $nakhoda) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Nakhoda <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           id="nama"
                           value="{{ old('nama', $nakhoda->nama) }}"
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
                    <select name="kapal_id"
                            id="kapal_id"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kapal_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kapal --</option>
                        @foreach($kapals as $kapal)
                        <option value="{{ $kapal->id }}" {{ old('kapal_id', $nakhoda->kapal_id) == $kapal->id ? 'selected' : '' }}>
                            {{ $kapal->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('kapal_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $nakhoda->is_active) ? 'checked' : '' }}
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
                <a href="{{ route('master.nakhoda.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
