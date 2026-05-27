@extends('layouts.dashboard.dashboard', ['title' => __('title.edit home'), 'active' => 'my-homes', 'breadcrumbs' => [
    ['url' => route('dashboard.homes.index'), 'title' => __('title.my_homes')],
    ['url' => null, 'title' => __('title.edit home')]
]])

@section('content')
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-3 mb-sm-0">
            @lang('title.edit home')
        </h3>
    </div>

    <form class="mt-5" action="{{ route('dashboard.homes.update', $home->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="reject_policy">@lang('title.reserve_cancel_policy')</label>
            <select name="reject_policy" id="reject_policy" class="form-control">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Home::REJECT_POLICIES as $policy)
                    <option value="{{ $policy['value'] }}" @if($policy['value'] == old('reject_policy', $home->reject_policy)) selected @endif>{{ $policy['title'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="name">@lang('title.name')</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $home->name) }}">
        </div>

        <div class="form-group">
            <label for="description">@lang('title.description')</label>
            <textarea class="form-control" id="description" name="description">{!! old('description', $home->description) !!}</textarea>
        </div>

        <div class="form-group">
            <label for="rules">@lang('title.rules')</label>
            <textarea class="form-control" id="rules" name="rules">{!! old('rules', $home->rules) !!}</textarea>
        </div>

        <x-dashboard.home.variables :values="$home->variables"></x-dashboard.home.variables>

        <div class="form-group row">
            <div class="col-12 col-md-3">
                <label for="yard">@lang('title.yard_meter')</label>
                <input type="number" class="form-control" id="yard" name="yard" value="{{ old('yard', $home->yard_meter) }}">
            </div>
            <div class="col-12 col-md-3">
                <label for="infrastructure">@lang('title.infrastructure_meter')</label>
                <input type="number" class="form-control" id="infrastructure" name="infrastructure" value="{{ old('infrastructure', $home->infrastructure_meter) }}">
            </div>
            <div class="col-12 col-md-3">
                <label for="main_guest">@lang('title.main_guest_count')</label>
                <input type="number" class="form-control" id="main_guest" name="main_guest" value="{{ old('main_guest', $home->main_guest) }}">
            </div>
            <div class="col-12 col-md-3">
                <label for="extra_guest">@lang('title.extra_guest_count')</label>
                <input type="number" class="form-control" id="extra_guest" name="extra_guest" value="{{ old('extra_guest', $home->extra_guest) }}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="atmosphere">فضا</label>
                <select name="atmosphere" id="atmosphere" class="form-control">
                    @foreach(\App\Models\Home::ATMOSPHERES as $atmosphere)
                        <option value="{{ $atmosphere['value'] }}" @if($atmosphere['value'] === $home->atmosphere) selected @endif>{{ $atmosphere['fa_text'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label for="type">نوع</label>
                <select name="type" id="type" class="form-control">
                    @foreach(\App\Models\Home::TYPES as $type)
                        <option value="{{ $type['value'] }}" @if($type['value'] === $home->type) selected @endif>{{ $type['fa_text'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label for="area">منطقه</label>
                <select name="area" id="area" class="form-control">
                    @foreach(\App\Models\Home::AREAS as $area)
                        <option value="{{ $area['value'] }}" @if($area['value'] === $home->area) selected @endif>{{ $area['fa_text'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group my-4">
            <home-bed-room
                count_sleep_rooms="@lang('title.count_sleep_rooms')"
                :old="{{ json_encode(['rooms' => $home->sleepPlaces, 'sleep_area_description' => $home->sleep_area_description]) }}"
            ></home-bed-room>
        </div>

        <div class="mt-5 text-center">
            <a href="{{ route('dashboard.homes.index') }}" class="btn btn-danger">@lang('title.return')</a>
            <button class="btn btn-info">@lang('title.edit')</button>
        </div>
    </form>
@endsection
