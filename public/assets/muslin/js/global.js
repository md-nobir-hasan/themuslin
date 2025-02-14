$(document).ready(function () {
  var windowWidth = $(window).width();
  var TM = TweenMax;
  var tl = new TimelineMax();
  $("body").prepend(
    '<div class="overlay"></div><div class="form-overlay"></div>'
  );

  // --------------------------
  // EXTEND jQuery
  $.js = function (el) {
    return $("[data-js=" + el + "]");
  };

  /**
   *
   * @param d
   * @returns {string}
   * @private
   */
  function _pad(d) {
    return d < 10 ? "0" + d.toString() : d.toString();
  }

  var _img;
  function isImageOk(img) {
    _img = img.data("img");
    if (typeof _img === "undefined") {
      var _img = new Image();
      _img.src = img.attr("src");
      img.data("img", _img);
    }
    if (!_img.complete) {
      return false;
    }
    if (typeof _img.naturalWidth !== "undefined" && _img.naturalWidth === 0) {
      return false;
    }
    return true;
  }
  var imagesToLoad = null;

  (function ($) {
    $.fn.queueLoading = function () {
      var maxLoading = 2;
      var images = $(this);
      if (imagesToLoad === null || imagesToLoad.length === 0) {
        imagesToLoad = images;
      } else {
        imagesToLoad = imagesToLoad.add(images);
      }
      var imagesLoading = null;

      function checkImages() {
        imagesLoading = imagesToLoad.filter(".is-loading");
        imagesLoading.each(function () {
          var image = $(this);
          if (isImageOk(image)) {
            image.addClass("is-loaded").removeClass("is-loading");
            image.trigger("loaded");
          }
        });
        imagesToLoad = images.not(".is-loaded");
        loadNextImages();
      }

      function loadNextImages() {
        imagesLoading = imagesToLoad.filter(".is-loading");
        var nextImages = imagesToLoad.slice(
          0,
          maxLoading - imagesLoading.length
        );
        nextImages.each(function () {
          var image = $(this);
          if (image.hasClass("is-loading")) return;
          image.attr("src", image.attr("data-src"));
          image.addClass("is-loading");
        });
        if (imagesToLoad.length != 0) setTimeout(checkImages, 25);
      }

      checkImages();
    };
  })(jQuery);

  var slideshow,
    slideshowDuration = 4000,
    loaderAnim = true;

  /**
   * cities slider - prev/next
   */
  function sliderArrows() {
    $(".slideshow .arrows .arrow").on("click", function () {
      TweenMax.to($(".page.is-active i.is-animating"), 1, { x: "101%" });
      slideshowNext(
        $(this).closest(".slideshow"),
        $(this).hasClass("prev"),
        false
      );
      loaderAnim = false;
    });

    $(".slideshow").each(function () {
      var $this = $(this);
      var mc = new Hammer(this);
      mc.on("swipe", function (ev) {
        if (ev.direction === 4) {
          slideshowNext($(ev.target).closest(".slideshow"), true, false);
        } else if (ev.direction === 2) {
          slideshowNext($(ev.target).closest(".slideshow"), false, false);
        } else {
          return false;
        }
      });
    });
  }

  /**
   * cities slider - pages/nav
   */
  function sliderPages() {
    $(".slideshow .pages .page").on("click", function () {
      TweenMax.to($(".page.is-active i.is-animating"), 1, { x: "101%" });
      slideshowSwitch($(this).closest(".slideshow"), $(this).index(), true);
      loaderAnim = true;
    });

    $(".slideshow .pages").on("check", function () {
      var pages = $(this).find(".page"),
        index = slideshow.find(".slides .is-active").index();
      pages.removeClass("is-active");
      pages.eq(index).addClass("is-active");
      sliderNavloader();
    });
  }

  $("#tabSelect").on("change", function () {
    var selectedTab = $(this).val();
    $(".tab-pane").removeClass("show active").delay(50); // Add a slight delay
    $("#" + selectedTab).addClass("show active");
  });

  /**
   * home slider
   */
  function homeSlider() {
    /**
     * first call loader on slider navigation
     */
    sliderNavloader();

    /**
     * preload slider images
     */
    $("img.queue-loading").queueLoading();

    $('[data-js="city-slider"]').each(function () {
      slideshow = $(this);
      var images = slideshow.find(".image").not(".is-loaded");
      images.on("loaded", function () {
        var image = $(this);
        var slide = image.closest(".slide");
        slide.addClass("is-loaded");
      });
      images.queueLoading();
      var timeout = setTimeout(function () {
        slideshowNext(slideshow, false, true);
        loaderAnim = true;
      }, slideshowDuration);
      slideshow.data("timeout", timeout);
    });
  }

  /**
   *
   * @param slideshow
   * @param index
   * @param auto
   */
  function slideshowSwitch(slideshow, index, auto) {
    if (slideshow.data("wait")) {
      return;
    }
    var slides = slideshow.find(".slide"),
      pages = slideshow.find(".pages"),
      activeSlide = slides.filter(".is-active"),
      activeSlideImage = activeSlide.find(".image-container"),
      newSlide = slides.eq(index),
      newSlideImage = newSlide.find(".image-container"),
      newSlideContent = newSlide.find(".slide__slide-content"),
      newSlideElements = newSlide.find(".slide__slide-content > *"),
      timeout = slideshow.data("timeout"),
      transition = slideshow.attr("data-transition");

    if (newSlide.is(activeSlide)) {
      return;
    }
    newSlide.addClass("is-new");
    clearTimeout(timeout);
    slideshow.data("wait", true);

    if (transition === "fade") {
      newSlide.css({ display: "block", zIndex: 2 });
      newSlideImage.css({ opacity: 0 });
      TweenMax.to(newSlideImage, 1, {
        alpha: 1,
        onComplete: function () {
          newSlide.addClass("is-active").removeClass("is-new");
          activeSlide.removeClass("is-active");
          newSlide.css({ display: "", zIndex: "" });
          newSlideImage.css({ opacity: "" });
          slideshow.find(".pages").trigger("check");
          slideshow.data("wait", false);
          if (auto) {
            timeout = setTimeout(function () {
              slideshowNext(slideshow, false, true);
            }, slideshowDuration);
            slideshow.data("timeout", timeout);
          }
        },
      });
    } else if (transition === "transform") {
      // TODO
    } else {
      if (newSlide.index() > activeSlide.index()) {
        var newSlideRight = 0,
          newSlideLeft = "auto",
          newSlideImageRight = -slideshow.width() / 8,
          newSlideImageLeft = "auto",
          newSlideImageToRight = 0,
          newSlideImageToLeft = "auto",
          newSlideContentLeft = "auto",
          newSlideContentRight = 0,
          activeSlideImageLeft = -slideshow.width() / 4;
      } else {
        var newSlideRight = "",
          newSlideLeft = 0,
          newSlideImageRight = "auto",
          newSlideImageLeft = -slideshow.width() / 8,
          newSlideImageToRight = "",
          newSlideImageToLeft = 0,
          newSlideContentLeft = 0,
          newSlideContentRight = "auto",
          activeSlideImageLeft = slideshow.width() / 4;
      }

      newSlide.css({
        display: "block",
        width: 0,
        right: newSlideRight,
        left: newSlideLeft,
        zIndex: 2,
      });
      newSlideImage.css({
        width: slideshow.width(),
        right: newSlideImageRight,
        left: newSlideImageLeft,
      });
      newSlideContent.css({
        width: slideshow.width(),
        left: newSlideContentLeft,
        right: newSlideContentRight,
      });
      activeSlideImage.css({ left: 0 });

      TweenMax.set(newSlideElements, { y: 20, force3D: true });
      TweenMax.to(activeSlideImage, 1, {
        left: activeSlideImageLeft,
        ease: Expo.easeInOut,
      });
      TweenMax.to(newSlide, 1, {
        width: slideshow.width(),
        ease: Expo.easeInOut,
      });
      TweenMax.to(newSlideImage, 1, {
        right: newSlideImageToRight,
        left: newSlideImageToLeft,
        ease: Expo.easeInOut,
      });

      TweenMax.staggerFromTo(
        newSlideElements,
        0.8,
        { alpha: 0, y: 60 },
        {
          alpha: 1,
          y: 0,
          ease: Expo.easeOut,
          force3D: true,
          delay: 0.6,
        },
        0.1,
        function () {
          newSlide.addClass("is-active").removeClass("is-new");
          activeSlide.removeClass("is-active");
          newSlide.css({ display: "", width: "", left: "", zIndex: "" });
          newSlideImage.css({ width: "", right: "", left: "" });
          newSlideContent.css({ width: "", left: "" });
          newSlideElements.css({ opacity: "", transform: "" });
          activeSlideImage.css({ left: "" });
          slideshow.find(".pages").trigger("check");
          slideshow.data("wait", false);
          if (auto) {
            timeout = setTimeout(function () {
              slideshowNext(slideshow, false, true);
            }, slideshowDuration);
            slideshow.data("timeout", timeout);
          }
        }
      );
    }

    /**
     * update counter
     */
    $.js("counter-from").html(_pad(newSlide.index() + 1));
  }

  /**
   *
   * @param slideshow
   * @param previous
   * @param auto
   */
  function slideshowNext(slideshow, previous, auto) {
    var slides = slideshow.find(".slide"),
      activeSlide = slides.filter(".is-active"),
      newSlide = null;
    if (previous) {
      newSlide = activeSlide.prev(".slide");
      if (newSlide.length === 0) {
        newSlide = slides.last();
      }
    } else {
      newSlide = activeSlide.next(".slide");
      if (newSlide.length === 0) {
        newSlide = slides.filter(".slide").first();
      }
    }

    slideshowSwitch(slideshow, newSlide.index(), auto);
  }

  /**
   * loader on slider nav
   */
  function sliderNavloader() {
    if ($(".page.is-active").length > 0) {
      var $self = $(".page.is-active i");
      $self.addClass("is-animating");

      TweenMax.fromTo(
        $self,
        4,
        { x: "-100%" },
        {
          x: "0%",
          onComplete: function () {
            if (loaderAnim === true) {
              TweenMax.to($self, 1, {
                x: "101%",
                onComplete: function () {
                  // TweenMax.to($self, 0, { x: '-101%' });
                  $self.removeClass("is-animating");
                },
              });
            }
          },
        }
      );
    }
  }

  sliderArrows();
  sliderPages();
  homeSlider();
  // --------------------------

  //------------ Light gallery
  if ($(".Light").length > 0) {
    $(".Light").lightGallery({
      selector: "a",
    });
  }

  //------------ Light gallery with thumbnail
  if ($(".LightThumb").length > 0) {
    $(".LightThumb").lightGallery({
      selector: "a",
      exThumbImage: "data-exthumbimage",
    });
  }

  //------------ nice select
  if ($(".Select").length > 0) {
    $(".Select select").niceSelect();
  }

  //------------ tab change in mobile using nice select
  $(".TabSelect").on("change", function (e) {
    $(".TabMenus li a").eq($(this).val()).tab("show");
  });

  //------ form validation
  $("form .dynamic_submit_btn").click(function () {
    $(".form-overlay").addClass("doit");
  });

  $(document).on("click", ".form-overlay.doit,.ok-class", function () {
    $(".form-overlay.doit, .form-message-container").hide();
  });

  $(".btn , button").click(function () {
    $(".form-overlay.doit, .form-message-container").removeAttr("style");
  });

  $(".dynamic_submit_btn").on("click", function () {
    setTimeout(function () {
      $(".form-overlay.doit").hide();
    }, 15000);
  });
  //------ form validation

  // sticky menu
  screenPosition = 0;
  $(window).scroll(function () {
    scrolled = $(window).scrollTop();
    if (screenPosition - scrolled > 0) {
      $("body").addClass("ShowIt");
    } else {
      $("body").removeClass("ShowIt");
    }
    screenPosition = scrolled;
  });
  var first_section = $("body").position().top + 250;
  $(window).scroll(function () {
    if ($(window).scrollTop() <= first_section) {
      $("body").removeClass("ShowIt");
    }
  });

  // scroll to section
  $(".scroll-to").click(function () {
    if (
      location.pathname.replace(/^\//, "") ==
        this.pathname.replace(/^\//, "") &&
      location.hostname == this.hostname
    ) {
      var $target = $(this.hash);
      $target =
        ($target.length && $target) || $("[name=" + this.hash.slice(1) + "]");
      if ($target.length) {
        var targetOffset = $target.offset().top;
        $("html,body").animate({ scrollTop: targetOffset }, 1000);
        return false;
      }
    }
  });

  // disable scroll
  $(".Overlay,.menuItems").bind(
    "mousewheel DOMMouseScroll hover",
    function (e) {
      var scrollTo = null;

      if (e.type == "mousewheel") {
        scrollTo = e.originalEvent.wheelDelta * -1;
      } else if (e.type == "DOMMouseScroll") {
        scrollTo = 40 * e.originalEvent.detail;
      }

      if (scrollTo) {
        e.preventDefault();
        $(this).scrollTop(scrollTo + $(this).scrollTop());
      }
    }
  );

  //------- message box start

  $(document).delegate(".msg_cont", "click", function () {
    open_msg();
  });

  $(document).delegate(".msg_icon", "click", function () {
    open_msg();
  });

  function open_msg() {
    $(".msg_cont_wrap").css({ width: "330px", height: "446px" });
    TM.to(".msg_cont", 0.2, {
      height: 580,
      width: 580,
      right: -86,
      bottom: -86,
      borderRadius: 290,
      background: "rgba(255, 255, 255, 1)",
      onComplete: function () {},
    });

    TM.to(".msg_form", 0.5, {
      right: 0,
      delay: 0.2,
      onComplete: function () {
        $(".msg_cont_wrap").addClass("bx_shadow");
      },
    });

    $(".msg_cont_wrap").addClass("msg_opened");
    $(".msg_cont_wrap").removeClass("msg_closed");
  }

  $(document).delegate(".close_btn", "click", function () {
    close_msg();
  });

  function close_msg() {
    $(".msg_cont_wrap").removeClass("bx_shadow");

    TM.to(".msg_cont", 0.2, {
      width: "50px",
      height: "50px",
      right: 35,
      bottom: 8,
      borderRadius: "100%",
      background: "#008C44",
      onComplete: function () {
        setTimeout(function () {
          $(".msg_cont_wrap").css({ width: "120px", height: "120px" });
        }, 500);
      },
    });

    TM.to(".msg_form", 0.5, {
      right: -500,
    });

    setTimeout(function () {
      $(".msg_cont_wrap").removeClass("msg_opened");
      $(".msg_cont_wrap").addClass("msg_closed");
    }, 500);
  }

  $(".msg_cont , .msg_icon").click(function () {
    $(".msg_cont_wrap .title").fadeIn(2000);
    $(".close_btn").fadeIn();
  });

  $(".close_btn").click(function () {
    $(".msg_cont_wrap .title,.close_btn").hide();
  });

  // image preloader on slider
  $(".img-preload").length > 0 &&
    $(".img-preload").on("afterChange", function (event, slick, currentSlide) {
      imgPreloader();
    });

  //------------ message box end

  // hamburger menu toggle
  // $("#close-icon").hide();
  // Toggle on click
  // $("#hamburger-icon").click(function () {
  //   $(this).hide();
  //   $("#close-icon").show();
  //   $("#search").hide();
  //   $("#menu-to-toggle").fadeIn();
  //   $("#menu-to-toggle").css({
  //     overflow: "auto !important",
  //     "max-height": "100vh !important",
  //   });
  // });

  // $("#close-icon").click(function () {
  //   $(this).hide();
  //   $("#search").fadeIn();
  //   $("#hamburger-icon").fadeIn();
  //   $("#menu-to-toggle").hide();
  //   $("#menu-to-toggle").css({
  //     overflow: "hidden !important",
  //     "max-height": "none !important",
  //   });
  // });

  // breadcrumb
  $(".breadcrumb-area ul li:last-child a")
    .attr("disabled", true)
    .click(function (e) {
      e.preventDefault(); // Prevent default behavior (e.g., navigating to the link)
    });

  $("#hamburger-icon").click(function () {
    $("header .mobile-menu-content").css({
      left: "0",
    });
    $("body").addClass("overflow-hidden");
  });

  $("#close-icon").click(function () {
    $("header .mobile-menu-content").css({
      left: "-100%",
    });
    $("body").removeClass("overflow-hidden");
  });

  $(".header-menu nav ul li ul li:has(ul) > a").append(
    '<img src="/assets/muslin/images/static/menu-arrow.svg" alt="" />'
  );

  // main menu active
  $(".header-menu nav ul li a").click(function (e) {
    e.preventDefault(); // Prevent the default behavior of the anchor tag

    // Remove "active" class from all menu items
    $(".header-menu nav ul li a").removeClass("active");

    // Add "active" class to the clicked menu item and its parent
    $(this)
      .addClass("active")
      .parents("li")
      .children("a")
      .addClass("active parent");

    // Get the href attribute of the clicked menu item
    var targetUrl = $(this).attr("href");

    // Navigate to the specified URL
    window.location.href = targetUrl;
  });

  // add overflow to the body class
  $(".filter").click(function () {
    // Add the 'overflow-hidden' class to the body or any other element
    $("body").addClass("overflow-hidden");
    $(".global-widget").css({
      bottom: 0,
      opacity: 1,
      visibility: "visible",
    });
  });
  // add overflow to the body class
  $("#close").click(function () {
    // Add the 'overflow-hidden' class to the body or any other element
    $("body").removeClass("overflow-hidden");
    $(".global-widget").css({
      bottom: "-100%",
      opacity: 0,
      visibility: "hidden",
    });
  });

  // Click event handler for elements with the 'close' class
  // $("#close-icon").click(function () {
  //   // Remove the 'overflow-hidden' class from the body
  //   $("body").removeClass("overflow-hidden");
  // });

  $("#show-addToCart").click(function () {
    // Add the 'overflow-hidden' class to the body or any other element
    $("body").addClass("overflow-hidden");
  });

  $("#show-addToCart").on("click", function () {
    $("#cartPopup").css("right", "0px");
    $(".overlay").css("opacity", "1");
    $(".overlay").css("display", "block");
    $(".overlay").css("z-index", "55");
  });

  $("#show-addToFav").click(function () {
    // Add the 'overflow-hidden' class to the body or any other element
    $("body").addClass("overflow-hidden");
    $(".overlay").css("opacity", "1");
    $(".overlay").css("display", "block");
    $(".overlay").css("z-index", "55");
  });
  $("#show-addToFav1").click(function () {
    console.log("clicked");
    // Add the 'overflow-hidden' class to the body or any other element
    $("body").addClass("overflow-hidden");
  });

  $("#show-addToFav").on("click", function () {
    $("#favPopup").css("right", "0px");
  });

  $("#show-addToFav1").on("click", function () {
    $("#favPopup").css("right", "0px");
  });

  // Click event handler for elements with the 'close' class
  $("#close-addToCart").click(function () {
    // Remove the 'overflow-hidden' class from the body
    $("body").removeClass("overflow-hidden");
    $(".overlay").css("opacity", "0");
    $(".overlay").css("display", "none");
    $(".overlay").css("z-index", "-55");
  });

  $("#close-addToFav").click(function () {
    // Remove the 'overflow-hidden' class from the body
    $("body").removeClass("overflow-hidden");
    $(".overlay").css("opacity", "0");
    $(".overlay").css("display", "none");
    $(".overlay").css("z-index", "-55");
  });

  // invoice show hide
  $(".invoice").hide();
  $("#show-invoice").on("click", function () {
    $(".invoice").show();
    $(".order").hide();
  });

  $(".invoice").hide();
  $(".show-svg-invoice").on("click", function () {
    $(".invoice").show();
    $(".order").hide();
  });

  $(".close-invoice").on("click", function () {
    $(".invoice").hide();
    $(".order").show();
  });

  // shoe the add to cart popup from the product single page
  $("#show-single-addToCart").on("click", function () {
    $("#cartPopup").css("right", "0px");
  });

  $("#close-addToCart").on("click", function () {
    $("#cartPopup").css("right", "-100%");
  });
  $("#close-addToFav").on("click", function () {
    $("#favPopup").css("right", "-100%");
  });

  // all page checked radion button start
  // user registration page
  $("#checkboxContainer").on("click", function () {
    var checkbox = $("#checkHomeAddress");
    checkbox.prop("checked", !checkbox.prop("checked"));
  });

  $("#homeAddress").on("click", function () {
    var checkbox = $("#customCheckbox");
    checkbox.prop("checked", !checkbox.prop("checked"));
  });
  $("#checkCoupon").on("click", function () {
    var checkbox = $("#customCheckCoupon");
    checkbox.prop("checked", !checkbox.prop("checked"));
  });

  // $(".single-item__content h6").each(function () {
  //   var words = $(this).text().split(" ");
  //   if (words.length > 5) {
  //     var trimmedText = words.slice(0, 5).join(" ");
  //     $(this).text(trimmedText + "...");
  //   }
  // });

  // action show order list and hide the invoice

  $("#checkOnlinePaymentClick").on("click", function () {
    var checkbox = $("#checkboxPayment");
    checkbox.prop("checked", !checkbox.prop("checked"));
  });
  $("#checkCashOnDeliveryClick").on("click", function () {
    var checkbox = $("#checkboxCashDelivery");
    checkbox.prop("checked", !checkbox.prop("checked"));
  });

  // select single address to the checkout address page start
  $(".hidden-checkbox2").change(function () {
    // Uncheck all checkboxes except the one that triggered the change event
    $(".hidden-checkbox2").not(this).prop("checked", false);
  });
  // select single address to the checkout address page end


  // profile saved address

  // Initial setup: show saved-address and addNewAddressBtn
  $(".saved-address").show();
  $("#addNewAddressBtn").show();
  $(".new-address").hide();
  $("#saveNewAddressBtn").hide();

  // Handle click on addNewAddressBtn
  $("#addNewAddressBtn").click(function () {
    // Hide saved-address and addNewAddressBtn
    $(".address-container").hide();
    $("#addNewAddressBtn").hide();
    $("#saveNewAddressBtn").show();
    // Show new-address
    $(".new-address").show();
  });

  // Handle click on saveNewAddressBtn
  $("#saveNewAddressBtn").click(function () {
    // Perform any save or validation logic here

    // After saving, you can show the saved-address and addNewAddressBtn again
    $(".saved-address").show();
    $("#addNewAddressBtn").show();
    $("#saveNewAddressBtn").hide();

    // Hide new-address
    $(".new-address").hide();
  });

  // Toggle tabs for mobile view
  $(".nav-link").on("click", function () {
    if ($(window).width() <= 767) {
      $(".nav-link").not(this).removeClass("active");
      $(this).toggleClass("active");
    }
  });

  // Toggle accordion behavior for mobile view
  $(".nav-pills").on("click", function () {
    if ($(window).width() <= 767) {
      $(this).toggleClass("accordion");
    }
  });


  $(".shop-btn").hover(
    function () {
      // Find the specific image within the same card
      $(this).closest(".feature-card").find(".modify-img").addClass("zoom-in");
    },
    function () {
      // Find the specific image within the same card
      $(this)
        .closest(".feature-card")
        .find(".modify-img")
        .removeClass("zoom-in");
    }
  );

  $(".shop-btn").hover(
    function () {
      // Find the specific image within the same card
      $(this)
        .closest(".feature-list__item")
        .find(".modify-img")
        .addClass("zoom-in");
    },
    function () {
      // Find the specific image within the same card
      $(this)
        .closest(".feature-list__item")
        .find(".modify-img")
        .removeClass("zoom-in");
    }
  );

  $(".shop-btn").hover(
    function () {
      // Find the specific image within the same card
      $(this)
        .closest(".best-offer__single")
        .find(".modify-img")
        .addClass("zoom-in");
    },
    function () {
      // Find the specific image within the same card
      $(this)
        .closest(".best-offer__single")
        .find(".modify-img")
        .removeClass("zoom-in");
    }
  );


  function showToast() {
    $(".close-toast").click(function () {
      $(".toast").addClass("hidden");
    });
  }

  $("#checkboxPayment").change(function () {
    if ($(this).prop("checked")) {
      $("#checkboxCashDelivery").prop("checked", false);
    }
  });

  $("#checkboxCashDelivery").change(function () {
    if ($(this).prop("checked")) {
      $("#checkboxPayment").prop("checked", false);
    }
  });

  $(".product-gallery__details__sizes__btns").change(function () {
    handleCheckedItem($(this));
  });

  $(".product-gallery__details__sizes ul li input").change(function () {
    handleCheckedSize($(this));
  });

  function handleCheckedSize(checkbox) {
    // Remove the 'active' class from all <li> elements
    $(".product-gallery__details__sizes ul li").removeClass("active");

    // If the checkbox is checked
    if (checkbox.prop("checked")) {
      // Add the 'active' class to the parent <li> element
      checkbox.closest("li").addClass("active");
    }
  }

  // Change event for checkboxes of color
  $("#widget-color li input").change(function () {
    handleCheckedWidgetColor($(this));
  });

  // Function to handle checked checkboxes
  function handleCheckedWidgetColor(checkbox) {
    if (checkbox.prop("checked")) {
      $("#widget-color li input").not(checkbox).prop("checked", false);
    }
  }

  // Change event for checkboxes of sizes
  $("#widget-sizes li input").change(function () {
    handleCheckedWidgetSize($(this));
  });

  // Function to handle checked checkboxes
  function handleCheckedWidgetSize(checkbox) {
    if (checkbox.prop("checked")) {
      $("#widget-sizes li input").not(checkbox).prop("checked", false);
    }
  }

  // Widget Category Load More
  // Initial number of visible items
  var initialCategoryItems = 4;

  // Check if there are more than initialCategoryItems items
  var totalCategoryItems = $("#widget-category li").length;

  if (totalCategoryItems > initialCategoryItems) {
    // Hide additional items initially
    $("#widget-category .hidden-item").slice(initialCategoryItems).hide();

    // Click event for "Load More" link
    $("#loadMore a").on("click", function (e) {
      e.preventDefault();

      // Show all hidden items
      $("#widget-category .hidden-item").show();

      // Hide the "Load More" link
      $("#loadMore").hide();
    });
  } else {
    // If there are not enough items, hide the "Load More" link
    $("#loadMore").hide();
  }

  // Initial number of visible items
  var initialSubItems = 4;

  // Check if there are more than initialSubItems items
  var totalSubItems = $("#widget-subcategory li").length;

  if (totalSubItems > initialSubItems) {
    // Hide additional items initially
    $("#widget-subcategory .hidden-item").slice(initialSubItems).hide();

    // Click event for "Load More" link
    $("#loadMoreSub a").on("click", function (e) {
      e.preventDefault();

      // Show all hidden items
      $("#widget-subcategory .hidden-item").show();

      // Hide the "Load More" link
      $("#loadMoreSub").hide();
    });
  } else {
    // If there are not enough items, hide the "Load More" link
    $("#loadMoreSub").hide();
  }

  // brand items load more
  var initialBrandItems = 3;

  // Hide additional items initially
  $("#widget-brand .hidden-item").slice(initialBrandItems).hide();

  // Click event for "Load More" link
  $("#loadMoreBrand a").on("click", function (e) {
    e.preventDefault();

    // Show all hidden items
    $("#widget-brand .hidden-item").show();

    // Hide the "Load More" link
    $("#loadMoreBrand").hide();
  });

  // best selling slider
  var swiper = new Swiper(".best-selling", {
    // slidesPerView: 4,
    spaceBetween: 30,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".custom-next",
      prevEl: ".custom-prev",
    },
    breakpoints: {
      120: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      374: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      375: {
        slidesPerView: 2,
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
}); //document.ready

// place order buttons
// $("#btnPlaceOrder").click(function () {
//   console.log("clicked");
// });

// push to the checked attribute
// $(".check-box2").on("click", function () {
//   var checkbox = $(this).prev(".hidden-checkbox2");
//   checkbox.prop("checked", !checkbox.prop("checked"));
//   $(".hidden-checkbox2")
//     .not($(this).prev(".hidden-checkbox2"))
//     .prop("checked", false);
// });

// $(".check-box").on("click", function () {
//   var checkbox = $(this).prev(".hidden-checkbox");
//   checkbox.prop("checked", !checkbox.prop("checked"));
//   $(".hidden-checkbox")
//     .not($(this).prev(".hidden-checkbox"))
//     .prop("checked", false);
// });

$(".check-box2, .check-box").on("click", function () {
  var checkbox = $(this).prev("[type='checkbox']");
  checkbox.prop("checked", !checkbox.prop("checked"));

  var checkboxClass = checkbox.hasClass("hidden-checkbox")
    ? ".hidden-checkbox"
    : ".hidden-checkbox2";

  $(checkboxClass).not(checkbox).prop("checked", false);
});

// Listen for changes to the "I have read and agree" checkbox
$("#isAgree").change(function () {
  // If the checkbox is checked, remove the 'disabled' attribute from the button
  if (this.checked) {
    $("#btnPlaceOrder").removeAttr("disabled");
  } else {
    // If the checkbox is unchecked, add the 'disabled' attribute to the button
    $("#btnPlaceOrder").attr("disabled", "disabled");
  }
});

$(".content-area__left__delivery__items__item").click(function () {
  // Remove the 'selected' class from all items
  $(".content-area__left__delivery__items__item").removeClass(
    "selected-method"
  );

  // Add the 'selected' class only to the clicked item
  $(this).addClass("selected-method");
});

// success message function
function showSuccessAlert(message) {
  // Set the alert message
  $("#SuccessToast").html(`
    ${message}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  `);

  // Show the alert
  $("#SuccessToast").addClass("show");
  $("#SuccessToast").css("display", "block");

  // Hide the alert after 3 seconds
  setTimeout(function () {
    $("#SuccessToast").removeClass("show");
    $("#SuccessToast").css("display", "none");
  }, 8000);
}

// success message function
function showErrorAlert(message) {
  // Set the alert message
  $("#ErrorToast").html(`
    ${message}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  `);

  // Show the alert
  $("#ErrorToast").addClass("show");
  $("#ErrorToast").css({
    "display":"block",
    "max-width": "85%"
  });

  // Hide the alert after 3 seconds
  setTimeout(function () {
    $("#ErrorToast").removeClass("show");
    $("#ErrorToast").css("display", "none");
  }, 4000);
}

var QtyInput = (function () {
  var $qtyInputs = $(".qty-input");

  if (!$qtyInputs.length) {
    return;
  }

  var $inputs = $qtyInputs.find(".product-qty");
  var $countBtn = $qtyInputs.find(".qty-count");
  var qtyMin = parseInt($inputs.attr("min"));
  var qtyMax = parseInt($inputs.attr("max"));

  $inputs.change(function () {
    var $this = $(this);
    var $minusBtn = $this.siblings(".qty-count--minus");
    var $addBtn = $this.siblings(".qty-count--add");
    var qty = parseInt($this.val());

    if (isNaN(qty) || qty <= qtyMin) {
      $this.val(qtyMin);
      $minusBtn.attr("disabled", true);
    } else {
      $minusBtn.attr("disabled", false);

      if (qty >= qtyMax) {
        $this.val(qtyMax);
        $addBtn.attr("disabled", true);
      } else {
        $this.val(qty);
        $addBtn.attr("disabled", false);
      }
    }
  });

  $countBtn.click(function () {
    var operator = this.dataset.action;
    var $this = $(this);
    var $input = $this.siblings(".product-qty");
    var qty = parseInt($input.val());

    if (operator == "add") {
      qty += 1;
      if (qty >= qtyMin + 1) {
        $this.siblings(".qty-count--minus").attr("disabled", false);
      }

      if (qty >= qtyMax) {
        $this.attr("disabled", true);
      }
    } else {
      qty = qty <= qtyMin ? qtyMin : (qty -= 1);

      if (qty == qtyMin) {
        $this.attr("disabled", true);
      }

      if (qty < qtyMax) {
        $this.siblings(".qty-count--add").attr("disabled", false);
      }
    }

    $input.val(qty);
  });
})();
