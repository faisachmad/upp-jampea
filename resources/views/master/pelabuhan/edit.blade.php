@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Tom Select overrides */
    .ts-control {
        border-radius: 0.5rem !important;
        padding: 0.5rem 1rem !important;
        border-color: #d1d5db !important;
    }
    .ts-control:focus {
        ring: 2px !important;
        ring-color: #3b82f6 !important;
    }
</style>
@endpush

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
                <!-- Kode (Read-only for context, but can be hidden if preferred) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Pelabuhan
                    </label>
                    <input type="text"
                           value="{{ $pelabuhan->kode }}"
                           disabled
                           class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                    <input type="hidden" name="kode" value="{{ $pelabuhan->kode }}">
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

                <div>
                    <label for="tipe-select" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Pelabuhan <span class="text-red-500">*</span>
                    </label>
                    <select name="tipe_pelabuhan_id"
                            id="tipe-select"
                            required
                            class="w-full">
                        <option value="">-- Pilih Tipe --</option>
                        @foreach($tipes as $tipe)
                            <option value="{{ $tipe->id }}" {{ old('tipe_pelabuhan_id', $pelabuhan->tipe_pelabuhan_id) == $tipe->id ? 'selected' : '' }}>
                                {{ $tipe->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipe_pelabuhan_id')
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
                               {{ old('is_active', $pelabuhan->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#tipe-select', {
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });
</script>
@endpush
@endsection
