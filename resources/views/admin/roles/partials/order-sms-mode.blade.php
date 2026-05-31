<div id="order-sms-mode-wrapper" class="mt-3 ps-2 @if(! $showOrderSmsMode) d-none @endif">
    <label class="form-label d-block">@lang('title.order_sms_mode')</label>
    <div class="form-check form-check-inline">
        <input
            class="form-check-input"
            type="radio"
            name="order_sms_mode"
            id="order_sms_mode_always"
            value="always"
            @if(old('order_sms_mode', $orderSmsMode ?? 'always') === 'always') checked @endif
        >
        <label class="form-check-label" for="order_sms_mode_always">@lang('title.order_sms_mode_always')</label>
    </div>
    <div class="form-check form-check-inline">
        <input
            class="form-check-input"
            type="radio"
            name="order_sms_mode"
            id="order_sms_mode_rotating"
            value="rotating"
            @if(old('order_sms_mode', $orderSmsMode ?? 'always') === 'rotating') checked @endif
        >
        <label class="form-check-label" for="order_sms_mode_rotating">@lang('title.order_sms_mode_rotating')</label>
    </div>
    <small class="text-muted d-block mt-1">@lang('text.order_sms_mode_hint')</small>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const permissionCheckbox = document.getElementById('permission-{{ $ordersSmsPermissionId }}');
        const wrapper = document.getElementById('order-sms-mode-wrapper');

        if (!permissionCheckbox || !wrapper) {
            return;
        }

        const toggleOrderSmsMode = () => {
            wrapper.classList.toggle('d-none', !permissionCheckbox.checked);
        };

        permissionCheckbox.addEventListener('change', toggleOrderSmsMode);
        toggleOrderSmsMode();
    });
</script>
