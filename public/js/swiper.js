const swiper = new Swiper('.swiper', {
    loop: true,
    slidesPerView: 4,
    spaceBetween: 8,


    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    breakpoints: {
        300: {
            slidesPerView: 1,
            spaceBetween: 8,
        },
        640: {
            slidesPerView: 1,
            spaceBetween: 8,
        },
        768: {
            slidesPerView: 1,
            spaceBetween: 8,
        },
        1024: {
            slidesPerView: 1,
            spaceBetween: 8,
        },
    },
});


