(function () {
    if (window.__footerSettingsInit) {
        return;
    }
    window.__footerSettingsInit = true;

    function getTemplateHtml(wrapper, name) {
        var template = wrapper.querySelector('[data-footer-template="' + name + '"]');
        if (!template) {
            return '';
        }
        return template.innerHTML;
    }

    window.footerRepeatableAdd = function (btn) {
        if (!btn) {
            return;
        }
        var name = btn.getAttribute('data-template');
        var wrapper = btn.closest('.footer-repeatable');
        if (!wrapper || !name) {
            return;
        }
        var list = wrapper.querySelector('.footer-repeatable-list');
        var templateHtml = getTemplateHtml(wrapper, name);
        if (!list || !templateHtml) {
            return;
        }
        var index = list.querySelectorAll('.footer-repeatable-row').length;
        list.insertAdjacentHTML('beforeend', templateHtml.replace(/__INDEX__/g, String(index)));
    };

    window.footerRepeatableRemove = function (btn) {
        if (!btn) {
            return;
        }
        var row = btn.closest('.footer-repeatable-row');
        var list = row ? row.closest('.footer-repeatable-list') : null;
        if (!row || !list) {
            return;
        }
        row.remove();
        if (!list.querySelector('.footer-repeatable-row')) {
            var wrapper = list.closest('.footer-repeatable');
            var fallbackAdd = wrapper ? wrapper.querySelector('.footer-repeatable-add') : null;
            if (fallbackAdd) {
                window.footerRepeatableAdd(fallbackAdd);
            }
        }
    };

    window.footerSocialIconTypeChange = function (select) {
        if (!select) {
            return;
        }
        var row = select.closest('.footer-repeatable-row');
        if (!row) {
            return;
        }
        var isFont = select.value === 'font';
        var fontPanel = row.querySelector('.footer-social-font-panel');
        var imagePanel = row.querySelector('.footer-social-image-panel');
        if (fontPanel) {
            fontPanel.classList.toggle('d-none', !isFont);
        }
        if (imagePanel) {
            imagePanel.classList.toggle('d-none', isFont);
        }
    };

    document.addEventListener('click', function (e) {
        var addBtn = e.target.closest('.footer-repeatable-add');
        if (addBtn) {
            e.preventDefault();
            window.footerRepeatableAdd(addBtn);
            return;
        }

        var removeBtn = e.target.closest('.footer-repeatable-remove');
        if (removeBtn) {
            e.preventDefault();
            window.footerRepeatableRemove(removeBtn);
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target.closest('.footer-social-icon-type')) {
            window.footerSocialIconTypeChange(e.target);
        }
    });
})();
