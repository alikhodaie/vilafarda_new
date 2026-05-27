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

@once
    @push('bottom-assets')
        <script>
            document.querySelectorAll('.footer-repeatable-add').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const name = btn.getAttribute('data-template');
                    const wrapper = btn.closest('.footer-repeatable');
                    const list = wrapper.querySelector('.footer-repeatable-list');
                    const template = document.getElementById('footer-repeatable-template-' + name);
                    if (!list || !template) return;
                    const index = list.querySelectorAll('.footer-repeatable-row').length;
                    list.insertAdjacentHTML('beforeend', template.innerHTML.replace(/__INDEX__/g, String(index)));
                });
            });

            document.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.footer-repeatable-remove');
                if (!removeBtn) return;
                const row = removeBtn.closest('.footer-repeatable-row');
                const list = row?.closest('.footer-repeatable-list');
                if (!row || !list) return;
                row.remove();
                if (!list.querySelector('.footer-repeatable-row')) {
                    const addBtn = list.closest('.footer-repeatable')?.querySelector('.footer-repeatable-add');
                    addBtn?.click();
                }
            });
        </script>
    @endpush
@endonce
