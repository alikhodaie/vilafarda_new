(function () {
    function bindRejectPolicyDetail() {
        var select = document.getElementById('reject_policy');
        var detail = document.getElementById('adminRejectPolicyDetail');
        var dataEl = document.getElementById('adminRejectPolicyData');
        var commissionEl = document.getElementById('adminRejectPolicyCommission');
        var descriptionEl = document.getElementById('adminRejectPolicyDescription');

        if (!select || !detail || !dataEl || !commissionEl || !descriptionEl) {
            return;
        }

        var policies = {};
        try {
            policies = JSON.parse(dataEl.textContent || '{}');
        } catch (e) {
            policies = {};
        }

        function updateRejectPolicyDetail() {
            var value = select.value;
            var policy = policies[value];

            if (!policy) {
                detail.style.display = 'none';
                commissionEl.textContent = '';
                descriptionEl.textContent = '';
                return;
            }

            commissionEl.textContent = 'با انتخاب سیاست «' + policy.title + '»، کمیسیون سایت '
                + policy.commission + '٪ است.';
            descriptionEl.textContent = policy.description || '';
            detail.style.display = 'block';
        }

        select.addEventListener('change', updateRejectPolicyDetail);
        updateRejectPolicyDetail();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindRejectPolicyDetail);
    } else {
        bindRejectPolicyDetail();
    }
})();
