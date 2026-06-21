(function (window) {
    'use strict';

    var OSM_TILE_URL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var OSM_ATTRIBUTION = '© OpenStreetMap';

    function getConfig() {
        return window.mapConfig || {};
    }

    function usesNeshanSdk() {
        var cfg = getConfig();

        return cfg.usesNeshanSdk === true && !!cfg.neshanApiKey;
    }

    function tileUrl() {
        return getConfig().tileUrl || OSM_TILE_URL;
    }

    function tileAttribution() {
        return getConfig().attribution || OSM_ATTRIBUTION;
    }

    function geocoderEnabled() {
        return getConfig().geocoderEnabled !== false;
    }

    function resolveMapTarget(target) {
        if (typeof target === 'string') {
            return target;
        }

        if (!target) {
            throw new Error('Map target is required');
        }

        if (!target.id) {
            target.id = 'map-' + Math.random().toString(36).slice(2);
        }

        return target.id;
    }

    function neshanLeaflet() {
        return window.__neshanLeaflet || window.L;
    }

    function createMap(target, options) {
        var cfg = getConfig();
        var opts = options || {};
        var targetId = resolveMapTarget(target);
        var center = opts.center || [32.4279, 53.6880];
        var zoom = typeof opts.zoom === 'number' ? opts.zoom : 6;

        if (usesNeshanSdk()) {
            var NeshanL = neshanLeaflet();

            if (!NeshanL || typeof NeshanL.Map !== 'function') {
                throw new Error('Neshan Leaflet SDK is required when MAP_PROVIDER=neshan');
            }

            return new NeshanL.Map(targetId, {
                key: cfg.neshanApiKey,
                maptype: cfg.neshanMapType || 'neshan',
                center: center,
                zoom: zoom,
                poi: opts.poi !== false,
                traffic: !!opts.traffic,
                zoomControl: opts.zoomControl !== false,
                scrollWheelZoom: opts.scrollWheelZoom !== false,
            });
        }

        if (!window.L || typeof window.L.map !== 'function') {
            throw new Error('Leaflet is required for MapUtils.createMap');
        }

        var mapOptions = {};

        if (opts.zoomControl === false) {
            mapOptions.zoomControl = false;
        }

        if (opts.scrollWheelZoom === false) {
            mapOptions.scrollWheelZoom = false;
        }

        if (opts.attributionControl === false) {
            mapOptions.attributionControl = false;
        }

        var map = window.L.map(targetId, mapOptions);
        map.setView(center, zoom);
        createTileLayer(window.L, opts.tileLayerOptions).addTo(map);

        return map;
    }

    function createTileLayer(L, options) {
        if (usesNeshanSdk()) {
            throw new Error('Use MapUtils.createMap for Neshan maps');
        }

        if (!L || typeof L.tileLayer !== 'function') {
            throw new Error('Leaflet is required for MapUtils.createTileLayer');
        }

        var defaults = {
            attribution: tileAttribution(),
            maxZoom: getConfig().maxZoom || 19,
        };

        return L.tileLayer(tileUrl(), Object.assign(defaults, options || {}));
    }

    function reverseGeocode(lat, lng) {
        if (!geocoderEnabled()) {
            return Promise.resolve(null);
        }

        var baseUrl = getConfig().reverseGeocodeUrl || '/api/map/reverse';
        var url = baseUrl
            + (baseUrl.indexOf('?') >= 0 ? '&' : '?')
            + 'lat=' + encodeURIComponent(lat)
            + '&lng=' + encodeURIComponent(lng);

        return fetch(url, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(function (response) {
                if (!response.ok) {
                    return null;
                }

                return response.json();
            })
            .then(function (data) {
                if (!data) {
                    return null;
                }

                return data.address || data.display_name || null;
            })
            .catch(function () {
                return null;
            });
    }

    window.MapUtils = {
        getConfig: getConfig,
        usesNeshanSdk: usesNeshanSdk,
        neshanLeaflet: neshanLeaflet,
        tileUrl: tileUrl,
        tileAttribution: tileAttribution,
        geocoderEnabled: geocoderEnabled,
        createMap: createMap,
        createTileLayer: createTileLayer,
        reverseGeocode: reverseGeocode,
    };
})(window);
