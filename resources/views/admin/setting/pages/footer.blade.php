@php($footer = footerSettings())
<form action="{{ route('admin.setting.footer') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <h4 class="text-center">@lang('title.first_menu')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="first_menu_title">@lang('title.header')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="first_menu_title" id="first_menu_title" value="{{ $footer['first_menu_title'] }}">
        </div>
    </div>
    <div class="mt-4">
        <h5>@lang('title.menu')</h5>
        <footer-link
            title_text="@lang('title.title')"
            url_text="@lang('title.url')"
            name="first_menu"
            :old="{{ json_encode($footer['first_menu']) }}"
        ></footer-link>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.second_menu')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="second_menu_title">@lang('title.header')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="second_menu_title" id="second_menu_title" value="{{ $footer['second_menu_title'] }}">
        </div>
    </div>
    <div class="mt-4">
        <h5>@lang('title.menu')</h5>
        <footer-link
            title_text="@lang('title.title')"
            url_text="@lang('title.url')"
            name="second_menu"
            :old="{{ json_encode($footer['second_menu']) }}"
        ></footer-link>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.third_menu')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="third_menu_title">@lang('title.header')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="third_menu_title" id="third_menu_title" value="{{ $footer['third_menu_title'] }}">
        </div>
    </div>
    <div class="mt-4">
        <h5>@lang('title.menu')</h5>
        <footer-link
            title_text="@lang('title.title')"
            url_text="@lang('title.url')"
            name="third_menu"
            :old="{{ json_encode($footer['third_menu']) }}"
        ></footer-link>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.footer_mobile_section')</h4>
    <p class="text-muted text-center small">@lang('title.footer_mobile_section_hint')</p>
    <p class="text-muted text-center small">@lang('title.footer_navbar_links_hint')</p>

    <h5 class="mt-4">@lang('title.footer_phones')</h5>
    @include('admin.setting.partials.footer-repeatable', [
        'name' => 'phones',
        'rows' => $footer['phones'] ?: [['label' => '', 'number' => '']],
        'emptyRow' => ['label' => '', 'number' => ''],
        'rowPartial' => 'admin.setting.partials.footer-row-phone',
    ])

    <h5 class="mt-4">@lang('title.footer_socials')</h5>
    @include('admin.setting.partials.footer-repeatable', [
        'name' => 'socials',
        'rows' => $footer['socials'] ?: [['title' => 'اینستاگرام', 'link' => '', 'icon_type' => 'font', 'icon_class' => 'bi-instagram', 'follower_count' => '']],
        'emptyRow' => ['title' => '', 'link' => '', 'icon_type' => 'font', 'icon_class' => 'bi-instagram', 'follower_count' => ''],
        'rowPartial' => 'admin.setting.partials.footer-row-social',
    ])

    <hr>
    <h4 class="text-center">@lang('title.footer_enamad')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="trust_section_title">@lang('title.footer_trust_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="trust_section_title" id="trust_section_title"
                   value="{{ $footer['trust_section_title'] }}"
                   placeholder="با خیال راحت به :app_name اعتماد کنید">
            <small class="text-muted">@lang('title.footer_trust_title_hint')</small>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="enamad_url">@lang('title.footer_enamad_url')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="url" name="enamad_url" id="enamad_url" dir="ltr"
                   value="{{ $footer['enamad_url'] }}"
                   placeholder="https://trustseal.enamad.ir/?id=...">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="enamad_image_url">@lang('title.footer_enamad_image')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="url" name="enamad_image_url" id="enamad_image_url" dir="ltr"
                   value="{{ $footer['enamad_image_url'] }}"
                   placeholder="https://trustseal.enamad.ir/logo.aspx?id=...">
            @if($footer['enamad_image_url'])
                <div class="mt-2">
                    <img src="{{ $footer['enamad_image_url'] }}" alt="@lang('title.footer_enamad')" width="120" height="120" style="object-fit: contain;">
                </div>
            @endif
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
