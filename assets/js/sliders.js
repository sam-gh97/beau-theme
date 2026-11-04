import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, Thumbs, FreeMode } from 'swiper/modules';
import { Fancybox } from '@fancyapps/ui';

export function initSliders() {
    // Banner Slider
    const bannerSliders = document.querySelectorAll('[data-swiper="banner"]');
    bannerSliders.forEach(slider => {
        const pagination = slider.querySelector('.banner-pagination');
        
        new Swiper(slider, {
            modules: [Autoplay, Pagination],
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 7000,
                disableOnInteraction: false,
            },
            speed: 800,
            pagination: {
                el: pagination,
                clickable: true,
                type: 'bullets',
            },
        });
    });

    // Testimonials Slider
    const testimonialSliders = document.querySelectorAll('[data-swiper="testimonials"]');
    testimonialSliders.forEach(slider => {
        const parentContainer = slider.closest('.flex.justify-between');
        const nextButton = parentContainer?.querySelector('.testimonial-nav-next');
        const prevButton = parentContainer?.querySelector('.testimonial-nav-prev');
        
        new Swiper(slider, {
            modules: [Navigation],
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            navigation: {
                nextEl: nextButton,
                prevEl: prevButton,
            },
        });
    });

    // Images Slider
    const sliders = document.querySelectorAll('[data-swiper="images"]');

    sliders.forEach(slider => {
        new Swiper(slider, {
            modules: [Autoplay, FreeMode],
            slidesPerView: 'auto',
            spaceBetween: 24,
            loop: true,
            centeredSlides: false,
            allowTouchMove: false,
    
            freeMode: {
                enabled: true,
                momentum: false
            },
    
            autoplay: {
                delay: 0,
                pauseOnMouseEnter: false,
                disableOnInteraction: false
            },
    
            speed: 5000, // increases smoothness, not literal speed
            grabCursor: false
        });
    });

    // Fancybox
    Fancybox.bind('[data-fancybox="video"]');

    // Logos Carousel
    const logosCarousels = document.querySelectorAll('[data-swiper="logos"]');
    logosCarousels.forEach(slider => {
        new Swiper(slider, {
            modules: [Autoplay, FreeMode],
            slidesPerView: 2.5,
            spaceBetween: 24,
            loop: true,
            loopedSlides: 6,
            freeMode: {
                enabled: true,
                momentum: false,
            },
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
            },
            speed: 3000,
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    loopedSlides: 2,
                },
                768: {
                    slidesPerView: 3,
                    loopedSlides: 3,
                },
                1024: {
                    slidesPerView: 4,
                    loopedSlides: 4,
                },
                1280: {
                    slidesPerView: 6,
                    loopedSlides: 6,
                },
            },
        });
    });

    // Products Slider
    const productsSliders = document.querySelectorAll('[data-swiper="products"]');
    productsSliders.forEach(slider => {
        const parentContainer = slider.closest('.container');
        const nextButton = parentContainer?.querySelector('.product-nav-next');
        const prevButton = parentContainer?.querySelector('.product-nav-prev');
        
        new Swiper(slider, {
            modules: [Navigation],
            slidesPerView: 1.35,
            spaceBetween: 10,
            loop: true,
            navigation: {
                nextEl: nextButton,
                prevEl: prevButton,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1.5,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
                1280: {
                    slidesPerView: 4,
                    spaceBetween: 24,
                },
            },
        });
    });

    // Product Gallery with Thumbnails
    const productGallerySliders = document.querySelectorAll('[data-swiper="product-gallery"]');
    productGallerySliders.forEach(gallerySlider => {
        const container = gallerySlider.parentElement.parentElement;
        const thumbsSlider = container?.querySelector('[data-swiper="product-thumbs"]');
        
        if (!thumbsSlider) return;

        // Initialize thumbnails slider (vertical)
        const thumbsSwiper = new Swiper(thumbsSlider, {
            modules: [Thumbs],
            direction: 'vertical',
            spaceBetween: 12,
            slidesPerView: 'auto',
            freeMode: true,
            watchSlidesProgress: true,
        });

        // Initialize main gallery slider
        new Swiper(gallerySlider, {
            modules: [Thumbs],
            spaceBetween: 0,
            slidesPerView: 1,
            thumbs: {
                swiper: thumbsSwiper,
            },
        });
    });
}

