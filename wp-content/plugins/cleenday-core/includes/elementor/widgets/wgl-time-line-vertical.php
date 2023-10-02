<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-time-line-vertical.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager, Control_Media, Repeater};
use Elementor\{Group_Control_Background, Group_Control_Border, Group_Control_Box_Shadow, Group_Control_Typography, Utils};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;

class Wgl_Time_Line_Vertical extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-time-line-vertical';
    }

    public function get_title()
    {
        return esc_html__('WGL Time Line Vertical', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-time-line-vertical';
    }

    public function get_categories()
    {
        return ['wgl-extensions'];
    }

    public function get_script_depends()
    {
        return ['jquery-appear'];
    }

    protected function register_controls() {
        /* Start General Settings Section */
        $this->start_controls_section('wgl_time_line_section',
            array(
                'label'         => esc_html__('General Settings', 'cleenday-core'),
            )
        );

        $this->add_control(
            'add_appear',
            array(
                'label'        => esc_html__('Add Appear Animation','cleenday-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'cleenday-core' ),
                'label_off'    => esc_html__( 'Off', 'cleenday-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'add_line',
            array(
                'label'        => esc_html__('Add Line','cleenday-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'cleenday-core' ),
                'label_off'    => esc_html__( 'Off', 'cleenday-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'prefix_class' => 'line-',
            )
        );

        $this->add_control('start_image',
            array(
                'label'         => esc_html__('Time Line Start Image', 'cleenday-core'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'def',
                'options'       => [
                    'def'           => 'Default',
                    'custom'        => 'Custom',
                    'none'          => 'None',
                ],
                'condition'   =>[
                    'add_line' => 'yes'
                ]
            )
        );

        $this->add_control('start_image_thumb',
            array(
                'label'       => esc_html__( 'Thumbnail', 'cleenday-core' ),
                'type'        => Controls_Manager::MEDIA,
                'condition'   =>[
                    'start_image' => 'custom',
                    'add_line' => 'yes'
                ]
            )
        );

        $this->add_responsive_control(
            'start_image_margin',
            array(
                'label' => esc_html__( 'Image Margin', 'cleenday-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .time_line-start_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'   =>[
                    'start_image' => 'custom',
                    'add_line' => 'yes'
                ]
            )
        );

        $this->add_control(
            'line_color',
            array(
                'label' => esc_html__( 'Line Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#dedbde',
                'selectors' => array(
                    '{{WRAPPER}} .time_line-curve svg' => 'stroke: {{VALUE}};',
                ),
                'condition'   =>[
                    'add_line' => 'yes'
                ]
            )
        );

        $this->end_controls_section();

        /* Start General Settings Section */
        $this->start_controls_section('content_section',
            array(
                'label'         => esc_html__('Content Settings', 'cleenday-core'),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            array(
                'label'       => esc_html__( 'Thumbnail', 'cleenday-core' ),
                'type'        => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            )
        );

        $repeater->add_control(
            'title',
            array(
                'label'             => esc_html__('Title', 'cleenday-core'),
                'type'              => Controls_Manager::TEXTAREA,
				'default'           => esc_html__( 'This is the heading​', 'cleenday-core' ),
				'placeholder'       => esc_html__( 'This is the heading​', 'cleenday-core' ),
                'dynamic' => ['active' => true],
            )
        );

        $repeater->add_control(
            'content',
            array(
                'label' => esc_html__('Content', 'cleenday-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Tortor at risus viverra adipiscing at in tellus integer. Duis ultricies lacus tincidunt aliquet risus feugiat.', 'cleenday-core'),
                'dynamic' => ['active' => true],
            )
        );

        $repeater->add_control(
            'date',
            array(
                'label'             => esc_html__('Date', 'cleenday-core'),
                'type'              => Controls_Manager::TEXT,
				'default' => '',
            )
        );

        $this->add_control(
            'items',
            array(
                'label'   => esc_html__( 'Layers', 'cleenday-core' ),
                'type'    => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default' => [
                    ['title' => esc_html__('This is the heading​', 'cleenday-core')],
                    ['title' => esc_html__('This is the heading​', 'cleenday-core')],
                ],
                'title_field' => '{{title}}',
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
        /*-----------------------------------------------------------------------------------*/

        //Image Styles

        $this->start_controls_section(
            'media_style_section',
            array(
                'label'     => esc_html__( 'Image', 'cleenday-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'media_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .time_line-media' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .time_line-media' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_size',
            array(
                'label' => esc_html__( 'Image Size', 'cleenday-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 290,
                        'max' => 500,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .time_line-media' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
            )
        );

        $this->add_responsive_control(
            'bg_position',
            array(
                'label' => esc_html__( 'Position', 'Background Control', 'cleenday-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center center',
                'responsive' => true,
                'options' => [
                    'top left' => esc_html__( 'Top Left', 'Background Control', 'cleenday-core' ),
                    'top center' => esc_html__( 'Top Center', 'Background Control', 'cleenday-core' ),
                    'top right' => esc_html__( 'Top Right', 'Background Control', 'cleenday-core' ),
                    'center left' => esc_html__( 'Center Left', 'Background Control', 'cleenday-core' ),
                    'center center' => esc_html__( 'Center Center', 'Background Control', 'cleenday-core' ),
                    'center right' => esc_html__( 'Center Right', 'Background Control', 'cleenday-core' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'Background Control', 'cleenday-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'Background Control', 'cleenday-core' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'Background Control', 'cleenday-core' ),

                ],
            )
        );

        $this->start_controls_tabs(
            'media_colors'
        );

        $this->start_controls_tab(
            'media_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'cleenday-core' ),
            ]
        );

        $this->add_control(
            'media_color',
            array(
                'label' => esc_html__( 'Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#dedbde',
                'selectors' => array(
                    '{{WRAPPER}} .time_line-media' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'media_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'cleenday-core' ),
            ]
        );

        $this->add_control(
            'media_hover_color',
            array(
                'label' => esc_html__( 'Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => array(
                    '{{WRAPPER}} .time_line-curve:before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .time_line-curve:after' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        //Title Styles

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => esc_html__( 'Title', 'cleenday-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .time_line-text',
            )
        );

        $this->start_controls_tabs(
            'title_colors'
        );

        $this->start_controls_tab(
            'title_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'cleenday-core' ),
            ]
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => array(
                    '{{WRAPPER}} .time_line-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'cleenday-core' ),
            ]
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => esc_html__( 'Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .time_line-item:hover .time_line-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Content Styles

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => esc_html__( 'Content', 'cleenday-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'content_typo',
                'selector' => '{{WRAPPER}} .time_line-text',
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label' => esc_html__( 'Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .time_line-text' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Date Styles

        $this->start_controls_section(
            'date_style_section',
            array(
                'label'     => esc_html__( 'Date', 'cleenday-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'date_typo',
                'selector' => '{{WRAPPER}} .time_line-date',
            )
        );

        $this->start_controls_tabs(
            'date_colors'
        );

        $this->start_controls_tab(
            'date_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'cleenday-core' ),
            ]
        );

        $this->add_control(
            'date_color',
            array(
                'label' => esc_html__( 'Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e0e0e0',
                'selectors' => array(
                    '{{WRAPPER}} .time_line-date' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'date_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'cleenday-core' ),
            ]
        );

        $this->add_control(
            'date_hover_color',
            array(
                'label' => esc_html__( 'Color', 'cleenday-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => array(
                    '{{WRAPPER}} .time_line-item:hover .time_line-date' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {
        wp_enqueue_script('appear', get_template_directory_uri() . '/js/jquery.appear.js', array(), false, false);

        $settings = $this->get_settings_for_display();

        // HTML tags allowed for rendering
        $allowed_html = array(
            'a' => array(
                'href' => true,
                'title' => true,
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
            'span' => array(
                'class' => true,
                'style' => true,
            ),
            'p' => array(
                'class' => true,
                'style' => true,
            )
        );

        $this->add_render_attribute( 'timeline-vertical', [
			'class' => [
                'wgl-timeline-vertical',
                'start-'.$settings['start_image'],
                ((bool)$settings['add_appear'] ? 'appear_animation' : ''),
            ],
        ] );

        $this->add_render_attribute( 'start_image', [
			'class' => 'start_image',
            'src' => isset($settings['start_image_thumb']['url']) ? esc_url($settings['start_image_thumb']['url']) : '',
            'alt' => Control_Media::get_image_alt( $settings['start_image_thumb'] ),
        ] );

        $curve_svg = '<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 65.8 167.9" style="enable-background:new 0 0 65.8 167.9;">
            <path d="M8,167.5c-4.2-13.8-6.5-28.5-6.5-43.7C1.5,73.2,26.6,28.4,65,1.2"/>
        </svg>';

        ?>
        <div <?php echo $this->get_render_attribute_string( 'timeline-vertical' ); ?>>

        <div class="time_line-start_image">
            <div class="time_line-curve"><?php echo $curve_svg; ?></div><?php
            if ($settings['start_image'] == 'custom') {?>
                <img <?php echo $this->get_render_attribute_string( 'start_image' ); ?> /><?php
            }?>
        </div>

        <div class="time_line-items_wrap"><?php

        foreach ( $settings['items'] as $index => $item ) {

            $media = $this->get_repeater_setting_key( 'media', 'items' , $index );
            $this->add_render_attribute( $media, [
                'class' => 'time_line-media',
                'style' => [
                    (!empty($item['thumbnail']['url']) ? 'background-image: url('.esc_url($item['thumbnail']['url']).');' : ''),
                    ($settings['bg_position'] != '' ? 'background-position: '.esc_attr($settings['bg_position']).';' : ''),
                ]
            ] );

            $title = $this->get_repeater_setting_key( 'title', 'items' , $index );
            $this->add_render_attribute( $title, [
                'class' => [
                    'time_line-title',
                ],
            ] );

            $item_wrap = $this->get_repeater_setting_key( 'item_wrap', 'items' , $index );
            $this->add_render_attribute( $item_wrap, [
                'class' => [
                    'time_line-item',
                ],
            ] );

            ?>
            <div <?php echo $this->get_render_attribute_string( $item_wrap ); ?>><?php
                if (!empty($item['date'])){?>
                    <div class="time_line-date"><?php echo $item['date'] ?></div><?php
                }?>
                <div <?php echo $this->get_render_attribute_string( $media ); ?>></div>
                <div class="time_line-content"><?php
                    if (!empty($item['content']) || !empty($item['title'])) {
                        if (!empty($item['title'])) {?>
                            <h3 <?php echo $this->get_render_attribute_string( $title ); ?>><?php echo $item['title'] ?></h3><?php
                        }
                        if (!empty($item['content'])){?>
                            <div class="time_line-text"><?php echo wp_kses( $item['content'], $allowed_html );?></div><?php
                        }
                    }?>
                </div>
                <div class="time_line-curve"><?php echo $curve_svg; ?></div>
            </div><?php
        }?>
        </div>
        </div><?php

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
