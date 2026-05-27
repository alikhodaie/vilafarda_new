document.addEventListener('DOMContentLoaded', function () {
    const defaultAvatar = window.COMMENT_DEFAULT_AVATAR || '/assets/images/avatar.jpg';
    const placeholderCover = window.COMMENT_PLACEHOLDER_COVER || '/assets/images/placeholder.jpg';

    fetch('/api/comment')
        .then(response => response.json())
        .then(data => {
            const comments = data.data || data;
            const container = document.getElementById('comments-list');

            if (!comments.length) {
                window.IndexSectionVisibility?.hide(container);
                return;
            }
            container.innerHTML = '';

            comments.forEach(comment => {
                const stars = Array.from({ length: 5 }, (_, i) => {
                    return i < comment.score
                        ? '<i class="bi bi-star-fill text-warning"></i>'
                        : '<i class="bi bi-star text-warning"></i>';
                }).join('');

                const avatarUrl = comment.user?.avatar_path || defaultAvatar;
                const coverUrl = comment.commentable?.cover_path || placeholderCover;
                const userName = comment.full_name || 'کاربر نامشخص';
                const commentText = comment.comment || '';

                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.innerHTML = `
                    <div class="card mb-4 shadow-sm text-white" style="border:none; position:relative; overflow:hidden; border-radius:1rem; height:200px;">
                        <div style="
                            background-image: url('${coverUrl}');
                            background-size: cover;
                            background-position: center;
                            width:100%;
                            height:100%;
                            position:absolute;
                            top:0;
                            left:0;
                        "></div>

                        <div style="
                            position:absolute;
                            top:0;
                            left:0;
                            width:100%;
                            height:100%;
                            background-color: rgb(0 0 0 / 54%);
                        "></div>

                        <div style="position:relative; z-index:2; padding:16px; height:100%; display:flex; flex-direction:column; justify-content:flex-end;">
                            <p style="color:#fff;">${commentText}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-row align-items-center">
                                    <img class="rounded" src="${avatarUrl}" alt="${userName}" width="25" height="25"
                                         onerror="this.onerror=null;this.src='${defaultAvatar}'" />
                                    <p class="small mb-0 ms-2">${userName}</p>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    ${stars}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.appendChild(slide);
            });

            new Swiper('.comments-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                freeMode: true,
                pagination: {
                    el: '.swiper-pagination',
                },
            });
        })
        .catch(() => {
            const container = document.getElementById('comments-list');
            window.IndexSectionVisibility?.hide(container);
        });
});
