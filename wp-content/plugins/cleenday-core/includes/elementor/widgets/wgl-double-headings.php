<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-double-headings.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager, Group_Control_Typography};
use WglAddons\Includes\Wgl_Elementor_Helper;
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;

class Wgl_Double_Headings extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-double-headings';
    }

    public function get_title()
    {
        return esc_html__('WGL Double Heading', 'cleenday-core');
    }

    public function get_icon()
    {
        return 'wgl-double-headings';
    }

	public function get_script_depends()
	{
		return ['jquery-appear'];
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
            'wgl_double_headings_section',
            ['label' => esc_html__('General', 'cleenday-core')]
        );

        $this->add_control(
            'presubtitle',
            [
                'label' => esc_html__('Subtitle Prefix', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('ex: 01', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('ex: About Us', 'cleenday-core'),
                'default' => esc_html__('SUBTITLE', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'dbl_title',
            [
                'label' => esc_html__('Title 1st Part', 'cleenday-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'placeholder' => esc_attr__('1st part', 'cleenday-core'),
                'default' => esc_html_x('Heading', 'WGL Double Heading', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'dbl_title2',
            [
                'label' => esc_html__('Title 2nd Part', 'cleenday-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'placeholder' => esc_attr__('2nd part', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'dbl_title3',
            [
                'label' => esc_html__('Title 3rd Part', 'cleenday-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'placeholder' => esc_attr__('3rd part', 'cleenday-core'),
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
	            'prefix_class' => 'a%s',
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Title Link', 'cleenday-core'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_attr__('https://your-link.com', 'cleenday-core'),
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> TITLE
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
                'name' => 'title_all',
                'selector' => '{{WRAPPER}} .dbl__title',
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
                    'span' => '‹span›',
                    'div' => '‹div›',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'heading_1st_part',
            [
                'label' => esc_html__('1st Part', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['dbl_title!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_1st',
                'condition' => ['dbl_title!' => ''],
                'selector' => '{{WRAPPER}} .dbl-title_1',
            ]
        );

        $this->add_control(
            'title_1st_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['dbl_title!' => ''],
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .dbl-title_1' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_2nd_part',
            [
                'label' => esc_html__('2nd Part', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['dbl_title2!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_2nd',
                'condition' => ['dbl_title2!' => ''],
                'selector' => '{{WRAPPER}} .dbl-title_2',
            ]
        );

        $this->add_control(
            'title_2nd_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['dbl_title2!' => ''],
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .dbl-title_2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_3rd_part',
            [
                'label' => esc_html__('3rd Part', 'cleenday-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['dbl_title3!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_3rd',
                'condition' => ['dbl_title3!' => ''],
                'selector' => '{{WRAPPER}} .dbl-title_3',
            ]
        );

        $this->add_control(
            'title_3rd_color',
            [
                'label' => esc_html__('Text Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['dbl_title3!' => ''],
                'dynamic' => ['active' => true],
                'default' => Cleenday_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .dbl-title_3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> SUBTITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'cleenday-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['subtitle!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typo',
                'selector' => '{{WRAPPER}} .dbl__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Subtitle Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#808080',
                'selectors' => [
                    '{{WRAPPER}} .dbl__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'add_subtitle_line',
		    [
			    'label' => esc_html__('Add Animated Line for Subtitle', 'cleenday-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__('Use', 'cleenday-core'),
			    'label_off' => esc_html__('Hide', 'cleenday-core'),
			    'default' => 'yes',
			    'render_type' => 'template',
			    'selectors' => [
				    '{{WRAPPER}} .dbl__subtitle:before' => 'display: inline-block;',
			    ],
		    ]
	    );

	    $this->add_control(
            'subtitle_line_color',
            [
                'label' => esc_html__('Line Color', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'add_subtitle_line' => 'yes' ],
	            'selectors' => [
		            '{{WRAPPER}} .dbl__subtitle:before' => 'background-color: {{VALUE}};',
	            ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_padding',
            [
                'label' => esc_html__('Padding', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .dbl__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Margin', 'cleenday-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
	                'top' => '0',
	                'right' => '0',
	                'bottom' => '9',
	                'left' => '0',
	                'unit' => 'px',
	                'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dbl__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

	public function wgl_svg_to_data( $wgl_svg ) {
		return str_replace( [ '<', '>', '#' ], [ '%3C', '%3E', '%23' ], $wgl_svg );
	}

	protected function render()
    {
        $_s = $this->get_settings_for_display();

        if (!empty($_s['link']['url'])) {
            $this->add_render_attribute('link', 'class', 'dbl__link');
            $this->add_link_attributes('link', $_s['link']);
        }

	    $this->add_render_attribute('heading_wrapper', 'class', 'wgl-double_heading');

        echo '<div ', $this->get_render_attribute_string('heading_wrapper'), '>';

            if ($_s['subtitle'] || $_s['presubtitle']) {
                echo '<div class="dbl__subtitle">';
                    if ($_s['presubtitle']) echo '<span>', $_s['presubtitle'], '</span>';
                    if ($_s['subtitle']) echo '<span>', $_s['subtitle'], '</span>';
                echo '</div>';
            }

            if ($_s['dbl_title'] || $_s['dbl_title2'] || $_s['dbl_title3']) {

                if (!empty($_s['link']['url'])) echo '<a ', $this->get_render_attribute_string('link'), '>';

                echo '<', $_s['title_tag'], ' class="dbl__title-wrapper">';
                    if ($_s['dbl_title']) echo '<span class="dbl__title dbl-title_1">', $_s['dbl_title'], '</span>';
                    if ($_s['dbl_title2']) echo '<span class="dbl__title dbl-title_2">', $_s['dbl_title2'], '</span>';
                    if ($_s['dbl_title3']) echo '<span class="dbl__title dbl-title_3">', $_s['dbl_title3'], '</span>';
                echo '</', $_s['title_tag'], '>';

                if (!empty($_s['link']['url'])) echo '</a>';

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
