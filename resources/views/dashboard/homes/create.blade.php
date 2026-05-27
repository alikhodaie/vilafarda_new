@php use App\Models\Home; @endphp
@extends('layouts.main.main', ['title' => setting('submit-home:page-title'), 'has_footer'  => false])

@section('content')
    <div class="page-title" style="background:#f4f4f4 url({{ settingFilePath('submit-home:banner') }});"
         data-overlay="5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <div class="breadcrumbs-wrap">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"
                                aria-current="page">{{ setting('submit-home:page-title') }}</li>
                        </ol>
                        <h2 class="breadcrumb-title">{{ setting('submit-home:title') }}</h2>
                        <p class="mb-0 mt-2" style="font-size: 14px; opacity: 0.9; line-height: 1.7;">
                            ثبت اقامتگاه جدید — شما می‌توانید در صورت خروج از این صفحه، ادامه ثبت اقامتگاه خود را از صفحه
                            <a href="{{ route('dashboard.homes.index') }}" class="text-white fw-semibold">اقامتگاه‌های من</a>
                            ادامه دهید. هر مرحله به‌صورت خودکار ذخیره می‌شود.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <section class="mb-5">
        <div class="container">
            <form action="{{ route('dashboard.homes.store', $home) }}" method="POST" enctype="multipart/form-data">

                @csrf
                <create-home
                    :home="{{ json_encode($home) }}"
                    csrf="{{ csrf_token() }}"
                    next_text="@lang('title.next_page')"
                    prev_text="@lang('title.prev_page')"
                    submit_text="@lang('title.submit')"
                    message_active_page="@lang('title.submit')"
                    :variables="{{ \App\Models\Variable::getFromCache()->map(function ($variable){ $variable->option = ''; return $variable; })->toJson() }}"
                    :options="{{ \App\Models\Option::getFromCache()->toJson() }}"
                    :healths="{{ \App\Models\Health::getFromCache()->toJson() }}"
                    :safeties="{{ \App\Models\Safety::getFromCache()->toJson() }}"
                    :help_collection="{{ Home::getCreateHomeHelps()->toJson() }}"
                    :daily_off="{{ json_encode(Home::DAILY_OFF_AMOUNTS) }}"
                    change_cover_route="{{ route('dashboard.homes.custom.images.update', $home) }}"
                    change_images_route="{{ route('dashboard.homes.image.store', $home) }}"
                    validate_route_step1="{{ route('dashboard.homes.validate.store.step1', $home) }}"
                    validate_route_step2="{{ route('dashboard.homes.validate.store.step2', $home) }}"
                    validate_route_step3="{{ route('dashboard.homes.validate.store.step3', $home) }}"
                    validate_route_step4="{{ route('dashboard.homes.validate.store.step4', $home) }}"
                    validate_route_step5="{{ route('dashboard.homes.validate.store.step5', $home) }}"
                    validate_route_step6="{{ route('dashboard.homes.validate.store.step6', $home) }}"
                    validate_route_step7="{{ route('dashboard.homes.validate.store.step7', $home) }}"
                    validate_route_step8="{{ route('dashboard.homes.validate.store.step8', $home) }}"
                    validate_route_step9="{{ route('dashboard.homes.validate.store.step9', $home) }}"
                    validate_route_step10="{{ route('dashboard.homes.validate.store.step10', $home) }}"
                    validate_route_step11="{{ route('dashboard.homes.validate.store.step11', $home) }}"
                    validate_route_step12="{{ route('dashboard.homes.validate.store.step12', $home) }}"
                    validate_route_step13="{{ route('dashboard.homes.validate.store.step13', $home) }}"
                    validate_route_step14="{{ route('dashboard.homes.validate.store.step14', $home) }}"
                    count_sleep_rooms="@lang('title.count_sleep_rooms')"
                    atmosphere_title="@lang('title.atmosphere')"
                    type_title="@lang('title.type')"
                    area_title="@lang('title.area')"
                    :atmospheres="{{ json_encode(\App\Models\Home::ATMOSPHERES) }}"
                    :types="{{ json_encode(\App\Models\Home::TYPES) }}"
                    :areas="{{ json_encode(\App\Models\Home::AREAS) }}"
                    max_size="{{ Home::MAX_IMAGE_SIZE }}"
                    province_route="{{ route('dashboard.provinces') }}"
                    text_about="@lang('title.about')"
                    province_title="@lang('title.province')"
                    text_address="@lang('title.address')"
                    text_map="@lang('title.map')"
                    text_images="@lang('title.images')"
                    text_residence="@lang('title.residence_type')"
                    text_capacity="@lang('title.capacity')"
                    text_sleep_residence="@lang('title.sleep_residence')"
                    text_health_items="@lang('title.health_items')"
                    text_off="@lang('title.off')"
                    text_safety="@lang('title.safety')"
                    text_rule="@lang('title.rule')"
                    text_cancel_rule_reserve="@lang('title.cancel_rule_reserve')"
                    text_extra_detail="@lang('title.extra_detail')"
                    text_map="@lang('title.map')"
                    text_main_information="@lang('title.main_information')"
                    text_price="@lang('title.price')"
                    text_details="@lang('title.details')"
                    text_site_privacy="@lang('title.site_policy')"
                    text_options="@lang('title.options')"
                    :reject_policies="{{ json_encode(Home::getRejectPolicies()) }}"
                    reject_policy_easy_commission="{{ setting('commission:easy', 0) }}"
                    reject_policy_balanced_commission="{{ setting('commission:balanced', 0) }}"
                    reject_policy_strict_commission="{{ setting('commission:strict', 0) }}"
                    reject_policy_title="@lang('title.reserve_cancel_policy')"
                    city_title="@lang('title.city')"
                    address_title="@lang('title.address')"
                    city_place_holder="@lang('text.insert_city')"
                    province_place_holder="@lang('text.insert_province')"
                    option_place_holder="@lang('text.select_options')"
                    options_title="@lang('title.options')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    name_title="@lang('title.name')"
                    description_title="@lang('title.description')"
                    rules_title="@lang('title.rules')"
                    yard_title="@lang('title.yard_meter')"
                    infrastructure_title="@lang('title.infrastructure_meter')"
                    main_guest_title="@lang('title.main_guest_count')"
                    extra_guest_title="@lang('title.extra_guest_count')"
                    week_price_title="@lang('title.week_price')"
                    thu_price_title="@lang('title.thu_price')"
                    wed_price_title="@lang('title.wed_price')"
                    fri_price_title="@lang('title.fri_price')"
                    price_per_surplus_title="@lang('title.price_per_surplus')"
                    site_policy="{!! setting('new-home:policy') !!}"
                ></create-home>
            </form>
        </div>
    </section>
@endsection
