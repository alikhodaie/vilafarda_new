@props(['home'])

@php
    use App\Models\Home;

    $modalId = 'hostStatusModal'.$home->id;
    $isActive = $home->isHostActive();
    $btnClass = $attributes->get('class', 'btn btn-warning w-100 rounded');
@endphp

@if($isActive)
    <button type="button"
            class="{{ $btnClass }}"
            data-bs-toggle="modal"
            data-bs-target="#{{ $modalId }}">
        <i class="bi bi-pause-circle me-1"></i>
        غیرفعال کردن
    </button>

    <div class="modal fade host-deactivation-modal" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <form method="POST" action="{{ route('dashboard.homes.host-status', $home) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="deactivate">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" style="font-size: 16px;">غیرفعال کردن اقامتگاه</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3" style="font-size: 14px;">
                            لطفاً دلیل غیرفعال‌سازی را انتخاب کنید. تا زمان فعال‌سازی مجدد، اقامتگاه در سایت نمایش داده نمی‌شود.
                        </p>
                        <div class="host-deactivation-reasons">
                            @foreach(Home::HOST_DEACTIVATION_REASONS as $reason)
                                @php $inputId = $modalId.'-reason-'.$reason['value']; @endphp
                                <div class="host-deactivation-reason-item">
                                    <input type="radio"
                                           name="reason"
                                           id="{{ $inputId }}"
                                           value="{{ $reason['value'] }}"
                                           {{ $loop->first ? 'required' : '' }}>
                                    <label for="{{ $inputId }}">{{ $reason['label'] }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-warning text-dark">تأیید غیرفعال‌سازی</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
    <form method="POST" action="{{ route('dashboard.homes.host-status', $home) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="action" value="activate">
        <button type="submit" class="{{ str_replace('btn-warning', 'btn-success', $btnClass) }}">
            <i class="bi bi-play-circle me-1"></i>
            فعال‌سازی
        </button>
    </form>
@endif

@once
<style>
    .host-deactivation-modal .host-deactivation-reason-item {
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 10px;
        background: #fafafa;
    }
    .host-deactivation-modal .host-deactivation-reason-item:last-child {
        margin-bottom: 0;
    }
    .host-deactivation-modal .host-deactivation-reason-item label {
        margin-bottom: 0;
        width: 100%;
    }
    .host-deactivation-modal .host-deactivation-reason-item:hover {
        border-color: #D39D1A;
        background: #fff9eb;
    }
</style>
@endonce
