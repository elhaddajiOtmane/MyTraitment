<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-working-hours.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager, Scheme_Typography, Group_Control_Typography, Repeater};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;

class Wgl_Working_Hours extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-working-hours';
    }

    public function get_title()
    {
        return esc_html__('WGL Working Hours', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-working-hours';
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
        /*  Content
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_working_section',
            ['label' => esc_html__('Content', 'cleenday-core') ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'working_day',
            [
                'label' => esc_html__('Day', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Monday', 'cleenday-core'),
            ]
        );

        $repeater->add_control(
            'working_hours',
            [
                'label' => esc_html__('Hours', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('8.00 - 21.00', 'cleenday-core'),
            ]
        );

        $repeater->add_control(
            'custom_colors',
            [
                'label' => esc_html__('Custom Colors', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'cleenday-core'),
                'label_off' => esc_html__('Off', 'cleenday-core'),
            ]
        );

        $repeater->add_control(
            'day_color',
            [
                'label' => esc_html__('Day Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_main_font_color(),
                'condition' => ['custom_colors' => 'yes'],
            ]
        );

        $repeater->add_control(
            'hours_color',
            [
                'label' => esc_html__('Hours Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_secondary_color(),
                'condition' => ['custom_colors' => 'yes'],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Days', 'cleenday-core'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    ['working_day' => esc_html__('Monday', 'cleenday-core')],
                    ['working_day' => esc_html__('Tuesday', 'cleenday-core')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{working_day}}',
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Styles', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_styles',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Item Styles', 'cleenday-core'),
            ]
        );

        $this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__('Margin', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
				'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'left' => '0',
                    'right' => '0',
                    'bottom' => '6',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .working-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
        );

        $this->add_control(
            'day_styles',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Day Styles', 'cleenday-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'day_typo',
                'selector' => '{{WRAPPER}} .working-item_day',
            ]
        );

        $this->add_control(
            'day_color',
            [
                'label' => esc_html__('Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .working-item_day' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'day_margin',
			[
				'label' => esc_html__('Margin', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .working-item_day' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'hours_styles',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Hours Styles', 'cleenday-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'hours_typo',
                'selector' => '{{WRAPPER}} .working-item_hours',
            ]
        );

        $this->add_control(
            'hours_color',
            [
                'label' => esc_html__('Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .working-item_hours' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'hours_margin',
			[
				'label' => esc_html__('Margin', 'cleenday-core'),
				'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .working-item_hours' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
            'line_color',
            [
                'label' => esc_html__('Line Between Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e5e5e5',
                'selectors' => [
                    '{{WRAPPER}} .working-item:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('working-hours', 'class', 'wgl-working-hours');

        ?><div <?php echo $this->get_render_attribute_string('working-hours'); ?>><?php

        foreach ($settings['items'] as $index => $item) {

            $working_day = $this->get_repeater_setting_key('working_day', 'items' , $index);
            $this->add_render_attribute( $working_day, [
                'class' => [
                    'working-item_day',
                ],
                'style' => [
                    ((bool)$item['custom_colors'] ? 'color: ' . esc_attr($item['day_color']) . ';' : ''),
                ]
            ] );

            $working_hours = $this->get_repeater_setting_key('working_hours', 'items' , $index);
            $this->add_render_attribute($working_hours, [
                'class' => [
                    'working-item_hours',
                ],
                'style' => [
                    ((bool)$item['custom_colors'] ? 'color: '.esc_attr($item['hours_color']).';' : ''),
                ]
            ] );

            ?>
            <div class="working-item"><?php
                if (!empty($item['working_day'])) {
                    ?><div <?php echo $this->get_render_attribute_string($working_day); ?>><?php echo esc_html($item['working_day']); ?></div><?php
                }
                if (!empty($item['working_hours'])) {
                    ?><div <?php echo $this->get_render_attribute_string($working_hours); ?>><?php echo esc_html($item['working_hours']); ?></div><?php
                }?>
            </div><?php

        }

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