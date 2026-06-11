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
            <label for="enamad_html">@lang('title.footer_enamad_html')</label>
            <small class="text-muted d-block">@lang('title.footer_enamad_html_hint')</small>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" name="enamad_html" id="enamad_html" rows="6" dir="ltr"
                      placeholder="<a referrerpolicy='origin' target='_blank' href='...'><img ...></a>">{!! $footer['enamad_html'] !!}</textarea>
            @if(!empty($footer['enamad_html']))
                <div class="mt-2 border rounded p-2 bg-light">
                    <small class="text-muted d-block mb-1">پیش‌نمایش</small>
                    {!! $footer['enamad_html'] !!}
                </div>
            @endif
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>

@push('bottom-assets')
    <script>
        (function () {
            if (window.__footerSettingsInit) {
                return;
            }
            window.__footerSettingsInit = true;

            function addRepeatableRow(btn) {
                var name = btn.getAttribute('data-template');
                var wrapper = btn.closest('.footer-repeatable');
                if (!wrapper) {
                    return;
                }
                var list = wrapper.querySelector('.footer-repeatable-list');
                var template = wrapper.querySelector('#footer-repeatable-template-' + name);
                if (!list || !template) {
                    return;
                }
                var index = list.querySelectorAll('.footer-repeatable-row').length;
                var html = template.innerHTML.replace(/__INDEX__/g, String(index));
                list.insertAdjacentHTML('beforeend', html);
            }

            document.addEventListener('click', function (e) {
                var addBtn = e.target.closest('.footer-repeatable-add');
                if (addBtn) {
                    e.preventDefault();
                    addRepeatableRow(addBtn);
                    return;
                }

                var removeBtn = e.target.closest('.footer-repeatable-remove');
                if (!removeBtn) {
                    return;
                }
                e.preventDefault();
                var row = removeBtn.closest('.footer-repeatable-row');
                var list = row ? row.closest('.footer-repeatable-list') : null;
                if (!row || !list) {
                    return;
                }
                row.remove();
                if (!list.querySelector('.footer-repeatable-row')) {
                    var wrapper = list.closest('.footer-repeatable');
                    var fallbackAdd = wrapper ? wrapper.querySelector('.footer-repeatable-add') : null;
                    if (fallbackAdd) {
                        addRepeatableRow(fallbackAdd);
                    }
                }
            });

            document.addEventListener('change', function (e) {
                var select = e.target.closest('.footer-social-icon-type');
                if (!select) {
                    return;
                }
                var row = select.closest('.footer-repeatable-row');
                if (!row) {
                    return;
                }
                var isFont = select.value === 'font';
                var fontPanel = row.querySelector('.footer-social-font-panel');
                var imagePanel = row.querySelector('.footer-social-image-panel');
                if (fontPanel) {
                    fontPanel.classList.toggle('d-none', !isFont);
                }
                if (imagePanel) {
                    imagePanel.classList.toggle('d-none', isFont);
                }
            });
        })();
    </script>
@endpush
