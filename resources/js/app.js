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
                    containerOuter: 'choices w-full',
                }
            });
        }
    });
};

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    window.initChoices();
});

Alpine.start();
