/**
 * دکمه علاقه‌مندی — همان منطق صفحه نمایش اقامتگاه (show_mobile)
 */
(function (global) {
    'use strict';

    var pending = {};

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function appBase() {
        var body = document.body;
        var base = body && body.getAttribute('data-app-base');
        if (base) {
            return base.replace(/\/$/, '');
        }
        if (global.__homeFavoriteConfig && global.__homeFavoriteConfig.appUrl) {
            return String(global.__homeFavoriteConfig.appUrl).replace(/\/$/, '');
        }
        return '';
    }

    function favoritePath(homeId) {
        return appBase() + '/homes/' + encodeURIComponent(homeId) + '/favorite';
    }

    function ensureToast() {
        if (typeof global.showToast === 'function') {
            return;
        }
        global.showToast = function (message, type) {
            var el = document.createElement('div');
            el.textContent = message;
            el.setAttribute('role', 'alert');
            el.style.cssText = [
                'position:fixed',
                'top:16px',
                'left:50%',
                'transform:translateX(-50%)',
                'z-index:99999',
                'max-width:90%',
                'padding:12px 18px',
                'border-radius:12px',
                'color:#fff',
                'font-size:14px',
                'font-weight:600',
                'box-shadow:0 4px 16px rgba(0,0,0,0.2)',
                'background:' + (type === 'error' ? '#dc3545' : '#198754'),
            ].join(';');
            document.body.appendChild(el);
            window.setTimeout(function () {
                el.remove();
            }, 3500);
        };
    }

    function toast(message, type) {
        ensureToast();
        if (global.Vue && typeof global.Vue.swal === 'function') {
            global.Vue.swal({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                icon: type || 'success',
                showCloseButton: true,
                timer: 3500,
                timerProgressBar: true,
                text: message,
            });
            return;
        }
        global.showToast(message, type || 'success');
    }

    function responseOk(data) {
        return data === true || data === 1 || data === '1';
    }

    function setButtonState(btn, isFavorite) {
        if (!btn) {
            return;
        }
        btn.classList.toggle('is-active', isFavorite);
        btn.setAttribute('aria-pressed', isFavorite ? 'true' : 'false');
        btn.setAttribute('aria-label', isFavorite ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها');
        var icon = btn.querySelector('.bi');
        if (icon) {
            icon.classList.remove('bi-heart', 'bi-heart-fill');
            icon.classList.add(isFavorite ? 'bi-heart-fill' : 'bi-heart');
        }
    }

    function syncAllHearts(homeId, isFavorite) {
        document.querySelectorAll('.home-favorite-btn[data-home-id="' + homeId + '"]').forEach(function (btn) {
            setButtonState(btn, isFavorite);
        });
    }

    function removeFavoriteCardFromList(btn) {
        var card = btn.closest('[data-favorite-card]');
        if (!card) {
            return;
        }
        var list = document.getElementById('favoritesCardsList');
        if (!list || !list.contains(card)) {
            return;
        }
        card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.98)';
        window.setTimeout(function () {
            card.remove();
            if (list.children.length === 0) {
                list.remove();
                var empty = document.getElementById('favoritesEmptyStateDynamic');
                if (empty) {
                    empty.classList.remove('d-none');
                }
            }
        }, 250);
    }

    function requestFavorite(homeId, method) {
        var token = csrfToken();
        return fetch(favoritePath(homeId), {
            method: method,
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
        }).then(function (response) {
            if (response.status === 401 || response.status === 403) {
                throw new Error('login');
            }
            if (response.status === 419) {
                throw new Error('csrf');
            }
            return response.json().catch(function () {
                throw new Error('request');
            }).then(function (data) {
                if (!response.ok) {
                    throw new Error((data && data.message) ? data.message : 'request');
                }
                return data;
            });
        });
    }

    function toggleFavorite(btn) {
        if (!btn || btn.classList.contains('is-loading')) {
            return;
        }

        var homeId = btn.getAttribute('data-home-id');
        if (!homeId) {
            return;
        }

        if (pending[homeId]) {
            return;
        }

        var isFavorite = btn.classList.contains('is-active');
        var method = isFavorite ? 'DELETE' : 'POST';
        var buttons = document.querySelectorAll('.home-favorite-btn[data-home-id="' + homeId + '"]');

        pending[homeId] = true;
        buttons.forEach(function (b) {
            b.classList.add('is-loading');
        });

        requestFavorite(homeId, method)
            .then(function (data) {
                if (!responseOk(data)) {
                    throw new Error('response');
                }
                var next = !isFavorite;
                syncAllHearts(homeId, next);
                toast(
                    next ? 'به علاقه‌مندی‌ها اضافه شد' : 'از علاقه‌مندی‌ها حذف شد',
                    'success'
                );
                if (!next) {
                    removeFavoriteCardFromList(btn);
                }
                if (global.eventBus) {
                    global.eventBus.$emit('home_' + homeId, next);
                }
            })
            .catch(function (err) {
                var loginUrl = (global.__homeFavoriteConfig && global.__homeFavoriteConfig.loginUrl)
                    || '/login';
                var loginMessage = (global.__homeFavoriteConfig && global.__homeFavoriteConfig.loginMessage)
                    || 'لطفاً ابتدا وارد حساب خود شوید';

                if (err && err.message === 'login') {
                    toast(loginMessage, 'error');
                    window.setTimeout(function () {
                        window.location.href = loginUrl;
                    }, 700);
                    return;
                }
                if (err && err.message === 'csrf') {
                    toast('نشست منقضی شده. صفحه را رفرش کنید.', 'error');
                    return;
                }
                toast('خطا در ثبت علاقه‌مندی', 'error');
            })
            .finally(function () {
                delete pending[homeId];
                buttons.forEach(function (b) {
                    b.classList.remove('is-loading');
                });
            });
    }

    function onClick(btn, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            if (typeof event.stopImmediatePropagation === 'function') {
                event.stopImmediatePropagation();
            }
        }
        toggleFavorite(btn);
    }

    function buildButtonHtml(home) {
        var id = home && home.id != null ? home.id : '';
        var isFavorite = !!(home && home.is_favorite);
        var activeClass = isFavorite ? ' is-active' : '';
        var iconClass = isFavorite ? 'bi-heart-fill' : 'bi-heart';

        return (
            '<button type="button" class="home-favorite-btn home-favorite-btn--overlay' + activeClass + '"' +
            ' data-home-id="' + id + '"' +
            ' onclick="HomeFavorite.onClick(this,event)"' +
            ' aria-label="' + (isFavorite ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها') + '"' +
            ' aria-pressed="' + (isFavorite ? 'true' : 'false') + '">' +
            '<i class="bi ' + iconClass + '" aria-hidden="true"></i>' +
            '</button>'
        );
    }

    function bindClicks() {
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.home-favorite-btn');
            if (!btn) {
                return;
            }
            onClick(btn, e);
        }, true);
    }

    global.HomeFavorite = {
        onClick: onClick,
        toggle: toggleFavorite,
        buildButtonHtml: buildButtonHtml,
        setButtonState: setButtonState,
        syncAllHearts: syncAllHearts,
    };

    ensureToast();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindClicks);
    } else {
        bindClicks();
    }
})();
