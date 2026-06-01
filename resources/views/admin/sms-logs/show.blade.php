@extends('layouts.admin.admin', ['title' => __('title.sms_log_detail'), 'active' => 'sms-logs'])

@section('content')
    <x-admin.card title="{{ __('title.sms_log_detail') }}">
        <div class="mb-3">
            <a href="{{ route('admin.sms-logs.index') }}" class="btn btn-falcon-default btn-sm">
                <i class="fas fa-arrow-right ms-1"></i>
                @lang('title.back')
            </a>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th scope="row">@lang('title.id')</th>
                            <td>{{ $smsLog->id }}</td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('title.recipient')</th>
                            <td>
                                @if($smsLog->user)
                                    <a href="{{ route('admin.users.edit', $smsLog->user_id) }}">{{ $smsLog->user->full_name }}</a>
                                @else
                                    {{ $smsLog->recipient_name ?: '—' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('title.mobile')</th>
                            <td dir="ltr"><a href="tel:{{ $smsLog->mobile }}">{{ $smsLog->mobile }}</a></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('title.sms_pattern')</th>
                            <td>
                                <span dir="ltr">{{ $smsLog->pattern_id }}</span>
                                @if($smsLog->pattern_title)
                                    — {{ $smsLog->pattern_title }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('title.status')</th>
                            <td><span class="badge rounded-pill {{ $smsLog->statusBadgeClass() }}">{{ $smsLog->statusLabel() }}</span></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('title.send_date')</th>
                            <td>{{ $smsLog->persianCreatedAt('%d %B %Y — %H:%M:%S') }}</td>
                        </tr>
                        @if($smsLog->source)
                            <tr>
                                <th scope="row">@lang('title.sms_source')</th>
                                <td dir="ltr"><code>{{ $smsLog->source }}</code></td>
                            </tr>
                        @endif
                        @if($smsLog->error_message)
                            <tr>
                                <th scope="row">@lang('title.error')</th>
                                <td class="text-danger">{{ $smsLog->error_message }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="col-12 col-md-6">
                <h5 class="mb-3">@lang('title.sms_parameters')</h5>
                @if(!empty($smsLog->parameters))
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('title.title')</th>
                                <th>@lang('title.value')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($smsLog->parameters as $parameter)
                                <tr>
                                    <td dir="ltr">{{ $parameter['name'] ?? '—' }}</td>
                                    <td>{{ $parameter['value'] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">—</p>
                @endif

                @if($smsLog->response_body)
                    <h5 class="mb-3 mt-4">@lang('title.sms_api_response')</h5>
                    <pre class="bg-light p-3 rounded small mb-0" dir="ltr" style="white-space: pre-wrap;">{{ $smsLog->response_body }}</pre>
                @endif
            </div>
        </div>
    </x-admin.card>
@endsection
