@foreach($selectedOptions ?? [] as $optionId)
    <input type="hidden" name="options[]" value="{{ $optionId }}">
@endforeach
