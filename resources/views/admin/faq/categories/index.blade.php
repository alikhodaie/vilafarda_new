@extends('layouts.admin.admin', ['title' => __('title.faq').' | '.__('title.categories'), 'active' => 'faq-categories'])

@section('content')
    <x-admin.search-card route="{{ route('admin.faq.categories.index') }}">
        <div class="col-12 col-md-6 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-6 mt-2">
            <label for="title">@lang('title.title')</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ request('title') }}">
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.categories') }}"
        canSeeButton="{{ auth()->user()->can('createFAQ', \App\Models\Category::class) }}"
        buttonLink="{{ route('admin.faq.categories.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($categories->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.title')</th>
                            <th scope="col">@lang('title.faq count')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $category->id }}</td>
                            <td class="text-nowrap">{{ $category->title }}</td>
                            <td class="text-nowrap">{{ $category->faq_count }}</td>
                            <td class="text-end">
                                @can('updateFAQ', $category)
                                    <a href="{{ route('admin.faq.categories.edit', $category->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('deleteFAQ', $category)
                                    <delete-modal
                                        route="{{ route('admin.faq.categories.destroy', $category->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete category')"
                                        text="@lang('text.delete category')"
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
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>
@endsection
