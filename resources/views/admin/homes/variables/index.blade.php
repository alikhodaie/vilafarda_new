@extends('layouts.admin.admin', ['title' => __('title.homes').' | '.__('title.variables'), 'active' => 'homes-variables'])

@section('content')
    <x-admin.search-card route="{{ route('admin.homes.variables.index') }}">
        <div class="col-12 col-md-3 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="title">@lang('title.title')</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ request('title') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="input_type">@lang('title.input_type')</label>
            <select class="form-control" name="input_type" id="input_type">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Variable::INPUT_TYPES as $input_type)
                    <option value="{{ $input_type['value'] }}" @if ($input_type['value'] === request('input_type')) selected @endif>{{ $input_type['fa_text'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="type">@lang('title.type')</label>
            <select class="form-control" name="type" id="type">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Variable::TYPES as $type)
                    <option value="{{ $type['value'] }}" @if ($type['value'] === request('type')) selected @endif>{{ $type['fa_text'] }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.variables') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Variable::class) }}"
        buttonLink="{{ route('admin.homes.variables.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($variables->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.title')</th>
                            <th scope="col">@lang('title.place_holder')</th>
                            <th scope="col">@lang('title.input_type')</th>
                            <th scope="col">@lang('title.type')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($variables as $variable)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $variable->id }}</td>
                            <td class="text-nowrap">{{ $variable->title }}</td>
                            <td class="text-nowrap">{{ $variable->place_holder }}</td>
                            <td class="text-nowrap">{{ $variable->inputType() }}</td>
                            <td class="text-nowrap">{{ $variable->type() }}</td>
                            <td class="text-end">
                                @can('update', $variable)
                                    <a href="{{ route('admin.homes.variables.edit', $variable->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $variable)
                                    <delete-modal
                                        route="{{ route('admin.homes.variables.destroy', $variable->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete variable')"
                                        text="@lang('text.delete variable')"
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
                    {{ $variables->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>
@endsection
