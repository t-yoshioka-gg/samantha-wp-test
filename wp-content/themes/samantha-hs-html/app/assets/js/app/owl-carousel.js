import 'owl.carousel';
import OwlCss from "owl.carousel/dist/assets/owl.carousel.css";
import OwlThemeCss from "owl.carousel/dist/assets/owl.theme.default.css";

var defaultOptions = {
  selector: '.owl-carousel',
};

export default class OwlCarousel {
  /**
   * 初期化
   * @param options
   */
  constructor(options) {
    this.options = $.extend(defaultOptions, options);
    this.init();
  }

  /**
   * 初期化
   */
  init() {

    // ターゲットを取得する
    this.targetAll = $(this.options.selector);


    // ターゲットが存在しない場合は実行しない
    if (!this.targetAll.length) {
      return false;
    }

    // 実行する
    this.run();
  }

  /**
   * 実行する
   */


  run() {

    // const gsap = this.options.gsap;

    //->スライダー
    this.targetAll.imagesLoaded(function () {
      let $slider;
      $slider = $('.c-main-visual__slider.owl-carousel').owlCarousel({
        items: 1,
        margin: 0,
        dots: true,
        loop: true,
        nav: false,

        onInitialized: function () {
          // gsap.animate
        },
        autoplayHoverPause: true,
        autoplay: true,
        autoplaySpeed: 500,
        autoWidth: false,
        autoHeight: false,
        center: true,
        animateOut: 'fadeOut',
        navText: ['<img src="/wp-content/themes/samantha-hs-html/dist/assets/images/icon-slider-prev.svg" />', '<img src="/wp-content/themes/samantha-hs-html/dist/assets/images/icon-slider-next.svg" />'],
      });
      $slider.on('changed.owl.carousel', function (event) {
        startProgressbar();
      })

      var time = 20;
      var $bar,
        isPause,
        tick,
        percentTime;

      $bar = $('.c-main-visual__progress-bar');

      function startProgressbar() {
        resetProgressbar();
        percentTime = 0;
        isPause = false;
        tick = setInterval(interval, 0);
      }

      function interval() {
        if (isPause === false) {
          percentTime += 1 / (time + 0.1);
          $bar.css({
            width: percentTime + "%"
          });
          if (percentTime >= 100) {
            $slider.trigger('next.owl.carousel');
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


    });


    //->カード_カルーセル
    this.targetAll.imagesLoaded(function () {

      let $target = $('.js-card-slider');
      let $item = $('.js-slider-item')
      if ($target.length <= 0) {
        return;
      }

      if ($item.length <= 1) {
        $target.removeClass("owl-carousel");
        return;
      }

      var startItem = $item.length - 1;

      $target.owlCarousel({
        margin: 36,
        dots: true,
        loop: true,
        nav: true,
        autoplayHoverPause: true,
        autoplay: true,
        autoplaySpeed: 500,
        autoWidth: false,
        autoHeight: false,
        center: true,
        speed: 400,
        navText: ['<img src="/wp-content/themes/samantha-hs-html/dist/assets/images/icon-slider-prev.svg" />', '<img src="/wp-content/themes/samantha-hs-html/dist/assets/images/icon-slider-next.svg" />'],
        startPosition: startItem,
        responsive: {
          // breakpoint from 0 up
          0: {
            items: 1,
            dotsEach: 5,
          },
          750: {
            items: 2,
            dotsEach: 3,
          },
          // breakpoint from 750 up
          950: {
            items: 3,
            dotsEach: 3,
          }
        }
      });

      $('a[data-toggle="tab"]').click(function () {
        $(".js-card-slider.owl-carousel").trigger('refresh.owl.carousel');
      });


    });


    //->SNSギャラリー
    this.targetAll.imagesLoaded(function () {

      let $target = $('.js-image-slider');
      let $item = $('.c-box-image-slider__block')
      if ($target.length <= 0) {
        return;
      }

      if ($item.length <= 1) {
        $target.removeClass("owl-carousel");
        return;
      }

      var startItem = $item.length - 1;

      $target.owlCarousel({
        margin: 8,
        dots: false,
        nav: false,
        loop: true,
        autoplay: true,
        slideTransition: 'linear',
        autoplayTimeout: 6000,
        autoplaySpeed: 6000,
        autoplayHoverPause: false,
        autoWidth: true,
        autoHeight: true,
        center: false,
        responsive: {
          // breakpoint from 0 up
          0: {
            items: 3,
          },
          // breakpoint from 750 up
          750: {
            items: 5,
          }
        }
      });
    });









    this.targetAll.imagesLoaded(function () {

      let $target = $('.js-box-voice-slider');
      let $item = $('.c-box-voice__item')
      if ($target.length <= 0) {
        return;
      }

      if ($item.length <= 2) {
        $target.removeClass("owl-carousel");
        return;
      }

      $target.owlCarousel({
        margin: 32,
        dots: true,
        nav: true,
        loop: true,
        autoplay: true,
        slideTransition: 'linear',
        autoplayTimeout: 6000,
        smartSpeed: 500,
        autoplaySpeed: 500,
        autoplayHoverPause: false,
        autoWidth: false,
        autoHeight: true,
        center: false,
        items: 2,
        navText: ['<img src="/wp-content/themes/samantha-hs-html/dist/assets/images/icon-slider-prev.svg" />', '<img src="/wp-content/themes/samantha-hs-html/dist/assets/images/icon-slider-next.svg" />'],

        responsive: {
          // breakpoint from 0 up
          0: {
            items: 1,
            margin: 0,
          },
          // breakpoint from 750 up
          750: {
            items: 2,
          }
        }
      });
    });


    this.targetAll.imagesLoaded(function () {
      let $target = $('.js-comic-slider');
      let $item = $('.c-comic-slider__item')
      if ($target.length <= 0) {
        return;
      }

      if ($item.length <= 2) {
        $target.removeClass("owl-carousel");
        return;
      }

      $target.owlCarousel({
        dots: true,
        nav: true,
        loop: true,
        autoplay: false,
        slideTransition: 'linear',
        autoplayTimeout: 6000,
        smartSpeed: 500,
        autoplaySpeed: 1000,
        autoplayHoverPause: false,
        autoWidth: false,
        autoHeight: true,
        center: false,
        items: 1,
        navText: ['<span class="material-icons">arrow_back_ios_new</span>', '<span class="material-icons">arrow_forward_ios</span>'],
      });
    });





  }
}




