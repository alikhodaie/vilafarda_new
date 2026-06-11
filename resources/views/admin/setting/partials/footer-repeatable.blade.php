@php
    $rows = $rows ?? [];
    if (empty($rows)) {
        $rows = [$emptyRow ?? []];
    }
@endphp

<div class="footer-repeatable" data-footer-repeatable="{{ $name }}">
    <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-falcon-success btn-sm footer-repeatable-add" data-template="{{ $name }}">
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
    <template id="footer-repeatable-template-{{ $name }}">
        @include($rowPartial, [
            'name' => $name,
            'index' => '__INDEX__',
            'row' => $emptyRow ?? [],
        ])
    </template>
</div>
