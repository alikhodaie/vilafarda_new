<section class="index-section">
    <div class="index-section__header">
        <span class="index-section__title">آخرین نظرات</span>
    </div>
    <div class="swiper comments-swiper">
        <div class="swiper-wrapper" id="comments-list">
            
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>
<script>
    window.COMMENT_DEFAULT_AVATAR = @json(\App\Models\User::getDefaultAvatar());
    window.COMMENT_PLACEHOLDER_COVER = @json(asset('assets/images/placeholder.jpg'));
</script>
<script src="{{ asset('assets/js/index-section-visibility.js') }}"></script>
<script src="{{ asset('assets/js/comment.js') }}"></script>