<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-countdown.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use WglAddons\Templates\WglCountDown;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;


class Wgl_CountDown extends Widget_Base
{

	public function get_name()
	{
		return 'wgl-countdown';
	}

	public function get_title()
	{
		return esc_html__('WGL Countdown Timer', 'cleenday-core');
	}

	public function get_icon()
	{
		return 'wgl-countdown';
	}

	public function get_categories()
	{
		return ['wgl-extensions'];
	}

	public function get_script_depends()
	{
		return [
			'jquery-countdown',
			'wgl-elementor-extensions-widgets',
		];
	}

	protected function register_controls() {
		/* Start General Settings Section */
		$this->start_controls_section( 'wgl_countdown_section',
			array(
				'label' => esc_html__( 'Countdown Timer Settings', 'cleenday-core' ),
			)
		);

		$this->add_control( 'countdown_year',
			array(
				'label'       => esc_html__( 'Year', 'cleenday-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'cleenday-core' ),
				'default'     => esc_html__( '2020', 'cleenday-core' ),
				'label_block' => true,
				'description' => esc_html__( 'Example: 2020', 'cleenday-core' ),
			)
		);

		$this->add_control( 'countdown_month',
			array(
				'label'       => esc_html__( 'Month', 'cleenday-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '12', 'cleenday-core' ),
				'default'     => esc_html__( '12', 'cleenday-core' ),
				'label_block' => true,
				'description' => esc_html__( 'Example: 12', 'cleenday-core' ),
			)
		);

		$this->add_control( 'countdown_day',
			array(
				'label'       => esc_html__( 'Day', 'cleenday-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '31', 'cleenday-core' ),
				'default'     => esc_html__( '31', 'cleenday-core' ),
				'label_block' => true,
				'description' => esc_html__( 'Example: 31', 'cleenday-core' ),
			)
		);

		$this->add_control( 'countdown_hours',
			array(
				'label'       => esc_html__( 'Hours', 'cleenday-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '24', 'cleenday-core' ),
				'default'     => esc_html__( '24', 'cleenday-core' ),
				'label_block' => true,
				'description' => esc_html__( 'Example: 24', 'cleenday-core' ),
			)
		);

		$this->add_control( 'countdown_min',
			array(
				'label'       => esc_html__( 'Minutes', 'cleenday-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '59', 'cleenday-core' ),
				'default'     => esc_html__( '59', 'cleenday-core' ),
				'label_block' => true,
				'description' => esc_html__( 'Example: 59', 'cleenday-core' ),
			)
		);

		/*End General Settings Section*/
		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  Button Section
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section( 'wgl_countdown_content_section',
			array(
				'label' => esc_html__( 'Countdown Timer Content', 'cleenday-core' ),
			)
		);

		$this->add_control( 'hide_day',
			array(
				'label'        => esc_html__( 'Hide Days?', 'cleenday-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'cleenday-core' ),
				'label_off'    => esc_html__( 'Off', 'cleenday-core' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control( 'hide_hours',
			array(
				'label'        => esc_html__( 'Hide Hours?', 'cleenday-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'cleenday-core' ),
				'label_off'    => esc_html__( 'Off', 'cleenday-core' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control( 'hide_minutes',
			array(
				'label'        => esc_html__( 'Hide Minutes?', 'cleenday-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'cleenday-core' ),
				'label_off'    => esc_html__( 'Off', 'cleenday-core' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control( 'hide_seconds',
			array(
				'label'        => esc_html__( 'Hide Seconds?', 'cleenday-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'cleenday-core' ),
				'label_off'    => esc_html__( 'Off', 'cleenday-core' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control( 'show_value_names',
			array(
				'label'        => esc_html__( 'Show Value Names?', 'cleenday-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'cleenday-core' ),
				'label_off'    => esc_html__( 'Off', 'cleenday-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'show_value_names-',
			)
		);

		$this->add_control( 'show_separating',
			array(
				'label'        => esc_html__( 'Show Separating?', 'cleenday-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'cleenday-core' ),
				'label_off'    => esc_html__( 'Off', 'cleenday-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'show_separating-',
			)
		);
		$this->add_responsive_control(
			'align',
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
				'prefix_class' => 'elementor%s-align-',
				'default' => 'center',
			]
		);

		/*End General Settings Section*/
		$this->end_controls_section();

		$this->start_controls_section(
			'countdown_style_section',
			array(
				'label' => esc_html__( 'Style', 'cleenday-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_responsive_control(
			'number_text_padding',
			[
				'label' => esc_html__('Padding', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .countdown-amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control( 'size',
			array(
				'label'   => esc_html__( 'Countdown Size', 'cleenday-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'large'  => esc_html__( 'Large', 'cleenday-core' ),
					'medium' => esc_html__( 'Medium', 'cleenday-core' ),
					'small'  => esc_html__( 'Small', 'cleenday-core' ),
					'custom' => esc_html__( 'Custom', 'cleenday-core' ),
				],
				'default' => 'large'
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'     => esc_html__( 'Number Typography', 'cleenday-core' ),
				'name'      => 'custom_fonts_number',
				'selector'  => '{{WRAPPER}} .wgl-countdown .countdown-section',
				'condition' => [
					'size' => 'custom'
				]
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'     => esc_html__( 'Text Typography', 'cleenday-core' ),
				'name'      => 'custom_fonts_text',
				'selector'  => '{{WRAPPER}} .wgl-countdown .countdown-section .countdown-period',
				'condition' => [
					'size' => 'custom'
				]
			)
		);

		$this->add_control(
			'number_text_color',
			array(
				'label'     => esc_html__( 'Number Color', 'cleenday-core' ),
				'type'      => Controls_Manager::COLOR,
				'default' => Cleenday_Globals::get_h_font_color(),
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-section .countdown-amount' => 'color: {{VALUE}};',
				],
			)
		);

		$this->add_control(
			'period_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'cleenday-core' ),
				'type'      => Controls_Manager::COLOR,
				'default' => Cleenday_Globals::get_main_font_color(),
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-section .countdown-period' => 'color: {{VALUE}};',
				],
			)
		);

		$this->add_control(
			'separating_color',
			array(
				'label'     => esc_html__( 'Separate Color', 'cleenday-core' ),
				'type'      => Controls_Manager::COLOR,
				'default' => Cleenday_Globals::get_primary_color(),
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount:after,
					{{WRAPPER}} .wgl-countdown .countdown-amount:before'  => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_separating' => 'yes'
				]
			)
		);

		$this->add_responsive_control(
			'separating_size',
			[
				'label' => esc_html__('Separate Size', 'cleenday-core'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => ['max' => 20],
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount:after,
					{{WRAPPER}} .wgl-countdown .countdown-amount:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'separating_position',
			[
				'label' => esc_html__('Separate Position', 'cleenday-core'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => ['max' => 20],
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount:after' => 'transform: translateX(50%) translateY(calc(50% - 8px + {{SIZE}}{{UNIT}}));',
					'{{WRAPPER}} .wgl-countdown .countdown-amount:before' => 'transform: translateX(50%) translateY(calc(-50% - {{SIZE}}{{UNIT}}));',
				],
			]
		);

		/*End Style Section*/
		$this->end_controls_section();
	}

	protected function render()
	{
		$atts = $this->get_settings_for_display();

		$countdown = new WglCountDown();
		$countdown->render($this, $atts);
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
