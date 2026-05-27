<div class="footer-repeatable-row row mt-2 align-items-center">
    <div class="col-1">
        <button type="button" class="btn btn-falcon-danger btn-sm footer-repeatable-remove"><i class="fa fa-times"></i></button>
    </div>
    <div class="col-12 col-md-4">
        <input type="text" class="form-control" name="{{ $name }}[{{ $index }}][label]" value="{{ $row['label'] ?? '' }}" placeholder="@lang('title.title')">
    </div>
    <div class="col-12 col-md-7">
        <input type="text" class="form-control" name="{{ $name }}[{{ $index }}][number]" value="{{ $row['number'] ?? '' }}" placeholder="@lang('title.phone_number')" dir="ltr">
    </div>
</div>
