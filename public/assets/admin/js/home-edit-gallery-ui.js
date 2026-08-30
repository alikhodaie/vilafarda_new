(function () {
    var C = window.HomeImageCompress;
    var selectedCoverItem = null;
    var selectedGalleryItems = [];
    var formSubmitBusy = false;

    function looksLikeRasterImage(file) {
        return C ? C.isRasterImageFile(file) : false;
    }

    function initGalleryBulkControls() {
        var bulkForm = document.getElementById('form-bulk-delete-images');
        var deleteAllForm = document.getElementById('form-delete-all-images');
        var selectAll = document.getElementById('admin-gallery-select-all');
        var deleteBtn = document.getElementById('admin-gallery-bulk-delete-btn');
        var deleteAllBtn = document.getElementById('admin-gallery-delete-all-btn');
        var hint = document.getElementById('admin-gallery-selected-hint');

        function imageCheckboxes() {
            return document.querySelectorAll('.admin-gallery-check');
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

        function syncBulkFormIds() {
            if (!bulkForm) {
                return [];
            }
            bulkForm.querySelectorAll('input[name="ids[]"]').forEach(function (el) {
                el.remove();
            });
            var ids = [];
            imageCheckboxes().forEach(function (cb) {
                if (!cb.checked) {
                    return;
                }
                ids.push(cb.value);
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                bulkForm.appendChild(input);
            });
            return ids;
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                imageCheckboxes().forEach(function (cb) {
                    cb.checked = selectAll.checked;
                });
                refresh();
            });
        }

        document.addEventListener('change', function (e) {
            if (e.target && e.target.classList && e.target.classList.contains('admin-gallery-check')) {
                refresh();
            }
        });

        if (deleteBtn && bulkForm) {
            deleteBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var ids = syncBulkFormIds();
                if (!ids.length) {
                    return;
                }
                if (!confirm('تصاویر انتخاب‌شده برای همیشه حذف شوند؟')) {
                    return;
                }
                bulkForm.submit();
            });
        }

        if (deleteAllBtn && deleteAllForm) {
            deleteAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var msg = deleteAllBtn.getAttribute('data-confirm') ||
                    'همه تصاویر این اقامتگاه برای همیشه حذف می‌شوند. ادامه می‌دهید؟';
                if (!confirm(msg)) {
                    return;
                }
                deleteAllForm.submit();
            });
        }

        refresh();
    }

    function revokeUrl(imgEl) {
        if (imgEl && imgEl.src && imgEl.src.indexOf('blob:') === 0) {
            URL.revokeObjectURL(imgEl.src);
        }
    }

    function initCoverPreview() {
        var coverInput = document.getElementById('cover');
        var coverNameEl = document.getElementById('cover-file-name');
        var previewWrap = document.getElementById('cover-preview-wrap');
        var previewImg = document.getElementById('cover-preview-img');
        var captionEl = document.getElementById('cover-preview-caption');
        var currentThumb = document.getElementById('cover-current-thumb');
        if (!coverInput || !C) {
            return;
        }

        coverInput.addEventListener('change', async function () {
            var file = this.files && this.files[0];
            if (previewImg) {
                revokeUrl(previewImg);
                previewImg.src = '';
            }
            if (previewWrap) {
                previewWrap.classList.add('d-none');
            }
            selectedCoverItem = null;
            if (coverNameEl) {
                coverNameEl.textContent = '';
            }
            if (!file) {
                return;
            }
            if (!looksLikeRasterImage(file)) {
                if (coverNameEl) {
                    coverNameEl.textContent = 'فقط فایل تصویری مجاز است.';
                }
                return;
            }

            try {
                selectedCoverItem = await C.prepareImageFile(file);
                C.setInputFiles(coverInput, [selectedCoverItem.file]);
                if (previewImg) {
                    previewImg.src = URL.createObjectURL(selectedCoverItem.file);
                    previewImg.classList.remove('d-none');
                }
                if (captionEl) {
                    captionEl.textContent = C.formatImageMetaLine(selectedCoverItem.meta);
                }
                if (coverNameEl) {
                    coverNameEl.textContent = C.formatImageMetaLine(selectedCoverItem.meta);
                }
            } catch (err) {
                selectedCoverItem = { file: file, meta: C.buildImageMeta(file, file) };
                if (previewImg) {
                    previewImg.src = URL.createObjectURL(file);
                    previewImg.classList.remove('d-none');
                }
                if (coverNameEl) {
                    coverNameEl.textContent = C.formatImageMetaLine(selectedCoverItem.meta);
                }
                if (captionEl) {
                    captionEl.textContent = C.formatImageMetaLine(selectedCoverItem.meta);
                }
            }
            if (previewWrap) {
                previewWrap.classList.remove('d-none');
            }
            if (currentThumb) {
                currentThumb.classList.add('d-none');
            }
        });
    }

    function renderGallerySelectionPreview() {
        var galleryPreview = document.getElementById('gallery-files-preview');
        if (!galleryPreview || !C) {
            return;
        }
        galleryPreview.innerHTML = '';
        selectedGalleryItems.forEach(function (item, index) {
            var col = document.createElement('div');
            col.className = 'col-6 col-md-3';
            var wrap = document.createElement('div');
            wrap.className = 'border rounded-3 overflow-hidden shadow-sm bg-white';
            var img = document.createElement('img');
            img.alt = item.meta.processedName;
            img.className = 'w-100 d-block';
            img.style.height = '120px';
            img.style.objectFit = 'cover';
            img.src = URL.createObjectURL(item.file);
            var cap = document.createElement('div');
            cap.className = 'small px-2 py-1 bg-light border-top text-secondary';
            cap.style.fontSize = '11px';
            cap.style.lineHeight = '1.5';
            cap.textContent = C.formatImageMetaLine(item.meta);
            wrap.appendChild(img);
            wrap.appendChild(cap);
            col.appendChild(wrap);
            galleryPreview.appendChild(col);
        });
    }

    function initGalleryFilePreview() {
        var galleryInput = document.getElementById('gallery');
        var galleryPreview = document.getElementById('gallery-files-preview');
        if (!galleryInput || !galleryPreview || !C) {
            return;
        }

        galleryInput.addEventListener('change', async function () {
            var batchWarnEl = document.getElementById('gallery-batch-warning');
            if (batchWarnEl) {
                batchWarnEl.classList.add('d-none');
                batchWarnEl.textContent = '';
            }
            if (!this.files || !this.files.length) {
                selectedGalleryItems = [];
                galleryPreview.innerHTML = '';
                return;
            }

            var maxBatch = parseInt(galleryInput.getAttribute('data-max-batch') || '20', 10);
            if (!maxBatch || maxBatch < 1) {
                maxBatch = 20;
            }
            var files = Array.prototype.slice.call(this.files);
            if (files.length > maxBatch && batchWarnEl) {
                batchWarnEl.textContent =
                    'تعداد انتخاب‌شده (' +
                    files.length +
                    ') از حد مجاز PHP برای یک درخواست (' +
                    maxBatch +
                    ' فایل) بیشتر است؛ فقط حدود ' +
                    maxBatch +
                    ' فایل اول سرور می‌گیرد. دسته را تقسیم کنید.';
                batchWarnEl.classList.remove('d-none');
                files = files.slice(0, maxBatch);
            }

            selectedGalleryItems = await C.prepareFiles(files, { title: 'در حال پردازش گالری تصاویر' });
            C.setInputFiles(galleryInput, selectedGalleryItems.map(function (item) { return item.file; }));
            renderGallerySelectionPreview();
        });
    }

    function needsManualSubmit() {
        var coverInput = document.getElementById('cover');
        var galleryInput = document.getElementById('gallery');
        if (selectedCoverItem && (!coverInput || !coverInput.files || !coverInput.files.length)) {
            return true;
        }
        if (selectedGalleryItems.length && (!galleryInput || !galleryInput.files || !galleryInput.files.length)) {
            return true;
        }
        return false;
    }

    function bindFormSubmit(formId, options) {
        var form = document.getElementById(formId);
        if (!form || !C) {
            return;
        }
        options = options || {};
        var coverRequired = !!options.coverRequired;

        form.addEventListener('submit', async function (e) {
            if (formSubmitBusy) {
                e.preventDefault();
                return;
            }

            var coverInput = document.getElementById('cover');
            var galleryInput = document.getElementById('gallery');
            var submitBtn = form.querySelector('#adminHomeEditSubmit, #adminHomeCreateSubmit, button[type="submit"]');

            if (coverRequired && !selectedCoverItem && !(coverInput && coverInput.files && coverInput.files.length)) {
                e.preventDefault();
                alert('انتخاب تصویر کاور الزامی است.');
                return;
            }

            if (!selectedCoverItem && coverInput && !coverRequired) {
                coverInput.disabled = true;
            }

            e.preventDefault();
            formSubmitBusy = true;
            if (submitBtn) {
                submitBtn.disabled = true;
            }

            try {
                if (selectedCoverItem) {
                    C.setInputFiles(coverInput, [selectedCoverItem.file]);
                    if (coverInput) {
                        coverInput.disabled = false;
                    }
                }
                if (selectedGalleryItems.length && galleryInput) {
                    C.setInputFiles(galleryInput, selectedGalleryItems.map(function (item) { return item.file; }));
                }

                if (typeof window.prepareCompressedDocumentInput === 'function') {
                    await window.prepareCompressedDocumentInput('document');
                }

                if (needsManualSubmit()) {
                    C.showOverlay({
                        title: 'در حال ذخیره',
                        indeterminate: true,
                        fileName: '',
                    });
                    var fd = new FormData(form);
                    fd.delete('gallery[]');
                    fd.delete('gallery');
                    selectedGalleryItems.forEach(function (item) {
                        if (item && item.file) {
                            fd.append('gallery[]', item.file);
                        }
                    });
                    if (!selectedCoverItem) {
                        fd.delete('cover');
                    } else if (selectedCoverItem.file) {
                        fd.set('cover', selectedCoverItem.file);
                    }
                    var provinceSelect = document.getElementById('admin-home-province') || document.getElementById('province_id');
                    var citySelect = document.getElementById('admin-home-city') || document.getElementById('city_id');
                    if (provinceSelect && provinceSelect.value) {
                        if (provinceSelect.name) {
                            fd.set(provinceSelect.name, provinceSelect.value);
                        }
                    }
                    if (citySelect && citySelect.value) {
                        if (citySelect.name) {
                            fd.set(citySelect.name, citySelect.value);
                        }
                    } else if (citySelect && citySelect.getAttribute('data-current-city')) {
                        fd.set(citySelect.name, citySelect.getAttribute('data-current-city'));
                    }
                    var res = await fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        credentials: 'same-origin',
                        redirect: 'follow',
                    });
                    window.location.href = res.url;
                    return;
                }

                C.showOverlay({
                    title: 'در حال ذخیره',
                    indeterminate: true,
                    fileName: '',
                });
                form.submit();
            } catch (err) {
                formSubmitBusy = false;
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
                if (coverInput) {
                    coverInput.disabled = false;
                }
                C.hideOverlay();
                alert('ارسال انجام نشد. اتصال را بررسی کنید و دوباره ذخیره کنید.');
            }
        });
    }

    function initFormSubmit() {
        bindFormSubmit('admin-home-edit-form', { coverRequired: false });
        bindFormSubmit('admin-home-create-form', { coverRequired: true });
    }

    function run() {
        initGalleryBulkControls();
        initCoverPreview();
        initGalleryFilePreview();
        initFormSubmit();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
})();
