(function () {
    'use strict';

    function haversineKm(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = ((lat2 - lat1) * Math.PI) / 180;
        const dLon = ((lon2 - lon1) * Math.PI) / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos((lat1 * Math.PI) / 180) *
                Math.cos((lat2 * Math.PI) / 180) *
                Math.sin(dLon / 2) *
                Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function findNearestProvince(lat, lng, provinces) {
        if (!provinces || !provinces.length) {
            return null;
        }
        let nearest = null;
        let minDistance = Infinity;
        provinces.forEach(function (province) {
            const pLat = parseFloat(province.latitude);
            const pLng = parseFloat(province.longitude);
            if (!pLat || !pLng) {
                return;
            }
            const distance = haversineKm(lat, lng, pLat, pLng);
            if (distance < minDistance) {
                minDistance = distance;
                nearest = province;
            }
        });
        return nearest;
    }

    function buildHomesMapUrl(provinceId) {
        const base = document.getElementById('nearby-map-banner')?.dataset.homesUrl || '/homes';
        const url = new URL(base, window.location.origin);
        url.searchParams.set('map', '1');
        if (provinceId) {
            url.searchParams.set('province', String(provinceId));
        }
        return url.toString();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const banner = document.getElementById('nearby-map-banner');
        if (!banner) {
            return;
        }

        const provinces = Array.isArray(window.__provincesForNearbyMap)
            ? window.__provincesForNearbyMap
            : [];

        banner.addEventListener('click', function (e) {
            e.preventDefault();
            if (banner.classList.contains('is-loading')) {
                return;
            }

            const navigate = function (provinceId) {
                window.location.href = buildHomesMapUrl(provinceId);
            };

            if (!navigator.geolocation) {
                navigate(null);
                return;
            }

            banner.classList.add('is-loading');

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const province = findNearestProvince(lat, lng, provinces);
                    navigate(province ? province.id : null);
                },
                function () {
                    banner.classList.remove('is-loading');
                    navigate(null);
                },
                { enableHighAccuracy: false, timeout: 12000, maximumAge: 300000 }
            );
        });
    });
})();
