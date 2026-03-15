@extends('layouts.app')

@section('title', 'Master Kapal')

@section('content')
<div class="mb-6" x-data="{
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
                alert(result.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan saat menyimpan data.');
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
                alert(result.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan saat menyimpan data.');
        }
    }
}">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Master Kapal</h2>
        <button x-on:click="$dispatch('open-modal', 'tambah-kapal-modal')" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Tambah Kapal
        </button>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('master.kapal.index') }}" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama, Call Sign, Pemilik..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                <select name="jenis_kapal_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua</option>
                    @foreach($jenisKapals as $jk)
                        <option value="{{ $jk->id }}" {{ request('jenis_kapal_id') == $jk->id ? 'selected' : '' }}>
                            {{ $jk->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Per Halaman</label>
                <select name="per_page" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'jenis_kapal_id', 'status', 'per_page']))
                <a href="{{ route('master.kapal.index') }}" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Reset
                </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
            <p class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">{{ $kapals->firstItem() ?? 0 }}</span> 
                hingga <span class="font-medium">{{ $kapals->lastItem() ?? 0 }}</span> 
                dari <span class="font-medium">{{ $kapals->total() }}</span> data
            </p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kapal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bendera</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Call Sign</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik/Agen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kapals as $index => $kapal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $kapals->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $kapal->nama }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $kapal->jenisKapal->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $kapal->bendera->nama_negara ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $kapal->gt ? number_format($kapal->gt, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $kapal->call_sign ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $kapal->pemilik_agen ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($kapal->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Tidak Aktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition">
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
                                            x-on:click="editKapal({{ json_encode($kapal) }})"
                                            class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                                        Edit
                                    </button>

                                    <form action="{{ route('master.kapal.destroy', $kapal) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                class="block w-full text-left px-4 py-2 text-sm leading-5 text-red-600 hover:bg-gray-100 focus:outline-none transition">
                                            Hapus
                                        </button>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data kapal.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
            {{ $kapals->links() }}
        </div>
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
                    <label for="jk_kode" class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" id="jk_kode" required maxlength="10"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                
                <div>
                    <label for="jk_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="jk_nama" required maxlength="100"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
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

@endsection
