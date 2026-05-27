@extends('layouts.dashboard.dashboard-mobile', [
    'title' => __('title.my_profile'),
    'active' => 'my-profile',
    'breadcrumbs' => [
        ['url' => null, 'title' => __('title.my_profile')]
    ]
])

@section('content')

    <form method="POST" hidden action="{{ route('dashboard.profile.avatar.destroy') }}" 
          onsubmit="return confirm('@lang('text.delete avatar')')">
        @csrf
        @method('DELETE')
        <button id="btn-form-delete-avatar"></button>
    </form>

    <form method="POST" hidden action="{{ route('dashboard.profile.avatar.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input hidden id="input-avatar" accept="image/*" 
               onchange="readURL(this, 'avatar'); document.getElementById('btn-form-update-avatar').click();" 
               type="file" class="form-control-file" name="avatar">
        <button id="btn-form-update-avatar"></button>
    </form>


    <form class="profile-form" method="POST" action="{{ route('dashboard.profile.update') }}">
        @csrf
        @method('PUT')

        <h4 class="section-title">@lang('title.my_account')</h4>

        <div class="avatar-box text-center mb-4">
            <img id="avatar" alt="avatar" class="avatar-img"
                 src="{{ auth()->user()->avatar_path }}"/>
            <div class="d-flex justify-content-center gap-2 mt-3">
                <button onclick="document.getElementById('input-avatar').click()" 
                        type="button" class="btn btn-primary btn-rounded" style="background: #D39D1A; border-color: #D39D1A;">
                    @lang('title.edit')
                    <i class="fa fa-edit ms-1"></i>
                </button>
                <button onclick="document.getElementById('btn-form-delete-avatar').click();" 
                        type="button" class="btn btn-danger btn-rounded">
                    @lang('title.delete')
                    <i class="fa fa-trash ms-1"></i>
                </button>
            </div>
        </div>

        <div class="form-section">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="first_name">@lang('title.first name')</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                           value="{{ old('first_name', auth()->user()->first_name) }}">
                </div>

                <div class="col-12 col-md-6">
                    <label for="last_name">@lang('title.last name')</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                           value="{{ old('last_name', auth()->user()->last_name) }}">
                </div>

                <div class="col-12 col-md-6">
                    <label for="email">@lang('title.email')</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', auth()->user()->email) }}">
                </div>

                <div class="col-12 col-md-6">
                    <label for="mobile">@lang('title.mobile')</label>
                    <input type="text" name="mobile" id="mobile" class="form-control"
                           value="{{ old('mobile', auth()->user()->mobile) }}">
                </div>
            </div>
        </div>

        <h4 class="section-title">@lang('title.password')</h4>
        <div class="form-section">
            <div class="row g-3">
                <div class="col-6">
                    <label for="password">@lang('title.new password')</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="col-6">
                    <label for="password_confirmation">@lang('title.password confirmation')</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="col-12 mt-3">
                    <button class="btn btn-primary w-100 btn-lg" type="submit" style="background: #D39D1A; border-color: #D39D1A;">
                        @lang('title.save_changes')
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('bottom-assets')
    <style>
        body {
            background-color: #fff;
            font-family: 'IRANSans', sans-serif;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1B1A17;
        }

        .avatar-box {
            position: relative;
        }

        .avatar-img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 4px solid #FFC60B;
            object-fit: cover;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #ddd;
            padding: 10px 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #FFC60B;
            box-shadow: 0 0 5px rgba(255, 198, 11, 0.5);
        }

        .btn {
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 8px 16px;
        }

        .btn-primary {
            background-color: #FFC60B;
            border: none;
            color: #1B1A17;
        }

        .btn-primary:hover {
            background-color: #B8860B !important;
            border-color: #B8860B !important;
            color: #1B1A17;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .form-section {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
    </style>

    <script>
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('#' + id).src = e.target.result
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
