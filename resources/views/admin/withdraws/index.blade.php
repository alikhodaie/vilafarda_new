@extends('layouts.admin.admin', ['title' => __('title.withdraws'), 'active' => 'withdraws'])

@php
    $canBulkPay = auth()->user()->hasPermissionTo('withdraws:update');
@endphp

@section('content')
    <x-admin.card title="{{ __('title.withdraws') }}">
        @if($items->isEmpty())
            <x-admin.empty-message></x-admin.empty-message>
        @else
            <div v-pre id="withdraw-bulk-root">
                @if($canBulkPay)
                    <form action="{{ route('admin.withdraws.bulk-paid') }}"
                          method="POST"
                          id="withdraw-bulk-form"
                          onsubmit="return confirm('موارد انتخاب‌شده به «پرداخت شده» تغییر کنند؟');">
                        @csrf
                        <div class="d-flex flex-wrap align-items-center gap-3 px-3 pt-3 pb-2 border-bottom bg-light">
                            <div class="form-check mb-0">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="withdraw-select-all"
                                       aria-label="انتخاب همه"
                                       onclick="window.withdrawBulkToggleAll(this)">
                                <label class="form-check-label small fw-semibold" for="withdraw-select-all">انتخاب همه</label>
                            </div>
                            <button type="submit"
                                    class="btn btn-success btn-sm rounded-pill px-4"
                                    id="withdraw-bulk-paid-btn"
                                    disabled="disabled">
                                <span class="fas fa-check ms-1"></span>
                                علامت‌گذاری به‌عنوان پرداخت شده
                            </button>
                            <span class="text-muted small" id="withdraw-bulk-selected-hint"></span>
                        </div>

                        <div class="table-responsive scrollbar">
                            <table class="table table-hover table-striped overflow-hidden mb-0">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="width: 3rem;"></th>
                                    <th scope="col">شناسه</th>
                                    <th scope="col">@lang('title.user')</th>
                                    <th scope="col">اقامتگاه / رزرو</th>
                                    <th scope="col">مبلغ تسویه</th>
                                    <th scope="col">@lang('title.status')</th>
                                    <th scope="col">@lang('title.created at')</th>
                                    <th class="text-end" scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    @php
                                        $order = $item->order;
                                        $home = $order?->home;
                                        $isPending = $item->status === \App\Models\HostPayout::PENDING;
                                    @endphp
                                    <tr class="align-middle">
                                        <td class="text-center">
                                            @if($isPending && auth()->user()->can('update', $item))
                                                <input type="checkbox"
                                                       name="ids[]"
                                                       value="{{ $item->id }}"
                                                       class="form-check-input withdraw-bulk-check"
                                                       onclick="window.withdrawBulkRefresh()">
                                            @endif
                                        </td>
                                        <td class="text-nowrap fw-semibold">#{{ $item->id }}</td>
                                        <td class="text-nowrap">{{ $item->user?->full_name ?? '—' }}</td>
                                        <td class="text-nowrap">
                                            {{ $home?->name ?? '—' }}
                                            @if($order)
                                                <br><small class="text-muted">رزرو #{{ $order->id }}</small>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">{{ number_format((int) $item->amount) }} @lang('title.toman')</td>
                                        <td class="text-nowrap">
                                            <span class="badge rounded-pill d-block p-2 badge-soft-{{ $item->status('color') }}">
                                                {{ $item->status() }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">{{ $item->persianCreatedAt() }}</td>
                                        <td class="text-end">
                                            @can('update', $item)
                                                <a href="{{ route('admin.withdraws.edit', $item->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                                    <span class="text-500 fas fa-edit"></span>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <script>
                        window.withdrawBulkToggleAll = function (master) {
                            var form = document.getElementById('withdraw-bulk-form');
                            if (!form) {
                                return;
                            }
                            form.querySelectorAll('input.withdraw-bulk-check').forEach(function (cb) {
                                cb.checked = master.checked;
                            });
                            window.withdrawBulkRefresh();
                        };

                        window.withdrawBulkRefresh = function () {
                            var form = document.getElementById('withdraw-bulk-form');
                            if (!form) {
                                return;
                            }
                            var checks = form.querySelectorAll('input.withdraw-bulk-check');
                            var selectAll = document.getElementById('withdraw-select-all');
                            var submitBtn = document.getElementById('withdraw-bulk-paid-btn');
                            var hint = document.getElementById('withdraw-bulk-selected-hint');
                            var selected = 0;
                            var total = checks.length;

                            checks.forEach(function (cb) {
                                if (cb.checked) {
                                    selected++;
                                }
                                var row = cb.closest('tr');
                                if (row) {
                                    row.classList.toggle('table-warning', cb.checked);
                                }
                            });

                            if (submitBtn) {
                                if (selected > 0) {
                                    submitBtn.removeAttribute('disabled');
                                    submitBtn.classList.remove('disabled');
                                } else {
                                    submitBtn.setAttribute('disabled', 'disabled');
                                    submitBtn.classList.add('disabled');
                                }
                            }

                            if (hint) {
                                hint.textContent = selected ? (selected + ' مورد انتخاب شده') : '';
                            }

                            if (selectAll) {
                                selectAll.checked = total > 0 && selected === total;
                                selectAll.indeterminate = selected > 0 && selected < total;
                            }
                        };

                        window.withdrawBulkRefresh();
                    </script>
                @else
                    <div class="table-responsive scrollbar">
                        <table class="table table-hover table-striped overflow-hidden mb-0">
                            <thead>
                            <tr>
                                <th scope="col">شناسه</th>
                                <th scope="col">@lang('title.user')</th>
                                <th scope="col">اقامتگاه / رزرو</th>
                                <th scope="col">مبلغ تسویه</th>
                                <th scope="col">@lang('title.status')</th>
                                <th scope="col">@lang('title.created at')</th>
                                <th class="text-end" scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                @php
                                    $order = $item->order;
                                    $home = $order?->home;
                                @endphp
                                <tr class="align-middle">
                                    <td class="text-nowrap fw-semibold">#{{ $item->id }}</td>
                                    <td class="text-nowrap">{{ $item->user?->full_name ?? '—' }}</td>
                                    <td class="text-nowrap">
                                        {{ $home?->name ?? '—' }}
                                        @if($order)
                                            <br><small class="text-muted">رزرو #{{ $order->id }}</small>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">{{ number_format((int) $item->amount) }} @lang('title.toman')</td>
                                    <td class="text-nowrap">
                                        <span class="badge rounded-pill d-block p-2 badge-soft-{{ $item->status('color') }}">
                                            {{ $item->status() }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">{{ $item->persianCreatedAt() }}</td>
                                    <td class="text-end">
                                        @can('update', $item)
                                            <a href="{{ route('admin.withdraws.edit', $item->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                                <span class="text-500 fas fa-edit"></span>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-center py-3">
                {{ $items->links() }}
            </div>
        @endif
    </x-admin.card>
@endsection
