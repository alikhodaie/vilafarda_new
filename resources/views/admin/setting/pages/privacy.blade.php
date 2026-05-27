<form action="{{ route('admin.setting.privacy') }}" method="POST" enctype="multipart/form-data">
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
                <a href="{{ settingFilePath('privacy:banner') }}" target="_blank" class="input-group-text">
                    <img width="200" src="{{ settingFilePath('privacy:banner') }}" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="title" id="title" value="{{ setting('privacy:title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="first-description">@lang('title.first-description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="first-description" id="first-description" value="{{ setting('privacy:description1') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="second-description">@lang('title.second-description')</label>
        </div>
        <div class="col-612 col-md-6">
            <input class="form-control" type="text" name="second-description" id="second-description" value="{{ setting('privacy:description2') }}">
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.content')</h4>
    <div class="row mt-4">
        <div class="col-12">
            <tinymce-editor
                upload_file_route="{{ route('admin.tinymce_upload') }}"
                upload_directory="{{ \App\Models\Setting::FILE_PATH.'privacy/' }}"
                name="content"
                lang="{{ config('app.tiny_locale') }}"
                value="{{ old('content', setting('privacy:content')) }}"
            ></tinymce-editor>
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.articles')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="article-title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" name="article-title" id="article-title" value="{{ setting('privacy:article-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="article-description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control text-center" rows="6" name="article-description" id="article-description">{!! setting('privacy:article-description') !!}</textarea>
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
