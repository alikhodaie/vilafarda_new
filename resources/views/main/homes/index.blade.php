@extends('layouts.main.main_mobile')

<style>
    .leaflet-popup-content-wrapper {
    background: transparent !important;
    box-shadow: none !important;
    border-radius: 0 !important;
}

.leaflet-popup-content {
    margin: 0 !important;
    line-height: 1.2;
}
</style>

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    @include('main.partials.search-box')
    @include('main.homes.partials.filter')
    <div class="row pt-3">
        @forelse($homes as $home)
            <!-- Single Property -->
            <div class="col-12 mb-4 px-5">
                @include('main.homes.partials.new-home-card', ['home' => $home, 'is_today' => $is_today_price])
            </div>
            <!-- End Single Property -->
        @empty
            <div class="col-12 alert alert-danger text-center">@lang('text.empty search')</div>
        @endforelse
    </div>

    <!-- دکمه شناور نقشه -->
    <button id="mapToggleBtn" class="position-fixed" 
            style="bottom: 100px; right: 20px; z-index: 1000; background-color: black; color: white; border: none; padding: 12px 20px; border-radius: 50px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-map-marker-alt"></i> نقشه
    </button>

    <!-- Modal نقشه تمام صفحه -->
    <div id="mapModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1050; background:white;">
        <button id="closeMap" style="position:absolute; top:20px; right:20px; z-index:1001; background:black; color:white; border:none; padding:10px 15px; border-radius:50px;">بستن</button>
        <div id="map" style="width:100%; height:100%;"></div>
    </div>

@endsection

@section('scripts')
    <!-- Leaflet.js -->
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/dist/leaflet.css') }}" />
    <script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>

    @php
        $homesForMap = $homes->map(function($home){
            return [
                'lat' => $home->latitude,
                'lng' => $home->longitude,
                'name' => $home->name,
                'link' => $home->link,
                'price' => $home->show_price
            ];
        });
    @endphp

    <script>
        const mapToggleBtn = document.getElementById('mapToggleBtn');
        const mapModal = document.getElementById('mapModal');
        const closeMap = document.getElementById('closeMap');

        mapToggleBtn.addEventListener('click', () => {

            mapModal.style.display = 'block';

            if (!window.homeMap) {
                const map = L.map('map').setView([32, 53], 5); // مرکز ایران
                L.tileLayer('http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
                    attribution: '© Google'
                }).addTo(map);




                window.homeMap = map;

                // اضافه کردن مارکرها
                const homes = @json($homesForMap);
                

                homes.forEach(h => {
                console.log(h);
                const popupContent = `
                <a href="${h.link}">
                    <div class="card shadow-sm border-0 p-2" 
                        style="width: 20rem; border-radius: 16px; direction: rtl; text-align: right;">
                        <div class="d-flex align-items-center">
                            
    
                            <div class="flex-shrink-0 ms-2">
                                <img src="https://images.pexels.com/photos/1134176/pexels-photo-1134176.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500" 
                                    class="img-fluid" 
                                    style="width: 90px; height: 90px; object-fit: cover; border-radius: 12px;"
                                    alt="${h.name}">
                            </div>
                            
        
                            <div class="flex-grow-1 ms-2">
                                <h5 class="fw-bold mb-1" style="font-size: 14px;">${h.name}</h5>
                                <h6 class="fw-bold text-grey mb-0" style="font-size: 13px;">
                                    ${h.price ? h.price + ' تومان / شب' : 'قیمت تماس بگیرید'}
                                </h6>
                            </div>
                        </div>
                    </div>
                </a>
                `;


                    L.marker([h.lat, h.lng], {
                        icon: L.icon({
                            iconUrl: 'https://static.thenounproject.com/png/658934-200.png',
                            iconSize: [32, 32]
                        })
                    }).addTo(map)
                      .bindPopup(popupContent);
                });
            }
        });

        closeMap.addEventListener('click', () => {
            mapModal.style.display = 'none';
        });
    </script>
@endsection
