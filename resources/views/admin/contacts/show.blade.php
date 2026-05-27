@extends('layouts.admin.admin', ['title' => __('title.show contact'), 'active' => 'contacts'])

@section('content')
    <x-admin.card title="{{ __('title.show contact') }}">
        <div class="row">
            <div class="mt-2 col-6">
                <label for="name" class="form-label">@lang('title.name')</label>
                <div id="name" type="text" class="form-control">{{ $contact->name }}</div>
            </div>
            <div class="mt-2 col-6">
                <label for="mobile" class="form-label">@lang('title.mobile')</label>
                <a id="mobile" class="form-control" style="direction: ltr" href="tel:{{ $contact->mobile }}">{{ $contact->mobile }}</a>
            </div>
            <div class="mt-2 col-12">
                <label for="subject" class="form-label">@lang('title.subject')</label>
                <div id="subject" type="text" class="form-control">{{ $contact->subject }}</div>
            </div>
            <div class="mt-2 col-12">
                <label for="message" class="form-label">@lang('title.message')</label>
                <div id="message" class="form-control">{!! $contact->message !!}</div>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </div>
    </x-admin.card>
@endsection
