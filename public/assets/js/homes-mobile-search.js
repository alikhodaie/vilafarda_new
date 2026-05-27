/**
 * جستجوی تدریجی: هر Submit فقط کلمه جدید را به q[] اضافه می‌کند.
 */
(function () {
    'use strict';

    var form = document.getElementById('homesMobileSearchForm');
    var input = document.getElementById('homesMobileSearchInput');

    if (!form || !input) {
        return;
    }

    function existingTerms() {
        return Array.prototype.map.call(
            form.querySelectorAll('input[data-search-term][name="q[]"]'),
            function (el) {
                return (el.value || '').trim();
            }
        ).filter(Boolean);
    }

    function termExists(term) {
        var norm = term.trim().toLowerCase();
        return existingTerms().some(function (t) {
            return t.trim().toLowerCase() === norm;
        });
    }

    form.addEventListener('submit', function (e) {
        var value = (input.value || '').trim();

        if (!value && existingTerms().length === 0) {
            e.preventDefault();
            input.focus();
            return;
        }

        if (value && !termExists(value)) {
            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'q[]';
            hidden.value = value;
            hidden.setAttribute('data-search-term', '1');
            form.appendChild(hidden);
        }

        input.value = '';
    });
})();
