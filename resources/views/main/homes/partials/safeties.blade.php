@if($home->safeties->isNotEmpty() || $home->more_safety)
    <!-- Single Block Wrap -->
    <div class="property_block_wrap mb-1">
        <div class="property_block_wrap_header">
            <h4 class="property_block_title">ایمنی</h4>
        </div>

        <div class="block-body">
            <ul class="p-0 m-0 row d-flex">
                @foreach($home->safeties as $safety)
                    <li class="col-12 col-md-6 mt-2">
                        {{ $safety->title }}
                        @if($safety->pivot->description)
                            <span>({{ $safety->pivot->description }})</span>
                        @endif
                    </li>
                @endforeach
            </ul>

            @if($home->more_safety)
                <p class="p-3">{{ $home->more_safety }}</p>
            @endif
        </div>
    </div>
@endif
