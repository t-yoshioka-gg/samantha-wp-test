import slick from "slick-carousel/slick/slick.min.js";
import slickCss from "slick-carousel/slick/slick.css";
import slickTheme from "slick-carousel/slick/slick-theme.css";


export default class SlickSlider {

  /**
   * 初期化
   * @param options
   */
  constructor(options) {
    this.options = $.extend(options);
    this.init();
  }

  /**
   * 各スライダー登録
   */
  init() {
    this.slickGallery();
    // this.slickSlider();
    this.mainSlider();
  }



  slickGallery() {
    if ($('.c-gallery-slider').length < 0) {
      return false;
    }

    if ($('.c-gallery-slider__thumbnail .c-gallery-slider__thumbnail-image').length < 2) {
      $(".c-gallery-slider__thumbnail").addClass("is-simple-none");
    }

    $('.c-gallery-slider__main').slick({
      infinite: true,
      arrow: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      asNavFor: '.c-gallery-slider__thumbnail',
      autoplaySpeed: 4000,
      speed: 700,
      centerMode: true,
      variableWidth: true,
      dots: false,
      responsive: [{
        breakpoint: 750,
        settings: {
          variableWidth: false,
        }
      }]
    });

    // 下段
    $('.c-gallery-slider__thumbnail').slick({
      accessibility: true,
      autoplay: true,
      autoplaySpeed: 4000,
      speed: 800,
      arrows: true,
      prevArrow: '<div class="slick-prev"><img src="/wp-content/themes/samantha-hs-html/dist//assets/images/icon-thumbnail-prev.svg" /></div>',
      nextArrow: '<div class="slick-next"><img src="/wp-content/themes/samantha-hs-html/dist//assets/images/icon-thumbnail-next.svg" /></div>',
      dots: true,
      infinite: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      variableWidth: true,
      asNavFor: '.c-gallery-slider__main',
      focusOnSelect: true,
      responsive: [
        {
          breakpoint: 1920,
          settings: {
            variableWidth: false,
            autoplaySpeed: 2500,
            slidesToShow: 7
          }
        },
        {
          breakpoint: 1240,
          settings: {
            variableWidth: false,
            autoplaySpeed: 2500,
            slidesToShow: 5
          }
        },
        {
          breakpoint: 750,
          settings: {
            variableWidth: false,
            autoplaySpeed: 2500,
            slidesToShow: 4
          }
        }]
    });
  }



  mainSlider() {
    var time = 5;
    var $bar,
      $slick,
      isPause,
      tick,
      percentTime;
    $slick = $('.c-main-visual__main');
    if(!$slick) {
      return false;
    }

    // 上段
    $slick.slick({
      infinite: true,
      arrows: false,
      slidesToShow: 1,
      autoplaySpeed: 10000,
      fade: true,
      dots: true,
      draggable: false,
      speed: 1200,
    });
    $slick.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
      startProgressbar();
    });
    $bar = $('.c-main-visual__slider-progress-bar');
    function startProgressbar() {
      resetProgressbar();
      percentTime = 0;
      isPause = false;
      tick = setInterval(interval, 10);
    }
    function interval() {
      if (isPause === false) {
        percentTime += 1 / (time + 0.1);
        $bar.css({
          width: percentTime + "%"
        });
        if (percentTime >= 100) {
          $slick.slick('slickNext');
          startProgressbar();
        }
      }
    }
    function resetProgressbar() {
      $bar.css({
        width: 0 + '%'
      });
      clearTimeout(tick);
    }
    startProgressbar();
  }


  // // 複製して作業
  // slickSlider() {
  // // slick
  //   if ($('.js-gallery-slider').length < 0) {
  //     console.log('a');
  //     return false;
  //   }
  //   if ($('.c-gallery-slider__thumbnail .c-gallery-slider__thumbnail-image').length < 2) {
  //     $(".c-gallery-slider__thumbnail").addClass("is-simple-none");
  //   }
  //   // 上段
  //   $('.c-gallery-slider__main').slick({
  //     infinite: true,
  //     arrow: true,
  //     slidesToShow: 1,
  //     slidesToScroll: 1,
  //     prevArrow: '<div class="slick-prev"><img src="../assets/images/icon-arrow-left.png" /></div>',
  //     nextArrow: '<div class="slick-next"><img src="../assets/images/icon-arrow-next.png" /></div>',
  //     dots: true,
  //     autoplaySpeed: 2500,
  //     centerMode: true,
  //     variableWidth: true,
  //     dots: false,
  //     responsive: [{
  //       breakpoint: 750,
  //       settings: {
  //         variableWidth: false,
  //         autoplaySpeed: 2500,
  //         slidesToShow: 1
  //       }
  //     }]
  //   });
  // }
}




