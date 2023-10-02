<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-blog.php.
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
	Templates\WGL_Blog as Blog_Template};

class Wgl_Blog extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-blog';
    }

    public function get_title()
    {
        return esc_html__('WGL Blog', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-blog';
    }

    public function get_script_depends()
    {
        return [
            'slick',
            'jarallax',
            'jarallax-video',
            'imagesloaded',
            'isotope',
            'wgl-elementor-extensions-widgets',
        ];
    }

    public function get_categories()
    {
        return ['wgl-extensions'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> LAYOUT
         */

        $this->start_controls_section(
            'section_content_layout',
            ['label' => esc_html__('Layout', 'cleenday-core') ]
        );

        $this->add_control(
            'blog_title',
            [
                'label' => esc_html__('Title', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'blog_subtitle',
            [
                'label' => esc_html__('Subitle', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'separator' => 'after',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'blog_layout',
            [
                'label' => esc_html__('Layout', 'cleenday-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'masonry' => [
                        'title' => esc_html__('Masonry', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'blog_columns',
            [
                'label' => esc_html__('Grid Columns Amount', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    '12' => esc_html__('1 / One', 'cleenday-core'),
                    '6' => esc_html__('2 / Two', 'cleenday-core'),
                    '4' => esc_html__('3 / Three', 'cleenday-core'),
                    '3' => esc_html__('4 / Four', 'cleenday-core')
                ],
                'default' => '12',
                'tablet_default' => 'inherit',
                'mobile_default' => '1',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'label' => esc_html__('Image Size', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'blog_layout' => ['grid', 'carousel']
                ],
                'separator' => 'before',
                'options' => [
                    '150' => 'Thumbnail - 150x150',
                    '300' => 'Medium - 300x300',
                    '768' => 'Medium Large - 768x768',
                    '1024' => 'Large - 1024x1024',
                    '740x620' => '740x440 - 3 Columns',
                    '1140x680' => '1140x680 - 2 Columns',
                    '1170x700' => '1170x700 - 1 Column',
                    'full' => 'Full',
                    'custom' => 'Custom',
                ],
                'default' => '1170x725',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'cleenday-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => [
                    'img_size_string' => 'custom',
                    'blog_layout' => ['grid', 'carousel']
                ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'cleenday-core'),
                'default' => [
                    'width' => '1170',
                    'height' => '750',
                ]
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'blog_layout' => ['grid', 'carousel'],
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
            'navigation_type',
            [
                'label' => esc_html__('Navigation Type', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'blog_layout' => ['grid', 'masonry']
                ],
                'separator' => 'before',
                'options' => [
                    'none' => esc_html__('None', 'cleenday-core'),
                    'pagination' => esc_html__('Pagination', 'cleenday-core'),
                    'load_more' => esc_html__('Load More', 'cleenday-core'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_responsive_control(
            'navigation_align',
            [
                'label' => esc_html__('Navigation\'s Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['navigation_type' => 'pagination'],
                'toggle' => false,
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
                'prefix_class' => 'nav-%s',
            ]
        );

        $this->add_control(
            'navigation_offset',
            [
                'label' => esc_html__('Navigation Margin Top', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'navigation_type' => 'pagination',
                    'blog_layout' => ['grid', 'masonry']
                ],
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 240],
                ],
                'default' => ['size' => 60],
                'selectors' => [
                    '{{WRAPPER}} .wgl-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'items_load',
            [
                'label' => esc_html__('Items to be loaded', 'cleenday-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry']
                ],
                'min' => 1,
                'default' => 4,
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => esc_html__('Button Text', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry']
                ],
                'dynamic' => ['active' => true],
                'default' => esc_html__('LOAD MORE', 'cleenday-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> APPEARANCE
         */

        $this->start_controls_section(
            'section_content_appearance',
            ['label' => esc_html__('Appearance', 'cleenday-core') ]
        );

        $this->add_control(
            'hide_media',
            [
                'label' => esc_html__('Hide Media?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'media_link',
            [
                'label' => esc_html__('Clickable Image?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_media' => ''],
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'hide_blog_title',
            [
                'label' => esc_html__('Hide Title?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_content',
            [
                'label' => esc_html__('Hide Content?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
	            'default' => 'yes',
            ]
        );

        $this->add_control(
            'content_letter_count',
            [
                'label' => esc_html__('Characters Amount in Content', 'cleenday-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['hide_content' => ''],
                'min' => 1,
                'default' => '95',
            ]
        );

        $this->add_control(
            'read_more_hide',
            [
                'label' => esc_html__('Hide \'Read More\' button?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More Text', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['read_more_hide' => ''],
                'dynamic' => ['active' => true],
                'default' => esc_html__('READ MORE', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'hide_all_meta',
            [
                'label' => esc_html__('Hide all post meta?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_author',
            [
                'label' => esc_html__('Hide author?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta' => ''],
            ]
        );

        $this->add_control(
            'meta_comments',
            [
                'label' => esc_html__('Hide comments?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta' => ''],
            ]
        );

        $this->add_control(
            'meta_categories',
            [
                'label' => esc_html__('Hide post-meta categories?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta' => ''],
            ]
        );

        $this->add_control(
            'meta_date',
            [
                'label' => esc_html__('Hide post-meta date?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta' => ''],
            ]
        );

        $this->add_control(
            'hide_views',
            [
                'label' => esc_html__('Hide Views?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'hide_likes',
            [
                'label' => esc_html__('Hide Likes?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
	            'default' => 'yes',
            ]
        );

        $this->add_control(
            'hide_share',
            [
                'label' => esc_html__('Hide Shares?', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
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
                'condition' => [ 'blog_layout' => 'carousel' ],
		    ]
	    );

	    /*-----------------------------------------------------------------------------------*/
	    /*  SETTINGS -> QUERY
		/*-----------------------------------------------------------------------------------*/

        Wgl_Loop_Settings::init(
            $this,
            ['post_type' => 'post']
        );

        /**
         * STYLE -> POST ITEM
         */

        $this->start_controls_section(
            'section_style_post_item',
            [
                'label' => esc_html__('Post Item', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__('Content Section Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .blog-post_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .blog-post_wrapper .blog-post_meta-wrap' => 'padding-left: {{LEFT}}{{UNIT}};'
                                                                           . 'padding-right: {{RIGHT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_item',
                'selector' => '{{WRAPPER}} .blog-post_wrapper',
            ]
        );

        $this->add_control(
            'item_bg',
            [
                'label' => esc_html__('Add Item Background', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg',
                'label' => esc_html__('Background', 'cleenday-core'),
                'condition' => ['item_bg' => 'yes'],
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .blog-post_wrapper',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> MODULE TITLE
         */

        $this->start_controls_section(
            'section_style_module_title',
            [
                'label' => esc_html__('Module Title', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['blog_title!' => ''],
            ]
        );

        $this->add_control(
            'heading_blog_title',
            [
                'label' => esc_html__('Title', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'module_title',
                'selector' => '{{WRAPPER}} .blog_title',
            ]
        );

        $this->add_responsive_control(
            'blog_title_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .blog_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_blog_subtitle',
            [
                'label' => esc_html__('Subtitle', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'module_subtitle',
                'selector' => '{{WRAPPER}} .blog_subtitle',
            ]
        );

        $this->add_responsive_control(
            'blog_subtitle_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .blog_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_blog_headings',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .blog-post_title,
                               {{WRAPPER}} .blog-post_title > a',
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label' => esc_html__('HTML tag', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => '‹h1›',
                    'h2' => '‹h2›',
                    'h3' => '‹h3›',
                    'h4' => '‹h4›',
                    'h5' => '‹h5›',
                    'h6' => '‹h6›',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_headings');

        $this->start_controls_tab(
            'tab_headings_idle',
            ['label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_control(
            'headings_color_idle',
            [
                'label' => esc_html__('Title Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .blog-post_title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_headings_hover',
            ['label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_control(
            'headings_color_hover',
            [
                'label' => esc_html__('Title Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .blog-post_title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['hide_content' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content',
                'selector' => '{{WRAPPER}} .blog-post_text',
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .blog-post_text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> META DATA
         */

        $this->start_controls_section(
            'section_style_meta_data',
            [
                'label' => esc_html__('Meta Data', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'hide_all_meta',
                            'operator' => '==',
                            'value' => ''
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'meta_author',
                                    'operator' => '==',
                                    'value' => ''
                                ],
                                [
                                    'name' => 'meta_comments',
                                    'operator' => '==',
                                    'value' => ''
                                ],
                                [
                                    'name' => 'meta_categories',
                                    'operator' => '==',
                                    'value' => ''
                                ],
                                [
                                    'name' => 'meta_date',
                                    'operator' => '==',
                                    'value' => ''
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_data',
                'selector' => '{{WRAPPER}} .blog-post_meta-wrap',
            ]
        );

        $this->add_responsive_control(
            'meta_data_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_meta-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_data_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .blog-post_meta-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'meta_data',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .blog-post_meta-wrap',
            ]
        );

        $this->start_controls_tabs(
            'tabs_meta_data',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_meta_data_idle',
            ['label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_control(
            'meta_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_meta-wrap' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_meta_hover',
            ['label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_control(
            'meta_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_meta-wrap a:hover,
                     {{WRAPPER}} .share_post-container:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> MEDIA
         */

        $this->start_controls_section(
            'section_style_media',
            [
                'label' => esc_html__('Media', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['hide_media' => ''],
            ]
        );

        $this->add_control(
            'media_overlay_idle',
            [
                'label' => esc_html__('Image Overlay Idle', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .format-standard-image .image-overlay:before' => 'content: \'\'',
                    '{{WRAPPER}} .format-image .image-overlay:before' => 'content: \'\'',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'media_overlay_idle',
                'label' => esc_html__('Background', 'cleenday-core'),
                'condition' => ['media_overlay_idle!' => ''],
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .image-overlay:before',
            ]
        );

        $this->add_control(
            'media_overlay_hover',
            [
                'label' => esc_html__('Image Hover Overlay', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .format-standard-image .image-overlay:after' => 'content: \'\'',
                    '{{WRAPPER}} .format-image .image-overlay:after' => 'content: \'\'',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'media_overlay_hover',
                'label' => esc_html__('Background', 'cleenday-core'),
                'condition' => ['media_overlay_hover!' => ''],
                'types' => ['classic', 'gradient', 'video'],
                'default' => 'rgba(14,21,30,.6)',
                'selector' => '{{WRAPPER}} .image-overlay:after',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> READ MORE
         */

        $this->start_controls_section(
            'section_style_read_more',
            [
                'label' => esc_html__('Read More', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['read_more_hide' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'read_more',
                'selector' => '{{WRAPPER}} .button-read-more',
            ]
        );

        $this->add_responsive_control(
            'read_more_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .read-more-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .read-more-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_read_more',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_read_more_idle',
            ['label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_control(
            'read_more_color_idle',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .button-read-more,
                     {{WRAPPER}} .button-read-more:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_read_more_hover',
            ['label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_control(
            'read_more_color_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .button-read-more:hover,
                     {{WRAPPER}} .button-read-more:hover:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> LOAD MORE
         */

        $this->start_controls_section(
            'section_style_load_more',
            [
                'label' => esc_html__('Load More', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more',
                'selector' => '{{WRAPPER}} .load_more_item',
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
                    '{{WRAPPER}} .load_more_wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '30',
                    'right' => '0',
                    'bottom' => '50',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .load_more_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .load_more_item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg_idle',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .load_more_item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg_hover',
            [
                'label' => esc_html__('Background Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->add_control(
            'load_more_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_shadow',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        (new Blog_Template())->render($this->get_settings_for_display());
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
