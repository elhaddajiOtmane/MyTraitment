<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-gallery.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Background,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography
};
use WglAddons\{
    Cleenday_Global_Variables as Cleenday_Globals,
    Includes\Wgl_Carousel_Settings,
    Includes\Wgl_Elementor_Helper
};

class Wgl_Gallery extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-gallery';
    }

    public function get_title()
    {
        return esc_html__('WGL Gallery', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-gallery';
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
            'jquery-justifiedGallery',
            'wgl-elementor-extensions-widgets',
        ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  GENERAL -> Gallery Settings
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_gallery_section',
            ['label' => esc_html__('Gallery Settings', 'cleenday-core')]
        );

        $this->add_control(
            'gallery',
            [
                'type' => Controls_Manager::GALLERY,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'gallery_layout',
            [
                'label' => esc_html__('Gallery Layout', 'cleenday-core'),
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
                    'justified' => [
                        'title' => esc_html__('Justified', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_justified.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'cleenday-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('1', 'cleenday-core'),
                    '2' => esc_html__('2', 'cleenday-core'),
                    '3' => esc_html__('3', 'cleenday-core'),
                    '4' => esc_html__('4', 'cleenday-core'),
                    '5' => esc_html__('5', 'cleenday-core'),
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'render_type' => 'template',
                'prefix_class' => 'col%s-',
                'condition' => [
                    'gallery_layout!' => 'justified'
                ],
            ]
        );

        $this->add_responsive_control(
            'justified_height',
            [
                'label' => esc_html__('Row Height', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['gallery_layout' => 'justified'],
                'range' => [
                    'px' => ['min' => 20, 'max' => 600],
                ],
                'default' => ['size' => 200],
                'tablet_default' => ['size' => 150],
                'mobile_default' => ['size' => 100],
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__('Gap', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'tablet_default' => [
                    'size' => 10,
                ],
                'mobile_default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_items:not(.gallery-justified) .wgl-gallery_item-wrapper' => 'padding: calc({{SIZE}}px / 2);',
                    '{{WRAPPER}} .wgl-gallery_items:not(.gallery-justified)' => 'margin: calc(-{{SIZE}}px / 2);',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Size', 'cleenday-core'),
                'options' => [
                    '150' => 'Thumbnail - 150x150',
                    '300' => 'Medium - 300x300',
                    '768' => 'Medium Large - 768x768',
                    '1024' => 'Large - 1024x1024',
                    'full' => 'Full',
                    'custom' => 'Custom',
                ],
                'default' => 'full',
                'condition' => [
                    'gallery_layout' => ['grid', 'carousel']
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'cleenday-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => [
                    'img_size_string' => 'custom',
                    'gallery_layout' => ['grid', 'carousel']
                ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'gallery_layout' => ['grid', 'carousel']
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
                'default' => '1:1',
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => esc_html__('Link', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'cleenday-core'),
                    'file' => esc_html__('Media File', 'cleenday-core'),
                    'custom' => esc_html__('Custom URL', 'cleenday-core'),
                ],
                'default' => 'file',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'link_custom_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => [
                    'link_to' => 'custom',
                ],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Specify the link in the attachment details of each corresponding image.', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'link_target',
            [
                'label' => esc_html__('Open in New Tab', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'link_to' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'file_popup',
            [
                'label' => esc_html__('Open in Popup', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'link_to' => 'file',
                ],
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => esc_html__('Order By', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'cleenday-core'),
                    'random' => esc_html__('Random', 'cleenday-core'),
                    'asc' => esc_html__('ASC', 'cleenday-core'),
                    'desc' => esc_html__('DESC', 'cleenday-core'),
                ],
                'separator' => 'before',
                'default' => '',
            ]
        );

        $this->add_control(
            'add_animation',
            [
                'label' => esc_html__('Add Appear Animation', 'cleenday-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => [
                    'gallery_layout!' => 'carousel'
                ],
            ]
        );

        $this->add_control(
            'appear_animation',
            [
                'label' => esc_html__('Animation Style', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'add_animation' => 'yes',
                    'gallery_layout!' => 'carousel'
                ],
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


	    /*-----------------------------------------------------------------------------------*/
	    /*  CONTENT -> CAROUSEL OPTIONS
		/*-----------------------------------------------------------------------------------*/

	    Wgl_Carousel_Settings::options(
		    $this,
		    [
			    'default_use_carousel' => 'yes',
			    'hide_opt_responsive' => 'yes',
			    'condition' => [ 'gallery_layout' => 'carousel' ],
		    ]
	    );


	    /*-----------------------------------------------------------------------------------*/
	    /*  GENERAL -> IMAGE ATTACHMENT
	    /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_content_section',
            ['label' => esc_html__('Image Attachment', 'cleenday-core')]
        );

        $this->add_control(
            'info_animation',
            [
                'label' => esc_html__('Animation', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'cleenday-core'),
                    'until_hover' => esc_html__('Visible Until Hover', 'cleenday-core'),
                    'always' => esc_html__('Always Visible', 'cleenday-core'),
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'image_title',
            [
                'label' => esc_html__('Title', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'cleenday-core'),
                    'alt' => esc_html__('Alt', 'cleenday-core'),
                    'title' => esc_html__('Title', 'cleenday-core'),
                    'caption' => esc_html__('Caption', 'cleenday-core'),
                    'description' => esc_html__('Description', 'cleenday-core'),
                ],
            ]
        );

        $this->add_control(
            'image_descr',
            [
                'label' => esc_html__('Description', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'cleenday-core'),
                    'alt' => esc_html__('Alt', 'cleenday-core'),
                    'title' => esc_html__('Title', 'cleenday-core'),
                    'caption' => esc_html__('Caption', 'cleenday-core'),
                    'description' => esc_html__('Description', 'cleenday-core'),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> IMAGE
         */

        $this->start_controls_section(
            'image_styles_section',
            [
                'label' => esc_html__('Image', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_bg_color');

        $this->start_controls_tab(
            'tab_bg_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'bg_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bg_border',
                'selector' => '{{WRAPPER}} .wgl-gallery_item',
                'separator' => 'before',
                'condition' => [
                    'gallery_layout!' => 'justified'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bg_shadow',
                'selector' => '{{WRAPPER}} .wgl-gallery_item',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item:before',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_bg_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'bg_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bg_border_hover',
                'selector' => '{{WRAPPER}} .wgl-gallery_item:hover',
                'separator' => 'before',
                'condition' => [
                    'gallery_layout!' => 'justified'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bg_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-gallery_item:hover',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item:after',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> INFO
         */

        $this->start_controls_section(
            'info_styles_section',
            [
                'label' => esc_html__('Info', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'info_alignment',
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
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'info_vertical',
            [
                'label' => esc_html__('Vertical Position', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'cleenday-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'cleenday-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'cleenday-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'default' => 'middle',
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_padding',
            [
                'label' => esc_html__('Info Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Title Styles
        $this->add_control(
            'divider_1_1',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_control(
            'divider_1',
            [
                'label' => esc_html__('Title Styles', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'divider_1_2',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .wgl-gallery_image-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_title_color');

        $this->start_controls_tab(
            'tab_title_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'title_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-gallery_image-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'divider_2_1',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_control(
            'divider_2',
            [
                'label' => esc_html__('Description Styles', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'divider_2_2',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'descr_typo',
                'selector' => '{{WRAPPER}} .wgl-gallery_image-descr',
            ]
        );

        $this->add_responsive_control(
            'descr_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-descr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_descr_color');

        $this->start_controls_tab(
            'tab_descr_idle',
            ['label' => esc_html__('Idle', 'cleenday-core')]
        );

        $this->add_control(
            'descr_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-descr' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_descr_hover',
            ['label' => esc_html__('Hover', 'cleenday-core')]
        );

        $this->add_control(
            'descr_hover',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-gallery_image-descr' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $gallery = $_s['gallery'] ?? '';
        $img_size_string = $_s['img_size_string'] ?? '';
        $img_size_array = $_s['img_size_array'] ?? [];
        $img_aspect_ratio = $_s['img_aspect_ratio'] ?? '';
        $open_in_popup = $_s['file_popup'] ? 'yes' : 'no';
        $item_tag = $_s['link_to'] == 'none' ? 'div' : 'a';

        switch ($_s['gallery_layout']) {
            case 'masonry':
                $layout_class = 'gallery-masonry';
                break;
            case 'justified':
                $layout_class = 'gallery-justified';
                $this->add_render_attribute('gallery_items', [
                    'data-height' => $_s['justified_height']['size'],
                    'data-tablet-height' => $_s['justified_height_tablet']['size'],
                    'data-mobile-height' => $_s['justified_height_mobile']['size'],
                    'data-gap' => $_s['gap']['size'],
                    'data-tablet-gap' => $_s['gap_tablet']['size'],
                    'data-mobile-gap' => $_s['gap_mobile']['size'],
                ]);
                break;
            case 'carousel':
                $layout_class = 'gallery-carousel';
                break;
            default:
                $layout_class = '';
                break;
        }

        // Gallery order
        if ($_s['order_by'] == 'random') {
            shuffle($gallery);
        } else if ($_s['order_by'] == 'desc') {
            krsort($gallery);
        }
	
	    $this->add_render_attribute('gallery', [
		    'class' => [
			    'wgl-gallery',
		    ],
	    ]);

        $this->add_render_attribute('gallery_items', [
            'class' => [
                'wgl-gallery_items',
                $layout_class,
            ],
        ]);
        
	    $this->add_render_attribute('gallery_item_wrap', [
		    'class' => [
			    'wgl-gallery_item-wrapper',
		    ],
	    ]);

        $this->add_render_attribute('gallery_image_info', [
            'class' => [
                'wgl-gallery_image-info',
                !empty($_s['info_animation']) ? 'show_' . $_s['info_animation'] : '',
            ],
        ]);

        // Appear Animation
        if ($_s['gallery_layout'] != 'carousel' && $_s['add_animation']) {
            $this->add_render_attribute('gallery_items', [
                'class' => [
                    'appear-animation',
                    $_s['appear_animation'],
                ],
            ]);
        }

        ob_start();
        foreach ($gallery as $index => $item) {
            $id = $item['id'];
            $attachment = get_post($id);
            $image_data = wp_get_attachment_image_src($id, 'full');

            // Image size
            $dim = null;
            
            if($image_data){
                $dim = Wgl_Elementor_Helper::get_image_dimensions(
                    $img_size_array ?: $img_size_string,
                    $img_aspect_ratio,
                    $image_data
                );
            }

            if (is_null($dim)) {
                return;
            }

            $image_url = aq_resize($image_data[0], $dim['width'], $dim['height'], true, true, true) ?: $image_data[0];

            // Image Attachment
            $image_arr = [
	            'image' => $image_data[0],
                'src' => $image_url,
                'alt' => get_post_meta($id, '_wp_attachment_image_alt', true),
                'title' => $attachment->post_title,
                'caption' => $attachment->post_excerpt,
                'description' => $attachment->post_content
            ];

            $this->add_render_attribute('gallery_item_' . $index, 'class', 'wgl-gallery_item');

            // Link
            switch ($_s['link_to']) {
                case 'file':
                    $this->add_lightbox_data_attributes('gallery_item_' . $index, $id, $open_in_popup, 'all-' . $this->get_id());
                    $this->add_render_attribute('gallery_item_' . $index, [
                        'href' => $image_arr['image'],
                    ]);
                    break;
                case 'custom':
                    $custom_link = get_post_meta($id, 'custom_image_link', true);
                    if (!empty($custom_link)) {
                        $this->add_render_attribute('gallery_item_' . $index, [
                            'href' => $custom_link,
                            'target' => $_s['link_target'] ? '_blank' : '_self',
                        ]);
                        $item_tag = 'a';
                    } else {
                        $item_tag = 'div';
                    }
                    break;
            }

            $this->add_render_attribute(
                'gallery_image' . $index,
                [
                    'class' => 'wgl-gallery_image',
                    'src' => $image_arr['src'],
                    'alt' => $image_arr['alt']
                ]
            );

            echo '<div ', $this->get_render_attribute_string('gallery_item_wrap'), '>';
            echo '<div class="wgl-gallery_item-inner">';
            echo '<', $item_tag, ' ', $this->get_render_attribute_string('gallery_item_' . $index), '>';
            echo '<img ', $this->get_render_attribute_string('gallery_image' . $index), ' />'; // gallery image
            echo !empty($this->attachment_info($_s, $image_arr))
                ? '<div ' . $this->get_render_attribute_string('gallery_image_info') . '>' . $this->attachment_info($_s, $image_arr) . '</div>'
                : ''; // attachment info
            echo '</', $item_tag, '>'; // gallery item
            echo '</div>';
            echo '</div>'; // gallery item wrapper
        }
        $gallery_items = ob_get_clean();
	
	    echo '<div ', $this->get_render_attribute_string('gallery'), '>';
        echo '<div ', $this->get_render_attribute_string('gallery_items'), '>';

        switch ($_s['gallery_layout']) {
            case 'carousel':
	            $_s['items_per_line'] = $_s['columns'];
	            $_s['custom_resp'] = true;
	            $_s['resp_medium'] = '';
	            $_s['resp_medium_slides'] = '';
	            $_s['resp_tablets'] = '1025';
	            $_s['resp_tablets_slides'] = $_s['columns_tablet'];
	            $_s['resp_mobile'] = '767';
	            $_s['resp_mobile_slides'] = $_s['columns_mobile'];
	
                echo Wgl_Carousel_Settings::init($_s, $gallery_items, false);
                break;
            default:
                echo \Cleenday_Theme_Helper::render_html($gallery_items);
                break;
        }

        echo '</div>'; // gallery items
        echo '</div>'; // gallery module wrapper
    }

    private function attachment_info($_s, $image_arr)
    {
        ob_start();
        if ($_s['image_title'] && !empty($image_arr[$_s['image_title']])) {
            echo '<div class="wgl-gallery_image-title">', $image_arr[$_s['image_title']], '</div>';
        }
        if ($_s['image_descr'] && !empty($image_arr[$_s['image_descr']])) {
            echo '<div class="wgl-gallery_image-descr">', $image_arr[$_s['image_descr']], '</div>';
        }

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
