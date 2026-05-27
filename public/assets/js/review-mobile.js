(function () {
    const form = document.getElementById('reviewForm');
    if (!form) {
        return;
    }

    const submitBtn = document.getElementById('reviewSubmitBtn');
    const hintEl = document.getElementById('reviewFooterHint');
    const criteriaCount = form.querySelectorAll('.review-criterion').length;

    const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    function toPersianNumber(value) {
        return String(value).replace(/\d/g, function (digit) {
            return persianDigits[parseInt(digit, 10)];
        });
    }

    function starValue(star) {
        return parseInt(star.getAttribute('data-value'), 10);
    }

    function getCriterionValue(criterionEl) {
        const input = criterionEl.querySelector('input[type="hidden"]');
        return input ? parseInt(input.value, 10) : 0;
    }

    function allRated() {
        const criteria = form.querySelectorAll('.review-criterion');
        for (let i = 0; i < criteria.length; i++) {
            const value = getCriterionValue(criteria[i]);
            if (!value || value < 1) {
                return false;
            }
        }
        return criteria.length === criteriaCount;
    }

    function updateSubmitState() {
        const ready = allRated();
        if (submitBtn) {
            submitBtn.disabled = !ready;
        }
        if (hintEl) {
            hintEl.textContent = ready
                ? 'همه موارد امتیازدهی شدند.'
                : 'لطفاً به همه آیتم‌ها امتیاز دهید.';
        }
    }

    function updateCaption(criterionEl, value) {
        const caption = criterionEl.querySelector('[data-caption]');
        if (!caption) {
            return;
        }
        caption.textContent = value > 0 ? toPersianNumber(value) + ' ستاره' : '';
    }

    function paintStars(stars, value, activeClass) {
        stars.forEach(function (star) {
            const current = starValue(star);
            star.classList.toggle(activeClass, current > 0 && current <= value);
        });
    }

    form.querySelectorAll('.review-criterion').forEach(function (criterionEl) {
        const stars = criterionEl.querySelectorAll('.review-stars__btn');
        const hiddenInput = criterionEl.querySelector('input[type="hidden"]');
        const oldValue = hiddenInput ? parseInt(hiddenInput.value, 10) : 0;

        function setRating(value) {
            if (!hiddenInput) {
                return;
            }
            hiddenInput.value = value;
            paintStars(stars, value, 'is-active');
            updateCaption(criterionEl, value);
            updateSubmitState();
        }

        stars.forEach(function (star) {
            const value = starValue(star);

            star.addEventListener('click', function () {
                setRating(value);
            });

            star.addEventListener('mouseenter', function () {
                paintStars(stars, value, 'is-hover');
            });

            star.addEventListener('mouseleave', function () {
                paintStars(stars, 0, 'is-hover');
                const current = hiddenInput ? parseInt(hiddenInput.value, 10) : 0;
                paintStars(stars, current, 'is-active');
            });
        });

        if (oldValue > 0) {
            setRating(oldValue);
        }
    });

    updateSubmitState();
})();
