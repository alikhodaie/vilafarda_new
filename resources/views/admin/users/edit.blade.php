@extends('layouts.admin.admin', ['title' => __('title.edit user'), 'active' => 'users'])

@section('content')
    <x-admin.card title="{{ __('title.edit user') }}">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-3 row" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-12 mb-5">
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <img id="avatar" width="150" height="150" alt="avatar" class="rounded-circle"
                             src="{{ $user->avatar_path }}"/>
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
                <input class="form-control" name="first_name" id="first_name" type="text" value="{{ old('first_name', $user->first_name) }}"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="last_name">@lang('title.last name') <span>*</span></label>
                <input class="form-control" name="last_name" id="last_name" type="text" value="{{ old('last_name', $user->last_name) }}"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="mobile">@lang('title.mobile') <span>*</span></label>
                <input class="form-control" name="mobile" id="mobile" type="text" value="{{ old('mobile', $user->mobile) }}"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="email">@lang('title.email')</label>
                <input class="form-control" name="email" id="email" type="email" value="{{ old('email', $user->email) }}" placeholder="@lang('title.email')"/>
            </div>

            <div class="col-12 mt-4">
                <div class="alert alert-warning border-2 d-flex align-items-center" role="alert">
                    <div class="bg-warning me-3 icon-item"><span class="fas fa-exclamation-circle text-white fs-3"></span></div>
                    <p class="mb-0 flex-1">{{ __('text.alert.empty password') }}</p>
                </div>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="password">@lang('title.password')</label>
                <input class="form-control" name="password" id="password" type="password"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="password_confirmation">@lang('title.password confirmation')</label>
                <input class="form-control" name="password_confirmation" id="password_confirmation" type="password"/>
            </div>

            @can('block', $user)
                <div class="col-12 mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" id="blocked" name="blocked" type="checkbox" @if(old('blocked', $user->is_block)) checked="" @endif/>
                        <label class="form-check-label" for="blocked">@lang('title.block')</label>
                    </div>
                </div>
            @endcan

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.edit')</button>
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
