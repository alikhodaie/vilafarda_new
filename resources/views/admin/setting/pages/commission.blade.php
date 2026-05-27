<form action="{{ route('admin.setting.commission') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="easy_commission">سهل گیرانه</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="number" min="0" max="100" name="easy_commission" id="easy_commission" value="{{ setting('commission:easy', 0) }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="balanced_commission">متعادل</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="number" min="0" max="100" name="balanced_commission" id="balanced_commission" value="{{ setting('commission:balanced', 0) }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="strict_commission">سخت گیرانه</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="number" min="0" max="100" name="strict_commission" id="strict_commission" value="{{ setting('commission:strict', 0) }}">
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
