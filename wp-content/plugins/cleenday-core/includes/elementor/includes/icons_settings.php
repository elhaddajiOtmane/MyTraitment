<?php

namespace WglAddons\Includes;

defined('ABSPATH') || exit;

use Elementor\{Controls_Manager, Control_Media};
use Elementor\{Utils, Icons_Manager, Group_Control_Image_Size};

if (!class_exists('Wgl_Icons')) {
    /**
     * WGL Elementor Loop Settings
     *
     *
     * @category Class
     * @package cleenday-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Wgl_Icons
    {
        private static $instance = null;

        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function build($self, $atts, $pref)
        {
            $icon_builder = new Wgl_Icon_Builder();
            return $icon_builder->build( $self, $atts, $pref );
        }

        public static function init($self, $array = [])
        {
            if (! $self) return;

            $label = $array['label'] ?? '';
            $prefix = $array['prefix'] ?? '';

            $default_media_type = $array['default']['media_type'] ?? '';
            $default_icon = $array['default']['icon'] ?? [];

            if (isset($array['section']) && $array['section']) {
                $self->start_controls_section(
                    $prefix . 'add_icon_image_section',
                    ['label' => sprintf( esc_html__('%s Icon/Image', 'cleenday-core'), $label )]
                );
            }

            $media_types_options = [
                '' => [
                    'title' => esc_html__('None', 'cleenday-core'),
                    'icon' => 'fa fa-ban',
                ],
                'font' => [
                    'title' => esc_html__('Icon', 'cleenday-core'),
                    'icon' => 'fa fa-smile-o',
                ],
                'image' => [
                    'title' => esc_html__('Image', 'cleenday-core'),
                    'icon' => 'fa fa-picture-o',
                ],
            ];

            $self->add_control(
                $prefix . 'icon_type',
                [
                    'label' => esc_html__('Media Type', 'cleenday-core'),
                    'type' => Controls_Manager::CHOOSE,
                    'toggle' => false,
                    'label_block' => false,
                    'options' => $media_types_options,
                    'default' => $default_media_type,
                ]
            );

            $self->add_control(
                $prefix . 'icon_fontawesome',
                [
                    'label' => esc_html__('Icon', 'cleenday-core'),
                    'type' => Controls_Manager::ICONS,
                    'condition' => [ $prefix . 'icon_type' => 'font' ],
                    'label_block' => true,
                    'default' => $default_icon,
                ]
            );

            $self->add_control(
                $prefix . 'icon_render_class',
                [
                    'label' => esc_html__('Icon Class', 'cleenday-core'),
                    'type' => Controls_Manager::HIDDEN,
                    'condition' => [ $prefix . 'icon_type' => 'font' ],
                    'prefix_class' => 'elementor-widget-icon-box ',
                    'default' => 'wgl-icon-box',
                ]
            );

            $self->add_control(
                $prefix . 'thumbnail',
                [
                    'label' => esc_html__('Image', 'cleenday-core'),
                    'type' => Controls_Manager::MEDIA,
                    'condition' => [ $prefix . 'icon_type' => 'image' ],
                    'label_block' => true,
                    'default' => [ 'url' => Utils::get_placeholder_image_src() ],
                ]
            );

            $self->add_control(
                $prefix . 'image_render_class',
                [
                    'label' => esc_html__('Image Class', 'cleenday-core'),
                    'type' => Controls_Manager::HIDDEN,
                    'condition' => [ $prefix . 'icon_type' => 'image' ],
                    'default' => 'wgl-image-box',
                    'prefix_class' => 'elementor-widget-image-box ',
                ]
            );

            $self->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => $prefix . 'thumbnail',
                    'default' => 'full',
                    'separator' => 'none',
                    'condition' => [ $prefix . 'icon_type' => 'image' ],
                ]
            );

            if (!empty($array['output'])){
                foreach ($array['output'] as $key => $value) {
                    $self->add_control(
                        $key,
                        $value
                    );
                }
            }

            if (isset($array['section']) && $array['section']) {
                $self->end_controls_section();
            }
        }

    }
    new Wgl_Icons();
}

if (!class_exists('Wgl_Icon_Builder')) {
    /**
     * Wgl Icon Build
     *
     *
     * @category Class
     * @package cleenday-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Wgl_Icon_Builder
    {
        private static $instance = null;

        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function build($self, $atts, $pref)
        {
            $prefix = $output = '';
            $icon_tag = 'span';

            if (isset($pref) && !empty($pref)) {
                $prefix = $pref;
            }

            $media_type = $atts[$prefix . 'icon_type'];
            $icon_fontawesome = $atts[$prefix . 'icon_fontawesome'];
            $thumbnail = $atts[$prefix . 'thumbnail'];

            $self->add_render_attribute($prefix . 'icon', 'class', 'wgl-icon');
            if (isset($atts['hover_animation_icon']) && !empty($atts['hover_animation_icon'])) {
                $self->add_render_attribute($prefix . 'icon', 'class', 'elementor-animation-' . $atts['hover_animation_icon']);
            }

            // Wrapper Class
            $wrapper_class = $atts['wrapper_class'] ?? '';
            if ($media_type === 'image') $wrapper_class .= 'img-wrapper';
            if ($media_type === 'font') $wrapper_class .= 'icon-wrapper';
            $self->add_render_attribute($prefix . 'wrapper-icon', 'class', [
                'media-wrapper',
                $wrapper_class
            ]);

            if (!empty($atts['link_t']['url'])) {
                $icon_tag = 'a';
                $self->add_link_attributes($prefix . 'link_t', $atts['link_t']);
            }

            $icon_attributes = $self->get_render_attribute_string($prefix . 'icon');
            $link_attributes = $self->get_render_attribute_string($prefix . 'link_t');


            if (
                $media_type == 'font' && !empty($icon_fontawesome)
                || $media_type == 'image' && !empty($thumbnail)
           ) {
                $output .= '<div ' . $self->get_render_attribute_string($prefix . 'wrapper-icon') . '>';

                    if ($media_type == 'font' && !empty($icon_fontawesome['value'])) {
                        $output .= '<';
                            $output .= implode(' ', [$icon_tag, $icon_attributes, $link_attributes]);
                        $output .= '>';

                        if('svg' === $icon_fontawesome['library']){
                            $output .= '<span class="icon elementor-icon">';
                        }

                        // Icon migration
                        $migrated = isset($atts['__fa4_migrated'][$prefix . 'icon_fontawesome']);
                        $is_new = Icons_Manager::is_migration_allowed();
                        if ($is_new || $migrated) {
                            ob_start();
                            Icons_Manager::render_icon($icon_fontawesome, ['class' => 'icon elementor-icon', 'aria-hidden' => 'true']);
                            $output .= ob_get_clean();
                        } else {
                            $output .= '<i class="icon '.esc_attr($icon_fontawesome['value']).'"></i>';
                        }

                        if('svg' === $icon_fontawesome['library']){
                            $output .= '</span>';
                        }

                        $output .= '</'.$icon_tag.'>';
                    }
                    if (
                        $media_type == 'image'
                        && !empty($thumbnail['url'])
                    ) {
                        $self->add_render_attribute(
                            'thumbnail',
                            [
                                'src' => $thumbnail['url'],
                                'alt' => Control_Media::get_image_alt($thumbnail),
                                'title' => Control_Media::get_image_title($thumbnail),
                            ]
                        );

                        if (isset($atts['hover_animation_image'])) {
                            $atts['hover_animation'] = $atts['hover_animation_image'];
                        }

                        $output .= '<figure class="wgl-image-box_img">';

                        $output .= '<' . $icon_tag . ' ' . $link_attributes . '>';
                            $output .= Group_Control_Image_Size::get_attachment_image_html($atts, 'thumbnail', $prefix . 'thumbnail');
                        $output .= '</'.$icon_tag.'>';

                        $output .= '</figure>';

                    }

                $output .= '</div>';
            }

            return $output;
        }
    }
}
