$(function() {
    'use strict';

    // Showing page loader
    $(window).load(function() {
        setTimeout(function() {
                $(".page_loader").fadeOut("fast");
            }, 100)
            //$('link[id="style_sheet"]').attr('href', 'css/skins/green-light.css');
            //$('.logo img').attr('src', 'img/logos/green-light-logo.png');


        if ($('body .filter-portfolio').length > 0) {
            $(function() {
                $('.filter-portfolio').filterizr({
                    delay: 0
                });
            });
            $('.filteriz-navigation li').on('click', function() {
                $('.filteriz-navigation .filtr').removeClass('active');
                $(this).addClass('active');
            });
        }
    });




    /*
    // Banner slider
    (function ($) {
        //Function to animate slider captions
        function doAnimations(elems) {
            //Cache the animationend event in a variable
            var animEndEv = 'webkitAnimationEnd animationend';
            elems.each(function () {
                var $this = $(this),
                    $animationType = $this.data('animation');
                $this.addClass($animationType).one(animEndEv, function () {
                    $this.removeClass($animationType);
                });
            });
        }

        //Variables on page load
        var $myCarousel = $('#carousel-example-generic')
        var $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animation ^= 'animated']");
        //Initialize carousel
        $myCarousel.carousel();

        //Animate captions in first slide on page load
        doAnimations($firstAnimatingElems);
        //Pause carousel
        $myCarousel.carousel('pause');
        //Other slides to be animated on carousel slide event
        $myCarousel.on('slide.bs.carousel', function (e) {
            var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
            doAnimations($animatingElems);
        });
        $('#carousel-example-generic').carousel({
            interval: 3000,
            pause: "false"
        });
    })(jQuery);
	
	*/


    // Counter
    function isCounterElementVisible($elementToBeChecked) {
        var TopView = $(window).scrollTop();
        var BotView = TopView + $(window).height();
        var TopElement = $elementToBeChecked.offset().top;
        var BotElement = TopElement + $elementToBeChecked.height();
        return ((BotElement <= BotView) && (TopElement >= TopView));
    }

    $(window).scroll(function() {
        $(".counter").each(function() {
            var isOnView = isCounterElementVisible($(this));
            if (isOnView && !$(this).hasClass('Starting')) {
                $(this).addClass('Starting');
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 3000,
                    easing: 'swing',
                    step: function(now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            }
        });
    });




    $(".range-slider-ui").each(function() {
        var minRangeValue = $(this).attr('data-min');
        var maxRangeValue = $(this).attr('data-max');
        var minName = $(this).attr('data-min-name');
        var maxName = $(this).attr('data-max-name');
        var unit = $(this).attr('data-unit');

        $(this).append("" +
            "<span class='min-value'></span> " +
            "<span class='max-value'></span>" +
            "<input class='current-min' type='hidden' name='" + minName + "'>" +
            "<input class='current-max' type='hidden' name='" + maxName + "'>"
        );
        $(this).slider({
            range: true,
            min: minRangeValue,
            max: maxRangeValue,
            values: [minRangeValue, maxRangeValue],
            slide: function(event, ui) {
                event = event;
                var currentMin = parseInt(ui.values[0]);
                var currentMax = parseFloat(ui.values[1]);
                $(this).children(".min-value").text(currentMin + " " + unit);
                $(this).children(".max-value").text(currentMax + " " + unit);
                $(this).children(".current-min").val(currentMin);
                $(this).children(".current-max").val(currentMax);
            }
        });

        var currentMin = parseInt($(this).slider("values", 0));
        var currentMax = parseFloat($(this).slider("values", 1));
        $(this).children(".min-value").text(currentMin + " " + unit);
        $(this).children(".max-value").text(currentMax + " " + unit);
        $(this).children(".current-min").val(currentMin);
        $(this).children(".current-max").val(currentMax);
    });

    // Select picket
    $('.selectpicker').selectpicker();

    // Search option's icon toggle
    $('.search-options-btn').click(function() {
        $('.search-section').toggleClass('show-search-area');
        $('.search-options-btn .fa').toggleClass('fa-chevron-down');
    });

    // Carousel with partner initialization
    (function() {
        $('#ourPartners').carousel({ interval: 3600 });
    }());

    (function() {
        $('.our-partners .item').each(function() {
            var itemToClone = $(this);
            for (var i = 1; i < 4; i++) {
                itemToClone = itemToClone.next();
                if (!itemToClone.length) {
                    itemToClone = $(this).siblings(':first');
                }
                itemToClone.children(':first-child').clone()
                    .addClass("cloneditem-" + (i))
                    .appendTo($(this));
            }
        });
    }());


    // Megamenu activation
    $(".megamenu").on("click", function(e) {
        e.stopPropagation();
    });

    // Dropdown activation
    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');


        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.dropdown-submenu .show').removeClass("show");
        });

        return false;
    });


    // Expending/Collapsing advance search content
    $('.show-more-options').click(function() {
        if ($(this).find('.fa').hasClass('fa-minus-circle')) {
            $(this).find('.fa').removeClass('fa-minus-circle');
            $(this).find('.fa').addClass('fa-plus-circle');
        } else {
            $(this).find('.fa').removeClass('fa-plus-circle');
            $(this).find('.fa').addClass('fa-minus-circle');
        }
    });

    var videoWidth = $('.sidebar-widget').width();
    var videoHeight = videoWidth * .61;
    $('.sidebar-widget iframe').css('height', videoHeight);


    // Full  Page Search Activation
    $(function() {
        $('a[href="#full-page-search"]').on('click', function(event) {
            event.preventDefault();
            $('#full-page-search').addClass('open');
            $('#full-page-search > form > input[type="search"]').focus();
        });

        $('#full-page-search, #full-page-search button.close').on('click keyup', function(event) {
            if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
                $(this).removeClass('open');
            }
        });
    });


    // Slick Sliders
    $('.slick-carousel').each(function() {
        var slider = $(this);
        $(this).slick({
            rtl: true,
            infinite: true,
            dots: false,
            arrows: false,
            centerMode: true,
            centerPadding: '0'
        });

        $(this).closest('.slick-slider-area').find('.slick-prev').on("click", function() {
            slider.slick('slickPrev');
        });
        $(this).closest('.slick-slider-area').find('.slick-next').on("click", function() {
            slider.slick('slickNext');
        });
    });





    // Filterizr initialization
    $(function() {
        //$('.filtr-container').filterizr();
    });

    function toggleChevron(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".fa")
            .toggleClass('fa-minus fa-plus');
    }

    $('.panel-group').on('shown.bs.collapse', toggleChevron);
    $('.panel-group').on('hidden.bs.collapse', toggleChevron);

    // Switching Color schema
    $('.color-plate').on('click', function() {
        var name = $(this).attr('data-color');
        $('link[id="style_sheet"]').attr('href', 'css/skins/' + name + '.css');
        if (name == 'default') {
            $('.logo img').attr('src', 'img/logos/logo.png');
        } else {
            $('.logo img').attr('src', 'img/logos/' + name + '-logo.png');
        }
    });

    $('.setting-button').on('click', function() {
        $('.option-panel').toggleClass('option-panel-collased');
    });
});

// mCustomScrollbar initialization
(function($) {
    $(window).resize(function() {
        $('#map').css('height', $(this).height() - 110);
        if ($(this).width() > 768) {
            $(".map-content-sidebar").mCustomScrollbar({ theme: "minimal-dark" });
            $('.map-content-sidebar').css('height', $(this).height() - 110);
        } else {
            $('.map-content-sidebar').mCustomScrollbar("destroy"); //destroy scrollbar
            $('.map-content-sidebar').css('height', '100%');
        }
    }).trigger("resize");
})(jQuery);