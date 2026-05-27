<form action="{{ route('admin.setting.payment') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
            <input class="form-check" @if(setting('zarinpal:gate', false)) checked @endif type="checkbox" name="gate" id="gate" value="1">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="sandbox">Sandbox</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-check" @if(setting('zarinpal:sandbox', false)) checked @endif type="checkbox" name="sandbox" id="sandbox" value="1">
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
