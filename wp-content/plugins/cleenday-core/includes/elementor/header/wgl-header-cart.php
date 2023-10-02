<?php
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{Plugin, Widget_Base, Controls_Manager};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;

/**
 * Cart widget for Header CPT
 *
 *
 * @category Class
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Wgl_Header_Cart extends Widget_Base
{
    public function get_name() {
        return 'wgl-header-cart';
    }

    public function get_title() {
        return esc_html__('WooCart', 'cleenday-core');
    }

    public function get_icon() {
        return 'wgl-header-cart';
    }

    public function get_categories() {
        return [ 'wgl-header-modules' ];
    }

    public function get_script_depends() {
        return [
            'wgl-elementor-extensions-widgets',
        ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_search_settings',
            [ 'label' => esc_html__('General', 'cleenday-core') ]
        );

        $this->add_control(
            'cart_height',
            [
                'label' => esc_html__('Cart Icon Height', 'cleenday-core'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .mini-cart' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'cart_align',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'toggle' => true,
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
                'selectors' => [
                    '{{WRAPPER}} .wgl-mini-cart_wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__('General', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('icon_style_tabs');

        $this->start_controls_tab(
            'tab_idle',
            [ 'label' => esc_html__('Idle' , 'cleenday-core') ]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .mini-cart .wgl-cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'counter_color_idle',
            [
                'label' => esc_html__('Items Counter Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
	            'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .woo_mini-count > span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'counter_bg_idle',
            [
                'label' => esc_html__('Items Counter Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .woo_mini-count > span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_hover',
            [ 'label' => esc_html__('Hover' , 'cleenday-core') ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart:hover .wgl-cart' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'counter_color_hover',
		    [
			    'label' => esc_html__('Items Counter Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .mini-cart:hover .woo_mini-count > span' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

        $this->add_control(
            'counter_bg_hover',
            [
                'label' => esc_html__('Items Counter Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart:hover .woo_mini-count > span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function render()
    {
        if (!class_exists('\WooCommerce')) {
            return;
        } ?>
        <div class="wgl-mini-cart_wrapper">
            <div class="mini-cart woocommerce"><?php
                echo \Cleenday_Theme_Helper::render_html($this->icon_cart()),
                self::woo_cart(); ?>
            </div>
        </div><?php
    }

    public function icon_cart()
    {
        ob_start();
        $this->add_render_attribute('cart', 'class', 'wgl-cart woo_icon elementor-cart');
        $this->add_render_attribute('cart', 'role', 'button' );
        $this->add_render_attribute('cart', 'title', esc_attr__('Click to open Shopping Cart', 'cleenday-core')); ?>
        <a <?php echo \Cleenday_Theme_Helper::render_html($this->get_render_attribute_string('cart')); ?>>
            <span class="woo_mini-count">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="510px" height="510px" viewBox="0 0 510 510" xml:space="preserve">
					<path d="M153,408c-28.05,0-51,22.95-51,51s22.95,51,51,51s51-22.95,51-51S181.05,408,153,408z M0,0v51h51l91.8,193.8L107.1,306
			c-2.55,7.65-5.1,17.85-5.1,25.5c0,28.05,22.95,51,51,51h306v-51H163.2c-2.55,0-5.1-2.55-5.1-5.1v-2.551l22.95-43.35h188.7
			c20.4,0,35.7-10.2,43.35-25.5L504.9,89.25c5.1-5.1,5.1-7.65,5.1-12.75c0-15.3-10.2-25.5-25.5-25.5H107.1L84.15,0H0z M408,408
			c-28.05,0-51,22.95-51,51s22.95,51,51,51s51-22.95,51-51S436.05,408,408,408z"/>
				</svg><?php
                if (!(bool) Plugin::$instance->editor->is_edit_mode() && \WooCommerce::instance()->cart->cart_contents_count > 0) { ?>
                    <span><?php echo esc_html( \WooCommerce::instance()->cart->cart_contents_count ); ?></span><?php
                } ?>
            </span>
        </a><?php

        return ob_get_clean();
    }

    public static function woo_cart()
    {
        ob_start(); ?>
        <div class="wgl-woo_mini_cart"><?php
        if (!(bool) Plugin::$instance->editor->is_edit_mode() ) {
            woocommerce_mini_cart();
        } ?>
        </div><?php

        return ob_get_clean();
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