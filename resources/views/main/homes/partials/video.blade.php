@if($home->video)
<!-- Single Block Wrap -->
<div class="_prtis_list mb-4">

    <div class="_prtis_list_header min">
        <h4 class="m-0">ویدیو</h4>
    </div>

    <div class="_prtis_list_body">
        <div class="property_video">
            <div class="thumb">
                <img class="pro_img img-fluid w100" src="{{ asset('assets/img/p-3.png') }}" alt="{{ $home->title }}">
                <div class="overlay_icon">
                    <div class="bb-video-box">
                        <div class="bb-video-box-inner">
                            <div class="bb-video-box-innerup">
                                <a href="{{ $home->video_path }}" data-toggle="modal" data-target="#popup-video" class="theme-cl"><i class="ti-control-play"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Video Modal -->
<div class="modal fade" id="popup-video" tabindex="-1" role="dialog" aria-labelledby="popup-video" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="full-width">
            <video preload="none" class="w-100 h-100" src="{{ $home->video_path }}" controls></video>
        </div>
    </div>
</div>
<!-- End Video Modal -->
@endif
