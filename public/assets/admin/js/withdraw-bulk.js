(function () {
    function initWithdrawBulk() {
        var form = document.getElementById('withdraw-bulk-form');
        if (!form || form.dataset.bulkReady === '1') {
            return;
        }
        form.dataset.bulkReady = '1';

        var selectAll = document.getElementById('withdraw-select-all');
        var submitBtn = document.getElementById('withdraw-bulk-paid-btn');
        var hint = document.getElementById('withdraw-bulk-selected-hint');

        function rowChecks() {
            return form.querySelectorAll('input.withdraw-bulk-check');
        }

        function setSubmitEnabled(enabled) {
            if (!submitBtn) {
                return;
            }
            if (enabled) {
                submitBtn.removeAttribute('disabled');
                submitBtn.classList.remove('disabled');
            } else {
                submitBtn.setAttribute('disabled', 'disabled');
                submitBtn.classList.add('disabled');
            }
        }

        function refresh() {
            var selected = 0;
            var total = 0;

            rowChecks().forEach(function (cb) {
                total++;
                if (cb.checked) {
                    selected++;
                }
                var row = cb.closest('tr');
                if (row) {
                    row.classList.toggle('table-warning', cb.checked);
                }
            });

            setSubmitEnabled(selected > 0);

            if (hint) {
                hint.textContent = selected ? (selected + ' مورد انتخاب شده') : '';
            }

            if (selectAll) {
                selectAll.checked = total > 0 && selected === total;
                selectAll.indeterminate = selected > 0 && selected < total;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                var checked = selectAll.checked;
                rowChecks().forEach(function (cb) {
                    cb.checked = checked;
                });
                refresh();
            });
        }

        form.addEventListener('change', function (event) {
            if (event.target && event.target.classList.contains('withdraw-bulk-check')) {
                refresh();
            }
        });

        refresh();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWithdrawBulk);
    } else {
        initWithdrawBulk();
    }
})();
