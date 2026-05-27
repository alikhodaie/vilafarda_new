<form action="{{ route('admin.setting.reject-policy') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="easy_description">سهل گیرانه</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="easy_description" id="easy_description" value="{{ setting('reject-policy:'.\App\Models\Home::EASY) }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="balanced_description">متعادل</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="balanced_description" id="balanced_description" value="{{ setting('reject-policy:'.\App\Models\Home::BALANCED) }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="strict_description">سخت گیرانه</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="strict_description" id="strict_description" value="{{ setting('reject-policy:'.\App\Models\Home::STRICT) }}">
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
