<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-progress-bar.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager};
use Elementor\{Group_Control_Border, Group_Control_Typography, Group_Control_Box_Shadow};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;

class Wgl_Progress_Bar extends Widget_Base
{
    public function get_name() {
        return 'wgl-progress-bar';
    }

    public function get_title() {
        return esc_html__('WGL Progress Bar', 'cleenday-core');
    }

    public function get_icon() {
        return 'wgl-progress-bar';
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
    }

    public function get_script_depends() {
        return [ 'jquery-appear' ];
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
            'progress_title',
            [
                'label' => esc_html__('Title', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('ex: My Skill', 'cleenday-core'),
                'default' => esc_html__('MY SKILL', 'cleenday-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'value',
            [
                'label' => esc_html__('Value', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'units',
            [
                'label' => esc_html__('Units', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_attr__('ex: %, px, points, etc.', 'cleenday-core'),
                'default' => esc_html__('%', 'cleenday-core'),
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
                'name' => 'progress_title_typo',
                'selector' => '{{WRAPPER}} .progress_label_wrap',
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
                'default' => 'div',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .progress_label' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .progress_label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> VALUE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_value',
            [
                'label' => esc_html__('Value', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'progress_value_typo',
                'selector' => '{{WRAPPER}} .progress_value_wrap',
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .progress_value_wrap' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'value_bg',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .progress_value_wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'value_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .progress_value_wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'value_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .progress_value_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'value_position',
            [
                'label' => esc_html__('Value Position', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fixed' => esc_html__('Fixed', 'cleenday-core'),
                    'dynamic' => esc_html__('Dynamic', 'cleenday-core'),
                ],
                'default' => 'fixed',
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> PROGRESS BAR
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_bar',
            [
                'label' => esc_html__('Bar', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bar_height_filled',
            [
                'label' => esc_html__('Filled Bar Height', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 50 ],
                ],
                'default' => [ 'size' => 8 ],
                'selectors' => [
                    '{{WRAPPER}} .progress_bar_wrap .progress_bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_height_empty',
            [
                'label' => esc_html__('Empty Bar Height', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 50 ],
                ],
                'default' => [ 'size' => 8 ],
                'selectors' => [
                    '{{WRAPPER}} .progress_bar_wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_bg_empty',
            [
                'label' => esc_html__('Empty Bar Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ece8ec',
                'selectors' => [
                    '{{WRAPPER}} .progress_bar_wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bar_color_filled',
            [
                'label' => esc_html__('Filled Bar Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .progress_bar' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bar_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .progress_bar_wrap-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bar_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => '11',
                    'right' => '0',
                    'bottom' => '6',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .progress_bar_wrap-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .progress_bar_wrap,
                     {{WRAPPER}} .progress_bar,
                     {{WRAPPER}} .progress_bar_wrap-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bar_box_shadow',
                'selector' => '{{WRAPPER}} .progress_bar_wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .progress_bar_wrap-wrap',
            ]
        );

        $this->end_controls_section();

    }

    public function render()
    {
        $_s = $this->get_settings_for_display();

        $this->add_render_attribute('progress_bar', 'class', [
            'wgl-progress_bar',
            $_s['value_position'] == 'dynamic' ? 'dynamic-value' : '',
        ]);

        $this->add_render_attribute('bar', [
            'class' => 'progress_bar',
            'data-width' => esc_attr((int)$_s['value']['size']),
        ]);

        $this->add_render_attribute('label', 'class', 'progress_label');

        // Render ?>
        <div <?php echo $this->get_render_attribute_string('progress_bar'); ?>>
            <div class="progress_wrap">
                <div class="progress_label_wrap"><?php
                    if (!empty($_s['progress_title'])) {
                        echo '<', esc_attr($_s['title_tag']), ' ',
                            $this->get_render_attribute_string('label'),
                            '>',
                            esc_html($_s['progress_title']),
                            '</', esc_attr($_s['title_tag']), '>';
                    } ?>
                    <div class="progress_value_wrap">
                        <span class="progress_value">0</span><?php
                        if (!empty($_s['units'])) { ?>
                            <span class="progress_units"><?php
                                echo esc_html($_s['units']); ?>
                            </span><?php
                        }?>
                    </div>
                </div>
                <div class="progress_bar_wrap-wrap">
                    <div class="progress_bar_wrap">
                        <div <?php echo $this->get_render_attribute_string('bar'); ?>></div>
                    </div>
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