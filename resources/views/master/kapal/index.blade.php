@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
    /* DataTables Custom Styling */
    #kapal-table_wrapper .dataTables_filter {
        display: none;
    }

    #kapal-table_wrapper .dataTables_length {
        display: block;
    }

    #kapal-table_wrapper .dataTables_length label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    #kapal-table_wrapper .dataTables_length select {
        padding: 0.375rem 1.75rem 0.375rem 0.75rem !important;
        font-size: 0.75rem !important;
        line-height: 1.25rem !important;
        color: #374151 !important;
        background-color: #fff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
        appearance: none !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: right 0.5rem center !important;
        background-repeat: no-repeat !important;
        background-size: 1.5em 1.5em !important;
        cursor: pointer;
    }

    #kapal-table_wrapper .dataTables_length select:focus {
        outline: none !important;
        border-color: #3b82f6 !important;
        ring: 2px !important;
        ring-color: #3b82f6 !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #e5e7eb !important;
    }

    #kapal-table thead th {
        background-color: #f9fafb;
        color: #374151;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    #kapal-table tbody td {
        padding: 0.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.75rem;
    }

    #kapal-table tbody tr:hover {
        background-color: #eff6ff !important;
        transition: background-color 0.2s;
    }

    /* Custom Pagination Styling */
    .dataTables_wrapper .dataTables_info {
        padding-top: 1.5rem !important;
        color: #6b7280 !important;
        font-size: 0.875rem !important;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1.5rem !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem !important;
        border: 1px solid #e5e7eb !important;
        background: white !important;
        color: #374151 !important;
        padding: 0.4rem 0.8rem !important;
        margin-left: 0.25rem !important;
        font-size: 0.875rem !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #2563eb !important;
        color: white !important;
        border-color: #2563eb !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        color: #2563eb !important;
        border-color: #d1d5db !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f9fafb !important;
        color: #9ca3af !important;
        cursor: not-allowed !important;
    }
</style>
@endpush

@section('title', 'Master Kapal')

