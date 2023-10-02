<?php

defined('ABSPATH') || exit;

use Cleenday_Theme_Helper as Cleenday;
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;

/**
 * Cleenday Dynamic Styles
 *
 *
 * @package cleenday\core\class
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 * @version 1.0.3
 */
class Cleenday_Dynamic_Styles
{
    protected static $instance;

    private $theme_slug;
    private $template_directory_uri;
    private $use_minified;
    private $enqueued_stylesheets = [];
    private $header_page_id;

    public function __construct()
    {
        $this->theme_slug = $this->get_theme_slug();
        $this->template_directory_uri = get_template_directory_uri();
        $this->use_minified = Cleenday::get_option('use_minified') ? '.min' : '';
        $this->header_type = Cleenday::get_option('header_type');
        $this->gradient_enabled = Cleenday::get_mb_option('use-gradient', 'mb_page_colors_switch', 'custom');

        $this->enqueue_styles_and_scripts();
        $this->add_body_classes();
    }

    public function get_theme_slug()
    {
        return str_replace('-child', '', wp_get_theme()->get('TextDomain'));
    }

    public function enqueue_styles_and_scripts()
    {
        //* Elementor Compatibility
        add_action('wp_enqueue_scripts', [$this, 'get_elementor_css_theme_builder']);
        add_action('wp_enqueue_scripts', [$this, 'elementor_column_fix']);

        add_action('wp_enqueue_scripts', [$this, 'frontend_stylesheets']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_scripts']);

        add_action('admin_enqueue_scripts', [$this, 'admin_stylesheets']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
    }

    public function get_elementor_css_theme_builder()
    {
        $current_post_id = get_the_ID();
        $css_files = [];

        $locations[] = $this->get_elementor_css_cache_header();
        $locations[] = $this->get_elementor_css_cache_header_sticky();
        $locations[] = $this->get_elementor_css_cache_footer();
        $locations[] = $this->get_elementor_css_cache_side_panel();

        foreach ($locations as $location) {
            //* Don't enqueue current post here (let the preview/frontend components to handle it)
            if ($location && $current_post_id !== $location) {
                $css_file = new \Elementor\Core\Files\CSS\Post($location);
                $css_files[] = $css_file;
            }
        }

        if (!empty($css_files)) {
            \Elementor\Plugin::$instance->frontend->enqueue_styles();
            foreach ($css_files as $css_file) {
                $css_file->enqueue();
            }
        }
    }

    public function get_elementor_css_cache_header()
    {
        if (
            !apply_filters('cleenday/header/enable', true)
            || !class_exists('\Elementor\Core\Files\CSS\Post')
        ) {
            // Bailtout.
            return false;
        }

        if (
            $this->RWMB_is_active()
            && 'custom' === rwmb_meta('mb_customize_header_layout')
            && 'default' !== rwmb_meta('mb_header_content_type')
        ) {
            $this->header_type = 'custom';
            $this->header_page_id = rwmb_meta('mb_customize_header');
        } else {
            $this->header_page_id = Cleenday::get_option('header_page_select');
        }

        if ('custom' === $this->header_type) {
            return $this->multi_language_support($this->header_page_id, 'header');
        }
    }

    /**
     * @version 1.0.3
     */
    public function get_elementor_css_cache_header_sticky()
    {
        if (
            ! apply_filters( 'cleenday/header/enable', true )
            || 'custom' !== $this->header_type
            || ! class_exists( '\Elementor\Core\Files\CSS\Post' )
        ) {
            // Bailtout.
            return false;
        }

        $header_sticky_page_id = '';

        if (
            $this->RWMB_is_active()
            && 'custom' === rwmb_meta( 'mb_customize_header_layout' )
            && 'default' !== rwmb_meta( 'mb_sticky_header_content_type' )
        ) {
            $header_sticky_page_id = rwmb_meta( 'mb_customize_sticky_header' );
        } elseif ( Cleenday::get_option( 'header_sticky' ) ) {
            $header_sticky_page_id = Cleenday::get_option( 'header_sticky_page_select' );
        }

        return $this->multi_language_support( $header_sticky_page_id, 'header' );
    }

    public function get_elementor_css_cache_footer()
    {
        $footer = apply_filters('cleenday/footer/enable', true);
        $footer_switch = $footer['footer_switch'] ?? '';

        if (
            !$footer_switch
            || 'pages' !== Cleenday::get_mb_option('footer_content_type', 'mb_footer_switch', 'on')
            || !class_exists('\Elementor\Core\Files\CSS\Post')
        ) {
            // Bailout.
            return false;
        }

        $footer_page_id = Cleenday::get_mb_option('footer_page_select', 'mb_footer_switch', 'on');

        return $this->multi_language_support($footer_page_id, 'footer');
    }

    public function get_elementor_css_cache_side_panel()
    {
        if (
            !Cleenday::get_option('side_panel_enable')
            || 'pages' !== Cleenday::get_mb_option('side_panel_content_type', 'mb_customize_side_panel', 'custom')
            || !class_exists('\Elementor\Core\Files\CSS\Post')
        ) {
            // Bailout.
            return false;
        }

        $sp_page_id = Cleenday::get_mb_option('side_panel_page_select', 'mb_customize_side_panel', 'custom');

        return $this->multi_language_support($sp_page_id, 'side-panel');
    }

    public function multi_language_support($page_id, $page_type)
    {
        if (!$page_id) {
            // Bailout.
            return false;
        }

        $page_id = intval($page_id);

        if (class_exists('Polylang') && function_exists('pll_current_language')) {
            $currentLanguage = pll_current_language();
            $translations = PLL()->model->post->get_translations($page_id);

            $polylang_id = $translations[$currentLanguage] ?? '';
            $page_id = $polylang_id ?: $page_id;
        }

        if (class_exists('SitePress')) {
            $page_id = wpml_object_id_filter($page_id, $page_type, false, ICL_LANGUAGE_CODE);
        }

        return $page_id;
    }

    public function elementor_column_fix()
    {
        $css = '.elementor-container > .elementor-row > .elementor-column > .elementor-element-populated,'
            . '.elementor-container > .elementor-column > .elementor-element-populated {'
                . 'padding-top: 0;'
                . 'padding-bottom: 0;'
            . '}';

        $css .= '.elementor-column-gap-default > .elementor-row > .elementor-column > .elementor-element-populated,'
            . '.elementor-column-gap-default > .elementor-column > .elementor-element-populated {'
                . 'padding-left: 15px;'
                . 'padding-right: 15px;'
            . '}';

        wp_add_inline_style('elementor-frontend', $css);
    }

    public function frontend_stylesheets()
    {
        wp_enqueue_style($this->theme_slug . '-theme-info', get_bloginfo('stylesheet_url'));

        $this->enqueue_css_variables();
        $this->enqueue_additional_styles();
        $this->enqueue_style('main', '/css/');
        $this->enqueue_pluggable_styles();
        $this->enqueue_style('responsive', '/css/', $this->enqueued_stylesheets);
        $this->enqueue_style('dynamic', '/css/', $this->enqueued_stylesheets);
    }

    public function enqueue_css_variables()
    {
        return wp_add_inline_style(
            $this->theme_slug . '-theme-info',
            $this->retrieve_css_variables_and_extra_styles()
        );
    }

    public function enqueue_additional_styles()
    {
        wp_enqueue_style('font-awesome-5-all', $this->template_directory_uri . '/css/font-awesome-5.min.css');
        wp_enqueue_style('cleenday-flaticon', $this->template_directory_uri . '/fonts/flaticon/flaticon.css');
    }

    public function retrieve_css_variables_and_extra_styles()
    {
        $root_vars = $extra_css = '';

        /**
         * Color Variables
         */
        if (
            class_exists('RWMB_Loader')
            && 'custom' == Cleenday::get_mb_option('page_colors_switch', 'mb_page_colors_switch', 'custom')
        ) {
            $theme_primary_color = Cleenday::get_mb_option('theme-primary-color');
            $theme_secondary_color = Cleenday::get_mb_option('theme-secondary-color');

            $button_color_idle = Cleenday::get_mb_option('button-color-idle');
            $button_color_hover = Cleenday::get_mb_option('button-color-hover');

            $bg_body = Cleenday::get_mb_option('body_background_color');

            $scroll_up_arrow_color = Cleenday::get_mb_option('scroll_up_arrow_color');
            $scroll_up_bg_color = Cleenday::get_mb_option('scroll_up_bg_color');

            $this->gradient_enabled && $theme_gradient_from = Cleenday::get_mb_option('theme-gradient-from');
            $this->gradient_enabled && $theme_gradient_to = Cleenday::get_mb_option('theme-gradient-to');
        } else {
            $theme_primary_color = Cleenday_Globals::get_primary_color();
            $theme_secondary_color = Cleenday_Globals::get_secondary_color();

            $button_color_idle = Cleenday::get_option('button-color-idle');
            $button_color_hover = Cleenday::get_option('button-color-hover');

            $bg_body = Cleenday::get_option('body-background-color');

            $scroll_up_arrow_color = Cleenday::get_option('scroll_up_arrow_color');
            $scroll_up_bg_color = Cleenday::get_option('scroll_up_bg_color');

            $this->gradient_enabled && $theme_gradient = Cleenday::get_option('theme-gradient');
        }

        $root_vars .= '--cleenday-primary-color: ' . esc_attr($theme_primary_color) . ';';
	    $root_vars .= '--cleenday-primary-rgba-color: ' . Cleenday::hexToRGB(esc_attr($theme_primary_color)) . ';';
        $root_vars .= '--cleenday-secondary-color: ' . esc_attr($theme_secondary_color) . ';';
	    $root_vars .= '--cleenday-secondary-rgba-color: ' . Cleenday::hexToRGB(esc_attr($theme_secondary_color)) . ';';

        $root_vars .= '--cleenday-button-color-idle: ' . esc_attr($button_color_idle) . ';';
        $root_vars .= '--cleenday-button-rgb-color-idle: ' . Cleenday::hexToRGB(esc_attr($button_color_idle)) . ';';
        $root_vars .= '--cleenday-button-color-hover: ' . esc_attr($button_color_hover) . ';';
        $root_vars .= '--cleenday-button-rgb-color-hover: ' . Cleenday::hexToRGB(esc_attr($button_color_hover)) . ';';

        $root_vars .= '--cleenday-back-to-top-color: ' . esc_attr($scroll_up_arrow_color) . ';';
        $root_vars .= '--cleenday-back-to-top-background: ' . esc_attr($scroll_up_bg_color) . ';';

        $root_vars .= '--cleenday-body-background: ' . esc_attr($bg_body) . ';';
        //* ↑ color variables

        /**
         * Headings Variables
         */
        $header_font = Cleenday::get_option('header-font');
        $root_vars .= '--cleenday-header-font-family: ' . (esc_attr($header_font['font-family'] ?? '')) . ';';
        $root_vars .= '--cleenday-header-font-weight: ' . (esc_attr($header_font['font-weight'] ?? '')) . ';';
        $root_vars .= '--cleenday-header-font-color: ' . (esc_attr($header_font['color'] ?? '')) . ';';

        for ($i = 1; $i <= 6; $i++) {
            ${'header-h' . $i} = Cleenday::get_option('header-h' . $i);

            $root_vars .= '--cleenday-h' . $i . '-font-family: ' . (esc_attr(${'header-h' . $i}['font-family'] ?? '')) . ';';
            $root_vars .= '--cleenday-h' . $i . '-font-size: ' . (esc_attr(${'header-h' . $i}['font-size'] ?? '')) . ';';
            $root_vars .= '--cleenday-h' . $i . '-line-height: ' . (esc_attr(${'header-h' . $i}['line-height'] ?? '')) . ';';
            $root_vars .= '--cleenday-h' . $i . '-font-weight: ' . (esc_attr(${'header-h' . $i}['font-weight'] ?? '')) . ';';
            $root_vars .= '--cleenday-h' . $i . '-text-transform: ' . (esc_attr(${'header-h' . $i}['text-transform'] ?? '')) . ';';
        }
        //* ↑ headings variables

        /**
         * Content Variables
         */
        $main_font = Cleenday::get_option('main-font');
        $content_font_size = $main_font['font-size'] ?? '';
        $content_line_height = $main_font['line-height'] ?? '';
        $content_line_height = $content_line_height ? round(((int) $content_line_height / (int) $content_font_size), 3) : '';

        $root_vars .= '--cleenday-content-font-family: ' . (esc_attr($main_font['font-family'] ?? '')) . ';';
        $root_vars .= '--cleenday-content-font-size: ' . esc_attr($content_font_size) . ';';
        $root_vars .= '--cleenday-content-line-height: ' . esc_attr($content_line_height) . ';';
        $root_vars .= '--cleenday-content-font-weight: ' . (esc_attr($main_font['font-weight'] ?? '')) . ';';
        $root_vars .= '--cleenday-content-color: ' . (esc_attr($main_font['color'] ?? '')) . ';';
        //* ↑ content variables

        /**
         * Menu Variables
         */
        $menu_font = Cleenday::get_option('menu-font');
        $root_vars .= '--cleenday-menu-font-family: ' . (esc_attr($menu_font['font-family'] ?? '')) . ';';
        $root_vars .= '--cleenday-menu-font-size: ' . (esc_attr($menu_font['font-size'] ?? '')) . ';';
        $root_vars .= '--cleenday-menu-line-height: ' . (esc_attr($menu_font['line-height'] ?? '')) . ';';
        $root_vars .= '--cleenday-menu-font-weight: ' . (esc_attr($menu_font['font-weight'] ?? '')) . ';';
        //* ↑ menu variables

        /**
         * Submenu Variables
         */
        $sub_menu_font = Cleenday::get_option('sub-menu-font');
        $root_vars .= '--cleenday-submenu-font-family: ' . (esc_attr($sub_menu_font['font-family'] ?? '')) . ';';
        $root_vars .= '--cleenday-submenu-font-size: ' . (esc_attr($sub_menu_font['font-size'] ?? '')) . ';';
        $root_vars .= '--cleenday-submenu-line-height: ' . (esc_attr($sub_menu_font['line-height'] ?? '')) . ';';
        $root_vars .= '--cleenday-submenu-font-weight: ' . (esc_attr($sub_menu_font['font-weight'] ?? '')) . ';';
        $root_vars .= '--cleenday-submenu-color: ' . (esc_attr(Cleenday::get_option('sub_menu_color') ?? '')) . ';';
        $root_vars .= '--cleenday-submenu-background: ' . (esc_attr(Cleenday::get_option('sub_menu_background')['rgba'] ?? '')) . ';';

        $root_vars .= '--cleenday-submenu-mobile-color: ' . (esc_attr(Cleenday::get_option('mobile_sub_menu_color') ?? '')) . ';';
        $root_vars .= '--cleenday-submenu-mobile-background: ' . (esc_attr(Cleenday::get_option('mobile_sub_menu_background')['rgba'] ?? '')) . ';';
        $root_vars .= '--cleenday-submenu-mobile-overlay: ' . (esc_attr(Cleenday::get_option('mobile_sub_menu_overlay')['rgba'] ?? '')) . ';';

        $sub_menu_border = Cleenday::get_option('header_sub_menu_bottom_border');
        if ($sub_menu_border) {
            $sub_menu_border_height = Cleenday::get_option('header_sub_menu_border_height')['height'] ?? '';
            $sub_menu_border_color = Cleenday::get_option('header_sub_menu_bottom_border_color')['rgba'] ?? '';

            $extra_css .= '.primary-nav ul li ul li:not(:last-child),'
                . '.sitepress_container > .wpml-ls ul ul li:not(:last-child) {'
                    . ($sub_menu_border_height ? 'border-bottom-width: ' . (int) esc_attr($sub_menu_border_height) . 'px;' : '')
                    . ($sub_menu_border_color ? 'border-bottom-color: ' . esc_attr($sub_menu_border_color) . ';' : '')
                    . 'border-bottom-style: solid;'
                . '}';
        }
        //* ↑ submenu variables

        /**
         * Additional Font Variables
         */
        $extra_font = Cleenday::get_option('additional-font');
        empty($extra_font['font-family']) || $root_vars .= '--cleenday-additional-font-family: ' . esc_attr($extra_font['font-family']) . ';';
        empty($extra_font['font-weight']) || $root_vars .= '--cleenday-additional-font-weight: ' . esc_attr($extra_font['font-weight']) . ';';
        empty($extra_font['color']) || $root_vars .= '--cleenday-additional-font-color: ' . esc_attr($extra_font['color']) . ';';
        //* ↑ additional font variables

        /**
         * Footer Variables
         */
        if (
            Cleenday::get_option('footer_switch')
            && 'widgets' === Cleenday::get_option('footer_content_type')
        ) {
            $root_vars .= '--cleenday-footer-content-color: ' . (esc_attr(Cleenday::get_option('footer_text_color') ?? '')) . ';';
            $root_vars .= '--cleenday-footer-heading-color: ' . (esc_attr(Cleenday::get_option('footer_heading_color') ?? '')) . ';';
            $root_vars .= '--cleenday-copyright-content-color: ' . (esc_attr(Cleenday::get_mb_option('copyright_text_color', 'mb_copyright_switch', 'on') ?? '')) . ';';
        }
        //* ↑ footer variables

        /**
         * Encoded SVG variables
         */
	    $root_vars .= '--cleenday-bg-caret: url(\'data:image/svg+xml; utf8, <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="451.847px" height="451.847px" viewBox="0 0 451.847 451.847" preserveAspectRatio="none" fill="'.str_replace('#','%23',esc_attr($header_font['color'] ?? '#000000')).'"><path xmlns="http://www.w3.org/2000/svg" d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/></svg>\');';
        $root_vars .= '--cleenday-bg-marker-1: url(\'data:image/svg+xml; utf8, <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 297 97" preserveAspectRatio="none"><path fill="'.str_replace('#','%23',$theme_primary_color).'" d="M52 69.78C49.39 70.1 46.77 70.4 44.16 70.78C41.55 71.16 43.16 96.78 43.66 96.72C122.71 85.53 202.88 94.52 281.94 83.33C283.94 83.05 284.1 57.61 282.45 57.39C268.53 55.58 254.6 54.04 240.65 52.69L294.72 48.95C296.72 48.82 296.72 23.08 295.23 23.01L259.5 21.4C260.1 13.83 259.7 0.589973 258.79 0.629973L9.99999 10.12C8.15999 10.2 7.88998 35.99 9.48998 36.06L67.89 38.7L1.61998 43.28C-0.380017 43.42 -0.270027 69.2 1.10997 69.22C18.0366 69.3733 35 69.56 52 69.78Z" /></svg>\');';
        $root_vars .= '--cleenday-bg-marker-3: url(\'data:image/svg+xml; utf8, <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="242" height="98" viewBox="0 0 297 97" preserveAspectRatio="none" ><path fill="'.str_replace('#','%23',$theme_primary_color).'" d="M52 69.78C49.39 70.1 46.77 70.4 44.16 70.78C41.55 71.16 43.16 96.78 43.66 96.72C122.71 85.53 202.88 94.52 281.94 83.33C283.94 83.05 284.1 57.61 282.45 57.39C268.53 55.58 254.6 54.04 240.65 52.69L294.72 48.95C296.72 48.82 296.72 23.08 295.23 23.01L259.5 21.4C260.1 13.83 259.7 0.589973 258.79 0.629973L9.99999 10.12C8.15999 10.2 7.88998 35.99 9.48998 36.06L67.89 38.7L1.61998 43.28C-0.380017 43.42 -0.270027 69.2 1.10997 69.22C18.0366 69.3733 35 69.56 52 69.78Z" /></svg>\');';
        //* ↑ encoded SVG variables

        /**
         * Side Panel Variables
         */
        $sidepanel_title_color = Cleenday::get_mb_option('side_panel_title_color', 'mb_customize_side_panel', 'custom');
        $sidepanel_title_color && $root_vars .= '--cleenday-sidepanel-title-color: ' . esc_attr($sidepanel_title_color) . ';';
        //* ↑ side panel variables

        /**
         * Elementor Container
         */
        $root_vars .= '--cleenday-elementor-container-width: ' . $this->get_elementor_container_width() . 'px;';
        //* ↑ elementor container

        $css_variables = ':root {' . $root_vars . '}';

        $extra_css .= $this->get_mobile_header_extra_css();
        $extra_css .= $this->get_page_title_responsive_extra_css();

        return $css_variables . $extra_css;
    }

	public function get_elementor_container_width()
	{
		if (
			did_action('elementor/loaded')
			&& defined('ELEMENTOR_VERSION')
		) {
			if (version_compare(ELEMENTOR_VERSION, '3.0', '<')) {
				$container_width = get_option('elementor_container_width') ?: 1140;
			} else {
				$kit_id = (new \Elementor\Core\Kits\Manager())->get_active_id();
				$meta_key = \Elementor\Core\Settings\Page\Manager::META_KEY;
				$kit_settings = get_post_meta($kit_id, $meta_key, true);
				$container_width = $kit_settings['container_width']['size'] ?? 1140;
			}
		}

		return $container_width ?? 1170;
	}

    protected function get_mobile_header_extra_css()
    {
        $extra_css = '';

	    $mobile_background = Cleenday::get_option('mobile_background')['rgba'] ?? '';
	    $mobile_color = Cleenday::get_option('mobile_color');
        if (Cleenday::get_option('mobile_header')) {
            $extra_css .= '.wgl-theme-header {
                background-color: ' . esc_attr($mobile_background) . ' !important;
                color: ' . esc_attr($mobile_color) . ' !important;
            }';
        }
	    $extra_css .= '.header_search.search_standard .header_search-field {
            background-color: ' . esc_attr($mobile_background) . ';
        }
        .header_search.search_standard .header_search-field div.header_search-close{
                color: ' . esc_attr($mobile_color) . ';
        }
        header.wgl-theme-header .wgl-mobile-header {
            display: block;
        }
        #main{
            border-top: unset !important;
        }
        .wgl-site-header,
        .wgl-theme-header .primary-nav {
            display: none;
        }
        .wgl-theme-header .hamburger-box {
            display: inline-flex;
        }
        header.wgl-theme-header .mobile_nav_wrapper .primary-nav {
            display: block;
        }
        .wgl-theme-header .wgl-sticky-header {
            display: none;
        }
        .wgl-page-socials {
            display: none;
        }
        a#scroll_up:not(:empty){
            text-indent: -5000px;
	        width: 50px;
	        height: 50px;
			border-radius: 6px 0 0 6px;
		    min-height: auto;
		    transform: translateX(130%);
		    writing-mode: unset;
		    top: auto;
		    padding: 25px 0px 25px 2px;
        }
        a#scroll_up:not(:empty):after{
            content: \'\f102\';
            display: inline-block;
            font-family: \'Font Awesome 5 Free\';
            font-size: 16px;
            line-height: 50px;
            font-weight: 900;
            text-indent: 0;
        }
        a#scroll_up:not(:empty).active{
            transform: translateY(0);
        }

        .inside_image.offset_animation .wgl-portfolio-item_offset{
            transform: unset !important;
        }
        ';

        $mobile_sticky = Cleenday::get_option('mobile_sticky');

        if (Cleenday::get_option('mobile_over_content')) {
            $extra_css .= 'body .wgl-theme-header {
	            position: absolute;
	            z-index: 99;
	            width: 100%;
	            left: 0;
	            top: 0;
            }';

            if ($mobile_sticky) {
                $extra_css .= 'body .wgl-theme-header .wgl-mobile-header {
	                position: absolute;
	                left: 0;
	                width: 100%;
                }';
            }

        } else {
            $extra_css .= 'body .wgl-theme-header.header_overlap {
	            position: relative;
	            z-index: 3;
            }';
        }

        if ($mobile_sticky) {
            $extra_css .= 'body .wgl-theme-header,
	            body .wgl-theme-header.header_overlap {
	            position: sticky;
	            top: 0;
            }';
        }

        return '@media only screen and (max-width: ' . $this->get_header_mobile_breakpoint() . 'px) {' . $extra_css . '}';
    }

    protected function get_header_mobile_breakpoint()
    {
        $elementor_breakpoint = '';

        if (
            'custom' === $this->header_type
            && $this->header_page_id
            && did_action('elementor/loaded')
        ) {
            $settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
            $settings_model = $settings_manager->get_model($this->header_page_id);

            $elementor_breakpoint = $settings_model->get_settings('mobile_breakpoint');
        }

        return $elementor_breakpoint ?: (int) Cleenday::get_option('header_mobile_queris');
    }

    protected function get_page_title_responsive_extra_css()
    {
        $page_title_resp = Cleenday::get_option('page_title_resp_switch');

        if (
            $this->RWMB_is_active()
            && 'on' === rwmb_meta('mb_page_title_switch')
            && rwmb_meta('mb_page_title_resp_switch')
        ) {
            $page_title_resp = true;
        }

        if (!$page_title_resp) {
            // Bailout, if no any responsive logic
            return false;
        }

        $pt_breakpoint = (int) Cleenday::get_mb_option('page_title_resp_resolution', 'mb_page_title_resp_switch', true);
        $pt_padding = Cleenday::get_mb_option('page_title_resp_padding', 'mb_page_title_resp_switch', true);
        $pt_font = Cleenday::get_mb_option('page_title_resp_font', 'mb_page_title_resp_switch', true);

        $breadcrumbs_font = Cleenday::get_mb_option('page_title_resp_breadcrumbs_font', 'mb_page_title_resp_switch', true);
        $breadcrumbs_switch = Cleenday::get_mb_option('page_title_resp_breadcrumbs_switch', 'mb_page_title_resp_switch', true);

        //* Title styles
        $pt_color = !empty($pt_font['color']) ? 'color: ' . esc_attr($pt_font['color']) . ' !important;' : '';
        $pt_f_size = !empty($pt_font['font-size']) ? ' font-size: ' . esc_attr((int) $pt_font['font-size']) . 'px !important;' : '';
        $pt_line_height = !empty($pt_font['line-height']) ? ' line-height: ' . esc_attr((int) $pt_font['line-height']) . 'px !important;' : '';
        $pt_additional_style = !(bool) $breadcrumbs_switch ? ' margin-bottom: 0 !important;' : '';
        $title_style = $pt_color . $pt_f_size . $pt_line_height . $pt_additional_style;
        $featured_bg_title_style = $pt_f_size . $pt_line_height;

        //* Breadcrumbs Styles
        $breadcrumbs_color = !empty($breadcrumbs_font['color']) ? 'color: ' . esc_attr($breadcrumbs_font['color']) . ' !important;' : '';
        $breadcrumbs_f_size = !empty($breadcrumbs_font['font-size']) ? 'font-size: ' . esc_attr((int) $breadcrumbs_font['font-size']) . 'px !important;' : '';
        $breadcrumbs_line_height = !empty($breadcrumbs_font['line-height']) ? 'line-height: ' . esc_attr((int) $breadcrumbs_font['line-height']) . 'px !important;' : '';
        $breadcrumbs_display = !(bool) $breadcrumbs_switch ? 'display: none !important;' : '';
        $breadcrumbs_style = $breadcrumbs_color . $breadcrumbs_f_size . $breadcrumbs_line_height . $breadcrumbs_display;

        //* Blog Single Type 3
        $blog_t3_padding_top = Cleenday::get_option('single_padding_layout_3')['padding-top'] > 150 ? 150 : '';

        $extra_css = '.page-header {'
                . (!empty($pt_padding['padding-top']) ? 'padding-top: ' . esc_attr((int) $pt_padding['padding-top']) . 'px !important;' : '')
                . (!empty($pt_padding['padding-bottom']) ? 'padding-bottom: ' . esc_attr((int) $pt_padding['padding-bottom']) . 'px !important;' : '')
                . 'min-height: auto !important;
            }
            .page-header_content .page-header_title {'
                . $title_style .
            '}
            .post_featured_bg .blog-post_title{'
                 . $featured_bg_title_style .
            '}
            .page-header_content .page-header_breadcrumbs {'
                . $breadcrumbs_style .
            '}
            .page-header_breadcrumbs .divider:not(:last-child):before {
                width: 10px;
            }';

        if ($blog_t3_padding_top) {
            $extra_css .= '.single-post .post_featured_bg > .blog-post {
                padding-top: ' . $blog_t3_padding_top . 'px !important;
            }';
        }

        return '@media (max-width: ' . $pt_breakpoint . 'px) {' . $extra_css . '}';
    }

    /**
     * Enqueue theme stylesheets
     *
     * Function keeps track of already enqueued stylesheets and stores them in `enqueued_stylesheets[]`
     *
     * @param string   $tag      Unprefixed handle.
     * @param string   $file_dir Path to stylesheet folder, relative to root folder.
     * @param string[] $deps     Optional. An array of registered stylesheet handles this stylesheet depends on.
     */
    public function enqueue_style($tag, $file_dir, $deps = [])
    {
        $prefixed_tag = $this->theme_slug . '-' . $tag;
        $this->enqueued_stylesheets[] = $prefixed_tag;

        wp_enqueue_style(
            $prefixed_tag,
            $this->template_directory_uri . $file_dir . $tag . $this->use_minified . '.css',
            $deps
        );
    }

    public function enqueue_pluggable_styles()
    {
        //* Preloader
        if (Cleenday::get_option('preloader')) {
            $this->enqueue_style('preloader', '/css/pluggable/');
        }

        //* Page 404|Search
        if (is_404() || is_search()) {
            $this->enqueue_style('page-404', '/css/pluggable/');
        }

        //* Gutenberg
        if (Cleenday::get_option('disable_wp_gutenberg')) {
            wp_dequeue_style('wp-block-library');
        } else {
            $this->enqueue_style('gutenberg', '/css/pluggable/');
        }

        //* Post Single (blog, portfolio)
        if (is_single()) {
            $post_type = get_post()->post_type;
            if ('post' === $post_type || 'portfolio' === $post_type) {
                $this->enqueue_style('blog-single-post', '/css/pluggable/');
            }
        }

        //* WooCommerce Plugin
        if (class_exists('WooCommerce')) {
            $this->enqueue_style('woocommerce', '/css/pluggable/');
        }

        //* Side Panel
        if (Cleenday::get_option('side_panel_enable')) {
            $this->enqueue_style('side-panel', '/css/pluggable/');
        }

        //* WPML plugin
        if (class_exists('SitePress')) {
            $this->enqueue_style('wpml', '/css/pluggable/');
        }
    }

    public function frontend_scripts()
    {
        wp_enqueue_script('cleenday-theme-addons', $this->template_directory_uri . '/js/theme-addons' . $this->use_minified . '.js', ['jquery'], false, true);
        wp_enqueue_script('cleenday-theme', $this->template_directory_uri . '/js/theme.js', ['jquery'], false, true);

        wp_localize_script('cleenday-theme', 'wgl_core', [
            'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
        ]);

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    public function admin_stylesheets()
    {
        wp_enqueue_style('cleenday-admin', $this->template_directory_uri . '/core/admin/css/admin.css');
        wp_enqueue_style('font-awesome-5-all', $this->template_directory_uri . '/css/font-awesome-5.min.css');
        wp_enqueue_style('wp-color-picker');
    }

    public function admin_scripts()
    {
        wp_enqueue_media();

        wp_enqueue_script('wp-color-picker');
        wp_localize_script('wp-color-picker', 'wpColorPickerL10n', [
            'clear' => esc_html__('Clear', 'cleenday'),
            'clearAriaLabel' => esc_html__('Clear color', 'cleenday'),
            'defaultString' => esc_html__('Default', 'cleenday'),
            'defaultAriaLabel' => esc_html__('Select default color', 'cleenday'),
            'pick' => esc_html__('Select', 'cleenday'),
            'defaultLabel' => esc_html__('Color value', 'cleenday'),
        ]);

        wp_enqueue_script('cleenday-admin', $this->template_directory_uri . '/core/admin/js/admin.js');

        if (class_exists('RWMB_Loader')) {
            wp_enqueue_script('cleenday-metaboxes', $this->template_directory_uri . '/core/admin/js/metaboxes.js');
        }

        $currentTheme = wp_get_theme();
        $theme_name = $currentTheme->parent() == false ? wp_get_theme()->get('Name') : wp_get_theme()->parent()->get('Name');
        $theme_name = trim($theme_name);

        $purchase_code = $email = '';
        if (Cleenday::wgl_theme_activated()) {
            $theme_details = get_option('wgl_licence_validated');
            $purchase_code = $theme_details['purchase'] ?? '';
            $email = $theme_details['email'] ?? '';
        }

        wp_localize_script('cleenday-admin', 'wgl_verify', [
            'ajaxurl' => esc_js(admin_url('admin-ajax.php')),
            'wglUrlActivate' => esc_js(Wgl_Theme_Verify::get_instance()->api . 'verification'),
            'wglUrlDeactivate' => esc_js(Wgl_Theme_Verify::get_instance()->api . 'deactivate'),
            'domainUrl' => esc_js(site_url('/')),
            'themeName' => esc_js($theme_name),
            'purchaseCode' => esc_js($purchase_code),
            'email' => esc_js($email),
            'message' => esc_js(esc_html__('Thank you, your license has been validated', 'cleenday')),
            'ajax_nonce' => esc_js(wp_create_nonce('_notice_nonce'))
        ]);
    }

    protected function add_body_classes()
    {
        add_filter('body_class', function (Array $classes) {
            if ($this->gradient_enabled) {
                $classes[] = 'theme-gradient';
            }

            if (
                is_single()
                && 'post' === get_post_type(get_queried_object_id())
                && '3' === Cleenday::get_mb_option('single_type_layout', 'mb_post_layout_conditional', 'custom')
            ) {
                $classes[] = 'cleenday-blog-type-overlay';
            }

            return $classes;
        });
    }

    public function RWMB_is_active()
    {
        $id = !is_archive() ? get_queried_object_id() : 0;

        return class_exists('RWMB_Loader') && 0 !== $id;
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

new Cleenday_Dynamic_Styles();
