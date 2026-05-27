@if($home->healths->isNotEmpty() || $home->more_health)
    <!-- Single Block Wrap -->
    <div class="property_block_wrap mb-1">
        <div class="property_block_wrap_header">
            <h4 class="property_block_title">اقلام بهداشتی</h4>
        </div>

        <div class="block-body">
            <ul class="p-0 m-0 row d-flex">
                @foreach($home->healths as $health)
                    <li class="col-12 col-md-6 mt-2">
                        {{ $health->title }}
                    </li>
                @endforeach
            </ul>

            @if($home->more_health)
                <p class="p-3">{{ $home->more_health }}</p>
            @endif
        </div>
    </div>
@endif
