(function () {
    var provinceSelect = document.getElementById('admin-home-province');
    var citySelect = document.getElementById('admin-home-city');
    if (!provinceSelect || !citySelect) return;

    var cityOpts = Array.prototype.slice.call(citySelect.querySelectorAll('option'));

    function syncCities() {
        var pid = provinceSelect.value;
        var firstVisible = null;
        cityOpts.forEach(function (opt) {
            if (!opt.value) {
                opt.hidden = false;
                return;
            }
            var provinceMatches = String(opt.getAttribute('data-province')) === String(pid);
            opt.hidden = !provinceMatches;
            if (provinceMatches && firstVisible === null) firstVisible = opt;
        });
        var currentCityOpt = citySelect.options[citySelect.selectedIndex];
        if (!currentCityOpt || currentCityOpt.hidden || !currentCityOpt.value) {
            citySelect.value = firstVisible ? firstVisible.value : '';
        }
    }

    provinceSelect.addEventListener('change', syncCities);
    syncCities();
})();
