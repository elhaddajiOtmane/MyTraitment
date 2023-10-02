<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-toggle-accordion.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager, Frontend, Repeater, Group_Control_Border, Group_Control_Typography, Group_Control_Box_Shadow};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use WglAddons\Includes\Wgl_Elementor_Helper;

class Wgl_Toggle_Accordion extends Widget_Base
{
    public function get_name() {
        return 'wgl-toggle-accordion';
    }

    public function get_title() {
        return esc_html__('WGL Toggle/Accordion', 'cleenday-core');
    }

    public function get_icon() {
        return 'wgl-toggle-accordion';
    }

    public function get_categories() {
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

        $this->add_control(
            'acc_type',
            [
                'label' => esc_html__('Type', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'accordion' => esc_html__('Accordion', 'cleenday-core'),
                    'toggle' => esc_html__('Toggle', 'cleenday-core'),
                ],
                'default' => 'accordion',
            ]
        );

        $this->add_control(
            'heading_desktop',
            [
                'label' => esc_html__('Icon Settings', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'enable_acc_icon',
            [
                'label' => esc_html__('Type', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'cleenday-core'),
                    'plus' => esc_html__('Plus/Minus', 'cleenday-core'),
                    'custom' => esc_html__('Custom', 'cleenday-core'),
                ],
                'default' => 'custom',
            ]
        );

        $this->add_control(
            'acc_icon',
            [
                'label' => esc_html__('Icon', 'cleenday-core'),
                'type' => Controls_Manager::ICON,
                'condition' => ['enable_acc_icon' => 'custom'],
                'include' => [
                    'flaticon flaticon-arrow',
                    'fa fa-chevron-right',
                    'fa fa-plus',
                    'fa fa-long-arrow-alt-right',
                    'fa fa-chevron-circle-right',
                    'fa fa-arrow-right',
                    'fa fa-arrow-circle-right',
                    'fa fa-angle-right',
                    'fa fa-angle-double-right',
                ],
	            'default' => 'flaticon flaticon-arrow',
            ]
        );

        $this->add_control(
            'icon_style',
            [
                'label' => esc_html__('Style', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['enable_acc_icon!' => 'none'],
                'options' => [
                    'default' => esc_html__('Default', 'cleenday-core'),
                    'stacked' => esc_html__('Stacked', 'cleenday-core'),
                    'framed' => esc_html__('Framed', 'cleenday-core'),
                ],
                'default' => 'default',
                'prefix_class' => 'elementor-view-'
            ]
        );

        $this->add_control(
            'icon_alignment',
            [
                'label' => esc_html__('Position', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['enable_acc_icon!' => 'none'],
                'options' => [
                    'order: 1' => [
                        'title' => esc_html__('Left', 'cleenday-core'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'order: 0; flex-grow: 1' => [
                        'title' => esc_html__('Right', 'cleenday-core'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'order: 0; flex-grow: 1',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_title' => '{{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CONTENT
         */

        $this->start_controls_section(
            'section_content_content',
            ['label' => esc_html__('Content', 'cleenday-core') ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
			'acc_tab_title',
			[
                'label' => esc_html__('Panel Title', 'cleenday-core'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 1,
                'default' => esc_html__('Panel Title', 'cleenday-core'),
                'dynamic' => ['active' => true],
			]
        );
        $repeater->add_control(
			'title_prefix',
			[
                'label' => esc_html__('Title Prefix', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
			]
        );
        $repeater->add_control(
			'acc_tab_def_active',
			[
                'label' => esc_html__('Active as Default', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
			]
        );
        $repeater->add_control(
			'acc_content_type',
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
			'acc_content_templates',
			[
                'label' => esc_html__('Choose Template', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [
                    'acc_content_type' => 'template',
                ],
			]
        );
        $repeater->add_control(
			'acc_content',
			[
                'label' => esc_html__('Tab Content', 'cleenday-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'cleenday-core'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'acc_content_type' => 'content',
                ],
			]
        );

        $this->add_control(
            'acc_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'fields' => $repeater->get_controls(),
                'title_field' => '{{acc_tab_title}}',
                'default' => [
                    [
                        'acc_tab_title' => esc_html__('Panel Title 1', 'cleenday-core'),
                        'acc_tab_def_active' => 'yes',
                    ],
                    [
                        'acc_tab_title' => esc_html__('Panel Title 2', 'cleenday-core'),
                    ],
                    [
                        'acc_tab_title' => esc_html__('Panel Title 3', 'cleenday-core'),
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> PANEL
         */

        $this->start_controls_section(
            'section_style_panel',
            [
                'label' => esc_html__('Panel', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'panel_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'panel_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '5',
                    'right' => '5',
                    'bottom' => '5',
                    'left' => '5',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_panel',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_panel_idle',
            ['label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_responsive_control(
            'panel_padding_idle',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'panel_bg_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'panel_idle',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_panel_hover',
            ['label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_responsive_control(
            'panel_padding_hover',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => 'vertical',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'panel_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'panel_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_panel_active',
            ['label' => esc_html__('Active', 'cleenday-core') ]
        );

        $this->add_responsive_control(
            'panel_padding_active',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => 'vertical',
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '21',
                    'bottom' => '23',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'panel_bg_active',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'panel_active',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel.active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
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
                'name' => 'acc_title_typo',
                'selector' => '{{WRAPPER}} .wgl-accordion_title',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('HTML Tag', 'cleenday-core'),
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
            'title_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
	                'top' => '10',
	                'right' => '14',
	                'bottom' => '10',
	                'left' => '20',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_title');

        $this->start_controls_tab(
            'tab_title_idle',
            ['label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Title Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
	            'default' => '#f7f5f7',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
	                'top' => '6',
	                'left' => '6',
	                'right' => '6',
	                'bottom' => '6',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_idle',
                'selector' => '{{WRAPPER}} .wgl-accordion_header',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            ['label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Title Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
	            'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_title_border_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_header:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_active',
            ['label' => esc_html__('Active', 'cleenday-core') ]
        );

        $this->add_control(
            'title_color_active',
            [
                'label' => esc_html__('Title Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
	            'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .active .wgl-accordion_header' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_active',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .active .wgl-accordion_header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_title_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .active .wgl-accordion_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_title_border_active',
                'selector' => '{{WRAPPER}} .active .wgl-accordion_header',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> TITLE PREFIX
         */

        $this->start_controls_section(
            'section_style_title_pref',
            [
                'label' => esc_html__('Title Prefix', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'acc_title_pref_typo',
                'selector' => '{{WRAPPER}} .wgl-accordion_title-prefix',
            ]
        );

        $this->start_controls_tabs('tabs_prefix');

        $this->start_controls_tab(
            'tab_prefix_idle',
            ['label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_control(
            'prefix_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_prefix_hover',
            ['label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_control(
            'prefix_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_prefix_active',
            ['label' => esc_html__('Active', 'cleenday-core') ]
        );

        $this->add_control(
            'prefix_color_active',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> ICON
         */

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Font Size', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['enable_acc_icon' => 'custom'],
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 100 ],
                ],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
	                'top' => '0',
	                'right' => '0',
	                'bottom' => '0',
	                'left' => '10',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
	                'top' => '5',
	                'right' => '4',
	                'bottom' => '4',
	                'left' => '5',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_width',
            [
                'label' => esc_html__('Border Width', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_radius',
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
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_icon');

        $this->start_controls_tab(
            'tab_icon_idle',
            ['label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .icon-plus .wgl-accordion_icon:before,{{WRAPPER}} .icon-plus .wgl-accordion_icon:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_idle',
            [
                'label' => esc_html__('Border Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_idle',
                'selector' => '{{WRAPPER}} .wgl-accordion_icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_icon:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .icon-plus .wgl-accordion_header:hover .wgl-accordion_icon:before, {{WRAPPER}} .icon-plus .wgl-accordion_header:hover .wgl-accordion_icon:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_hover',
            [
                'label' => esc_html__('Border Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_active',
            ['label' => esc_html__('Active', 'cleenday-core') ]
        );

        $this->add_control(
            'icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_icon:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .icon-plus .wgl-accordion_panel.active .wgl-accordion_icon:before,
                     {{WRAPPER}} .icon-plus .wgl-accordion_panel.active .wgl-accordion_icon:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_active',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_active',
            [
                'label' => esc_html__('Border Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_active',
                'selector' => '{{WRAPPER}} .active .wgl-accordion_icon',
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
                'name' => 'acc_content_typo',
                'selector' => '{{WRAPPER}} .wgl-accordion_content',
            ]
        );

        $this->add_responsive_control(
            'acc_content_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '21',
                    'right' => '10',
                    'bottom' => '11',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'acc_content_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'acc_content_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_content_border',
                'selector' => '{{WRAPPER}} .wgl-accordion_content',
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $this->add_render_attribute(
            'accordion',
            [
                'class' => [
                    'wgl-accordion',
                    'icon-'.$_s['enable_acc_icon'],
                ],
                'id' => 'wgl-accordion-'.esc_attr($this->get_id()),
                'data-type' => $_s['acc_type'],
            ]
        );

        echo '<div ', $this->get_render_attribute_string('accordion'), '>';

        foreach ($_s['acc_tab'] as $index => $item) :

            $tab_count = $index + 1;

            $tab_title_key = $this->get_repeater_setting_key('acc_tab_title', 'acc_tab', $index);

            $this->add_render_attribute(
                $tab_title_key,
                [
                    'id' => 'wgl-accordion_header-' . $id_int . $tab_count,
                    'class' => 'wgl-accordion_header',
                    'data-default' => $item['acc_tab_def_active'],
                ]
            );

            // Render
            echo '<div class="wgl-accordion_panel">';
            echo '<', $_s['title_tag'], ' ', $this->get_render_attribute_string($tab_title_key), '>';

                echo '<span class="wgl-accordion_title">';
                    if (!empty($item['title_prefix'])) {
                        echo '<span class="wgl-accordion_title-prefix">',
                            $item['title_prefix'],
                        '</span>';
                    }
                    echo $item['acc_tab_title'];
                echo '</span>'; // wgl-accordion_title

                if ($_s['enable_acc_icon'] != 'none') {
                    echo '<i class="wgl-accordion_icon elementor-icon ', $_s['acc_icon'], '"></i>';
                }

            echo '</', $_s['title_tag'], '>';

            echo '<div class="wgl-accordion_content">';

                if ($item['acc_content_type'] == 'content') {
                    echo do_shortcode($item['acc_content']);
                } elseif ($item['acc_content_type'] == 'template') {
                    $id = $item['acc_content_templates'];
                    $wgl_frontend = new Frontend;
                    echo $wgl_frontend->get_builder_content_for_display($id, true);
                }

            echo '</div>'; // wgl-accordion_content

            echo '</div>'; // wgl-accordion_panel

        endforeach;

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