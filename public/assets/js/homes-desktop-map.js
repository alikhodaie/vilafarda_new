(function () {
    const config = window.homesDesktopMapConfig || {};
    const mapEl = document.getElementById('desktopHomesMap');
    if (!mapEl || typeof L === 'undefined') {
        return;
    }

    const state = {
        map: null,
        markers: [],
        userMarker: null,
        homes: [],
        selectedHomeId: null,
        filters: new URLSearchParams(window.location.search),
    };

    const HOME_MARKER_HTML = '<div style="background: #1a1a1a; width: 28px; height: 28px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-house-fill" style="color: white; font-size: 14px;"></i></div>';

    function formatMapPriceShort(price) {
        const n = Number(price) || 0;
        if (n >= 1000000) {
            const millions = n / 1000000;
            const text = millions % 1 === 0
                ? millions.toLocaleString('fa-IR', { maximumFractionDigits: 0 })
                : millions.toLocaleString('fa-IR', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
            return text + ' میلیون';
        }
        if (n >= 1000) {
            return Math.round(n / 1000).toLocaleString('fa-IR') + ' هزار';
        }
        return n.toLocaleString('fa-IR');
    }

    function createHomeMarkerIcon(home, isSelected) {
        if (isSelected) {
            return L.divIcon({
                className: 'map-price-marker',
                html: `<div class="map-price-bubble">${formatMapPriceShort(home.price)}</div>`,
                iconSize: [80, 32],
                iconAnchor: [40, 32],
            });
        }
        return L.divIcon({
            className: 'custom-home-marker',
            html: HOME_MARKER_HTML,
            iconSize: [28, 28],
            iconAnchor: [14, 14],
        });
    }

    function mapPreviewShell() {
        return document.querySelector('.homes-desktop-map-shell');
    }

    function hideMapPropertyPreview() {
        const preview = document.getElementById('desktopMapPropertyPreview');
        if (preview) {
            preview.style.display = 'none';
        }
        mapPreviewShell()?.classList.remove('has-map-preview');
        state.selectedHomeId = null;
        renderMapMarkers();
    }

    function showMapPropertyPreview(home) {
        const preview = document.getElementById('desktopMapPropertyPreview');
        if (!preview || !home) {
            return;
        }

        state.selectedHomeId = home.id;
        renderMapMarkers();

        document.getElementById('desktopMapPreviewImage').src = home.cover_path || '/images/placeholder.jpg';
        document.getElementById('desktopMapPreviewImage').alt = home.name;
        document.getElementById('desktopMapPreviewTitle').textContent = home.name;
        document.getElementById('desktopMapPreviewLink').href = home.link;

        const bedroomText = home.bedroom_count ? `${home.bedroom_count} خوابه` : '';
        const meterText = home.infrastructure_meter ? `${home.infrastructure_meter.toLocaleString('fa-IR')} متر` : '';
        const guestText = home.max_guests ? `تا ${home.max_guests} نفر` : '';
        const ratingText = (typeof hasGuestRating === 'function' && hasGuestRating(home))
            ? `<i class="bi bi-star-fill text-warning"></i> ${home.guest_score_display || home.guest_score} (${home.count_comments || 0} نظر مهمان)`
            : 'جدید';
        const metaParts = [bedroomText, meterText, guestText].filter(Boolean);
        document.getElementById('desktopMapPreviewMeta').innerHTML = `${metaParts.join(' · ')}${metaParts.length ? ' · ' : ''}${ratingText}`;

        const priceText = home.price
            ? `هر شب از ${Number(home.price).toLocaleString('fa-IR')} تومان`
            : 'قیمت تماس بگیرید';
        document.getElementById('desktopMapPreviewPrice').textContent = priceText;

        const badge = document.getElementById('desktopMapPreviewBadge');
        if (home.successful_bookings_count > 0) {
            badge.textContent = `+${home.successful_bookings_count.toLocaleString('fa-IR')} رزرو موفق`;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }

        preview.style.display = 'block';
        mapPreviewShell()?.classList.add('has-map-preview');
    }

    function clearMapMarkers() {
        if (!state.map) {
            return;
        }
        state.markers.forEach(marker => state.map.removeLayer(marker));
        state.markers = [];
        if (state.userMarker) {
            state.map.removeLayer(state.userMarker);
            state.userMarker = null;
        }
    }

    function renderMapMarkers() {
        if (!state.map) {
            return;
        }
        clearMapMarkers();

        state.homes.forEach(home => {
            const isSelected = state.selectedHomeId === home.id;
            const marker = L.marker([home.latitude, home.longitude], {
                icon: createHomeMarkerIcon(home, isSelected),
                zIndexOffset: isSelected ? 1000 : 0,
            }).addTo(state.map);

            marker.on('click', function (e) {
                L.DomEvent.stopPropagation(e);
                showMapPropertyPreview(home);
            });

            state.markers.push(marker);
        });
    }

    function updateMapResultsSummary(count, minPrice) {
        const summary = document.getElementById('desktopMapResultsSummary');
        if (!summary) {
            return;
        }
        if (!count) {
            summary.textContent = 'اقامتگاهی یافت نشد';
            return;
        }
        const minText = minPrice ? Number(minPrice).toLocaleString('fa-IR') : '0';
        summary.textContent = `${count.toLocaleString('fa-IR')} اقامتگاه از ${minText} تومان`;
    }

    function fitMapToHomes() {
        if (!state.map || !state.homes.length) {
            return;
        }
        const bounds = L.latLngBounds(state.homes.map(h => [h.latitude, h.longitude]));
        state.map.fitBounds(bounds, { padding: [60, 60], maxZoom: 14 });
    }

    function fitMapToProvinceOrHomes() {
        if (!state.map) {
            return;
        }

        const provinceId = state.filters.get('province');
        const provinceConfig = provinceId ? window.provinceMapCenters?.[provinceId] : null;

        if (!provinceConfig) {
            fitMapToHomes();
            return;
        }

        const maxZoom = provinceConfig.zoom || 8;

        if (state.homes.length >= 2) {
            const bounds = L.latLngBounds(state.homes.map(h => [h.latitude, h.longitude]));
            state.map.fitBounds(bounds, { padding: [60, 60], maxZoom: maxZoom });
            return;
        }

        if (state.homes.length === 1) {
            const home = state.homes[0];
            state.map.setView([home.latitude, home.longitude], maxZoom);
            return;
        }

        state.map.setView(
            [provinceConfig.latitude, provinceConfig.longitude],
            maxZoom
        );
    }

    function loadMapHomes() {
        const summary = document.getElementById('desktopMapResultsSummary');
        if (summary) {
            summary.textContent = 'در حال بارگذاری...';
        }
        hideMapPropertyPreview();

        const query = state.filters.toString();
        const url = `${config.mapDataUrl || '/homes/map-data'}${query ? '?' + query : ''}`;

        return fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('map-data failed');
                }
                return response.json();
            })
            .then(data => {
                state.homes = data.homes || [];
                updateMapResultsSummary(data.count || 0, data.min_price || 0);
                renderMapMarkers();
                fitMapToProvinceOrHomes();
            })
            .catch(() => {
                state.homes = [];
                updateMapResultsSummary(0, 0);
                if (summary) {
                    summary.textContent = 'خطا در بارگذاری نقشه';
                }
            });
    }

    function initDesktopMap() {
        if (state.map) {
            setTimeout(() => state.map.invalidateSize(), 150);
            return;
        }

        state.map = L.map('desktopHomesMap', {
            zoomControl: false,
        }).setView([35.6892, 51.3890], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
        }).addTo(state.map);

        state.map.on('click', hideMapPropertyPreview);
    }

    function refreshDesktopMapSize() {
        if (!state.map) {
            return;
        }
        state.map.invalidateSize({ pan: false });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initDesktopMap();
        loadMapHomes();
        setTimeout(refreshDesktopMapSize, 0);
        setTimeout(refreshDesktopMapSize, 300);

        document.getElementById('desktopMapZoomInBtn')?.addEventListener('click', () => {
            state.map?.zoomIn();
        });
        document.getElementById('desktopMapZoomOutBtn')?.addEventListener('click', () => {
            state.map?.zoomOut();
        });
        document.getElementById('desktopMapMyLocationBtn')?.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('مرورگر شما از GPS پشتیبانی نمی‌کند');
                return;
            }
            const btn = this;
            btn.disabled = true;
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    if (state.map) {
                        if (state.userMarker) {
                            state.map.removeLayer(state.userMarker);
                        }
                        const userIcon = L.divIcon({
                            className: 'custom-user-marker',
                            html: '<div style="background: #D39D1A; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-geo-alt-fill" style="color: white; font-size: 18px;"></i></div>',
                            iconSize: [32, 32],
                            iconAnchor: [16, 16],
                        });
                        state.userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(state.map);
                        state.map.setView([lat, lng], 13);
                    }
                    btn.disabled = false;
                },
                function (error) {
                    alert('خطا در دریافت موقعیت: ' + error.message);
                    btn.disabled = false;
                }
            );
        });
        document.getElementById('desktopMapPreviewCloseBtn')?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            hideMapPropertyPreview();
        });

        window.addEventListener('resize', refreshDesktopMapSize);

        if (typeof ResizeObserver !== 'undefined') {
            const mapPanel = document.querySelector('.homes-desktop-map-panel');
            if (mapPanel) {
                const mapResizeObserver = new ResizeObserver(refreshDesktopMapSize);
                mapResizeObserver.observe(mapPanel);
            }
        }
    });
})();
