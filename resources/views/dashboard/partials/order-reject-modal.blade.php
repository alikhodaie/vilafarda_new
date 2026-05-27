@php
    use App\Models\Order;

    $rejectReasons = Order::REJECT_REASONS;
    $reasonInputName = isset($order) ? 'reject_reason' : 'reject_reason_modal';
    $formId = isset($order) ? 'rejectOrderForm' : null;
@endphp

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-body py-4 px-4">
                <h5 class="fw-bold mb-2 text-center">رد درخواست</h5>
                <p class="text-muted mb-3 text-center" style="font-size: 14px;">علت رد درخواست را انتخاب کنید:</p>

                <div class="order-reject-reasons">
                    @foreach($rejectReasons as $value => $label)
                        <label class="order-reject-reasons__item">
                            <input type="radio"
                                   name="{{ $reasonInputName }}"
                                   value="{{ $value }}"
                                   class="order-reject-reasons__input"
                                   @if($formId) form="{{ $formId }}" @endif
                                   required>
                            <span class="order-reject-reasons__label">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">انصراف</button>

                    @if(isset($order))
                        <form action="{{ route('dashboard.orders.reject', $order) }}"
                              method="POST"
                              class="flex-fill m-0"
                              id="rejectOrderForm">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100">رد کن</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-danger flex-fill" id="confirmReject">رد کن</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
