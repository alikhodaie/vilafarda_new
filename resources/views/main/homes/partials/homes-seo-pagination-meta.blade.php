@if(isset($homes) && $homes instanceof \Illuminate\Pagination\LengthAwarePaginator && $homes->hasPages())
    @if($homes->onFirstPage() === false)
        <link rel="prev" href="{{ $homes->previousPageUrl() }}">
    @endif
    @if($homes->hasMorePages())
        <link rel="next" href="{{ $homes->nextPageUrl() }}">
    @endif
@endif
