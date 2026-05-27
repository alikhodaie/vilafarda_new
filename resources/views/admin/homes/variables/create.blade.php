@extends('layouts.admin.admin', ['title' => __('title.create variable'), 'active' => 'homes-variables'])

@section('content')
    <x-admin.card title="{{ __('title.create variable') }}">
        <form action="{{ route('admin.homes.variables.store') }}" method="POST" class="p-3 row">
            @csrf

            <variable-form
                title_text="@lang('title.title')"
                placeholder_text="@lang('title.place_holder')"
                type_text="@lang('title.type')"
                input_type_text="@lang('title.input_type')"
                select_text="@lang('title.select')"
                :types="{{ json_encode(\App\Models\Variable::TYPES) }}"
                :input_types="{{ json_encode(\App\Models\Variable::INPUT_TYPES) }}"
                options_text="@lang('title.variables')"
                @if(old())
                    :old="{{ json_encode(old()) }}"
                @endif
            ></variable-form>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success" style="margin-left: 20px">@lang('title.submit')</button>
                <a href="{{ route('admin.homes.variables.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
