<form action="{{ route('admin.setting.faq') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <h4 class="text-center">@lang('title.main_title')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="banner">@lang('title.banner')</label>
        </div>
        <div class="col-12 col-md-6">
            <div class="input-group">
                <input class="form-control" type="file" name="banner" id="banner">
                <a href="{{ settingFilePath('faq:banner') }}" target="_blank" class="input-group-text">
                    <img width="200" src="{{ settingFilePath('faq:banner') }}" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="title" id="title" value="{{ setting('faq:title') }}">
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
