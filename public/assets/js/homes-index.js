document.addEventListener('DOMContentLoaded', function() {
    if (window.hasOwnProperty('homesRangeSliderConfig')) {
        const { min, max, from, to } = window.homesRangeSliderConfig;
        $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: min,
            max: max,
            from: from,
            to: to,
            grid: true
        });
    }
}); 