@section('content')
<div class="space-y-6" x-data="{
    editData: {},
    editAction: '',
    jenisKapals: {{ json_encode($jenisKapals) }},
    benderas: {{ json_encode($benderas) }},
    editKapal(kapal) {
        this.editData = { ...kapal };
        this.editAction = '{{ route('master.kapal.index') }}/' + kapal.id;
        $dispatch('open-modal', 'edit-kapal-modal');
    },
    async saveJenisKapal(event) {
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route('master.kapal.store-jenis-kapal') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.jenisKapals.push(result.data);
                $dispatch('close-modal', 'tambah-jenis-kapal-modal');
                form.reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menyimpan data.'
            });
        }
    },
    async saveBendera(event) {
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route('master.kapal.store-bendera') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.benderas.push(result.data);
                $dispatch('close-modal', 'tambah-bendera-modal');
                form.reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menyimpan data.'
            });
        }
    }
}" @edit-kapal.window="editKapal($event.detail)">
    <!-- Alert Success (Handled globally) -->

    <!-- Search, Filter & Action Card -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <form id="filter-form" class="flex flex-col md:flex-row gap-3 w-full md:w-auto flex-1 items-center z-50">
            <div class="w-full md:w-72">
                <input type="text" name="search" id="search-input" placeholder="Nama, Call Sign, Pemilik..."
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-full md:w-48 relative z-50">
                <select name="jenis_kapal_id" id="jenis-filter" class="searchable-select w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisKapals as $jk)
                        <option value="{{ $jk->id }}">{{ $jk->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-48 relative z-50">
                <select name="status" id="status-filter" class="searchable-select w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
            <div class="flex w-full md:w-auto">
                <div class="inline-flex shadow-sm rounded-md" role="group">
                    <button type="button" id="btn-cari" class="px-4 py-1.5 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-l-md hover:bg-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500 transition-all">
                        Cari
                    </button>
                    <button type="button" id="btn-reset" class="px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-10 focus:ring-2 focus:ring-blue-500 transition-all">
                        Reset
                    </button>
                </div>
            </div>
        </form>

        <div class="w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-4 flex justify-end">
            <button x-on:click="$dispatch('open-modal', 'tambah-kapal-modal')" class="px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Kapal
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
        <div class="overflow-visible">
            <table id="kapal-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kapal</th>
                        <th>Jenis</th>
                        <th>Bendera</th>
                        <th>GT</th>
                        <th>Call Sign</th>
                        <th>Pemilik/Agen</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <!-- DataTables will fill this -->
                </tbody>
            </table>
        </div>
    </div>

<!-- Modal Tambah Kapal -->
<x-modal name="tambah-kapal-modal" :show="$errors->any() && !session('is_edit')" maxWidth="4xl">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Tambah Kapal Baru</h2>
            <button x-on:click="$dispatch('close-modal', 'tambah-kapal-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('master.kapal.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kapal -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kapal <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nama') border-red-500 @enderror">
                    @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kapal -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="jenis_kapal_id" class="block text-sm font-medium text-gray-700">Jenis Kapal</label>
                        <button type="button" x-on:click="$dispatch('open-modal', 'tambah-jenis-kapal-modal')"
                                class="text-xs text-blue-600 hover:text-blue-800">+ Tambah Baru</button>
                    </div>
                    <select name="jenis_kapal_id" id="jenis_kapal_id"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Jenis --</option>
                        <template x-for="jk in jenisKapals" :key="jk.id">
                            <option :value="jk.id" x-text="jk.nama"></option>
                        </template>
                    </select>
                </div>

                <!-- Bendera -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="bendera_id" class="block text-sm font-medium text-gray-700">Bendera</label>
                        <button type="button" x-on:click="$dispatch('open-modal', 'tambah-bendera-modal')"
                                class="text-xs text-blue-600 hover:text-blue-800">+ Tambah Baru</button>
                    </div>
                    <select name="bendera_id" id="bendera_id"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Bendera --</option>
                        <template x-for="bendera in benderas" :key="bendera.id">
                            <option :value="bendera.id" x-text="bendera.nama_negara"></option>
                        </template>
                    </select>
                </div>

                <!-- GT -->
                <div>
                    <label for="gt" class="block text-sm font-medium text-gray-700 mb-1">GT (Gross Tonnage)</label>
                    <input type="number" name="gt" id="gt" step="0.01" value="{{ old('gt') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- DWT -->
                <div>
                    <label for="dwt" class="block text-sm font-medium text-gray-700 mb-1">DWT (Dead Weight Tonnage)</label>
                    <input type="number" name="dwt" id="dwt" step="0.01" value="{{ old('dwt') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Panjang -->
                <div>
                    <label for="panjang" class="block text-sm font-medium text-gray-700 mb-1">Panjang (m)</label>
                    <input type="number" name="panjang" id="panjang" step="0.01" value="{{ old('panjang') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tanda Selar -->
                <div>
                    <label for="tanda_selar" class="block text-sm font-medium text-gray-700 mb-1">Tanda Selar</label>
                    <input type="text" name="tanda_selar" id="tanda_selar" value="{{ old('tanda_selar') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Call Sign -->
                <div>
                    <label for="call_sign" class="block text-sm font-medium text-gray-700 mb-1">Call Sign</label>
                    <input type="text" name="call_sign" id="call_sign" value="{{ old('call_sign') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tempat Kedudukan -->
                <div>
                    <label for="tempat_kedudukan" class="block text-sm font-medium text-gray-700 mb-1">Tempat Kedudukan</label>
                    <input type="text" name="tempat_kedudukan" id="tempat_kedudukan" value="{{ old('tempat_kedudukan') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Pemilik/Agen -->
                <div class="md:col-span-2">
                    <label for="pemilik_agen" class="block text-sm font-medium text-gray-700 mb-1">Pemilik/Agen</label>
                    <input type="text" name="pemilik_agen" id="pemilik_agen" value="{{ old('pemilik_agen') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <span class="ml-2 text-sm text-gray-700">Kapal Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'tambah-kapal-modal')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modal Edit Kapal -->
<x-modal name="edit-kapal-modal" :show="$errors->any() && session('is_edit')" maxWidth="4xl">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Edit Kapal</h2>
            <button x-on:click="$dispatch('close-modal', 'edit-kapal-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form :action="editAction" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kapal -->
                <div class="md:col-span-2">
                    <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kapal <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="edit_nama" x-model="editData.nama" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Jenis Kapal -->
                <div>
                    <label for="edit_jenis_kapal_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kapal</label>
                    <select name="jenis_kapal_id" id="edit_jenis_kapal_id" x-model="editData.jenis_kapal_id"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Jenis --</option>
                        <template x-for="jk in jenisKapals" :key="jk.id">
                            <option :value="jk.id" x-text="jk.nama"></option>
                        </template>
                    </select>
                </div>

                <!-- Bendera -->
                <div>
                    <label for="edit_bendera_id" class="block text-sm font-medium text-gray-700 mb-1">Bendera</label>
                    <select name="bendera_id" id="edit_bendera_id" x-model="editData.bendera_id"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Bendera --</option>
                        <template x-for="bendera in benderas" :key="bendera.id">
                            <option :value="bendera.id" x-text="bendera.nama_negara"></option>
                        </template>
                    </select>
                </div>

                <!-- GT -->
                <div>
                    <label for="edit_gt" class="block text-sm font-medium text-gray-700 mb-1">GT (Gross Tonnage)</label>
                    <input type="number" name="gt" id="edit_gt" step="0.01" x-model="editData.gt"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- DWT -->
                <div>
                    <label for="edit_dwt" class="block text-sm font-medium text-gray-700 mb-1">DWT (Dead Weight Tonnage)</label>
                    <input type="number" name="dwt" id="edit_dwt" step="0.01" x-model="editData.dwt"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Panjang -->
                <div>
                    <label for="edit_panjang" class="block text-sm font-medium text-gray-700 mb-1">Panjang (m)</label>
                    <input type="number" name="panjang" id="edit_panjang" step="0.01" x-model="editData.panjang"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tanda Selar -->
                <div>
                    <label for="edit_tanda_selar" class="block text-sm font-medium text-gray-700 mb-1">Tanda Selar</label>
                    <input type="text" name="tanda_selar" id="edit_tanda_selar" x-model="editData.tanda_selar"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Call Sign -->
                <div>
                    <label for="edit_call_sign" class="block text-sm font-medium text-gray-700 mb-1">Call Sign</label>
                    <input type="text" name="call_sign" id="edit_call_sign" x-model="editData.call_sign"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tempat Kedudukan -->
                <div>
                    <label for="edit_tempat_kedudukan" class="block text-sm font-medium text-gray-700 mb-1">Tempat Kedudukan</label>
                    <input type="text" name="tempat_kedudukan" id="edit_tempat_kedudukan" x-model="editData.tempat_kedudukan"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Pemilik/Agen -->
                <div class="md:col-span-2">
                    <label for="edit_pemilik_agen" class="block text-sm font-medium text-gray-700 mb-1">Pemilik/Agen</label>
                    <input type="text" name="pemilik_agen" id="edit_pemilik_agen" x-model="editData.pemilik_agen"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" x-bind:checked="editData.is_active"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <span class="ml-2 text-sm text-gray-700">Kapal Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'edit-kapal-modal')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modal Tambah Jenis Kapal -->
<x-modal name="tambah-jenis-kapal-modal" maxWidth="lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Tambah Jenis Kapal</h2>
            <button x-on:click="$dispatch('close-modal', 'tambah-jenis-kapal-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form x-on:submit.prevent="saveJenisKapal">
            <div class="space-y-4">
                <div>
                    <label for="jk_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="jk_nama" required maxlength="100"
                           placeholder="Contoh: Kapal Layar Motor"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <p class="mt-1 text-xs text-gray-500">Kode akan dibuat otomatis dari nama</p>
                </div>

                <div>
                    <label for="jk_keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" id="jk_keterangan" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'tambah-jenis-kapal-modal')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modal Tambah Bendera -->
<x-modal name="tambah-bendera-modal" maxWidth="lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Tambah Bendera</h2>
            <button x-on:click="$dispatch('close-modal', 'tambah-bendera-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form x-on:submit.prevent="saveBendera">
            <div class="space-y-4">
                <div>
                    <label for="b_kode" class="block text-sm font-medium text-gray-700 mb-1">Kode (ISO 3) <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" id="b_kode" required maxlength="3" placeholder="IDN"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="b_nama_negara" class="block text-sm font-medium text-gray-700 mb-1">Nama Negara <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_negara" id="b_nama_negara" required maxlength="100" placeholder="Indonesia"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'tambah-bendera-modal')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-modal>

