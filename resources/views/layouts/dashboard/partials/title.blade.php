<!-- ============================ Page Title Start================================== -->
<div class="page-title" style="background:#f4f4f4 url({{ asset('assets/img/slider-5.jpg') }});" data-overlay="5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">

                <div class="breadcrumbs-wrap">
                    <ol class="breadcrumb">
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item @if($loop->last) active @endif" aria-current="page">
                                @if(isset($breadcrumb['url']) && !$loop->last)
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                @else
                                    <span class="text-white">{{ $breadcrumb['title'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                    <h2 class="breadcrumb-title">{{ $title }}</h2>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- ============================ Page Title End ================================== -->
