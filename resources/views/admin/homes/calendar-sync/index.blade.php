@extends('layouts.admin.admin', ['title' => __('title.homes').' | '.__('title.calendar_sync'), 'active' => 'homes-calendar-sync'])

@section('content')
    <x-admin.search-card route="{{ route('admin.homes.calendar-sync.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="code">@lang('title.code')</label>
            <input type="text" class="form-control" name="code" id="code" value="{{ request('code') }}" placeholder="کد اقامتگاه">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="name">@lang('title.name')</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ request('name') }}" placeholder="نام اقامتگاه یا کد">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="user">@lang('title.user') / میزبان</label>
            <user-input
                @if(request()->filled('user'))
                old="{{ request('user') }}"
                @endif
                route="{{ route('admin.ajax.users') }}"
                placeholder="@lang('text.select_user')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
            ></user-input>
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="has_external_link">@lang('title.external_link_status')</label>
            <select name="has_external_link" id="has_external_link" class="form-control">
                <option value="">@lang('title.all')</option>
                <option value="yes" @selected(request('has_external_link') === 'yes')>@lang('title.has_external_link')</option>
                <option value="no" @selected(request('has_external_link') === 'no')>@lang('title.no_external_link')</option>
            </select>
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="sync_enabled">@lang('title.auto_sync_status')</label>
            <select name="sync_enabled" id="sync_enabled" class="form-control">
                <option value="">@lang('title.all')</option>
                <option value="yes" @selected(request('sync_enabled') === 'yes')>@lang('title.enabled')</option>
                <option value="no" @selected(request('sync_enabled') === 'no')>@lang('title.disabled')</option>
            </select>
        </div>
    </x-admin.search-card>

    <x-admin.card title="{{ __('title.calendar_sync') }}">
        <div class="alert alert-info mb-3" role="alert">
            لینک صفحه اقامتگاه در سایت‌های دیگر (مثل جاجیگا یا جاباما) را وارد کنید. پلتفرم به‌صورت خودکار تشخیص داده می‌شود.
            همگام‌سازی فعلاً برای <strong>جاجیگا</strong> و <strong>جاباما</strong> فعال است.
            <hr class="my-2">
            <strong>ترتیب لیست:</strong> اول اقامتگاه‌های لینک‌دار (قدیمی‌ترین به‌روزرسانی بالاتر)، بعد بدون لینک.
            <br>
            <strong>محدودیت همگام‌سازی دستی:</strong> بین هر درخواست حداقل {{ $syncCooldownTotal }} ثانیه فاصله لازم است تا IP مسدود نشود.
            <span id="calendar-sync-cooldown-banner"
                  class="d-none ms-1"
                  data-remaining="{{ (int) $syncCooldownSeconds }}">
                بعدی: <strong id="calendar-sync-cooldown-banner-text">—</strong>
            </span>
        </div>

        @can('syncAllCalendar', \App\Models\Home::class)
            <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
                <form method="POST" action="{{ route('admin.homes.calendar-sync.sync-all') }}" class="d-inline"
                      onsubmit="return confirm('فقط قدیمی‌ترین اقامتگاه لینک‌دار (نفر اول صف) همگام شود؟');">
                    @csrf
                    <button type="submit"
                            class="btn btn-outline-primary calendar-sync-action-btn"
                            @disabled($syncCooldownSeconds > 0)>
                        <span class="fas fa-sync-alt me-1"></span>
                        @lang('title.manual_sync_all')
                    </button>
                </form>
            </div>
        @endcan

        @if($homes->isEmpty())
            <x-admin.empty-message></x-admin.empty-message>
        @else
            <form method="POST" action="{{ route('admin.homes.calendar-sync.update') }}" id="calendar-sync-form">
                @csrf
                @method('PUT')

                <div class="table-responsive scrollbar">
                    <table class="table table-hover table-striped overflow-hidden">
                        <thead>
                            <tr>
                                <th scope="col">@lang('title.code')</th>
                                <th scope="col">@lang('title.name')</th>
                                <th scope="col">@lang('title.user')</th>
                                <th scope="col">@lang('title.platform')</th>
                                <th scope="col" style="min-width: 280px;">@lang('title.external_link')</th>
                                <th scope="col">@lang('title.auto_sync')</th>
                                <th scope="col" style="min-width: 170px;">@lang('title.last_sync')</th>
                                <th class="text-end" scope="col">@lang('title.manual_sync_actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($homes as $home)
                            @php($source = $home->calendarSource)
                            @php($hasLink = filled($source?->external_url))
                            <tr class="align-middle {{ $hasLink ? '' : 'table-light' }}">
                                <td class="text-nowrap">
                                    <span class="badge rounded-pill badge-soft-primary">{{ $home->code ?: '—' }}</span>
                                    <small class="d-block text-muted mt-1">#{{ $home->id }}</small>
                                </td>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        @if($home->cover)
                                            <img width="60" src="{{ $home->cover_path }}" alt="{{ $home->name }}" loading="lazy" onerror="this.remove()">
                                        @endif
                                        <div class="ms-2">{{ $home->name }}</div>
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <span>{{ $home->user->full_name }}</span>
                                        <x-admin.user-contact-button :home="$home" />
                                    </div>
                                </td>
                                <td class="text-nowrap" style="min-width: 130px;">
                                    <select
                                        name="sources[{{ $home->id }}][platform]"
                                        class="form-select form-select-sm"
                                        @cannot('updateCalendarSync', \App\Models\Home::class) disabled @endcannot
                                    >
                                        <option value="">@lang('title.auto_detect')</option>
                                        @foreach(\App\Support\ExternalCalendarPlatform::labels() as $value => $label)
                                            <option value="{{ $value }}" @selected(($source?->platform ?? '') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input
                                        type="url"
                                        name="sources[{{ $home->id }}][external_url]"
                                        class="form-control form-control-sm"
                                        value="{{ old('sources.'.$home->id.'.external_url', $source?->external_url) }}"
                                        placeholder="https://www.jabama.com/stay/... یا jajiga.com/room/..."
                                        dir="ltr"
                                        @cannot('updateCalendarSync', \App\Models\Home::class) disabled @endcannot
                                    >
                                    @if($source?->external_room_id)
                                        <small class="text-muted d-block mt-1">شناسه خارجی: {{ $source->external_room_id }}</small>
                                    @endif
                                    @if(! $hasLink)
                                        <small class="text-warning d-block mt-1">بدون لینک</small>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <div class="form-check form-switch">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            role="switch"
                                            name="sources[{{ $home->id }}][sync_enabled]"
                                            value="1"
                                            @checked(old('sources.'.$home->id.'.sync_enabled', $source?->sync_enabled))
                                            @cannot('updateCalendarSync', \App\Models\Home::class) disabled @endcannot
                                        >
                                    </div>
                                </td>
                                <td class="text-nowrap" style="min-width: 170px;">
                                    @if($source?->last_synced_at)
                                        <div class="fw-semi-bold">{{ jdate($source->last_synced_at)->format('Y/m/d H:i') }}</div>
                                        <small class="text-muted d-block">{{ $source->last_synced_at->diffForHumans() }}</small>
                                        @if($source->syncStatusLabel())
                                            <span class="badge rounded-pill badge-soft-{{ $source->syncStatusColor() }} mt-1">
                                                {{ $source->syncStatusLabel() }}
                                            </span>
                                        @endif
                                        @if($source->last_sync_message)
                                            <small class="d-block text-muted mt-1" style="font-size: 11px;">{{ $source->last_sync_message }}</small>
                                        @endif
                                    @elseif($hasLink)
                                        <span class="badge rounded-pill badge-soft-warning">هنوز همگام نشده</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-inline-flex flex-column align-items-end gap-1">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            @can('showDate', $home)
                                                <a href="{{ route('admin.homes.date.show', $home) }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="@lang('title.calendar')">
                                                    <span class="fas fa-calendar-alt"></span>
                                                </a>
                                            @endcan
                                            @can('syncCalendar', $home)
                                                @if($hasLink)
                                                    <button
                                                        type="submit"
                                                        form="calendar-sync-now-{{ $home->id }}"
                                                        class="btn btn-sm btn-primary calendar-sync-action-btn"
                                                        @disabled($syncCooldownSeconds > 0)
                                                    >
                                                        <span class="fas fa-sync-alt me-1"></span>
                                                        @lang('title.manual_sync')
                                                    </button>
                                                @else
                                                    <span class="text-muted small">لینک ثبت نشده</span>
                                                @endif
                                            @endcan
                                        </div>
                                        @can('syncCalendar', $home)
                                            @if($hasLink)
                                                <small class="calendar-sync-countdown text-muted {{ $syncCooldownSeconds > 0 ? '' : 'd-none' }}"
                                                       style="font-size: 11px;">
                                                    قابل به‌روزرسانی تا <span class="calendar-sync-countdown-value">{{ (int) $syncCooldownSeconds }}</span> ثانیه دیگر
                                                </small>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @can('updateCalendarSync', \App\Models\Home::class)
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <span class="fas fa-save me-1"></span>
                            @lang('title.save_changes')
                        </button>
                    </div>
                @endcan

                <div class="d-flex justify-content-center mt-3">
                    {{ $homes->links() }}
                </div>
            </form>

            @foreach($homes as $home)
                @can('syncCalendar', $home)
                    @if($home->calendarSource?->external_url)
                        <form method="POST" action="{{ route('admin.homes.calendar-sync.sync', $home) }}" id="calendar-sync-now-{{ $home->id }}" class="d-none">
                            @csrf
                        </form>
                    @endif
                @endcan
            @endforeach
        @endif
    </x-admin.card>
@endsection

@push('bottom-assets')
<script>
(function () {
    var remaining = parseInt(@json((int) $syncCooldownSeconds), 10) || 0;
    var banner = document.getElementById('calendar-sync-cooldown-banner');
    var bannerText = document.getElementById('calendar-sync-cooldown-banner-text');
    var buttons = document.querySelectorAll('.calendar-sync-action-btn');
    var countdowns = document.querySelectorAll('.calendar-sync-countdown');
    var timerId = null;

    function toPersianDigits(value) {
        return String(value).replace(/\d/g, function (digit) {
            return '۰۱۲۳۴۵۶۷۸۹'[digit];
        });
    }

    function formatSeconds(total) {
        var minutes = Math.floor(total / 60);
        var seconds = total % 60;
        var mm = String(minutes).padStart(2, '0');
        var ss = String(seconds).padStart(2, '0');
        return toPersianDigits(mm + ':' + ss);
    }

    function render() {
        var disabled = remaining > 0;

        buttons.forEach(function (btn) {
            btn.disabled = disabled;
        });

        countdowns.forEach(function (el) {
            var valueEl = el.querySelector('.calendar-sync-countdown-value');
            if (disabled) {
                el.classList.remove('d-none');
                if (valueEl) {
                    valueEl.textContent = toPersianDigits(remaining);
                }
            } else {
                el.classList.add('d-none');
            }
        });

        if (banner && bannerText) {
            if (disabled) {
                banner.classList.remove('d-none');
                bannerText.textContent = formatSeconds(remaining);
            } else {
                banner.classList.add('d-none');
            }
        }
    }

    function tick() {
        if (remaining <= 0) {
            remaining = 0;
            render();
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
            }
            return;
        }

        remaining -= 1;
        render();
    }

    render();

    if (remaining > 0) {
        timerId = setInterval(tick, 1000);
    }
})();
</script>
@endpush
