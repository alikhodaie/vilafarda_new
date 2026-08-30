(function (global) {
    'use strict';

    var IMAGE_COMPRESS = {
        maxEdge: 1280,
        quality: 0.82,
        skipBelowBytes: 350 * 1024,
        heicQuality: 0.88,
    };

    var overlayState = { total: 0, current: 0, indeterminate: true, fileName: '', title: 'در حال بهینه‌سازی تصاویر' };
    var overlayBusy = 0;

    function canUseDataTransfer() {
        return typeof DataTransfer !== 'undefined';
    }

    function canvasSupportsWebp() {
        try {
            var canvas = document.createElement('canvas');
            return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
        } catch (e) {
            return false;
        }
    }

    function getOutputImageFormat() {
        if (canvasSupportsWebp()) {
            return { mime: 'image/webp', ext: 'webp' };
        }
        return { mime: 'image/jpeg', ext: 'jpg' };
    }

    function isRasterImageFile(file) {
        if (!file) {
            return false;
        }
        var type = (file.type || '').toLowerCase();
        var name = (file.name || '').toLowerCase();
        if (type.indexOf('image/') === 0) {
            return true;
        }
        return /\.(jpe?g|png|gif|webp|bmp|heic|heif)$/i.test(name);
    }

    function isHeicFile(file) {
        if (!file) {
            return false;
        }
        var type = (file.type || '').toLowerCase();
        var name = (file.name || '').toLowerCase();
        return /heic|heif/.test(type) || /\.heic$|\.heif$/i.test(name);
    }

    function getFileExtensionLabel(file) {
        var name = (file && file.name) ? file.name : '';
        var ext = name.split('.').pop();
        return ext ? ext.toUpperCase() : 'تصویر';
    }

    function formatFileSize(bytes) {
        if (bytes >= 1024 * 1024) {
            return (bytes / 1024 / 1024).toFixed(1) + ' MB';
        }
        return Math.max(1, Math.round(bytes / 1024)) + ' KB';
    }

    function buildImageMeta(originalFile, processedFile) {
        var originalExt = getFileExtensionLabel(originalFile);
        var processedExt = getFileExtensionLabel(processedFile);
        return {
            originalName: originalFile.name || 'image',
            processedName: processedFile.name || originalFile.name || 'image.jpg',
            originalSize: originalFile.size || 0,
            processedSize: processedFile.size || 0,
            formatLabel: originalExt !== processedExt ? (originalExt + ' → ' + processedExt) : processedExt,
            optimized: (processedFile.size || 0) < (originalFile.size || 0),
        };
    }

    function formatImageMetaLine(meta) {
        var lines = [];
        lines.push(meta.processedName);
        if (meta.optimized && meta.originalSize > meta.processedSize) {
            lines.push(formatFileSize(meta.originalSize) + ' → ' + formatFileSize(meta.processedSize) + ' (بهینه‌شده)');
        } else {
            lines.push(formatFileSize(meta.processedSize));
        }
        lines.push('فرمت: ' + meta.formatLabel);
        return lines.join(' · ');
    }

    function shouldSkipClientCompress(file) {
        if (!isRasterImageFile(file)) {
            return true;
        }
        if (file.size <= IMAGE_COMPRESS.skipBelowBytes) {
            return true;
        }
        var type = (file.type || '').toLowerCase();
        var name = (file.name || '').toLowerCase();
        if (type === 'image/gif' || /\.gif$/i.test(name)) {
            return true;
        }
        return false;
    }

    function setInputFiles(input, files) {
        if (!input) {
            return false;
        }
        var list = Array.isArray(files) ? files.filter(Boolean) : [];
        if (!canUseDataTransfer()) {
            return list.length > 0 && input.files && input.files.length > 0;
        }
        try {
            var dt = new DataTransfer();
            list.forEach(function (file) {
                dt.items.add(file);
            });
            input.files = dt.files;
            return input.files && input.files.length === list.length;
        } catch (err) {
            return false;
        }
    }

    function ensureOverlay() {
        if (document.getElementById('imageCompressOverlay')) {
            return;
        }
        var wrap = document.createElement('div');
        wrap.innerHTML =
            '<div id="imageCompressOverlay" class="image-compress-overlay" hidden aria-live="polite" aria-busy="true">' +
                '<div class="image-compress-overlay__backdrop"></div>' +
                '<div class="image-compress-overlay__card" role="status">' +
                    '<div class="image-compress-overlay__icon-wrap" aria-hidden="true">' +
                        '<div class="image-compress-overlay__ring"></div>' +
                        '<div class="image-compress-overlay__ring image-compress-overlay__ring--delay"></div>' +
                        '<span class="fas fa-images image-compress-overlay__icon"></span>' +
                    '</div>' +
                    '<p class="image-compress-overlay__title" id="imageCompressOverlayTitle">در حال بهینه‌سازی تصاویر</p>' +
                    '<p class="image-compress-overlay__file" id="imageCompressOverlayFile"></p>' +
                    '<div class="image-compress-overlay__progress-track">' +
                        '<div class="image-compress-overlay__progress-bar" id="imageCompressOverlayBar"></div>' +
                    '</div>' +
                    '<p class="image-compress-overlay__progress-text" id="imageCompressOverlayProgress"></p>' +
                    '<p class="image-compress-overlay__thanks">از صبر و شکیبایی شما در فرایند فشرده‌سازی تصاویر سپاسگزاریم</p>' +
                '</div>' +
            '</div>';
        document.body.appendChild(wrap.firstChild);
    }

    function renderOverlay() {
        var overlay = document.getElementById('imageCompressOverlay');
        var titleEl = document.getElementById('imageCompressOverlayTitle');
        var fileEl = document.getElementById('imageCompressOverlayFile');
        var barEl = document.getElementById('imageCompressOverlayBar');
        var progressEl = document.getElementById('imageCompressOverlayProgress');
        if (!overlay || !titleEl || !fileEl || !barEl || !progressEl) {
            return;
        }
        if (overlayState.indeterminate || !overlayState.total) {
            barEl.style.width = '38%';
            barEl.classList.add('is-indeterminate');
            progressEl.textContent = 'لطفاً چند لحظه صبر کنید…';
        } else {
            barEl.classList.remove('is-indeterminate');
            var percent = Math.min(100, Math.round((overlayState.current / overlayState.total) * 100));
            barEl.style.width = percent + '%';
            progressEl.textContent = 'تصویر ' + overlayState.current + ' از ' + overlayState.total + ' (' + percent + '٪)';
        }
        fileEl.textContent = overlayState.fileName || '';
        if (overlayState.title) {
            titleEl.textContent = overlayState.title;
        }
    }

    function showOverlay(options) {
        ensureOverlay();
        var overlay = document.getElementById('imageCompressOverlay');
        if (!overlay) {
            return;
        }
        overlayState = Object.assign({
            total: 0,
            current: 0,
            indeterminate: true,
            fileName: '',
            title: 'در حال بهینه‌سازی تصاویر',
        }, options || {});
        overlay.hidden = false;
        renderOverlay();
    }

    function updateOverlay(patch) {
        overlayState = Object.assign({}, overlayState, patch || {});
        renderOverlay();
    }

    function hideOverlay() {
        var overlay = document.getElementById('imageCompressOverlay');
        if (overlay) {
            overlay.hidden = true;
        }
        overlayState = { total: 0, current: 0, indeterminate: true, fileName: '', title: 'در حال بهینه‌سازی تصاویر' };
        overlayBusy = 0;
    }

    function beginOverlay(message, options) {
        overlayBusy += 1;
        var payload = Object.assign({ title: message }, options || {});
        if (overlayBusy === 1) {
            showOverlay(payload);
        } else {
            updateOverlay(payload);
        }
    }

    function endOverlay() {
        overlayBusy = Math.max(0, overlayBusy - 1);
        if (overlayBusy === 0) {
            hideOverlay();
        }
    }

    async function convertHeicToJpeg(file) {
        if (typeof global.heic2any !== 'function') {
            throw new Error('heic2any unavailable');
        }
        var result = await global.heic2any({
            blob: file,
            toType: 'image/jpeg',
            quality: IMAGE_COMPRESS.heicQuality,
        });
        var blob = Array.isArray(result) ? result[0] : result;
        if (!blob) {
            throw new Error('heic conversion empty');
        }
        var baseName = (file.name || 'image').replace(/\.[^.]+$/i, '');
        return new File([blob], baseName + '.jpg', {
            type: 'image/jpeg',
            lastModified: Date.now(),
        });
    }

    function compressImageFile(file) {
        if (shouldSkipClientCompress(file)) {
            return Promise.resolve(file);
        }

        return new Promise(function (resolve) {
            var url = URL.createObjectURL(file);
            var img = new Image();
            var timer = setTimeout(function () {
                URL.revokeObjectURL(url);
                resolve(file);
            }, 25000);

            img.onload = function () {
                clearTimeout(timer);
                URL.revokeObjectURL(url);

                var w = img.naturalWidth;
                var h = img.naturalHeight;
                if (!w || !h) {
                    resolve(file);
                    return;
                }

                var maxEdge = IMAGE_COMPRESS.maxEdge;
                if (w > maxEdge || h > maxEdge) {
                    if (w >= h) {
                        h = Math.round(h * maxEdge / w);
                        w = maxEdge;
                    } else {
                        w = Math.round(w * maxEdge / h);
                        h = maxEdge;
                    }
                }

                var canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                var ctx = canvas.getContext('2d');
                if (!ctx) {
                    resolve(file);
                    return;
                }
                ctx.drawImage(img, 0, 0, w, h);

                var output = getOutputImageFormat();
                canvas.toBlob(function (blob) {
                    if (!blob || blob.size >= file.size * 0.95) {
                        resolve(file);
                        return;
                    }
                    var baseName = (file.name || 'image').replace(/\.[^.]+$/i, '');
                    resolve(new File([blob], baseName + '.' + output.ext, {
                        type: output.mime,
                        lastModified: Date.now(),
                    }));
                }, output.mime, IMAGE_COMPRESS.quality);
            };

            img.onerror = function () {
                clearTimeout(timer);
                URL.revokeObjectURL(url);
                resolve(file);
            };

            img.src = url;
        });
    }

    async function prepareImageFile(file, options) {
        var silent = options && options.silent;
        var originalFile = file;
        var working = file;

        if (isHeicFile(file)) {
            if (!silent) {
                beginOverlay('در حال تبدیل HEIC…', {
                    fileName: file.name,
                    indeterminate: true,
                });
            }
            try {
                working = await convertHeicToJpeg(file);
            } finally {
                if (!silent) {
                    endOverlay();
                }
            }
        }

        if (!silent) {
            beginOverlay('در حال بهینه‌سازی و تبدیل به WebP…', {
                fileName: working.name || file.name,
                indeterminate: true,
            });
        }
        var processed;
        try {
            processed = await compressImageFile(working);
        } finally {
            if (!silent) {
                endOverlay();
            }
        }

        return {
            file: processed,
            meta: buildImageMeta(originalFile, processed),
        };
    }

    async function prepareFiles(files, options) {
        var incoming = Array.prototype.slice.call(files || []).filter(isRasterImageFile);
        var items = [];
        if (!incoming.length) {
            return items;
        }
        showOverlay({
            title: (options && options.title) || 'در حال پردازش تصاویر',
            total: incoming.length,
            current: 0,
            indeterminate: false,
            fileName: incoming[0].name,
        });
        for (var i = 0; i < incoming.length; i++) {
            updateOverlay({
                current: i + 1,
                total: incoming.length,
                fileName: incoming[i].name,
                title: isHeicFile(incoming[i]) ? 'در حال تبدیل و فشرده‌سازی…' : 'در حال بهینه‌سازی تصاویر',
                indeterminate: false,
            });
            try {
                items.push(await prepareImageFile(incoming[i], { silent: true }));
            } catch (err) {
                items.push({
                    file: incoming[i],
                    meta: buildImageMeta(incoming[i], incoming[i]),
                });
            }
        }
        hideOverlay();
        return items;
    }

    global.HomeImageCompress = {
        IMAGE_COMPRESS: IMAGE_COMPRESS,
        isRasterImageFile: isRasterImageFile,
        isHeicFile: isHeicFile,
        formatImageMetaLine: formatImageMetaLine,
        formatFileSize: formatFileSize,
        buildImageMeta: buildImageMeta,
        setInputFiles: setInputFiles,
        canUseDataTransfer: canUseDataTransfer,
        ensureOverlay: ensureOverlay,
        showOverlay: showOverlay,
        updateOverlay: updateOverlay,
        hideOverlay: hideOverlay,
        prepareImageFile: prepareImageFile,
        prepareFiles: prepareFiles,
    };
})(window);
