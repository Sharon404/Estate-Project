// Hero Swiper with immediate fade transitions and duplicate-active support
document.addEventListener('DOMContentLoaded', function () {
    const initHero = () => {
        if (typeof Swiper === 'undefined') return false;
        const el = document.querySelector('.swiper-hero');
        if (!el) return false;

        // Destroy any existing instance to avoid conflicts from other scripts
        if (el.swiper) {
            el.swiper.destroy(true, true);
        }

        new Swiper('.swiper-hero', {
            direction: 'horizontal',
            loop: true,
            slidesPerView: 1,
            allowTouchMove: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            speed: 300,
            effect: 'fade',
            fadeEffect: {
                crossFade: true,
            },
            pagination: {
                el: '.swiper-hero-pagination',
                clickable: true,
            },
        });
        return true;
    };

    // Try immediately, and retry once after a short delay in case Swiper loads later
    if (!initHero()) {
        setTimeout(initHero, 300);
    }
});
