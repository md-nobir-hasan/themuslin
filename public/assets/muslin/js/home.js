$(document).ready(function () {
  var windowWidth = $(window).width();
  var TM = TweenMax;
  var tl = new TimelineMax();

  // home banner slider

  // Get the title element inside the active slide

  var swiper3 = new Swiper(".banner-active", {
    slidesPerView: 1,
    speed: 1500,
    autoHeight: true,
    autoplay: {
      delay: 5000,
    },
    loop: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".next-arrow",
      prevEl: ".prev-arrow",
    },

    on: {
      slideChangeTransitionEnd: function () {
        // Get all titles
        var titles = document.querySelectorAll(
          ".banner-slider .swiper-slide .swiper-slide-active h1"
        );
        //titles.style.color = "orange";
      },
    },
  });

  // best selling slider
  var swiper2 = new Swiper(".best-offer-active", {
    slidesPerView: 1,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".custom-next",
      prevEl: ".custom-prev",
    },
  });

  // home facilities slider
  if ($(".facilities-active").length > 0) {
    $(".facilities-active").slick({
      arrows: true,
      slidesToShow: 4,
      infinite: true,
      speed: 500,
      fade: false,
      cssEase: "linear",
      autoHeight: true, // Set this to true to enable autoHeight
    });
  }

  // facilities slider
  var swiper = new Swiper(".facilities", {
    slidesPerView: 1,
    spaceBetween: 30,
    speed: 1000,
    autoHeight: true,
    autoplay: {
      delay: 3000,
    },
    loop: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".arrow-next",
      prevEl: ".arrow-prev",
    },
    loop: true,
    breakpoints: {
      120: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      375: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 30,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 30,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      1366: {
        slidesPerView: 4,
        spaceBetween: 30,
      },
    },
  });

  // best selling slider
  var swiper2 = new Swiper(".best-offer-active", {
    slidesPerView: 1,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".custom-next",
      prevEl: ".custom-prev",
    },
  });

  // var totalSlides = swiper2.slides.length;
  //
  // // if (totalSlides <= 1) {
  // //   $(".arrows").css("display", "none");
  // // }
  
}); //document.ready
