@if($home->rules)
    <!-- Single Block Wrap -->
    <div class="property_block_wrap mb-1">

        <div class="property_block_wrap_header">
            <h4 class="property_block_title">@lang('title.rules') @lang('title.residence')</h4>
        </div>

        <div class="block-body">
  
            <p style="white-space: break-spaces">{!! $home->rules !!}</p>

            @foreach($home->variables as $item)
                <div @if(! $loop->first) class="mt-2" @endif>
                    - {{ $item->option->name ?? $item->value }}
                </div>
            @endforeach
        </div>

    </div>
@endif
