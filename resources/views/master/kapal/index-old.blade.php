@extends('layouts.app')

@section('title', 'Master Kapal')

@section('content')
<div class="mb-6" x-data="{
    editData: {},
    editAction: '',
    editKapal(kapal) {
        this.editData = { ...kapal };
        this.editAction = '{{ route('master.kapal.index') }}/' + kapal.id;
        $dispatch('open-modal', 'edit-kapal-modal');
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <select name="jenis" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua</option>
                    <option value="KLM" {{ request('jenis') == 'KLM' ? 'selected' : '' }}>KLM</option>
                    <option value="KM" {{ request('jenis') == 'KM' ? 'selected' : '' }}>KM</option>
                    <option value="KMP" {{ request('jenis') == 'KMP' ? 'selected' : '' }}>KMP</option>
                    <option value="MV" {{ request('jenis') == 'MV' ? 'selected' : '' }}>MV</option>
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
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'jenis', 'status']))
                <a href="{{ route('master.kapal.index') }}" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Reset
                </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kapal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
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
                        {{ $kapal->jenis ?? '-' }}
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
                                        x-on:click="editKapal({{ $kapal }})"
                                        class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('Edit') }}
                                </button>

                                <form action="{{ route('master.kapal.destroy', $kapal) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-dropdown-link :href="route('master.kapal.destroy', $kapal)"
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
                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada data kapal.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
            {{ $kapals->links() }}
        </div>
    </div>

<!-- Modal Tambah Kapal -->
<x-modal name="tambah-kapal-modal" :show="$errors->any() && !session('is_edit')" maxWidth="4xl" :closeable="false">
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
                    <input type="text"
                           name="nama"
                           id="nama"
                           value="{{ old('nama') }}"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nama') border-red-500 @enderror">
                    @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kapal -->
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kapal</label>
                    <select name="jenis"
                            id="jenis"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="KLM" {{ old('jenis') == 'KLM' ? 'selected' : '' }}>KLM</option>
                        <option value="KM" {{ old('jenis') == 'KM' ? 'selected' : '' }}>KM</option>
                        <option value="KMP" {{ old('jenis') == 'KMP' ? 'selected' : '' }}>KMP</option>
                        <option value="MV" {{ old('jenis') == 'MV' ? 'selected' : '' }}>MV</option>
                    </select>
                </div>

                <!-- GT -->
                <div>
                    <label for="gt" class="block text-sm font-medium text-gray-700 mb-1">GT (Gross Tonnage)</label>
                    <input type="number"
                           name="gt"
                           id="gt"
                           step="0.01"
                           value="{{ old('gt') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- DWT -->
                <div>
                    <label for="dwt" class="block text-sm font-medium text-gray-700 mb-1">DWT (Dead Weight Tonnage)</label>
                    <input type="number"
                           name="dwt"
                           id="dwt"
                           step="0.01"
                           value="{{ old('dwt') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Panjang -->
                <div>
                    <label for="panjang" class="block text-sm font-medium text-gray-700 mb-1">Panjang (m)</label>
                    <input type="number"
                           name="panjang"
                           id="panjang"
                           step="0.01"
                           value="{{ old('panjang') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tanda Selar -->
                <div>
                    <label for="tanda_selar" class="block text-sm font-medium text-gray-700 mb-1">Tanda Selar</label>
                    <input type="text"
                           name="tanda_selar"
                           id="tanda_selar"
                           value="{{ old('tanda_selar') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Call Sign -->
                <div>
                    <label for="call_sign" class="block text-sm font-medium text-gray-700 mb-1">Call Sign</label>
                    <input type="text"
                           name="call_sign"
                           id="call_sign"
                           value="{{ old('call_sign') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tempat Kedudukan -->
                <div>
                    <label for="tempat_kedudukan" class="block text-sm font-medium text-gray-700 mb-1">Tempat Kedudukan</label>
                    <input type="text"
                           name="tempat_kedudukan"
                           id="tempat_kedudukan"
                           value="{{ old('tempat_kedudukan') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Bendera -->
                <div>
                    <label for="bendera" class="block text-sm font-medium text-gray-700 mb-1">Bendera</label>
                    <input type="text"
                           name="bendera"
                           id="bendera"
                           value="{{ old('bendera', 'INDONESIA') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Pemilik/Agen -->
                <div class="md:col-span-2">
                    <label for="pemilik_agen" class="block text-sm font-medium text-gray-700 mb-1">Pemilik/Agen</label>
                    <input type="text"
                           name="pemilik_agen"
                           id="pemilik_agen"
                           value="{{ old('pemilik_agen') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
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
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modal Edit Kapal -->
<x-modal name="edit-kapal-modal" :show="$errors->any() && session('is_edit')" maxWidth="4xl" :closeable="false">
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
                    <input type="text"
                           name="nama"
                           id="edit_nama"
                           x-model="editData.nama"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kapal -->
                <div>
                    <label for="edit_jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kapal</label>
                    <select name="jenis"
                            id="edit_jenis"
                            x-model="editData.jenis"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="KLM">KLM</option>
                        <option value="KM">KM</option>
                        <option value="KMP">KMP</option>
                        <option value="MV">MV</option>
                    </select>
                </div>

                <!-- GT -->
                <div>
                    <label for="edit_gt" class="block text-sm font-medium text-gray-700 mb-1">GT (Gross Tonnage)</label>
                    <input type="number"
                           name="gt"
                           id="edit_gt"
                           step="0.01"
                           x-model="editData.gt"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- DWT -->
                <div>
                    <label for="edit_dwt" class="block text-sm font-medium text-gray-700 mb-1">DWT (Dead Weight Tonnage)</label>
                    <input type="number"
                           name="dwt"
                           id="edit_dwt"
                           step="0.01"
                           x-model="editData.dwt"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Panjang -->
                <div>
                    <label for="edit_panjang" class="block text-sm font-medium text-gray-700 mb-1">Panjang (m)</label>
                    <input type="number"
                           name="panjang"
                           id="edit_panjang"
                           step="0.01"
                           x-model="editData.panjang"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tanda Selar -->
                <div>
                    <label for="edit_tanda_selar" class="block text-sm font-medium text-gray-700 mb-1">Tanda Selar</label>
                    <input type="text"
                           name="tanda_selar"
                           id="edit_tanda_selar"
                           x-model="editData.tanda_selar"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Call Sign -->
                <div>
                    <label for="edit_call_sign" class="block text-sm font-medium text-gray-700 mb-1">Call Sign</label>
                    <input type="text"
                           name="call_sign"
                           id="edit_call_sign"
                           x-model="editData.call_sign"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Tempat Kedudukan -->
                <div>
                    <label for="edit_tempat_kedudukan" class="block text-sm font-medium text-gray-700 mb-1">Tempat Kedudukan</label>
                    <input type="text"
                           name="tempat_kedudukan"
                           id="edit_tempat_kedudukan"
                           x-model="editData.tempat_kedudukan"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Bendera -->
                <div>
                    <label for="edit_bendera" class="block text-sm font-medium text-gray-700 mb-1">Bendera</label>
                    <input type="text"
                           name="bendera"
                           id="edit_bendera"
                           x-model="editData.bendera"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Pemilik/Agen -->
                <div class="md:col-span-2">
                    <label for="edit_pemilik_agen" class="block text-sm font-medium text-gray-700 mb-1">Pemilik/Agen</label>
                    <input type="text"
                           name="pemilik_agen"
                           id="edit_pemilik_agen"
                           x-model="editData.pemilik_agen"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               :checked="editData.is_active"
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
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-modal>
</div>
@endsection
