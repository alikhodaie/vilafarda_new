@extends('layouts.admin.admin', ['title' => __('title.sms_logs'), 'active' => 'sms-logs'])

@section('content')
    <x-admin.card title="{{ __('title.sms_logs') }}">
        <p class="text-muted mb-4">{{ __('text.sms_logs_intro') }}</p>

        <form method="GET" action="{{ route('admin.sms-logs.index') }}" class="row g-3 mb-4">
            <div class="col-12 col-md-3">
                <label class="form-label" for="recipient">@lang('title.name')</label>
                <input type="text" class="form-control" id="recipient" name="recipient" value="{{ request('recipient') }}" placeholder="@lang('title.name')">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label" for="mobile">@lang('title.mobile')</label>
                <input type="text" class="form-control" id="mobile" name="mobile" value="{{ request('mobile') }}" placeholder="09..." dir="ltr">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label" for="status">@lang('title.status')</label>
                <select class="form-select" id="status" name="status">
                    <option value="">@lang('title.all')</option>
                    <option value="sent" @selected(request('status') === 'sent')>@lang('title.sms_log_status_sent')</option>
                    <option value="failed" @selected(request('status') === 'failed')>@lang('title.sms_log_status_failed')</option>
                    <option value="skipped" @selected(request('status') === 'skipped')>@lang('title.sms_log_status_skipped')</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label" for="pattern_id">@lang('title.sms_pattern_id')</label>
                <input type="text" class="form-control" id="pattern_id" name="pattern_id" value="{{ request('pattern_id') }}" dir="ltr">
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-falcon-primary w-100">@lang('title.search')</button>
                <a href="{{ route('admin.sms-logs.index') }}" class="btn btn-falcon-default">@lang('title.reset')</a>
            </div>
        </form>

        <div class="table-responsive scrollbar">
            @if($smsLogs->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.recipient')</th>
                            <th scope="col">@lang('title.mobile')</th>
                            <th scope="col">@lang('title.sms_pattern')</th>
                            <th scope="col">@lang('title.status')</th>
                            <th scope="col">@lang('title.error')</th>
                            <th scope="col">@lang('title.send_date')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($smsLogs as $smsLog)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $smsLog->id }}</td>
                            <td class="text-nowrap">
                                @if($smsLog->user)
                                    <a href="{{ route('admin.users.edit', $smsLog->user_id) }}">{{ $smsLog->user->full_name }}</a>
                                @else
                                    {{ $smsLog->recipient_name ?: '—' }}
                                @endif
                            </td>
                            <td class="text-nowrap" dir="ltr"><a href="tel:{{ $smsLog->mobile }}">{{ $smsLog->mobile }}</a></td>
                            <td class="text-nowrap">
                                <span dir="ltr">{{ $smsLog->pattern_id }}</span>
                                @if($smsLog->pattern_title)
                                    <br><small class="text-muted">{{ $smsLog->pattern_title }}</small>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <span class="badge rounded-pill {{ $smsLog->statusBadgeClass() }}">{{ $smsLog->statusLabel() }}</span>
                            </td>
                            <td class="text-danger small">{{ $smsLog->error_message ?: '—' }}</td>
                            <td class="text-nowrap">{{ $smsLog->persianCreatedAt('%d %B %Y — %H:%M') }}</td>
                            <td class="text-end">
                                @can('show', $smsLog)
                                    <a href="{{ route('admin.sms-logs.show', $smsLog) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.show')">
                                        <span class="text-500 fas fa-eye"></span>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $smsLogs->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
