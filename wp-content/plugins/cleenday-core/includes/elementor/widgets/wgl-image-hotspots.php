<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-image-hotspots.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Cleenday_Theme_Helper;
use Elementor\{Widget_Base, Controls_Manager, Repeater, Icons_Manager, Utils, Control_Media, Frontend};
use Elementor\{Group_Control_Border, Group_Control_Box_Shadow, Group_Control_Image_Size, Group_Control_Css_Filter, Group_Control_Background, Group_Control_Typography};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use WglAddons\Includes\{Wgl_Elementor_Helper};

class Wgl_Image_Hotspots extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-image-hotspots';
    }

    public function get_title()
    {
        return esc_html__('WGL Image HotSpots', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-image-hotspots';
    }

    public function get_categories()
    {
        return ['wgl-extensions'];
    }

    public function get_script_depends()
    {
        return ['jquery-appear'];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> IMAGE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_image',
            ['label' => esc_html__('Image', 'cleenday-core')]
        );

        $this->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Image', 'cleenday-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'label' => esc_html__('Image Size', 'cleenday-core'),
                'default' => 'full',
            ]
        );

        $this->add_responsive_control(
            'image_align',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'cleenday-core'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'cleenday-core'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'cleenday-core'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'prefix_class' => 'a%s',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> HOTSPOTS
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_hotspots',
            ['label' => esc_html__('HotSpots', 'cleenday-core')]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs(
            'hotspot_tabs'
        );

        $repeater->start_controls_tab(
            'hotspot_tab_content',
            ['label' => esc_html__('Content', 'cleenday-core')]
        );

        $repeater->add_control(
            'hotspot_type',
            [
                'label' => esc_html__('Type', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'cleenday-core'),
                    'icon' => esc_html__('Icon', 'cleenday-core'),
                    'image' => esc_html__('Image', 'cleenday-core'),
                ],
                'default' => 'default',
            ]
        );

        $repeater->add_control(
            'hotspot_icon',
            [
                'label' => esc_html__('Icon', 'cleenday-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'hotspot_type' => 'icon',
                ],
                'label_block' => true,
                'description' => esc_html__('Select icon from Fontawesome library.', 'cleenday-core'),
            ]
        );

        $repeater->add_control(
            'hotspot_image',
            [
                'label' => esc_html__('Image', 'cleenday-core'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'hotspot_type' => 'image',
                ],
                'label_block' => true,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_text',
            [
                'label' => esc_html__('Text', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_responsive_control(
            'hotspot_media_position',
            [
                'label' => esc_html__('Media Position', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left' => esc_html__('Left', 'cleenday-core'),
                    'top' => esc_html__('Top', 'cleenday-core'),
                    'right' => esc_html__('Right', 'cleenday-core'),
                    'bottom' => esc_html__('Bottom', 'cleenday-core'),
                ],
                'default' => 'left',
                'condition'        => [
                    'hotspot_text!' => '',
                    'hotspot_type!' => 'default',
                ]
            ]
        );

        $repeater->add_control(
            'hotspot_link',
            [
                'label' => esc_html__('Link', 'cleenday-core'),
                'type' => Controls_Manager::URL,
                'label_block' => false,
                'placeholder' => esc_attr__('https://your-link.com', 'cleenday-core'),
                'default' => ['is_external' => 'true'],
            ]
        );

        $repeater->add_control(
            'hotspot_content_type',
            [
                'label' => esc_html__('Content Type', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => esc_html__('Content', 'cleenday-core'),
                    'template' => esc_html__('Saved Templates', 'cleenday-core'),
                ],
                'default' => 'content',
            ]
        );
        $repeater->add_control(
            'hotspot_content_templates',
            [
                'label' => esc_html__('Choose Template', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [
                    'hotspot_content_type' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_content',
            [
                'label' => esc_html__('Content', 'cleenday-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'cleenday-core'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'hotspot_content_type' => 'content',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'hotspot_position',
            ['label' => esc_html__('Position', 'cleenday-core')]
        );

        $repeater->add_responsive_control(
            'hotspot_position_horizontal',
            [
                'label'     => esc_html__('Horizontal position (%)', 'cleenday-core'),
                'type'         => Controls_Manager::SLIDER,
                'default'    => [
                    'size'    => 50,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 100,
                        'step'    => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%;',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'hotspot_position_vertical',
            [
                'label'     => esc_html__('Vertical position (%)', 'cleenday-core'),
                'type'         => Controls_Manager::SLIDER,
                'default'    => [
                    'size'    => 50,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 100,
                        'step'    => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%;',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'tooltips_cur_position',
            [
                'label' => esc_html__('ToolTip Position', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'cleenday-core'),
                    'left' => esc_html__('Left', 'cleenday-core'),
                    'top' => esc_html__('Top', 'cleenday-core'),
                    'right' => esc_html__('Right', 'cleenday-core'),
                    'bottom' => esc_html__('Bottom', 'cleenday-core'),
                ],
                'default' => '',
            ]
        );

        $repeater->add_responsive_control(
            'tooltips_cur_margin',
            [
                'label' => esc_html__('ToolTip Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'tooltips_cur_position!' => '',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'hotspot_cur_styles',
            ['label' => esc_html__('Style', 'cleenday-core')]
        );

        $repeater->add_responsive_control(
            'hotspot_icon_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_point-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_cur_style',
            [
                'label' => esc_html__('Colors', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'cleenday-core'),
                    'custom' => esc_html__('Custom', 'cleenday-core'),
                ],
                'default' => 'default',
            ]
        );

        $repeater->add_control(
            'icon_c_color_idle',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_media-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_media-wrap svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'hotspot_cur_style' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'text_c_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_point-text' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'hotspot_cur_style' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'text_c_background_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_point-wrap' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'hotspot_cur_style' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'current_style_divider',
            ['type' => Controls_Manager::DIVIDER]
        );

        $repeater->add_control(
            'icon_c_color_hover',
            [
                'label' => esc_html__('Hover Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.hotspots_item:hover .hotspots_media-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.hotspots_item:hover .hotspots_media-wrap svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'hotspot_cur_style' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'text_c_color_hover',
            [
                'label' => esc_html__('Hover Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.hotspots_item:hover .hotspots_point-text' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'hotspot_cur_style' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'text_c_background_hover',
            [
                'label' => esc_html__('Hover Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.hotspots_item:hover .hotspots_point-wrap' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'hotspot_cur_style' => 'custom',
                ],
            ]
        );

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'items_list',
            [
                'label' => esc_html__('HotSpots', 'cleenday-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item' => 1
                    ],
                    [
                        'item' => 2
                    ],
                    [
                        'item' => 3
                    ],
                ],
                'title_field' => '',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> TOOLTIPS
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_tooltips',
            ['label' => esc_html__('ToolTips', 'cleenday-core')]
        );

        $this->add_responsive_control(
            'show_always',
            [
                'label' => esc_html__('Show Always', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_responsive_control(
            'tooltips_position',
            [
                'label' => esc_html__('Position', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left' => esc_html__('Left', 'cleenday-core'),
                    'top' => esc_html__('Top', 'cleenday-core'),
                    'right' => esc_html__('Right', 'cleenday-core'),
                    'bottom' => esc_html__('Bottom', 'cleenday-core'),
                ],
                'default' => 'top',
            ]
        );

        $this->add_responsive_control(
            'tooltips_animation',
            [
                'label' => esc_html__('Animation', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade' => esc_html__('Fade', 'cleenday-core'),
                    'zoom' => esc_html__('Zoom', 'cleenday-core'),
                    'to_left' => esc_html__('To Left', 'cleenday-core'),
                    'to_top' => esc_html__('To Top', 'cleenday-core'),
                    'to_right' => esc_html__('To Right', 'cleenday-core'),
                    'to_bottom' => esc_html__('To Bottom', 'cleenday-core'),
                    'shake' => esc_html__('Shake', 'cleenday-core'),
                ],
                'default' => 'fade',
                'condition' => [
                    'show_always' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltips_align',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'cleenday-core'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'cleenday-core'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'cleenday-core'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center'
            ]
        );

        $this->add_responsive_control(
            'tooltips_max_width',
            [
                'label' => esc_html__('Max Width', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500
                    ],
                ],
                'default' => [
                    'size' => 250,
                    'unit' => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_content' => 'width: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> ADDITIONAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_additional',
            ['label' => esc_html__('Additional Options', 'cleenday-core')]
        );

        $this->add_control(
            'appear_animation',
            [
                'label' => esc_html__('Appear Animation', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'interval',
            [
                'label' => esc_html__('HotSpots Appearing Interval (ms)', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 2500,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'size' => 700,
                    'unit' => 'px'
                ],
                'condition' => [
                    'appear_animation!' => '',
                ],
            ]
        );

        $this->add_control(
            'transition',
            [
                'label' => esc_html__('Transition Duration (ms)', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 2500,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'size' => 700,
                    'unit' => 'px'
                ],
                'condition' => [
                    'appear_animation!' => '',
                ],
            ]
        );

        $this->add_control(
            'loop_animation',
            [
                'label' => esc_html__('HotSpots Loop Animation', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'loop_interval',
            [
                'label' => esc_html__('HotSpots Loop Interval (ms)', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 2500,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'size' => 800,
                    'unit' => 'px'
                ],
                'condition' => [
                    'loop_animation!' => '',
                ],
            ]
        );

        $this->add_control(
            'loop_duration',
            [
                'label' => esc_html__('HotSpots Loop Duration (seconds)', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 200,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 5,
                    'unit' => 'px'
                ],
                'condition' => [
                    'loop_animation!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'loop_animation_option',
            [
                'label' => esc_html__('Animation', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'pulse' => esc_html__('Pulse', 'cleenday-core'),
                    'shake' => esc_html__('Shake', 'cleenday-core'),
                    'flash' => esc_html__('Flash', 'cleenday-core'),
                    'zoom' => esc_html__('Zoom', 'cleenday-core'),
                    'rubber' => esc_html__('Rubber', 'cleenday-core'),
                    'swing' => esc_html__('Swing', 'cleenday-core'),
                ],
                'default' => 'pulse',
                'condition' => [
                    'loop_animation!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> IMAGE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'hotspots_image_border',
                'selector' => '{{WRAPPER}} .hotspots_image',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .hotspots_image',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .hotspots_image',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> HOTSPOTS
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_hotspots',
            [
                'label' => esc_html__('HotSpots', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hotspots_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '6',
                    'bottom' => '6',
                    'left' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'hotspots_typo',
                'selector' => '{{WRAPPER}} .hotspots_point-text',
            ]
        );

        $this->add_control(
            'text_tag',
            [
                'label' => esc_html__('Text Tag', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => '‹h1›',
                    'h2' => '‹h2›',
                    'h3' => '‹h3›',
                    'h4' => '‹h4›',
                    'h5' => '‹h5›',
                    'h6' => '‹h6›',
                    'span' => '‹span›',
                    'div' => '‹div›',
                ],
                'default' => 'h4',
            ]
        );

        $this->add_responsive_control(
            'hotspots_icon_size',
            [
                'label' => esc_html__('Icon Size', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 14, 'max' => 200],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_hotspots'
        );

        $this->start_controls_tab(
            'tab_hotspots_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hotspots_media-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hotspots_media-wrap svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspots_bg_idle',
            [
                'label' => esc_html__('HotSpots Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspots_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'hotspots_border_idle',
                'fields_options' => [
                    'border' => ['default' => 'solid'],
                    'width' => [
                        'default' => [
                            'top' => 2,
                            'right' => 2,
                            'bottom' => 2,
                            'left' => 2,
                        ],
                    ],
                    'color' => [
                        'default' => '#ffffff'
                    ],
                ],
                'selector' => '{{WRAPPER}} .hotspots_point-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hotspots_shadow_idle',
                'selector' => '{{WRAPPER}} .hotspots_point-wrap',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hotspots_item:hover .hotspots_media-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hotspots_item:hover .hotspots_media-wrap svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hotspots_item:hover .hotspots_point-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspots_bg_hover',
            [
                'label' => esc_html__('HotSpots Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hotspots_item:hover .hotspots_point-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspots_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_item:hover .hotspots_point-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'hotspots_border_hover',
                'selector' => '{{WRAPPER}} .hotspots_item:hover .hotspots_point-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hotspots_shadow_hover',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes'
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 0,
                            'blur' => 0,
                            'spread' => 30,
                            'color' => 'rgba(' . Cleenday_Theme_Helper::HexToRGB(Cleenday_Globals::get_primary_color()) . ', 0.15)',
                        ]
                    ]
                ],
                'selector' => '{{WRAPPER}} .hotspots_item:hover .hotspots_point-wrap',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TOOLTIPS
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_tooltips',
            [
                'label' => esc_html__('Tooltips', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content',
                'selector' => '{{WRAPPER}} .hotspots_content-inner',
            ]
        );

        $this->add_control(
            'tooltips_color',
            [
                'label' => esc_html__('Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hotspots_content-inner' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tooltips_background',
                'label' => esc_html__('Background', 'cleenday-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .hotspots_content-inner',
            ]
        );

        $this->add_control(
            'tooltips_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tooltips_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tooltips_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'hotspots_tooltips_border',
                'selector' => '{{WRAPPER}} .hotspots_content-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tooltips_shadow',
                'selector' => '{{WRAPPER}} .hotspots_content-inner',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $transition_delay = $animation_delay = 0;
        $transition_delay_s = $animation_delay_s = '';
        $transition = !empty($_s['transition']['size']) ? ' transition: all ' . $_s['transition']['size'] . 'ms;' : '';
        $loop_duration = !empty($_s['loop_duration']['size']) ? ' animation-duration: ' . $_s['loop_duration']['size'] . 's;' : '';

        // Main Container
        $this->add_render_attribute('hotspots_container', [
            'class' => [
                'hotspots_container',
                $_s['appear_animation'] ? 'appear_animation' : '',
                $_s['loop_animation'] ? 'loop_animation' : '',
                $_s['loop_animation'] ? 'loop_animation-'.$_s['loop_animation_option'] : '',
            ],
        ]);

        // Main Image
        $this->add_render_attribute('thumbnail', [
            'class' => [
                'hotspots_image',
            ],
            'src' => !empty($_s['thumbnail']['url']) ? esc_url($_s['thumbnail']['url']) : '',
            'alt' => Control_Media::get_image_alt($_s['thumbnail']),
        ]);

        // Render
        echo '<div class="wgl-image-hotspots">';
        echo '<div class="hotspots_image-wrap">';
        echo '<img ', $this->get_render_attribute_string('thumbnail'), ' />';
        echo '</div>';
        echo '<div ', $this->get_render_attribute_string('hotspots_container'), '>';

        foreach ($_s['items_list'] as $index => $item) {

            if (!empty($_s['interval']['size'])) {
                $transition_delay = $transition_delay + $_s['interval']['size'];
                $transition_delay_s = ' transition-delay: ' . $transition_delay . 'ms;';
            }

            if (!empty($_s['loop_interval']['size'])) {
                $animation_delay = $animation_delay + $_s['loop_interval']['size'];
                $animation_delay_s = ' animation-delay: ' . $animation_delay . 'ms;';
            }

            $items_styles = $transition . $transition_delay_s . $animation_delay_s . $loop_duration;

            $this->add_render_attribute('hotspots_content', [
                'class' => [
                    'hotspots_content',
                    (bool)$_s['show_always'] ? 'desktop-tooltips-show' : 'desktop-tooltips-hover',
                    (bool)$_s['show_always_tablet'] ? 'tablet-tooltips-show' : 'tablet-tooltips-hover',
                    (bool)$_s['show_always_mobile'] ? 'mobile-tooltips-show' : 'mobile-tooltips-hover',
                    'a' . $_s['tooltips_align'],
                    'animation-' . $_s['tooltips_animation'],
                ],
            ]);

            $hotspots_item = $this->get_repeater_setting_key('hotspot_title', 'items_list', $index);
            $this->add_render_attribute($hotspots_item, [
                'class' => [
                    'hotspots_item',
                    'elementor-repeater-item-' . $item['_id'],
                    !empty($item['hotspot_media_position']) ? 'm-desktop-' . $item['hotspot_media_position'] : '',
                    !empty($item['hotspot_media_position_tablet']) ? 'm-tablet-' . $item['hotspot_media_position_tablet'] : '',
                    !empty($item['hotspot_media_position_mobile']) ? 'm-mobile-' . $item['hotspot_media_position_mobile'] : '',
                    !empty($_s['tooltips_position']) ? 'tt-desktop-' . $_s['tooltips_position'] : '',
                    !empty($_s['tooltips_position_tablet']) ? 'tt-tablet-' . $_s['tooltips_position_tablet'] : '',
                    !empty($_s['tooltips_position_mobile']) ? 'tt-mobile-' . $_s['tooltips_position_mobile'] : '',
                    !empty($item['tooltips_cur_position']) ? 'tt-c-desktop-' . $item['tooltips_cur_position'] : '',
                    !empty($item['tooltips_cur_position_tablet']) ? 'tt-c-tablet-' . $item['tooltips_cur_position_tablet'] : '',
                    !empty($item['tooltips_cur_position_mobile']) ? 'tt-c-mobile-' . $item['tooltips_cur_position_mobile'] : ''
                ],
                'style' => $items_styles
            ]);

            if (!empty($item['hotspot_link']['url'])) {
                $hotspot_link = $this->get_repeater_setting_key('hotspot_link', 'items', $index);
                $this->add_render_attribute($hotspot_link, 'class', 'hotspots_link');
                $this->add_link_attributes($hotspot_link, $item['hotspot_link']);
            }

            // Icon
            if ($item['hotspot_type'] == 'icon' && (!empty($item['hotspot_icon']))) {
                $hotspot_icon = '';
                $icon_font = $item['hotspot_icon'];
                // add icon migration
                $migrated = isset($item['__fa4_migrated'][$item['hotspot_icon']]);
                $is_new = Icons_Manager::is_migration_allowed();
                if ($is_new || $migrated) {
                    ob_start();
                    Icons_Manager::render_icon($item['hotspot_icon'], ['aria-hidden' => 'true']);
                    $hotspot_icon = ob_get_clean();
                } else {
                    $hotspot_icon = '<i class="icon ' . esc_attr($icon_font) . '"></i>';
                }
                ob_start();
                echo '<span class="hotspots_point-icon hotspots_point-icon_font">';
                echo $hotspot_icon;
                echo '</span>';
                $media_out = ob_get_clean();
            }
            // Image
            if ($item['hotspot_type'] == 'image' && !empty($item['hotspot_image'])) {
                if (!empty($item['hotspot_image']['url'])) {
                    $this->add_render_attribute('thumbnail', 'src', $item['hotspot_image']['url']);
                    $this->add_render_attribute('thumbnail', 'alt', Control_Media::get_image_alt($item['hotspot_image']));
                    $this->add_render_attribute('thumbnail', 'title', Control_Media::get_image_title($item['hotspot_image']));

                    ob_start();
                    echo '<span class="hotspots_point-icon hotspots_point-icon_image">';
                    echo Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'hotspot_image');
                    echo '</span>';
                    $media_out = ob_get_clean();
                }
            }
            // Content
            if ($item['hotspot_content_type'] == 'content') {
                $content_out = $item['hotspot_content'];
            } else if ($item['hotspot_content_type'] == 'template') {
                $id = $item['hotspot_content_templates'];
                $wgl_frontend = new Frontend;
                ob_start();
                echo $wgl_frontend->get_builder_content_for_display($id, false);
                $content_out = ob_get_clean();
            }

            // Render
            echo '<div ', $this->get_render_attribute_string($hotspots_item), '>';
            if (!empty($item['hotspot_link']['url'])) {
                echo '<a ', $this->get_render_attribute_string($hotspot_link), '>';
            }
            echo '<div class="hotspots_point-wrap">';
            echo '<div class="hotspots_media-wrap">';
            if ($item['hotspot_type'] != 'default' && !empty($media_out)) {
                echo $media_out;
            } else {
                echo '<span class="hotspots_point-icon hotspots_point-icon_default"></span>';
            }
            echo '</div>';
            echo !empty($item['hotspot_text']) ? '<' . $_s['text_tag'] . ' class="hotspots_point-text">' . $item['hotspot_text'] . '</' . $_s['text_tag'] . '>' : '';
            echo '</div>'; // hotspot point
            if (!empty($item['hotspot_link']['url'])) {
                echo '</a>';
            }
            if (!empty($content_out)) {
                echo '<div ', $this->get_render_attribute_string('hotspots_content'), '>';
                echo '<div class="hotspots_content-inner">';
                echo $content_out;
                echo '</div>';
                echo '</div>'; //hotspot content
            }
            echo '</div>'; // hotspot item
        }

        echo '</div>'; // hotspots items
        echo '</div>'; // wgl-image-hotspots wrapper
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
