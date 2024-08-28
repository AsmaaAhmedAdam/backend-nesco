(function($) {
    "use strict";

    $(document).ready(function() {
        /*---------------------------------------
        	curency and language js
        ----------------------------------------- */
        $(".current-currency").on("click", function() {
            $(".currency-toogle").slideToggle(400);
        });
        $(".current-lang").on("click", function() {
            $(".language-toogle").slideToggle(400);
        });

        /*---------------------------------------
        	price range ui slider js
        ----------------------------------------- */
        $("#price-range").slider({
            range: true,
            min: 1,
            max: 100,
            values: [10, 90],
            slide: function(event, ui) {
                $("#slidevalue").val("$" + ui.values[0] + " - $" + ui.values[1]);
            }
        });
        $("#slidevalue").val("$" + $("#price-range").slider("values", 0) +
            " - $" + $("#price-range").slider("values", 1));

        /*---------------------------------------
        	scroll to top
        ----------------------------------------- */
        $(window).scroll(function() {
            if ($(this).scrollTop() > 250) {
                $('.bstore-scrollertop').fadeIn();
            } else {
                $('.bstore-scrollertop').fadeOut();
            }
        });
        //Click event to scroll to top
        $('.bstore-scrollertop').on("click", function() {
            $('html, body').animate({ scrollTop: 0 }, 800);
            return false;
        });

        /*---------------------------------------
        	mobile menu
        ----------------------------------------- */
        $('.mobile-menu').meanmenu();

        /*---------------------------------------
        	new  product, sale product carousel
        ----------------------------------------- */
        $('.new-pro-carousel, .sale-carousel').owlCarousel({
            items: 2,
            rtl: true,
            itemsDesktop: [1199, 2],
            itemsDesktopSmall: [991, 1],
            itemsTablet: [767, 2],
            itemsMobile: [480, 1],
            autoPlay: false,
            navigation: true,
            pagination: false,
            navigationText: ['<i class="fa fa-angle-left owl-prev-icon"></i>', '<i class="fa fa-angle-right owl-next-icon"></i>']
        });

        /*---------------------------------------
        	featured  product, bestseller, carousel
        ----------------------------------------- */
        $('.feartured-carousel, .bestseller-carousel').owlCarousel({
            items: 5,
            rtl: true,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [991, 3],
            itemsTablet: [767, 2],
            itemsMobile: [480, 1],
            autoPlay: false,
            stopOnHover: false,
            navigation: true,
            pagination: false,
            navigationText: ['<i class="fa fa-angle-left owl-prev-icon"></i>', '<i class="fa fa-angle-right owl-next-icon"></i>']
        });

        /*---------------------------------------
        	related-product  carousel
        ----------------------------------------- */
        $('.related-product').owlCarousel({
            items: 4,
            rtl: true,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [991, 3],
            itemsTablet: [767, 2],
            itemsMobile: [480, 1],
            autoPlay: false,
            stopOnHover: false,
            navigation: true,
            pagination: false,
            navigationText: ['<i class="fa fa-angle-left owl-prev-icon"></i>', '<i class="fa fa-angle-right owl-next-icon"></i>']
        });

        /*---------------------------------------
        	latest news carousel
        ----------------------------------------- */
        $('.latest-news-carousel').owlCarousel({
            items: 4,
            rtl: true,
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [991, 3],
            itemsTablet: [767, 2],
            itemsMobile: [480, 1],
            autoPlay: false,
            stopOnHover: false,
            navigation: true,
            pagination: false,
            navigationText: ['<i class="fa fa-angle-left owl-prev-icon"></i>', '<i class="fa fa-angle-right owl-next-icon"></i>']
        });

        /*---------------------------------------
        	client carousel
        ----------------------------------------- */
        $('.client-carousel').owlCarousel({
            items: 6,
            rtl: true,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [991, 3],
            itemsTablet: [767, 2],
            itemsMobile: [480, 1],
            autoPlay: false,
            stopOnHover: false,
            navigation: true,
            pagination: false,
            navigationText: ['<i class="fa fa-angle-left owl-prev-icon"></i>', '<i class="fa fa-angle-right owl-next-icon"></i>']
        });
        /*---------------------------------------
        	home 2 left category menu
        ----------------------------------------- */
        $('.category-heading').on("click", function() {
            $('.category-menu-list').slideToggle(300);
        });

        /*---------------------------------------
        	home 2 new product, home 2 sale product carousel
        ----------------------------------------- */
        $('.home2-new-pro-carousel, .home2-sale-carousel').owlCarousel({
            items: 4,
            rtl: true,
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [991, 2],
            itemsTablet: [767, 2],
            itemsMobile: [480, 1],
            autoPlay: false,
            stopOnHover: false,
            navigation: true,
            pagination: false,
            navigationText: ['<i class="fa fa-angle-left owl-prev-icon"></i>', '<i class="fa fa-angle-right owl-next-icon"></i>']
        });

        /*---------------------------------------
        	sidebar best seller carousel
        ----------------------------------------- */
        $('.sidebar-best-seller-carousel').owlCarousel({
            items: 1,
            rtl: true,
            itemsDesktop: [1199, 1],
            itemsDesktopSmall: [991, 1],
            itemsTablet: [767, 1],
            itemsMobile: [480, 1],
            autoPlay: false,
            stopOnHover: false,
            navigation: true,
            pagination: false,
            navigationText: ['<i class="fa fa-angle-left owl-prev-icon"></i>', '<i class="fa fa-angle-right owl-next-icon"></i>']
        });

        /*---------------------------------------
        	tab product carousel	
        ----------------------------------------- */
        $('.tab-carousel-1, .tab-carousel-2, .tab-carousel-3').owlCarousel({
            items: 4,
            rtl: true,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [991, 3],
            itemsTablet: [767, 2],
            itemsMobile: [480, 1],
            autoPlay: false,
            stopOnHover: false,
            navigation: true,
            pagination: false,
            navigationText: ['<i class="fa fa-angle-left owl-prev-icon"></i>', '<i class="fa fa-angle-right owl-next-icon"></i>']
        });

        /*---------------------------------------
        	mainSlider
        ----------------------------------------- */
        $('#mainSlider').nivoSlider({
            controlNav: true,
            directionNav: false,
            pauseTime: 5000,
            nextText: '<div class="slider-bolut"></div>',
            prevText: '<div class="slider-bolut"></div>'

        });

        /*---------------------------------------
        	single product product thumbnail
        ----------------------------------------- */
        $('.bxslider').bxSlider({
            minSlides: 3,
            maxSlides: 3,
            slideWidth: 88,
            responsive: true,
            nextText: '<i class="fa fa-angle-left"></i>',
            prevText: '<i class="fa fa-angle-right"></i>'
        });

        /*---------------------------------------
        	francy box lightbox
        ----------------------------------------- */
        $(".fancybox").fancybox();



        /**********************
         * Contact Form
         ***********************/

        var $form = $('#contact-form');
        var $formMessages = $('.form__output');
        // Set up an event listener for the contact form.
        $form.submit(function(e) {
            // Stop the browser from submitting the form.
            e.preventDefault();

            // Serialize the form data.
            var formData = $(this).serialize();
            // Submit the form using AJAX.
            $.ajax({
                    type: 'POST',
                    url: $($form).attr('action'),
                    data: formData
                })
                .done(function(response) {
                    // Make sure that the formMessages div has the 'success' class.
                    $formMessages.removeClass('error');
                    $formMessages.addClass('success');

                    // Set the message text.
                    $formMessages.text(response);

                    // Clear the form.
                    $('#contact-form input,#contact-form textarea').val('');
                })
                .fail(function(data) {
                    // Make sure that the formMessages div has the 'error' class.
                    $formMessages.removeClass('success');
                    $formMessages.addClass('error');

                    // Set the message text.
                    if (data.responseText !== '') {
                        $formMessages.text(data.responseText);
                    } else {
                        $formMessages.text('Oops! An error occured and your message could not be sent.');
                    }
                });
        });

    });

})(jQuery);