<?php
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{Widget_Base, Controls_Manager, Group_Control_Typography};

/**
 * Search widget for Header CPT
 *
 *
 * @category Class
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Wgl_Header_Search extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-header-search';
    }

    public function get_title()
    {
        return esc_html__('WGL Search', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-header-search';
    }

    public function get_categories()
    {
        return ['wgl-header-modules'];
    }

    public function get_script_depends()
    {
        return [ 'wgl-elementor-extensions-widgets' ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'cleenday-core')]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
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
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-search' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'height_full',
            [
                'label' => esc_html__('Full Height', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
                'prefix_class' => 'full-height-',
            ]
        );

        $this->add_control(
            'height_custom',
            [
                'label' => esc_html__('Height', 'cleenday-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['height_full' => ''],
                'min' => 0,
                'default' => 55,
                'selectors' => [
                    '{{WRAPPER}} .header_search' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> SEARCH
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_search',
            [
                'label' => esc_html__('Search', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search',
                'selector' => '{{WRAPPER}} .header_search-button, {{WRAPPER}} .header_search-close',
                'exclude' => ['font_family', 'text_transform', 'font_style', 'text_decoration', 'letter_spacing'],
            ]
        );

        $this->start_controls_tabs(
            'icon',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_icon_idle',
            ['label' => esc_html__('Idle' , 'cleenday-core')]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-button,
                     {{WRAPPER}} .header_search-close' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search .wgl-search' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover' , 'cleenday-core')]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-search:hover .header_search-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-search:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_active',
            ['label' => esc_html__('Active' , 'cleenday-core')]
        );

        $this->add_control(
            'icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-close' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_active',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search.header_search-open .wgl-search' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'search_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-search' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .header_search,
                     {{WRAPPER}} .wgl-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        $description = esc_html__('Type To Search', 'cleenday-core');
        $search_style = \Cleenday_Theme_Helper::get_option('search_style') ?? 'standard';
        $search_counter = null;

        if (class_exists('\Cleenday_Get_Header')) {
            $search_counter = \Cleenday_Get_Header::$search_form_counter ?? null;
        }

        $search_class = ' search_' . \Cleenday_Theme_Helper::get_option('search_style');

        $render_search = true;
        if ($search_style === 'alt') {
            // the only search form in Default and Sticky headers is allowed
            $render_search = $search_counter > 0 ? false : true;

            if (isset($search_counter)) \Cleenday_Get_Header::$search_form_counter++;
        }

        $this->add_render_attribute('search', 'class', 'wgl-search elementor-search header_search-button-wrapper');
        $this->add_render_attribute('search', 'role', 'button');

        echo '<div class="header_search', esc_attr($search_class), '">';

	        echo '<div ', $this->get_render_attribute_string('search'), '>'; ?>
	            <div class="header_search-button flaticon-null-2"></div>
	            <div class="header_search-close flaticon-close"></div><?php
	        echo '</div>';
	
	        if ($render_search) { ?>
	            <div class="header_search-field"><?php
	            if ('alt' === $search_style) { ?>
	                <div class="header_search-wrap">
	                    <div class="cleenday_module_double_headings aleft">
	                    <h3 class="header_search-heading_description heading_title">
	                        <?php echo apply_filters('cleenday/search/description', $description); ?>
	                    </h3>
	                    </div>
	                    <div class="header_search-close"></div>
	                </div><?php
	            } else { ?>
		            <div class="header_search-close flaticon-close"></div><?php
	            }
	                echo get_search_form(false); ?>
	            </div><?php
	        }

        echo '</div>';
    }
}
