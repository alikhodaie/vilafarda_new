(function () {
    if (!('serviceWorker' in navigator)) {
        return;
    }

    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js', {
            scope: '/',
            updateViaCache: 'none'
        }).catch(function () {
            // نصب Service Worker روی HTTP معمولی (غیر localhost) پشتیبانی نمی‌شود.
        });
    });
})();