</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    let table;
    $(document).ready(function() {
        table = $('#kapal-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            ajax: {
                url: "{{ route('master.kapal.index') }}",
                data: function (d) {
                    d.jenis_kapal_id = $('#jenis-filter').val();
                    d.status = $('#status-filter').val();
                    d.search_custom = $('#search-input').val();
                }
            },
            dom: "<'flex flex-col'<'w-full overflow-visible't><'flex flex-col md:flex-row justify-between items-center p-4 gap-4'<'flex items-center gap-6'li>p>>",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama', name: 'nama'},
                {data: 'jenis_kapal_nama', name: 'jenis_kapal_nama'},
                {data: 'bendera_nama', name: 'bendera_nama'},
                {
                    data: 'gt',
                    name: 'gt',
                    render: function(data) {
                        return data ? parseFloat(data).toFixed(2) : '-';
                    }
                },
                {data: 'call_sign', name: 'call_sign'},
                {data: 'pemilik_agen', name: 'pemilik_agen'},
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data) {
                        const color = data ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                        const text = data ? 'Aktif' : 'Tidak Aktif';
                        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${color}">${text}</span>`;
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            responsive: true,
            autoWidth: false
        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        $('#btn-cari').on('click', function() {
            table.ajax.reload();
        });

        $('#btn-reset').on('click', function() {
            $('#filter-form')[0].reset();
            table.ajax.reload();
        });

        $('#search-input').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                table.ajax.reload();
            }
        });

        // Export edit function to window
        // Removed window.editKapal as it is now event-based
    });
</script>
@endpush
@endsection
