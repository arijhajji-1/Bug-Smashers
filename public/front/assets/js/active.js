/*global jQuery */
(function($) {
    "use strict";

    /*===============================
        ----- JS Index -----

    01. Background Image JS
    02. Countdown JS
    03. Responsive Menu
    04. Magnific Popup JS
    05. Scroll top Button JS
    06. Ajax Contact Form JS
    07. Contact Map JS
    08. Shop Layout Change
    09. Product Quantity
    10. Checkout Page Checkbox Accordion

    ==================================*/

    jQuery(document).ready(function($) {

        /*--------------------------
            01. Background Image JS
        ---------------------------*/
        var bgSelector = $("[data-bg]");
        bgSelector.each(function(index, elem) {
            var element = $(elem),
                bgSource = element.data('bg');
            element.css('background-image', 'url(' + bgSource + ')');
        });

        /*-------------------------
          02. Countdown JS
        -----------------------------*/
        $(".ht-countdown").each(function(index, element) {
            var $element = $(element),
                $date = $element.data('date');

            $element.countdown($date, function(event) {
                var $this = $(this).html(event.strftime(''

                    +
                    '<div class="countdown-item"><span class="countdown-item__time">%D</span><span class="countdown-item__label">Days</span></div>' +
                    '<div class="countdown-item"><span class="countdown-item__time">%H</span><span class="countdown-item__label">Hours</span></div>' +
                    '<div class="countdown-item"><span class="countdown-item__time">%M</span><span class="countdown-item__label">Minutes</span></div>' +
                    '<div class="countdown-item"><span class="countdown-item__time">%S</span><span class="countdown-item__label">Seconds</span></div>'));
            });
        });

        /*------------------------------
          03. Responsive Menu JS
        --------------------------------*/
        $('.main-menu.nav').slicknav({
            appendTo: '.res-mobile-menu',
            closeOnClick: true,
            removeClasses: true,
            closedSymbol: '<i class="ion-plus"></i>',
            openedSymbol: '<i class="ion-minus"></i>'
        });

        var resCanvasWrapper = $(".off-canvas-menu");
        $(".btn-menu").on('click', function() {
            resCanvasWrapper.addClass('active');
            $("body").addClass('fix');
        });

        $(".off-canvas-overlay, .btn-close").on('click', function() {
            $(".off-canvas-wrapper").removeClass('active');
            $("body").removeClass('fix');
        });


        /*---------------------------
           04. Magnific Popup JS
        ------------------------------*/
        // For Video Popup
        var videopopup = $(".btn-video-popup");
        videopopup.magnificPopup({
            type: 'iframe',
            mainClass: 'ht-mfp zoom-animate',
            removalDelay: 800,
            closeBtnInside: false
        });

        // For Image Gallery Popup
        var imgGallery = $(".image-gallery");
        imgGallery.magnificPopup({
            delegate: '[data-mfp-src]',
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: false,
            mainClass: 'ht-mfp mfp-with-zoom mfp-img-mobile',
            image: {
                verticalFit: true
            },
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true,
                duration: 400,
                opener: function(element) {
                    return element.find('img');
                }
            }
        });

        // Custom Gallery on Button Click
        var galleryBtnPopup = $(".btn-gallery-popup");
        galleryBtnPopup.on('click', function(event) {
            event.preventDefault();

            var gallery = $(this).attr('href');

            $(gallery).magnificPopup({
                delegate: '[data-mfp-src]',
                type: 'image',
                closeOnContentClick: false,
                closeBtnInside: false,
                mainClass: 'ht-mfp zoom-animate mfp-img-mobile',
                removalDelay: 800,
                image: {
                    verticalFit: true
                },
                gallery: {
                    enabled: true
                }
            }).magnificPopup('open');
        });


        /*---------------------------
           05. Scroll top Button JS
        ------------------------------*/

        $(".btn-scroll-top").on('click', function() {
            $('html, body').animate({
                scrollTop: 0
            }, 1500);
        });


        /*--------------------------
          06. Ajax Contact Form JS
         ---------------------------*/
        const form = $('#contact-form'),
            formMessages = $('.form-message');

        $(form).submit(function(e) {
            e.preventDefault();
            var formData = form.serialize();
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: formData
            }).done(function(response) {
                // Make sure that the formMessages div has the 'success' class.
                $(formMessages).removeClass('alert alert-danger');
                $(formMessages).addClass('alert alert-success fade show');

                // Set the message text.
                formMessages.html("<button type='button' class='close' data-dismiss='alert'>&times;</button>");
                formMessages.append(response);

                // Clear the form.
                $('#contact-form input,#contact-form textarea').val('');
            }).fail(function(data) {
                // Make sure that the formMessages div has the 'error' class.
                $(formMessages).removeClass('alert alert-success');
                $(formMessages).addClass('alert alert-danger fade show');

                // Set the message text.
                if (data.responseText !== '') {
                    formMessages.html("<button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    formMessages.append(data.responseText);
                } else {
                    $(formMessages).text('Oops! An error occurred and your message could not be sent.');
                }
            });
        });

        /*-------------------------
          07. Contact Map JS
        -----------------------------*/
        var map_id = $('#map_content');
        if (map_id.length > 0) {
            var $lat = map_id.data('lat'),
                $lng = map_id.data('lng'),
                $zoom = map_id.data('zoom'),
                $maptitle = map_id.data('maptitle'),
                $mapaddress = map_id.data('mapaddress'),
                mymap = L.map('map_content').setView([$lat, $lng], $zoom);

            L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map',
                maxZoom: 14,
                minZoom: 2,
                id: 'mapbox.streets',
                scrollWheelZoom: false,
                accessToken: 'sk.eyJ1IjoicmFqdWh0IiwiYSI6ImNqdHk5dGdpYzJqM3A0NGxsYmI3NmhnN3EifQ.kNdHkgfVGmSz6XPmmfG02A'
            }).addTo(mymap);

            var marker = L.marker([$lat, $lng]).addTo(mymap);
            mymap.zoomControl.setPosition('bottomright');
            mymap.scrollWheelZoom.disable();
        }

        $("select").niceSelect();

        /*--------------------------
            08. Shop Layout Change
        -----------------------------*/
        var layoutSelector = $(".layout-switcher li"),
            areaWrap = $(".product-layout");

        layoutSelector.on('click', function() {
            var viewMode = $(this).data("layout");

            layoutSelector.removeClass('active');
            $(this).addClass('active');

            areaWrap.removeClass('layout-grid layout-list').addClass('layout-' + viewMode);
        });

        /*-----------------------------------
           09. Product Quantity
        -----------------------------------*/
        var proQty = $(".pro-qty");
        proQty.append('<a href="#" class="inc qty-btn">+</a>');
        proQty.append('<a href="#" class= "dec qty-btn">-</a>');
        $('.qty-btn').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var oldValue = $button.parent().find('input').val();
            if ($button.hasClass('inc')) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                // Don't allow decrementing below zero
                if (oldValue > 0) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 0;
                }
            }
            $button.parent().find('input').val(newVal);
        });

        /*--------------------------------------
          10. Checkout Page Checkbox Accordion
        ----------------------------------------*/
        $("#create_pwd").on("change", function() {
            $(".account-create").slideToggle("100");
        });

        $("#ship_to_different").on("change", function() {
            $(".ship-to-different").slideToggle("100");
        });


        /*--------------------------
            All Slider Activation
        ---------------------------*/

        // Hero Slider Js
        $(".slider-content-active").slick({
            slidesToShow: 1,
            dots: true,
            adaptiveHeight: true,
            nextArrow: '<button class="slick-next"><i class="ion-chevron-right"></i></button>',
            prevArrow: '<button class="slick-prev"><i class="ion-chevron-left"></i></button>',
            responsive: [{
                breakpoint: 992,
                settings: {
                    arrows: false
                }
            }, ]
        });

        // Best Product Slider Js
        $(".product-carousel").slick({
            slidesToShow: 5,
            slidesToScroll: 2,
            arrows: false,
            responsive: [{
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // Brand Logo Slider Js
        $(".brand-logo-content").slick({
            slidesToShow: 5,
            slidesToScroll: 2,
            arrows: false,
            infinite: true,
            responsive: [{
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 950,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // Product Details Thumbnail
        $(".product-thumbnail-slider").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            draggable: false,
            infinite: false,
            asNavFor: ".product-details-thumbnail-nav"
        });

        // Product Details Thumbnail
        $(".product-details-thumbnail-nav").slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: false,
            infinite: false,
            asNavFor: ".product-thumbnail-slider",
            focusOnSelect: true,
            responsive: [{
                breakpoint: 480,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            }]
        });

    }); //End Ready Function

    jQuery(window).on('scroll', function() {
        //Scroll top Hide Show
        if ($(window).scrollTop() >= 400) {
            $('.btn-scroll-top').addClass('show');
        } else {
            $('.btn-scroll-top').removeClass('show');
        }
    }); // End Scroll Function
}(jQuery));