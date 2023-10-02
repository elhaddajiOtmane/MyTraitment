<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/templates/wgl-testimonials.php.
*/
namespace WglAddons\Templates;

defined('ABSPATH') || exit;

use WglAddons\Includes\Wgl_Carousel_Settings;

if (!class_exists('WglTestimonials')) {
    /**
     * WGL Elementor Testimonials Template
     *
     *
     * @category Class
     * @package cleenday-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WglTestimonials
    {
        private static $instance = null;

        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function render($self, $_s)
        {
            if ($_s['use_carousel']) {
                wp_enqueue_script(
                    'slick',
                    get_template_directory_uri() . '/js/slick.min.js',
                    [],
                    false,
                    false
                );
            }

            switch ($_s['items_per_line']) {
                case '1':
                    $col = 12;
                    break;
                case '2':
                    $col = 6;
                    break;
                case '3':
	            default:
                    $col = 4;
                    break;
                case '4':
                    $col = 3;
                    break;
                case '5':
                    $col = '1/5';
                    break;
            }

            // Wrapper classes
            $self->add_render_attribute(
                'wrapper',
                [
                    'class' => [
                        'wgl-testimonials',
                        'type-' . $_s['item_type'],
                        'a' . $_s['item_align']
                    ],
                ]
            );
            if ($_s['hover_animation']) {
                $self->add_render_attribute('wrapper', 'class', 'hover_animation');
            }

            $values = (array) $_s['list'];
            $item_data = [];
            foreach ($values as $data) {
                $new_data = $data;
                $new_data['thumbnail'] = $data['thumbnail'] ?? '';
                $new_data['quote'] = $data['quote'] ?? '';
                $new_data['author_name'] = $data['author_name'] ?? '';
                $new_data['author_rating'] = $data['author_rating'] ?? '';
                $new_data['link_author'] = $data['link_author'] ?? '';

                $item_data[] = $new_data;
            }

            $content =  '';

            foreach ($item_data as $item_d) {

                if (!empty($item_d['link_author']['url'])) {
                    $self->add_link_attributes('link_author', $item_d['link_author']);
                }

                // outputs
                $name_output = '<' . esc_attr($_s['name_tag']) . ' class="wgl-testimonials_name">';
                $name_output .= !empty($item_d['link_author']['url']) ? '<a ' . $self->get_render_attribute_string('link_author') . '>' : '';
                $name_output .= esc_html($item_d['author_name']);
                $name_output .= !empty($item_d['link_author']['url']) ? '</a>' : '';
                $name_output .= '</' . esc_attr($_s['name_tag']) . '>';

                $quote_output = '<' . esc_attr($_s['quote_tag']) . ' class="wgl-testimonials_quote">';
                $quote_output .= $item_d['quote'];
                $quote_output .= '</' . esc_attr($_s['quote_tag']) . '>';

                if (!empty($item_d['author_rating'])){
	                $rating_output = '<span class="wgl-testimonials_rating">';
		                $rating_output .= esc_html($item_d['author_rating']);
		                if (esc_html($item_d['author_rating_count'])){
			                $rating_output .= '<span class="wgl-testimonials_rating_count">';
			                    $rating_output .= esc_html($item_d['author_rating_count']);
			                $rating_output .= '</span>';
		                }
	                $rating_output .= '</span>';
                }else{
	                $rating_output = '';
                }

                $image_output = '';
                if (!empty($item_d['thumbnail']['url'])) {
                    $image_output = '<div class="wgl-testimonials_image">';
                    $image_output .= !empty($item_d['link_author']['url']) ? '<a ' . $self->get_render_attribute_string('link_author') . '>' : '';
                    $image_output .= '<img src="' . esc_url($item_d['thumbnail']['url']) . '" alt="' . esc_attr($item_d['author_name']) . ' photo">';
                    $image_output .= !empty($item_d['link_author']['url']) ? '</a>' : '';
                    $image_output .= '</div>';
                }

                $content .= '<div class="wgl-testimonials-item_wrap' . (!$_s['use_carousel'] ? " wgl_col-" . $col : '') . '">';
                switch ($_s['item_type']) {
                    case 'author_top':
                        $content .= '<div class="wgl-testimonials_item">';
                        $content .= $image_output;
                        $content .= '<div class="content_wrap">';
                        $content .= $quote_output;
                        $content .= '</div>';
                        $content .= '<div class="meta_wrap">';
                        $content .= '<div class="name_wrap">';
                        $content .= $name_output;
	                    $content .= $rating_output;
                        $content .= '</div>';
                        $content .= '</div>';
                        $content .= '</div>';
                        break;
                    case 'inline_top':
                        $content .= '<div class="wgl-testimonials_item">';
                        $content .= '<div class="meta_wrap">';
                        $content .= $image_output;
                        $content .= '<div class="name_wrap">';
                        $content .= $name_output;
	                    $content .= $rating_output;
                        $content .= '</div>';
                        $content .= '</div>';
                        $content .= '<div class="content_wrap">';
                        $content .= $quote_output;
                        $content .= '</div>';
                        $content .= '</div>';
                        break;
	                case 'author_bottom':
                    case 'inline_bottom':
                    default:
                        $content .= '<div class="wgl-testimonials_item">';
                        $content .= '<div class="content_wrap">';
                        $content .= $quote_output;
                        $content .= '</div>';
                        $content .= '<div class="meta_wrap">';
                        $content .= $image_output;
                        $content .= '<div class="name_wrap">';
                        $content .= $name_output;
	                    $content .= $rating_output;
                        $content .= '</div>';
                        $content .= '</div>';
                        $content .= '</div>';
                        break;
                }
                $content .= '</div>';
            }

            $output = '<div  ' . $self->get_render_attribute_string('wrapper') . '>';
            if ($_s['use_carousel']) {
                $output .= Wgl_Carousel_Settings::init($_s, $content, false);
            } else {
                $output .= $content;
            }
            $output .= '</div>';

            return $output;
        }
    }
}
