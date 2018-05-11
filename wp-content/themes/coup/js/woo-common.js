(function($) { 'use strict';

    var w=window,d=document,
    e=d.documentElement,
    g=d.getElementsByTagName('body')[0];

    var x=w.innerWidth||e.clientWidth||g.clientWidth, // Viewport Width
        y=w.innerHeight||e.clientHeight||g.clientHeight; // Viewport Height


    $(document).ready(function($){

        var x=w.innerWidth||e.clientWidth||g.clientWidth, // Viewport Width
        y=w.innerHeight||e.clientHeight||g.clientHeight; // Viewport Height

        // Global Vars

        var $window = $(window);
        var body = $('body');
        var mainHeader = $('#masthead');
        var sidebar = $('#secondary');
        var mainContent = $('#content');
        var primaryContent = $('#primary');


        // add .woocommerce-ordering to it

        //$('.woocommerce-ordering').prependTo('.shop-area');


        // woocommerce widget remove parenthesses

        if ($('.widget_rating_filter').length || $('.widget_layered_nav').length) {
            var wooStarLink = $('.widget_rating_filter li a, .widget_layered_nav li span');

            wooStarLink.each(function() {
                var val = $(this).html();
                val = val.replace(/\)|\(/g,"");
                $(this).html(val);
            });
        }

        // woocommerce single review meta remove parenthesses

        if ($('.single.woocommerce-page a.woocommerce-review-link').length) {
            var wooReviewLink = $('.single.woocommerce-page a.woocommerce-review-link');

            wooReviewLink.each(function() {
                var val = $(this).html();
                val = val.replace(/\)|\(/g,"");
                $(this).html(val);
            });
        };

        // open & close mini cart and shop sidebar

        $('.cart-touch .cart-contents').on('click touchend', function(){
            if ( x < 900 ) {
                return;

            } else if (body.hasClass('mini-cart-open')) {
                $('html').css('overflow', 'auto');
                body.toggleClass('mini-cart-open');
                return false;

            } else {
                $('html').css('overflow', 'hidden');
                body.toggleClass('mini-cart-open');
                return false;
            };
        });

        if ($('.cart-touch').length && x < 1200) {
            body.addClass('has-mini-cart');
        }

        // if clicked outside of modal, close modal

        $(window).on('click touchend', function(){
            if (body.hasClass('mini-cart-open')) {
                if (!event.target.closest('.mini-cart, .cart-touch') ) {
                    body.removeClass('mini-cart-open');
                    $('html').css('overflow', 'auto');
                }
            } else if (body.hasClass('open-shop-sidebar')) {
                if (!event.target.closest('.shop-sidebar, .shop-sidebar-button') ) {
                    body.removeClass('open-shop-sidebar');
                    $('html').css('overflow', 'auto');
                }
            }
        });

        // call for close on ESC

        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
               if(body.hasClass('quick-view-pop-up') || $( '.modal-container' ).length ){
                    body.removeClass('quick-view-pop-up');
                    productModalWrap.hide();
                    $( '.modal-container' ).remove();

                    return;
                };
            }
        });

        // Remove button for woo messages

        if(body.is('[class*="woocommerce"]')){
            var removeWooMsg = function(){
                var removeMsg = $('i.woo-msg-close');

                removeMsg.on('click', function(){
                    $(this).parent().fadeOut(300);
                });
            };

            removeWooMsg();

            $( document.body ).on( 'updated_cart_totals', function() {
                    removeWooMsg();
            } );
        }



        // on single page remodel .reviews_tab

        if ($('.single .reviews_tab').length) {
            var val = $('.single .reviews_tab a').html();
            var valNumber = val.match(/\d/g,"");
            val = val.replace(/\(([^)]+)\)/g,"");
            $('.single .reviews_tab a').html(val);
            $('<sup>' + valNumber + '</sup>').appendTo($('.single .reviews_tab a'));
        }


    }); // End Window Load

    $(window).resize(function(){

        var x=w.innerWidth||e.clientWidth||g.clientWidth, // Viewport Width
        y=w.innerHeight||e.clientHeight||g.clientHeight; // Viewport Height


    }); // End Window Resize

})(jQuery);
