<?php

defined('ABSPATH') || exit;

if (!class_exists('Cleenday_Header_Sticky')) {
    class Cleenday_Header_Sticky extends Cleenday_Get_Header
    {
        public function __construct()
        {
            $this->header_vars();
            $this->html_render = 'sticky';

            if (Cleenday_Theme_Helper::get_mb_option('header_sticky', 'mb_customize_header_layout', 'custom')) {
                $header_sticky_style = Cleenday_Theme_Helper::get_option('header_sticky_style');

                echo "<div class='wgl-sticky-header wgl-sticky-element", ($this->header_type === 'default' ? ' header_sticky_shadow' : ''), "'", (!empty($header_sticky_style) ? ' data-style="' . esc_attr($header_sticky_style) . '"' : ''), ">";

                echo '<div class="container-wrapper">';

                    $this->build_header_layout('sticky');

                echo '</div>';

                echo '</div>';
            }
        }
    }

    new Cleenday_Header_Sticky();
}
