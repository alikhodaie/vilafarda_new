@extends('layouts.admin.admin', ['title' => __('title.navbar'), 'active' => 'navbar'])

@section('content')
    <x-admin.search-card route="{{ route('admin.navbar.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="title">@lang('title.title')</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ request('title') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="parent">@lang('title.parent item')</label>
            <select name="parent" id="parent" class="form-control">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Navbar::all() as $item)
                    <option value="{{ $item->id }}" @if($item->id == request('parent')) selected @endif>{{ $item->title }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.navbar') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Navbar::class) }}"
        buttonLink="{{ route('admin.navbar.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($items->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                    <tr>
                        <th scope="col">@lang('title.id')</th>
                        <th scope="col">@lang('title.title')</th>
                        <th scope="col">@lang('title.link')</th>
                        <th scope="col">@lang('title.parent item')</th>
                        <th class="text-end" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $item->id }}</td>
                            <td class="text-nowrap">{{ $item->title }}</td>
                            <td class="text-nowrap"><a href="{{ $item->link }}">{{ $item->link }}</a></td>
                            <td class="text-nowrap">
                                @if($item->parent)
                                    {{ $item->parent->title }}({{ $item->parent->id }})
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end">
                                @can('update', $item)
                                    <a href="{{ route('admin.navbar.edit', $item->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $item)
                                    <delete-modal
                                        route="{{ route('admin.navbar.destroy', $item->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete navbar')"
                                        text="@lang('text.delete navbar')"
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
            @endif
        </div>

    </x-admin.card>
@endsection
