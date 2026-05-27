@if($home->sleepPlaces->isNotEmpty() || $home->sleep_area_description)
    <!-- Single Block Wrap -->
    <div class="property_block_wrap mb-1">
        <div class="property_block_wrap_header">
            <h4 class="property_block_title">{{ $home->sleepPlaces->where('is_share', false)->count() }} اتاق - اطلاعات فضای خواب</h4>
        </div>

        <div class="block-body">
            @php($share_place = $home->sleepPlaces->where('is_share', true)->first())
            @if($share_place && ($share_place->single_bed || $share_place->double_bed || $share_place->traditional_bed || $share_place->more))
                <span>فضای مشترک</span>
                <ul>
                    <li>
                        @if($share_place->single_bed)
                            {{ number_format($share_place->single_bed) }} تخت یک نفره
                        @endif
                        @if($share_place->double_bed)
                            {{ number_format($share_place->double_bed) }} تخت دو نفره
                        @endif
                        @if($share_place->traditional_bed)
                            {{ number_format($share_place->traditional_bed) }} رخت خواب سنتی
                        @endif
                        @if($share_place->more)
                            <br>
                            <p>{!! $share_place->more !!}</p>
                        @endif
                    </li>
                </ul>
            @endif

            @foreach($home->sleepPlaces->where('is_share', false) as $index => $place)
                <span>اتاق {{ $index }}</span>
                <ul>
                    @if($place->single_bed)
                        <li>
                            {{ number_format($place->single_bed) }} تخت یک نفره
                        </li>
                    @endif
                    @if($place->double_bed)
                        <li>{{ number_format($place->double_bed) }} تخت دو نفره</li>
                    @endif
                    @if($place->traditional_bed)
                        <li>{{ number_format($place->traditional_bed) }} رخت خواب سنتی</li>
                    @endif
                    @if($place->more)
                        <li>
                            <br>
                            <p>{!! $place->more !!}</p>
                        </li>
                    @endif
                </ul>
            @endforeach

            @if($home->sleep_area_description)
                <p class="p-2">{{ $home->sleep_area_description }}</p>
            @endif
        </div>
    </div>
@endif
