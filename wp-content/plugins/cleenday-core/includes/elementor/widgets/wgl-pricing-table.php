<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-pricing-table.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Cleenday_Theme_Helper;
use Elementor\{Widget_Base, Controls_Manager, Group_Control_Border, Group_Control_Typography, Group_Control_Background, Group_Control_Box_Shadow};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use WglAddons\templates\WGL_Button;

class Wgl_Pricing_Table extends Widget_Base
{
    public function get_name() {
        return 'wgl-pricing-table';
    }

    public function get_title() {
        return esc_html__('WGL Pricing Table', 'cleenday-core');
    }

    public function get_icon() {
        return 'wgl-pricing-table';
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
    }


    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            [ 'label' => esc_html__('General', 'cleenday-core') ]
        );

        $this->add_control(
            'p_alignment',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
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
                'default' => 'left',
                'prefix_class' => 'a',
            ]
        );

        $this->add_control(
            'p_title',
            [
                'label' => esc_html__('Title', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('Title...', 'cleenday-core'),
                'default' => esc_html__('BASIC', 'cleenday-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'p_currency',
            [
                'label' => esc_html__('Currency', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('Currency...', 'cleenday-core'),
                'default' => esc_html__('€', 'cleenday-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'p_price',
            [
                'label' => esc_html__('Price', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('Price...', 'cleenday-core'),
                'default' => esc_html__('120', 'cleenday-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'p_period',
            [
                'label' => esc_html__('Period', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('Period...', 'cleenday-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'p_content',
            [
                'label' => esc_html__('Content', 'cleenday-core'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Your content...', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'p_description',
            [
                'label' => esc_html__('Description', 'cleenday-core'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'placeholder' => esc_attr__('Description...', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__('Enable hover animation', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Lift up the item on hover.', 'cleenday-core'),
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> HIGHLIGHTER
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_highlighter',
            [ 'label' => esc_html__('Highlighter', 'cleenday-core') ]
        );

        $this->add_control(
            'highlighter_switch',
            [
                'label' => esc_html__('Use highlighting element?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'highlighter_text',
            [
                'label' => esc_html__('Highlighting Text', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [ 'highlighter_switch' => 'yes' ],
                'default' => esc_html__('Best', 'cleenday-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> BUTTON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_button',
            [ 'label' => esc_html__('Button', 'cleenday-core') ]
        );

        $this->add_control(
            'b_switch',
            [
                'label' => esc_html__('Use button?','cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'b_title',
            [
                'label' => esc_html__('Button Text', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [ 'b_switch' => 'yes' ],
                'label_block' => true,
                'default' => esc_html__('ORDER CLEANING', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'b_link',
            [
                'label' => esc_html__('Button Link', 'cleenday-core'),
                'type' => Controls_Manager::URL,
                'condition' => [ 'b_switch' => 'yes' ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> HIGHLIGHTER
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_highlighter',
            [
                'label' => esc_html__('Highlighter', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'highlighter_switch!' => '' ],
            ]
        );

        $this->add_control(
            'highlighter_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pricing_highlighter' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pricing_highlighter-icon' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'highlighter_bg_color',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .pricing_highlighter' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .pricing_highlighter-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'highlighter_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_highlighter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'highlighter_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '13',
                    'bottom' => '6',
                    'left' => '6',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_highlighter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'highlighter_b_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => '50',
                    'right' => '0',
                    'bottom'=> '0',
                    'left' => '50',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_highlighter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TITLE
        /*-----------------------------------------------------------------------------------*/

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
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .pricing_title',

            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .pricing_title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing_title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .pricing_title',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> PRICE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_price',
            [
                'label' => esc_html__('Price', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pricing_price_typo',
                'selector' => '{{WRAPPER}} .pricing_price_wrap',
            ]
        );

        $this->add_control(
            'custom_price_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .pricing_price_wrap' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => '7',
                    'right' => '0',
                    'bottom'=> '26',
                    'left'  => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_price_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> PERIOD
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_period',
            [
                'label' => esc_html__('Period', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pricing_period_typo',
                'selector' => '{{WRAPPER}} .pricing_period',
                'fields_options' => [
                    'font_size' => [
                        'default' => [ 'size' => 0.3, 'unit' => 'em' ]
                    ]
                ],
            ]
        );

        $this->add_control(
            'period_color',
            [
                'label' => esc_html__('Period Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .pricing_period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'period_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_period' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CONTENT
        /*-----------------------------------------------------------------------------------*/

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
                'name' => 'pricing_content_typo',
                'selector' => '{{WRAPPER}} .pricing_content',
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
                    '{{WRAPPER}} .pricing_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content-padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
	                'top' => '22',
	                'right' => '0',
	                'bottom' => '25',
	                'left' => '0',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_content' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}} !important; padding-right: {{RIGHT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .pricing_content',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [ 'default' => [
                        'top' => '1',
                        'right' => '0',
                        'bottom' => '0',
                        'left' => '0',
                    ] ],
                    'color' => [
                        'default' => '#f7f5f7'
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> DESCRIPTION
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__('Description', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'p_description!' => '' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pricing_desc_typo',
                'selector' => '{{WRAPPER}} .pricing_desc',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .pricing_desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> BUTTON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'b_switch!' => '' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .wgl-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_idle',
            [ 'label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'b_bg_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .wgl-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [ 'label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_control(
            'b_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover, {{WRAPPER}} .wgl-button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'b_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover, {{WRAPPER}} .wgl-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .wgl-button:hover',
            ]
        );

        $this->add_control(
            'b_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['border_border!' => ''],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover, {{WRAPPER}} .wgl-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .wgl-button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'b_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'b_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'b_switch' => 'yes' ],
                'separator' => 'before',
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'b_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'b_switch' => 'yes' ],
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
	                'top' => '17',
	                'right' => '33',
	                'bottom' => '14',
	                'left' => '33',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> BACKGROUND
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_bg',
            [
                'label' => esc_html__('Background', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_scheme',
            [
                'label' => esc_html__('Customize for:', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'module' => esc_html__('whole module', 'cleenday-core'),
                    'sections'  => esc_html__('separate sections', 'cleenday-core'),
                ],
                'default' => 'module',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'module',
                'types' => ['classic', 'gradient'],
                'condition' => ['bg_scheme' => 'module'],
                'selector' => '{{WRAPPER}} .pricing_plan_wrap',
                'fields_options' => [
					'background' => ['default' => 'classic'],
					'color' => ['default' => '#ffffff'],
				],
            ]
        );

        $this->add_control(
			'header_s_label',
			[
				'label' => esc_html__('Header Section Background', 'cleenday-core'),
				'type' => Controls_Manager::HEADING,
			]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'header_s',
                'label' => esc_html__('Background', 'cleenday-core'),
                'types' => [ 'classic', 'gradient' ],
                'condition' => [ 'bg_scheme' => 'sections' ],
                'selector' => '{{WRAPPER}} .pricing_header',
                'fields_options' => [
					'background' => [ 'default' => 'classic' ],
					'color' => [ 'default' => Cleenday_Globals::get_primary_color() ],
				],
            ]
        );

        $this->add_control(
            'content_s_bg',
            [
                'label' => esc_html__('Content Section Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['bg_scheme' => 'sections'],
                'separator' => 'before',
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing_content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'footer_s_bg',
            [
                'label' => esc_html__('Footer Section Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['bg_scheme' => 'sections'],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing_footer' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => '31',
                    'right' => '40',
                    'bottom' => '40',
                    'left' => '40',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_plan_wrap' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};padding-bottom: {{BOTTOM}}{{UNIT}};padding-top: {{TOP}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bg_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'left' => '12',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing_plan_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bg_border',
                'selector' => '{{WRAPPER}} .pricing_plan_wrap',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

	    $price = $title = $description = $highlighter = $button = '';

        // Wrapper classes
        $wrap_classes = $_s['hover_animation'] ? ' hover-animation' : '';

        // Title
        if (!empty($_s['p_title'])) {
            $title .= '<div class="pricing_title_wrapper">';
            $title .= '<h4 class="pricing_title">';
                $title .= esc_html($_s['p_title']);
            $title .= '</h4>';
            $title .= '</div>';
        }

        // Currency
        $currency = ! empty($_s['p_currency']) ? '<span class="pricing_currency">' . esc_html($_s['p_currency']) . '</span>' : '';

        // Price
        if ( isset($_s['p_price']) ) {
            $price = '<div class="pricing_price">'.$_s['p_price'].'</div>';
        }

        // Period
        $period = ! empty($_s['p_period']) ? '<span class="pricing_period">' . esc_html($_s['p_period']) . '</span>' : '';

        // Description
        if ( $_s['p_description'] ) {
            $allowed_html = [
                'a' => [
                    'id' => true, 'class' => true, 'style' => true,
                    'href' => true, 'title' => true,
                    'rel' => true, 'target' => true,
                ],
                'br' => ['id' => true, 'class' => true, 'style' => true],
                'em' => ['id' => true, 'class' => true, 'style' => true],
                'strong' => ['id' => true, 'class' => true, 'style' => true],
                'span' => ['id' => true, 'class' => true, 'style' => true],
                'p' => ['id' => true, 'class' => true, 'style' => true],
                'ul' => ['id' => true, 'class' => true, 'style' => true],
                'ol' => ['id' => true, 'class' => true, 'style' => true],
                'li' => ['id' => true, 'class' => true, 'style' => true],
            ];
            $description = '<div class="pricing_desc">'
                . wp_kses($_s['p_description'], $allowed_html)
                . '</div>';
        }

        // Highlighter
        if ($_s['highlighter_switch'] && !empty($_s['highlighter_text'])) {
            $highlighter = '<div class="pricing_highlighter">'
                . '<span class="pricing_highlighter-icon"></span>'
                . esc_html($_s['highlighter_text'])
                . '</div>';
        }

        // Button
        if ( $_s['b_switch'] ) {
            $button_options = [
                'icon_type' => '',
                'text' => $_s['b_title'],
                'link' => $_s['b_link'],
                'size' => 'lg',
            ];
            $button = new WGL_Button();
            ob_start();
                $button->render($this, $button_options);
            $button = ob_get_clean();
        }

        // Render ?>
        <div class="wgl-pricing_plan<?php echo $wrap_classes; ?>">
            <div class="pricing_plan_wrap"><?php
                echo $highlighter; ?>
                <div class="pricing_header"><?php
                    echo $title; ?>
                    <div class="pricing_price_wrap"><?php
                        echo $currency,
                        $price,
                        $period; ?>
                    </div>
                </div>
                <div class="pricing_content"><?php
                    echo $_s['p_content']; ?>
                </div>
                <div class="pricing_footer"><?php
                    echo $description,
                    $button; ?>
                </div>
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
