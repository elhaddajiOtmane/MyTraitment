<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-portfolio.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Background,
    Group_Control_Box_Shadow
};
use WglAddons\{Cleenday_Global_Variables as Cleenday_Globals,
	Includes\Wgl_Carousel_Settings,
	Includes\Wgl_Loop_Settings,
	Templates\WglPortfolio};

class Wgl_Portfolio extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-portfolio';
    }

    public function get_title()
    {
        return esc_html__('WGL Portfolio', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-portfolio';
    }

    public function get_categories()
    {
        return ['wgl-extensions'];
    }

    public function get_script_depends()
    {
        return [
            'slick',
            'imagesloaded',
            'isotope',
            'wgl-elementor-extensions-widgets',
        ];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'wgl_portfolio_section',
            ['label' => esc_html__('General', 'cleenday-core')]
        );

        $this->add_control(
            'portfolio_layout',
            [
                'label' => esc_html__('Layout', 'cleenday-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                    'masonry' => [
                        'title' => esc_html__('Masonry', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'masonry2' => [
                        'title' => esc_html__('Masonry 2', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_2.png',
                    ],
                    'masonry3' => [
                        'title' => esc_html__('Masonry 3', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_3.png',
                    ],
                    'masonry4' => [
                        'title' => esc_html__('Masonry 4', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_4.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'items_per_line',
            [
                'label' => esc_html__('Columns Amount', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'portfolio_layout' => ['grid', 'masonry', 'carousel']
                ],
                'options' => [
                    '1' => esc_html__('1', 'cleenday-core'),
                    '2' => esc_html__('2', 'cleenday-core'),
                    '3' => esc_html__('3', 'cleenday-core'),
                    '4' => esc_html__('4', 'cleenday-core'),
                    '5' => esc_html__('5', 'cleenday-core'),
                ],
                'default' => '3',
            ]
        );

        $this->add_control(
            'grid_gap',
            [
                'label' => esc_html__('Grid Gap', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '0px' => esc_html__('0', 'cleenday-core'),
                    '1px' => esc_html__('1', 'cleenday-core'),
                    '2px' => esc_html__('2', 'cleenday-core'),
                    '3px' => esc_html__('3', 'cleenday-core'),
                    '4px' => esc_html__('4', 'cleenday-core'),
                    '5px' => esc_html__('5', 'cleenday-core'),
                    '10px' => esc_html__('10', 'cleenday-core'),
                    '15px' => esc_html__('15', 'cleenday-core'),
                    '20px' => esc_html__('20', 'cleenday-core'),
                    '25px' => esc_html__('25', 'cleenday-core'),
                    '30px' => esc_html__('30', 'cleenday-core'),
                    '35px' => esc_html__('35', 'cleenday-core'),
                ],
                'default' => '30px',
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'label' => esc_html__('Image Size', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'portfolio_layout' => ['grid', 'carousel']
                ],
                'separator' => 'before',
                'options' => [
                    '150' => 'Thumbnail - 150x150',
                    '300' => 'Medium - 300x300',
                    '768' => 'Medium Large - 768x768',
                    '1024' => 'Large - 1024x1024',
                    '740x740' => '740x740', // 3col
                    '886x886' => '886x886', // 4col-wide
                    '1140x840' => '1140x840', // 2col
                    'full' => 'Full',
                    'custom' => 'Custom',
                ],
                'default' => '740x740',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'cleenday-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => [
                    'img_size_string' => 'custom',
                    'portfolio_layout' => ['grid', 'carousel']
                ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'cleenday-core'),
                'default' => [
                    'width' => '740',
                    'height' => '940',
                ]
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'portfolio_layout' => ['grid', 'carousel'],
                    'img_size_string!' => 'custom',
                ],
                'options' => [
                    '1:1' => '1:1',
                    '3:2' => '3:2',
                    '4:3' => '4:3',
                    '9:16' => '9:16',
                    '16:9' => '16:9',
                    '21:9' => '21:9',
                    '' => 'Not Crop',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => esc_html__('Navigation Type', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['portfolio_layout!' => 'carousel'],
                'separator' => 'before',
                'options' => [
                    'none' => esc_html__('None', 'cleenday-core'),
                    'pagination' => esc_html__('Pagination', 'cleenday-core'),
                    'infinite' => esc_html__('Infinite Scroll', 'cleenday-core'),
                    'load_more' => esc_html__('Load More', 'cleenday-core'),
                    'custom_link' => esc_html__('Custom Link', 'cleenday-core'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'item_link',
            [
                'label' => esc_html__('Link', 'cleenday-core'),
                'type' => Controls_Manager::URL,
                'condition' => ['navigation' => 'custom_link'],
                'placeholder' => esc_attr__('https://your-link.com', 'cleenday-core'),
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'link_position',
            [
                'label' => esc_html__('Link Position', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['navigation' => 'custom_link'],
                'options' => [
                    'below_items' => esc_html__('Below Items', 'cleenday-core'),
                    'after_items' => esc_html__('After Items', 'cleenday-core'),
                ],
                'default' => 'below_items',
            ]
        );

        $this->add_control(
            'link_align',
            [
                'label' => esc_html__('Link Alignment', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['navigation' => 'custom_link'],
                'options' => [
                    'left' => esc_html__('Left', 'cleenday-core'),
                    'center' => esc_html__('Сenter', 'cleenday-core'),
                    'right' => esc_html__('Right', 'cleenday-core'),
                ],
                'default' => 'left',
            ]
        );

        $this->add_responsive_control(
            'link_margin',
            [
                'label' => esc_html__('Spacing', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['navigation' => 'custom_link'],
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'left' => '0',
                    'right' => '0',
                    'bottom' => '60',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .wgl-portfolio_item_link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'nav_align',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['navigation' => 'pagination'],
                'options' => [
                    'left' => esc_html__('Left', 'cleenday-core'),
                    'center' => esc_html__('Сenter', 'cleenday-core'),
                    'right' => esc_html__('Right', 'cleenday-core'),
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'items_load',
            [
                'label' => esc_html__('Items to be loaded', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'portfolio_layout!' => 'carousel',
                    'navigation' => ['load_more', 'infinite'],
                ],
                'default' => esc_html__('4', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => esc_html__('Button Text', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'portfolio_layout!' => 'carousel',
                    'navigation' => ['load_more', 'custom_link'],
                ],
                'default' => esc_html__('LOAD MORE', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'add_animation',
            [
                'label' => esc_html__('Add Appear Animation', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'appear_animation',
            [
                'label' => esc_html__('Animation Style', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['add_animation' => 'yes'],
                'options' => [
                    'fade-in' => esc_html__('Fade In', 'cleenday-core'),
                    'slide-top' => esc_html__('Slide Top', 'cleenday-core'),
                    'slide-bottom' => esc_html__('Slide Bottom', 'cleenday-core'),
                    'slide-left' => esc_html__('Slide Left', 'cleenday-core'),
                    'slide-right' => esc_html__('Slide Right', 'cleenday-core'),
                    'zoom' => esc_html__('Zoom', 'cleenday-core'),
                ],
                'default' => 'fade-in',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> FILTER
         */

        $this->start_controls_section(
            'filter_section',
            [
                'label' => esc_html__('Filter', 'cleenday-core'),
                'condition' => ['portfolio_layout!' => 'carousel'],
            ]
        );

	    $this->add_control(
		    'show_filter',
		    [
			    'label' => esc_html__('Show Filter', 'cleenday-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'condition' => ['portfolio_layout!' => 'carousel'],
			    'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'filter_all_text',
		    [
			    'label' => esc_html__('All Text', 'cleenday-core'),
			    'type' => Controls_Manager::TEXT,
			    'condition' => [
			    	'portfolio_layout!' => 'carousel',
				    'show_filter' => 'yes'
			    ],
			    'default' => esc_html__('ALL', 'cleenday-core'),
		    ]
	    );

	    $this->add_control(
		    'add_number_cats',
		    [
			    'label' => esc_html__('Show Number of Categories', 'cleenday-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'condition' => ['show_filter' => 'yes'],
		    ]
	    );

	    $this->add_control(
		    'add_max_width_filter',
		    [
			    'label' => esc_html__('Limit the Filter Container Width', 'cleenday-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'condition' => ['show_filter' => 'yes'],
			    'default' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'max_width_filter',
		    [
			    'label' => esc_html__('Filter Container Max Width (px)', 'cleenday-core'),
			    'type' => Controls_Manager::NUMBER,
			    'condition' => [
				    'show_filter' => 'yes',
				    'add_max_width_filter' => 'yes',
			    ],
			    'default' => '1170',
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .wgl-filter_swiper_wrapper' => 'max-width: {{VALUE}}px; overflow: hidden;',
			    ],
		    ]
	    );

	    $this->add_control(
		    'filter_align',
		    [
			    'label' => esc_html__('Filter Align', 'cleenday-core'),
			    'type' => Controls_Manager::SELECT,
			    'condition' => [
				    'portfolio_layout!' => 'carousel',
				    'show_filter' => 'yes',
			    ],
			    'options' => [
				    'left' => esc_html__('Left', 'cleenday-core'),
				    'center' => esc_html__('Сenter', 'cleenday-core'),
				    'right' => esc_html__('Right', 'cleenday-core'),
			    ],
			    'default' => 'center',
		    ]
	    );

	    $this->end_controls_section();

        /**
         * CONTENT -> APPEARANCE
         */

        $this->start_controls_section(
            'display_section',
            ['label' => esc_html__('Appearance', 'cleenday-core')]
        );

        $this->add_control(
            'gallery_mode',
            [
                'label' => esc_html__('Gallery Mode', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'after',
                'label_on' => esc_html__('On', 'cleenday-core'),
                'label_off' => esc_html__('Off', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'show_portfolio_title',
            [
                'label' => esc_html__('Show Heading?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta_categories',
            [
                'label' => esc_html__('Show Categories?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_content',
            [
                'label' => esc_html__('Show Excerpt/Content?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode' => ''],
                'label_on' => esc_html__('On', 'cleenday-core'),
                'label_off' => esc_html__('Off', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'content_letter_count',
            [
                'label' => esc_html__('Content Limit (symbols)', 'cleenday-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'show_content' => 'yes',
                    'gallery_mode' => '',
                ],
                'min' => 1,
                'default' => '85',
            ]
        );

        $this->add_control(
            'info_position',
            [
                'label' => esc_html__('Meta Position', 'cleenday-core'),
                'condition' => ['gallery_mode' => ''],
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    'inside_image' => esc_html__('within image', 'cleenday-core'),
                    'under_image' => esc_html__('beneath image', 'cleenday-core'),
                ],
                'default' => 'inside_image',
            ]
        );

        $this->add_control(
            'info_anim',
            [
                'label' => esc_html__('Meta Animation', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'info_position' => 'inside_image',
                    'gallery_mode' => ''
                ],
                'options' => [
                    'simple' => esc_html__('Simple', 'cleenday-core'),
                    'sub_layer' => esc_html__('On Sub-Layer', 'cleenday-core'),
                    'offset' => esc_html__('Side Offset', 'cleenday-core'),
                    'zoom_in' => esc_html__('Zoom In', 'cleenday-core'),
                    'outline' => esc_html__('Outline', 'cleenday-core'),
                ],
                'default' => 'offset',
            ]
        );

	    $this->add_control(
		    'info_visibility',
		    [
			    'label' => esc_html__('Meta Visibility', 'cleenday-core'),
			    'type' => Controls_Manager::SELECT,
			    'condition' => [
				    'info_position' => 'inside_image',
				    'info_anim!' => ['offset', 'outline'],
				    'gallery_mode' => ''
			    ],
			    'options' => [
				    'on_hover' => esc_html__('Visible On Hover', 'cleenday-core'),
				    'until_hover' => esc_html__('Visible Until Hover', 'cleenday-core'),
				    'always_visible' => esc_html__('Always Visible', 'cleenday-core'),
			    ],
			    'default' => 'on_hover',
		    ]
	    );

	    $this->add_control(
		    'info_visibility_hybrid',
		    [
			    'label' => esc_html__('Meta Visibility', 'cleenday-core'),
			    'type' => Controls_Manager::SELECT,
			    'condition' => [
				    'info_position' => 'inside_image',
				    'info_anim' => ['offset', 'outline'],
				    'gallery_mode' => ''
			    ],
			    'options' => [
				    'on_hover' => esc_html__('Visible On Hover', 'cleenday-core'),
				    'until_hover' => esc_html__('Visible Until Hover', 'cleenday-core'),
				    'always_visible' => esc_html__('Always Visible', 'cleenday-core'),
				    'hybrid' => esc_html__('Hybrid Visible', 'cleenday-core'),
			    ],
			    'default' => 'on_hover',
		    ]
	    );

        $this->add_control(
            'meta_alignment',
            [
                'label' => esc_html__('Meta Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['gallery_mode' => ''],
                'label_block' => true,
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
                    'justify' => [
                        'title' => esc_html__('Justified', 'cleenday-core'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .portfolio__description' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> LINKS
         */

        $this->start_controls_section(
            'section_content_links',
            [
                'label' => esc_html__('Links', 'cleenday-core'),
                'condition' => ['gallery_mode' => ''],
            ]
        );

        $this->add_control(
            'linked_image',
            [
                'label' => esc_html__('Add link on Image', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'linked_title',
            [
                'label' => esc_html__('Add link on Heading', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['show_portfolio_title!' => ''],
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'link_destination',
            [
                'label' => esc_html__('Link Action', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'linked_title',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'linked_image',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'options' => [
                    'single' => esc_html__('Open Single Page', 'cleenday-core'),
                    'custom' => esc_html__('Open Custom Link', 'cleenday-core'),
                    'popup' => esc_html__('Popup the Image', 'cleenday-core'),
                ],
                'default' => 'single',
            ]
        );

        $this->add_control(
            'link_custom_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => ['link_destination' => 'custom'],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Specify the link in metabox section of each corresponding post.', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'link_target',
            [
                'label' => esc_html__('Open link in a new tab', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'linked_title',
                                    'operator' => '!==',
                                    'value' => '',
                                ],
                                [
                                    'name' => 'linked_image',
                                    'operator' => '!==',
                                    'value' => '',
                                ],
                            ],
                        ],
                        [
                            'name' => 'link_destination',
                            'operator' => '!==',
                            'value' => 'popup',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();


	    /*-----------------------------------------------------------------------------------*/
	    /*  CONTENT -> CAROUSEL OPTIONS
		/*-----------------------------------------------------------------------------------*/

	    Wgl_Carousel_Settings::options(
		    $this,
		    [
			    'default_use_carousel' => 'yes',
			    'condition' => [ 'portfolio_layout' => 'carousel' ],
		    ]
	    );


        /**
         * SETTINGS -> QUERY
         */

        Wgl_Loop_Settings::init(
            $this,
            [
                'post_type' => 'portfolio',
                'hide_cats' => true,
                'hide_tags' => true
            ]
        );

        /**
         * STYLE -> GENERAL
         */

        $this->start_controls_section(
            'media_style_section',
            [
                'label' => esc_html__('General', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'items_padding',
            [
                'label' => esc_html__('General Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['gallery_mode' => ''],
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '28',
                    'right' => '35',
                    'bottom' => '28',
                    'left' => '35',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .wgl-portfolio-item_description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'items_border_radius',
		    [
			    'label' => esc_html__('Border Radius', 'cleenday-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0, 'max' => 50],
			    ],
			    'default' => ['size' => 12],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio' => '--portfolio-border-radius: {{SIZE}}px;',
			    ],
		    ]
	    );

	    $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'description',
                'types' => ['classic', 'gradient'],
                'condition' => [
                    'info_position' => 'under_image',
                    'gallery_mode' => ''
                ],
                'selector' => '{{WRAPPER}} .wgl-portfolio .wgl-portfolio-item_description',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color' => ['default' => 'rgba(' . \Cleenday_Theme_Helper::HexToRGB(Cleenday_Globals::get_h_font_color()) . ', 0.7)'],
                ],
            ]
        );

        $this->add_control(
            'overlay_heading',
            [
                'label' => esc_html__('Item Overlay', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'info_position',
                            'operator' => '===',
                            'value' => 'inside_image',
                        ],
                        [
                            'name' => 'gallery_mode',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'custom_image_mask_color',
                'types' => ['classic', 'gradient'],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'terms' => [
                                [
                                    'name' => 'info_position',
                                    'operator' => '===',
                                    'value' => 'inside_image',
                                ],
                                [
                                    'name' => 'info_anim',
                                    'operator' => '!==',
                                    'value' => 'sub_layer',
                                ],
                            ],
                        ],
                        [
                            'name' => 'gallery_mode',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .wgl-portfolio .overlay',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'custom_desc_mask_color',
                'types' => ['classic', 'gradient'],
                'condition' => [
                    'info_position' => 'inside_image',
                    'info_anim' => 'sub_layer',
                    'gallery_mode' => ''
                ],
                'selector' => '{{WRAPPER}} .wgl-portfolio .wgl-portfolio-item_description',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color' => ['default' => 'rgba(34,35,40,0.45)'],
                ],
            ]
        );

        $this->add_control(
            'sec_overlay_color',
            [
                'label' => esc_html__('Additional Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'info_position' => 'inside_image',
                    'info_anim' => ['outline'],
                ],
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .inside_image .overlay:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sec_overlay_bg',
            [
                'label' => esc_html__('Offset Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'info_position' => 'inside_image',
                    'info_anim' => ['offset'],
                ],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .inside_image.offset_animation:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'sec_overlay_svg_color',
            [
                'label' => esc_html__('Offset SVG Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'info_position' => 'inside_image',
                    'info_anim' => ['offset'],
                ],
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_primary_color(),
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> FILTER
         */

        $this->start_controls_section(
            'section_style_filter',
            [
                'label' => esc_html__('Filter', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_filter' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'filter_cats_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
	            'default' => [
		            'top' => '10',
		            'right' => '17',
		            'bottom' => '6',
		            'left' => '17',
		            'unit' => 'px',
		            'isLinked' => false
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_cats_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '50',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'filter_cats_gap',
		    [
			    'label' => esc_html__('Items Gap', 'cleenday-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0, 'max' => 200],
			    ],
			    'default' => ['size' => 10],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .isotope-filter a:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_filter_cats',
                'selector' => '{{WRAPPER}} .wgl-portfolio .isotope-filter a',
            ]
        );

        $this->start_controls_tabs('filter_cats_style_tabs');

        $this->start_controls_tab(
            'filter_cats_style_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'filter_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_bg_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
	            'default' => '#f7f5f7',
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'filter_number_color_idle',
		    [
			    'label' => esc_html__('Number Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['add_number_cats' => 'yes'],
			    'default' => '#ffffff',
			    'separator' => 'before',
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .isotope-filter .number_filter' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'filter_number_bg_idle',
		    [
			    'label' => esc_html__('Number Background Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['add_number_cats' => 'yes'],
			    'default' => Cleenday_Globals::get_primary_color(),
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .isotope-filter .number_filter' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_cats_style_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'filter_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
	            'default' => Cleenday_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'filter_number_color_hover',
		    [
			    'label' => esc_html__('Number Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['add_number_cats' => 'yes'],
			    'separator' => 'before',
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .isotope-filter .number_filter' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'filter_number_bg_hover',
		    [
			    'label' => esc_html__('Number Background Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['add_number_cats' => 'yes'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .isotope-filter .number_filter' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_cats_style_active',
            ['label' => esc_html__('Active', 'cleenday-core')]
        );

        $this->add_control(
            'filter_color_active',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_bg_active',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
	            'default' => Cleenday_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'filter_number_color_active',
		    [
			    'label' => esc_html__('Number Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['add_number_cats' => 'yes'],
			    'separator' => 'before',
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .isotope-filter .number_filter' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'filter_number_bg_active',
		    [
			    'label' => esc_html__('Number Background Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['add_number_cats' => 'yes'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .isotope-filter .number_filter' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
        $this->end_controls_tabs();

	    $this->add_control(
		    'filter_border_idle',
		    [
			    'label' => esc_html__('Border Color', 'cleenday-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'separator' => 'before',
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .isotope-filter a:before' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
            'filter_cats_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
	            'default' => [
		            'top' => '6',
		            'right' => '6',
		            'bottom' => '6',
		            'left' => '6',
		            'unit' => 'px',
		            'isLinked' => false
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wgl-portfolio .isotope-filter a:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_cats_shadow',
                'selector' => '{{WRAPPER}} .wgl-portfolio .isotope-filter a',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> HEADINGS
         */

        $this->start_controls_section(
            'section_style_headings',
            [
                'label' => esc_html__('Headings', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_portfolio_title!' => '',
                    'gallery_mode' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'headings_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .portfolio-item__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_portfolio_headings',
                'selector' => '{{WRAPPER}} .wgl-portfolio .portfolio-item__title .title',
            ]
        );

        $this->start_controls_tabs('headings_color');

        $this->start_controls_tab(
            'custom_headings_color_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'custom_headings_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .portfolio-item__title .title:before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-portfolio .portfolio-item__title .title,
                     {{WRAPPER}} .wgl-portfolio .inside_image .portfolio-item__title .title a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_headings_color_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'custom_hover_headings_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .portfolio-item__title .title:hover,
                     {{WRAPPER}} .wgl-portfolio .portfolio-item__title .title:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> CATEGORIES
         */

        $this->start_controls_section(
            'cats_style_section',
            [
                'label' => esc_html__('Categories', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_meta_categories!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_portfolio_cats',
                'selector' => '{{WRAPPER}} .wgl-portfolio .post_cats',
            ]
        );

	    $this->add_responsive_control(
		    'portfolio_cats_padding',
		    [
			    'label' => esc_html__('Padding', 'cleenday-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-portfolio .portfolio__item-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->start_controls_tabs('cats_color_tabs');

        $this->start_controls_tab(
            'custom_cats_color_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'cat_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .portfolio-category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cat_color_bg',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .portfolio-category' => 'background-color: {{VALUE}}; padding: 8px 12px 5px;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_cats_color_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'cat_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
	            'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .portfolio-category:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cat_color_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .portfolio-category:hover' => 'background-color: {{VALUE}};',
	                '{{WRAPPER}} .wgl-portfolio .portfolio-category' => 'padding: 8px 12px 5px;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> EXCERPT/CONTENT
         */

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Excerpt/Content', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_content!' => ''],
            ]
        );

        $this->add_control(
            'custom_content_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .wgl-portfolio-item_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> LOAD MORE
         */

        $this->start_controls_section(
            'load_more_style_section',
            [
                'label' => esc_html__('Load More', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['navigation' => 'load_more'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_load_more',
                'selector' => '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item',
            ]
        );

        $this->add_control(
            'align_load_more',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'cleenday-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'cleenday-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'cleenday-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .load_more_wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '37',
                    'left' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .load_more_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_load_more',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'load_more_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'load_more_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg_idle',
            [
                'label' => esc_html__('Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'load_more_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'load_more_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg_hover',
            [
                'label' => esc_html__('Background', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_border',
                'label' => esc_html__('Border Type', 'cleenday-core'),
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item',
            ]
        );

        $this->add_control(
            'load_more_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '5',
                    'right' => '5',
                    'bottom' => '5',
                    'left' => '5',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_shadow',
                'selector' => '{{WRAPPER}} .wgl-portfolio .load_more_wrapper .load_more_item',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
	    $_s = $this->get_settings_for_display();

        echo (new WglPortfolio())->render($_s, $this);
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
