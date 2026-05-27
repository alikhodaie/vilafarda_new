@if($home->options->isNotEmpty())
    <!-- Single Block Wrap -->
    <div class="property_block_wrap mb-1">

        <div class="property_block_wrap_header">
            <h4 class="property_block_title">@lang('title.options')</h4>
        </div>

        <div class="block-body">
            <ul class="row p-0 m-0">
                @foreach($home->options as $option)
                    <li class="col-lg-4 col-md-6 mb-2 p-0">
                        <x-option-icon :option="$option" :size="30" img-class="ml-1" icon-class="ml-1" />
                        {{ $option->title }}
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
@endif
