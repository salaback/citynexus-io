var oakleaf = oakleaf || {};
"use strict";
$(function() {
 
  // global inicialization functions
  oakleaf.global = {

    init: function() {
      oakleaf.global.deviceSize();
      oakleaf.global.layout();
      oakleaf.global.animsition();
    },
    // device identification function
    deviceSize: function(){
			var jRes = jRespond([
				{
					label: 'smallest',
					enter: 0,
					exit: 479
				},{
					label: 'handheld',
					enter: 480,
					exit: 767
				},{
					label: 'tablet',
					enter: 768,
					exit: 991
				},{
					label: 'laptop',
					enter: 992,
					exit: 1199
				},{
					label: 'desktop',
					enter: 1200,
					exit: 10000
				}
			]);
			jRes.addFunc([
				{
					breakpoint: 'desktop',
					enter: function() { $body.addClass('device-lg'); },
					exit: function() { $body.removeClass('device-lg'); }
				},{
					breakpoint: 'laptop',
					enter: function() { $body.addClass('device-md'); },
					exit: function() { $body.removeClass('device-md'); }
				},{
					breakpoint: 'tablet',
					enter: function() { $body.addClass('device-sm'); },
					exit: function() { $body.removeClass('device-sm'); }
				},{
					breakpoint: 'handheld',
					enter: function() { $body.addClass('device-xs'); },
					exit: function() { $body.removeClass('device-xs'); }
				},{
					breakpoint: 'smallest',
					enter: function() { $body.addClass('device-xxs'); },
					exit: function() { $body.removeClass('device-xxs'); }
				}
			]);
		},

    layout: function() {
      var defaultHeadertheme = 'theme-light',
          defaultNavbartheme = 'theme-default',
          defaultBrandingtheme = 'theme-default',
          defaultColortheme = 'default-theme-color',
          defaultHeaderPosition = 'header-fixed',
          defaultNavbarPosition = 'aside-fixed',
          defaultRightbarVisibility = 'rightmenu-hidden',
          defaultAppClasses = 'theme-default default-theme-color header-fixed aside-fixed rightmenu-hidden';

      $body.addClass(defaultAppClasses);
      $header.addClass(defaultHeadertheme);
      $branding.addClass(defaultBrandingtheme);
      $leftmenu.addClass(defaultNavbartheme).addClass(defaultNavbarPosition);

      $headerthemeEl.on('click', function($event) {
        var theme = $(this).data('theme');

        $body.removeClass(defaultHeadertheme).addClass(theme);
        $header.removeClass(defaultHeadertheme).addClass(theme);
        defaultHeadertheme = theme;
        $event.stopPropagation();
      });

      $brandingthemeEl.on('click', function($event) {
        var theme = $(this).data('theme');

        $branding.removeClass(defaultBrandingtheme).addClass(theme);
        defaultBrandingtheme = theme;
        $event.stopPropagation();
      });

      $leftmenuthemeEl.on('click', function($event) {
        var theme = $(this).data('theme');

        $body.removeClass(defaultNavbartheme).addClass(theme);
        $leftmenu.removeClass(defaultNavbartheme).addClass(theme);
        defaultNavbartheme = theme;
        $event.stopPropagation();
      });

      $colorthemeEl.on('click', function($event) {
        var theme = $(this).data('theme');

        $body.removeClass(defaultColortheme).addClass(theme);
        defaultColortheme = theme;
        $event.stopPropagation();
      });

      $fixedHeaderEl.change(function() {
        if ($body.hasClass('header-fixed')) {
          $body.removeClass('header-fixed').addClass('header-static');
        } else {
          $body.removeClass('header-static').addClass('header-fixed');
        }
      });
      $fixedHeaderEl.parent().on('click', function($event) {
        $event.stopPropagation();
      });

      $fixedAsideEl.change(function() {
        if ($body.hasClass('aside-fixed')) {
          $body.removeClass('aside-fixed').addClass('aside-static');
          $leftmenu.removeClass('aside-fixed').addClass('aside-static');
        } else {
          $body.removeClass('aside-static').addClass('aside-fixed');
          $leftmenu.removeClass('aside-static').addClass('aside-fixed');
        }
      });
      $fixedAsideEl.parent().on('click', function($event) {
        $event.stopPropagation();
      });

      $toggleRightbarEl.on('click', function() {
        if ($body.hasClass('rightmenu-hidden')) {
          $body.removeClass('rightmenu-hidden').addClass('rightmenu-show');
        } else {
          $body.removeClass('rightmenu-show').addClass('rightmenu-hidden');
        }
      });

      if ($app.hasClass('boxed-layout')){
        $app.parent().addClass('boxed-layout');
      }

      if ($app.hasClass('leftmenu-offcanvas')){
        $app.parent().addClass('leftmenu-offcanvas');
      }

      if ($app.hasClass('hz-menu')){
        $app.parent().addClass('hz-menu');
      }

      if ($app.hasClass('rtl')){
        $app.parent().addClass('rtl');
      }

    },

    // initialize animsition
    animsition: function() {
      $wrap.animsition({
        inClass               :   'fade-in',
        outClass              :   'fade-out',
        inDuration            :    1500,
        outDuration           :    800,
        linkElement           :   '.animsition-link',
        // e.g. linkElement   :   'a:not([target="_blank"]):not([href^=#])'
        loading               :    true,
        loadingParentElement  :   'body', //animsition wrapper element
        loadingClass          :   'animsition-loading',
        unSupportCss          : [ 'animation-duration',
          '-webkit-animation-duration',
          '-o-animation-duration'
        ],
        //"unSupportCss" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
        //The default setting is to disable the "animsition" in a browser that does not support "animation-duration".

        overlay               :   false,
        overlayClass          :   'animsition-overlay-slide',
        overlayParentElement  :   'body'
      });
    }

  };

  // header section functions

  oakleaf.header = {

    init: function() {

    }

  };

  // navbar section functions

  oakleaf.navbar = {

    init: function() {
      oakleaf.navbar.menu();
      oakleaf.navbar.ripple();
      oakleaf.navbar.removeRipple();
      oakleaf.navbar.collapse();
      oakleaf.navbar.offcanvas();
    },

    menu: function(){
      if( $dropdowns.length > 0 ) {

        $dropdowns.addClass('dropdown');

        var $submenus = $dropdowns.find('ul >.dropdown');
        $submenus.addClass('submenu');

        $a.append('<i class="fa fa-plus"></i>');

        $a.on('click', function(event) {
          if ($app.hasClass('leftmenu-sm') || $app.hasClass('leftmenu-xs') || $app.hasClass('hz-menu')) {
            return false;
          }

          var $this = $(this),
              $parent = $this.parent('li'),
              $openSubmenu = $('.submenu.open');

          if (!$parent.hasClass('submenu')) {
            $dropdowns.not($parent).removeClass('open').find('ul').slideUp();
          }

          $openSubmenu.not($this.parents('.submenu')).removeClass('open').find('ul').slideUp();
          $parent.toggleClass('open').find('>ul').stop().slideToggle();
          event.preventDefault();
        });

        $dropdowns.on('mouseenter', function() {
          $leftmenu.addClass('dropdown-open');
          $controls.addClass('dropdown-open');
        });

        $dropdowns.on('mouseleave', function() {
          $leftmenu.removeClass('dropdown-open');
          $controls.removeClass('dropdown-open');
        });

        $notDropdownsLinks.on('click', function() {
          $dropdowns.removeClass('open').find('ul').slideUp();
        });

        var $activeDropdown = $('.dropdown>ul>.active').parent();

        $activeDropdown.css('display', 'block');
      }
    },

    ripple: function() {
      var parent, ink, d, x, y;

      $navigation.find('>li>a').click(function(e){
        parent = $(this).parent();

        if(parent.find('.ink').length === 0) {
          parent.prepend('<span class="ink"></span>');
        }

        ink = parent.find('.ink');
        //incase of quick double clicks stop the previous animation
        ink.removeClass('animate');

        //set size of .ink
        if(!ink.height() && !ink.width())
        {
          //use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
          d = Math.max(parent.outerWidth(), parent.outerHeight());
          ink.css({height: d, width: d});
        }

        //get click coordinates
        //logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
        x = e.pageX - parent.offset().left - ink.width()/2;
        y = e.pageY - parent.offset().top - ink.height()/2;

        //set the position and add class .animate
        ink.css({top: y+'px', left: x+'px'}).addClass('animate');

        setTimeout(function(){
          $('.ink').remove();
        }, 600);
      });
    },

    removeRipple: function(){
      $leftmenu.find('.ink').remove();
    },

    collapse: function(){
      $collapseSidebarEl.on('click', function(e) {
        if ($app.hasClass('leftmenu-sm')) {
          $app.removeClass('leftmenu-sm').addClass('leftmenu-xs');
        }
        else if ($app.hasClass('leftmenu-xs')) {
          $app.removeClass('leftmenu-xs');
        }
        else {
          $app.addClass('leftmenu-sm');
        }

        $app.removeClass('leftmenu-sm-forced leftmenu-xs-forced');
        $app.parent().removeClass('leftmenu-sm leftmenu-xs');
        oakleaf.navbar.removeRipple;
        $window.trigger('resize');
        e.preventDefault();
      });
    },

    offcanvas: function() {
      $offcanvasToggleEl.on('click', function(e) {
        if ($app.hasClass('offcanvas-opened')) {
          $app.removeClass('offcanvas-opened');
        } else {
          $app.addClass('offcanvas-opened');
        }
        e.preventDefault();
      });
    }


  };
  

  // boxss functions

  oakleaf.boxss = {

    init: function() {
      oakleaf.boxss.toggle();
      oakleaf.boxss.refresh();
      oakleaf.boxss.fullscreen();
      oakleaf.boxss.close();
    },

    toggle: function() {
      $boxsToggleEl.on('click', function(){
        var element = $(this);
        var boxs = element.parents('.boxs');

        boxs.toggleClass('collapsed');
        boxs.children().not('.boxs-header').slideToggle(150);
      });
    },

    refresh: function() {
      $boxsRefreshEl.on('click', function(){
        var element = $(this);
        var boxs = element.parents('.boxs');
        var dropdown = element.parents('.dropdown');

        boxs.addClass('refreshing');
        dropdown.trigger('click');

        var t = setTimeout( function(){
          boxs.removeClass('refreshing');
        }, 3000 );
      });
    },

    fullscreen: function() {
      $boxsFullscreenEl.on('click', function(){
        var element = $(this);
        var boxs = element.parents('.boxs');
        var dropdown = element.parents('.dropdown');

        screenfull.toggle(boxs[0]);
        dropdown.trigger('click');
      });

      if ($boxsFullscreenEl.length > 0) {
        $(document).on(screenfull.raw.fullscreenchange, function () {
          var element = $(screenfull.element);
          if (screenfull.isFullscreen) {
            element.addClass('isInFullScreen');
          } else {
            $('.boxs.isInFullScreen').removeClass('isInFullScreen');
          }
        });
      }
    },

    close: function() {
      $boxsCloseEl.on('click', function(){
        var element = $(this);
        var boxs = element.parents('.boxs');

        boxs.addClass('closed').fadeOut();
      });
    }

  };

  // extra functions

  oakleaf.extra = {

    init: function() {
      oakleaf.extra.sparklineChart();
      oakleaf.extra.slimScroll();
      oakleaf.extra.daterangePicker();
      oakleaf.extra.easypiechart();
      oakleaf.extra.chosen();
      oakleaf.extra.toggleClass();
      oakleaf.extra.colorpicker();
      oakleaf.extra.touchspin();
      oakleaf.extra.datepicker();
      oakleaf.extra.animateProgress();
      oakleaf.extra.counter();
      oakleaf.extra.popover();
      oakleaf.extra.tooltip();
      oakleaf.extra.splash();
      oakleaf.extra.lightbox();
    },

    //initialize sparkline chart on elements
    sparklineChart: function(){

      if( $sparklineEl.length > 0 ){
        $sparklineEl.each(function() {
          var element = $(this);

          element.sparkline('html', { enableTagOptions: true });
        });
      }

    },

    //initialize slimscroll on elements
    slimScroll: function(){

      if( $slimScrollEl.length > 0 ){
        $slimScrollEl.each(function() {
          var element = $(this);

          element.slimScroll({height: '100%'});
        });
      }

    },

    //initialize date range picker on elements
    daterangePicker: function() {

      if ($pickDateEl.length > 0) {
        $pickDateEl.each(function() {
          var element = $(this);

          element.find('span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

          element.daterangepicker({
            format: 'MM/DD/YYYY',
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2015',
            dateLimit: { days: 60 },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'left',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-success',
            cancelClass: 'btn-default',
            separator: ' to ',
            locale: {
              applyLabel: 'Submit',
              cancelLabel: 'Cancel',
              fromLabel: 'From',
              toLabel: 'To',
              customRangeLabel: 'Custom',
              daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
              monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
              firstDay: 1
            }
          }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
            element.find('span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          });

        });
      }

    },
    
    toggleClass: function() {
      $toggleClassEl.on('click', function(){
        var element = $(this),
            className = element.data('toggle'),
            type = element.data('type');

        if (type === 'radio') {
          element.parent().find('.'+className).removeClass(className);
        }

        if (element.hasClass(className)) {
          element.removeClass(className);
        } else {
          element.addClass(className);
        }
      });
    },

    colorpicker: function() {
      if ($colorPickerEl.length > 0) {
        $colorPickerEl.each(function() {
          var element = $(this);
          element.colorpicker();
        });
      }
    },
    easypiechart: function() {
      if ($easypiechartEl.length > 0) {
        $easypiechartEl.each(function() {
          var element = $(this);
          element.easyPieChart({
            onStart: function(value) {
              if (element.hasClass('animate')) {
                $(this.el).find('span').countTo({to: value});
              }
            }
          });
        });
      }
    },

    chosen: function() {
      if ($chosenEl.length > 0) {
        $chosenEl.each(function() {
          var element = $(this);
          element.on('chosen:ready', function(e, chosen) {
            var width = element.css("width");
            element.next().find('.chosen-choices').addClass('form-control');
            element.next().css("width", width);
            element.next().find('.search-field input').css("width", "125px");
          }).chosen();
        });
      }
    },


    

    datepicker: function() {
      if ($datepickerEl.length > 0) {
        $datepickerEl.each(function() {
          var element = $(this);
          var format = element.data('format')
          element.datetimepicker({
            format: format
          });
        });
      }
    },
    
    touchspin: function() {
      if ($touchspinEl.length > 0) {
        $touchspinEl.each(function() {
          var element = $(this);
          element.TouchSpin();
        });
      }
    },

    animateProgress: function() {
      if ($animateProgressEl.length > 0) {
        $animateProgressEl.each(function() {
          var element = $(this);
          var progress =  element.data('percentage');

          element.css('width', progress);
        });
      }
    },    

    popover: function() {
      $popoverEl = $('[data-toggle="popover"]');
      if ($popoverEl.length > 0) {
        $popoverEl.each(function() {
          var element = $(this);

          element.popover();
        });
      }
    },

    tooltip: function() {
      $tooltipEl = $('[data-toggle="tooltip"]');
      if ($tooltipEl.length > 0){
        $tooltipEl.each(function() {
          var element = $(this);

          element.tooltip();
        });
      }
    },
    counter: function(){
			if ($counterEl.length > 0) {
        $counterEl.each(function() {
          var element = $(this);

          element.countTo();
        });
      }
		},

    splash: function() {
      var options = "";
      var target = "";
      $splashEl.on('show.bs.modal', function (e) {
        options = e.relatedTarget.dataset.options;
        target = $(e.target);

        target.addClass(options);
        $body.addClass(options).addClass('splash');
      });
      $splashEl.on('hidden.bs.modal', function () {
        target.removeClass(options);
        $body.removeClass(options).removeClass('splash');
      });
    },

    //initialize magnificPopup lightbox
    lightbox: function(){
			var $lightboxImageEl = $('[data-lightbox="image"]'),
          $lightboxIframeEl = $('[data-lightbox="iframe"]'),
          $lightboxGalleryEl = $('[data-lightbox="gallery"]');

			if( $lightboxImageEl.length > 0 ) {
				$lightboxImageEl.magnificPopup({
					type: 'image',
					closeOnContentClick: true,
					closeBtnInside: false,
					fixedContentPos: true,
					image: {
						verticalFit: true
					}
				});
			}

      if( $lightboxIframeEl.length > 0 ) {
				$lightboxIframeEl.magnificPopup({
					disableOn: 600,
					type: 'iframe',
					removalDelay: 160,
					preloader: false,
					fixedContentPos: false
				});
			}

			if( $lightboxGalleryEl.length > 0 ) {
				$lightboxGalleryEl.each(function() {
					var element = $(this);

					if( element.find('a[data-lightbox="gallery-item"]').parent('.clone').hasClass('clone') ) {
						element.find('a[data-lightbox="gallery-item"]').parent('.clone').find('a[data-lightbox="gallery-item"]').attr('data-lightbox','');
					}

					element.magnificPopup({
						delegate: 'a[data-lightbox="gallery-item"]',
						type: 'image',
						closeOnContentClick: true,
						closeBtnInside: false,
						fixedContentPos: true,
						image: {
							verticalFit: true
						},
						gallery: {
							enabled: true,
							navigateByImgClick: true,
							preload: [0,1] // Will preload 0 - before current, and 1 after the current image
						}
					});
				});
			}
		}

  };


  // check mobile device

  oakleaf.isMobile = {
    Android: function() {
      return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
      return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
      return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
      return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
      return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
      return (oakleaf.isMobile.Android() || oakleaf.isMobile.BlackBerry() || oakleaf.isMobile.iOS() || oakleaf.isMobile.Opera() || oakleaf.isMobile.Windows());
    }
  };



  // initialize after resize

  oakleaf.documentOnResize = {

		init: function(){

      var t = setTimeout( function(){

        oakleaf.documentOnReady.setSidebar();
        oakleaf.navbar.removeRipple();

			}, 500 );

		}

	};

  // initialize when document ready

  oakleaf.documentOnReady = {

		init: function(){
      oakleaf.global.init();
			oakleaf.header.init();
      oakleaf.navbar.init();
      oakleaf.documentOnReady.windowscroll();
      oakleaf.boxss.init();
      oakleaf.extra.init();
      oakleaf.documentOnReady.setSidebar();
		},

    // run on window scrolling

    windowscroll: function(){

			$window.on( 'scroll', function(){


			});
		},


    setSidebar: function() {
      width = $window.width();
      if (width < 992) {
        $app.addClass('leftmenu-sm');
      }
      if (width < 768) {
        $app.removeClass('leftmenu-sm').addClass('leftmenu-xs');
      }
      if ($app.hasClass('leftmenu-sm-forced')) {
        $app.addClass('leftmenu-sm');
      }
      if ($app.hasClass('leftmenu-xs-forced')) {
        $app.addClass('leftmenu-xs');
      }
    }
	};

  // initialize when document load

	oakleaf.documentOnLoad = {
		init: function(){
		}
	};


    // material Kit JS ============================================================================
    
   var transparent = true;
    var transparentDemo = true;
    var fixedTop = false;    
    var navbar_initialized = false;    
    $(document).ready(function(){    
        // Init Material scripts for buttons ripples, inputs animations etc, more info on the next link https://github.com/FezVrasta/bootstrap-material-design#materialjs
        $.material.init();        
        //  Activate the Tooltips
        $('[data-toggle="tooltip"], [rel="tooltip"]').tooltip();    
        // Activate Datepicker
        if($('.m-datepicker').length != 0){
            $('.m-datepicker').datepicker({
                 weekStart:1
            });
        }    
        // Activate Popovers
        $('[data-toggle="popover"]').popover();    
        // Active Carousel
        $('.carousel').carousel({
          interval: 400000
        });    
    });    
    materialKit = {
        misc:{
            navbar_menu_visible: 0
        },    
        checkScrollForTransparentNavbar: debounce(function() {
                if($(document).scrollTop() > 260 ) {
                    if(transparent) {
                        transparent = false;
                        $('.navbar-color-on-scroll').removeClass('navbar-transparent');
                    }
                } else {
                    if( !transparent ) {
                        transparent = true;
                        $('.navbar-color-on-scroll').addClass('navbar-transparent');
                    }
                }
        }, 17),    
        initSliders: function(){
            // Sliders for demo purpose
            $('#sliderRegular').noUiSlider({
                start: 40,
                connect: "lower",
                range: {
                    min: 0,
                    max: 100
                }
            });    
            $('#sliderDouble').noUiSlider({
                start: [20, 60] ,
                connect: true,
                range: {
                    min: 0,
                    max: 100
                }
            });
        }
    }
    var big_image;    
    materialKitDemo = {
        checkScrollForParallax: debounce(function(){
            var current_scroll = $(this).scrollTop();
    
            oVal = ($(window).scrollTop() / 3);
            big_image.css({
                'transform':'translate3d(0,' + oVal +'px,0)',
                '-webkit-transform':'translate3d(0,' + oVal +'px,0)',
                '-ms-transform':'translate3d(0,' + oVal +'px,0)',
                '-o-transform':'translate3d(0,' + oVal +'px,0)'
            });    
        }, 6)    
    }
    // Returns a function, that, as long as it continues to be invoked, will not
    // be triggered. The function will be called after it stops being called for
    // N milliseconds. If `immediate` is passed, trigger the function on the
    // leading edge, instead of the trailing.    
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            }, wait);
            if (immediate && !timeout) func.apply(context, args);
        };
    };
    
    //  End material Kit JS ============================================================================

  // global variables

  var $window = $(window),
      $body = $('body'),
      $header = $('#header'),
      $branding = $('#header .branding'),
      $leftmenu = $('#leftmenu'),
      $controls = $('#controls'),
      $app = $('.main_Wrapper'),
      $navigation = $('#navigation'),
      $sparklineEl = $('.sparklineChart'),
      $slimScrollEl = $('.slim-scroll'),
      $collapseSidebarEl = $('.collapse-leftmenu'),
      $wrap = $('#wrap'),
      $offcanvasToggleEl = $('.offcanvas-toggle'),

      //navigation elements
      $dropdowns = $navigation.find('ul').parent('li'),
      $a = $dropdowns.children('a'),
      $notDropdowns = $navigation.children('li').not($dropdowns),
      $notDropdownsLinks = $notDropdowns.children('a'),
      // end of navuigation elements

      $headerthemeEl = $('.color-themes .header-theme'),
      $brandingthemeEl = $('.color-themes .branding-theme'),
      $leftmenuthemeEl = $('.color-themes .leftmenu-theme'),
      $colorthemeEl = $('.color-themes .color-theme'),
      $fixedHeaderEl = $('#fixed-header'),
      $fixedAsideEl = $('#fixed-aside'),
      $toggleRightbarEl = $('.toggle-right-leftmenu'),
      $pickDateEl = $('.pickDate'),

      $boxsEl = $('.boxs'),
      $boxsToggleEl = $('.boxs .boxs-toggle'),
      $boxsRefreshEl = $('.boxs .boxs-refresh'),
      $boxsFullscreenEl = $('.boxs .boxs-fullscreen'),
      $boxsCloseEl = $('.boxs .boxs-close'),

      $easypiechartEl = $('.easypiechart'),
      $chosenEl = $('.chosen-select'),
      $toggleClassEl = $('.toggle-class'),
      $colorPickerEl = $('.colorpicker'),
      $touchspinEl = $('.touchspin'),
      $datepickerEl = $('.datepicker'),
      $animateProgressEl = $('.animate-progress-bar'),
      $counterEl = $('.counter'),
      $splashEl = $('.splash');


  // initializing
  $(document).ready( oakleaf.documentOnReady.init );
  $window.load( oakleaf.documentOnLoad.init );
  $window.on( 'resize', oakleaf.documentOnResize.init );

});

