@props(['home'])

@php
    use App\Models\Home;

    $rejectPolicyDetails = [];
    foreach (Home::REJECT_POLICIES as $policy) {
        $value = $policy['value'];
        $rejectPolicyDetails[$value] = [
            'title' => $policy['title'],
            'description' => Home::getRejectPolicyDescription($value) ?? '',
            'commission' => (int) setting('commission:'.$value, 0),
        ];
    }
    $selectedRejectPolicy = old('reject_policy', $home->reject_policy);
@endphp

<div class="card-mobile mb-3">
    <h5 class="text-mobile-primary mb-3">
        <i class="bi bi-journal-text me-2"></i>
        قوانین
    </h5>

    <div class="mb-3">
        <label for="reject_policy" class="form-label-mobile">سیاست لغو رزرو</label>
        <select name="reject_policy" id="reject_policy" class="form-select form-control-mobile">
            <option value="">انتخاب کنید</option>
            @foreach(Home::REJECT_POLICIES as $policy)
                <option value="{{ $policy['value'] }}"
                    @if($selectedRejectPolicy === $policy['value']) selected @endif>
                    {{ $policy['title'] }}
                </option>
            @endforeach
        </select>

        <div id="rejectPolicyDetail" class="reject-policy-detail mt-2 p-3 rounded"
             style="display: {{ $selectedRejectPolicy ? 'block' : 'none' }}; background: #fff9eb; border: 1px solid #f0e6c8;">
            <p class="mb-2 fw-semibold text-mobile-primary" id="rejectPolicyCommission" style="font-size: 13px;"></p>
            <p class="mb-0 text-mobile-muted" id="rejectPolicyDescription" style="font-size: 12px; line-height: 1.75;"></p>
        </div>

        @error('reject_policy')
            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-0">
        <label for="rules" class="form-label-mobile">قوانین اقامتگاه</label>
        <textarea name="rules" id="rules" class="form-control form-control-mobile" rows="4"
                  placeholder="مثلاً ساعت ورود، سکوت شبانه، ممنوعیت سیگار و ...">{{ old('rules', $home->rules) }}</textarea>
        @error('rules')
            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
        @enderror
    </div>
</div>

<script type="application/json" id="rejectPolicyData">@json($rejectPolicyDetails)</script>
<script>
(function () {
    if (window.__rejectPolicyDetailInit) {
        return;
    }
    window.__rejectPolicyDetailInit = true;

    function bindRejectPolicyDetail(root) {
        const select = root.querySelector ? root.querySelector('#reject_policy') : document.getElementById('reject_policy');
        const detail = root.querySelector ? root.querySelector('#rejectPolicyDetail') : document.getElementById('rejectPolicyDetail');
        const dataEl = document.getElementById('rejectPolicyData');
        const commissionEl = root.querySelector ? root.querySelector('#rejectPolicyCommission') : document.getElementById('rejectPolicyCommission');
        const descriptionEl = root.querySelector ? root.querySelector('#rejectPolicyDescription') : document.getElementById('rejectPolicyDescription');

        if (!select || !detail || !dataEl || !commissionEl || !descriptionEl) {
            return;
        }

        let policies = {};
        try {
            policies = JSON.parse(dataEl.textContent || '{}');
        } catch (e) {
            policies = {};
        }

        function updateRejectPolicyDetail() {
            const value = select.value;
            const policy = policies[value];

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

        select.removeEventListener('change', select.__rejectPolicyChangeHandler);
        select.__rejectPolicyChangeHandler = updateRejectPolicyDetail;
        select.addEventListener('change', select.__rejectPolicyChangeHandler);
        updateRejectPolicyDetail();
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindRejectPolicyDetail(document);
    });
})();
</script>
