@extends('layouts.dashboard.dashboard', ['title' => __('title.my_profile'), 'active' => 'my-profile', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.my_profile')]
]])

@section('content')
    <form method="POST" hidden action="{{ route('dashboard.profile.avatar.destroy') }}" onsubmit="return confirm('@lang('text.delete avatar')')">
        @csrf
        @method('DELETE')
        <button id="btn-form-delete-avatar"></button>
    </form>
    <form method="POST" hidden action="{{ route('dashboard.profile.avatar.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <input hidden id="input-avatar" accept="image/*" onchange="readURL(this, 'avatar'); document.getElementById('btn-form-update-avatar').click();" type="file" class="form-control-file" name="avatar">

        <button id="btn-form-update-avatar"></button>
    </form>

    <!-- Basic Information -->
    <form class="frm_submit_block" method="POST" action="{{ route('dashboard.profile.update') }}">
        @csrf
        @method('PUT')

        <h4>@lang('title.my_account')</h4>
        <div class="frm_submit_wrap">
            <div class="form-row">

                <div class="col-12 mb-5">
                    <div class="form-group">
                        <div class="dash_user_avater">
                            <img id="avatar" alt="avatar" class="img-fluid avater"
                                 src="{{ auth()->user()->avatar_path }}"/>
                            <div class="d-flex justify-content-center">
                                <button onclick="document.getElementById('input-avatar').click()" type="button" class="btn btn-theme btn-rounded">
                                    @lang('title.edit')
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button onclick="document.getElementById('btn-form-delete-avatar').click();" type="button" class="btn btn-danger btn-rounded">
                                    @lang('title.delete')
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="first_name">@lang('title.first name')</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', auth()->user()->first_name) }}">
                </div>

                <div class="form-group col-md-6">
                    <label for="last_name">@lang('title.last name')</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', auth()->user()->last_name) }}">
                </div>

                <div class="form-group col-md-6">
                    <label for="email">@lang('title.email')</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', auth()->user()->email) }}">
                </div>

                <div class="form-group col-md-6">
                    <label for="mobile">@lang('title.mobile')</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile', auth()->user()->mobile) }}">
                </div>

            </div>
        </div>

        <h4>@lang('title.password')</h4>
        <div class="frm_submit_wrap">
            <div class="form-row">

                <div class="form-group col-6">
                    <label for="password">@lang('title.new password')</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="form-group col-6">
                    <label for="password_confirmation">@lang('title.password confirmation')</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-group col-lg-12 col-md-12 mt-4">
                    <button class="btn btn-theme btn-lg" type="submit">@lang('title.save_changes')</button>
                </div>

            </div>
        </div>
    </form>
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
