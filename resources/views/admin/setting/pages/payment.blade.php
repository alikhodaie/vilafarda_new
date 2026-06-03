@php
    use App\Classes\Payment\SizpayClient;
    use App\Models\Transaction;
    use App\Services\PaymentGatewayService;
    $sizpayReady = SizpayClient::fromSettings()->isConfigured();
@endphp
<form action="{{ route('admin.setting.payment') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <h5 class="mb-3">@lang('title.payment_gateways_active')</h5>
    <p class="text-muted small">@lang('text.payment_gateways_active_hint')</p>

    <div class="row mt-3">
        <div class="col-12 col-md-6">
            <label for="gateway_zarinpal">{{ Transaction::GATEWAY[Transaction::ZARINPAL]['text'] }}</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-check" type="checkbox" name="gateway_zarinpal" id="gateway_zarinpal" value="1"
                   @if(settingBoolean(PaymentGatewayService::settingKey(Transaction::ZARINPAL))) checked @endif>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-6">
            <label for="gateway_idpay">{{ Transaction::GATEWAY[Transaction::IDPAY]['text'] }}</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-check" type="checkbox" name="gateway_idpay" id="gateway_idpay" value="1"
                   @if(settingBoolean(PaymentGatewayService::settingKey(Transaction::IDPAY))) checked @endif>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-6">
            <label for="gateway_sizpay">{{ Transaction::GATEWAY[Transaction::SIZPAY]['text'] }}</label>
            @if(! $sizpayReady)
                <div class="small text-warning">@lang('text.sizpay_admin_not_ready')</div>
            @endif
        </div>
        <div class="col-12 col-md-6">
            <input class="form-check" type="checkbox" name="gateway_sizpay" id="gateway_sizpay" value="1"
                   @if(settingBoolean(PaymentGatewayService::settingKey(Transaction::SIZPAY))) checked @endif
                   @if(! $sizpayReady) disabled title="@lang('text.sizpay_admin_not_ready')" @endif>
        </div>
    </div>

    <div class="row mt-3 mb-4">
        <div class="col-12 col-md-6">
            <label for="gateway_wallet">{{ Transaction::GATEWAY[Transaction::WALLET]['text'] }}</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-check" type="checkbox" name="gateway_wallet" id="gateway_wallet" value="1"
                   @if(settingBoolean(PaymentGatewayService::settingKey(Transaction::WALLET))) checked @endif>
        </div>
    </div>

    <hr>

    <h5 class="mb-3 mt-4">@lang('title.zarinpal_settings')</h5>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="merchant_id">Merchant ID</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="merchant_id" id="merchant_id" value="{{ setting('zarinpal:merchant-id') }}">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="gate">ZarinGate</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-check" @if(settingBoolean('zarinpal:gate')) checked @endif type="checkbox" name="gate" id="gate" value="1">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="sandbox">Sandbox (زرین‌پال)</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-check" @if(settingBoolean('zarinpal:sandbox')) checked @endif type="checkbox" name="sandbox" id="sandbox" value="1">
        </div>
    </div>

    <hr>

    <h5 class="mb-3 mt-4">@lang('title.idpay_settings')</h5>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="idpay_api_key">API Key (آیدی‌پی)</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="idpay_api_key" id="idpay_api_key" value="{{ setting('idpay:api-key') }}">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="idpay_sandbox">Sandbox (آیدی‌پی)</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-check" @if(settingBoolean('idpay:sandbox')) checked @endif type="checkbox" name="idpay_sandbox" id="idpay_sandbox" value="1">
        </div>
    </div>

    <hr>

    <h5 class="mb-3 mt-4">@lang('title.sizpay_settings')</h5>
    <p class="text-muted small">@lang('text.sizpay_settings_hint')</p>

    @if(! extension_loaded('soap'))
        <div class="alert alert-warning py-2 small">@lang('text.sizpay_soap_required')</div>
    @endif

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="sizpay_merchant_id">Merchant ID (کد پذیرنده)</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="sizpay_merchant_id" id="sizpay_merchant_id" value="{{ setting('sizpay:merchant-id') }}">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="sizpay_terminal_id">Terminal ID</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="sizpay_terminal_id" id="sizpay_terminal_id" value="{{ setting('sizpay:terminal-id') }}">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="sizpay_username">UserName</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="sizpay_username" id="sizpay_username" value="{{ setting('sizpay:username') }}" autocomplete="off">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="sizpay_password">Password</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="password" name="sizpay_password" id="sizpay_password" value="{{ setting('sizpay:password') }}" autocomplete="new-password">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="sizpay_sign_data">SignData</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="sizpay_sign_data" id="sizpay_sign_data" value="{{ setting('sizpay:sign-data') }}" autocomplete="off">
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
