@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
    /* DataTables Custom Styling */
    #pelabuhan-table_wrapper .dataTables_filter {
        display: none;
    }

    #pelabuhan-table_wrapper .dataTables_length {
        display: block;
    }

    #pelabuhan-table_wrapper .dataTables_length label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    #pelabuhan-table_wrapper .dataTables_length select {
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

    #pelabuhan-table_wrapper .dataTables_length select:focus {
        outline: none !important;
        border-color: #3b82f6 !important;
        ring: 2px !important;
        ring-color: #3b82f6 !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #e5e7eb !important;
    }

    #pelabuhan-table thead th {
        background-color: #f9fafb;
        color: #374151;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    #pelabuhan-table tbody td {
        padding: 0.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.75rem;
    }

    #pelabuhan-table tbody tr:hover {
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
    resolveTipePelabuhanId(pelabuhan) {
        if (pelabuhan.tipe_pelabuhan_id) {
            return String(pelabuhan.tipe_pelabuhan_id);
        }

        const editSelect = document.getElementById('edit-tipe-select');
        if (!editSelect) {
            return '';
        }

        const tipeName = String(pelabuhan.tipe_name || pelabuhan.tipe || '').trim().toLowerCase();
        if (!tipeName) {
            return '';
        }

        const matchedOption = Array.from(editSelect.options).find((option) => {
            if (!option.value) {
                return false;
            }

            const optionText = option.textContent.trim().toLowerCase();
            return optionText === tipeName || optionText.includes(tipeName) || tipeName.includes(optionText);
        });

        return matchedOption ? String(matchedOption.value) : '';
    },
    setEditTipeValue(pelabuhan) {
        const tipePelabuhanId = this.resolveTipePelabuhanId(pelabuhan);
        const editSelect = document.getElementById('edit-tipe-select');

        if (!editSelect) {
            return;
        }

        if (editSelect.tomselect) {
            editSelect.tomselect.setValue(tipePelabuhanId);
            return;
        }

        editSelect.value = tipePelabuhanId;
    },
    editPelabuhan(pelabuhan) {
        this.editData = { ...pelabuhan };
        this.editAction = '{{ route('master.pelabuhan.index') }}/' + pelabuhan.id;

        $dispatch('open-modal', 'edit-pelabuhan-modal');

        // Wait for modal to be visible/select to be ready
        setTimeout(() => {
            this.setEditTipeValue(pelabuhan);
        }, 100);
    }
}" @edit-pelabuhan.window="editPelabuhan($event.detail)">
    <!-- Search, Filter & Action Card -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <form id="filter-form" class="flex flex-col md:flex-row gap-3 w-full md:w-auto flex-1">
            <div class="w-full md:w-72">
                <input type="text" name="search" id="search-input" placeholder="Cari kode atau nama..."
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-full md:w-48 relative z-50">
                <select name="tipe_pelabuhan_id" id="tipe-filter" class="searchable-select w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Tipe</option>
                    @foreach($tipes as $tipe)
                        <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
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
            <button x-on:click="$dispatch('open-modal', 'tambah-pelabuhan-modal')" class="px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pelabuhan
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
        <div class="overflow-visible">
            <table id="pelabuhan-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Pelabuhan</th>
                        <th>Tipe</th>
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
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Pelabuhan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="nama"
                               value="{{ old('nama') }}"
                               required
                               maxlength="100"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror">
                        @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="tipe-select" class="block text-sm font-medium text-gray-700">
                                Tipe Pelabuhan <span class="text-red-500">*</span>
                            </label>
                            <button type="button"
                                    x-on:click="$dispatch('open-modal', 'tambah-tipe-modal')"
                                    class="text-[10px] text-blue-600 hover:text-blue-700 font-medium bg-blue-50 px-2 py-0.5 rounded border border-blue-200 italic transition-all">
                                + Tambah Tipe Baru
                            </button>
                        </div>
                        <select name="tipe_pelabuhan_id"
                                id="tipe-select"
                                required
                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('tipe_pelabuhan_id') border-red-500 @enderror">
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
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-md border border-gray-200">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">Status Aktif</span>
                            <span class="text-[10px] text-gray-500">Aktifkan untuk menampilkan data ini</span>
                        </div>
                        <label class="toggle-switch transform scale-90 origin-right">
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
                    <button type="button" x-on:click="$dispatch('close-modal', 'tambah-pelabuhan-modal')" class="px-4 py-2 text-xs font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">
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
                <button x-on:click="$dispatch('close-modal', 'edit-pelabuhan-modal')" class="text-gray-400 hover:text-gray-600 transition-colors">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Pelabuhan
                        </label>
                        <input type="text"
                               x-model="editData.kode"
                               disabled
                               class="w-full px-3 py-1.5 text-sm bg-gray-100 border border-gray-300 rounded-md text-gray-500 cursor-not-allowed italic">
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Pelabuhan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="edit_nama"
                               x-model="editData.nama"
                               required
                               maxlength="100"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Tipe -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="edit-tipe-select" class="block text-sm font-medium text-gray-700">
                                Tipe Pelabuhan <span class="text-red-500">*</span>
                            </label>
                            <button type="button"
                                    x-on:click="$dispatch('open-modal', 'tambah-tipe-modal')"
                                    class="text-[10px] text-blue-600 hover:text-blue-700 font-medium bg-blue-50 px-2 py-0.5 rounded border border-blue-200 italic transition-all">
                                + Tambah Tipe Baru
                            </button>
                        </div>
                        <select name="tipe_pelabuhan_id"
                                id="edit-tipe-select"
                                required
                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Tipe --</option>
                            @foreach($tipes as $tipe)
                                <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-md border border-gray-200">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">Status Aktif</span>
                            <span class="text-[10px] text-gray-500">Aktifkan untuk menampilkan data ini</span>
                        </div>
                        <label class="toggle-switch transform scale-90 origin-right">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   x-bind:checked="editData.is_active">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-pelabuhan-modal')" class="px-4 py-2 text-xs font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">
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
                        <label for="tipe_nama" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Tipe <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="tipe_nama"
                               required
                               maxlength="50"
                               placeholder="Contoh: UPP, POSKER, WILKER, LUAR"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Nama singkat untuk tipe pelabuhan</p>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="tipe_keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan
                        </label>
                        <input type="text"
                               name="keterangan"
                               id="tipe_keterangan"
                               maxlength="255"
                               placeholder="Deskripsi lengkap tipe pelabuhan"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div id="tipe-error-message" class="hidden p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg"></div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'tambah-tipe-modal')"
                            class="px-4 py-2 text-xs font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">
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
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    let tipeSelect, editTipeSelect;
    let table;

    $(document).ready(function() {
        table = $('#pelabuhan-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            ajax: {
                url: "{{ route('master.pelabuhan.index') }}",
                data: function (d) {
                    d.tipe = $('#tipe-filter').val();
                    d.status = $('#status-filter').val();
                    d.search_custom = $('#search-input').val();
                }
            },
            dom: "<'flex flex-col'<'w-full overflow-visible't><'flex flex-col md:flex-row justify-between items-center p-4 gap-4'<'flex items-center gap-6'li>p>>",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'kode', name: 'kode'},
                {data: 'nama', name: 'nama'},
                {
                    data: 'tipe_name',
                    name: 'tipe_name',
                    render: function(data, type, row) {
                        const badgeColors = {
                            'UPP': 'bg-blue-100 text-blue-800',
                            'POSKER': 'bg-green-100 text-green-800',
                            'WILKER': 'bg-yellow-100 text-yellow-800',
                            'LUAR': 'bg-gray-100 text-gray-800',
                        };
                        const color = badgeColors[data] || 'bg-purple-100 text-purple-800';
                        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${color}">${data}</span>`;
                    }
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data, type, row) {
                        const color = data ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
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

        // Trigger search on enter
        $('#search-input').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                table.ajax.reload();
            }
        });

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

        // Value assignment for edit tipe is handled directly from Alpine via #edit-tipe-select.tomselect
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

                // Refresh table
                table.ajax.reload();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Tipe pelabuhan berhasil ditambahkan!',
                    timer: 2000,
                    showConfirmButton: false
                });
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
