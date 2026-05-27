(function (global) {
    'use strict';

    function escapeAttr(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;');
    }

    function altForHome(home) {
        var name = (home && (home.name || home.title)) ? String(home.name || home.title).trim() : '';
        if (!name) {
            name = 'اقامتگاه';
        }
        return 'تصویر ' + name;
    }

    global.SeoImage = {
        escapeAttr: escapeAttr,
        altForHome: altForHome,
    };
})(typeof window !== 'undefined' ? window : this);
