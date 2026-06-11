@php
    $rows = $rows ?? [];
    if (empty($rows)) {
        $rows = [$emptyRow ?? []];
    }
@endphp

<div class="footer-repeatable" data-footer-repeatable="{{ $name }}">
    <div class="d-flex justify-content-end mb-2">
        <button type="button"
                class="btn btn-falcon-success btn-sm footer-repeatable-add"
                data-template="{{ $name }}"
                onclick="window.footerRepeatableAdd && window.footerRepeatableAdd(this)">
            <i class="fa fa-plus"></i>
            <span class="visually-hidden">افزودن</span>
        </button>
    </div>
    <div class="footer-repeatable-list">
        @foreach($rows as $index => $row)
            @include($rowPartial, [
                'name' => $name,
                'index' => $index,
                'row' => $row,
            ])
        @endforeach
    </div>
    <div class="d-none footer-repeatable-template" data-footer-template="{{ $name }}" aria-hidden="true">
        @include($rowPartial, [
            'name' => $name,
            'index' => '__INDEX__',
            'row' => $emptyRow ?? [],
        ])
    </div>
</div>
