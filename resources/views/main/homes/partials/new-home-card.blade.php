<div class="d-flex flex-wrap m-0 w-100">
    <div class="position-relative" style="border-radius: 20px; overflow: hidden; width: 100%;">
        <!-- تصویر اصلی ویلا -->
        <a href="{{ $home->link }}" class="d-block position-relative w-100">
            <img id="mainImage-{{ $home->id }}" 
                 src="https://galleries.crovillas.com/img/properties/cebf45e2-a463-4b5c-a32b-72c5ce609447/fc742ed7-e820-48c4-8ed8-dee4c358ddca/30fd1a43-fd7d-4140-a007-b5bf6b07a581/villa-just-be-happy.jpg"
                 class="img-fluid w-100" style="border-radius: 20px;" alt="{{ $home->name }}">

            <!-- Overlay دیتا -->
            <div class="position-absolute bottom-0 start-0 w-100 p-3"
                 style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <h5 class="text-white mb-1" style="font-weight: 600; font-size: 1.1rem;">{{ $home->name }}</h5>
                <p class="text-light small mb-1" style="font-size: 0.85rem;">
                    {{ $home->typeLabel() }} •
                    {{ __('title.sleep_room', ['count' => number_format($home->sleep_places_count)]) }} •
                    {{ $home->province->name }} •
                    {{ $home->city->name }} •
                    {{ number_format($home->total_guest) }} نفر
                </p>
                <h6 class="text-white mb-0" style="font-weight: 600; font-size: 0.95rem;">
                    {{ $home->price($is_today ?? false) }} @lang('title.toman') هر شب
                </h6>
            </div>

            <!-- رزرو سریع -->
            @if($home->has_fast_reserve)
                <div class="position-absolute bottom-2 start-2">
                    <span class="badge bg-warning text-dark">رزرو سریع</span>
                </div>
            @endif
        </a>
    </div>
</div>
