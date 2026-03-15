@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
    /* DataTables Custom Styling */
    #nakhoda-table_wrapper .dataTables_filter {
        display: none;
    }

    #nakhoda-table_wrapper .dataTables_length {
        display: block;
    }

    #nakhoda-table_wrapper .dataTables_length label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    #nakhoda-table_wrapper .dataTables_length select {
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

    #nakhoda-table_wrapper .dataTables_length select:focus {
        outline: none !important;
        border-color: #3b82f6 !important;
        ring: 2px !important;
        ring-color: #3b82f6 !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #e5e7eb !important;
    }

    #nakhoda-table thead th {
        background-color: #f9fafb;
        color: #374151;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    #nakhoda-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }

    #nakhoda-table tbody tr:hover {
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

@section('title', 'Master Nakhoda')

@section('content')
<div class="space-y-6" x-data="{
    editData: {},
    editAction: '',
    editNakhoda(nakhoda) {
        this.editData = { ...nakhoda };
        this.editAction = '{{ route('master.nakhoda.index') }}/' + nakhoda.id;
        $dispatch('open-modal', 'edit-nakhoda-modal');
    }
}" @edit-nakhoda.window="editNakhoda($event.detail)">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Master Nakhoda</h1>
        <button x-on:click="$dispatch('open-modal', 'tambah-nakhoda-modal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Tambah Nakhoda
        </button>
    </div>

    <!-- Success Alert (Handled globally by x-sweet-alert) -->

    <!-- Search & Filter Form -->
    <div class="bg-white p-6 rounded-lg shadow">
        <form id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text"
                       name="search"
                       id="search-input"
                       placeholder="Cari nama nakhoda..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="kapal_id" id="kapal-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kapal</option>
                    @foreach($kapals as $kapal)
                    <option value="{{ $kapal->id }}">{{ $kapal->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="button" id="btn-cari" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Cari
                </button>
                <button type="button" id="btn-reset" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
        <div class="overflow-x-auto overflow-visible">
            <table id="nakhoda-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Nakhoda</th>
                        <th>Kapal</th>
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

    <!-- Modal Tambah Nakhoda -->
    <x-modal name="tambah-nakhoda-modal" :show="$errors->any()" maxWidth="2xl" :closeable="false">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Tambah Nakhoda</h2>
                <button x-on:click="$dispatch('close-modal', 'tambah-nakhoda-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

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
                        <select name="kapal_id"
                                id="kapal_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kapal_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kapal --</option>
                            @foreach($kapals as $kapal)
                            <option value="{{ $kapal->id }}" {{ old('kapal_id') == $kapal->id ? 'selected' : '' }}>
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
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'tambah-nakhoda-modal')" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
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
    <!-- Modal Edit Nakhoda -->
    <x-modal name="edit-nakhoda-modal" :show="false" maxWidth="2xl" :closeable="false">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Edit Nakhoda</h2>
                <button x-on:click="$dispatch('close-modal', 'edit-nakhoda-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="editAction" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Nama -->
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Nakhoda <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="edit_nama"
                               x-model="editData.nama"
                               required
                               maxlength="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Kapal -->
                    <div>
                        <label for="edit_kapal_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kapal <span class="text-red-500">*</span>
                        </label>
                        <select name="kapal_id"
                                id="edit_kapal_id"
                                x-model="editData.kapal_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Kapal --</option>
                            @foreach($kapals as $kapal)
                            <option value="{{ $kapal->id }}">
                                {{ $kapal->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   x-model="editData.is_active"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-nakhoda-modal')" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update
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
        table = $('#nakhoda-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            ajax: {
                url: "{{ route('master.nakhoda.index') }}",
                data: function (d) {
                    d.kapal_id = $('#kapal-filter').val();
                    d.status = $('#status-filter').val();
                    d.search_custom = $('#search-input').val();
                }
            },
            dom: "<'flex flex-col'<'overflow-x-auto't><'flex flex-col md:flex-row justify-between items-center p-4 gap-4'<'flex items-center gap-6'li>p>>",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama', name: 'nama'},
                {data: 'kapal_nama', name: 'kapal_nama'},
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data) {
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
            responsive: true
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
        // Removed window.editNakhoda as it is now event-based
    });
</script>
@endpush
@endsection
