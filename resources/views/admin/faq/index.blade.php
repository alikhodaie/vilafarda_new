@extends('layouts.admin.admin', ['title' => __('title.faq'), 'active' => 'faq'])

@section('content')
    <x-admin.search-card route="{{ route('admin.faq.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="question">@lang('title.question')</label>
            <input type="text" class="form-control" name="question" id="question" value="{{ request('question') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="category">@lang('title.category')</label>
            <select name="category" id="category" class="form-control">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Category::query()->FAQ()->get() as $category)
                    <option value="{{ $category->id }}" @if($category->id == request('category')) selected @endif>{{ $category->title }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.faq') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\FAQ::class) }}"
        buttonLink="{{ route('admin.faq.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($faq->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.question')</th>
                            <th scope="col">@lang('title.category')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($faq as $item)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $item->id }}</td>
                            <td class="text-nowrap">{{ \Illuminate\Support\Str::limit($item->question, 50) }}</td>
                            <td class="text-nowrap">{{ $item->category->title }}</td>
                            <td class="text-end">
                                @can('update', $item)
                                    <a href="{{ route('admin.faq.edit', $item->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $item)
                                    <delete-modal
                                        route="{{ route('admin.faq.destroy', $item->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete faq')"
                                        text="@lang('text.delete faq')"
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
                    {{ $faq->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>
@endsection
