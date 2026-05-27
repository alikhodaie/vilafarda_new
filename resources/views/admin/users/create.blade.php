@extends('layouts.admin.admin', ['title' => __('title.create user'), 'active' => 'users'])

@section('content')
    <x-admin.card title="{{ __('title.create user') }}">
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-3 row" enctype="multipart/form-data">
            @csrf

            <div class="col-12 mb-5">
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <img id="avatar" width="150" height="150" alt="avatar" class="rounded-circle"
                             src="{{ \App\Models\User::getDefaultAvatar() }}"/>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <div class="custom-file">
                                <input onchange="readURL(this, 'avatar');" type="file" class="form-control-file" name="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="first_name">@lang('title.first name') <span>*</span></label>
                <input class="form-control" name="first_name" id="first_name" type="text" value="{{ old('first_name') }}"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="last_name">@lang('title.last name') <span>*</span></label>
                <input class="form-control" name="last_name" id="last_name" type="text" value="{{ old('last_name') }}"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="mobile">@lang('title.mobile') <span>*</span></label>
                <input class="form-control" name="mobile" id="mobile" type="text" value="{{ old('mobile') }}"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="email">@lang('title.email')</label>
                <input class="form-control" name="email" id="email" type="email" value="{{ old('email') }}" placeholder="@lang('title.email')"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="password">@lang('title.password') <span>*</span></label>
                <input class="form-control" name="password" id="password" type="password"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="password_confirmation">@lang('title.password confirmation') <span>*</span></label>
                <input class="form-control" name="password_confirmation" id="password_confirmation" type="password"/>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
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
