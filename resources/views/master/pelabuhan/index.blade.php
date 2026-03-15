@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .toggle-slider {
        background-color: #2563eb;
    }
    input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }
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

@section('title', 'Master Pelabuhan')

@section('content')
<div class="space-y-6" x-data="{
    editData: {},
    editAction: '',
    editTipeSelect: null,
    editPelabuhan(pelabuhan) {
        this.editData = { ...pelabuhan };
        this.editAction = '{{ route('master.pelabuhan.index') }}/' + pelabuhan.id;
        
        $dispatch('open-modal', 'edit-pelabuhan-modal');
        
        // Wait for modal to be visible/select to be targets
        this.$nextTick(() => {
            if (this.editTipeSelect) {
                this.editTipeSelect.clear();
                this.editTipeSelect.addItem(String(pelabuhan.tipe_pelabuhan_id));
            }
        });
    }
}">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Master Pelabuhan</h1>
        <button x-on:click="$dispatch('open-modal', 'tambah-pelabuhan-modal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Tambah Pelabuhan
        </button>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Search & Filter Form -->
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="GET" action="{{ route('master.pelabuhan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode atau nama..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="tipe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tipe</option>
                    @foreach($tipes as $tipe)
                        <option value="{{ $tipe->id }}" {{ request('tipe') == $tipe->id ? 'selected' : '' }}>{{ $tipe->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Cari
                </button>
                <a href="{{ route('master.pelabuhan.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @php
                        $sortableColumns = [
                            'kode' => 'Kode',
                            'nama' => 'Nama Pelabuhan',
                            'tipe' => 'Tipe',
                            'is_active' => 'Status'
                        ];
                    @endphp
                    @foreach($sortableColumns as $column => $label)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'direction' => request('sort') == $column && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                {{ $label }}
                                @if(request('sort') == $column)
                                    @if(request('direction') == 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 ml-1 text-gray-300 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                    @endforeach
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pelabuhans as $pelabuhan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $pelabuhan->kode }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $pelabuhan->nama }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @php
                            $badgeColors = [
                                'UPP' => 'bg-blue-100 text-blue-800',
                                'POSKER' => 'bg-green-100 text-green-800',
                                'WILKER' => 'bg-yellow-100 text-yellow-800',
                                'LUAR' => 'bg-gray-100 text-gray-800',
                            ];
                            $typeName = $pelabuhan->tipePelabuhan->nama ?? $pelabuhan->tipe;
                            $color = $badgeColors[$typeName] ?? 'bg-purple-100 text-purple-800';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                            {{ $typeName }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pelabuhan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $pelabuhan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">Aksi</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <button type="button"
                                        x-on:click="editPelabuhan({{ $pelabuhan }})"
                                        class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('Edit') }}
                                </button>

                                <form action="{{ route('master.pelabuhan.destroy', $pelabuhan) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-dropdown-link :href="route('master.pelabuhan.destroy', $pelabuhan)"
                                            onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); }">
                                        <span class="text-red-600">{{ __('Hapus') }}</span>
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data pelabuhan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <label for="per_page" class="text-sm text-gray-700">Tampilkan:</label>
            <select name="per_page" id="per_page" onchange="window.location.href = '{{ route('master.pelabuhan.index') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), per_page: this.value}).toString()" class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm text-gray-700">data per halaman</span>
        </div>
        <div>
            {{ $pelabuhans->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal Tambah Pelabuhan -->
    <x-modal name="tambah-pelabuhan-modal" :show="$errors->any()" maxWidth="2xl" :closeable="false">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Tambah Pelabuhan</h2>
                <button x-on:click="$dispatch('close-modal', 'tambah-pelabuhan-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('master.pelabuhan.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Kode (Hidden but kept for form consistency if needed, though model generates it) -->
                    <input type="hidden" name="kode" value="">

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
                        <div class="flex items-center justify-between mb-2">
                            <label for="tipe-select" class="block text-sm font-medium text-gray-700">
                                Tipe Pelabuhan <span class="text-red-500">*</span>
                            </label>
                            <button type="button" 
                                    x-on:click="$dispatch('open-modal', 'tambah-tipe-modal')"
                                    class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                + Tambah Tipe Baru
                            </button>
                        </div>
                        <select name="tipe_pelabuhan_id"
                                id="tipe-select"
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
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'tambah-pelabuhan-modal')" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Edit Pelabuhan -->
    <x-modal name="edit-pelabuhan-modal" :show="false" maxWidth="2xl" :closeable="false">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Edit Pelabuhan</h2>
                <button x-on:click="$dispatch('close-modal', 'edit-pelabuhan-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="editAction" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Kode (Locked) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Pelabuhan
                        </label>
                        <input type="text"
                               x-model="editData.kode"
                               disabled
                               class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pelabuhan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="edit_nama"
                               x-model="editData.nama"
                               required
                               maxlength="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Tipe -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="edit-tipe-select" class="block text-sm font-medium text-gray-700">
                                Tipe Pelabuhan <span class="text-red-500">*</span>
                            </label>
                            <button type="button" 
                                    x-on:click="$dispatch('open-modal', 'tambah-tipe-modal')"
                                    class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                + Tambah Tipe Baru
                            </button>
                        </div>
                        <select name="tipe_pelabuhan_id"
                                id="edit-tipe-select"
                                required
                                class="w-full">
                            <option value="">-- Pilih Tipe --</option>
                            @foreach($tipes as $tipe)
                                <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
                            @endforeach
                        </select>
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
                                   :checked="editData.is_active">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-pelabuhan-modal')" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Tambah Tipe Pelabuhan -->
    <x-modal name="tambah-tipe-modal" :show="false" maxWidth="md" :closeable="false">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Tambah Tipe Pelabuhan</h2>
                <button x-on:click="$dispatch('close-modal', 'tambah-tipe-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="form-tambah-tipe" x-on:submit.prevent="submitTipePelabuhan">
                <div class="space-y-4">
                    <!-- Nama Tipe -->
                    <div>
                        <label for="tipe_nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Tipe <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="tipe_nama"
                               required
                               maxlength="50"
                               placeholder="Contoh: UPP, POSKER, WILKER, LUAR"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Nama singkat untuk tipe pelabuhan</p>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="tipe_keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <input type="text"
                               name="keterangan"
                               id="tipe_keterangan"
                               maxlength="255"
                               placeholder="Deskripsi lengkap tipe pelabuhan"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div id="tipe-error-message" class="hidden p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg"></div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'tambah-tipe-modal')" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    let tipeSelect, editTipeSelect;
    
    document.addEventListener('DOMContentLoaded', function() {
        tipeSelect = new TomSelect('#tipe-select', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // Initialize Edit Tom Select
        editTipeSelect = new TomSelect('#edit-tipe-select', {
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // Wait for Alpine to be ready, then store editTipeSelect
        document.addEventListener('alpine:init', () => {
            // This runs when Alpine is initialized
        });
        
        // Alternative: use setTimeout to wait for Alpine
        setTimeout(() => {
            const alpineEl = document.querySelector('[x-data]');
            if (alpineEl && alpineEl.__x && alpineEl.__x.$data) {
                alpineEl.__x.$data.editTipeSelect = editTipeSelect;
            }
        }, 100);
    });
    
    // Define as window function so Alpine can access it
    window.submitTipePelabuhan = async function(event) {
        const form = event.target;
        const formData = new FormData(form);
        const errorDiv = document.getElementById('tipe-error-message');
        errorDiv.classList.add('hidden');
        
        try {
            const response = await fetch('{{ route('master.tipe-pelabuhan.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Add new option to both selects
                const newOption = {
                    value: data.data.id,
                    text: `${data.data.nama} (${data.data.keterangan || ''})`
                };
                
                tipeSelect.addOption(newOption);
                tipeSelect.addItem(data.data.id);
                
                editTipeSelect.addOption(newOption);
                
                // Close modal using Alpine's event system
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'tambah-tipe-modal' }));
                form.reset();
                
                // Show success message
                alert('Tipe pelabuhan berhasil ditambahkan!');
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            errorDiv.textContent = error.message || 'Gagal menambahkan tipe pelabuhan';
            errorDiv.classList.remove('hidden');
        }
    };
</script>
@endpush
@endsection
