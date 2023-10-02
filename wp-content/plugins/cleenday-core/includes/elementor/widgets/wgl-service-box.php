<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-service-box.php.
*/
namespace WglAddons\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{Widget_Base,
	Controls_Manager,
	Icons_Manager,
	Group_Control_Border,
	Group_Control_Css_Filter,
	Group_Control_Typography,
	Group_Control_Background,
	Group_Control_Box_Shadow,
	Utils};
use Cleenday_Theme_Helper;
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use WglAddons\Includes\Wgl_Icons;

class Wgl_Service_Box extends Widget_Base {
	public function get_name() {
		return 'wgl-service-box';
	}

	public function get_title() {
		return esc_html__( 'WGL Service Box', 'cleenday-core' );
	}

	public function get_icon() {
		return 'wgl-service-box';
	}

	public function get_categories() {
		return [ 'wgl-extensions' ];
	}

	protected function register_controls() {
		/**
		 * CONTENT -> GENERAL
		 */

		$this->start_controls_section(
			'section_content_general',
			[ 'label' => esc_html__( 'General', 'cleenday-core' ) ]
		);

		$this->add_control(
			's_title',
			[
				'label' => esc_html__( 'Title', 'cleenday-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'This is the heading', 'cleenday-core' ),
			]
		);

		$this->add_control(
			's_subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'cleenday-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			's_description',
			[
				'label' => esc_html__( 'Description', 'cleenday-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Nisi vitae suscipit tellus mauris a diam maecenas enim blandit volutpat sed', 'cleenday-core' ),
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'cleenday-core' ),
				'type' => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'cleenday-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cleenday-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'cleenday-core' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Full Width', 'cleenday-core' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => 'center',
				'prefix_class' => 'a%s',
			]
		);

		$this->add_control(
			'hover_toggling',
			[
				'label' => esc_html__( 'Toggle Subtitle/Content Visibility', 'cleenday-core' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [ 'hover_scaling' => '' ],
				'label_on' => esc_html__( 'On', 'cleenday-core' ),
				'label_off' => esc_html__( 'Off', 'cleenday-core' ),
				'return_value' => 'toggling',
				'prefix_class' => 'animation_',
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'hover_toggling_offset',
			[
				'label' => esc_html__( 'Animation Distance in %', 'cleenday-core' ),
				'type' => Controls_Manager::SLIDER,
				'condition' => [ 'hover_toggling!' => '' ],
				'range' => [
					'%' => [ 'min' => 30, 'max' => 100 ],
				],
				'default' => [ 'size' => 86, 'unit' => '%' ],
				'selectors' => [
					'{{WRAPPER}}.animation_toggling .wgl-service-box_content' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'hover_scaling',
			[
				'label' => esc_html__( 'Toggle Item Scaling', 'cleenday-core' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [ 'hover_toggling' => '' ],
				'label_on' => esc_html__( 'On', 'cleenday-core' ),
				'label_off' => esc_html__( 'Off', 'cleenday-core' ),
				'return_value' => 'scaling',
				'prefix_class' => 'animation_',
			]
		);

		$this->add_responsive_control(
			'hover_scaling_offset',
			[
				'label' => esc_html__( 'Animation Distance', 'cleenday-core' ),
				'type' => Controls_Manager::SLIDER,
				'condition' => [ 'hover_scaling!' => '' ],
				'range' => [
					'px' => [ 'min' => 0.5, 'max' => 1, 'step' => 0.01 ],
				],
				'default' => [ 'size' => 0.92 ],
				'selectors' => [
					'{{WRAPPER}}.animation_scaling .elementor-widget-container:hover .wgl-service-box_background' => 'transform: scale({{SIZE}});',
					'{{WRAPPER}}.animation_scaling .wgl-service-box' => 'margin-right: calc(100% * (1 - {{SIZE}}));',
					'{{WRAPPER}}.animation_scaling .elementor-widget-container:hover .wgl-service-box' => 'transform: translateX(calc(49% * (1 - {{SIZE}}))) translateY(calc(-49% * (1 - {{SIZE}})));',
				],
			]
		);

		$this->add_responsive_control(
			'hover_toggling_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'cleenday-core' ),
				'type' => Controls_Manager::SLIDER,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'hover_toggling',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'hover_scaling',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
				'range' => [
					'px' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.1 ],
				],
				'default' => [ 'size' => 0.6 ],
				'selectors' => [
					'{{WRAPPER}}.animation_toggling .wgl-service-box_content,
                     {{WRAPPER}}.animation_toggling .wgl-service-box_subtitle,
                     {{WRAPPER}} .wgl-service-box' => 'transition-duration: {{SIZE}}s;',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * CONTENT -> ICON/IMAGE
		 */
		$output = [];

		$output['view'] = [
			'label' => esc_html__('View', 'cleenday-core'),
			'type' => Controls_Manager::SELECT,
			'condition' => ['icon_type' => 'font'],
			'options' => [
				'default' => esc_html__('Default', 'cleenday-core'),
				'stacked' => esc_html__('Stacked', 'cleenday-core'),
				'framed' => esc_html__('Framed', 'cleenday-core'),
			],
			'default' => 'default',
			'prefix_class' => 'elementor-view-',
		];

		$output['shape'] = [
			'label' => esc_html__('Shape', 'cleenday-core'),
			'type' => Controls_Manager::SELECT,
			'condition' => [
				'icon_type' => 'font',
				'view!' => 'default',
			],
			'options' => [
				'circle' => esc_html__('Circle', 'cleenday-core'),
				'square' => esc_html__('Square', 'cleenday-core'),
			],
			'default' => 'circle',
			'prefix_class' => 'elementor-shape-',
		];

		Wgl_Icons::init( $this, [
			'output' => $output,
			'section' => true
		] );

		/**
		 * CONTENT -> BUTTON
		 */

		$this->start_controls_section(
			'section_content_button',
			[ 'label' => esc_html__( 'Link', 'cleenday-core' ) ]
		);

		$this->add_control(
			'item_link',
			[
				'label' => esc_html__( 'Link', 'cleenday-core' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [ 'active' => true ],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'add_item_link',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'add_read_more',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'add_item_link',
			[
				'label' => esc_html__( 'Whole Item Link', 'cleenday-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'cleenday-core' ),
				'label_off' => esc_html__( 'Off', 'cleenday-core' ),
			]
		);

		$this->add_control(
			'add_read_more',
			[
				'label' => esc_html__( '\'Read More\' Button', 'cleenday-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'cleenday-core' ),
				'label_off' => esc_html__( 'Off', 'cleenday-core' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => esc_html__( 'Button Text', 'cleenday-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
				'default' => esc_html__( 'VIEW ALL', 'cleenday-core' ),
				'condition' => [ 'add_read_more' => 'yes' ],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'cleenday-core' ),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'add_read_more' => 'yes',
					'read_more_text!' => '',
				],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [ 'size' => 13, 'unit' => 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_button i' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'read_more_icon_fontawesome',
			[
				'label' => esc_html__( 'Icon', 'cleenday-core' ),
				'type' => Controls_Manager::ICONS,
				'condition' => [ 'add_read_more' => 'yes' ],
				'label_block' => true,
				'description' => esc_html__( 'Select icon from available libraries.', 'cleenday-core' ),
			]
		);

		$this->add_responsive_control(
			'read_more_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'cleenday-core' ),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'add_read_more' => 'yes',
				],
				'range' => [
					'px' => [ 'min' => 10, 'max' => 100 ],
				],
				'default' => [ 'size' => 14 ],
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_button i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * STYLE -> ICON
		 */

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__('Icon', 'cleenday-core'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['icon_type' => 'font'],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Font Size', 'cleenday-core'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem'],
				'range' => [
					'px' => ['min' => 6, 'max' => 300],
				],
				'default' => ['size' => 21],
				'selectors' => [
					'{{WRAPPER}} .media-wrapper .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_rotate',
			[
				'label' => esc_html__('Rotate', 'cleenday-core'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg', 'turn'],
				'range' => [
					'deg' => ['max' => 360],
					'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
				],
				'default' => ['unit' => 'deg'],
				'selectors' => [
					'{{WRAPPER}} .media-wrapper .elementor-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => esc_html__('Margin', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .media-wrapper .elementor-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => esc_html__('Padding', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .media-wrapper .elementor-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => esc_html__('Border Width', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'condition' => ['view' => 'framed'],
				'selectors' => [
					'{{WRAPPER}} .media-wrapper .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__('Border Radius', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'condition' => ['view!' => 'default'],
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .media-wrapper .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'tabs_icons',
			['separator' => 'before']
		);

		$this->start_controls_tab(
			'tab_icon_idle',
			['label' => esc_html__('Idle', 'cleenday-core')]
		);

		$this->add_control(
			'icon_primary_color_idle',
			[
				'label' => esc_html__('Color', 'cleenday-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon,
                     {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon svg,
                     {{WRAPPER}}.elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color_idle',
			[
				'label' => esc_html__('Additional Color', 'cleenday-core'),
				'type' => Controls_Manager::COLOR,
				'condition' => ['view!' => 'default'],
				'dynamic' => ['active' => true],
				'default' => Cleenday_Globals::get_secondary_color(),
				'selectors' => [
					'{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_idle',
				'selector' => '{{WRAPPER}} .elementor-icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			['label' => esc_html__('Hover', 'cleenday-core')]
		);

		$this->add_control(
			'icon_primary_color_hover',
			[
				'label' => esc_html__('Color', 'cleenday-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed:hover .elementor-icon,
                     {{WRAPPER}}.elementor-view-default:hover .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed:hover .elementor-icon svg,
                     {{WRAPPER}}.elementor-view-default:hover .elementor-icon svg' => 'fill: {{VALUE}}; ',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color_hover',
			[
				'label' => esc_html__('Additional Color', 'cleenday-core'),
				'type' => Controls_Manager::COLOR,
				'condition' => ['view!' => 'default'],
				'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}}.elementor-view-framed:hover .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_hover',
				'selector' => '{{WRAPPER}}:hover .elementor-icon',
			]
		);

		$this->add_control(
			'hover_animation_icon',
			[
				'label' => esc_html__('Hover Animation', 'cleenday-core'),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
		 * STYLE -> IMAGE
		 */

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__('Image', 'cleenday-core'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['icon_type' => 'image'],
			]
		);

		$this->add_responsive_control(
			'image_space',
			[
				'label' => esc_html__('Margin', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .wgl-image-box_img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__('Width', 'cleenday-core'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => ['min' => 50, 'max' => 800],
					'%' => ['min' => 5, 'max' => 100],
				],
				'default' => ['size' => 100, 'unit' => '%'],
				'selectors' => [
					'{{WRAPPER}} .wgl-image-box_img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_animation_image',
			[
				'label' => esc_html__('Hover Animation', 'cleenday-core'),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->start_controls_tabs('image_effects');

		$this->start_controls_tab(
			'Idle',
			['label' => esc_html__('Idle', 'cleenday-core')]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .wgl-image-box_img img',
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => esc_html__('Opacity', 'cleenday-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => ['min' => 0.10, 'max' => 1, 'step' => 0.01],
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-image-box_img img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => esc_html__('Transition Duration', 'cleenday-core'),
				'type' => Controls_Manager::SLIDER,
				'default' => ['size' => 0.3],
				'range' => [
					'px' => ['max' => 3, 'step' => 0.1],
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-image-box_img img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			['label' => esc_html__('Hover', 'cleenday-core')]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .elementor-widget-container:hover .wgl-image-box_img img',
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label' => esc_html__('Opacity', 'cleenday-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => ['min' => 0.10, 'max' => 1, 'step' => 0.01],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover .wgl-image-box_img img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
		 * STYLE -> TITLE
		 */

		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'Title', 'cleenday-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'custom_fonts_title',
				'selector' => '{{WRAPPER}} .wgl-service-box_title',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'cleenday-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'h1' => '‹h1›',
					'h2' => '‹h2›',
					'h3' => '‹h3›',
					'h4' => '‹h4›',
					'h5' => '‹h5›',
					'h6' => '‹h6›',
					'div' => '‹div›',
					'span' => '‹span›',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'cleenday-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '10',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'cleenday-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'title' );

		$this->start_controls_tab(
			'custom_service_color_idle',
			[ 'label' => esc_html__( 'Idle', 'cleenday-core' ) ]
		);

		$this->add_control(
			'service_color',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_title' => 'color: {{VALUE}};'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'custom_service_color_hover',
			[ 'label' => esc_html__( 'Hover', 'cleenday-core' ) ]
		);

		$this->add_control(
			'service_color_hover',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}:hover .wgl-service-box_title' => 'color: {{VALUE}};'
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
		 * STYLE -> SUBTITLE
		 */

		$this->start_controls_section(
			'section_style_subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'cleenday-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_custom_fonts',
				'selector' => '{{WRAPPER}} .wgl-service-box_subtitle',
			]
		);

		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label' => esc_html__( 'Subtitle Margin', 'cleenday-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'subtitle' );

		$this->start_controls_tab(
			'custom_service_color_normal_subtitle',
			[ 'label' => esc_html__( 'Idle', 'cleenday-core' ) ]
		);

		$this->add_control(
			'service_color_subtitle',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_subtitle' => 'color: {{VALUE}};'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'custom_service_color_hover_subtitle',
			[ 'label' => esc_html__( 'Hover', 'cleenday-core' ) ]
		);

		$this->add_control(
			'service_color_hover_subtitle',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}:hover .wgl-service-box_subtitle' => 'color: {{VALUE}};'
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
		 * STYLE -> DESCRIPTION
		 */

		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'cleenday-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'descr_custom_fonts',
				'selector' => '{{WRAPPER}} .wgl-service-box_description',
			]
		);

		$this->add_responsive_control(
			'descr_margin',
			[
				'label' => esc_html__( 'Description Margin', 'cleenday-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '46',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'description' );

		$this->start_controls_tab(
			'custom_service_color_normal_descr',
			[ 'label' => esc_html__( 'Idle', 'cleenday-core' ) ]
		);

		$this->add_control(
			'service_color_descr',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_description' => 'color: {{VALUE}};'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'custom_service_color_hover_descr',
			[ 'label' => esc_html__( 'Hover', 'cleenday-core' ) ]
		);

		$this->add_control(
			'service_color_hover_descr',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}:hover .wgl-service-box_description' => 'color: {{VALUE}};'
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
		 * STYLE -> BUTTON
		 */

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'cleenday-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [ 'add_read_more!' => '' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'custom_fonts_button',
				'selector' => '{{WRAPPER}} .wgl-service-box_button span',
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'cleenday-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'cleenday-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '11',
					'right' => '42',
					'bottom' => '9',
					'left' => '42',
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'cleenday-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '6',
					'right' => '6',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'button_color_tab',
			[ 'separator' => 'before' ]
		);

		$this->start_controls_tab(
			'tab_button_idle',
			[ 'label' => esc_html__( 'Idle', 'cleenday-core' ) ]
		);

		$this->add_control(
			'button_color_idle',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_button i, {{WRAPPER}} .wgl-service-box_button span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_idle',
			[
				'label' => esc_html__( 'Background Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4b494d',
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_idle',
				'label' => esc_html__( 'Border Type', 'cleenday-core' ),
				'selector' => '{{WRAPPER}} .wgl-service-box_button',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_shadow',
				'selector' => '{{WRAPPER}} .wgl-service-box_button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_item', [
				'label' => esc_html__( 'Hover Item', 'cleenday-core' ),
				'condition' => [ 'add_item_link!' => '' ],
			]
		);

		$this->add_control(
			'button_color_hover_item',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_link:hover ~ .wgl-service-box .wgl-service-box_button i,
					{{WRAPPER}} .wgl-service-box_link:hover ~ .wgl-service-box .wgl-service-box_button span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_hover_item',
			[
				'label' => esc_html__( 'Background Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => Cleenday_Globals::get_primary_color(),
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box_link:hover ~ .wgl-service-box .wgl-service-box_button' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_hover_item',
				'label' => esc_html__( 'Border Type', 'cleenday-core' ),
				'selector' => '{{WRAPPER}} .wgl-service-box:hover .wgl-service-box_button',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_shadow_hover_item',
				'selector' => '{{WRAPPER}} .wgl-service-box:hover .wgl-service-box_button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover', [
				'label' => esc_html__( 'Hover Button', 'cleenday-core' ) ,
				'condition' => [ 'add_item_link!' => 'yes' ],
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label' => esc_html__( 'Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box .wgl-service-box_button:hover i,
                     {{WRAPPER}} .wgl-service-box .wgl-service-box_button:hover span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_hover',
			[
				'label' => esc_html__( 'Background Color', 'cleenday-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => Cleenday_Globals::get_primary_color(),
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box .wgl-service-box_button:hover' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_hover',
				'label' => esc_html__( 'Border Type', 'cleenday-core' ),
				'selector' => '{{WRAPPER}} .wgl-service-box .wgl-service-box_button:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_shadow_hover',
				'selector' => '{{WRAPPER}} .wgl-service-box .wgl-service-box_button:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
		 * STYLE -> BACKGROUND
		 */

		$this->start_controls_section(
			'section_style_background',
			[
				'label' => esc_html__( 'Background', 'cleenday-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => esc_html__( 'Idle', 'cleenday-core' ),
				'name' => 'section_background',
				'types' => [ 'classic' ],
				'fields_options' => [
					'color' => [
						'label' => esc_html__( 'Background Color', 'cleenday-core' ),
                    ],
					'background' => [
						'type' => Controls_Manager::HIDDEN,
						'default' => 'classic'
					],
					'image' => [ 'default' =>[
						'url' => Utils::get_placeholder_image_src() ]
					],
					'position' => [ 'default' => 'center center' ],
					'repeat' => [ 'default' => 'no-repeat' ],
					'size' => [ 'default' => 'cover' ],
				],
				'selector' => '{{WRAPPER}} .wgl-service-box_background',
			]
		);

		$this->start_controls_tabs( 'background', ['separator' => 'before'] );

		$this->start_controls_tab(
			'tab_bg_idle',
			[ 'label' => esc_html__( 'Gradient Idle', 'cleenday-core' ) ]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_idle',
				'types' => [ 'gradient' ],
				'fields_options' => [
					'background' => [ 'type' => Controls_Manager::HIDDEN, 'default' => 'gradient' ],
					'color' => [ 'default' => 'rgba(0,0,0,0)' ],
					'color_stop' => [ 'default' => [ 'unit' => '%', 'size' => 0, ], ],
					'color_b' => [ 'default' => 'rgba(0,0,0,.65)' ],
					'color_b_stop' => [ 'default' => [ 'unit' => '%', 'size' => 100, ], ],
				],
				'selector' => '{{WRAPPER}} .wgl-service-box_background:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_bg_hover',
			[ 'label' => esc_html__( 'Gradient Hover', 'cleenday-core' ) ]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_hover',
				'types' => [ 'gradient' ],
				'fields_options' => [
					'background' => [ 'type' => Controls_Manager::HIDDEN, 'default' => 'gradient' ],
					'color' => [ 'default' => 'rgba(0,0,0,.5)' ],
					'color_stop' => [ 'default' => [ 'unit' => '%', 'size' => 0, ], ],
					'color_b' => [ 'default' => 'rgba(0,0,0,.95)' ],
					'color_b_stop' => [ 'default' => [ 'unit' => '%', 'size' => 100, ], ],
				],
				'selector' => '{{WRAPPER}} .wgl-service-box_background:after',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
		 * STYLE -> ITEM
		 */

		$this->start_controls_section(
			'section_style_ITEM',
			[
				'label' => esc_html__( 'Item', 'cleenday-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'cleenday-core' ),
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
					'{{WRAPPER}} .elementor-widget-container,
					 {{WRAPPER}} .wgl-service-box_background' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'cleenday-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '96',
					'right' => '20',
					'bottom' => '0',
					'left' => '20',
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-service-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}


	public function render() {
		$_s = $this->get_settings_for_display();
		$ib_media = $s_button = '';

		$kses_allowed_html = [
			'a' => [
				'id' => true,
				'class' => true,
				'style' => true,
				'href' => true,
				'title' => true,
				'rel' => true,
				'target' => true,
			],
			'br' => [ 'id' => true, 'class' => true, 'style' => true ],
			'em' => [ 'id' => true, 'class' => true, 'style' => true ],
			'strong' => [ 'id' => true, 'class' => true, 'style' => true ],
			'span' => [ 'id' => true, 'class' => true, 'style' => true ],
			'p' => [ 'id' => true, 'class' => true, 'style' => true ],
		];

		$this->add_render_attribute( 'service-box', 'class', [ 'wgl-service-box' ] );

		// Media
		if ( ! empty( $_s['icon_type'] ) ) {
			$media = new Wgl_Icons;
			$ib_media .= $media->build( $this, $_s, [] );
		}

		// Link
		if ( ! empty( $_s['item_link']['url'] ) ) {
			$this->add_link_attributes( 'link', $_s['item_link'] );
		}

		// Read more button
		if ( $_s['add_read_more'] ) {
			$this->add_render_attribute( 'btn', 'class', 'wgl-service-box_button' );

			$icon_font = $_s['read_more_icon_fontawesome'];

			$migrated = isset( $_s['__fa4_migrated']['read_more_icon_fontawesome'] );
			$is_new = Icons_Manager::is_migration_allowed();
			$icon_output = '';

			if ( $is_new || $migrated ) {
				ob_start();
				Icons_Manager::render_icon( $_s['read_more_icon_fontawesome'], [ 'aria-hidden' => 'true' ] );
				$icon_output .= ob_get_clean();
			} else {
				$icon_output .= '<i class="icon ' . esc_attr( $icon_font ) . '"></i>';
			}

			$s_button = '<div class="wgl-service-box_button-wrapper">';
			$s_button .= sprintf( '<%s %s %s>',
				$_s['add_item_link'] ? 'div' : 'a',
				$_s['add_item_link'] ? '' : $this->get_render_attribute_string( 'link' ),
				$this->get_render_attribute_string( 'btn' )
			);
			$s_button .= $_s['read_more_text'] ? '<span>' . esc_html( $_s['read_more_text'] ) . '</span>' : '';
			$s_button .= $icon_output;
			$s_button .= $_s['add_item_link'] ? '</div>' : '</a>';
			$s_button .= '</div>';
		}

		// Render
		if ( $_s['add_item_link'] && ! empty( $_s['item_link']['url'] ) ) { ?>
			<a class="wgl-service-box_link" <?php echo $this->get_render_attribute_string( 'link' ); ?>></a><?php
		}?>
        <div class="wgl-service-box_background"></div>
		<div <?php echo $this->get_render_attribute_string( 'service-box' ); ?>>
			<div class="wgl-service-box_content-wrap">
				<div class="wgl-service-box_content"><?php
					echo $ib_media;
					if ( ! empty( $_s['s_subtitle'] ) ) { ?>
						<div class="wgl-service-box_subtitle"><?php echo wp_kses( $_s['s_subtitle'], $kses_allowed_html ); ?></div><?php
					}
					if ( ! empty( $_s['s_title'] ) ) {
						echo '<', $_s['title_tag'], ' class="wgl-service-box_title">', wp_kses( $_s['s_title'], $kses_allowed_html ), '</', $_s['title_tag'], '>';
					}
					if ( ! empty( $_s['s_description'] ) ) { ?>
						<div class="wgl-service-box_description"><?php echo wp_kses( $_s['s_description'], $kses_allowed_html ); ?></div><?php
					}?>
				</div>
			</div><?php
			echo $s_button; ?>
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