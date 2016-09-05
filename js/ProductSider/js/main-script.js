$(document).ready(function () {
    "use strict";

    singleProduct(); // Cloud Zoom

    /* FlexSlider */
    $('.flexslider').flexslider({
        animation: "slide",
        controlNav: false,
        prevText: "",
        nextText: "",
        start: function (slider) {
            $('body').removeClass('loading');
        }
    });

    /* Single Product Page */
    function singleProduct() {
        /* Product Images Carousel */
        $('#product-carousel').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            directionNav: false,
            slideshow: false,
            itemWidth: 120,
            itemMargin: 10,
            start: function (slider) {
                setActive($('#product-carousel li:first-child img'));
                slider.find('.right-arrow').click(function () {
                    slider.flexAnimate(slider.getTarget("next"));
                });

                slider.find('.left-arrow').click(function () {
                    slider.flexAnimate(slider.getTarget("prev"));
                });

                slider.find('img').click(function () {
                    var large = $(this).attr('data-large');
                    setActive($(this));
                    $('#product-slider img').fadeOut(300, changeImg(large, $('#product-slider img')));
                    $('#product-slider a.fullscreen-button').attr('href', large);
                });

                function changeImg(large, element) {
                    var element = element;
                    var large = large;
                    setTimeout(function () { startF() }, 300);
                    function startF() {
                        element.attr('src', large)
                        element.attr('data-large', large)
                        element.fadeIn(300);
                    }
                }

                function setActive(el) {
                    var element = el;
                    $('#product-carousel img').removeClass('active-item');
                    element.addClass('active-item');
                }
            }
        });

        /* FullScreen Button */
        $('a.fullscreen-button').click(function (e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $('#product-carousel a.fancybox[href="' + target + '"]').trigger('click');
        });

        /* Cloud Zoom */
        $(".cloud-zoom").imagezoomsl({
            zoomrange: [3, 3]
        });

        /* FancyBox */
        $(".fancybox").fancybox();
    }
});