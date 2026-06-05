@foreach(request('q', []) as $term)
    @if(is_string($term) && trim($term) !== '')
        <input type="hidden" name="q[]" value="{{ $term }}">
    @endif
@endforeach
@if(request('start_at'))
    <input type="hidden" name="start_at" value="{{ request('start_at') }}">
@endif
@if(request('end_at'))
    <input type="hidden" name="end_at" value="{{ request('end_at') }}">
@endif
