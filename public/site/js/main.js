$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

const navbar = document.querySelector(".navbar");
window.onscroll = () => {
    if (window.scrollY > 100) {
        navbar.classList.add("active");
    } else {
        navbar.classList.remove("active");
    }
};

$(".servicesSlider").owlCarousel({
    loop: true,
    margin: 10,
    items: 1,
    responsiveClass: true,
    responsive: {
        480: {
            items: 1,
            nav: true,
        },
        768: {
            items: 2,
            nav: false,
        },
        1024: {
            items: 3,
            nav: true,
            loop: false,
        },
    },
});

$(".testimonialSlider").owlCarousel({
    loop: true,
    margin: 0,
    items: 1,
    responsiveClass: true,
    responsive: {
        480: {
            items: 1,
            nav: true,
        },
        768: {
            items: 2,
            nav: false,
        },
        1024: {
            items: 2,
            nav: true,
            loop: false,
        },
    },
});
