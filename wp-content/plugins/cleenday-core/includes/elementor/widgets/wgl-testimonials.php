<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-testimonials.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager, Utils, Repeater};
use Elementor\{Group_Control_Border,
	Group_Control_Box_Shadow,
	Group_Control_Css_Filter,
	Group_Control_Typography,
	Group_Control_Background};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use WglAddons\Includes\Wgl_Carousel_Settings;
use WglAddons\Templates\WglTestimonials;

class Wgl_Testimonials extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-testimonials';
    }

    public function get_title()
    {
        return esc_html__('WGL Testimonials', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-testimonials';
    }

    public function get_script_depends()
    {
        return ['slick'];
    }

    public function get_categories()
    {
        return ['wgl-extensions'];
    }


    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_testimonials_section',
            ['label' => esc_html__('General', 'cleenday-core')]
        );

        $this->add_control(
            'items_per_line',
            [
                'label' => esc_html__('Grid Columns Amount', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('One ', 'cleenday-core'),
                    '2' => esc_html__('Two', 'cleenday-core'),
                    '3' => esc_html__('Three', 'cleenday-core'),
                    '4' => esc_html__('Four', 'cleenday-core'),
                    '5' => esc_html__('Five', 'cleenday-core'),
                ],
                'default' => '1',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Author Image', 'cleenday-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'author_name',
            [
                'label' => esc_html__('Author Name', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'link_author',
            [
                'label' => esc_html__('Link Author', 'cleenday-core'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'author_rating',
            [
                'label' => esc_html__('Rating Text', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'author_rating_count',
            [
                'label' => esc_html__('Rating Count', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'quote',
            [
                'label' => esc_html__('Quote', 'cleenday-core'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Items', 'cleenday-core'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'author_name' => esc_html__('Tina Johanson', 'cleenday-core'),
                        'author_rating' => esc_html__('Rating', 'cleenday-core'),
                        'author_rating_count' => esc_html__('4.9', 'cleenday-core'),
                        'quote' => esc_html__('Fermentum et sollicitudin ac orci phasellus egestas tellus rutrellus. Scelerisque felis imperdiet proin fermentum leo pretium orci porta. Mattis pellentesque tortor.', 'cleenday-core'),
                        'thumbnail' => Utils::get_placeholder_image_src(),
                    ],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ author_name }}}',
            ]
        );

        $this->add_control(
            'item_type',
            [
                'label' => esc_html__('Layout', 'cleenday-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'author_top' => [
                        'title' => esc_html__('Top', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_1.png',
                    ],
                    'author_bottom' => [
                        'title' => esc_html__('Bottom', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_4.png',
                    ],
                    'inline_top' => [
                        'title' => esc_html__('Top Inline', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_2.png',
                    ],
                    'inline_bottom' => [
                        'title' => esc_html__('Bottom Inline', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_3.png',
                    ],
                ],
                'default' => 'inline_bottom',
            ]
        );

        $this->add_control(
            'item_align',
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
                'toggle' => true,
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__('Enable Hover Animation', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Lift up the item on hover.', 'cleenday-core'),
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CAROUSEL OPTIONS
        /*-----------------------------------------------------------------------------------*/

        Wgl_Carousel_Settings::options($this);

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> IMAGE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Author Image', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => esc_html__('Image Width', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 20, 'max' => 1000],
                ],
                'default' => ['size' => 60],
	            'selectors' => [
		            '{{WRAPPER}} .wgl-testimonials_image img' => 'width: {{SIZE}}{{UNIT}};',
	            ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Author Image Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Author Image Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'testimonials_image_shadow',
                'selector' => '{{WRAPPER}} .wgl-testimonials_image img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .wgl-testimonials_image img',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '50',
                    'left' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'unit' => '%',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> QUOTE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_quote',
            [
                'label' => esc_html__('Quote', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'quote_tag',
            [
                'label' => esc_html__('Quote tag', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'div' => '‹div›',
                    'span' => '‹span›',
                    'h1' => '‹h1›',
                    'h2' => '‹h2›',
                    'h3' => '‹h3›',
                    'h4' => '‹h4›',
                    'h5' => '‹h5›',
                    'h6' => '‹h6›',
                ],
                'default' => 'div',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_quote',
                'selector' => '{{WRAPPER}} .wgl-testimonials_quote',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_quote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_quote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_control(
		    'icon_color',
		    [
			    'label' => esc_html__('Icon Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'default' => Cleenday_Globals::get_h_font_color(),
			    'selectors' => [
				    '{{WRAPPER}} .wgl-testimonials_item .content_wrap:before' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'icon_size',
		    [
			    'label' => esc_html__('Quote Icon Size', 'cleenday-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 10, 'max' => 150],
			    ],
			    'default' => ['size' => 35],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-testimonials_item .content_wrap:before' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'icon_quote_margin',
		    [
			    'label' => esc_html__('Quote Icon Margin', 'cleenday-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-testimonials_item .content_wrap:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> AUTHOR NAME
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_author_name',
            [
                'label' => esc_html__('Author Name', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_tag',
            [
                'label' => esc_html__('HTML tag', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'div' => '‹div›',
                    'span' => '‹span›',
                    'h1' => '‹h1›',
                    'h2' => '‹h2›',
                    'h3' => '‹h3›',
                    'h4' => '‹h4›',
                    'h5' => '‹h5›',
                    'h6' => '‹h6›',
                ],
            ]
        );

        $this->add_responsive_control(
            'name_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('name_colors');

        $this->start_controls_tab(
            'tab_name_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'name_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_name_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'name_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_name:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_name',
                'selector' => '{{WRAPPER}} .wgl-testimonials_name',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> RATING
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_rating',
            [
                'label' => esc_html__('Rating', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'custom_fonts_rating',
			    'selector' => '{{WRAPPER}} .wgl-testimonials_rating',
		    ]
	    );

        $this->add_responsive_control(
            'rating_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'custom_rating_color',
            [
                'label' => esc_html__('Rating Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_rating' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'rating_count_padding',
		    [
			    'label' => esc_html__('Count Padding', 'cleenday-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-testimonials_rating_count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_control(
            'custom_rating_count_color',
            [
                'label' => esc_html__('Rating Count Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'separator' => 'before',
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_rating_count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'custom_rating_count_bg_color',
            [
                'label' => esc_html__('Rating Count BG Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_rating_count' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> ITEM BOX
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_item_box',
            [
                'label' => esc_html__('Item Box', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_item',
                'label' => esc_html__('Background', 'cleenday-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-testimonials_item:after',
            ]
        );

	    $this->add_group_control(
		    Group_Control_Css_Filter::get_type(),
		    [
			    'name' => 'background_grayscale',
			    'conditions' => [
				    'terms' => [
					    [
						    'relation' => 'or',
						    'terms' => [
							    [
								    'name' => 'background_item_image[url]',
								    'operator' => '!==',
								    'value' => '',
							    ],
							    [
								    'name' => 'background_item_color',
								    'operator' => '!==',
								    'value' => '',
							    ],
						    ],
					    ],
					    [
						    'name' => 'use_carousel',
						    'operator' => '===',
						    'value' => 'yes',
					    ],
					    [
						    'name' => 'center_mode',
						    'operator' => '===',
						    'value' => 'yes',
					    ],
				    ],
			    ],
			    'separator' => 'after',
			    'selector' => '{{WRAPPER}} .wgl-testimonials-item_wrap:not(.slick-center) .wgl-testimonials_item:after',
		    ]
	    );

	    $this->add_control(
		    'item_opacity',
		    [
			    'label' => esc_html__('Item Opacity', 'cleenday-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0.10, 'max' => 1, 'step' => 0.01],
			    ],
			    'default' => ['size' => 0.7],
			    'condition' => [
				    'use_carousel' => 'yes',
				    'center_mode' => 'yes',
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-testimonials-item_wrap:not(.slick-center) .wgl-testimonials_item' => 'opacity: {{SIZE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'testimonials_shadow',
                'selector' => '{{WRAPPER}} .wgl-testimonials_item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'testimonials_border',
                'label' => esc_html__('Border', 'cleenday-core'),
                'selector' => '{{WRAPPER}} .wgl-testimonials_item',
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_item' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
                    '{{WRAPPER}} .wgl-testimonials-item_wrap' => 'margin-left: {{LEFT}}{{UNIT}}; margin-right:{{RIGHT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '12',
                    'left' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

	    /*-----------------------------------------------------------------------------------*/
	    /*  STYLE -> STICKY THUMBNAIL
		/*-----------------------------------------------------------------------------------*/

	    $this->start_controls_section(
		    'section_style_sticky_thumb',
		    [
			    'label' => esc_html__('Sticky Thumbnail', 'cleenday-core'),
			    'tab' => Controls_Manager::TAB_STYLE,
		    ]
	    );

	    $this->add_control(
		    'sticky_thumb',
		    [
			    'label' => esc_html__('Sticky Thumbnail', 'cleenday-core'),
			    'type' => Controls_Manager::MEDIA,
			    'label_block' => true,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-testimonials_item:before' => 'content: url({{URL}});',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'sticky_thumb_margin',
		    [
			    'label' => esc_html__('Sticky Thumbnail Margin', 'cleenday-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-testimonials_item:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->end_controls_section();
    }

    protected function render()
    {
        echo (new WglTestimonials())->render($this, $this->get_settings_for_display());
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
