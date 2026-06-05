(function () {
    function navigateWithParams(urlParams) {
        let newUrl = window.location.pathname;
        const queryString = urlParams.toString();
        if (queryString) {
            newUrl += '?' + queryString;
        }
        window.location.href = newUrl;
    }

    function removeAllOptionParams(urlParams) {
        [...urlParams.keys()].forEach(function (key) {
            if (key === 'options[]' || key === 'options' || key === 'features[]' || key === 'features') {
                urlParams.delete(key);
            }
        });
    }

    window.selectProvince = function (value) {
        const form = document.getElementById('provinceFilterForm');
        if (!form) return;
        if (value === '') {
            form.querySelector('input[name="province"]')?.remove();
        } else {
            let input = form.querySelector('input[name="province"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'province';
                form.appendChild(input);
            }
            input.value = value;
        }
        const currentProvince = form.dataset.currentProvince || '';
        if (value !== currentProvince) {
            const cityInput = form.querySelector('input[name="city"]');
            if (cityInput) cityInput.remove();
        }
        form.submit();
    };

    window.selectCity = function (value) {
        const form = document.getElementById('cityFilterForm');
        if (!form) return;
        if (value === '') {
            form.querySelector('input[name="city"]')?.remove();
        } else {
            let input = form.querySelector('input[name="city"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'city';
                form.appendChild(input);
            }
            input.value = value;
        }
        form.submit();
    };

    window.selectType = function (value) {
        const form = document.getElementById('typeFilterForm');
        if (!form) return;
        if (value === '') {
            form.querySelector('input[name="type"]')?.remove();
        } else {
            let input = form.querySelector('input[name="type"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'type';
                form.appendChild(input);
            }
            input.value = value;
        }
        form.submit();
    };

    window.selectGuestCount = function (value) {
        const form = document.getElementById('guestFilterForm');
        if (!form) return;
        if (value === '') {
            form.querySelector('input[name="guest_count"]')?.remove();
        } else {
            let input = form.querySelector('input[name="guest_count"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'guest_count';
                form.appendChild(input);
            }
            input.value = value;
        }
        form.submit();
    };

    window.clearFilter = function (filterType, value) {
        const urlParams = new URLSearchParams(window.location.search);

        if (filterType === 'province') {
            urlParams.delete('province');
            urlParams.delete('city');
        } else if (filterType === 'price') {
            urlParams.delete('min_price');
            urlParams.delete('max_price');
        } else if (filterType === 'options') {
            removeAllOptionParams(urlParams);
        } else if (filterType === 'option' && value) {
            const kept = urlParams.getAll('options[]').filter(function (id) { return id !== value; });
            removeAllOptionParams(urlParams);
            kept.forEach(function (id) { urlParams.append('options[]', id); });
        } else if (filterType === 'features') {
            removeAllOptionParams(urlParams);
        } else if (filterType === 'feature' && value) {
            const kept = urlParams.getAll('features[]').filter(function (f) { return f !== value; });
            removeAllOptionParams(urlParams);
            kept.forEach(function (f) { urlParams.append('features[]', f); });
        } else if (filterType === 'travel_dates') {
            urlParams.delete('start_at');
            urlParams.delete('end_at');
        } else if (filterType === 'name') {
            urlParams.delete('name');
            urlParams.delete('search');
            urlParams.delete('q[]');
        } else if (filterType === 'q' && value) {
            const kept = urlParams.getAll('q[]').filter(function (t) { return t !== value; });
            urlParams.delete('q[]');
            urlParams.delete('name');
            urlParams.delete('search');
            kept.forEach(function (t) { urlParams.append('q[]', t); });
        } else {
            urlParams.delete(filterType);
        }

        navigateWithParams(urlParams);
    };

    window.clearFilterChip = function (chipKey, chipValue) {
        if (chipKey === 'option') {
            clearFilter('option', chipValue);
        } else if (chipKey === 'feature') {
            clearFilter('feature', chipValue);
        } else if (chipKey === 'q') {
            clearFilter('q', chipValue);
        } else {
            clearFilter(chipKey);
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.filter-badge-btn[data-bs-target]').forEach(function (badge) {
            badge.setAttribute('role', 'button');
            if (!badge.hasAttribute('tabindex')) {
                badge.setAttribute('tabindex', '0');
            }

            const openModal = function (event) {
                const target = badge.getAttribute('data-bs-target');
                if (!target || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                const modalEl = document.querySelector(target);
                if (modalEl) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            };

            badge.addEventListener('click', openModal);
            badge.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    openModal(event);
                }
            });
        });

        document.querySelectorAll('.homes-filter-modals .modal').forEach(function (modalEl) {
            modalEl.addEventListener('show.bs.modal', function () {
                document.body.classList.add('homes-filter-modal-open');
            });
            modalEl.addEventListener('hidden.bs.modal', function () {
                if (!document.querySelector('.homes-filter-modals .modal.show')) {
                    document.body.classList.remove('homes-filter-modal-open');
                }
            });
        });

        document.getElementById('filterPriceModal')?.addEventListener('shown.bs.modal', function () {
            if (typeof window.initMobilePriceRanges === 'function') {
                window.initMobilePriceRanges();
            }
        });
        document.getElementById('filterModal')?.addEventListener('shown.bs.modal', function () {
            if (typeof window.initMobilePriceRanges === 'function') {
                window.initMobilePriceRanges();
            }
        });

        document.querySelectorAll('[data-filter-options-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                const panelId = button.getAttribute('aria-controls');
                const panel = panelId ? document.getElementById(panelId) : null;
                if (!panel) return;

                const isOpen = !panel.classList.contains('is-open');
                panel.classList.toggle('is-open', isOpen);
                panel.hidden = !isOpen;
                button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                const label = button.querySelector('[data-filter-options-toggle-label]');
                const icon = button.querySelector('[data-filter-options-toggle-icon]');
                if (label) {
                    label.textContent = isOpen ? 'نمایش کمتر' : 'موارد بیشتر';
                }
                if (icon) {
                    icon.className = 'bi ' + (isOpen ? 'bi-chevron-up' : 'bi-chevron-down');
                }
            });
        });

        document.querySelectorAll('.homes-filter-option-item input[type="checkbox"]').forEach(function (input) {
            const syncCheckedState = function () {
                input.closest('.homes-filter-option-item')?.classList.toggle('is-checked', input.checked);
            };
            input.addEventListener('change', syncCheckedState);
            syncCheckedState();
        });

        const provinceSelect = document.getElementById('province');
        if (provinceSelect) {
            provinceSelect.addEventListener('change', function () {
                const provinceId = this.value;
                const citySelect = document.getElementById('city');
                if (!citySelect) return;

                citySelect.innerHTML = '<option value="">انتخاب شهر</option>';

                if (provinceId) {
                    fetch(`/api/cities/${provinceId}`)
                        .then(response => response.json())
                        .then(cities => {
                            cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                citySelect.appendChild(option);
                            });
                        });
                }
            });
        }
    });
})();
