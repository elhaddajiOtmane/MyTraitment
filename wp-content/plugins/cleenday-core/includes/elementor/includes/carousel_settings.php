<?php

namespace WglAddons\Includes;

defined('ABSPATH') || exit;

use Elementor\{Frontend, Controls_Manager, Group_Control_Box_Shadow};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;

if (!class_exists('Wgl_Carousel_Settings')) {
    /**
     * WGL Elementor Carousel Settings
     *
     *
     * @category Class
     * @package cleenday-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Wgl_Carousel_Settings
    {
        private static $instance = null;

        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function options($self, $array = [])
        {
	        if (! $self) return;

	        $default_use_carousel = $array['default_use_carousel'] ?? '';
	        $condition_use_carousel = $array['condition'] ?? [];
	        $hide_opt_responsive = $array['hide_opt_responsive'] ?? [];

            $desktop_width = get_option('elementor_container_width') ?: '1140';
            $tablet_width = get_option('elementor_viewport_lg') ?: '1025';
            $mobile_width = get_option('elementor_viewport_md') ?: '768';

            $self->start_controls_section(
                'wgl_carousel_section',
                [
                	'label' => esc_html__('Carousel Options', 'cleenday-core'),
	                'condition' => $condition_use_carousel,
                ]
            );

            $self->add_control(
                'use_carousel',
                [
                    'label' => esc_html__('Use Carousel', 'cleenday-core'),
                    'type' => $default_use_carousel ? Controls_Manager::HIDDEN : Controls_Manager::SWITCHER,
                    'default' => $default_use_carousel,
                ]
            );

            $self->add_control(
                'autoplay',
                [
                    'label' => esc_html__('Autoplay', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_carousel' => 'yes'],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

            $self->add_control(
                'autoplay_speed',
                [
                    'label' => esc_html__('Autoplay Speed', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => ['autoplay' => 'yes'],
                    'min' => 1,
                    'step' => 1,
                    'default' => '3000',
                ]
            );

            $self->add_control(
                'fade_animation',
                [
                    'label' => esc_html__('Fade Animation', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'use_carousel' => 'yes',
                        'posts_per_line' => '1',
                    ],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

            $self->add_control(
                'slides_to_scroll',
                [
                    'label' => esc_html__('Slide per single item', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_carousel' => 'yes'],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

            $self->add_control(
                'infinite',
                [
                    'label' => esc_html__('Infinite Loop Sliding', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_carousel' => 'yes'],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

            $self->add_control(
                'center_mode',
                [
                    'label' => esc_html__('Center Mode', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_carousel' => 'yes'],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

	        $self->add_control(
		        'center_info',
		        [
			        'label' => esc_html__('Show Center Item Info', 'cleenday-core'),
			        'type' => Controls_Manager::SWITCHER,
			        'condition' => [
				        'use_carousel' => 'yes',
				        'center_mode' => 'yes',
				        'portfolio_layout' => 'carousel'
			        ],
		        ]
	        );

	        $self->add_control(
		        'variable_width',
		        [
			        'label' => esc_html__('Variable Width', 'cleenday-core'),
			        'type' => Controls_Manager::SWITCHER,
			        'condition' => [
				        'use_carousel' => 'yes',
				        'center_mode' => 'yes',
			        ],
			        'label_on' => esc_html__('On', 'cleenday-core'),
			        'label_off' => esc_html__('Off', 'cleenday-core'),
		        ]
	        );

	        $self->add_responsive_control(
		        'variable_width_dim',
		        [
			        'label' => esc_html__('Set New Width for Items', 'cleenday-core'),
			        'type' => Controls_Manager::SLIDER,
			        'condition' => [
				        'use_carousel' => 'yes',
				        'center_mode' => 'yes',
			        	'variable_width' => 'yes',
			        ],
			        'render_type' => 'template',
			        'size_units' => ['px', 'vw'],
			        'range' => [
				        'px' => [
				        	'min' => 200,
				        	'max' => 1200
				        ],
			        ],
			        'desktop_default' => [
				        'size' => 630,
				        'unit' => 'px',
			        ],
			        'tablet_default' => [
				        'size' => 450,
				        'unit' => 'px',
			        ],
			        'mobile_default' => [
				        'size' => 300,
				        'unit' => 'px',
			        ],
			        'selectors' => [
				        '{{WRAPPER}} .slick-slide > div' => 'width: {{SIZE}}{{UNIT}};',
				        '{{WRAPPER}} .wgl-carousel .wgl-portfolio-list_item' => 'width: {{SIZE}}{{UNIT}};',
			        ],
		        ]
	        );

	        $self->add_control(
                'use_pagination',
                [
                    'label' => esc_html__('Add Pagination control', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_carousel' => 'yes'],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

            $self->add_control(
                'pag_type',
                [
                    'label' => esc_html__('Pagination Type', 'cleenday-core'),
                    'type' => 'wgl-radio-image',
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_pagination' => 'yes',
                    ],
                    'options' => [
                        'circle' => [
                            'title' => esc_html__('Circle', 'cleenday-core'),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_circle.png',
                        ],
                        'circle_border' => [
                            'title' => esc_html__('Empty Circle', 'cleenday-core'),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_circle_border.png',
                        ],
                        'square' => [
                            'title' => esc_html__('Square', 'cleenday-core'),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_square.png',
                        ],
                        'square_border' => [
                            'title' => esc_html__('Empty Square', 'cleenday-core'),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_square_border.png',
                        ],
                        'line' => [
                            'title' => esc_html__('Line', 'cleenday-core'),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_line.png',
                        ],
                        'line_circle' => [
                            'title' => esc_html__('Line - Circle', 'cleenday-core'),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_line_circle.png',
                        ],
                    ],
                    'default' => 'circle',
                ]
            );

            $self->add_control(
                'pag_align',
                [
                    'label' => esc_html__('Pagination Aligning', 'cleenday-core'),
                    'type' => Controls_Manager::SELECT,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_pagination' => 'yes',
                    ],
                    'options' => [
                        'left' => esc_html__('Left', 'cleenday-core'),
                        'right' => esc_html__('Right', 'cleenday-core'),
                        'center' => esc_html__('Center', 'cleenday-core'),
                    ],
                    'default' => 'center',
                ]
            );

            $self->add_control(
                'pag_offset',
                [
                    'label' => esc_html__('Pagination Top Offset', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_pagination' => 'yes',
                    ],
                    'min' => -500,
                    'step' => 1,
                    'default' => 30,
                    'selectors' => [
                        '{{WRAPPER}} .wgl-carousel .slick-dots' => 'margin-top: {{VALUE}}px;',
                    ],
                ]
            );

            $self->add_control(
                'custom_pag_color',
                [
                    'label' => esc_html__('Custom Pagination Color', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_pagination' => 'yes',
                    ],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

            $self->add_control(
                'pag_color',
                [
                    'label' => esc_html__('Pagination Color', 'cleenday-core'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_pagination' => 'yes',
	                    'custom_pag_color' => 'yes'
                    ],
                'default' => Cleenday_Globals::get_main_font_color(),
                    'dynamic' => ['active' => true],
                    'selectors' => [
	                    '{{WRAPPER}} .pagination_circle .slick-dots li button,
		                 {{WRAPPER}} .pagination_line .slick-dots li button:before,
		                 {{WRAPPER}} .pagination_line_circle .slick-dots li button,
		                 {{WRAPPER}} .pagination_square .slick-dots li button,
		                 {{WRAPPER}} .pagination_square_border .slick-dots li button:before,
		                 {{WRAPPER}} .pagination_circle_border .slick-dots li button:before' => 'background-color: {{VALUE}};',
	                    '{{WRAPPER}} .pagination_circle_border .slick-dots li.slick-active button,
                         {{WRAPPER}}.pagination_square_border .slick-dots li.slick-active button' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_control(
                'pag_color_bg',
                [
                    'label' => esc_html__('Pagination Wrapper Color', 'cleenday-core'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_pagination' => 'yes',
	                    'custom_pag_color' => 'yes'
                    ],
                    'dynamic' => ['active' => true],
                    'default' => '#ffffff',
                    'selectors' => [
	                    '{{WRAPPER}} .slick-dots' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_control(
                'use_prev_next',
                [
                    'label' => esc_html__('Add Prev/Next buttons', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_carousel' => 'yes'],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

            $self->add_responsive_control(
                'prev_next_offset',
                [
                    'label' => esc_html__('Arrows Vertical Offset', 'cleenday-core'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_prev_next!' => ''
                    ],
                    'size_units' => ['px', '%', 'rem'],
                    'range' => [
                        'px' => ['max' => 500],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-arrow' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_responsive_control(
                'prev_next_h_offset',
                [
                    'label' => esc_html__('Arrows Horizontal Offset', 'cleenday-core'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_prev_next!' => ''
                    ],
                    'size_units' => ['px', '%', 'rem'],
                    'range' => [
                        'px' => ['min' => -200, 'max' => 200],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-next' => 'right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                'custom_prev_next_color',
                [
                    'label' => esc_html__('Customize Prev/Next Buttons', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'use_prev_next' => 'yes'
                    ],
                ]
            );

            $self->start_controls_tabs(
                'arrows_style',
                [
                    'condition' => [
	                    'use_carousel' => 'yes',
                        'use_prev_next' => 'yes',
                        'custom_prev_next_color' => 'yes'
                    ]
                ]
            );

            $self->start_controls_tab(
                'arrows_button_normal',
                ['label' => esc_html__('Idle', 'cleenday-core')]
            );

            $self->add_control(
                'prev_next_color',
                [
                    'label' => esc_html__('Button Text Color', 'cleenday-core'),
                    'type' =>  Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'default' => Cleenday_Globals::get_h_font_color(),
                    'selectors' => [
	                    '{{WRAPPER}} .slick-arrow:after' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_control(
                'prev_next_bg_idle',
                [
                    'label' => esc_html__('Button Background Color', 'cleenday-core'),
                    'type' =>  Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'default' => '#ffffff',
                    'selectors' => [
	                    '{{WRAPPER}} .slick-arrow' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_control(
                'prev_next_border_idle',
                [
                    'label' => esc_html__('Button Border Color', 'cleenday-core'),
                    'type' =>  Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
	                    '{{WRAPPER}} .slick-arrow' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'prev_next_shadow_idle',
                    'selector' => '{{WRAPPER}} .slick-arrow',
                ]
            );

            $self->add_control(
                'prev_next_divider_idle',
                ['type' => Controls_Manager::DIVIDER]
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                'arrows_button_hover',
                ['label' => esc_html__('Hover', 'cleenday-core')]
            );

            $self->add_control(
                'prev_next_color_hover',
                [
                    'label' => esc_html__('Button Text Color', 'cleenday-core'),
                    'type' =>  Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
	                    '{{WRAPPER}} .slick-arrow:hover:after' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_control(
                'prev_next_bg_hover',
                [
                    'label' => esc_html__('Button Background Color', 'cleenday-core'),
                    'type' =>  Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
	                    '{{WRAPPER}} .slick-arrow:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

	        $self->add_control(
		        'prev_next_border_hover',
		        [
			        'label' => esc_html__('Button Border Color', 'cleenday-core'),
			        'type' =>  Controls_Manager::COLOR,
			        'dynamic' => ['active' => true],
			        'selectors' => [
				        '{{WRAPPER}} .slick-arrow:hover' => 'border-color: {{VALUE}};',
			        ],
		        ]
	        );

            $self->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'prev_next_shadow_hover',
                    'selector' => '{{WRAPPER}} .slick-arrow:hover',
                ]
            );

            $self->add_control(
                'prev_next_divider_hover',
                ['type' => Controls_Manager::DIVIDER]
            );

            $self->end_controls_tab();
            $self->end_controls_tabs();

            $self->add_control(
                'custom_resp',
                [
                    'label' => esc_html__('Customize Responsive', 'cleenday-core'),
	                'type' => $hide_opt_responsive ? Controls_Manager::HIDDEN : Controls_Manager::SWITCHER,
                    'condition' => [
	                    'use_carousel' => 'yes',
	                    'center_mode!' => 'yes',
                    ],
                    'label_on' => esc_html__('On', 'cleenday-core'),
                    'label_off' => esc_html__('Off', 'cleenday-core'),
                ]
            );

            $self->add_control(
                'heading_desktop',
                [
                    'label' => esc_html__('Desktop Settings', 'cleenday-core'),
                    'type' => Controls_Manager::HEADING,
                    'condition' => ['custom_resp' => 'yes'],
                ]
            );

            $self->add_control(
                'resp_medium',
                [
                    'label' => esc_html__('Desktop Screen Breakpoint', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => ['custom_resp' => 'yes'],
                    'default' => $desktop_width,
                    'min' => 500,
                ]
            );

            $self->add_control(
                'resp_medium_slides',
                [
                    'label' => esc_html__('Slides to show', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => ['custom_resp' => 'yes'],
                    'min' => 1,
                ]
            );

            $self->add_control(
                'heading_tablet',
                [
                    'label' => esc_html__('Tablet Settings', 'cleenday-core'),
                    'type' => Controls_Manager::HEADING,
                    'condition' => ['custom_resp' => 'yes'],
                    'separator' => 'before',
                ]
            );

            $self->add_control(
                'resp_tablets',
                [
                    'label' => esc_html__('Tablet Screen Breakpoint', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => ['custom_resp' => 'yes'],
                    'min' => 400,
                    'default' => $tablet_width,
                ]
            );

            $self->add_control(
                'resp_tablets_slides',
                [
                    'label' => esc_html__('Slides to show', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => ['custom_resp' => 'yes'],
                    'min' => 1,
                    'step' => 1,
                ]
            );

            $self->add_control(
                'heading_mobile',
                [
                    'label' => esc_html__('Mobile Settings', 'cleenday-core'),
                    'type' => Controls_Manager::HEADING,
                    'condition' => ['custom_resp' => 'yes'],
                    'separator' => 'before',
                ]
            );

            $self->add_control(
                'resp_mobile',
                [
                    'label' => esc_html__('Mobile Screen Breakpoint', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => ['custom_resp' => 'yes'],
                    'min' => 300,
                    'default' => $mobile_width,
                ]
            );

            $self->add_control(
                'resp_mobile_slides',
                [
                    'label' => esc_html__('Slides to show', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => ['custom_resp' => 'yes'],
                    'min' => 1,
                ]
            );

            $self->end_controls_section();
        }

        public static function init($_s, $items = [], $templates = false)
        {
            wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js');

	        $carousel_defaults = [
		        // General
		        'items_per_line' => '1',
		        'speed' => '300',
		        'autoplay' => true,
		        'autoplay_speed' => '3000',
		        'slides_to_scroll' => false,
		        'infinite' => false,
		        'adaptive_height' => false,
		        'fade_animation' => false,
		        'variable_width' => false,
		        'extra_class' => '',
		        // Navigation
		        'use_pagination' => true,
		        'use_navigation' => false,
		        'pag_type' => 'circle_border',
		        'nav_type' => 'element',
		        'pag_align' => 'center',
		        'custom_pag_color' => false,
		        'pag_color' => Cleenday_Globals::get_h_font_color(),
		        'center_mode' => false,
		        'use_prev_next' => false,
		        'custom_prev_next_color' => false,
		        'prev_next_color' => Cleenday_Globals::get_h_font_color(),
		        'prev_next_color_hover' => '',
		        'prev_next_bg_idle' => '#ffffff',
		        'prev_next_bg_hover' => '',
		        'prev_next_border_color' => Cleenday_Globals::get_h_font_color(),
		        'prev_next_border_color_hover' => '',
		        // Responsive
		        'custom_resp' => false,
		        'resp_medium' => '1025',
		        'resp_medium_slides' => '',
		        'resp_tablets' => '993',
		        'resp_tablets_slides' => '',
		        'resp_mobile' => '480',
		        'resp_mobile_slides' => '',
	        ];
	        $_s = array_merge($carousel_defaults, array_intersect_key($_s, $carousel_defaults));

            switch ($_s['items_per_line']) {
                case '2':
                    $responsive_medium = 2;
                    $responsive_tablets = 2;
                    $responsive_mobile = 1;
                    break;
                case '3':
                    $responsive_medium = 3;
                    $responsive_tablets = 2;
                    $responsive_mobile = 1;
                    break;
                case '4':
                case '5':
                case '6':
                    $responsive_medium = 4;
                    $responsive_tablets = 2;
                    $responsive_mobile = 1;
                    break;
                default:
                    $responsive_medium = 1;
                    $responsive_tablets = 1;
                    $responsive_mobile = 1;
                    break;
            }

            // If custom responsive
            if ($_s['custom_resp']) {
                $responsive_medium = !empty($_s['resp_medium_slides']) ? (int) $_s['resp_medium_slides'] : $responsive_medium;
                $responsive_tablets = !empty($_s['resp_tablets_slides']) ? (int) $_s['resp_tablets_slides'] : $responsive_tablets;
                $responsive_mobile = !empty($_s['resp_mobile_slides']) ? (int) $_s['resp_mobile_slides'] : $responsive_mobile;
            }

            if ($_s['slides_to_scroll']) {
                $responsive_sltscrl_medium = $responsive_sltscrl_tablets = $responsive_sltscrl_mobile = 1;
            } else {
                $responsive_sltscrl_medium = $responsive_medium;
                $responsive_sltscrl_tablets = $responsive_tablets;
                $responsive_sltscrl_mobile = $responsive_mobile;
            }

            $data_array = [];
            $data_array['slidesToShow'] = (int) $_s['items_per_line'];
            $data_array['slidesToScroll'] = $_s['slides_to_scroll'] ? 1 : (int) $_s['items_per_line'];
            $data_array['infinite'] = $_s['infinite'] ? true : false;
            $data_array['variableWidth'] = $_s['variable_width'] ? true : false;

            $data_array['autoplay'] = $_s['autoplay'] ? true : false;
            $data_array['autoplaySpeed'] = $_s['autoplay_speed'] ?: '';
            $data_array['speed'] = $_s['speed'] ? (int) $_s['speed'] : '300';
            if ($_s['center_mode']) {
                $data_array['centerMode'] = $_s['center_mode'] ? true : false;
                $data_array['centerPadding'] = '0px';
            }

            $data_array['arrows'] = $_s['use_prev_next'] ? true : false;
            $data_array['dots'] = $_s['use_pagination'] ? true : false;
            $data_array['adaptiveHeight'] = $_s['adaptive_height'] ? true : false;

            // Responsive settings
            $data_array['responsive'][0]['breakpoint'] = (int) $_s['resp_medium'];
            $data_array['responsive'][0]['settings']['slidesToShow'] = (int) esc_attr($responsive_medium);
            $data_array['responsive'][0]['settings']['slidesToScroll'] = (int) esc_attr($responsive_sltscrl_medium);

            $data_array['responsive'][1]['breakpoint'] = (int) $_s['resp_tablets'];
            $data_array['responsive'][1]['settings']['slidesToShow'] = (int) esc_attr($responsive_tablets);
            $data_array['responsive'][1]['settings']['slidesToScroll'] = (int) esc_attr($responsive_sltscrl_tablets);

            $data_array['responsive'][2]['breakpoint'] = (int) $_s['resp_mobile'];
            $data_array['responsive'][2]['settings']['slidesToShow'] = (int) esc_attr($responsive_mobile);
            $data_array['responsive'][2]['settings']['slidesToScroll'] = (int) esc_attr($responsive_sltscrl_mobile);

            $data_attribute = " data-slick='" . json_encode($data_array, true) . "'";

            // Classes
	        $carousel_wrap_classes = $_s['use_pagination'] ? ' pagination_' . $_s['pag_type'] : '';
	        $carousel_wrap_classes .= $_s['use_navigation'] ? ' navigation_' . $_s['nav_type'] : '';
            $carousel_wrap_classes .= $_s['use_pagination'] ? ' pag_align_' . $_s['pag_align'] : '';
	        $carousel_wrap_classes .= $_s['items_per_line'] ? ' items_per_line-' . (int) $_s['items_per_line'] : '';

            $carousel_classes = $_s['fade_animation'] ? ' fade_slick' : '';

            // Render
            $output = '<div class="wgl-carousel_wrapper">';
            $output .= '<div class="wgl-carousel' . esc_attr($carousel_wrap_classes) . '">';
            $output .= '<div class="wgl-carousel_slick' . $carousel_classes . '"' . $data_attribute . '>';

            if (!empty($templates)) {
	            if (!empty($items)) {
                    ob_start();
                    foreach ($items as $id) {
                        ?><div class="item"><?php
                            echo (new Frontend())->get_builder_content_for_display($id, true); ?>
                        </div><?php
                    }
                    $output .= ob_get_clean();
	            }
            } else {
                $output .= $items;
            }

            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>'; // wgl-carousel_wrapper

            return $output;
        }
    }

    new Wgl_Carousel_Settings();
}
