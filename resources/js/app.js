import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Choices from 'choices.js';

Alpine.plugin(collapse);

window.Alpine = Alpine;
window.Choices = Choices;

// Initialize Choices.js on all select elements with .searchable-select class
window.initChoices = function() {
    document.querySelectorAll('.searchable-select:not(.choices__input)').forEach(function(element) {
        if (!element.classList.contains('choices__input--cloned')) {
            new Choices(element, {
                searchEnabled: true,
                searchPlaceholderValue: 'Ketik untuk mencari...',
                noResultsText: 'Tidak ada hasil ditemukan',
                itemSelectText: 'Klik untuk memilih',
                removeItemButton: false,
                shouldSort: false,
                searchFields: ['label', 'value'],
                fuseOptions: {
                    threshold: 0.3,
                    distance: 100
                },
                classNames: {
                    containerOuter: 'choices',
                }
            });
        }
    });
};

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    window.initChoices();
});

// Register Alpine Components
Alpine.data('kunjunganForm', () => ({
    currentTab: 1,
    muatans: [],
    b3s: [],
    addMuatan() {
        this.muatans.push({ tipe: '', jenis_barang: '', ton_m3: '', jenis_hewan: '', jumlah_hewan: '' });
    },
    removeMuatan(index) { this.muatans.splice(index, 1); },
    addB3() {
        this.b3s.push({ barang_b3_id: '', jenis_kegiatan: '', bentuk_muatan: '', jumlah_ton: '', jumlah_container: '', kemasan: '', jumlah: '', petugas: '' });
    },
    removeB3(index) { this.b3s.splice(index, 1); }
}));

Alpine.data('autocomplete', (url, fieldName) => ({
    searchQuery: '',
    results: [],
    showResults: false,
    selectedId: '',
    async search() {
        if (this.searchQuery.length < 2) { this.results = []; return; }
        try {
            const response = await fetch(`${url}?q=${encodeURIComponent(this.searchQuery)}`);
            this.results = await response.json();
            this.showResults = true;
        } catch (error) { console.error('Search error:', error); }
    },
    selectItem(item) {
        this.searchQuery = item.label || item.nama;
        this.selectedId = item.id;
        this.showResults = false;
    }
}));

Alpine.data('autocompleteNakhoda', (apiUrl) => ({
    searchQuery: '',
    nakhodaId: '',
    results: [],
    showResults: false,
    async search() {
        if (this.searchQuery.length < 2) { this.results = []; return; }
        try {
            const r = await fetch(`${apiUrl}?q=${encodeURIComponent(this.searchQuery)}`);
            this.results = await r.json();
            this.showResults = true;
        } catch(e) { this.results = []; }
    },
    selectNakhoda(item) {
        this.searchQuery = item.nama;
        this.nakhodaId = item.id;
        this.showResults = false;
    }
}));

// Modal Helpers (keep on window for global onclick access)
window.openModal = (id) => document.getElementById(id)?.classList.remove('hidden');
window.closeModal = (id) => document.getElementById(id)?.classList.add('hidden');

Alpine.start();
