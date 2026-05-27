@extends('layouts.dashboard.dashboard', ['title' => __('title.edit_home_image') .' - '. $home->name , 'active' => 'my-homes', 'breadcrumbs' => [
    ['url' => route('dashboard.homes.index'), 'title' => __('title.my_homes')],
    ['url' => null, 'title' => __('title.edit_home_image')],
    ['url' => null, 'title' =>  $home->name]
]])

@section('content')
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-3 mb-sm-0">
            @lang('title.edit_home_image') - {{ $home->name }}
        </h3>
    </div>

    <div class="form-group">
        <change-cover
            csrf="{{ csrf_token() }}"
            route="{{ route('dashboard.homes.custom.images.update', $home) }}"
            max_size="{{ \App\Models\Home::MAX_IMAGE_SIZE }}"
            :cover="{{ json_encode($home->cover) }}"
        ></change-cover>
    </div>

    <div class="form-group">
        <change-images
            csrf="{{ csrf_token() }}"
            route="{{ route('dashboard.homes.image.store', $home) }}"
            max_size="{{ \App\Models\Home::MAX_IMAGE_SIZE }}"
            :images="{{ json_encode($home->images) }}"
        ></change-images>
    </div>

    <div class="mt-5 text-center">
        <a href="{{ route('dashboard.homes.index') }}" class="btn btn-danger">@lang('title.return')</a>
    </div>
@endsection
