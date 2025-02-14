$(document).ready(function () {
  var windowWidth = $(window).width();
  var TM = TweenMax;
  var tl = new TimelineMax();

  // Initialize the product thumbs gallery
  var swiper = new Swiper(".gallery-thumb", {
    spaceBetween: 10,
    slidesPerView: 5,
    freeMode: true,
    watchSlidesProgress: true,
  });

  var swiper2 = new Swiper(".gallery-slide", {
    autoplay: {
      delay: 5000,
    },
    loop: false,
    speed: 1500,
    navigation: {
      nextEl: ".product-gallery-custom-next",
      prevEl: ".product-gallery-custom-prev",
    },
    thumbs: {
      swiper: swiper,
    },
  });

  // Initial quantity value
  var quantity = 1;

  // Event handler for the plus button
  $(".plus").on("click", function () {
    quantity++;
    updateQuantity();
  });

  // Event handler for the minus button
  $(".minus").on("click", function () {
    if (quantity > 1) {
      quantity--;
      updateQuantity();
    }
  });

  // Function to update the quantity in the <p> element
  function updateQuantity() {
    $(".product-gallery__details__qty__btns p").text(quantity);
  }






  // change URL when tab click
  var hash = window.location.hash;
  $('a[href="' + hash + '"]').tab('show');

  // Change hash for page-reload
  $('.nav-pills a').on('show.bs.tab', function (e) {
      window.location.hash = e.target.hash;
      window.scrollTo(0, 0);
  });

  $(document).delegate('.invoice .close-invoice', 'click', function (event, jqXHR, settings) {

      $(".invoice").hide();
      $(".order").show();
  });


}); //document.ready





