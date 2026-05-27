(function () {
    var MAX_RESULTS = 180;

    function parseJsonAttr(el, name, fallback) {
        try {
            var raw = el.getAttribute(name);
            return raw ? JSON.parse(raw) : fallback;
        } catch (e) {
            return fallback;
        }
    }

    function normalizeQuery(query) {
        return (query || '').trim().toLowerCase();
    }

    function collectSearchTerms(query, aliases) {
        var terms = [];
        if (query) {
            terms.push(query);
        }

        Object.keys(aliases || {}).forEach(function (key) {
            var keyLower = key.toLowerCase();
            if (!query) {
                return;
            }
            if (keyLower.indexOf(query) !== -1 || query.indexOf(keyLower) !== -1) {
                (aliases[key] || []).forEach(function (fragment) {
                    if (fragment && terms.indexOf(fragment.toLowerCase()) === -1) {
                        terms.push(fragment.toLowerCase());
                    }
                });
            }
        });

        return terms;
    }

    function iconMatches(icon, terms) {
        if (!terms.length) {
            return false;
        }
        var name = icon.toLowerCase();
        return terms.some(function (term) {
            return term && name.indexOf(term) !== -1;
        });
    }

    function initHomeOptionIconField(root) {
        if (!root || root.dataset.iconPickerInit === '1') {
            return;
        }
        root.dataset.iconPickerInit = '1';

        var allIcons = parseJsonAttr(root, 'data-all-icons', []);
        var featuredIcons = parseJsonAttr(root, 'data-featured-icons', []);
        var aliases = parseJsonAttr(root, 'data-search-aliases', {});
        var selectedIcon = root.getAttribute('data-selected-icon') || (featuredIcons[0] || 'bi-house');

        var typeFont = root.querySelector('#icon_type_font');
        var typeImage = root.querySelector('#icon_type_image');
        var panelFont = root.querySelector('[data-icon-panel="font"]');
        var panelImage = root.querySelector('[data-icon-panel="image"]');
        var hiddenClass = root.querySelector('#icon_class');
        var previewIcon = root.querySelector('#home-option-icon-preview');
        var previewLabel = root.querySelector('#home-option-icon-label');
        var searchInput = root.querySelector('#home-option-icon-search');
        var grid = root.querySelector('#home-option-icon-grid');
        var hint = root.querySelector('#home-option-icon-hint');
        var fileInput = root.querySelector('#home-option-icon-file');
        var imagePreview = root.querySelector('#home-option-image-preview');
        var imagePlaceholder = root.querySelector('#home-option-image-placeholder');

        var featuredSet = {};
        featuredIcons.forEach(function (icon) {
            featuredSet[icon] = true;
        });

        function isFontMode() {
            return typeFont && typeFont.checked;
        }

        function syncPanels() {
            var font = isFontMode();
            if (panelFont) {
                panelFont.classList.toggle('d-none', !font);
            }
            if (panelImage) {
                panelImage.classList.toggle('d-none', font);
            }
            if (fileInput) {
                fileInput.disabled = font;
            }
        }

        function iconsForQuery(query) {
            var q = normalizeQuery(query);

            if (!q) {
                var base = featuredIcons.slice();
                if (selectedIcon && base.indexOf(selectedIcon) === -1) {
                    base.unshift(selectedIcon);
                }
                return { icons: base, truncated: false, mode: 'featured' };
            }

            var terms = collectSearchTerms(q, aliases);
            var matched = allIcons.filter(function (icon) {
                return iconMatches(icon, terms);
            });

            if (selectedIcon && matched.indexOf(selectedIcon) === -1 && iconMatches(selectedIcon, terms)) {
                matched.unshift(selectedIcon);
            } else if (selectedIcon && matched.indexOf(selectedIcon) === -1 && iconMatches(selectedIcon, [q])) {
                matched.unshift(selectedIcon);
            }

            var truncated = matched.length > MAX_RESULTS;

            return {
                icons: matched.slice(0, MAX_RESULTS),
                truncated: truncated,
                total: matched.length,
                mode: 'search',
            };
        }

        function renderGrid(query) {
            if (!grid) {
                return;
            }

            var result = iconsForQuery(query);
            var html = '';
            var current = hiddenClass ? hiddenClass.value : selectedIcon;

            result.icons.forEach(function (icon) {
                var isSelected = icon === current;
                html += '<button type="button" class="home-option-icon-grid__btn' + (isSelected ? ' is-selected' : '') + '"'
                    + ' data-icon="' + icon + '" data-label="' + icon + '" title="' + icon + '"'
                    + ' role="option" aria-selected="' + (isSelected ? 'true' : 'false') + '">'
                    + '<i class="bi ' + icon + '" aria-hidden="true"></i></button>';
            });

            grid.innerHTML = html;

            if (hint) {
                if (result.mode === 'featured') {
                    hint.textContent = 'نمایش ' + result.icons.length + ' آیکون پرکاربرد. برای دسترسی به همه ' + allIcons.length + ' آیکون، جستجو کنید (مثلاً: تاب، استخر، wifi).';
                } else if (!result.icons.length) {
                    hint.textContent = 'آیکونی یافت نشد. نام انگلیسی آیکون را امتحان کنید یا از کلمات فارسی مثل تاب، استخر، وایفای استفاده کنید.';
                } else if (result.truncated) {
                    hint.textContent = result.total + ' نتیجه — فقط ' + MAX_RESULTS + ' مورد اول نمایش داده شد. جستجو را دقیق‌تر کنید.';
                } else {
                    hint.textContent = result.icons.length + ' آیکون یافت شد.';
                }
            }
        }

        function selectIcon(iconClass) {
            if (!hiddenClass || !previewIcon || !iconClass) {
                return;
            }
            selectedIcon = iconClass;
            hiddenClass.value = iconClass;
            previewIcon.className = 'bi ' + iconClass + ' text-primary';
            previewIcon.style.fontSize = '4rem';
            previewIcon.style.lineHeight = '1';
            if (previewLabel) {
                previewLabel.textContent = iconClass;
            }
            if (grid) {
                grid.querySelectorAll('.home-option-icon-grid__btn').forEach(function (btn) {
                    var isSelected = btn.getAttribute('data-icon') === iconClass;
                    btn.classList.toggle('is-selected', isSelected);
                    btn.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                });
            }
        }

        function previewImageFile(file) {
            if (!file || !imagePreview) {
                return;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('d-none');
                if (imagePlaceholder) {
                    imagePlaceholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        }

        if (typeFont) {
            typeFont.addEventListener('change', syncPanels);
        }
        if (typeImage) {
            typeImage.addEventListener('change', syncPanels);
        }

        if (grid) {
            grid.addEventListener('click', function (e) {
                var btn = e.target.closest('.home-option-icon-grid__btn');
                if (!btn) {
                    return;
                }
                selectIcon(btn.getAttribute('data-icon'));
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                renderGrid(searchInput.value);
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (fileInput.files && fileInput.files[0]) {
                    previewImageFile(fileInput.files[0]);
                }
            });
        }

        syncPanels();
        renderGrid('');
        if (hiddenClass && hiddenClass.value) {
            selectIcon(hiddenClass.value);
        }
    }

    document.querySelectorAll('[data-home-option-icon-field]').forEach(initHomeOptionIconField);
})();
