<form action="{{ route('admin.setting.about-us') }}" method="POST" enctype="multipart/form-data">
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
                <a href="{{ settingFilePath('about-us:banner') }}" target="_blank" class="input-group-text">
                    <img width="200" src="{{ settingFilePath('about-us:banner') }}" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="page_title">@lang('title.page_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="page_title" id="page_title" value="{{ setting('about-us:page-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="title" id="title" value="{{ setting('about-us:title') }}">
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.story')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="story_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="story_title" id="story_title" value="{{ setting('about-us:story-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="story_little_title">@lang('title.little_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="story_little_title" id="story_little_title" value="{{ setting('about-us:story-title1') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="story_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="story_description" id="story_description">{!! setting('about-us:story-description') !!}</textarea>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="story_btn_title">@lang('title.title') @lang('title.button')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="story_btn_title" id="story_btn_title" value="{{ setting('about-us:story-btn-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="story_btn_link">@lang('title.link') @lang('title.button')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="story_btn_link" id="story_btn_link" value="{{ setting('about-us:story-btn-link') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="story_banner">@lang('title.banner')</label>
        </div>
        <div class="col-12 col-md-6">
            <div class="input-group">
                <input class="form-control" type="file" name="story_banner" id="story_banner">
                <a href="{{ settingFilePath('about-us:story-image') }}" target="_blank" class="input-group-text">
                    <img width="200" src="{{ settingFilePath('about-us:story-image') }}" alt="">
                </a>
            </div>
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.rewards')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="reward_title" id="reward_title" value="{{ setting('about-us:reward-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="reward_description" id="reward_description" value="{{ setting('about-us:reward-description') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_box1_title">@lang('title.first_box_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="reward_box1_title" id="reward_box1_title" value="{{ setting('about-us:reward-box1-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_box1_count">@lang('title.first_box_count')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="number" name="reward_box1_count" id="reward_box1_count" value="{{ setting('about-us:reward-box1-count') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_box2_title">@lang('title.second_box_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="reward_box2_title" id="reward_box2_title" value="{{ setting('about-us:reward-box2-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_box2_count">@lang('title.second_box_count')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="number" name="reward_box2_count" id="reward_box2_count" value="{{ setting('about-us:reward-box2-count') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_box3_title">@lang('title.third_box_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="reward_box3_title" id="reward_box3_title" value="{{ setting('about-us:reward-box3-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_box3_count">@lang('title.third_box_count')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="number" name="reward_box3_count" id="reward_box3_count" value="{{ setting('about-us:reward-box3-count') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_box4_title">@lang('title.forth_box_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="reward_box4_title" id="reward_box4_title" value="{{ setting('about-us:reward-box4-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="reward_box4_count">@lang('title.forth_box_count')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="number" name="reward_box4_count" id="reward_box4_count" value="{{ setting('about-us:reward-box4-count') }}">
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.comments')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="comments_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="comments_title" id="comments_title" value="{{ setting('about-us:comments-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="comments_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="comments_description" id="comments_description" value="{{ setting('about-us:comments-description') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <about-us-comments
                text_name="@lang('title.name')"
                text_job="@lang('title.job')"
                text_score="@lang('title.score')"
                text_description="@lang('title.description')"
                :values="{{ setting('about-us:comments') }}"
            ></about-us-comments>
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.articles')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="articles_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="articles_title" id="articles_title" value="{{ setting('about-us:articles-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="articles_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="articles_description" id="articles_description" value="{{ setting('about-us:articles-description') }}">
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
