@extends('layouts.admin.admin', ['title' => __('title.edit consultant'), 'active' => 'consultants'])

@section('content')
    <x-admin.card title="{{ __('title.edit consultant') }}">
        <form action="{{ route('admin.consultants.update', $consultant->id) }}" method="POST" class="p-3 row" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-12 mb-5">
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <img id="image" width="150" height="150" alt="image" class="rounded-circle"
                             src="{{ $consultant->image_path }}" onerror="this.src='{{ asset('assets/images/avatar.jpg') }}'"/>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <div class="custom-file">
                                <input onchange="readURL(this, 'image');" type="file" class="form-control-file" name="image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="name">@lang('title.name') <span>*</span></label>
                <input class="form-control" name="name" id="name" type="text" value="{{ old('name', $consultant->name) }}"/>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="province">@lang('title.province')<span>*</span></label>
                <province-input
                    route="{{ route('dashboard.provinces') }}"
                    name="province"
                    placeholder="@lang('text.insert_province')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                    old="{{ old('province', $consultant->province_id) }}"
                ></province-input>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="city">@lang('title.city')<span>*</span></label>
                <city-input
                    placeholder="@lang('text.insert_city')"
                    name="city"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                    old="{{ old('city', $consultant->city_id) }}"
                ></city-input>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="phone_number">@lang('title.mobile')</label>
                <input class="form-control" name="phone_number" id="phone_number" type="text" value="{{ old('phone_number', $consultant->phone_number) }}"/>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="whatsapp_number">@lang('title.whatsapp_number')</label>
                <input class="form-control" name="whatsapp_number" id="whatsapp_number" type="text" value="{{ old('whatsapp_number', $consultant->whatsapp_number) }}"/>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="whatsapp_default_message">@lang('title.whatsapp_default_message')</label>
                <input class="form-control" name="whatsapp_default_message" id="whatsapp_default_message" type="text" value="{{ old('whatsapp_default_message', $consultant->whatsapp_default_message) }}">
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.edit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.consultants.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
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
