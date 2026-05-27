@extends('layouts.dashboard.dashboard', ['title' => __('title.edit_home_options') .' - '. $home->name , 'active' => 'my-homes', 'breadcrumbs' => [
    ['url' => route('dashboard.homes.index'), 'title' => __('title.my_homes')],
    ['url' => null, 'title' => __('title.edit_home_options')],
    ['url' => null, 'title' =>  $home->name]
]])

@section('content')
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-3 mb-sm-0">
            @lang('title.edit_home_options') - {{ $home->name }}
        </h3>
    </div>

    <form class="my-5" method="POST" action="{{ route('dashboard.homes.custom.option.update', $home) }}">
        @csrf
        @method('PUT')

        <div class="form-group row">
            <div class="col-12">
                <h4>@lang('title.options')</h4>
            </div>

            @foreach(\App\Models\Option::getFromCache() as $option)
                <div class="col-12 col-md-6 mb-3">
                    <span class="px-5">
                        <input type="checkbox" @if($home->options->contains($option->id)) checked @endif id="option-{{ $option->id }}" class="form-check-input" name="options[]" value="{{ $option->id }}">
                    </span>
                    <label for="option-{{ $option->id }}" class="form-label">
                        <img src="{{ $option->icon_path }}" alt="option.title" width="60">
                        {{ $option->title }}
                    </label>
                </div>
            @endforeach
        </div>

        <div class="form-group row">
            <div class="col-12">
                <h4>@lang('title.health_items')</h4>
            </div>

            @foreach(\App\Models\Health::getFromCache() as $health)
                <div class="col-12 col-md-6 mb-2">
                    <input type="checkbox" @if($home->healths->contains($health->id)) checked @endif id="health-{{ $health->id }}" class="form-check-input" name="healths[]" value="{{ $health->id }}">
                    <label for="health-{{ $health->id }}" class="form-label" style="margin-right: 30px;">{{ $health->title }}</label>
                </div>
            @endforeach
        </div>
        <div class="row mb-4">
            <div class="col-12 col-md-6 mb-2">
                <label for="more_health" class="form-label">موارد بیشتر</label>
                <textarea id="more_health" class="form-control" name="more_health">{{ old('more_health', $home->more_health) }}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <h4>@lang('title.safety')</h4>
            </div>

            @foreach(\App\Models\Safety::getFromCache() as $index => $safety)
                <div class="col-12 col-md-6 mb-2">
                    <input type="checkbox" @if($home->safeties->contains($safety->id)) checked @endif id="safety-{{ $safety->id }}" class="form-check-input" name="safeties[{{ $index }}][id]" value="{{ $safety->id }}">
                    <label for="safety-{{ $safety->id }}" class="form-label" style="margin-right: 30px;">{{ $safety->title }}</label>
                    <input type="text" placeholder="{{ $safety->placeholder }}" class="form-control" name="safeties[{{ $index }}][description]" value="{{ $home->safeties()->where('safety_id', $safety->id)->first()->pivot->description ?? '' }}">
                </div>
            @endforeach
        </div>
        <div class="row mb-4">
            <div class="col-12 col-md-6 mb-2">
                <label for="more_safety" class="form-label">موارد بیشتر</label>
                <textarea id="more_safety" class="form-control" name="more_safety">{{ old('more_safety', $home->more_safety) }}</textarea>
            </div>
        </div>

        <div class="mt-5 text-center">
            <a href="{{ route('dashboard.homes.index') }}" class="btn btn-danger">@lang('title.return')</a>
            <button class="btn btn-info">@lang('title.edit')</button>
        </div>
    </form>
@endsection
