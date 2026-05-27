@php use App\Models\Home; @endphp
@extends('layouts.dashboard.dashboard', ['title' => __('title.my_homes'), 'active' => 'my-homes', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.my_homes')]
]])

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="_prt_filt_dash">
                <div class="_prt_filt_dash_flex">
                    <div class="foot-news-last">
                        <form action="{{ route('dashboard.homes.index') }}" class="input-group">
                            <input name="name" value="{{ request('name') }}" type="text" class="form-control"
                                   placeholder="@lang('title.search')">
                            <div class="input-group-append">
                                <span type="button" class="input-group-text theme-bg b-0 text-light">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="_prt_filt_dash_last m2_hide">
                    <div class="_prt_filt_radius">

                    </div>
                    <div class="_prt_filt_add_new">
                        <a href="{{ route('dashboard.homes.create') }}" class="prt_submit_link">
                            <i class="fas fa-plus-circle"></i>
                            <span class="d-none d-lg-block d-md-block">@lang('title.submit_new_home')</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if($homes->isNotEmpty())
                <div class="row">
                    @foreach($homes as $home)
                        <div class="col-12 card">
                            @if($home->is_draft)
                                <div class="row p-3 align-items-center">
                                    <div class="col-12 col-md-2">
                                        <div class="w-100" style="position: relative;">
                                            <img src="{{ $home->cover_path }}" class="img-fluid" alt=""/>
                                            <div class="_leads_status text-dark rounded p-1 w-100 text-center bg-warning"
                                                 style="position: absolute; bottom: 0; font-size: 11px;">اقامتگاه کامل ثبت نشده</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7 p-2">
                                        <h5>{{ $home->name ?: 'پیش‌نویس ثبت اقامتگاه' }}</h5>
                                        <p class="text-muted mb-0 small">
                                            @if($home->province && $home->city)
                                                {{ $home->province->name }} — {{ $home->city->name }}
                                            @else
                                                ثبت تدریجی در حال انجام است؛ می‌توانید بعداً ادامه دهید.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-12 col-md-3 p-2 d-flex flex-column gap-2">
                                        <a class="btn btn-warning w-100 rounded text-center text-dark"
                                           href="{{ route('dashboard.homes.create', ['continue' => 1, 'home' => $home->id]) }}">
                                            ادامه ثبت اقامتگاه
                                        </a>
                                        <delete-modal
                                            route="{{ route('dashboard.homes.destroy', $home->id) }}"
                                            csrf="{{ csrf_token() }}"
                                            button_title="@lang('title.delete')"
                                            title="@lang('title.delete home')"
                                            text="@lang('text.delete home')"
                                            button_hover_text="@lang('title.delete')"
                                            button_cancel_text="@lang('title.cancel')"
                                            button_submit_text="@lang('title.delete')"
                                            btn_class="btn btn-danger w-100 rounded"
                                        ></delete-modal>
                                    </div>
                                </div>
                            @else
                            <div class="row">
                                <div class="col-12 col-md-2">
                                    <div class="w-100" style="position: relative;">
                                        <img src="{{ $home->cover_path }}" class="img-fluid" alt="{{ $home->name }}"/>
                                        <div
                                            class="_leads_status text-light rounded p-1 w-100 text-center bg-{{ $home->status('color') }}"
                                            style="position: absolute; bottom: 0">{{ $home->status() }}</div>
                                    </div>
                                    <div class="text-center my-3">کد اقامتگاه: {{ $home->id }}</div>
                                </div>
                                <div class="col-12 col-md-10 p-2">
                                    <h5>{{ $home->name }}</h5>
                                    <div class="prt_dashb_lot"><i
                                            class="fa fa-map-marker-alt"></i> {{ $home->province->name }} {{ $home->city->name }}
                                    </div>
                                    <div class="row my-3">
                                        <div class="col-6 col-md-4 mb-2">
                                            <a class="btn btn-info w-100 rounded text-center"
                                               title="@lang('title.edit_home_date')"
                                               href="{{ route('dashboard.homes.custom.date.show', $home) }}">تاریخ</a>
                                        </div>
                                        <div class="col-6 col-md-4 mb-2">
                                            <a class="btn btn-info w-100 rounded text-center"
                                               title="@lang('title.edit_home_info')"
                                               href="{{ route('dashboard.homes.edit', $home) }}">اطلاعات</a>
                                        </div>
                                        <div class="col-6 col-md-4 mb-2">
                                            <a class="btn btn-info w-100 rounded text-center"
                                               title="@lang('title.edit_home_price')"
                                               href="{{ route('dashboard.homes.custom.price.show', $home) }}">قیمت</a>
                                        </div>
                                        <div class="col-6 col-md-4 mb-2">
                                            <a class="btn btn-info w-100 rounded text-center"
                                               title="@lang('title.edit_home_address')"
                                               href="{{ route('dashboard.homes.custom.address.show', $home) }}">آدرس</a>
                                        </div>
                                        <div class="col-6 col-md-4 mb-2">
                                            <a class="btn btn-info w-100 rounded text-center"
                                               title="@lang('title.edit_home_image')"
                                               href="{{ route('dashboard.homes.custom.images.show', $home) }}">تصاویر</a>
                                        </div>
                                        <div class="col-6 col-md-4 mb-2">
                                            <a class="btn btn-info w-100 rounded text-center"
                                               title="@lang('title.edit_home_options')"
                                               href="{{ route('dashboard.homes.custom.option.show', $home) }}">ویژگی
                                                ها</a>
                                        </div>

                                        <div class="col-12 mb-2">
                                            <x-dashboard.home.host-activation-button :home="$home" />
                                            @if(!$home->isHostActive())
                                                <small class="text-muted d-block mt-2 text-center" style="font-size: 12px;">
                                                    غیرفعال: {{ $home->hostDeactivationReasonLabel() }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($home->status === Home::ACCEPTED && $home->isHostActive())
                                <a class="w-100 text-center mb-3 pt-3" style="border-top: 1px solid #cdcdcd;"
                                   href="{{ $home->link }}" target="_blank">نمایش</a>
                            @endif
                            @endif
                        </div>

                    @endforeach
                </div>
            @endif

            <a class="w-100 text-center pt-3 btn rounded"
               style="background-color: green" href="{{ route('main.submit.home') }}">
                <i class="fa fa-home"></i>
                ثبت اقامتگاه
                <i class="fa fa-home"></i>
            </a>
        </div>
    </div>
@endsection
