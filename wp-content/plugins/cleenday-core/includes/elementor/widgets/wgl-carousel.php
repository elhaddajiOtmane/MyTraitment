<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/widgets/wgl-carousel.php.
*/
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Widget_Base, Controls_Manager, Repeater};
use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;
use WglAddons\Includes\{Wgl_Carousel_Settings, Wgl_Elementor_Helper};

class Wgl_Carousel extends Widget_Base
{
    public function get_name() {
        return 'wgl-carousel';
    }

    public function get_title() {
        return esc_html__('WGL Carousel', 'cleenday-core');
    }

    public function get_icon() {
        return 'wgl-carousel';
    }

    public function get_script_depends() {
        return ['slick'];
    }

    public function get_categories() {
        return ['wgl-extensions'];
    }


    protected function register_controls()
    {
        $repeater = new REPEATER();

        $this->start_controls_section('wgl_carousel_content_section',
            ['label' => esc_html__('General' , 'cleenday-core')]
        );

	    $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'cleenday-core'),
                'type' => Controls_Manager::SELECT2,
                'options' => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
            ]
        );

        $this->add_control(
            'content_repeater',
            [
                'label' => esc_html__('Templates', 'cleenday-core'),
                'type' => Controls_Manager::REPEATER,
                'description' => esc_html__('Slider content is a template which you can choose from Elementor library. Each template will be a slider content', 'cleenday-core'),
                'fields' => $repeater->get_controls(),
                'title_field' => esc_html__('Template:', 'cleenday-core') . ' {{{ content }}}'
            ]
        );


        $this->add_control(
            'items_per_line',
            [
                'label' => esc_html__('Columns Amount', 'cleenday-core'),
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

        $this->end_controls_section();

	    /*-----------------------------------------------------------------------------------*/
	    /*  CONTENT -> CAROUSEL OPTIONS
		/*-----------------------------------------------------------------------------------*/

	    Wgl_Carousel_Settings::options(
	    	$this, [ 'default_use_carousel' => 'yes' ]
	    );
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        $content = [];

        foreach ($_s['content_repeater'] as $template) {
            array_push($content, $template['content']);
        }
        echo Wgl_Carousel_Settings::init($_s, $content, true);
    }

}