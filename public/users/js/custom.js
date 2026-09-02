document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | New-arrivals slider
    |--------------------------------------------------------------------------
    */

    const newArrivalsElement = document.querySelector(".new-arrivals-swiper");

    if (newArrivalsElement) {
        new Swiper(newArrivalsElement, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            watchOverflow: true,

            pagination: {
                el: ".new-arrivals-pagination",
                clickable: true,
            },

            navigation: {
                nextEl: ".new-arrivals-arrow-right",
                prevEl: ".new-arrivals-arrow-left",
            },

            breakpoints: {
                576: {
                    slidesPerView: 2,
                    spaceBetween: 22,
                },

                768: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },

                1200: {
                    slidesPerView: 4,
                    spaceBetween: 28,
                },
            },
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Best-selling slider
    |--------------------------------------------------------------------------
    */

    const bestSellingElement = document.querySelector(".best-selling-swiper");

    if (bestSellingElement) {
        new Swiper(bestSellingElement, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            watchOverflow: true,

            pagination: {
                el: ".best-selling-pagination",
                clickable: true,
            },

            navigation: {
                nextEl: ".best-selling-arrow-right",
                prevEl: ".best-selling-arrow-left",
            },

            breakpoints: {
                576: {
                    slidesPerView: 2,
                    spaceBetween: 22,
                },

                768: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },

                1200: {
                    slidesPerView: 4,
                    spaceBetween: 28,
                },
            },
        });
    }
});
