<?php

namespace WglAddons\Includes;

defined('ABSPATH') || exit;

use Elementor\Plugin;

if (!class_exists('Wgl_Elementor_Helper')) {
    /**
     * Wgl Elementor Helper Settings
     *
     *
     * @package cleenday-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Wgl_Elementor_Helper
    {
        private static $instance;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_wgl_icons()
        {
            return [
                'marker',
                'close',
                'arrow',
                'heart',
                'tick',
                'heart-1',
                'cart',
                'link',
                'socket',
                'back',
                'null',
                'null-1',
                'map',
                'quote',
                'null-2',
                'null-3',
                'play',
                'null-4',
                'clock',
                'gift-card',
                'euro',
                'tick-1',
                'smartphone',
                'chat',
                'share',
                'ebook',
                'group',
                'sms',
                'null-5',
                'null-6',
                'null-7',
                'star',
                'view',
            ];
        }

	    /**
	     * @param $style
	     * @param bool $esc_attr
	     *
	     * @return mixed|string
	     */
	    public static function enqueue_css($style, $esc_attr = true)
        {
            if (!(bool) Plugin::$instance->editor->is_edit_mode()) {
                if (!empty($style)) {
                    ob_start();
                        echo $style;
                    $css = ob_get_clean();
                    $css = apply_filters('cleenday/enqueue_shortcode_css', $css, $style);

                    return $css;
                }
            } else {
                echo '<style>', ((bool)$esc_attr ? esc_attr($style) : $style ), '</style>';
            }
        }

        public function get_elementor_templates()
        {
            $templates = get_posts([
                'post_type' => 'elementor_library',
                'posts_per_page' => -1,
            ]);

            if (!empty($templates) && !is_wp_error($templates)) {

                foreach ($templates as $template) {
                    $options[$template->ID] = $template->post_title;
                }

                update_option('temp_count', $options);

                return $options ?? [];
            }
        }

        /**
         * Retrieve image dimensions based on passed arguments.
         *
         * @param array|string $desired_dimensions  Required. Desired dimensions. Ex: `700x300`, `[700, 300]`, `['width' => 700, 'height' => 300]`
         * @param string       $aspect_ratio        Required. Desired ratio. Ex: `16:9`
         * @param array        $img_data            Optional. Result of `wp_get_attachment_image_src` function.
         */
        public static function get_image_dimensions(
            $desired_dimensions,
            String $aspect_ratio,
            Array $img_data = []
        ) {
            if ($aspect_ratio) {
                $ratio_arr = explode(':', $aspect_ratio);
                $ratio = round($ratio_arr[0] / $ratio_arr[1], 4);
            }

            if ('full' === $desired_dimensions) {
                $attachemnt_data = $img_data ?: wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');

                if (!$attachemnt_data) {
                    // Bailout, if no featured image
                    return;
                }

                return [
                    'width' => $attachemnt_data[1],
                    'height' => isset($ratio) ? round((int) $attachemnt_data[1] / $ratio) : $attachemnt_data[2]
                ];
            }

            if (is_array($desired_dimensions)) {
                $desired_width = $desired_dimensions['width'];
                $desired_height = $desired_dimensions['height'];
            } else {
                $dims = explode('x', $desired_dimensions);
                $desired_width = $dims[0];
                $desired_height = !empty($dims[1]) ? $dims[1] : $dims[0];
            }

            return [
                'width' => (int) $desired_width,
                'height' => isset($ratio) ? round($desired_width / $ratio) : (int) $desired_height
            ];
        }

        /**
         * Retrieve the name of the highest priority template file that exists.
         *
         * @param string|array $template_names Template file(s) to search for, in order.
         * @param string       $origin_path    Template file(s) origin path. (../cleenday-core/includes/elementor)
         * @param string       $override_path  New template file(s) override path. (../cleenday)
         *
         * @return string The template filename if one is located.
         */
        public static function get_locate_template(
            $template_names,
            $origin_path,
            $override_path
        ) {
            $files = [];
            $file = '';
            foreach ((array)$template_names as $template_name) {
                if (file_exists(get_stylesheet_directory() . $override_path . $template_name)) {
                    $file = get_stylesheet_directory() . $override_path . $template_name;
                } elseif (file_exists(get_template_directory() . $override_path . $template_name)) {
                    $file = get_template_directory() . $override_path . $template_name;
                } elseif (file_exists(realpath(__DIR__ . '/..') . $origin_path . $template_name)) {
                    $file = realpath(__DIR__ . '/..') . $origin_path . $template_name;
                }
                array_push($files, $file);
            }
            return $files;
        }
    }

    new Wgl_Elementor_Helper;
}
