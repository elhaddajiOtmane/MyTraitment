<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/templates/wgl-info-boxes.php.
*/
namespace WglAddons\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WglAddons\Includes\Wgl_Elementor_Helper;
use WglAddons\Includes\Wgl_Icons;

/**
 * WGL Elementor Info Boxes Template
 *
 *
 * @category Class
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WglInfoBoxes
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	public function wgl_svg_to_data( $wgl_svg ) {
		return str_replace( [ '<', '>', '#' ], [ '%3C', '%3E', '%23' ], $wgl_svg );
	}

    public function render($self, $atts)
    {
        extract($atts);
        $ib_media = $infobox_content = $ib_button = $module_link_html = '';

        $wrapper_classes = $layout ? ' wgl-layout-' . $layout : '';

        $kses_allowed_html = [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
            'small' => ['id' => true, 'class' => true, 'style' => true],
            'p' => ['id' => true, 'class' => true, 'style' => true],
        ];

        // Title
        $infobox_title = '<div class="wgl-infobox-title_wrapper">';
        $infobox_title .= !empty($ib_subtitle) ? '<div class="wgl-infobox_subtitle">' . wp_kses($ib_subtitle, $kses_allowed_html) . '</div>' : '';
        $infobox_title .= !empty($ib_title) ? '<' . esc_attr($title_tag) . ' class="wgl-infobox_title">' : '';
        $infobox_title .= !empty($ib_title) ? '<span class="wgl-infobox_title-idle">' . wp_kses($ib_title, $kses_allowed_html) . '</span>' : '';
        $infobox_title .= (!empty($ib_title) && !empty($ib_title_add)) ? '<span class="wgl-infobox_title-add">' . wp_kses($ib_title_add, $kses_allowed_html) . '</span>' : '';
        $infobox_title .= !empty($ib_title) ? '</' . esc_attr($title_tag) . '>' : '';
        $infobox_title .= '</div>';

        // Content
        if (!empty($ib_content)) {
            $infobox_content = '<' . esc_attr($content_tag) . ' class="wgl-infobox_content">';
            $infobox_content .= $ib_content;
            $infobox_content .= '</' . esc_attr($content_tag) . '>';
        }

        // Media
        if (!empty($icon_type)) {
            $media = new Wgl_Icons;
            $ib_media .= $media->build($self, $atts, []);
        }

        // Link
        if (!empty($link['url'])) {
            $self->add_link_attributes('link', $link);
        }

        // Read more button
        if ($add_read_more) {
	        // Custom styles
	        if ($add_button_bg && $button_icon_bg_idle) {
		        $svg_code = "url(\"data:image/svg+xml; utf8, <svg version='1.1' xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' viewBox='0 0 308 97' style='enable-background:new 0 0 308 97;' width='76' height='24' xml:space='preserve'><path fill='" . $button_icon_bg_idle . "' d='M2.41001 48.6301C4.67001 48.9801 6.94001 49.2601 9.19001 49.6301C7.25001 54.3101 5.75001 59.1801 5.48001 59.1501C11.48 59.8301 17.38 60.3801 23.33 60.8701C22.76 65.5201 22.97 73.1201 23.98 76.1701C16.7 76.0901 9.42001 76.0301 2.15001 75.9001C-0.0399921 75.9001 -0.219994 96.2701 2.41001 96.3101C100.17 98.1101 197.93 94.5101 295.69 96.3101C297.88 96.3101 298.06 75.9501 295.43 75.9001C291.1 75.8201 286.77 75.8001 282.43 75.7401C282.56 75.1601 282.66 74.4501 282.75 73.6801L289.01 73.8001C292.88 73.8801 295.92 63.5901 296.5 58.0701H299.39C301.69 58.0701 301.63 37.6601 299.12 37.6601C291.93 37.6601 284.73 37.6601 277.52 37.7101C277.67 35.9501 277.73 33.9301 277.7 31.9401C287.23 31.9401 296.77 31.9901 306.3 31.9401C308.6 31.9401 308.55 11.5301 306.03 11.5301C295.577 11.5301 285.127 11.5301 274.68 11.5301C276.94 6.40012 278.52 0.130117 278.98 0.120117L149.57 1.55011C114.64 1.94011 78.08 -0.44989 43.57 6.55011C36.3 6.26011 29.04 5.98014 21.77 5.67014C19.77 5.58014 19.26 25.9601 22.04 26.0801L23.54 26.1401C23.54 26.2801 23.48 26.3901 23.45 26.4901C16.34 27.0401 9.24001 27.6201 2.14001 28.2801C-0.209994 28.4301 -0.259994 48.2101 2.41001 48.6301ZM26.2 26.1801H26.57L25.96 26.2301L26.2 26.1801Z'/></svg>\")";

		        $styles = '.elementor-element-' . esc_attr( $self->get_id() ) . ' .wgl-infobox_button:before { background-image: '.$this->wgl_svg_to_data( $svg_code ).'; }';
		        Wgl_Elementor_Helper::enqueue_css( $styles, false );
	        }

	        $self->add_render_attribute('btn', 'class', 'wgl-infobox_button button-read-more');

            $ib_button = '<div class="wgl-infobox-button_wrapper">';
            $ib_button .= sprintf(
                '<%s %s %s>',
                $module_link ? 'div' : 'a',
                $module_link ? '' : $self->get_render_attribute_string('link'),
                $self->get_render_attribute_string('btn')
            );
            $ib_button .= $read_more_text ? '<span>' . esc_html($read_more_text) . '</span>' : '';
            $ib_button .= $module_link ? '</div>' : '</a>';
            $ib_button .= '</div>';
        }

        if ($module_link && !empty($link['url'])) {
            $module_link_html = '<a class="wgl-infobox__link" ' . $self->get_render_attribute_string('link') . '></a>';
        }

        // Render
        echo $module_link_html,
            '<div class="wgl-infobox">',
                '<div class="wgl-infobox_wrapper', esc_attr($wrapper_classes), '">',
                    $ib_media,
                    '<div class="content_wrapper">',
                        $infobox_title,
                        $infobox_content,
                        $read_more_inline ? '' : $ib_button,
                    '</div>',
                    $read_more_inline ? $ib_button : '',
                '</div>',
            '</div>';
    }
}
