<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-clients.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager, Control_Media};
use Elementor\{Group_Control_Border, Group_Control_Box_Shadow, Group_Control_Background};
use Elementor\{Repeater, Utils};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use WglAddons\Includes\{Wgl_Carousel_Settings, Wgl_Elementor_Helper};

class Wgl_Clients extends Widget_Base
{
    public function get_name() {
        return 'wgl-clients';
    }

    public function get_title() {
        return esc_html__('WGL Clients', 'cleenday-core');
    }

    public function get_icon() {
        return 'wgl-clients';
    }

    public function get_script_depends() {
        return [ 'slick' ];
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
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
            'item_grid',
            [
                'label' => esc_html__('Grid Columns Amount', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('1 / One', 'cleenday-core'),
                    '2' => esc_html__('2 / Two', 'cleenday-core'),
                    '3' => esc_html__('3 / Three', 'cleenday-core'),
                    '4' => esc_html__('4 / Four', 'cleenday-core'),
                    '5' => esc_html__('5 / Five', 'cleenday-core'),
                    '6' => esc_html__('6 / Six', 'cleenday-core'),
                ],
                'default' => '1',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Thumbnail', 'cleenday-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            ]
        );

        $repeater->add_control(
            'hover_thumbnail',
            [
                'label' => esc_html__('Hover Thumbnail', 'cleenday-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'description' => esc_html__('For \'Toggle Image\' animations only.', 'cleenday-core' ),
                'default' => [ 'url' => '' ],
            ]
        );

