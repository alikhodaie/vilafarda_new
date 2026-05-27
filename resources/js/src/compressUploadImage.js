export function isRasterImageFile(file) {
    if (!file) {
        return false;
    }
    const type = (file.type || '').toLowerCase();
    const name = (file.name || '').toLowerCase();
    if (type.startsWith('image/')) {
        return true;
    }
    return /\.(jpe?g|png|gif|webp|bmp|heic|heif)$/i.test(name);
}

export function isHeicFile(file) {
    if (!file) {
        return false;
    }
    const type = (file.type || '').toLowerCase();
    const name = (file.name || '').toLowerCase();
    return /heic|heif/.test(type) || /\.heic$|\.heif$/.test(name);
}

/**
 * فشرده‌سازی تصویر در مرورگر قبل از ارسال فرم (resize + WebP/JPEG).
 *
 * @param {File} file
 * @param {{ maxEdge?: number, quality?: number, preferWebp?: boolean }} options
 * @returns {Promise<File>}
 */
export async function compressUploadImage(file, options = {}) {
    const maxEdge = options.maxEdge ?? 1200;
    const quality = options.quality ?? 0.85;
    const preferWebp = options.preferWebp !== false;

    if (!isRasterImageFile(file)) {
        return file;
    }

    if (file.type === 'image/svg+xml' || file.type === 'image/gif') {
        return file;
    }

    const mimeType = preferWebp && canvasSupportsWebp() ? 'image/webp' : 'image/jpeg';
    const extension = mimeType === 'image/webp' ? 'webp' : 'jpg';

    return new Promise((resolve) => {
        const url = URL.createObjectURL(file);
        const img = new Image();

        img.onload = () => {
            URL.revokeObjectURL(url);

            let width = img.naturalWidth || img.width;
            let height = img.naturalHeight || img.height;

            if (!width || !height) {
                resolve(file);
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
                resolve(file);
                return;
            }

            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob(
                (blob) => {
                    if (!blob || blob.size >= file.size) {
                        resolve(file);
                        return;
                    }

                    const baseName = (file.name || 'image').replace(/\.[^.]+$/, '');
                    resolve(new File([blob], `${baseName}.${extension}`, {
                        type: mimeType,
                        lastModified: Date.now(),
                    }));
                },
                mimeType,
                quality
            );
        };

        img.onerror = () => {
            URL.revokeObjectURL(url);
            resolve(file);
        };

        img.src = url;
    });
}

function canvasSupportsWebp() {
    try {
        const canvas = document.createElement('canvas');
        return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    } catch (e) {
        return false;
    }
}
