@if(app(\App\Services\OrderShowPresenter::class)->canHostRespondToPending($order))
    <div class="row justify-content-center text-center pt-3">
        <div class="col-6">
            <change-status
                route="{{ route('dashboard.orders.accept', $order) }}"
                csrf="{{ csrf_token() }}"
                title="@lang('title.accept_reserve')"
                button_text='@lang("title.accept")'
                button_class="btn btn-success w-100"
                text="@lang('text.accept_reserve', ['minute' => \App\Models\Order::MAX_PAY_TIME_IN_MINUTE])"
                button_cancel_text="@lang('title.cancel')"
                button_submit_text="@lang('title.accept')"
            ></change-status>
        </div>
        <div class="col-6">
            <change-status
                route="{{ route('dashboard.orders.reject', $order) }}"
                csrf="{{ csrf_token() }}"
                title="@lang('title.reject_reserve')"
                button_text='@lang("title.reject")'
                button_class="btn btn-danger w-100"
                text="@lang('text.reject_reserve')"
                button_cancel_text="@lang('title.cancel')"
                button_submit_text="@lang('title.reject')"
                :reject-reasons="{{ json_encode(\App\Models\Order::REJECT_REASONS) }}"
            ></change-status>
        </div>
    </div>
@endif
