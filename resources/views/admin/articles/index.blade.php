@extends('layouts.admin.admin', ['title' => __('title.blog').' | '.__('title.articles'), 'active' => 'articles'])

@section('content')
    <x-admin.search-card route="{{ route('admin.articles.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="search">@lang('title.title')</label>
            <input type="text" class="form-control" name="search" id="search" value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="category">@lang('title.category')</label>
            <select name="category" id="category" class="form-control">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Category::query()->article()->get() as $category)
                    <option value="{{ $category->id }}" @if($category->id == request('category')) selected @endif>{{ $category->title }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.articles') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Article::class) }}"
        buttonLink="{{ route('admin.articles.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($articles->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.image')</th>
                            <th scope="col">@lang('title.title')</th>
                            <th scope="col">@lang('title.category')</th>
                            <th scope="col">@lang('title.count comments')</th>
                            <th scope="col">@lang('title.created at')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($articles as $article)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $article->id }}</td>
                            <td class="text-nowrap"><img width="75" src="{{ $article->image_path }}" alt="{{ $article->title }}"></td>
                            <td class="text-nowrap">{{ $article->title }}</td>
                            <td class="text-nowrap">{{ $article->categories()->first()->title }}</td>
                            <td class="text-nowrap">{{ $article->count_comments }}</td>
                            <td class="text-nowrap">{{ $article->persianCreatedAt() }}</td>
                            <td class="text-end">
                                @can('update', $article)
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $article)
                                    <delete-modal
                                        route="{{ route('admin.articles.destroy', $article->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete article')"
                                        text="@lang('text.delete article')"
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
                    {{ $articles->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>
@endsection
