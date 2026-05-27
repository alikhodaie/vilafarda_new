(function () {
    var map;
    var marker;
    var previewMap = null;
    var previewMarker = null;

    function updateLocationDisplay(lat, lng, renderMap) {
        var wrap = document.getElementById('adminLocationPreviewWrap');
        var locationText = document.getElementById('adminLocationText');
        var latDisplay = document.getElementById('admin_latitude_display');
        var lngDisplay = document.getElementById('admin_longitude_display');
        var latInput = document.getElementById('latitude');
        var lngInput = document.getElementById('longitude');

        var latFixed = Number(lat).toFixed(6);
        var lngFixed = Number(lng).toFixed(6);

        if (latInput) {
            latInput.value = latFixed;
        }
        if (lngInput) {
            lngInput.value = lngFixed;
        }
        if (latDisplay) {
            latDisplay.value = latFixed;
        }
        if (lngDisplay) {
            lngDisplay.value = lngFixed;
        }

        if (locationText) {
            locationText.textContent = 'عرض: ' + latFixed + ' — طول: ' + lngFixed;
        }
        if (wrap) {
            wrap.style.display = 'block';
        }

        var locPane = document.getElementById('tab-location');
        var shouldRenderMap = renderMap !== false && locPane && locPane.classList.contains('active');

        if (shouldRenderMap) {
            renderLocationPreviewMap(parseFloat(latFixed), parseFloat(lngFixed));
        }

        getAddressFromCoordinates(parseFloat(latFixed), parseFloat(lngFixed));
    }

    function renderLocationPreviewMap(lat, lng) {
        var wrap = document.getElementById('adminLocationPreviewWrap');
        var container = document.getElementById('adminLocationPreviewMap');
        if (!wrap || !container || typeof L === 'undefined') {
            return;
        }

        wrap.style.display = 'block';

        if (!previewMap) {
            previewMap = L.map(container, {
                zoomControl: true,
                attributionControl: true,
            }).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
            }).addTo(previewMap);
            previewMarker = L.marker([lat, lng]).addTo(previewMap);
        } else {
            previewMap.setView([lat, lng], 15);
            if (previewMarker) {
                previewMarker.setLatLng([lat, lng]);
            } else {
                previewMarker = L.marker([lat, lng]).addTo(previewMap);
            }
        }

        setTimeout(function () {
            if (previewMap) {
                previewMap.invalidateSize();
            }
        }, 250);
    }

    function getAddressFromCoordinates(lat, lng) {
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&accept-language=fa')
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (!data.display_name) {
                    return;
                }
                var locationText = document.getElementById('adminLocationText');
                if (!locationText) {
                    return;
                }
                locationText.textContent = 'عرض: ' + lat.toFixed(6) + ' — طول: ' + lng.toFixed(6) + ' · ' + data.display_name;
            })
            .catch(function () {
                //
            });
    }

    window.adminRefreshLocationPreview = function () {
        var lat = parseFloat(document.getElementById('latitude')?.value || '');
        var lng = parseFloat(document.getElementById('longitude')?.value || '');
        if (!isNaN(lat) && !isNaN(lng)) {
            renderLocationPreviewMap(lat, lng);
        }
    };

    window.adminSaveMapLocation = function () {
        if (marker) {
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;
            updateLocationDisplay(lat, lng);
            var modalEl = document.getElementById('adminMapModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                var modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            }
        } else {
            alert('لطفاً روی نقشه کلیک کنید تا موقعیت را انتخاب کنید');
        }
    };

    function initAdminMapModal() {
        var modalEl = document.getElementById('adminMapModal');
        if (!modalEl || typeof L === 'undefined') {
            return;
        }

        modalEl.addEventListener('shown.bs.modal', function () {
            var latVal = document.getElementById('latitude')?.value;
            var lngVal = document.getElementById('longitude')?.value;
            var hasCoords = latVal && lngVal && latVal !== '' && lngVal !== '';

            if (!map) {
                map = L.map('adminMap').setView([32.4279, 53.6880], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                }).addTo(map);

                map.on('click', function (e) {
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker(e.latlng).addTo(map);
                });
            }

            if (hasCoords) {
                var lat = parseFloat(latVal);
                var lng = parseFloat(lngVal);
                if (!isNaN(lat) && !isNaN(lng)) {
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);
                }
            } else if (marker) {
                map.removeLayer(marker);
                marker = null;
                map.setView([32.4279, 53.6880], 6);
            }

            setTimeout(function () {
                if (map) {
                    map.invalidateSize();
                }
            }, 200);
        });

        var latInput = document.getElementById('latitude');
        var lngInput = document.getElementById('longitude');
        if (latInput && lngInput) {
            ['change', 'input'].forEach(function (evt) {
                latInput.addEventListener(evt, syncCoordsFromInputs);
                lngInput.addEventListener(evt, syncCoordsFromInputs);
            });
        }

        var initialLat = parseFloat(document.getElementById('latitude')?.value || '');
        var initialLng = parseFloat(document.getElementById('longitude')?.value || '');
        if (!isNaN(initialLat) && !isNaN(initialLng)) {
            updateLocationDisplay(initialLat, initialLng, false);
        }
    }

    function syncCoordsFromInputs() {
        var lat = parseFloat(document.getElementById('latitude')?.value || '');
        var lng = parseFloat(document.getElementById('longitude')?.value || '');
        if (!isNaN(lat) && !isNaN(lng)) {
            updateLocationDisplay(lat, lng, false);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminMapModal);
    } else {
        initAdminMapModal();
    }
})();
