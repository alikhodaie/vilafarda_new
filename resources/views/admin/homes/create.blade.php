@extends('layouts.admin.admin', ['title' => __('title.create home'), 'active' => 'homes'])

@section('content')
    <x-admin.card title="{{ __('title.create home') }}">
     
        <form action="{{ route('admin.homes.store') }}" enctype="multipart/form-data" method="POST" class="p-3 row">
            @csrf

            <div class="col-12 col-md-4 mb-3">
                <label for="code">@lang('title.code')</label>
                <input type="text" class="form-control" name="code" id="code" maxlength="50" value="{{ old('code') }}">
            </div>

            <div class="col-12 col-md-4 mb-3">
                <label for="document">@lang('title.document')</label>
                <input type="file" class="form-control" name="document" id="document">
            </div>

            <div class="col-12 col-md-4 mb-3">
                <label for="shaba">@lang('title.shaba')</label>
                <input type="text" class="form-control" name="shaba" value="{{ old('shaba') }}">
            </div>

            <div class="col-12 mb-3">
                <label for="user">@lang('title.user')</label>
                <user-input
                    @if(old('user'))
                    old="{{ old('user') }}"
                    @endif
                    route="{{ route('admin.ajax.users') }}"
                    placeholder="@lang('text.select_user')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                ></user-input>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label for="status">@lang('title.status')</label>
                <select name="status" id="status" class="form-control">
                    <option value="">@lang('title.select')</option>
                    @foreach(\App\Models\Home::STATUSES as $status)
                        <option value="{{ $status['value'] }}" @if($status['value'] == old('status')) selected @endif>{{ $status['fa_text'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label for="reject_policy">@lang('title.reserve_cancel_policy')</label>
                <select name="reject_policy" id="reject_policy" class="form-control">
                    <option value="">@lang('title.select')</option>
                    @foreach(\App\Models\Home::REJECT_POLICIES as $policy)
                        <option value="{{ $policy['value'] }}" @if($policy['value'] == old('reject_policy')) selected @endif>{{ $policy['title'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mb-3">
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <img id="cover" width="200" alt="cover"
                             src="/"/>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <div class="custom-file">
                                <input onchange="readURL(this, 'cover');" type="file" class="form-control-file" name="cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <x-dashboard.home.variables class="mb-3"></x-dashboard.home.variables>
            </div>

            <div class="col-12 mb-3">
                <label for="name">@lang('title.name')</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            </div>

            <div class="col-12">
                <label for="description">@lang('title.description')</label>
                <textarea class="form-control" id="description" name="description">{!! old('description') !!}</textarea>
            </div>

            <div class="col-12">
                <label for="rules">@lang('title.rules')</label>
                <textarea class="form-control" id="rules" name="rules">{!! old('rules') !!}</textarea>
            </div>

            <div class="col-12 row">
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
                        old="{{ old('province') }}"
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
                        old="{{ old('city') }}"
                    ></city-input>
                </div>
            </div>

            <div class="col-12">
                <label for="address">@lang('title.address')</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
            </div>

            <div class="col-12 my-2">
                <label for="map">@lang('title.map')</label>
                <leaftlet-map :watch_province="true"></leaftlet-map>
            </div>

            <div class="col-12 row">
                <div class="col-12 col-md-4 mb-3">
                    <label for="atmosphere">@lang('title.atmosphere')</label>
                    <select name="atmosphere" id="atmosphere" class="form-control">
                        <option value="">@lang('title.select')</option>
                        @foreach(\App\Models\Home::ATMOSPHERES as $atmosphere)
                            <option value="{{ $atmosphere['value'] }}" @if($atmosphere['value'] == old('atmosphere')) selected @endif>{{ $atmosphere['fa_text'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4 mb-3">
                    <label for="type">@lang('title.type')</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">@lang('title.select')</option>
                        @foreach(\App\Models\Home::TYPES as $type)
                            <option value="{{ $type['value'] }}" @if($type['value'] == old('type')) selected @endif>{{ $type['fa_text'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4 mb-3">
                    <label for="area">@lang('title.area')</label>
                    <select name="area" id="area" class="form-control">
                        <option value="">@lang('title.select')</option>
                        @foreach(\App\Models\Home::AREAS as $area)
                            <option value="{{ $area['value'] }}" @if($area['value'] == old('area')) selected @endif>{{ $area['fa_text'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group my-4">
                <home-bed-room
                    count_sleep_rooms="@lang('title.count_sleep_rooms')"
                ></home-bed-room>
            </div>

            <div class="col-12 row">
                <div class="col-12 col-md-3">
                    <label for="yard">@lang('title.yard_meter')</label>
                    <input type="number" class="form-control" id="yard" name="yard" value="{{ old('yard', 0) }}">
                </div>
                <div class="col-12 col-md-3">
                    <label for="infrastructure">@lang('title.infrastructure_meter')</label>
                    <input type="number" class="form-control" id="infrastructure" name="infrastructure" value="{{ old('infrastructure', 0) }}">
                </div>
                <div class="col-12 col-md-3">
                    <label for="main_guest">@lang('title.main_guest_count')</label>
                    <input type="number" class="form-control" id="main_guest" name="main_guest" value="{{ old('main_guest', 0) }}">
                </div>
                <div class="col-12 col-md-3">
                    <label for="extra_guest">@lang('title.extra_guest_count')</label>
                    <input type="number" class="form-control" id="extra_guest" name="extra_guest" value="{{ old('extra_guest', 0) }}">
                </div>
            </div>

            <div class="col-12 row">
                <div class="col-12 col-md-3">
                    <label for="week_price">@lang('title.week_price')</label>
                    <money-input min="10000" type="number" class_input="form-control" id="week_price" name="week_price" old="{{ old('week_price', 0) }}"></money-input>
                </div>
                <div class="col-12 col-md-2">
                    <label for="wed_price">@lang('title.wed_price')</label>
                    <money-input min="10000" type="number" class_input="form-control" id="wed_price" name="wed_price" old="{{ old('wed_price', 0) }}"></money-input>
                </div>
                <div class="col-12 col-md-2">
                    <label for="thu_price">@lang('title.thu_price')</label>
                    <money-input min="10000" type="number" class_input="form-control" id="thu_price" name="thu_price" old="{{ old('thu_price', 0) }}"></money-input>
                </div>
                <div class="col-12 col-md-2">
                    <label for="fri_price">@lang('title.fri_price')</label>
                    <money-input min="10000" type="number" class_input="form-control" id="fri_price" name="fri_price" old="{{ old('fri_price', 0) }}"></money-input>
                </div>
                <div class="col-12 col-md-3">
                    <label for="price_per_surplus">@lang('title.price_per_surplus')</label>
                    <money-input min="0" type="number" class_input="form-control" id="price_per_surplus" name="price_per_surplus" old="{{ old('price_per_surplus', 0) }}"></money-input>
                </div>
                <div class="col-12 col-md-6">
                    <label for="off">@lang('title.off')</label>
                    <input min="0" max="50" type="number" class="form-control" id="off" name="off" value="{{ old('off', 0) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">تخفیف روزانه</label>
                </div>
                <div class="col-12 col-md-6">
                    <label for="daily_off" class="form-label">تعداد روز</label>
                    <input class="form-control" type="number" id="daily_off" name="daily_off" min="0" max="90" value="{{ old('daily_off', 0) }}">
                    <p class="text-muted">اگر برابر صفر بگذارید تخفیف روزانه ای اعمال نمیشود</p>
                </div>
                <div class="col-12 col-md-6">
                    <label for="daily_off_amount" class="form-label">درصد تخفیف</label>
                    <select class="form-control" id="daily_off_amount" name="daily_off_amount">
                        @foreach(\App\Models\Home::DAILY_OFF_AMOUNTS as $item)
                            <option @if(old('daily_off_amount', 0) === $item['value']) selected @endif value="{{ $item['value'] }}">{{ $item['text'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group row">
                    <div class="col-12">
                        <h4>@lang('title.options')</h4>
                    </div>

                    @foreach(\App\Models\Option::getFromCache() as $option)
                        <div class="col-12 col-md-6 mb-3">
                    <span class="px-5">
                        <input type="checkbox" @if(collect(old('options', []))->contains($option->id)) checked @endif id="option-{{ $option->id }}" class="form-check-input" name="options[]" value="{{ $option->id }}">
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
                            <input type="checkbox" @if(collect(old('healths', []))->contains($health->id)) checked @endif id="health-{{ $health->id }}" class="form-check-input" name="healths[]" value="{{ $health->id }}">
                            <label for="health-{{ $health->id }}" class="form-label" style="margin-right: 30px;">{{ $health->title }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="row mb-4">
                    <div class="col-12 col-md-6 mb-2">
                        <label for="more_health" class="form-label">موارد بیشتر</label>
                        <textarea id="more_health" class="form-control" name="more_health">{{ old('more_health') }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <h4>@lang('title.safety')</h4>
                    </div>

                    @foreach(\App\Models\Safety::getFromCache() as $index => $safety)
                        <div class="col-12 col-md-6 mb-2">
                            <input type="checkbox" @if(collect(old('safeties', []))->contains($safety->id)) checked @endif id="safety-{{ $safety->id }}" class="form-check-input" name="safeties[{{ $index }}][id]" value="{{ $safety->id }}">
                            <label for="safety-{{ $safety->id }}" class="form-label" style="margin-right: 30px;">{{ $safety->title }}</label>
                            <input type="text" placeholder="{{ $safety->placeholder }}" class="form-control" name="safeties[{{ $index }}][description]">
                        </div>
                    @endforeach
                </div>
                <div class="row mb-4">
                    <div class="col-12 col-md-6 mb-2">
                        <label for="more_safety" class="form-label">موارد بیشتر</label>
                        <textarea id="more_safety" class="form-control" name="more_safety">{{ old('more_safety') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <a href="{{ route('admin.homes.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection

@section('bottom-assets')
    <script>
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    // jQuery('#' + id).attr('src', e.target.result);
                    document.querySelector('#' + id).src = e.target.result
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
