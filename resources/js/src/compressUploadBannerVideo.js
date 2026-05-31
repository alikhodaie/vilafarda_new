import { FFmpeg } from '@ffmpeg/ffmpeg';
import { fetchFile, toBlobURL } from '@ffmpeg/util';

const DEFAULT_TARGET_MAX_BYTES = 12 * 1024 * 1024;

const ENCODE_PROFILES = [
    { maxWidth: 1920, crf: 20, preset: 'fast' },
    { maxWidth: 1920, crf: 23, preset: 'fast' },
    { maxWidth: 1920, crf: 26, preset: 'fast' },
];

let ffmpegBundle = null;
let ffmpegLoadPromise = null;

function extensionFromFile(file) {
    const name = (file.name || '').toLowerCase();
    const match = name.match(/\.([a-z0-9]+)$/);
    if (match) {
        return match[1];
    }
    const type = (file.type || '').toLowerCase();
    if (type.includes('quicktime')) {
        return 'mov';
    }
    if (type.includes('webm')) {
        return 'webm';
    }
    return 'mp4';
}

function resolveFfmpegBaseUrl(ffmpegBaseUrl) {
    const raw = (ffmpegBaseUrl || '/vendor/ffmpeg').replace(/\/$/, '');
    if (raw.startsWith('http://') || raw.startsWith('https://')) {
        return raw;
    }
    const path = raw.startsWith('/') ? raw : `/${raw}`;

    return `${window.location.origin}${path}`;
}

async function loadFfmpeg(onProgress, ffmpegBaseUrl) {
    if (ffmpegBundle) {
        return ffmpegBundle;
    }

    if (!ffmpegLoadPromise) {
        ffmpegLoadPromise = (async () => {
            const base = resolveFfmpegBaseUrl(ffmpegBaseUrl);
            const ffmpeg = new FFmpeg();

            ffmpeg.on('progress', ({ progress }) => {
                if (typeof onProgress === 'function') {
                    onProgress(Math.min(99, Math.round((progress ?? 0) * 100)));
                }
            });

            await ffmpeg.load({
                coreURL: await toBlobURL(`${base}/ffmpeg-core.js`, 'text/javascript'),
                wasmURL: await toBlobURL(`${base}/ffmpeg-core.wasm`, 'application/wasm'),
            });

            ffmpegBundle = { ffmpeg, fetchFile };
            return ffmpegBundle;
        })().catch((error) => {
            ffmpegLoadPromise = null;
            throw error;
        });
    }

    return ffmpegLoadPromise;
}

function buildScaleFilter(maxWidth) {
    const maxHeight = 1080;

    return `scale=w='min(${maxWidth},iw)':h='min(${maxHeight},ih)':force_original_aspect_ratio=decrease,scale=trunc(iw/2)*2:trunc(ih/2)*2`;
}

async function encodeAttempt(ffmpeg, inputName, { maxWidth, crf, preset }) {
    const outputName = 'banner-output.mp4';

    try {
        await ffmpeg.deleteFile(outputName);
    } catch (e) {
        //
    }

    await ffmpeg.exec([
        '-y',
        '-i', inputName,
        '-an',
        '-vf', buildScaleFilter(maxWidth),
        '-r', '30',
        '-c:v', 'libx264',
        '-preset', preset,
        '-crf', String(crf),
        '-pix_fmt', 'yuv420p',
        '-movflags', '+faststart',
        outputName,
    ]);

    const data = await ffmpeg.readFile(outputName);
    await ffmpeg.deleteFile(outputName);

    return new Blob([data.buffer], { type: 'video/mp4' });
}

/**
 * فقط برای ویدئوهای بزرگ‌تر از سقف آپلود سرور (فشرده‌سازی در مرورگر).
 */
export async function compressUploadBannerVideo(file, hooks = {}) {
    if (!file) {
        throw new Error('فایلی انتخاب نشده است.');
    }

    const { onProgress, onStatus, ffmpegBaseUrl } = hooks;
    const targetMaxBytes = hooks.targetMaxBytes ?? DEFAULT_TARGET_MAX_BYTES;

    onStatus?.('در حال آماده‌سازی فشرده‌ساز ویدئو…');
    onProgress?.(0);

    let bundle;
    try {
        bundle = await loadFfmpeg(onProgress, ffmpegBaseUrl);
    } catch (e) {
        const detail = e && e.message ? ` (${e.message})` : '';
        const err = new Error(
            'فشرده‌سازی در مرورگر ممکن نشد.'
            + detail
            + ' فایل‌های public/vendor/ffmpeg (ffmpeg-core.js و .wasm) را روی سرور بررسی کنید.'
        );
        err.cause = e;
        err.code = 'FFMPEG_WASM_LOAD_FAILED';
        throw err;
    }

    const { ffmpeg, fetchFile } = bundle;
    const inputName = `input.${extensionFromFile(file)}`;

    onStatus?.('در حال خواندن فایل…');
    await ffmpeg.writeFile(inputName, await fetchFile(file));

    let outputBlob = null;

    for (const profile of ENCODE_PROFILES) {
        onStatus?.(`فشرده‌سازی (${profile.maxWidth}px، CRF ${profile.crf})…`);

        outputBlob = await encodeAttempt(ffmpeg, inputName, profile);

        if (outputBlob.size <= targetMaxBytes) {
            break;
        }
    }

    try {
        await ffmpeg.deleteFile(inputName);
    } catch (e) {
        //
    }

    if (!outputBlob || outputBlob.size < 1024) {
        throw new Error('فشرده‌سازی ویدئو نتیجه‌ای نداد.');
    }

    onProgress?.(100);
    onStatus?.(`آماده ارسال (${formatMb(outputBlob.size)} مگابایت)`);

    return new File([outputBlob], 'banners.mp4', {
        type: 'video/mp4',
        lastModified: Date.now(),
    });
}

function formatMb(bytes) {
    return (bytes / (1024 * 1024)).toFixed(1);
}
