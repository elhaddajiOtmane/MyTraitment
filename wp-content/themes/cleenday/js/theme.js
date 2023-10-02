"use strict";

is_visible_init();
cleenday_slick_navigation_init();

jQuery(document).ready(function($) {
    cleenday_sticky_init();
    cleenday_search_init();
    cleenday_side_panel_init();
    cleenday_mobile_header();
    cleenday_woocommerce_helper();
    cleenday_woocommerce_login_in();
    cleenday_init_appear();
    cleenday_accordion_init();
    cleenday_services_accordion_init();
    cleenday_progress_bars_init();
    cleenday_carousel_slick();
    cleenday_image_comparison();
    cleenday_counter_init();
    cleenday_countdown_init();
    cleenday_img_layers();
    cleenday_page_title_parallax();
    cleenday_extended_parallax();
    cleenday_portfolio_parallax();
    cleenday_message_anim_init();
    cleenday_scroll_up();
    cleenday_link_scroll();
    cleenday_skrollr_init();
    cleenday_sticky_sidebar();
    cleenday_videobox_init();
    cleenday_parallax_video();
    cleenday_tabs_init();
    cleenday_circuit_service();
    cleenday_select_wrap();
    jQuery( '.wgl_module_title .carousel_arrows' ).cleenday_slick_navigation();
    jQuery( '.wgl-filter_wrapper .carousel_arrows' ).cleenday_slick_navigation();
    jQuery( '.wgl-products > .carousel_arrows' ).cleenday_slick_navigation();
    jQuery( '.cleenday_module_custom_image_cats > .carousel_arrows' ).cleenday_slick_navigation();
    cleenday_scroll_animation();
    cleenday_woocommerce_mini_cart();
    cleenday_text_background();
    cleenday_dynamic_styles();
    cleenday_dbl_heading();
    cleenday_filter_swiper();
});

jQuery(window).load(function () {
    cleenday_images_gallery();
    cleenday_isotope();
    cleenday_blog_masonry_init();
    setTimeout(function(){
        jQuery('#preloader-wrapper').fadeOut();
    },1100);

    cleenday_particles_custom();
    cleenday_particles_image_custom();
    cleenday_menu_lavalamp();
    jQuery(".wgl-currency-stripe_scrolling").each(function(){
        jQuery(this).simplemarquee({
            speed: 40,
            space: 0,
            handleHover: true,
            handleResize: true
        });
    })
});
