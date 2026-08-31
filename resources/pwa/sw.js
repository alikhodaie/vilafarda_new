/* eslint-disable no-restricted-globals */
/**
 * Service Worker نسخه موبایل/PWA.
 * تصاویر /files را عمداً اینجا کش نمی‌کنیم: Safari روی iOS اگر fetch عکس
 * از SW رد شود اغلب آیکون شکسته (?) نشان می‌دهد یا لود را خیلی دیر می‌کند.
 * اگر منطق کش استاتیک را عوض کردید STATIC_CACHE را یک واحد بالا ببرید.
 */
var STATIC_CACHE = 'rentnaab-static-v2';
var PRECACHE_URLS = ['/offline'];
var CDN_HOSTS = ['cdn.jsdelivr.net'];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(STATIC_CACHE).then(function (cache) {
            return cache.addAll(PRECACHE_URLS);
        }).then(function () {
            return self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(keys.map(function (key) {
                if (key !== STATIC_CACHE) {
                    return caches.delete(key);
                }
                return undefined;
            }));
        }).then(function () {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function (event) {
    var request = event.request;
    if (request.method !== 'GET') {
        return;
    }

    var url;
    try {
        url = new URL(request.url);
    } catch (e) {
        return;
    }

    if (url.protocol !== 'http:' && url.protocol !== 'https:') {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(networkFirstNavigation(request));
        return;
    }

    if (shouldBypass(url) || isUncachedFile(url, request)) {
        return;
    }

    if (isCdnAsset(url) || isStaticAsset(url)) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
    }
});

function networkFirstNavigation(request) {
    return fetch(request).then(function (response) {
        return response;
    }).catch(function () {
        return caches.match('/offline').then(function (cached) {
            return cached || new Response('آفلاین هستید.', {
                status: 503,
                headers: { 'Content-Type': 'text/plain; charset=UTF-8' }
            });
        });
    });
}

function cacheFirst(request, cacheName) {
    return caches.match(request).then(function (cached) {
        if (cached) {
            return cached;
        }
        return fetch(request).then(function (response) {
            if (!isCacheableResponse(response)) {
                return response;
            }
            var copy = response.clone();
            caches.open(cacheName).then(function (cache) {
                cache.put(request, copy);
            });
            return response;
        });
    });
}

function isCacheableResponse(response) {
    return response && response.ok && response.type !== 'opaque' && response.status === 200;
}

function shouldBypass(url) {
    if (url.origin !== self.location.origin) {
        return CDN_HOSTS.indexOf(url.hostname) === -1;
    }

    var path = url.pathname;
    var skipPrefixes = [
        '/admin',
        '/dashboard',
        '/login',
        '/login-temp',
        '/register',
        '/payment',
        '/call-back',
        '/api/',
        '/hc/',
        '/livewire',
        '/broadcasting'
    ];

    return skipPrefixes.some(function (prefix) {
        if (prefix.charAt(prefix.length - 1) === '/') {
            return path.indexOf(prefix) === 0;
        }
        return path === prefix || path.indexOf(prefix + '/') === 0;
    });
}

function isUncachedFile(url, request) {
    if (url.origin !== self.location.origin) {
        return false;
    }

    if (url.pathname.indexOf('/files/') === 0) {
        return true;
    }

    return request.destination === 'image';
}

function isStaticAsset(url) {
    if (url.origin !== self.location.origin) {
        return false;
    }
    return /^\/(assets|vendor)\//.test(url.pathname);
}

function isCdnAsset(url) {
    return CDN_HOSTS.indexOf(url.hostname) !== -1;
}
