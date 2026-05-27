@extends('layouts.admin.admin', ['title' => __('title.edit variable'), 'active' => 'homes-variables'])

@section('content')
    <x-admin.card title="{{ __('title.edit variable') }}">
        <form action="{{ route('admin.homes.variables.update', $variable->id) }}" method="POST" class="p-3 row">
            @csrf
            @method('PUT')

            @php
            $values = collect([
                'title' => old('title', $variable->title),
                'placeholder' => old('placeholder', $variable->place_holder),
                'type' => old('type', $variable->type),
                'input_type' => old('input_type', $variable->input_type),
                'options' => old('options', $variable->options)
            ])->toJson()
            @endphp

            <variable-form
                title_text="@lang('title.title')"
                placeholder_text="@lang('title.place_holder')"
                type_text="@lang('title.type')"
                input_type_text="@lang('title.input_type')"
                select_text="@lang('title.select')"
                :types="{{ json_encode(\App\Models\Variable::TYPES) }}"
                :input_types="{{ json_encode(\App\Models\Variable::INPUT_TYPES) }}"
                options_text="@lang('title.variables')"
                :old="{{ $values }}"
            ></variable-form>


            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success" style="margin-left: 20px;">@lang('title.edit')</button>
                <a href="{{ route('admin.homes.variables.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
