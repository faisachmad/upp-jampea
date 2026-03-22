@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
    #jenis-pelayaran-table_wrapper .dataTables_filter { display: none; }
    #jenis-pelayaran-table_wrapper .dataTables_length label {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.875rem; color: #6b7280;
    }
    #jenis-pelayaran-table_wrapper .dataTables_length select {
        padding: 0.375rem 1.75rem 0.375rem 0.75rem !important;
        font-size: 0.75rem !important; color: #374151 !important;
        background-color: #fff !important; border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important; appearance: none !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: right 0.5rem center !important;
        background-repeat: no-repeat !important; background-size: 1.5em 1.5em !important; cursor: pointer;
    }
    table.dataTable.no-footer { border-bottom: 1px solid #e5e7eb !important; }
    #jenis-pelayaran-table thead th {
        background-color: #f9fafb; color: #374151; text-transform: uppercase;
        font-size: 0.7rem; letter-spacing: 0.05em; font-weight: 600;
        padding: 0.5rem 1rem; border-bottom: 1px solid #e5e7eb;
    }
    #jenis-pelayaran-table tbody td {
        padding: 0.5rem 1rem; vertical-align: middle;
        border-bottom: 1px solid #f3f4f6; font-size: 0.75rem;
    }
    #jenis-pelayaran-table tbody tr:hover {
        background-color: #eff6ff !important; transition: background-color 0.2s;
    }
    .dataTables_wrapper .dataTables_info { padding-top: 1.5rem !important; color: #6b7280 !important; font-size: 0.875rem !important; }
    .dataTables_wrapper .dataTables_paginate { padding-top: 1.5rem !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem !important; border: 1px solid #e5e7eb !important;
        background: white !important; color: #374151 !important;
        padding: 0.4rem 0.8rem !important; margin-left: 0.25rem !important; font-size: 0.875rem !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #2563eb !important; color: white !important; border-color: #2563eb !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important; color: #2563eb !important; border-color: #d1d5db !important;
    }
</style>
@endpush

@section('title', 'Master Jenis Pelayaran')

@section('content')
<div class="mb-6" x-data="{
    editData: {},
    editAction: '',
    editJenisPelayaran(jenis) {
        this.editData = { ...jenis };
        this.editAction = '{{ route('master.jenis-pelayaran.index') }}/' + jenis.id;
        $dispatch('open-modal', 'edit-jenis-pelayaran-modal');
    }
}" @edit-jenis-pelayaran.window="editJenisPelayaran($event.detail)">

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

    {{-- Search & Action Card --}}
    <div class="bg-white p-4 lg:p-5 rounded-xl shadow-sm border border-gray-100 mb-6 space-y-4">
        <!-- Action Button Top Right -->
        <div class="flex justify-between items-center sm:justify-end">
            <button x-on:click="$dispatch('open-modal', 'tambah-jenis-pelayaran-modal')" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Jenis Pelayaran
            </button>
        </div>

        <form id="filter-form" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="w-full lg:col-span-2">
                <input type="text" id="search-input" placeholder="Kode, Prefix, Nama..."
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div class="w-full sm:col-span-1 flex justify-end">
                <div class="inline-flex shadow-sm rounded-md w-full sm:w-auto" role="group">
                    <button type="button" id="btn-cari" class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-l-md hover:bg-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500 transition-all">
                        Cari
                    </button>
                    <button type="button" id="btn-reset" class="flex-1 sm:flex-none flex items-center justify-center px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-10 focus:ring-2 focus:ring-blue-500 transition-all">
                        Reset
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
        <div class="overflow-visible">
            <table id="jenis-pelayaran-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Prefix</th>
                        <th>Nama</th>
                        <th>Jml Kunjungan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    {{-- DataTables will fill this --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info note --}}
    <p class="mt-3 text-xs text-gray-500 italic">* Data Jenis Pelayaran bersifat tetap (fixed). Gunakan hanya untuk tambah data baru jika diperlukan.</p>

    {{-- Modal Tambah --}}
    <x-modal name="tambah-jenis-pelayaran-modal" maxWidth="lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Tambah Jenis Pelayaran</h2>
                <button x-on:click="$dispatch('close-modal', 'tambah-jenis-pelayaran-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('master.jenis-pelayaran.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="kode" required maxlength="20"
                                   placeholder="Contoh: PELRA"
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix <span class="text-red-500">*</span></label>
                            <input type="text" name="prefix" id="prefix" required maxlength="1"
                                   placeholder="A"
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <p class="mt-1 text-[10px] text-gray-500">1 huruf saja (A, B, C, ...)</p>
                        </div>
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" required maxlength="100"
                               placeholder="Contoh: Pelayaran Rakyat"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'tambah-jenis-pelayaran-modal')"
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

    {{-- Modal Edit --}}
    <x-modal name="edit-jenis-pelayaran-modal" maxWidth="lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Edit Jenis Pelayaran</h2>
                <button x-on:click="$dispatch('close-modal', 'edit-jenis-pelayaran-modal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="editAction" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_kode" class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="edit_kode" x-model="editData.kode" required maxlength="20"
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label for="edit_prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix <span class="text-red-500">*</span></label>
                            <input type="text" name="prefix" id="edit_prefix" x-model="editData.prefix" required maxlength="1"
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                    </div>
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="edit_nama" x-model="editData.nama" required maxlength="100"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-jenis-pelayaran-modal')"
                            class="px-4 py-2 text-xs font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-xs font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">
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
        table = $('#jenis-pelayaran-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            ajax: {
                url: "{{ route('master.jenis-pelayaran.index') }}",
                data: function (d) {
                    d.search_custom = $('#search-input').val();
                }
            },
            dom: "<'flex flex-col'<'w-full overflow-visible't><'flex flex-col md:flex-row justify-between items-center p-4 gap-4'<'flex items-center gap-6'li>p>>",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'kode', name: 'kode'},
                {
                    data: 'prefix',
                    name: 'prefix',
                    render: function(data) {
                        return `<span class="w-7 h-7 inline-flex items-center justify-center bg-blue-600 text-white font-bold text-xs rounded-full">${data}</span>`;
                    }
                },
                {data: 'nama', name: 'nama'},
                {
                    data: 'kunjungans_count',
                    name: 'kunjungans_count',
                    render: function(data) {
                        return `<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">${data} kunjungan</span>`;
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
    });
</script>
@endpush
@endsection
