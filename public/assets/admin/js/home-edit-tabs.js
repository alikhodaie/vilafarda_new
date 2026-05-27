(function () {
    function initAdminHomeEditTabs() {
        var wrap = document.querySelector('.admin-home-edit-wrap');
        if (!wrap) {
            return;
        }

        var tabs = wrap.querySelectorAll('.admin-home-edit-tab-pill[data-target]');
        var panes = wrap.querySelectorAll('.admin-home-edit-tab-pane');
        var openTabField = document.getElementById('adminOpenTabField');

        if (!tabs.length || !panes.length) {
            return;
        }

        function syncTabRequired() {
            panes.forEach(function (pane) {
                var isActive = pane.classList.contains('active');
                pane.querySelectorAll('[data-tab-required="1"]').forEach(function (el) {
                    if (isActive) {
                        el.setAttribute('required', '');
                    } else {
                        el.removeAttribute('required');
                    }
                });
            });
        }

        function initTabRequiredSync() {
            panes.forEach(function (pane) {
                pane.querySelectorAll('[required]').forEach(function (el) {
                    el.dataset.tabRequired = '1';
                });
            });
            syncTabRequired();
        }

        function activateTab(target, scrollTab) {
            var pane = document.getElementById(target);
            if (!pane) {
                return;
            }

            tabs.forEach(function (t) {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            panes.forEach(function (p) {
                p.classList.remove('active');
            });

            var tabBtn = wrap.querySelector('.admin-home-edit-tab-pill[data-target="' + target + '"]');
            if (tabBtn) {
                tabBtn.classList.add('active');
                tabBtn.setAttribute('aria-selected', 'true');
                if (scrollTab) {
                    tabBtn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
            }

            pane.classList.add('active');
            if (openTabField) {
                openTabField.value = target;
            }
            syncTabRequired();

            if (target === 'tab-rooms' && typeof window.initAdminBedrooms === 'function') {
                window.initAdminBedrooms();
            }
            if (target === 'tab-location' && typeof window.adminRefreshLocationPreview === 'function') {
                setTimeout(window.adminRefreshLocationPreview, 100);
            }
            if (target === 'tab-pricing' && typeof window.adminRefreshAllPriceWords === 'function') {
                setTimeout(window.adminRefreshAllPriceWords, 50);
            }
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activateTab(tab.dataset.target, true);
            });
        });

        initTabRequiredSync();

        var initialTab = (openTabField && openTabField.value) ? openTabField.value : 'tab-admin';
        activateTab(initialTab, false);

        var form = document.getElementById('admin-home-edit-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                syncTabRequired();

                var slugInput = document.getElementById('home_seo_slug');
                if (slugInput && slugInput.value) {
                    slugInput.value = slugInput.value
                        .trim()
                        .replace(/[\s?؟\/\\()&%#!،]+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-+|-+$/g, '');
                }

                if (typeof window.adminNormalizeAllPriceFieldsForSubmit === 'function') {
                    window.adminNormalizeAllPriceFieldsForSubmit();
                }

                var activePane = wrap.querySelector('.admin-home-edit-tab-pane.active');
                if (activePane) {
                    var invalid = activePane.querySelector(':invalid');
                    if (invalid) {
                        e.preventDefault();
                        invalid.reportValidity();
                        invalid.focus({ preventScroll: false });
                        return;
                    }
                }

                panes.forEach(function (pane) {
                    if (!pane.classList.contains('active')) {
                        pane.querySelectorAll('input, select, textarea').forEach(function (el) {
                            el.setCustomValidity('');
                        });
                    }
                });
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminHomeEditTabs);
    } else {
        initAdminHomeEditTabs();
    }
})();
