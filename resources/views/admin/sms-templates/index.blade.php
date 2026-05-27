@extends('layouts.admin.admin', ['title' => __('title.sms_templates'), 'active' => 'sms-templates'])

@section('content')
    <x-admin.card title="{{ __('title.sms_templates') }}">
        <p class="text-muted mb-4">{{ __('text.sms_templates_intro') }}</p>

        @if($templates->isEmpty())
            <x-admin.empty-message></x-admin.empty-message>
        @else
            @foreach($grouped as $category => $items)
                <h5 class="mb-3">{{ \App\Support\SmsTemplates::categoryLabel($category) }}</h5>
                <div class="table-responsive scrollbar mb-4">
                    <table class="table table-hover table-striped overflow-hidden">
                        <thead>
                        <tr>
                            <th scope="col">@lang('title.sms_pattern_id')</th>
                            <th scope="col">@lang('title.title')</th>
                            <th scope="col">@lang('title.sms_recipient')</th>
                            <th scope="col">@lang('title.sms_trigger')</th>
                            <th scope="col">@lang('title.sms_parameters')</th>
                            <th scope="col">@lang('title.sms_source')</th>
                            <th scope="col">@lang('title.sms_platforms')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $template)
                            <tr class="align-middle">
                                <td class="text-nowrap"><code>{{ $template['pattern_id'] }}</code></td>
                                <td>{{ $template['title'] }}</td>
                                <td class="text-nowrap">{{ $template['recipient'] }}</td>
                                <td>{{ $template['trigger'] }}</td>
                                <td>
                                    @foreach($template['parameters'] as $param)
                                        <span class="badge bg-soft-secondary text-dark me-1">{{ $param }}</span>
                                    @endforeach
                                </td>
                                <td class="text-nowrap"><small class="text-muted">{{ $template['source'] }}</small></td>
                                <td class="text-nowrap">
                                    <span class="badge bg-soft-success">@lang('title.desktop')</span>
                                    <span class="badge bg-soft-success">@lang('title.mobile_web')</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </x-admin.card>
@endsection
