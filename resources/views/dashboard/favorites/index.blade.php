@extends('layouts.dashboard.dashboard', ['title' => __('title.favorites'), 'active' => 'favorites', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.favorites')]
]])

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if($favorites->isNotEmpty())
                <div class="row">
                    @foreach($favorites as $favorite)
                        @if($favorite->favoritable_type === \App\Models\Home::class)
                        <div class="col-12 col-md-4 mt-4 mt-md-2">
                            <div class="listing-img-wrapper">
                                <div><img src="{{ $favorite->favoritable->cover_path }}" class="img-fluid mx-auto w-100" alt="" /></div>

                                <div style="position: absolute; top: 15px; left: 15px">
                                    <delete-modal
                                        route="{{ route('dashboard.favorites.destroy', $favorite->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete item')"
                                        text="@lang('text.delete item')"
                                        button_hover_text="@lang('title.delete')"
                                        button_cancel_text="@lang('title.cancel')"
                                        button_submit_text="@lang('title.delete')"
                                        btn_class="w-100 fas fa-trash text-danger fa-2x"
                                    ></delete-modal>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <h6 class="listing-card-info-price mb-0 p-0">{{ $favorite->favoritable->price(true) }} @lang('title.toman')</h6>
                                </div>
                                <div class="col-12 col-md-6 footer-flex mt-3 mt-md-0">
                                    <a target="_blank" href="{{ $favorite->favoritable->link }}" class="w-100 prt-view">@lang('title.show_detail')</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{ $favorites->links() }}
            @else
                <div class="alert alert-warning">
                    @lang('title.nothing found')
                </div>
            @endif
        </div>
    </div>
@endsection