!function(a,b,c){"object"==typeof module&&module&&"object"==typeof module.exports?module.exports=c:(a[b]=c,"function"==typeof define&&define.amd&&define(b,[],function(){return c}))}(this,"jRespond",function(a,b,c){"use strict";return function(a){var b=[],d=[],e=a,f="",g="",h=0,i=100,j=500,k=j,l=function(){var a=0;return a="number"!=typeof window.innerWidth?0!==document.documentElement.clientWidth?document.documentElement.clientWidth:document.body.clientWidth:window.innerWidth},m=function(a){if(a.length===c)n(a);else for(var b=0;b<a.length;b++)n(a[b])},n=function(a){var e=a.breakpoint,h=a.enter||c;b.push(a),d.push(!1),q(e)&&(h!==c&&h.call(null,{entering:f,exiting:g}),d[b.length-1]=!0)},o=function(){for(var a=[],e=[],h=0;h<b.length;h++){var i=b[h].breakpoint,j=b[h].enter||c,k=b[h].exit||c;"*"===i?(j!==c&&a.push(j),k!==c&&e.push(k)):q(i)?(j===c||d[h]||a.push(j),d[h]=!0):(k!==c&&d[h]&&e.push(k),d[h]=!1)}for(var l={entering:f,exiting:g},m=0;m<e.length;m++)e[m].call(null,l);for(var n=0;n<a.length;n++)a[n].call(null,l)},p=function(a){for(var b=!1,c=0;c<e.length;c++)if(a>=e[c].enter&&a<=e[c].exit){b=!0;break}b&&f!==e[c].label?(g=f,f=e[c].label,o()):b||""===f||(f="",o())},q=function(a){if("object"==typeof a){if(a.join().indexOf(f)>=0)return!0}else{if("*"===a)return!0;if("string"==typeof a&&f===a)return!0}},r=function(){var a=l();a!==h?(k=i,p(a)):k=j,h=a,setTimeout(r,k)};return r(),{addFunc:function(a){m(a)},getBreakpoint:function(){return f}}}}(this,this.document)),function(a){jQuery.fn.extend({slimScroll:function(b){var c=a.extend({width:"auto",height:"250px",size:"7px",color:"#000",position:"right",distance:"1px",start:"top",opacity:.4,alwaysVisible:!1,disableFadeOut:!1,railVisible:!1,railColor:"#333",railOpacity:.2,railDraggable:!0,railClass:"slimScrollRail",barClass:"slimScrollBar",wrapperClass:"slimScrollDiv",allowPageScroll:!1,wheelStep:20,touchScrollStep:200,borderRadius:"7px",railBorderRadius:"7px"},b);return this.each(function(){function d(b){if(j){b=b||window.event;var d=0;b.wheelDelta&&(d=-b.wheelDelta/120),b.detail&&(d=b.detail/3),a(b.target||b.srcTarget||b.srcElement).closest("."+c.wrapperClass).is(u.parent())&&e(d,!0),b.preventDefault&&!s&&b.preventDefault(),s||(b.returnValue=!1)}}function e(a,b,d){s=!1;var e=a,f=u.outerHeight()-w.outerHeight();b&&(e=parseInt(w.css("top"))+a*parseInt(c.wheelStep)/100*w.outerHeight(),e=Math.min(Math.max(e,0),f),e=0<a?Math.ceil(e):Math.floor(e),w.css({top:e+"px"})),p=parseInt(w.css("top"))/(u.outerHeight()-w.outerHeight()),e=p*(u[0].scrollHeight-u.outerHeight()),d&&(e=a,a=e/u[0].scrollHeight*u.outerHeight(),a=Math.min(Math.max(a,0),f),w.css({top:a+"px"})),u.scrollTop(e),u.trigger("slimscrolling",~~e),h(),i()}function f(){window.addEventListener?(this.addEventListener("DOMMouseScroll",d,!1),this.addEventListener("mousewheel",d,!1),this.addEventListener("MozMouseoakleafScroll",d,!1)):document.attachEvent("onmousewheel",d)}function g(){o=Math.max(u.outerHeight()/u[0].scrollHeight*u.outerHeight(),r),w.css({height:o+"px"});var a=o==u.outerHeight()?"none":"block";w.css({display:a})}function h(){g(),clearTimeout(m),p==~~p?(s=c.allowPageScroll,q!=p&&u.trigger("slimscroll",0==~~p?"top":"bottom")):s=!1,q=p,o>=u.outerHeight()?s=!0:(w.stop(!0,!0).fadeIn("fast"),c.railVisible&&x.stop(!0,!0).fadeIn("fast"))}function i(){c.alwaysVisible||(m=setTimeout(function(){c.disableFadeOut&&j||k||l||(w.fadeOut("slow"),x.fadeOut("slow"))},1e3))}var j,k,l,m,n,o,p,q,r=30,s=!1,u=a(this);if(u.parent().hasClass(c.wrapperClass)){var v=u.scrollTop(),w=u.parent().find("."+c.barClass),x=u.parent().find("."+c.railClass);if(g(),a.isPlainObject(b)){if("height"in b&&"auto"==b.height){u.parent().css("height","auto"),u.css("height","auto");var y=u.parent().parent().height();u.parent().css("height",y),u.css("height",y)}if("scrollTo"in b)v=parseInt(c.scrollTo);else if("scrollBy"in b)v+=parseInt(c.scrollBy);else if("destroy"in b)return w.remove(),x.remove(),void u.unwrap();e(v,!1,!0)}}else{c.height="auto"==c.height?u.parent().height():c.height,v=a("<div></div>").addClass(c.wrapperClass).css({position:"relative",overflow:"hidden",width:c.width,height:c.height}),u.css({overflow:"hidden",width:c.width,height:c.height});var x=a("<div></div>").addClass(c.railClass).css({width:c.size,height:"100%",position:"absolute",top:0,display:c.alwaysVisible&&c.railVisible?"block":"none","border-radius":c.railBorderRadius,background:c.railColor,opacity:c.railOpacity,zIndex:90}),w=a("<div></div>").addClass(c.barClass).css({background:c.color,width:c.size,position:"absolute",top:0,opacity:c.opacity,display:c.alwaysVisible?"block":"none","border-radius":c.borderRadius,BorderRadius:c.borderRadius,MozBorderRadius:c.borderRadius,WebkitBorderRadius:c.borderRadius,zIndex:99}),y="right"==c.position?{right:c.distance}:{left:c.distance};x.css(y),w.css(y),u.wrap(v),u.parent().append(w),u.parent().append(x),c.railDraggable&&w.bind("mousedown",function(b){var c=a(document);return l=!0,t=parseFloat(w.css("top")),pageY=b.pageY,c.bind("mousemove.slimscroll",function(a){currTop=t+a.pageY-pageY,w.css("top",currTop),e(0,w.position().top,!1)}),c.bind("mouseup.slimscroll",function(a){l=!1,i(),c.unbind(".slimscroll")}),!1}).bind("selectstart.slimscroll",function(a){return a.stopPropagation(),a.preventDefault(),!1}),x.hover(function(){h()},function(){i()}),w.hover(function(){k=!0},function(){k=!1}),u.hover(function(){j=!0,h(),i()},function(){j=!1,i()}),u.bind("touchstart",function(a,b){a.originalEvent.touches.length&&(n=a.originalEvent.touches[0].pageY)}),u.bind("touchmove",function(a){s||a.originalEvent.preventDefault(),a.originalEvent.touches.length&&(e((n-a.originalEvent.touches[0].pageY)/c.touchScrollStep,!0),n=a.originalEvent.touches[0].pageY)}),g(),"bottom"===c.start?(w.css({top:u.outerHeight()-w.outerHeight()}),e(0,!0)):"top"!==c.start&&(e(a(c.start).position().top,null,!0),c.alwaysVisible||w.hide()),f()}}),this}}),jQuery.fn.extend({slimscroll:jQuery.fn.slimScroll})}(jQuery),!function(a){"use strict";var b="animsition",c={init:function(d){d=a.extend({inClass:"fade-in",outClass:"fade-out",inDuration:1500,outDuration:800,linkElement:".animsition-link",loading:!0,loadingParentElement:"body",loadingClass:"animsition-loading",unSupportCss:["animation-duration","-webkit-animation-duration","-o-animation-duration"],overlay:!1,overlayClass:"animsition-overlay-slide",overlayParentElement:"body"},d);var e=c.supportCheck.call(this,d);if(!e)return"console"in window||(window.console={},window.console.log=function(a){return a}),console.log("Animsition does not support this browser."),c.destroy.call(this);var f=c.optionCheck.call(this,d);return f&&c.addOverlay.call(this,d),d.loading&&c.addLoading.call(this,d),this.each(function(){var e=this,f=a(this),g=a(window),h=f.data(b);h||(d=a.extend({},d),f.data(b,{options:d}),g.on("load."+b+" pageshow."+b,function(){c.pageIn.call(e)}),g.on("unload."+b,function(){}),a(d.linkElement).on("click."+b,function(b){b.preventDefault();var d=a(this),f=d.attr("href");2===b.which||b.metaKey||b.shiftKey||-1!==navigator.platform.toUpperCase().indexOf("WIN")&&b.ctrlKey?window.open(f,"_blank"):c.pageOut.call(e,d,f)}))})},addOverlay:function(b){a(b.overlayParentElement).prepend('<div class="'+b.overlayClass+'"></div>')},addLoading:function(b){a(b.loadingParentElement).append('<div class="'+b.loadingClass+'"></div>')},removeLoading:function(){var c=a(this),d=c.data(b).options,e=a(d.loadingParentElement).children("."+d.loadingClass);e.fadeOut().remove()},supportCheck:function(b){var c=a(this),d=b.unSupportCss,e=d.length,f=!1;0===e&&(f=!0);for(var g=0;e>g;g++)if("string"==typeof c.css(d[g])){f=!0;break}return f},optionCheck:function(b){var c,d=a(this);return c=!(!b.overlay&&!d.data("animsition-overlay"))},animationCheck:function(c,d,e){var f=a(this),g=f.data(b).options,h=typeof c,i=!d&&"number"===h,j=d&&"string"===h&&c.length>0;return i||j?c=c:d&&e?c=g.inClass:!d&&e?c=g.inDuration:d&&!e?c=g.outClass:d||e||(c=g.outDuration),c},pageIn:function(){var d=this,e=a(this),f=e.data(b).options,g=e.data("animsition-in-duration"),h=e.data("animsition-in"),i=c.animationCheck.call(d,g,!1,!0),j=c.animationCheck.call(d,h,!0,!0),k=c.optionCheck.call(d,f);f.loading&&c.removeLoading.call(d),k?c.pageInOverlay.call(d,j,i):c.pageInBasic.call(d,j,i)},pageInBasic:function(b,c){var d=a(this);d.trigger("animsition.start").css({"animation-duration":c/1e3+"s"}).addClass(b).animateCallback(function(){d.removeClass(b).css({opacity:1}).trigger("animsition.end")})},pageInOverlay:function(c,d){var e=a(this),f=e.data(b).options;e.trigger("animsition.start").css({opacity:1}),a(f.overlayParentElement).children("."+f.overlayClass).css({"animation-duration":d/1e3+"s"}).addClass(c).animateCallback(function(){e.trigger("animsition.end")})},pageOut:function(d,e){var f=this,g=a(this),h=g.data(b).options,i=d.data("animsition-out"),j=g.data("animsition-out"),k=d.data("animsition-out-duration"),l=g.data("animsition-out-duration"),m=i?i:j,n=k?k:l,o=c.animationCheck.call(f,m,!0,!1),p=c.animationCheck.call(f,n,!1,!1),q=c.optionCheck.call(f,h);q?c.pageOutOverlay.call(f,o,p,e):c.pageOutBasic.call(f,o,p,e)},pageOutBasic:function(b,c,d){var e=a(this);e.css({"animation-duration":c/1e3+"s"}).addClass(b).animateCallback(function(){location.href=d})},pageOutOverlay:function(d,e,f){var g=this,h=a(this),i=h.data(b).options,j=h.data("animsition-in"),k=c.animationCheck.call(g,j,!0,!0);a(i.overlayParentElement).children("."+i.overlayClass).css({"animation-duration":e/1e3+"s"}).removeClass(k).addClass(d).animateCallback(function(){location.href=f})},destroy:function(){return this.each(function(){var c=a(this);a(window).unbind("."+b),c.css({opacity:1}).removeData(b)})}};a.fn.animateCallback=function(b){var c="animationend webkitAnimationEnd mozAnimationEnd oAnimationEnd MSAnimationEnd";return this.each(function(){a(this).bind(c,function(){return a(this).unbind(c),b.call(this)})})},a.fn.animsition=function(d){return c[d]?c[d].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof d&&d?void a.error("Method "+d+" does not exist on jQuery."+b):c.init.apply(this,arguments)}}(jQuery);
//# sourceMappingURL=all.js.map
