@extends('layouts.admin.admin', ['title' => __('title.contact'), 'active' => 'contacts'])

@section('content')
    <x-admin.card
        title="{{ __('title.contact') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($contacts->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.name')</th>
                            <th scope="col">@lang('title.mobile')</th>
                            <th scope="col">@lang('title.subject')</th>
                            <th scope="col">@lang('title.is_seen')</th>
                            <th scope="col">@lang('title.send_date')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($contacts as $contact)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $contact->id }}</td>
                            <td class="text-nowrap">{{ $contact->name }}</td>
                            <td class="text-nowrap"><a href="tel:{{ $contact->mobile }}">{{ $contact->mobile }}</a></td>
                            <td class="text-nowrap">{{ $contact->subject }}</td>
                            <td class="text-nowrap">
                                @if($contact->is_seen)
                                    <span class="badge rounded-pill bg-success"><i class="fa fa-check"></i></span>
                                @else
                                    <span class="badge rounded-pill bg-danger"><i class="fa fa-times"></i></span>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $contact->persianCreatedAt('%d %B %Y') }}</td>
                            <td class="text-end">
                                @can('index', \App\Models\Contact::class)
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.show')">
                                        <span class="text-500 fas fa-eye"></span>
                                    </a>
                                @endcan
                                @can('delete', $contact)
                                    <delete-modal
                                        route="{{ route('admin.contacts.destroy', $contact->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete contact')"
                                        text="@lang('text.delete contact')"
                                        button_hover_text="@lang('title.delete')"
                                        button_cancel_text="@lang('title.cancel')"
                                        button_submit_text="@lang('title.delete')"
                                    ></delete-modal>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
