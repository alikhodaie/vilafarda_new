@extends('layouts.dashboard.dashboard', ['title' => __('title.edit_home_price') .' - '. $home->name , 'active' => 'my-homes', 'breadcrumbs' => [
    ['url' => route('dashboard.homes.index'), 'title' => __('title.my_homes')],
    ['url' => null, 'title' => __('title.edit_home_price')],
    ['url' => null, 'title' =>  $home->name]
]])

@section('content')
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-3 mb-sm-0">
            @lang('title.edit_home_price') - {{ $home->name }}
        </h3>
    </div>

    <form class="my-5" method="POST" action="{{ route('dashboard.homes.custom.price.update', $home) }}">
        @csrf
        @method('PUT')

        <div class="form-group row">
            <div class="col-12 col-md-3 mb-3">
                <label for="week_price">@lang('title.week_price')</label>
                <money-input min="10000" type="number" class_input="form-control" id="week_price" name="week_price" old="{{ old('week_price', $home->week_price) }}"></money-input>
            </div>
            <div class="col-12 col-md-3 mb-3">
                <label for="wed_price">@lang('title.wed_price')</label>
                <money-input min="10000" type="number" class_input="form-control" id="wed_price" name="wed_price" old="{{ old('wed_price', $home->wed_price) }}"></money-input>
            </div>
            <div class="col-12 col-md-3 mb-3">
                <label for="thu_price">@lang('title.thu_price')</label>
                <money-input min="10000" type="number" class_input="form-control" id="thu_price" name="thu_price" old="{{ old('thu_price', $home->thu_price) }}"></money-input>
            </div>
            <div class="col-12 col-md-3 mb-3">
                <label for="fri_price">@lang('title.fri_price')</label>
                <money-input min="10000" type="number" class_input="form-control" id="fri_price" name="fri_price" old="{{ old('fri_price', $home->fri_price) }}"></money-input>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="price_per_surplus">@lang('title.price_per_surplus')</label>
                <money-input min="0" type="number" class_input="form-control" id="price_per_surplus" name="price_per_surplus" old="{{ old('price_per_surplus', $home->price_per_surplus) }}"></money-input>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="off">@lang('title.off')</label>
                <input min="0" max="50" type="number" class="form-control" id="off" name="off" value="{{ old('off', $home->off) }}">
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">تخفیف روزانه</label>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="daily_off" class="form-label">تعداد روز</label>
                <input class="form-control" type="number" id="daily_off" name="daily_off" min="0" max="90" value="{{ old('daily_off', $home->daily_off) }}">
                <p class="text-muted">اگر برابر صفر بگذارید تخفیف روزانه ای اعمال نمیشود</p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="daily_off_amount" class="form-label">درصد تخفیف</label>
                <select class="form-control" id="daily_off_amount" name="daily_off_amount">
                    @foreach(\App\Models\Home::DAILY_OFF_AMOUNTS as $item)
                        <option @if(old('daily_off_amount', $home->daily_off_amount) === $item['value']) selected @endif value="{{ $item['value'] }}">{{ $item['text'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-5 text-center">
            <a href="{{ route('dashboard.homes.index') }}" class="btn btn-danger">@lang('title.return')</a>
            <button class="btn btn-info">@lang('title.edit')</button>
        </div>
    </form>
@endsection
