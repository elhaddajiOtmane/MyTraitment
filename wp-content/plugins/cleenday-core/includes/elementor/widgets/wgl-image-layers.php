<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-image-layers.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager, Control_Media, Repeater};

class Wgl_Image_Layers extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-image-layers';
    }

    public function get_title()
    {
        return esc_html__('WGL Image Layers', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-image-layers';
    }

    public function get_categories()
    {
        return [ 'wgl-extensions' ];
    }

    public function get_script_depends()
    {
        return [ 'jquery-appear' ];
    }


    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            [
                'label' => esc_html__('General', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'interval',
            [
                'label' => esc_html__('Images Appearing Interval (ms)', 'cleenday-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 50,
				'step' => 50,
				'default' => 600,
            ]
        );

        $this->add_control(
            'transition',
            [
                'label' => esc_html__('Transition Duration (ms)', 'cleenday-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 50,
				'step' => 50,
				'default' => 800,
            ]
        );

        $this->add_control(
            'image_link',
            [
                'label' => esc_html__('Add Module Link', 'cleenday-core'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_content',
            [ 'label' => esc_html__('Content', 'cleenday-core') ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Image', 'cleenday-core'),
                'type' => Controls_Manager::MEDIA,
                'default' => [ 'url' => '' ],
                'label_block' => true,
            ]
        );

        $repeater->add_responsive_control(
            'top_offset',
            [
                'label' => esc_html__('Top Offset (%)', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'max' => 400 ],
                ],
                'default' => [ 'size' => 0, 'unit' => '%' ],
            ]
        );

        $repeater->add_responsive_control(
            'left_offset',
            [
                'label' => esc_html__('Left Offset (%)', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'max' => 400 ],
                ],
                'default' => [ 'size' => 0 ],
            ]
        );

        $repeater->add_control(
            'image_animation',
            [
                'label' => esc_html__('Layer Animation', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade_in' => esc_html__('Fade In', 'cleenday-core'),
                    'slide_up' => esc_html__('Slide Up', 'cleenday-core'),
                    'slide_down' => esc_html__('Slide Down', 'cleenday-core'),
                    'slide_left' => esc_html__('Slide Left', 'cleenday-core'),
                    'slide_right' => esc_html__('Slide Right', 'cleenday-core'),
                    'slide_big_up' => esc_html__('Slide Big Up', 'cleenday-core'),
                    'slide_big_down' => esc_html__('Slide Big Down', 'cleenday-core'),
                    'slide_big_left' => esc_html__('Slide Big Left', 'cleenday-core'),
                    'slide_big_right' => esc_html__('Slide Big Right', 'cleenday-core'),
                    'flip_x' => esc_html__('Flip Horizontally', 'cleenday-core'),
                    'flip_y' => esc_html__('Flip Vertically', 'cleenday-core'),
                    'zoom_in' => esc_html__('Zoom In', 'cleenday-core'),
                ],
                'default' => 'fade_in',
            ]
        );

        $repeater->add_control(
            'image_order',
            [
                'label' => esc_html__('Image z-index', 'cleenday-core'),
                'type' => Controls_Manager::NUMBER,
				'step' => 1,
                'default' => '1',
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'cleenday-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $content = '';
        $animation_delay = 0;
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('image-layers', 'class', 'wgl-image-layers');

        if (!empty($settings['image_link']['url'])) {
            $this->add_render_attribute('image_link', 'class', 'image_link');
            $this->add_link_attributes('image_link', $settings['image_link']);
        }

        foreach ($settings[ 'items' ] as $index => $item) {
            $animation_delay = $animation_delay + $settings['interval'];

            $image_layer = $this->get_repeater_setting_key('image_layer', 'items' , $index);
            $this->add_render_attribute($image_layer, [
                'src' => !empty($item['thumbnail']['url']) ? esc_url($item['thumbnail']['url']) : '',
                'alt' => Control_Media::get_image_alt($item['thumbnail']),
            ]);

            $image_wrapper = $this->get_repeater_setting_key('image_wrapper', 'items' , $index);
            $this->add_render_attribute($image_wrapper, [
                'class' => [
                    'img-layer_image-wrapper',
                    esc_attr($item['image_animation'])
                ],
                'style' => 'z-index: '.esc_attr((int)$item['image_order']),
            ]);

            $layer_item = $this->get_repeater_setting_key('layer_item', 'items' , $index);
            $left_offset = $item['left_offset']['size'] ? $item['left_offset']['size'].$item['left_offset']['unit'] : '0%';
            $top_offset = $item['top_offset']['size'] ? $item['top_offset']['size'].$item['top_offset']['unit'] : '0%';
	        $this->add_render_attribute($layer_item, [
                'class' => 'img-layer_item',
                'style' => 'transform: translate('.esc_attr($left_offset).', '.esc_attr($top_offset).');'
            ]);

            $layer_image = $this->get_repeater_setting_key('layer_image', 'items' , $index);
            $this->add_render_attribute($layer_image, [
                'class' => 'img-layer_image',
                'style' => 'transition: all '.$settings[ 'transition' ].'ms; transition-delay: '.$animation_delay.'ms;'
            ]);

            ob_start();

            ?><div <?php echo $this->get_render_attribute_string( $image_wrapper ); ?>>
                <div <?php echo $this->get_render_attribute_string( $layer_item ); ?>>
                    <div <?php echo $this->get_render_attribute_string( $layer_image ); ?>>
                        <img <?php echo $this->get_render_attribute_string( $image_layer ); ?> />
                    </div>
                </div>
            </div> <?php

            $content .= ob_get_clean();
        }

        ?><div <?php echo $this->get_render_attribute_string('image-layers'); ?>><?php
            if (!empty($settings['image_link']['url'])) : ?><a <?php echo $this->get_render_attribute_string('image_link'); ?>><?php endif;
                echo $content;
            if (!empty($settings['image_link']['url'])) : ?></a><?php endif;
        ?></div><?php

    }

    public function wpml_support_module() {
        add_filter( 'wpml_elementor_widgets_to_translate',  [$this, 'wpml_widgets_to_translate_filter']);
    }

    public function wpml_widgets_to_translate_filter( $widgets ){
        return \WglAddons\Includes\WGL_WPML_Settings::get_translate(
            $this, $widgets
        );
    }

}