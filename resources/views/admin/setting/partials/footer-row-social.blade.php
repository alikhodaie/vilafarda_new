@php
    $iconType = $row['icon_type'] ?? 'font';
    $iconPreview = ($iconType === 'image' && !empty($row['icon'])) ? footerSocialIconUrl($row) : null;
@endphp
<div class="footer-repeatable-row border rounded p-3 mt-2">
    <div class="row align-items-center">
        <div class="col-1">
            <button type="button" class="btn btn-falcon-danger btn-sm footer-repeatable-remove"><i class="fa fa-times"></i></button>
        </div>
        <div class="col-12 col-md-2">
            <input type="text" class="form-control" name="{{ $name }}[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}" placeholder="@lang('title.title')">
        </div>
        <div class="col-12 col-md-3">
            <input type="url" class="form-control" name="{{ $name }}[{{ $index }}][link]" value="{{ $row['link'] ?? '' }}" placeholder="@lang('title.url')" dir="ltr">
        </div>
        <div class="col-12 col-md-2">
            <input type="text" class="form-control" name="{{ $name }}[{{ $index }}][follower_count]" value="{{ $row['follower_count'] ?? '' }}" placeholder="@lang('title.footer_follower_count')" dir="ltr">
            <small class="text-muted">مثلاً 1M یا 10K</small>
        </div>
        <div class="col-12 col-md-3">
            <select class="form-select footer-social-icon-type" name="{{ $name }}[{{ $index }}][icon_type]">
                <option value="font" @selected($iconType === 'font')>آیکون Bootstrap</option>
                <option value="image" @selected($iconType === 'image')>تصویر</option>
            </select>
        </div>
    </div>
    <div class="row mt-2 footer-social-font-panel {{ $iconType === 'font' ? '' : 'd-none' }}">
        <div class="col-12 col-md-6">
            <input type="text" class="form-control footer-social-icon-class" name="{{ $name }}[{{ $index }}][icon_class]" value="{{ $row['icon_class'] ?? '' }}" placeholder="bi-instagram" dir="ltr">
            <small class="text-muted">کلاس Bootstrap Icons (مثلاً bi-instagram)</small>
        </div>
    </div>
    <div class="row mt-2 footer-social-image-panel {{ $iconType === 'image' ? '' : 'd-none' }}">
        <div class="col-12 col-md-6">
            <input type="file" class="form-control" name="{{ $name }}[{{ $index }}][icon_image]" accept="image/*">
            @if($iconType === 'image' && !empty($row['icon']))
                <input type="hidden" name="{{ $name }}[{{ $index }}][icon]" value="{{ $row['icon'] }}">
                @if($iconPreview)
                    <img src="{{ $iconPreview }}" alt="" width="48" height="48" class="mt-2" style="object-fit:contain;">
                @endif
            @endif
        </div>
    </div>
</div>

@once
    @push('bottom-assets')
        <script>
            document.addEventListener('change', function (e) {
                const select = e.target.closest('.footer-social-icon-type');
                if (!select) return;
                const row = select.closest('.footer-repeatable-row');
                const isFont = select.value === 'font';
                row.querySelector('.footer-social-font-panel')?.classList.toggle('d-none', !isFont);
                row.querySelector('.footer-social-image-panel')?.classList.toggle('d-none', isFont);
            });

        </script>
    @endpush
@endonce
