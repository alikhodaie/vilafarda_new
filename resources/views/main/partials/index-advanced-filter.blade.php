<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="simple_tab_search center">
            <div class="full_search_box nexio_search lightanic_search hero_search-radius modern">
                <form action="{{ route('main.homes.index') }}" class="search_hero_wrapping">

                    <div class="row m-0">

{{--                        <div class="col-4 p-0">--}}
{{--                            <province-input--}}
{{--                                class="test"--}}
{{--                                :only_has_home="true"--}}
{{--                                route="{{ route('main.provinces') }}"--}}
{{--                                placeholder="@lang('title.province')"--}}
{{--                                select_label="@lang('title.select')"--}}
{{--                                selected_label="@lang('title.selected')"--}}
{{--                                deselect_label="@lang('title.remove')"--}}
{{--                                no_result_text="@lang('text.empty_result')"--}}
{{--                                no_options_text="@lang('text.empty_list')"--}}
{{--                                old="{{ request('province') }}"--}}
{{--                            ></province-input>--}}
{{--                        </div>--}}

{{--                        <div class="col-4 p-0">--}}
{{--                            <city-input--}}
{{--                                placeholder="@lang('title.city')"--}}
{{--                                select_label="@lang('title.select')"--}}
{{--                                selected_label="@lang('title.selected')"--}}
{{--                                deselect_label="@lang('title.remove')"--}}
{{--                                no_result_text="@lang('text.empty_result')"--}}
{{--                                no_options_text="@lang('text.empty_list')"--}}
{{--                                old="{{ request('city') }}"--}}
{{--                            ></city-input>--}}
{{--                        </div>--}}


                        <div class="col-8 p-0">
                            <input type="text" name="name" value="{{ request('name') }}" class="form-control" style="height: 100%;" placeholder="@lang('title.search')">
                        </div>


                        <div class="col-2 p-0">
                            <a class="collapsed ad-search custom-filter-button" data-toggle="collapse" data-parent="#search" data-target="#advance-search-2" href="javascript:void(0);" aria-expanded="false" aria-controls="advance-search">
                                <i class="fa fa-calendar-alt ml-0 ml-md-2"></i>
                                <span class="d-none d-md-inline-block">@lang('title.advance_filter')</span>
                            </a>
                        </div>

                        <div class="col-2 p-0">
                            <button type="submit" class="btn search-btn no-height">@lang('title.search_home')</button>
                        </div>
                    </div>

                    <!-- Collapse Advance Search Form -->
                    <div class="p-4 collapse" id="advance-search-2" aria-expanded="false" role="banner">
                        @include('main.homes.partials.index-filter')
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
