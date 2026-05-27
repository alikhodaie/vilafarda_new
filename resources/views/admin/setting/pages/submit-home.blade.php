<form action="{{ route('admin.setting.submit-home') }}" method="POST" enctype="multipart/form-data">
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
                <a href="{{ settingFilePath('submit-home:banner') }}" target="_blank" class="input-group-text">
                    <img width="200" src="{{ settingFilePath('submit-home:banner') }}" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="page_title">@lang('title.page_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="page_title" id="page_title" value="{{ setting('submit-home:page-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="title" id="title" value="{{ setting('submit-home:title') }}">
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.before_submit_home')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="first_box_title">@lang('title.first-box-title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="first_box_title" id="first_box_title" value="{{ setting('submit-home:first-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="first_box_description">@lang('title.first-box-description')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="first_box_description" id="first_box_description">{!! setting('submit-home:first-description') !!}</textarea>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="second_box_title">@lang('title.second-box-title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="second_box_title" id="second_box_title" value="{{ setting('submit-home:second-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="second_box_description">@lang('title.second-box-description')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="second_box_description" id="second_box_description">{!! setting('submit-home:second-description') !!}</textarea>
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.submit_home_page')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="policy">@lang('title.policy')</label>
        </div>
        <div class="col-12 col-md-6">
            <tinymce-editor
                upload_file_route="{{ route('admin.tinymce_upload') }}"
                upload_directory="{{ \App\Models\Setting::FILE_PATH.'submit-home/' }}"
                name="policy"
                lang="{{ config('app.tiny_locale') }}"
                value="{{ old('policy', setting('new-home:policy')) }}"
            ></tinymce-editor>
        </div>
    </div>
    <!-- Help 1 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help1">@lang('title.help_page') 1</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help1" id="help1">{!! setting('new-home:help-page-1') !!}</textarea>
        </div>
    </div>
    <!-- Help 2 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help2">@lang('title.help_page') 2</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help2" id="help2">{!! setting('new-home:help-page-2') !!}</textarea>
        </div>
    </div>
    <!-- Help 3 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help3">@lang('title.help_page') 3</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help3" id="help3">{!! setting('new-home:help-page-3') !!}</textarea>
        </div>
    </div>
    <!-- Help 4 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help4">@lang('title.help_page') 4</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help4" id="help4">{!! setting('new-home:help-page-4') !!}</textarea>
        </div>
    </div>
    <!-- Help 5 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help5">@lang('title.help_page') 5</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help5" id="help5">{!! setting('new-home:help-page-5') !!}</textarea>
        </div>
    </div>
    <!-- Help 6 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help6">@lang('title.help_page') 6</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help6" id="help1">{!! setting('new-home:help-page-6') !!}</textarea>
        </div>
    </div>
    <!-- Help 7 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help7">@lang('title.help_page') 7</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help7" id="help7">{!! setting('new-home:help-page-7') !!}</textarea>
        </div>
    </div>
    <!-- Help 8 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help8">@lang('title.help_page') 8</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help8" id="help8">{!! setting('new-home:help-page-8') !!}</textarea>
        </div>
    </div>
    <!-- Help 9 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help9">@lang('title.help_page') 9</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help9" id="help9">{!! setting('new-home:help-page-9') !!}</textarea>
        </div>
    </div>
    <!-- Help 10 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help10">@lang('title.help_page') 10</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help10" id="help10">{!! setting('new-home:help-page-10') !!}</textarea>
        </div>
    </div>
    <!-- Help 11 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help11">@lang('title.help_page') 11</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help11" id="help11">{!! setting('new-home:help-page-11') !!}</textarea>
        </div>
    </div>
    <!-- Help 12 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help12">@lang('title.help_page') 12</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help12" id="help12">{!! setting('new-home:help-page-12') !!}</textarea>
        </div>
    </div>
    <!-- Help 13 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help13">@lang('title.help_page') 13</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help13" id="help13">{!! setting('new-home:help-page-13') !!}</textarea>
        </div>
    </div>
    <!-- Help 14 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help14">@lang('title.help_page') 14</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help14" id="help14">{!! setting('new-home:help-page-14') !!}</textarea>
        </div>
    </div>
    <!-- Help 15 -->
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="help15">@lang('title.help_page') 15</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="help15" id="help15">{!! setting('new-home:help-page-15') !!}</textarea>
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
