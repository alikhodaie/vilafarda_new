(function () {
    function navigateWithParams(urlParams) {
        let newUrl = window.location.pathname;
        const queryString = urlParams.toString();
        if (queryString) {
            newUrl += '?' + queryString;
        }
        window.location.href = newUrl;
    }

    function applyTravelDates(payload) {
        const urlParams = new URLSearchParams(window.location.search);

        if (payload.start_at && payload.end_at) {
            urlParams.set('start_at', payload.start_at);
            urlParams.set('end_at', payload.end_at);
        } else {
            urlParams.delete('start_at');
            urlParams.delete('end_at');
        }

        urlParams.delete('page');
        navigateWithParams(urlParams);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!window.MapTravelFilter) {
            return;
        }

        MapTravelFilter.init({
            minDate: window.homesDateFilterConfig?.minDate || '',
            maxDate: window.homesDateFilterConfig?.maxDate || '',
            maxGuests: 20,
            labels: {
                start: window.homesDateFilterConfig?.startLabel || 'تاریخ ورود',
                end: window.homesDateFilterConfig?.endLabel || 'تاریخ خروج',
            },
        });

        MapTravelFilter.onDateApplied(function (payload) {
            const mapModal = document.getElementById('mapExplorerModal');
            if (mapModal && mapModal.classList.contains('show')) {
                return;
            }
            applyTravelDates(payload);
        });

        const dateBadge = document.getElementById('filterDateBadgeBtn');
        if (dateBadge) {
            const openDate = function () {
                const params = new URLSearchParams(window.location.search);
                MapTravelFilter.openDateFilter({
                    start_at: params.get('start_at') || '',
                    end_at: params.get('end_at') || '',
                });
            };

            dateBadge.addEventListener('click', openDate);
            dateBadge.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openDate();
                }
            });
        }
    });
})();
