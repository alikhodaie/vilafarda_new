@php
$icons = [
    [
        'link' => route('main.homes.index', ['types' => ['eghamatgah_boom_gardy']]),
        'image' => asset('assets/img/bomgardi.png'),
        'alt' => 'bomgardi',
        'text' => 'بوم‌گردی',
    ],
    [
        'link' => route('main.homes.index', ['types' => ['kolbeh']]),
        'image' => asset('assets/img/kolbe.png'),
        'alt' => 'kolbe',
        'text' => 'کلبه',
    ],
    [
        'link' => route('main.homes.index', ['areas' => ['saheli']]),
        'image' => asset('assets/img/saheli.png'),
        'alt' => 'saheli',
        'text' => 'ساحلی',
    ],
    [
        'link' => route('main.homes.index', ['options' => [6, 7, 8]]),
        'image' => asset('assets/img/estakhr.png'),
        'alt' => 'estakhr',
        'text' => 'استخردار',
    ],
    [
        'link' => route('main.homes.index', ['types' => ['aparteman']]),
        'image' => asset('assets/img/apartment.png'),
        'alt' => 'apartment',
        'text' => 'آپارتمان',
    ],
    [
        'link' => route('main.homes.index', ['types' => ['vilaiy']]),
        'image' => asset('assets/img/villa.png'),
        'alt' => 'villa',
        'text' => 'ویلا',
    ],
    [
        'link' => route('main.homes.index', ['areas' => ['hotel_aparteman']]),
        'image' => asset('assets/img/hotel.png'),
        'alt' => 'hotel',
        'text' => 'هتل',
    ],
    [
        'link' => route('main.articles.index'),
        'image' => asset('assets/img/tajrobe.png'),
        'alt' => 'tajrobe',
        'text' => 'تجربه',
    ],
];

$icons_chunk = array_chunk($icons, 4);
@endphp

<!-- ============================ Category Icons ================================== -->
<section class="min mt-0 mt-md-2 pb-0 pt-2 pb-md-5">
    <div class="container">
        <div class="d-none d-md-flex justify-content-center">
            @foreach($icons as $icon)
                <div class="w-100 d-flex justify-content-center mt-3 @if(!$loop->last) ml-1 @endif">
                    <a href="{{ $icon['link'] }}" class="border border-1 position-relative text-center pt-3 px-2" style="border-radius: 8px; border-color: #bbb0af !important;">
                        <img src="{{ $icon['image'] }}" alt="{{ $icon['alt'] }}" class="w-100">
                        <b class="d-block text-dark my-1">{{ $icon['text'] }}</b>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="d-flex d-md-none justify-content-center row">
            @foreach($icons_chunk as $icons)
                <div class="col-12 d-flex">
                    @foreach($icons as $icon)
                        <div class="w-100 d-flex justify-content-center mt-3 @if(!$loop->last) ml-4 @endif">
                            <a href="{{ $icon['link'] }}" class="border border-1 position-relative text-center pt-1" style="border-radius: 8px; border-color: #bbb0af !important;">
                                <img src="{{ $icon['image'] }}" alt="{{ $icon['alt'] }}" class="w-50">
                                <b class="d-block text-dark my-1" style="font-size: 10px">{{ $icon['text'] }}</b>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- ============================ Category Icons End ================================== -->
