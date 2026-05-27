@extends('layouts.admin.admin', ['title' => __('title.landing_pages'), 'active' => 'landing-pages'])

@section('content')
    <x-admin.search-card route="{{ route('admin.landing-pages.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="search">@lang('title.title') / @lang('title.slug')</label>
            <input type="text" class="form-control" name="search" id="search" value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="is_active">@lang('title.status')</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="">@lang('title.all')</option>
                <option value="1" @if(request('is_active') === '1') selected @endif>@lang('title.active')</option>
                <option value="0" @if(request('is_active') === '0') selected @endif>@lang('title.inactive')</option>
            </select>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.landing_pages') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\LandingPage::class) }}"
        buttonLink="{{ route('admin.landing-pages.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($landingPages->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.title')</th>
                            <th scope="col">@lang('title.slug')</th>
                            <th scope="col">@lang('title.city')</th>
                            <th scope="col">@lang('title.landing_filters')</th>
                            <th scope="col">@lang('title.status')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($landingPages as $page)
                        <tr class="align-middle">
                            <td>{{ $page->id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($page->title, 40) }}</td>
                            <td dir="ltr"><a href="{{ $page->url }}" target="_blank" rel="noopener">{{ $page->slug }}</a></td>
                            <td>{{ $page->city->name ?? '—' }}</td>
                            <td class="small">
                                @if($page->hasAdvancedFilters())
                                    @php($labels = $page->filterSummaryLabels())
                                    {{ $labels !== [] ? implode('، ', array_slice($labels, 0, 3)) : '✓' }}
                                    @if(count($labels) > 3)
                                        …
                                    @endif
                                @else
                                    <span class="text-muted">@lang('title.landing_filters_basic')</span>
                                @endif
                            </td>
                            <td>
                                @if($page->is_active)
                                    <span class="badge bg-success">@lang('title.active')</span>
                                @else
                                    <span class="badge bg-secondary">@lang('title.inactive')</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @can('update', $page)
                                    <a href="{{ route('admin.landing-pages.edit', $page) }}" class="btn p-0" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $page)
                                    <delete-modal
                                        route="{{ route('admin.landing-pages.destroy', $page) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete_landing_page')"
                                        text="@lang('text.delete_landing_page')"
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
                    {{ $landingPages->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
