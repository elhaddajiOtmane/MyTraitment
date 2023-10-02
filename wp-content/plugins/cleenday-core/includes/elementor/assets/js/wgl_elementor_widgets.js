(function ($) {
    "use strict";

    jQuery(window).on('elementor/frontend/init', function () {
        if (window.elementorFrontend.isEditMode()) {
            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-blog.default',
                function ($scope) {
                    cleenday_parallax_video();
                    cleenday_blog_masonry_init();
                    cleenday_carousel_slick();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-carousel.default',
                function ($scope) {
                    cleenday_carousel_slick();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-portfolio.default',
                function ($scope) {
                    cleenday_isotope();
                    cleenday_carousel_slick();
                    cleenday_scroll_animation();
                    cleenday_filter_swiper();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-gallery.default',
                function ($scope) {
                    cleenday_images_gallery();
                    cleenday_carousel_slick();
                    cleenday_scroll_animation();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-progress-bar.default',
                function ($scope) {
                    cleenday_progress_bars_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-testimonials.default',
                function ($scope) {
                    cleenday_carousel_slick();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-toggle-accordion.default',
                function ($scope) {
                    cleenday_accordion_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-accordion-service.default',
                function ($scope) {
                    cleenday_services_accordion_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-team.default',
                function ($scope) {
                    cleenday_isotope();
                    cleenday_carousel_slick();
                    cleenday_scroll_animation();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-tabs.default',
                function ($scope) {
                    cleenday_tabs_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-clients.default',
                function ($scope) {
                    cleenday_carousel_slick();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-image-layers.default',
                function ($scope) {
                    cleenday_img_layers();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-video-popup.default',
                function ($scope) {
                    cleenday_videobox_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-countdown.default',
                function ($scope) {
                    cleenday_countdown_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-time-line-vertical.default',
                function ($scope) {
                    cleenday_init_appear();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-image-hotspots.default',
                function ($scope) {
                    cleenday_init_appear();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-image-comparison.default',
                function ($scope) {
                    cleenday_image_comparison();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-circuit-service.default',
                function ($scope) {
                    cleenday_circuit_service();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-counter.default',
                function ($scope) {
                    cleenday_counter_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-menu.default',
                function ($scope) {
                    cleenday_menu_lavalamp();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-header-search.default',
                function ($scope) {
                    cleenday_search_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-header-side_panel.default',
                function ($scope) {
                    cleenday_side_panel_init();
                }
            );

            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/wgl-products-grid.default',
                function ($scope) {
                    cleenday_isotope();
                    cleenday_carousel_slick();
                }
            );

        }
    });

})(jQuery);
