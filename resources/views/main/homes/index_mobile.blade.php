@extends('layouts.main.main', ['title' => __('title.homes'), 'has_footer' => false])

@section('content')
    <a class="contact-button text-light d-flex d-md-none" style="width: 80px; right: 37%; border-radius: 20px; bottom: 15px;" href="#" data-toggle="modal" data-target="#map-homes">
        نقشه
        <i class="fas fa-map-marked mr-2"></i>
    </a>

    <div class="modal fade" id="map-homes" tabindex="-1" role="dialog" aria-labelledby="map-homes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
            <div class="modal-content">
                <span class="mod-close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></span>
                <div class="modal-body mt-4" style="height: 600px">
                    <leaflet-map-homes :homes="{{ json_encode($homes->all()) }}" @if($province) :latitude="{{ $province->latitude }}" :longitude="{{ $province->longitude }}" @endif></leaflet-map-homes>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================ Hero Banner  Start================================== -->
    <div class="home-map-banner half-map">

        <div class="fs-left-map-box">
            <div class="home-map fl-wrap">
                <div class="hm-map-container fw-map">
                    <leaflet-map-homes :homes="{{ json_encode($homes->all()) }}" @if($province) :latitude="{{ $province->latitude }}" :longitude="{{ $province->longitude }}" @endif></leaflet-map-homes>
                </div>
            </div>
        </div>

        <div class="fs-inner-container">
            <div class="fs-content">

                @include('main.homes.partials.filter')

                <!--- All List -->
                <div class="row">
                    @forelse($homes as $home)
                        <!-- Single Property -->
                        <div class="col-12">
                            @include('main.homes.partials.home-card', ['home' => $home, 'is_today' => $is_today_price])
                        </div>
                        <!-- End Single Property -->
                    @empty
                        <div class="col-12 alert alert-danger text-center">@lang('text.empty search')</div>
                    @endforelse
                </div>

                <div class="mb-5 md-mb-0">
                    {{ $homes->links() }}
                </div>

            </div>
        </div>

    </div>
    <div class="clearfix"></div>
    <!-- ============================ Hero Banner End ================================== -->
@endsection

@section('bottom-assets')
    @if($min && $max)
        @php($price_range = (request()->filled('price_range') && is_string('price_range')) ? explode(';', request('price_range')): null)
        
    @endif
@endsection
