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

@include('admin.partials.home-edit.help', ['text' => '<strong>قوانین و لغو:</strong> سیاست لغو رزرو روی کمیسیون و شرایط بازگشت وجه اثر دارد. قوانین داخلی اقامتگاه را برای مهمان بنویسید.'])

<div class="mb-3">
    <label for="reject_policy">@lang('title.reserve_cancel_policy')</label>
    <select name="reject_policy" id="reject_policy" class="form-control" required>
        <option value="">@lang('title.select')</option>
        @foreach(Home::REJECT_POLICIES as $policy)
            <option value="{{ $policy['value'] }}"
                    {{ $policy['value'] == $selectedRejectPolicy ? 'selected' : '' }}>{{ $policy['title'] }}</option>
        @endforeach
    </select>

    <div id="adminRejectPolicyDetail" class="alert alert-warning border-0 mt-2 mb-0 small"
         style="display: {{ $selectedRejectPolicy ? 'block' : 'none' }}; background: #fff9eb;">
        <p class="mb-1 fw-semibold" id="adminRejectPolicyCommission"></p>
        <p class="mb-0 text-secondary" id="adminRejectPolicyDescription"></p>
    </div>
    <script type="application/json" id="adminRejectPolicyData">@json($rejectPolicyDetails)</script>
</div>

<div class="mb-0">
    <label for="rules">@lang('title.rules')</label>
    <textarea class="form-control" id="rules" name="rules" rows="4"
              placeholder="ساعت ورود، سکوت شبانه، ممنوعیت سیگار و ...">{!! old('rules', $home->rules) !!}</textarea>
    <p class="text-muted small mb-0 mt-1">قوانین اختصاصی این اقامتگاه (اختیاری).</p>
</div>
