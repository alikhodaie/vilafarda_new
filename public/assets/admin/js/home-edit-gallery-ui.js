(function () {
    function looksLikeRasterImage(file) {
        if (!file) {
            return false;
        }
        if (/^image\//.test(file.type || '')) {
            return true;
        }
        var n = (file.name || '').toLowerCase();
        return /\.(jpe?g|png|gif|webp|bmp|heic|heif)$/.test(n);
    }

    /** HEIC و چند MIME خالی در مرورگر معمولاً در تگ img درست نمایش داده نمی‌شوند */
    function canBlobPreviewInBrowser(file) {
        var t = (file.type || '').toLowerCase();
        if (/^image\/(jpeg|png|gif|webp)$/.test(t)) {
            return true;
        }
        return false;
    }

    function revokeUrl(imgEl) {
        if (imgEl && imgEl.src && imgEl.src.indexOf('blob:') === 0) {
            URL.revokeObjectURL(imgEl.src);
        }
    }

    function initGalleryBulkControls() {
        var bulkForm = document.getElementById('form-bulk-delete-images');
        if (!bulkForm) {
            return;
        }
        var selectAll = document.getElementById('admin-gallery-select-all');
        var deleteBtn = document.getElementById('admin-gallery-bulk-delete-btn');
        var hint = document.getElementById('admin-gallery-selected-hint');

        function imageCheckboxes() {
            return bulkForm.querySelectorAll('input[type="checkbox"][name="ids[]"]');
        }

        function refresh() {
            var n = 0;
            imageCheckboxes().forEach(function (cb) {
                if (cb.checked) {
                    n++;
                }
                var tile = cb.closest('.admin-gallery-tile');
                if (tile) {
                    tile.classList.toggle('border-warning', cb.checked);
                }
            });
            if (deleteBtn) {
                deleteBtn.disabled = n === 0;
            }
            if (hint) {
                hint.textContent = n ? String(n) + ' مورد انتخاب شده' : '';
            }
            var total = imageCheckboxes().length;
            if (selectAll && total) {
                selectAll.checked = n === total;
                selectAll.indeterminate = n > 0 && n < total;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                imageCheckboxes().forEach(function (cb) {
                    cb.checked = selectAll.checked;
                });
                refresh();
            });
        }

        bulkForm.addEventListener('change', function (e) {
            if (e.target && e.target.name === 'ids[]') {
                refresh();
            }
        });

        refresh();
    }

    function initCoverPreview() {
        var coverInput = document.getElementById('cover');
        var coverNameEl = document.getElementById('cover-file-name');
        var previewWrap = document.getElementById('cover-preview-wrap');
        var previewImg = document.getElementById('cover-preview-img');
        var captionEl = document.getElementById('cover-preview-caption');
        var currentThumb = document.getElementById('cover-current-thumb');
        if (!coverInput || !previewWrap || !previewImg) {
            return;
        }

        coverInput.addEventListener('change', function () {
            revokeUrl(previewImg);
            previewImg.src = '';
            previewWrap.classList.add('d-none');
            if (coverNameEl) {
                coverNameEl.textContent = '';
            }
            if (captionEl) {
                captionEl.textContent = 'پیش‌نمایش کاور جدید';
            }
            if (!this.files || !this.files[0]) {
                return;
            }
            var f = this.files[0];
            if (!looksLikeRasterImage(f)) {
                if (coverNameEl) {
                    coverNameEl.textContent = 'فقط فایل تصویری مجاز است.';
                }
                return;
            }
            if (coverNameEl) {
                coverNameEl.textContent = 'فایل انتخاب‌شده: ' + f.name;
            }
            if (canBlobPreviewInBrowser(f)) {
                previewImg.src = URL.createObjectURL(f);
                previewImg.classList.remove('d-none');
                if (captionEl) {
                    captionEl.textContent = 'پیش‌نمایش کاور جدید';
                }
            } else {
                previewImg.src = '';
                previewImg.classList.add('d-none');
                if (captionEl) {
                    captionEl.textContent = 'پیش‌نمایش زنده برای این فرمت در مرورگر نیست؛ با ذخیرهٔ فرم، تصویر روی سرور به WebP ذخیره می‌شود.';
                }
            }
            previewWrap.classList.remove('d-none');
            if (currentThumb) {
                currentThumb.classList.add('d-none');
            }
        });
    }

    function initGalleryFilePreview() {
        var galleryInput = document.getElementById('gallery');
        var galleryPreview = document.getElementById('gallery-files-preview');
        if (!galleryInput || !galleryPreview) {
            return;
        }

        function renderGallerySelectionPreview() {
            galleryPreview.innerHTML = '';
            var batchWarnEl = document.getElementById('gallery-batch-warning');
            if (batchWarnEl) {
                batchWarnEl.classList.add('d-none');
                batchWarnEl.textContent = '';
            }
            if (!galleryInput.files || !galleryInput.files.length) {
                return;
            }
            var maxBatch = parseInt(galleryInput.getAttribute('data-max-batch') || '20', 10);
            if (!maxBatch || maxBatch < 1) {
                maxBatch = 20;
            }
            if (galleryInput.files.length > maxBatch && batchWarnEl) {
                batchWarnEl.textContent =
                    'تعداد انتخاب‌شده (' +
                    galleryInput.files.length +
                    ') از حد مجاز PHP برای یک درخواست (' +
                    maxBatch +
                    ' فایل) بیشتر است؛ فقط حدود ' +
                    maxBatch +
                    ' فایل اول سرور می‌گیرد و بقیه بی‌صدا کنار گذاشته می‌شوند. دسته را تقسیم کنید یا max_file_uploads را بالا ببرید (برای لوکال: composer run serve).';
                batchWarnEl.classList.remove('d-none');
            }
            Array.prototype.forEach.call(galleryInput.files, function (file) {
                if (!looksLikeRasterImage(file)) {
                    var warn = document.createElement('div');
                    warn.className = 'col-12 small text-warning';
                    warn.textContent = 'رد شد (غیر تصویر): ' + file.name;
                    galleryPreview.appendChild(warn);
                    return;
                }
                var col = document.createElement('div');
                col.className = 'col-6 col-md-3';
                var wrap = document.createElement('div');
                wrap.className = 'border rounded-3 overflow-hidden shadow-sm bg-white';
                var cap = document.createElement('div');
                cap.className = 'small text-truncate px-2 py-1 bg-light border-top text-secondary';
                cap.title = file.name;
                cap.textContent = file.name;
                if (canBlobPreviewInBrowser(file)) {
                    var img = document.createElement('img');
                    img.alt = file.name;
                    img.className = 'w-100 d-block';
                    img.style.height = '120px';
                    img.style.objectFit = 'cover';
                    img.src = URL.createObjectURL(file);
                    wrap.appendChild(img);
                } else {
                    var ph = document.createElement('div');
                    ph.className = 'small text-secondary text-center px-2 py-3 bg-light';
                    ph.style.minHeight = '120px';
                    ph.textContent = 'بدون پیش‌نمایش زنده برای این فرمت؛ پس از ذخیره در گالری نمایش داده می‌شود.';
                    wrap.appendChild(ph);
                }
                wrap.appendChild(cap);
                col.appendChild(wrap);
                galleryPreview.appendChild(col);
            });
        }

        galleryInput.addEventListener('change', renderGallerySelectionPreview);
        galleryInput.addEventListener('input', renderGallerySelectionPreview);
    }

    function run() {
        initGalleryBulkControls();
        initCoverPreview();
        initGalleryFilePreview();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
})();
