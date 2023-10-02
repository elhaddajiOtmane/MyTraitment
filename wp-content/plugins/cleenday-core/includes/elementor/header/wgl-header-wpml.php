<?php
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use Elementor\{Group_Control_Typography, Widget_Base, Controls_Manager};

/**
 * WPML widget for Header CPT
 *
 *
 * @category Class
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Wgl_Header_Wpml extends Widget_Base
{
    public function get_name() {
        return 'wgl-header-wpml';
    }

    public function get_title() {
        return esc_html__('WPML Selector', 'cleenday-core' );
    }

    public function get_icon() {
        return 'wgl-header-wpml';
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
        $this->start_controls_section(
            'section_navigation_settings',
            [
                'label' => esc_html__( 'WPML Settings', 'cleenday-core' ),
            ]
        );
	
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'module_title',
			    'selector' => '{{WRAPPER}} .wpml-ls-legacy-dropdown a',
		    ]
	    );

        $this->add_control(
            'wpml_height',
            array(
                'label' => esc_html__( 'WPML Height', 'cleenday-core' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 100,
                'description' => esc_html__( 'Enter value in pixels', 'cleenday-core' ),
                'selectors' => [
                    '{{WRAPPER}} .sitepress_container' => 'height: {{VALUE}}px;',
                ],
            )
        );

        $this->add_control(
            'wpml_align',
            array(
                'label' => esc_html__( 'Alignment', 'cleenday-core' ),
                'type' => Controls_Manager::CHOOSE,
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
                ],
                'label_block' => false,
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .sitepress_container,
                     {{WRAPPER}} .wpml-ls-sub-menu' => 'text-align: {{VALUE}};',
                ],
            )
        );
	
	    $this->add_control(
		    'wpml_sub_menu_position',
		    [
			    'label' => esc_html__('Sub Menu Position', 'cleenday-core'),
			    'type' => Controls_Manager::CHOOSE,
			    'options' => [
				    'left' => [
					    'title' => esc_html__('Left', 'cleenday-core'),
					    'icon' => 'eicon-h-align-left',
				    ],
				    'center' => [
					    'title' => esc_html__('Center', 'cleenday-core'),
					    'icon' => 'eicon-h-align-center',
				    ],
				    'right' => [
					    'title' => esc_html__('Right', 'cleenday-core'),
					    'icon' => 'eicon-h-align-right',
				    ],
			    ],
			    'default' => 'right',
			    'prefix_class' => 'submenu-pos-',
		    ]
	    );
	
	    $this->start_controls_tabs('wpml_tabs');
	
	    $this->start_controls_tab(
		    'wpml_idle',
		    ['label' => esc_html__('Idle', 'cleenday-core')]
	    );
	
	    $this->add_control(
		    'wpml_text_color_idle',
		    [
			    'label' => esc_html__('Text Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} a.wpml-ls-item-toggle' => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'menu_icon_idle',
		    [
			    'label' => esc_html__('Child Indicator Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'default' => Cleenday_Globals::get_additional_font_color(),
			    'selectors' => [
				    '{{WRAPPER}} a.wpml-ls-item-toggle:after' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	
	    $this->end_controls_tab();
	
	    $this->start_controls_tab(
		    'wpml_hover',
		    ['label' => esc_html__('Hover', 'cleenday-core')]
	    );
	
	    $this->add_control(
		    'wpml_text_color_hover',
		    [
			    'label' => esc_html__('Text Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} a.wpml-ls-item-toggle:hover' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'menu_icon_hover',
		    [
			    'label' => esc_html__('Child Indicator Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wpml-ls-legacy-dropdown a.wpml-ls-item-toggle:hover:after' => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	
	    $this->end_controls_tab();
	    $this->end_controls_tabs();
	    
	    $this->end_controls_section();
    }

    public function render(){
	    if (class_exists('\SitePress')) {
		    ob_start();
		    do_action('wpml_add_language_selector');
		    $wpml_render = ob_get_clean();
		
		    if(!empty($wpml_render)){
			    echo "<div class='sitepress_container'>";
			    do_action('wpml_add_language_selector');
			    echo "</div>";
		    }
	    }
    }
}