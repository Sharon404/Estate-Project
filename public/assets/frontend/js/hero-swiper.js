// Hero Swiper with immediate fade transitions and duplicate-active support
document.addEventListener('DOMContentLoaded', function () {
    const initHero = () => {
        console.log('Hero Swiper Init: Swiper available?', typeof Swiper !== 'undefined');
        if (typeof Swiper === 'undefined') return false;
        
        const el = document.querySelector('.swiper-hero');
        console.log('Hero Swiper Init: Element found?', !!el);
        if (!el) return false;

        // Destroy any existing instance to avoid conflicts from other scripts
        if (el.swiper) {
            console.log('Hero Swiper: Destroying existing instance');
            el.swiper.destroy(true, true);
        }

        console.log('Hero Swiper: Initializing...');
        const swiperInstance = new Swiper('.swiper-hero', {
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
        console.log('Hero Swiper: Initialized successfully', swiperInstance);
        return true;
    };

    // Try immediately, and retry once after a short delay in case Swiper loads later
    if (!initHero()) {
        setTimeout(initHero, 300);
    }
});
