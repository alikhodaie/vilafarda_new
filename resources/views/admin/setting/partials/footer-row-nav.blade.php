<div class="footer-repeatable-row row mt-2 align-items-center">
    <div class="col-1">
        <button type="button" class="btn btn-falcon-danger btn-sm footer-repeatable-remove"><i class="fa fa-times"></i></button>
    </div>
    <div class="col-12 col-md-3">
        <input type="text" class="form-control" name="{{ $name }}[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}" placeholder="@lang('title.title')">
    </div>
    <div class="col-12 col-md-4">
        <input type="url" class="form-control" name="{{ $name }}[{{ $index }}][link]" value="{{ $row['link'] ?? '' }}" placeholder="@lang('title.url')" dir="ltr">
    </div>
    <div class="col-12 col-md-4">
        <input type="text" class="form-control" name="{{ $name }}[{{ $index }}][icon]" value="{{ $row['icon'] ?? 'bi-link-45deg' }}" placeholder="bi-house" dir="ltr">
    </div>
</div>
