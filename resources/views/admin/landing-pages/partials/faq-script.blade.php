<script>
(function () {
    var container = document.getElementById('landing-faq-rows');
    var addBtn = document.getElementById('add-landing-faq');
    if (!container || !addBtn) return;

    addBtn.addEventListener('click', function () {
        var index = container.querySelectorAll('.landing-faq-row').length;
        var div = document.createElement('div');
        div.className = 'border rounded p-3 mb-2 landing-faq-row';
        div.innerHTML =
            '<div class="mb-2"><input type="text" class="form-control" name="faq[' + index + '][question]" placeholder="{{ __('title.question') }}"></div>' +
            '<textarea class="form-control" name="faq[' + index + '][answer]" rows="2" placeholder="{{ __('title.answer') }}"></textarea>';
        container.appendChild(div);
    });
})();
</script>
