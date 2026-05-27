(function (global) {
    function toPersianDigits(value) {
        if (typeof global.toPersianDigits === 'function') {
            return global.toPersianDigits(value);
        }

        var map = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return String(value).replace(/\d/g, function (digit) {
            return map[parseInt(digit, 10)];
        });
    }

    global.hasGuestRating = function (home) {
        if (!home) {
            return false;
        }

        return !!(home.has_guest_reviews || (home.guest_score > 0 && home.count_comments > 0));
    };

    global.formatGuestRatingHtml = function (home, className) {
        className = className || 'last-minute-off-card__rating';

        if (!global.hasGuestRating(home)) {
            return '';
        }

        var score = home.guest_score_display || home.guest_score;
        var count = home.count_comments || 0;

        return (
            '<span class="' + className + '">' +
                '<i class="bi bi-star-fill"></i>' +
                toPersianDigits(score) +
                ' (' + toPersianDigits(count) + ' نظر مهمان)' +
            '</span>'
        );
    };
})(window);
