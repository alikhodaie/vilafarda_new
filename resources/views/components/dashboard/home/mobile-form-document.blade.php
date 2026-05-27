@props(['home', 'requireUpload' => null])

@php
    $documents = ($home->relationLoaded('documents') ? $home->documents : $home->documents()->get()) ?? collect();
    if ($documents->isEmpty() && $home->document) {
        $legacyUrl = asset($home->getDocumentPath().$home->document);
        $documents = collect([(object) [
            'id' => 0,
            'name' => $home->document,
            'original_name' => $home->document,
            'url' => $legacyUrl,
            'is_image' => in_array(strtolower(pathinfo($home->document, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'heic', 'heif'], true),
            'is_pdf' => strtolower(pathinfo($home->document, PATHINFO_EXTENSION)) === 'pdf',
            'updated_at' => $home->updated_at,
        ]]);
    }
    $requireUpload = $requireUpload ?? $documents->isEmpty();
@endphp

<div class="card-mobile mb-3" id="mobileDocumentRoot">
    <h5 class="text-mobile-primary mb-3">
        <i class="bi bi-file-earmark-arrow-up me-2"></i>
        بارگذاری مدارک
    </h5>
    <p class="text-mobile-muted mb-3" style="font-size: 12px; line-height: 1.6;">
        تصویر یا PDF مدرک مالکیت را بارگذاری کنید (قولنامه، اجاره‌نامه، قبض آب، برق یا گاز).
        می‌توانید چند فایل جداگانه ارسال کنید؛ هر مدرک در لیست زیر نمایش داده می‌شود.
        تصاویر و فرمت HEIC قبل از ارسال به‌صورت خودکار سبک‌تر و به JPG تبدیل می‌شوند (معمولاً زیر ۱٫۵ مگابایت).
    </p>

    @if($documents->isNotEmpty())
        <div class="mb-3" id="existingDocumentsList">
            <span class="form-label-mobile d-block mb-2">مدارک بارگذاری‌شده ({{ $documents->count() }})</span>
            @foreach($documents as $doc)
                <div class="mobile-doc-preview-card mb-2" data-document-id="{{ $doc->id }}">
                    @if($doc->is_image)
                        <div class="mobile-doc-preview-media-wrap">
                            <a href="{{ $doc->url }}" target="_blank" rel="noopener" class="mobile-doc-preview-media-link">
                                <img src="{{ $doc->url }}?v={{ md5($doc->name.$doc->updated_at) }}"
                                     alt="مدرک اقامتگاه"
                                     class="mobile-doc-preview-image existing-doc-preview-image"
                                     style="width: 50px"
                                     decoding="async"
                                     onerror="window.mobileDocPreviewImageError && window.mobileDocPreviewImageError(this)">
                            </a>
                            <div class="mobile-doc-preview-fallback mobile-doc-preview-fallback-hidden d-none">
                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                <span>پیش‌نمایش در دسترس نیست؛ «باز کردن» را بزنید.</span>
                            </div>
                        </div>
                    @elseif($doc->is_pdf)
                        <iframe src="{{ $doc->url }}#toolbar=0" class="mobile-doc-preview-pdf" title="پیش‌نمایش PDF"></iframe>
                    @else
                        <div class="mobile-doc-preview-fallback">
                            <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                            <span>{{ $doc->original_name ?: $doc->name }}</span>
                        </div>
                    @endif
                    <div class="mobile-doc-preview-meta">
                        <span class="mobile-doc-preview-filename">{{ $doc->original_name ?: $doc->name }}</span>
                        <a href="{{ $doc->url }}" target="_blank" rel="noopener" class="mobile-doc-open-link">
                            <i class="bi bi-box-arrow-up-left"></i>
                            باز کردن
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mb-2">
        <label for="document" class="form-label-mobile">
            @if($documents->isNotEmpty())
                افزودن مدرک دیگر
            @else
                فایل مدرک
            @endif
            @if($requireUpload)<span class="text-danger">*</span>@endif
        </label>
        <input type="file" name="document" id="document" class="form-control form-control-mobile"
               accept="image/*,.pdf,application/pdf,.heic,.heif" @if($requireUpload) required @endif>
        @error('document')
            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
        @enderror
        <small id="documentCompressStatus" class="text-mobile-muted d-block mt-2" style="font-size: 12px; display: none;"></small>
    </div>

    <div id="newDocumentPreview" class="mobile-doc-new-preview" style="display: none;" aria-live="polite">
        <span class="form-label-mobile d-block mb-2">پیش‌نمایش فایل انتخاب‌شده (قبل از ذخیره)</span>
        <div class="mobile-doc-preview-card" id="newDocumentPreviewBody"></div>
        <button type="button" class="btn btn-sm btn-outline-danger mt-2 w-100" id="clearNewDocumentBtn">
            <i class="bi bi-x-circle me-1"></i>
            حذف انتخاب
        </button>
    </div>
</div>

<style>
.mobile-doc-preview-card {
    border: 1px solid #e7e7e7;
    border-radius: 12px;
    overflow: hidden;
    background: #fafafa;
}
.mobile-doc-preview-media-link {
    display: block;
    line-height: 0;
}
.mobile-doc-preview-image {
    display: block;
    width: auto;
    max-width: 100%;
    height: auto;
    max-height: 140px;
    margin: 0 auto;
    object-fit: contain;
    background: #f0f0f0;
}
.mobile-doc-preview-media-wrap {
    min-height: 0;
    max-height: 160px;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f0f0;
    overflow: hidden;
}
.mobile-doc-preview-pdf {
    width: 100%;
    height: 160px;
    border: 0;
    background: #fff;
}
.mobile-doc-preview-fallback {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 16px 12px;
    color: #666;
    font-size: 13px;
    word-break: break-all;
    text-align: center;
}
.mobile-doc-preview-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 10px 12px;
    border-top: 1px solid #ececec;
    background: #fff;
    font-size: 12px;
}
.mobile-doc-preview-filename {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #444;
}
.mobile-doc-open-link {
    flex-shrink: 0;
    color: #D39D1A;
    text-decoration: none;
    font-weight: 500;
}
.mobile-doc-new-preview .mobile-doc-preview-meta {
    margin-top: 0;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js" defer></script>
<script src="{{ asset('assets/js/mobile-document-compress.js') }}" defer></script>

<script>
(function () {
    let newDocumentObjectUrl = null;
    let documentCompressBusy = false;

    window.mobileDocPreviewImageError = function (img) {
        if (!img) return;
        img.style.display = 'none';
        const wrap = img.closest('.mobile-doc-preview-media-wrap');
        const fallback = wrap ? wrap.querySelector('.mobile-doc-preview-fallback-hidden') : null;
        if (fallback) {
            fallback.classList.remove('d-none');
        }
    };

    window.refreshExistingDocumentPreview = function () {
        document.querySelectorAll('.existing-doc-preview-image').forEach(function (img) {
            if (!img.getAttribute('src')) {
                return;
            }
            const base = img.getAttribute('src').split('?')[0];
            img.style.display = 'block';
            const wrap = img.closest('.mobile-doc-preview-media-wrap');
            const fallback = wrap ? wrap.querySelector('.mobile-doc-preview-fallback-hidden') : null;
            if (fallback) {
                fallback.classList.add('d-none');
            }
            img.src = base + '?v=' + Date.now();
        });
    };

    function revokeNewDocumentUrl() {
        if (newDocumentObjectUrl) {
            URL.revokeObjectURL(newDocumentObjectUrl);
            newDocumentObjectUrl = null;
        }
    }

    function formatFileSize(bytes) {
        const n = Number(bytes) || 0;
        if (n < 1024) {
            return n + ' بایت';
        }
        if (n < 1024 * 1024) {
            return (n / 1024).toFixed(1).replace(/\./g, '٫') + ' کیلوبایت';
        }
        return (n / (1024 * 1024)).toFixed(2).replace(/\./g, '٫') + ' مگابایت';
    }

    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/"/g, '&quot;');
    }

    function isPdfFile(file) {
        if (!file) return false;
        const name = (file.name || '').toLowerCase();
        return file.type === 'application/pdf' || name.endsWith('.pdf');
    }

    function isImageFile(file) {
        if (!file) return false;
        if (file.type && file.type.startsWith('image/')) {
            return true;
        }
        return /\.(jpe?g|png|gif|webp|bmp|heic|heif)$/i.test(file.name || '');
    }

    function setDocumentCompressStatus(message) {
        const el = document.getElementById('documentCompressStatus');
        if (!el) return;
        if (message) {
            el.textContent = message;
            el.style.display = 'block';
        } else {
            el.textContent = '';
            el.style.display = 'none';
        }
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

    function renderNewDocumentPreview(file, originalSize) {
        const wrap = document.getElementById('newDocumentPreview');
        const body = document.getElementById('newDocumentPreviewBody');
        const input = document.getElementById('document');
        if (!wrap || !body || !input || !file) {
            return;
        }

        revokeNewDocumentUrl();
        newDocumentObjectUrl = URL.createObjectURL(file);

        let mediaHtml = '';
        if (isImageFile(file)) {
            mediaHtml = '<div class="mobile-doc-preview-media-wrap"><img src="' + newDocumentObjectUrl + '" alt="پیش‌نمایش مدرک" class="mobile-doc-preview-image" style="width: 50px"></div>';
        } else if (isPdfFile(file)) {
            mediaHtml = '<iframe src="' + newDocumentObjectUrl + '#toolbar=0" class="mobile-doc-preview-pdf" title="پیش‌نمایش PDF"></iframe>';
        } else {
            mediaHtml = '<div class="mobile-doc-preview-fallback"><i class="bi bi-file-earmark" style="font-size:2rem;"></i><span>' + escapeHtml(file.name) + '</span></div>';
        }

        body.innerHTML = mediaHtml + '<div class="mobile-doc-preview-meta">' +
            '<span class="mobile-doc-preview-filename">' + escapeHtml(file.name) + '</span>' +
            '<span class="text-mobile-muted">' + (originalSize && originalSize > file.size
                ? formatFileSize(originalSize) + ' ← ' + formatFileSize(file.size) + ' (بهینه‌شده)'
                : formatFileSize(file.size)) + '</span>' +
            '</div>';

        wrap.style.display = 'block';
    }

    async function processDocumentFile(file) {
        const input = document.getElementById('document');
        if (!file || !input) return;

        const originalSize = file.size;

        if (isPdfFile(file) || !isImageFile(file)) {
            renderNewDocumentPreview(file, null);
            return;
        }

        if (typeof window.compressDocumentForUpload !== 'function') {
            renderNewDocumentPreview(file, null);
            return;
        }

        documentCompressBusy = true;
        setDocumentCompressStatus('در حال بهینه‌سازی و تبدیل مدرک…');

        try {
            const processed = await window.compressDocumentForUpload(file);
            if (processed) {
                setFileInputFromFile(input, processed);
                renderNewDocumentPreview(processed, processed.size < originalSize ? originalSize : null);
            } else {
                renderNewDocumentPreview(file, null);
            }
        } catch (e) {
            console.warn('document compress failed', e);
            renderNewDocumentPreview(file, null);
        } finally {
            documentCompressBusy = false;
            setDocumentCompressStatus('');
        }
    }

    function clearNewDocumentPreview() {
        const wrap = document.getElementById('newDocumentPreview');
        const body = document.getElementById('newDocumentPreviewBody');
        const input = document.getElementById('document');
        revokeNewDocumentUrl();
        if (body) {
            body.innerHTML = '';
        }
        if (wrap) {
            wrap.style.display = 'none';
        }
        if (input) {
            input.value = '';
        }
        setDocumentCompressStatus('');
    }

    window.initMobileDocumentPreview = function () {
        const root = document.getElementById('mobileDocumentRoot');
        const input = document.getElementById('document');
        const clearBtn = document.getElementById('clearNewDocumentBtn');
        if (!root || !input) {
            return;
        }
        if (root.dataset.docPreviewBound === '1') {
            return;
        }
        root.dataset.docPreviewBound = '1';

        input.addEventListener('change', function () {
            if (documentCompressBusy) {
                return;
            }
            const file = input.files && input.files[0];
            if (!file) {
                clearNewDocumentPreview();
                return;
            }
            processDocumentFile(file);
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                clearNewDocumentPreview();
            });
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.initMobileDocumentPreview);
    } else {
        window.initMobileDocumentPreview();
    }
})();
</script>
