<div class="card">
    <div class="card-header border-bottom">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">
                    {{ $title }}
                </h5>
            </div>
            @if($canSeeButton ?? false)
                <div class="col-auto">
                    <a href="{{ $buttonLink }}" class="btn btn-falcon-default">{{ $buttonText }}</a>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body pt-0">
        {{ $slot }}
    </div>
</div>
