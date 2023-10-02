<?php

namespace WglAddons;

defined('ABSPATH') || exit;

if (!class_exists('Cleenday_Global_Variables')) {
    /**
     * Cleenday Global Variables
     *
     *
     * @category Class
     * @package cleenday\core\class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Cleenday_Global_Variables
    {
        protected static $primary_color;
        protected static $secondary_color;
        protected static $h_font_color;
        protected static $main_font_color;
        protected static $additional_font_color;

        function __construct()
        {
            if (class_exists('\Cleenday_Theme_Helper')) {
                $this->set_variables();
            }
        }

        protected function set_variables()
        {
            self::$primary_color = esc_attr(\Cleenday_Theme_Helper::get_option('theme-primary-color'));
            self::$secondary_color = esc_attr(\Cleenday_Theme_Helper::get_option('theme-secondary-color'));
            self::$h_font_color = esc_attr(\Cleenday_Theme_Helper::get_option('header-font')['color'] ?? null);
            self::$main_font_color = esc_attr(\Cleenday_Theme_Helper::get_option('main-font')['color'] ?? null);
            self::$additional_font_color = esc_attr(\Cleenday_Theme_Helper::get_option('additional-font')['color'] ?? null);
        }

        public static function get_primary_color()
        {
            return self::$primary_color;
        }

        public static function get_secondary_color()
        {
            return self::$secondary_color;
        }

        public static function get_h_font_color()
        {
            return self::$h_font_color;
        }

        public static function get_main_font_color()
        {
            return self::$main_font_color;
        }

        public static function get_additional_font_color()
        {
            return self::$additional_font_color;
        }
    }

    new Cleenday_Global_Variables();
}
