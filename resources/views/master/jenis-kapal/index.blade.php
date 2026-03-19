@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
    /* DataTables Custom Styling */
    #jenis-kapal-table_wrapper .dataTables_filter {
        display: none;
    }

    #jenis-kapal-table_wrapper .dataTables_length {
        display: block;
    }

    #jenis-kapal-table_wrapper .dataTables_length label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    #jenis-kapal-table_wrapper .dataTables_length select {
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

    #jenis-kapal-table_wrapper .dataTables_length select:focus {
        outline: none !important;
        border-color: #3b82f6 !important;
        ring: 2px !important;
        ring-color: #3b82f6 !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #e5e7eb !important;
    }

    #jenis-kapal-table thead th {
        background-color: #f9fafb;
        color: #374151;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    #jenis-kapal-table tbody td {
        padding: 0.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.75rem;
    }

    #jenis-kapal-table tbody tr:hover {
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

@section('title', 'Master Jenis Kapal')

@section('content')
<div class="mb-6" x-data="{
    editData: {},
    editAction: '',
    editJenisKapal(jenis) {
        this.editData = { ...jenis };
        this.editAction = '{{ route('master.jenis-kapal.index') }}/' + jenis.id;
        $dispatch('open-modal', 'edit-jenis-kapal-modal');
    }
}" @edit-jenis-kapal.window="editJenisKapal($event.detail)">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Search, Filter & Action Card -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <form id="filter-form" class="flex flex-col md:flex-row gap-3 w-full md:w-auto flex-1">
            <div class="w-full md:w-72">
                <input type="text" id="search-input" placeholder="Nama, Kode, Keterangan..."
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-full md:w-48">
                <select id="status-filter" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="button" id="btn-cari" class="px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-all">
                    Cari
                </button>
                <button type="button" id="btn-reset" class="px-4 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-md border border-gray-200 hover:bg-gray-200 transition-all">
                    Reset
                </button>
            </div>
        </form>

        <div class="w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-4 flex justify-end">
            <button x-on:click="$dispatch('open-modal', 'tambah-jenis-kapal-modal')" class="px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Jenis Kapal
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
        <div class="overflow-visible">
            <table id="jenis-kapal-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Keterangan</th>
                        <th>Jumlah Kapal</th>
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

            <form action="{{ route('master.jenis-kapal.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" required maxlength="100"
                               placeholder="Contoh: Kapal Layar Motor"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <p class="mt-1 text-xs text-gray-500">Kode akan dibuat otomatis dari nama</p>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
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

    <!-- Modal Edit Jenis Kapal -->
    <x-modal name="edit-jenis-kapal-modal" maxWidth="lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Edit Jenis Kapal</h2>
                <button x-on:click="$dispatch('close-modal', 'edit-jenis-kapal-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="editAction" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="edit_nama" x-model="editData.nama" required maxlength="100"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label for="edit_keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" x-model="editData.keterangan" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" :checked="editData.is_active"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-jenis-kapal-modal')"
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
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    let table;
    $(document).ready(function() {
        table = $('#jenis-kapal-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            ajax: {
                url: "{{ route('master.jenis-kapal.index') }}",
                data: function (d) {
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
                    data: 'keterangan',
                    name: 'keterangan',
                    render: function(data) {
                        return data ? (data.length > 50 ? data.substring(0, 50) + '...' : data) : '-';
                    }
                },
                {
                    data: 'kapals_count',
                    name: 'kapals_count',
                    render: function(data) {
                        return `<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">${data} kapal</span>`;
                    }
                },
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
        // Removed window.editJenisKapal as it is now event-based
    });
</script>
@endpush
@endsection
