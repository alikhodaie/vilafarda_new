<div class="card mb-3 accordion" id="search_card">
    <div class="card-header border-bottom">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">
                    @lang('title.search')
                </h5>
            </div>
            <div class="col-auto">
                <button class="accordion-button @if(!hasFilter()) collapsed @endif btn btn-falcon-warning" @if(hasFilter()) aria-expanded="true" @else aria-expanded="false" @endif type="button" data-bs-toggle="collapse" data-bs-target="#search_body" aria-controls="search_body"></button>
            </div>
        </div>
    </div>
    <div class="card-body pt-0 accordion-collapse collapse @if(hasFilter()) show @endif" id="search_body" data-bs-parent="#search_card">
        <form action="{{ $route }}" class="p-3 row">
            {{ $slot }}

            <div class="col-12 mt-3 d-flex justify-content-center">
                <button class="btn btn-falcon-default rounded-pill me-1 mb-1" type="submit">
                    @lang('title.search')
                </button>
                @if(hasFilter())
                    <a href="{{ $route }}" class="btn btn-falcon-danger rounded-pill me-1 mb-1">
                        @lang('title.remove filter')
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
