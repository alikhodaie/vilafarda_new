(function () {
    'use strict';

    var HOME_MARKER_HTML = '<div style="background: #1a1a1a; width: 28px; height: 28px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-house-fill" style="color: white; font-size: 14px;"></i></div>';

    function createHomeMarkerIcon() {
        return L.divIcon({
            className: 'custom-home-marker',
            html: HOME_MARKER_HTML,
            iconSize: [28, 28],
            iconAnchor: [14, 14],
        });
    }

    function initRentLocationMap() {
        var mapEl = document.getElementById('rentLocationMap');
        if (!mapEl || typeof L === 'undefined') {
            return;
        }

        var card = document.getElementById('rentLocationCard');
        if (!card) {
            return;
        }

        var lat = parseFloat(card.dataset.lat);
        var lng = parseFloat(card.dataset.lng);
        if (!isFinite(lat) || !isFinite(lng)) {
            return;
        }

        var isPrecise = card.dataset.locationMode === 'precise';
        var radius = 750;

        var map = L.map(mapEl, {
            zoomControl: true,
            attributionControl: true,
            scrollWheelZoom: false,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        var fitView;

        if (isPrecise) {
            L.marker([lat, lng], { icon: createHomeMarkerIcon() }).addTo(map);

            fitView = function () {
                map.invalidateSize({ pan: false });
                map.setView([lat, lng], 16);
            };
        } else {
            L.circle([lat, lng], {
                radius: radius,
                color: '#D39D1A',
                fillColor: '#D39D1A',
                fillOpacity: 0.22,
                weight: 2,
            }).addTo(map);

            fitView = function () {
                map.invalidateSize({ pan: false });
                var bounds = L.circle([lat, lng], { radius: radius }).getBounds();
                map.fitBounds(bounds, { padding: [24, 24], maxZoom: 14 });
            };
        }

        fitView();
        setTimeout(fitView, 200);
        setTimeout(fitView, 800);

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        fitView();
                    }
                });
            }, { threshold: 0.15 });
            observer.observe(mapEl);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRentLocationMap);
    } else {
        initRentLocationMap();
    }
})();
