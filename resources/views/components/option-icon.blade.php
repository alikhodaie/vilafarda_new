@props([
    'option',
    'size' => 24,
    'imgClass' => '',
    'iconClass' => '',
])

@if($option->is_font_icon)
    <i {{ $attributes->merge(['class' => trim('bi '.$option->icon.' '.$iconClass), 'style' => 'font-size: '.$size.'px; line-height: 1; vertical-align: middle;']) }} aria-hidden="true"></i>
@elseif($option->icon_path)
    <img
        src="{{ $option->icon_path }}"
        alt="{{ $option->title }}"
        width="{{ $size }}"
        height="{{ $size }}"
        class="{{ $imgClass }}"
        {{ $attributes->except(['class']) }}
    />
@endif
