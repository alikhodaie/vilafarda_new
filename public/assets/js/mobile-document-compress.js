/**
 * فشرده‌سازی مدرک قبل از آپلود (JPEG + resize؛ تبدیل HEIC با heic2any).
 * هدف: زیر سقف پیش‌فرض PHP (upload_max_filesize معمولاً 2M).
 */
(function (global) {
    'use strict';

    const DOCUMENT_COMPRESS = {
        maxEdge: 1600,
        quality: 0.82,
        heicQuality: 0.88,
        minQuality: 0.5,
        targetBytes: 1400 * 1024,
        forceAboveBytes: 400 * 1024,
    };

    function isPdfFile(file) {
        if (!file) return false;
        const name = (file.name || '').toLowerCase();
        return file.type === 'application/pdf' || name.endsWith('.pdf');
    }

    function isRasterImageFile(file) {
        if (!file) return false;
        const type = (file.type || '').toLowerCase();
        const name = (file.name || '').toLowerCase();
        if (type.startsWith('image/')) return true;
        return /\.(jpe?g|png|gif|webp|bmp|heic|heif)$/i.test(name);
    }

    function isHeicFile(file) {
        if (!file) return false;
        const type = (file.type || '').toLowerCase();
        const name = (file.name || '').toLowerCase();
        return /heic|heif/.test(type) || /\.heic$|\.heif$/.test(name);
    }

    function setFileInputFromFile(input, file) {
        if (!input || !file) return;
        try {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
        } catch (e) {
            //
        }
    }

    function compressRasterFileOnce(file, maxEdge, quality) {
        return new Promise(function (resolve) {
            const url = URL.createObjectURL(file);
            const img = new Image();

            img.onload = function () {
                URL.revokeObjectURL(url);

                let width = img.naturalWidth || img.width;
                let height = img.naturalHeight || img.height;

                if (!width || !height) {
                    resolve(null);
                    return;
                }

                const scale = Math.min(maxEdge / width, maxEdge / height, 1);
                width = Math.max(1, Math.round(width * scale));
                height = Math.max(1, Math.round(height * scale));

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    resolve(null);
                    return;
                }

                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    function (blob) {
                        if (!blob) {
                            resolve(null);
                            return;
                        }
                        const baseName = (file.name || 'document').replace(/\.[^.]+$/i, '');
                        resolve(new File([blob], baseName + '.jpg', {
                            type: 'image/jpeg',
                            lastModified: Date.now(),
                        }));
                    },
                    'image/jpeg',
                    quality
                );
            };

            img.onerror = function () {
                URL.revokeObjectURL(url);
                resolve(null);
            };

            img.src = url;
        });
    }

    async function compressRasterToTarget(file) {
        if (!isRasterImageFile(file)) {
            return file;
        }

        const mustCompress = file.size > DOCUMENT_COMPRESS.forceAboveBytes;
        if (!mustCompress && file.size <= DOCUMENT_COMPRESS.targetBytes) {
            return file;
        }

        let maxEdge = DOCUMENT_COMPRESS.maxEdge;
        let quality = DOCUMENT_COMPRESS.quality;
        let best = file;

        for (let pass = 0; pass < 6; pass++) {
            const attempt = await compressRasterFileOnce(best, maxEdge, quality);
            if (attempt && attempt.size < best.size) {
                best = attempt;
            }

            if (best.size <= DOCUMENT_COMPRESS.targetBytes) {
                break;
            }

            quality = Math.max(DOCUMENT_COMPRESS.minQuality, quality - 0.1);
            maxEdge = Math.max(900, Math.round(maxEdge * 0.88));
        }

        return best;
    }

    async function convertHeicToJpeg(file) {
        if (!isHeicFile(file)) {
            return file;
        }

        const heic2any = global.heic2any;
        if (typeof heic2any !== 'function') {
            return file;
        }

        const result = await heic2any({
            blob: file,
            toType: 'image/jpeg',
            quality: DOCUMENT_COMPRESS.heicQuality,
        });

        const blob = Array.isArray(result) ? result[0] : result;
        if (!blob) {
            return file;
        }

        const baseName = (file.name || 'document').replace(/\.[^.]+$/i, '');
        return new File([blob], baseName + '.jpg', {
            type: 'image/jpeg',
            lastModified: Date.now(),
        });
    }

    global.compressDocumentForUpload = async function (file) {
        if (!file) {
            return file;
        }

        if (isPdfFile(file)) {
            if (file.size > DOCUMENT_COMPRESS.targetBytes * 2) {
                console.warn('PDF بزرگ است؛ در صورت خطا upload_max_filesize را افزایش دهید.');
            }
            return file;
        }

        if (!isRasterImageFile(file)) {
            return file;
        }

        let working = file;

        if (isHeicFile(file)) {
            try {
                working = await convertHeicToJpeg(file);
            } catch (e) {
                console.warn('HEIC conversion failed', e);
            }
        }

        return compressRasterToTarget(working);
    };

    global.prepareCompressedDocumentInput = async function (inputId) {
        const input = document.getElementById(inputId || 'document');
        if (!input || !input.files || !input.files[0]) {
            return;
        }
        const original = input.files[0];
        const processed = await global.compressDocumentForUpload(original);
        if (processed && processed !== original) {
            setFileInputFromFile(input, processed);
        }
    };
})(typeof window !== 'undefined' ? window : this);
