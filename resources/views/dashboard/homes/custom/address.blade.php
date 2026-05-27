@extends('layouts.dashboard.dashboard', ['title' => __('title.edit_home_address') .' - '. $home->name , 'active' => 'my-homes', 'breadcrumbs' => [
    ['url' => route('dashboard.homes.index'), 'title' => __('title.my_homes')],
    ['url' => null, 'title' => __('title.edit_home_address')],
    ['url' => null, 'title' =>  $home->name]
]])

@section('content')
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-3 mb-sm-0">
            @lang('title.edit_home_address') - {{ $home->name }}
        </h3>
    </div>

    <form class="my-5" method="POST" action="{{ route('dashboard.homes.custom.address.update', $home) }}">
        @csrf
        @method('PUT')

        <div class="form-group row">
            <div class="col-12 col-md-6">
                <label for="province">@lang('title.province')</label>
                <province-input
                    route="{{ route('dashboard.provinces') }}"
                    placeholder="@lang('text.insert_province')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                    old="{{ old('province', $home->province_id) }}"
                ></province-input>
            </div>
            <div class="col-12 col-md-6">
                <label for="city">@lang('title.city')</label>
                <city-input
                    placeholder="@lang('text.insert_city')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                    old="{{ old('city', $home->city_id) }}"
                ></city-input>
            </div>
        </div>

        <div class="form-group">
            <label for="address">@lang('title.address')</label>
            <textarea cols="30" rows="10" class="form-control" id="address" name="address">{!! old('address', $home->address) !!}</textarea>
        </div>

        <div class="form-group">
            <label for="map">@lang('title.map')</label>
            <leaftlet-map @if($home->latitude && $home->longitude) :latitude="{{ $home->latitude }}" :longitude="{{ $home->longitude }}" @endif :watch_province="true"></leaftlet-map>
        </div>

        <div class="mt-5 text-center">
            <a href="{{ route('dashboard.homes.index') }}" class="btn btn-danger">@lang('title.return')</a>
            <button class="btn btn-info">@lang('title.edit')</button>
        </div>
    </form>
@endsection
