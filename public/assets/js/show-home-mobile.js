document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.swiper', {
        loop: true,
        // autoplay: {
        //     delay: 3000,
        //     disableOnInteraction: false,
        // },
        pagination: {
            el: '.slider-dots',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '"></span>';
            },
        },
    });

    // Update dots on slide change
    swiper.on('slideChange', function () {
        const dots = document.querySelectorAll('.slider-dots span');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === swiper.realIndex);
        });
    });

    // Sticky Navbar Logic
    let lastScroll = 0;
    const navbar = document.querySelector('.sticky-nav');
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) { // Show navbar after scrolling 100px
            navbar.classList.add('visible');
        } else {
            navbar.classList.remove('visible');
        }
        
        lastScroll = currentScroll;
    });
}); 