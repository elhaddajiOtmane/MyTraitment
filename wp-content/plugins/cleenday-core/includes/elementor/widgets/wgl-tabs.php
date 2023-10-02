<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-tabs.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base,Controls_Manager, Frontend, Repeater, Group_Control_Border, Group_Control_Typography, Group_Control_Box_Shadow, Group_Control_Image_Size, Control_Media, Icons_Manager, Utils};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use WglAddons\Includes\{Wgl_Elementor_Helper};

class Wgl_Tabs extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-tabs';
    }

    public function get_title()
    {
        return esc_html__('WGL Tabs', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-tabs';
    }

    public function get_categories()
    {
        return ['wgl-extensions'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'cleenday-core')]
        );

        $this->add_responsive_control(
            'title_align',
            [
                'label' => esc_html__('Title Container Position', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'cleenday-core'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'cleenday-core'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'cleenday-core'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Justify', 'cleenday-core'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
				'desktop_default' => 'flex-start',
				'mobile_default' => 'stretch',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_headings' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => esc_html__('Content Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                    'justify' => [
                        'title' => esc_html__('Justify', 'cleenday-core'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CONTENT
         */

        $this->start_controls_section(
            'section_content_content',
            ['label' => esc_html__('Content', 'cleenday-core')]
        );

        $repeater = new Repeater();
        $repeater->add_control(
			'tab_title',
			[
                'label' => esc_html__('Tab Title', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('TAB TITLE', 'cleenday-core'),
                'dynamic' => ['active' => true],
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_type',
			[
                'label' => esc_html__('Add Icon/Image', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
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
                    ]
                ],
                'default' => '',
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_fontawesome',
			[
                'label'       => esc_html__( 'Icon', 'cleenday-core' ),
                'type'        => Controls_Manager::ICONS,
                'label_block' => true,
                'condition'     => [
                    'tabs_tab_icon_type'  => 'font',
                ],
                'description' => esc_html__( 'Select icon from Fontawesome library.', 'cleenday-core' ),
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_thumbnail',
			[
                'label'       => esc_html__( 'Image', 'cleenday-core' ),
                'type'        => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition'     => [
                    'tabs_tab_icon_type'   => 'image',
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
        );
        $repeater->add_control(
			'tabs_content_type',
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
			'tabs_content_templates',
			[
                'label' => esc_html__('Choose Template', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [
                    'tabs_content_type' => 'template',
                ],
			]
        );
        $repeater->add_control(
			'tabs_content',
			[
                'label' => esc_html__('Tab Content', 'cleenday-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'cleenday-core'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'tabs_content_type' => 'content',
                ],
			]
        );

        $this->add_control(
            'tabs_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['tab_title' => esc_html__('Tab Title 1', 'cleenday-core')],
                    ['tab_title' => esc_html__('Tab Title 2', 'cleenday-core')],
                    ['tab_title' => esc_html__('Tab Title 3', 'cleenday-core')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{tab_title}}',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> TITLES CONTAINER
         */

        $this->start_controls_section(
            'section_style_title_container',
            [
                'label' => esc_html__('Titles Container', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_container_background',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_headings' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_container_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_headings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_container_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_headings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_container',
                'selector' => '{{WRAPPER}} .wgl-tabs_headings',
            ]
        );

        $this->add_control(
            'title_container_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_headings' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> TITLE
         */

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .tab_title',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => '‹h1›',
                    'h2' => '‹h2›',
                    'h3' => '‹h3›',
                    'h4' => '‹h4›',
                    'h5' => '‹h5›',
                    'h6' => '‹h6›',
                    'div' => '‹div›',
                ],
                'default' => 'h4',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '10',
                    'bottom' => '0',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '16',
                    'bottom' => '2',
                    'left' => '16',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_min_width',
            [
                'label' => esc_html__('Min Width', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 250],
                    '%' => ['min' => 10, 'max' => 100],
                ],
                'mobile_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_control(
            'title_bottom_line',
            [
                'label' => esc_html__('Add Title Bottom Line', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->start_controls_tabs(
            'tabs_header_tabs',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tabs_header_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header, {{WRAPPER}} .wgl-tabs_icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#f7f5f7',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            't_title_line_color',
            [
                'label' => esc_html__('Title Bottom Line Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [ 'title_bottom_line' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '6',
                    'bottom' => '6',
                    'left' => '6',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wgl-tabs_headings' => 'border-radius: calc({{TOP}}{{UNIT}} + {{title_container_padding.top}}px)
                                                                        calc({{RIGHT}}{{UNIT}} + {{title_container_padding.right}}px)
                                                                        calc({{BOTTOM}}{{UNIT}} + {{title_container_padding.bottom}}px)
                                                                        calc({{LEFT}}{{UNIT}} + {{title_container_padding.left}}px);',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_idle',
                'selector' => '{{WRAPPER}} .wgl-tabs_header .wgl-tabs_header',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_idle',
                'selector' => '{{WRAPPER}} .wgl-tabs_header .wgl-tabs_header',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_header_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Title Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover, {{WRAPPER}} .wgl-tabs_header:hover .wgl-tabs_icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            't_title_line_color_hover',
            [
                'label' => esc_html__('Title Bottom Line Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [ 'title_bottom_line' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_hover',
                'selector' => '{{WRAPPER}} .wgl-tabs_header:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_hover',
                'selector' => '{{WRAPPER}} .wgl-tabs_header:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            't_header_active',
            ['label' => esc_html__('Active', 'cleenday-core')]
        );

        $this->add_control(
            'title_color_active',
            [
                'label' => esc_html__('Title Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active, {{WRAPPER}} .wgl-tabs_header.active .wgl-tabs_icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_active',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            't_title_line_color_active',
            [
                'label' => esc_html__('Title Bottom Line Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [ 'title_bottom_line' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_radius_active',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_active',
                'selector' => '{{WRAPPER}} .wgl-tabs_header.active',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_active',
                'selector' => '{{WRAPPER}} .wgl-tabs_header.active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> TITLE ICON
         */

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Title Icon', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_icon_size',
            [
                'label' => esc_html__('Icon Size', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'default' => [
                    'size' => 26,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_icon:not(.wgl-tabs_icon-image)' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_icon_position',
            [
                'label' => esc_html__('Icon/Image Position', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'column-reverse' => [
                        'title' => esc_html__('Top', 'cleenday-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'row' => [
                        'title' => esc_html__('Right', 'cleenday-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Bottom', 'cleenday-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Left', 'cleenday-core'),
                        'icon' => 'eicon-h-align-left',
                    ]
                ],
                'default' => 'row-reverse',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_icon_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '10',
                    'bottom' => '0',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_title_icon');

        $this->start_controls_tab(
            'tab_icon_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'title_icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-tabs_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'title_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover .wgl-tabs_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-tabs_header:hover .wgl-tabs_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_active',
            ['label' => esc_html__('Active', 'cleenday-core')]
        );

        $this->add_control(
            'title_icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active .wgl-tabs_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-tabs_header.active .wgl-tabs_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content',
                'selector' => '{{WRAPPER}} .wgl-tabs_content',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '31',
                    'right' => '0',
                    'bottom' => '5',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Content Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg',
            [
                'label' => esc_html__('Content Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_content_border',
                'selector' => '{{WRAPPER}} .wgl-tabs_content',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $this->add_render_attribute('tabs', [
            'class' => [
                'wgl-tabs',
					$_s['title_align'] ? 'title_align-' . $_s['title_align'] : '',
					$_s['title_align_tablet'] ? 'title_align-tablet-' . $_s['title_align_tablet'] : '',
					$_s['title_align_mobile'] ? 'title_align-mobile-' . $_s['title_align_mobile'] : '',
					$_s['title_icon_position'] ? 'icon_position-' . $_s['title_icon_position'] : '',
					$_s['title_icon_position_tablet'] ? 'icon_position-tablet-' . $_s['title_icon_position_tablet'] : '',
					$_s['title_icon_position_mobile'] ? 'icon_position-mobile-' . $_s['title_icon_position_mobile'] : '',
            ],
        ]);

        // Render
        echo '<div ', $this->get_render_attribute_string('tabs'), '>'; ?>
            <div class="wgl-tabs_headings"><?php
                foreach ($_s['tabs_tab'] as $index => $item) :

                    $tab_count = $index + 1;
                    $tab_title_key = $this->get_repeater_setting_key('tab_title', 'tabs_tab', $index);
                    $this->add_render_attribute($tab_title_key, [
                        'class' => 'wgl-tabs_header',
                        'data-tab-id' => 'wgl-tab_' . $id_int . $tab_count,
                    ]);

                    ?>
                    <<?php echo $_s['title_tag']; ?> <?php echo $this->get_render_attribute_string($tab_title_key); ?>>
                        <span class="tab_title"><?php echo $item['tab_title'] ?></span>

                        <?php
                        // ↓ Icon|Image
                        if ($item['tabs_tab_icon_type'] != '') {
                            if (
                                $item['tabs_tab_icon_type'] == 'font'
                                && !empty($item['tabs_tab_icon_fontawesome'])
                            ) {
                                $icon_font = $item['tabs_tab_icon_fontawesome'];
                                $icon_out = '';
                                // add icon migration
                                $migrated = isset( $item['__fa4_migrated'][$item['tabs_tab_icon_fontawesome']] );
                                $is_new = Icons_Manager::is_migration_allowed();
                                if ($is_new || $migrated) {
                                    ob_start();
                                    Icons_Manager::render_icon($item['tabs_tab_icon_fontawesome'], ['aria-hidden' => 'true']);
                                    $icon_out .= ob_get_clean();
                                } else {
                                    $icon_out .= '<i class="icon ' . esc_attr($icon_font) . '"></i>';
                                }

                                echo '<span class="wgl-tabs_icon">',
                                    $icon_out,
                                '</span>';
                            }
                            if (
                                $item['tabs_tab_icon_type'] == 'image'
                                && !empty($item['tabs_tab_icon_thumbnail']['url'])
                            ) {
                                $this->add_render_attribute('thumbnail', 'src', $item['tabs_tab_icon_thumbnail']['url']);
                                $this->add_render_attribute('thumbnail', 'alt', Control_Media::get_image_alt($item['tabs_tab_icon_thumbnail']));
                                $this->add_render_attribute('thumbnail', 'title', Control_Media::get_image_title($item['tabs_tab_icon_thumbnail']));

                                echo '<span class="wgl-tabs_icon wgl-tabs_icon-image">',
                                    Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'tabs_tab_icon_thumbnail'),
                                '</span>';
                            }
                        }
                        // ↑ icon|image

                    echo '</', $_s['title_tag'], '>';

                endforeach; ?>
            </div>

            <div class="wgl-tabs_content-wrap"><?php
                foreach ( $_s['tabs_tab'] as $index => $item ) :

                    $tab_count = $index + 1;
                    $tab_content_key = $this->get_repeater_setting_key( 'tab_content', 'tabs_tab', $index );
                    $this->add_render_attribute($tab_content_key, [
                        'data-tab-id' => 'wgl-tab_' . $id_int . $tab_count,
                        'class' => ['wgl-tabs_content'],
                    ]);

                    ?>
                    <div <?php echo $this->get_render_attribute_string( $tab_content_key ); ?>>
                    <?php
                        if ( $item['tabs_content_type'] == 'content' ) {
                            echo do_shortcode( $item['tabs_content'] );
                        } else if ( $item['tabs_content_type'] == 'template' ) {
                            $id = $item['tabs_content_templates'];
                            $wgl_frontend = new Frontend;
                            echo $wgl_frontend->get_builder_content_for_display( $id, false );
                        }
                    ?>
                    </div>

                <?php endforeach;
            echo '</div>';

        echo '</div>';
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
