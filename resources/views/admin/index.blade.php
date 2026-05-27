@extends('layouts.admin.admin', ['title' => __('title.dashboard'), 'active' => 'dashboard'])

@section('content')
    @include('admin.homes.partials.statistics-charts')

    <div class="row mt-3">
        <div class="col-12 col-md-6">
            <count-chart title="تعداد سفارشات" route="{{ route('admin.order-count') }}"></count-chart>
        </div>
    </div>
@endsection
