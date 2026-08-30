(function () {
    var provinceSelect = document.getElementById('admin-home-province');
    var citySelect = document.getElementById('admin-home-city');
    if (!provinceSelect || !citySelect) return;

    var cityOpts = Array.prototype.slice.call(citySelect.querySelectorAll('option'));

    function syncCities() {
        var pid = provinceSelect.value;
        var selected = citySelect.value;

        cityOpts.forEach(function (opt) {
            if (!opt.value) {
                opt.hidden = false;
                return;
            }
            var match = String(opt.getAttribute('data-province')) === String(pid);
            var keepSelected = selected && String(opt.value) === String(selected);
            opt.hidden = !match && !keepSelected;
        });

        if (selected) {
            citySelect.value = selected;
        }
    }

    provinceSelect.addEventListener('change', syncCities);
    syncCities();
})();
