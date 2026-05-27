<form action="{{ route('admin.setting.contact-us') }}" method="POST" enctype="multipart/form-data">
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
                <a href="{{ settingFilePath('contact-us:banner') }}" target="_blank" class="input-group-text">
                    <img width="200" src="{{ settingFilePath('contact-us:banner') }}" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="title" id="title" value="{{ setting('contact-us:title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="first-description">@lang('title.first-description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="first-description" id="first-description" value="{{ setting('contact-us:description1') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="second-description">@lang('title.second-description')</label>
        </div>
        <div class="col-612 col-md-6">
            <input class="form-control" type="text" name="second-description" id="second-description" value="{{ setting('contact-us:description2') }}">
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.boxes')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="first-box-title">@lang('title.first-box-title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="first-box-title" id="first-box-title" value="{{ setting('contact-us:box1-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="first-box-email">@lang('title.first-box-email')</label>
        </div>
        <div class="col-612 col-md-6">
            <input class="form-control" type="email" name="first-box-email" id="first-box-email" value="{{ setting('contact-us:box1-email') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="first-box-phone">@lang('title.first-box-phone')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="first-box-phone" id="first-box-phone" value="{{ setting('contact-us:box1-phone') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="second-box-title">@lang('title.second-box-title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="second-box-title" id="second-box-title" value="{{ setting('contact-us:box2-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="second-box-email">@lang('title.second-box-email')</label>
        </div>
        <div class="col-612 col-md-6">
            <input class="form-control" type="email" name="second-box-email" id="second-box-email" value="{{ setting('contact-us:box2-email') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="second-box-phone">@lang('title.second-box-phone')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="second-box-phone" id="second-box-phone" value="{{ setting('contact-us:box2-phone') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="third-box-title">@lang('title.third-box-title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="third-box-title" id="third-box-title" value="{{ setting('contact-us:box3-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="third-box-email">@lang('title.third-box-email')</label>
        </div>
        <div class="col-612 col-md-6">
            <input class="form-control" type="email" name="third-box-email" id="third-box-email" value="{{ setting('contact-us:box3-email') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="third-box-phone">@lang('title.third-box-phone')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="third-box-phone" id="third-box-phone" value="{{ setting('contact-us:box3-phone') }}">
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.map')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="map-iframe">@lang('title.map-iframe')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" style="direction: ltr" rows="6" name="map-iframe" id="map-iframe">{!! setting('contact-us:map-iframe') !!}</textarea>
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.articles')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="article-title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" name="article-title" id="article-title" value="{{ setting('contact-us:article-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="article-description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control text-center" rows="6" name="article-description" id="article-description">{!! setting('contact-us:article-description') !!}</textarea>
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