        $repeater->add_responsive_control(
            'images_width',
            [
                'label' => esc_html__('Image/Images Width', 'cleenday-core'),
	            'type' => Controls_Manager::SLIDER,
	            'range' => [
		            'px' => ['min' => 10, 'max' => 500 ],
		            '%' => ['min' => 10, 'max' => 100 ],
	            ],
	            'size_units' => ['px', '%'],
	            'selectors' => [
		            '{{WRAPPER}} {{CURRENT_ITEM}}.clients_image img:not(.lazyload),
		             {{WRAPPER}} {{CURRENT_ITEM}}.clients_image img.lazyloaded' => 'width: {{SIZE}}{{UNIT}};',
	            ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'client_link',
            [
                'label' => esc_html__('Add Link', 'cleenday-core'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Items', 'cleenday-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'item_anim',
            [
                'label' => esc_html__('Thumbnail Animation', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    'none' => esc_html__('None', 'cleenday-core'),
                    'ex_images' => esc_html__('Toggle Image - Fade', 'cleenday-core'),
                    'ex_images_ver' => esc_html__('Toggle Image - Vertical', 'cleenday-core'),
                    'grayscale' => esc_html__('Grayscale', 'cleenday-core'),
                    'opacity' => esc_html__('Opacity', 'cleenday-core'),
                    'zoom' => esc_html__('Zoom', 'cleenday-core'),
                    'contrast' => esc_html__('Contrast', 'cleenday-core'),
                    'blur-1' => esc_html__('Blur 1', 'cleenday-core'),
                    'blur-2' => esc_html__('Blur 2', 'cleenday-core'),
                    'invert' => esc_html__('Invert', 'cleenday-core'),
                ],
                'default' => 'ex_images',
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => esc_html__('Custom Items Height', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [ 'item_anim' => 'ex_images_bg' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 300 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment_h',
            [
                'label' => esc_html__('Horizontal Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => true,
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
                    '{{WRAPPER}} .clients_image' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'alignment_v',
            [
                'label' => esc_html__('Vertical Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => true,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'cleenday-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'cleenday-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'cleenday-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-clients' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .slick-track' => 'align-items: {{VALUE}}; display: flex;',
                ],
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CAROUSEL OPTIONS
        /*-----------------------------------------------------------------------------------*/

        Wgl_Carousel_Settings::options($this);


        /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> ITEMS
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_items',
            [
                'label' => esc_html__('Items', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_items',
            [ 'separator' => 'before' ]
        );

        $this->start_controls_tab(
            'tab_item_idle',
            [ 'label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_idle',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .clients_image',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_idle',
                'selector' => '{{WRAPPER}} .clients_image',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_hover',
            [ 'label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_hover',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .clients_image:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_hover',
                'selector' => '{{WRAPPER}} .clients_image:hover',
            ]
        );

        $this->add_control(
            'item_transition',
            [
                'label' => esc_html__('Transition Duration', 'cleenday-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range' => [
                    's' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ],
                ],
                'default' => [ 'size' => 0.4, 'unit' => 's' ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> IMAGES
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_images',
            [
                'label' => esc_html__('Images', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_images' );

        $this->start_controls_tab(
            'tab_image_idle',
            [ 'label' => esc_html__('Idle', 'cleenday-core') ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_idle',
                'selector' => '{{WRAPPER}} .image_wrapper > img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_idle',
                'selector' => '{{WRAPPER}} .image_wrapper > img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_image_hover',
            [ 'label' => esc_html__('Hover', 'cleenday-core') ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_hover',
                'selector' => '{{WRAPPER}} .image_wrapper:hover > img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_hover',
                'selector' => '{{WRAPPER}} .image_wrapper:hover > img',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

    }

    protected function render()
    {
        $content = '';
        $_s = $this->get_settings_for_display();

        if ($_s['use_carousel']) {
	        $_s['items_per_line'] = $_s['item_grid'];

            wp_enqueue_script(
                'slick',
                get_template_directory_uri() . '/js/slick.min.js',
                [],
                false,
                false
            );

            /* ↓ Fix box-shadow issue with Elementor capabilities only ↓ */
            $styles = '';
            if (isset($_margin['left'])) {
                $styles .= '.elementor-element-' . $this->get_id() .' .slick-slider .slick-list { '
                    . 'margin-left: ' . $_margin['left'] . $_margin['unit'] . ';'
                    . 'margin-right: ' . $_margin['right'] . $_margin['unit'] . ';'
                . ' } ';
            }
            if (isset($_padding['left'])) {
                $styles .= '.elementor-element-' . $this->get_id() .' .slick-slider .slick-list { '
                    . 'padding-left: ' . $_padding['left'] . $_padding['unit'] . ';'
                    . 'padding-right: ' . $_padding['right'] . $_padding['unit'] . ';'
                . ' } ';
            }
            if ($styles) Wgl_Elementor_Helper::enqueue_css($styles);
            /* ↑ fix box-shadow issue ↑ */
        }

        $this->add_render_attribute(
            'clients',
            [
                'class' => [
                    'wgl-clients',
                    'clearfix',
                    'anim-' . $_s['item_anim'],
                    'items-' . $_s['item_grid'],
                ],
                'data-carousel' => $_s['use_carousel']
            ]
        );

        foreach ($_s['list'] as $index => $item) {

            if (!empty($item['client_link']['url'])) {
                $client_link = $this->get_repeater_setting_key('client_link', 'list', $index);
                $this->add_render_attribute($client_link, 'class', 'image_link image_wrapper');
                $this->add_link_attributes($client_link, $item['client_link']);
            }

            $client_image = $this->get_repeater_setting_key('thumbnail', 'list', $index);
            $url_idle = $item['thumbnail']['url'] ?? false;
            $this->add_render_attribute($client_image, [
                'class' => 'main_image',
                'alt' => Control_Media::get_image_alt($item['thumbnail']),
            ]);
            if ($url_idle) $this->add_render_attribute($client_image, 'src', esc_url($url_idle));

            $client_hover_image = $this->get_repeater_setting_key('hover_thumbnail', 'list', $index);
            $url_hover = $item['hover_thumbnail']['url'] ?? false;
            $this->add_render_attribute($client_hover_image, [
                'class' => 'hover_image',
                'alt' => Control_Media::get_image_alt($item['hover_thumbnail']),
            ]);
            if ($url_hover) $this->add_render_attribute($client_hover_image, 'src', esc_url($url_hover));

            ob_start();

            echo '<div class="clients_image elementor-repeater-item-'. $item['_id'].'">';
                if (!empty($item['client_link']['url'])) {
                    echo '<a ', $this->get_render_attribute_string($client_link), '>';
                } else {
                    echo '<div class="image_wrapper">';
                }
                    if ($url_hover && ($_s['item_anim'] === 'ex_images' || $_s['item_anim'] === 'ex_images_bg' || $_s['item_anim'] === 'ex_images_ver') ) {
                        echo '<img ', $this->get_render_attribute_string($client_hover_image), ' />';
                    }

                    echo '<img ', $this->get_render_attribute_string($client_image), ' />';

                if (!empty($item['client_link']['url'])) {
                    echo '</a>';
                } else {
                    echo '</div>';
                }
            echo '</div>';

            $content .= ob_get_clean();
        }

        // Render
        echo '<div ', $this->get_render_attribute_string('clients'), '>';
            if ($_s['use_carousel']) {
                echo Wgl_Carousel_Settings::init($_s, $content, false);
            } else {
                echo $content;
            }
        echo '</div>';

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
