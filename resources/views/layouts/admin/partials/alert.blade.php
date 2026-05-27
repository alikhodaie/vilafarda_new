@if(session()->exists('success'))
    <div class="alert alert-success border-2 d-flex align-items-center" role="alert">
        <div class="bg-success me-2 icon-item"><span class="fas fa-check-circle text-white"></span></div>
        <p class="mb-0 flex-1">{{ session()->get('success') }}</p>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session()->exists('info'))
    <div class="alert alert-info border-2 d-flex align-items-center" role="alert">
        <div class="bg-info me-2 icon-item"><span class="fas fa-info-circle text-white"></span></div>
        <p class="mb-0 flex-1">{{ session()->get('info') }}</p>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session()->exists('warning'))
    <div class="alert alert-warning border-2 d-flex align-items-center" role="alert">
        <div class="bg-warning me-2 icon-item"><span class="fas fa-exclamation-circle text-white"></span></div>
        <p class="mb-0 flex-1">{!! nl2br(e(session()->get('warning'))) !!}</p>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session()->exists('danger'))
    <div class="alert alert-danger border-2 d-flex align-items-center" role="alert">
        <div class="bg-danger me-2 icon-item"><span class="fas fa-times-circle text-white"></span></div>
        <p class="mb-0 flex-1">{{ session()->get('danger') }}</p>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-2 d-flex align-items-center" role="alert">
        <div class="bg-danger me-2 icon-item"><span class="fas fa-times-circle text-white"></span></div>
        <p class="mb-0 flex-1">
            @foreach($errors->all() as $error)
                {{ $error }} <br>
            @endforeach
        </p>
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
