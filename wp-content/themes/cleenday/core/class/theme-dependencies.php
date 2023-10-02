<?php

defined('ABSPATH') || exit;

if (!class_exists('Cleenday_Theme_Dependencies')) {
    /**
     * Require list of theme necessary files.
     *
     *
     * @category Class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Cleenday_Theme_Dependencies
    {
        public function __construct()
        {
            self::include_theme_essential_files();
            self::include_plugins_configurations();
        }

        public static function include_theme_essential_files()
        {
            /** Theme Global Functions */
            require_once get_theme_file_path('/core/class/theme-global-functions.php');

            /** Theme Helper */
            require_once get_theme_file_path('/core/class/theme-helper.php');

            /** Walker Comments */
            require_once get_theme_file_path('/core/class/walker-comment.php');

            /** Walker Mega Menu */
            require_once get_theme_file_path('/core/class/walker-mega-menu.php');

            /** Theme Cats Meta */
            require_once get_theme_file_path('/core/class/theme-cat-meta.php');

            /** Single Post */
            require_once get_theme_file_path('/core/class/single-post.php');

            /** Tinymce Icon */
            require_once get_theme_file_path('/core/class/tinymce-icon.php');

            /** Default Options */
            require_once get_theme_file_path('/core/includes/default-options.php');

            /** Metabox Configuration */
            require_once get_theme_file_path('/core/includes/metabox/metabox-config.php');

            /** Redux Configuration */
            require_once get_theme_file_path('/core/includes/redux/redux-config.php');

            /** Theme Global Variables */
            require_once get_theme_file_path('/core/class/theme-global-variables.php');

            /** Dynamic Styles */
            require_once get_theme_file_path('/core/class/dynamic-styles.php');

            /** Theme Support */
            require_once get_theme_file_path('/core/class/theme-support.php');

            /** TGM */
            require_once get_theme_file_path('/core/tgm/wgl-tgm.php');

            /** Theme Dashboard */
            require_once get_theme_file_path('/core/class/theme-panel.php');

            /** Theme Verify */
            require_once get_theme_file_path('/core/class/theme-verify.php');
        }

        public static function include_plugins_configurations()
        {
            /** Elementor Pro */
            if (class_exists('\ElementorPro\Modules\ThemeBuilder\Module')) {
                require_once get_theme_file_path('/core/class/theme-elementor-pro-support.php');
            }

            /** Woocommerce */
            if (class_exists('WooCommerce')) {
                require_once get_theme_file_path('/woocommerce/woocommerce-init.php');
            }
        }
    }

    new Cleenday_Theme_Dependencies();
}
