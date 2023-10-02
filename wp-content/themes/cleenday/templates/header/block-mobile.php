<?php
defined('ABSPATH') || exit;

if (!class_exists('Cleenday_Header_Mobile')) {
    class Cleenday_Header_Mobile extends Cleenday_Get_Header
    {
        public function __construct()
        {
            $this->header_vars();
            $this->html_render = 'mobile';

            $header_mobile_background = Cleenday_Theme_Helper::get_option('mobile_background');
            $header_mobile_color = Cleenday_Theme_Helper::get_option('mobile_color');
            $mobile_header_custom =  Cleenday_Theme_Helper::get_option('mobile_header');
            $mobile_sticky = Cleenday_Theme_Helper::get_option('mobile_sticky');

            $mobile_styles = !empty($header_mobile_background['rgba']) ? 'background-color: '.(esc_attr($header_mobile_background['rgba'])).';' : '';
            $mobile_styles .= !empty($header_mobile_color) ? 'color: '.(esc_attr($header_mobile_color)).';' : '';
            $mobile_styles = !empty($mobile_styles) ? ' style="'.$mobile_styles.'"' : '';

            echo "<div class='wgl-mobile-header", ($mobile_sticky === '1' ? ' wgl-sticky-element' : ''), "'",
                $mobile_styles,
                ($mobile_sticky === '1' ? ' data-style="standard"' : ''),
                ">";
            echo "<div class='container-wrapper'>";
            if (!empty($mobile_header_custom)) {
                $this->build_header_layout('mobile');
            } else {
                $this->default_header_mobile();
            }
            $this->build_header_mobile_menu();
            echo '</div>';
            echo '</div>';
        }

        public function default_header_mobile()
        { ?>
            <div class="wgl-header-row">
            <div class="wgl-container">
            <div class="wgl-header-row_wrapper" style="height: 60px;">
                <div class="header_side display_grow h_align_left">
                <div class="header_area_container">
                <nav class="primary-nav"><?php
                if (has_nav_menu('main_menu')) {
                    cleenday_main_menu('main_menu', null);
                } ?>
                </nav>

                <div class="hamburger-box">
                    <div class="hamburger-inner">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                </div>
                </div>
                <div class="header_side display_grow h_align_center">
	                <div class="header_area_container"><?php
	                    $this->get_logo(); ?>
	                </div>
                </div>
                <div class="header_side display_grow h_align_right">
                    <div class="header_area_container"><?php
                        Cleenday_Theme_Helper::render_html($this->search('mobile', '')); ?>
                    </div>
                </div>

            </div>
            </div>
            </div><?php
        }
    }

    new Cleenday_Header_Mobile();
}
