(function () {
    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/"/g, '&quot;');
    }

    function defaultRoom() {
        return { id: null, single_bed: 0, double_bed: 0, traditional_bed: 0, more: '' };
    }

    function readInitialBedrooms() {
        var dataEl = document.getElementById('adminBedroomsInitialData');
        if (!dataEl) {
            return [defaultRoom()];
        }
        try {
            var parsed = JSON.parse(dataEl.textContent || '[]');
            if (Array.isArray(parsed) && parsed.length > 0) {
                return parsed;
            }
        } catch (e) {
            //
        }
        return [defaultRoom()];
    }

    window.adminBedroomsSyncFromDom = function () {
        var listEl = document.getElementById('adminBedroomsList');
        if (!listEl || !window.adminBedroomsState) {
            return;
        }

        var cards = listEl.querySelectorAll('.admin-bedroom-card');
        if (!cards.length) {
            return;
        }

        var synced = [];
        cards.forEach(function (card, index) {
            var idInput = card.querySelector('input[name="sleep_room[' + index + '][id]"]');
            synced.push({
                id: idInput ? (idInput.value || null) : null,
                single_bed: parseInt(card.querySelector('input[name="sleep_room[' + index + '][single_bed]"]')?.value || '0', 10) || 0,
                double_bed: parseInt(card.querySelector('input[name="sleep_room[' + index + '][double_bed]"]')?.value || '0', 10) || 0,
                traditional_bed: parseInt(card.querySelector('input[name="sleep_room[' + index + '][traditional_bed]"]')?.value || '0', 10) || 0,
                more: card.querySelector('input[name="sleep_room[' + index + '][more]"]')?.value || '',
            });
        });

        window.adminBedroomsState.bedrooms = synced;
    };

    window.adminBedroomsRender = function () {
        var listEl = document.getElementById('adminBedroomsList');
        var countDisplay = document.getElementById('adminBedroomCountDisplay');
        if (!listEl || !window.adminBedroomsState) {
            return;
        }

        listEl.innerHTML = '';
        var bedrooms = window.adminBedroomsState.bedrooms;

        bedrooms.forEach(function (room, index) {
            var card = document.createElement('div');
            card.className = 'card mb-3 admin-bedroom-card';
            var idField = room.id ? '<input type="hidden" name="sleep_room[' + index + '][id]" value="' + room.id + '">' : '';
            card.innerHTML = ''
                + idField
                + '<div class="card-body">'
                + '<h6 class="card-title mb-3">اتاق ' + (index + 1) + '</h6>'
                + '<div class="row g-2">'
                + '<div class="col-6 col-md-4"><label class="form-label small">تخت یک‌نفره</label>'
                + '<input type="number" min="0" max="100" class="form-control" name="sleep_room[' + index + '][single_bed]" value="' + (room.single_bed || 0) + '"></div>'
                + '<div class="col-6 col-md-4"><label class="form-label small">تخت دو‌نفره</label>'
                + '<input type="number" min="0" max="100" class="form-control" name="sleep_room[' + index + '][double_bed]" value="' + (room.double_bed || 0) + '"></div>'
                + '<div class="col-6 col-md-4"><label class="form-label small">رخت‌خواب سنتی</label>'
                + '<input type="number" min="0" max="100" class="form-control" name="sleep_room[' + index + '][traditional_bed]" value="' + (room.traditional_bed || 0) + '"></div>'
                + '<div class="col-12"><label class="form-label small">سایر موارد</label>'
                + '<input type="text" class="form-control" placeholder="مثل: مبل تخت‌خواب‌شو" name="sleep_room[' + index + '][more]" value="' + escapeHtml(room.more) + '"></div>'
                + '</div></div>';
            listEl.appendChild(card);
        });

        if (countDisplay) {
            countDisplay.textContent = String(bedrooms.length);
        }
    };

    window.adminBedroomsSetCount = function (count) {
        if (!window.adminBedroomsState) {
            return;
        }
        window.adminBedroomsSyncFromDom();
        count = Math.min(20, Math.max(0, count));
        var bedrooms = window.adminBedroomsState.bedrooms;
        while (bedrooms.length < count) {
            bedrooms.push(defaultRoom());
        }
        while (bedrooms.length > count) {
            bedrooms.pop();
        }
        window.adminBedroomsRender();
    };

    window.adminBedroomsAdjustCount = function (delta) {
        if (!window.adminBedroomsState) {
            window.initAdminBedrooms(true);
        }
        var next = window.adminBedroomsState.bedrooms.length + delta;
        window.adminBedroomsSetCount(next);
    };

    window.initAdminBedrooms = function (resetFromServer) {
        var root = document.getElementById('adminBedroomsRoot');
        if (!root) {
            return;
        }

        if (!window.adminBedroomsState || resetFromServer) {
            window.adminBedroomsState = { bedrooms: readInitialBedrooms() };
        } else if (document.getElementById('adminBedroomsList')?.children.length) {
            window.adminBedroomsSyncFromDom();
        }

        window.adminBedroomsRender();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            window.initAdminBedrooms(true);
        });
    } else {
        window.initAdminBedrooms(true);
    }
})();
