<?php
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{Widget_Base, Controls_Manager, Group_Control_Typography};

/**
 * Date widget for Header CPT
 *
 *
 * @category Class
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Wgl_Header_Date extends Widget_Base
{
    public function get_name() {
        return 'wgl-header-date';
    }

    public function get_title() {
        return esc_html__('WGL Current Date', 'cleenday-core');
    }

    public function get_icon() {
        return 'wgl-header-date';
    }

    public function get_categories() {
        return [ 'wgl-header-modules' ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  Build Icon/Image Box
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'section_date_settings',
            [
                'label' => esc_html__('Date Settings', 'cleenday-core'),
            ]
        );

        $this->add_control(
            'date_format_select',
            [
                'label' => esc_html__('Date Format', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'cleenday-core'),
                    'wordpress_format' => esc_html__('Wordpress Format', 'cleenday-core'),
                    'custom' => esc_html__('Custom', 'cleenday-core'),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'date_format_custom',
            [
                'label' => esc_html__('Custom Date Format', 'cleenday-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'l, F j, Y',
                'dynamic' => ['active' => true],
                'condition' => [
                    'date_format_select' => 'custom'
                ],
                'description' => __( 'Set your date format, about this, please refer to the ', 'cleenday-core') . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/article/formatting-date-and-time/', __( 'Wordpress.org', 'cleenday-core') ),
            ]
        );

        $this->add_control(
            'time_zone',
            [
                'label' => esc_html__('Time Zone', 'cleenday-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'UTC' => esc_html__('Default', 'cleenday-core'),
                    'Pacific/Midway' => "(GMT-11:00) Midway Island",
                    'US/Samoa' => "(GMT-11:00) Samoa",
                    'US/Hawaii' => "(GMT-10:00) Hawaii",
                    'US/Alaska' => "(GMT-09:00) Alaska",
                    'US/Pacific' => "(GMT-08:00) Pacific Time (US &amp; Canada)",
                    'America/Tijuana' => "(GMT-08:00) Tijuana",
                    'US/Arizona' => "(GMT-07:00) Arizona",
                    'US/Mountain' => "(GMT-07:00) Mountain Time (US &amp; Canada)",
                    'America/Chihuahua' => "(GMT-07:00) Chihuahua",
                    'America/Mazatlan' => "(GMT-07:00) Mazatlan",
                    'America/Mexico_City' => "(GMT-06:00) Mexico City",
                    'America/Monterrey' => "(GMT-06:00) Monterrey",
                    'Canada/Saskatchewan' => "(GMT-06:00) Saskatchewan",
                    'US/Central' => "(GMT-06:00) Central Time (US &amp; Canada)",
                    'US/Eastern' => "(GMT-05:00) Eastern Time (US &amp; Canada)",
                    'US/East-Indiana' => "(GMT-05:00) Indiana (East)",
                    'America/Bogota' => "(GMT-05:00) Bogota",
                    'America/Lima' => "(GMT-05:00) Lima",
                    'America/Caracas' => "(GMT-04:30) Caracas",
                    'Canada/Atlantic' => "(GMT-04:00) Atlantic Time (Canada)",
                    'America/La_Paz' => "(GMT-04:00) La Paz",
                    'America/Santiago' => "(GMT-04:00) Santiago",
                    'Canada/Newfoundland' => "(GMT-03:30) Newfoundland",
                    'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
                    'Greenland' => "(GMT-03:00) Greenland",
                    'Atlantic/Stanley' => "(GMT-02:00) Stanley",
                    'Atlantic/Azores' => "(GMT-01:00) Azores",
                    'Atlantic/Cape_Verde' => "(GMT-01:00) Cape Verde Is.",
                    'Africa/Casablanca' => "(GMT) Casablanca",
                    'Europe/Dublin' => "(GMT) Dublin",
                    'Europe/Lisbon' => "(GMT) Lisbon",
                    'Europe/London' => "(GMT) London",
                    'Africa/Monrovia' => "(GMT) Monrovia",
                    'Europe/Amsterdam' => "(GMT+01:00) Amsterdam",
                    'Europe/Belgrade' => "(GMT+01:00) Belgrade",
                    'Europe/Berlin' => "(GMT+01:00) Berlin",
                    'Europe/Bratislava' => "(GMT+01:00) Bratislava",
                    'Europe/Brussels' => "(GMT+01:00) Brussels",
                    'Europe/Budapest' => "(GMT+01:00) Budapest",
                    'Europe/Copenhagen' => "(GMT+01:00) Copenhagen",
                    'Europe/Ljubljana' => "(GMT+01:00) Ljubljana",
                    'Europe/Madrid' => "(GMT+01:00) Madrid",
                    'Europe/Paris' => "(GMT+01:00) Paris",
                    'Europe/Prague' => "(GMT+01:00) Prague",
                    'Europe/Rome' => "(GMT+01:00) Rome",
                    'Europe/Sarajevo' => "(GMT+01:00) Sarajevo",
                    'Europe/Skopje' => "(GMT+01:00) Skopje",
                    'Europe/Stockholm' => "(GMT+01:00) Stockholm",
                    'Europe/Vienna' => "(GMT+01:00) Vienna",
                    'Europe/Warsaw' => "(GMT+01:00) Warsaw",
                    'Europe/Zagreb' => "(GMT+01:00) Zagreb",
                    'Europe/Athens' => "(GMT+02:00) Athens",
                    'Europe/Bucharest' => "(GMT+02:00) Bucharest",
                    'Africa/Cairo' => "(GMT+02:00) Cairo",
                    'Africa/Harare' => "(GMT+02:00) Harare",
                    'Europe/Helsinki' => "(GMT+02:00) Helsinki",
                    'Europe/Istanbul' => "(GMT+02:00) Istanbul",
                    'Asia/Jerusalem' => "(GMT+02:00) Jerusalem",
                    'Europe/Kiev' => "(GMT+02:00) Kyiv",
                    'Europe/Minsk' => "(GMT+02:00) Minsk",
                    'Europe/Riga' => "(GMT+02:00) Riga",
                    'Europe/Sofia' => "(GMT+02:00) Sofia",
                    'Europe/Tallinn' => "(GMT+02:00) Tallinn",
                    'Europe/Vilnius' => "(GMT+02:00) Vilnius",
                    'Asia/Baghdad' => "(GMT+03:00) Baghdad",
                    'Asia/Kuwait' => "(GMT+03:00) Kuwait",
                    'Africa/Nairobi' => "(GMT+03:00) Nairobi",
                    'Asia/Riyadh' => "(GMT+03:00) Riyadh",
                    'Europe/Moscow' => "(GMT+03:00) Moscow",
                    'Asia/Tehran' => "(GMT+03:30) Tehran",
                    'Asia/Baku' => "(GMT+04:00) Baku",
                    'Europe/Volgograd' => "(GMT+04:00) Volgograd",
                    'Asia/Muscat' => "(GMT+04:00) Muscat",
                    'Asia/Tbilisi' => "(GMT+04:00) Tbilisi",
                    'Asia/Yerevan' => "(GMT+04:00) Yerevan",
                    'Asia/Kabul' => "(GMT+04:30) Kabul",
                    'Asia/Karachi' => "(GMT+05:00) Karachi",
                    'Asia/Tashkent' => "(GMT+05:00) Tashkent",
                    'Asia/Kolkata' => "(GMT+05:30) Kolkata",
                    'Asia/Kathmandu' => "(GMT+05:45) Kathmandu",
                    'Asia/Yekaterinburg' => "(GMT+06:00) Ekaterinburg",
                    'Asia/Almaty' => "(GMT+06:00) Almaty",
                    'Asia/Dhaka' => "(GMT+06:00) Dhaka",
                    'Asia/Novosibirsk' => "(GMT+07:00) Novosibirsk",
                    'Asia/Bangkok' => "(GMT+07:00) Bangkok",
                    'Asia/Jakarta' => "(GMT+07:00) Jakarta",
                    'Asia/Krasnoyarsk' => "(GMT+08:00) Krasnoyarsk",
                    'Asia/Chongqing' => "(GMT+08:00) Chongqing",
                    'Asia/Hong_Kong' => "(GMT+08:00) Hong Kong",
                    'Asia/Kuala_Lumpur' => "(GMT+08:00) Kuala Lumpur",
                    'Australia/Perth' => "(GMT+08:00) Perth",
                    'Asia/Singapore' => "(GMT+08:00) Singapore",
                    'Asia/Taipei' => "(GMT+08:00) Taipei",
                    'Asia/Ulaanbaatar' => "(GMT+08:00) Ulaan Bataar",
                    'Asia/Urumqi' => "(GMT+08:00) Urumqi",
                    'Asia/Irkutsk' => "(GMT+09:00) Irkutsk",
                    'Asia/Seoul' => "(GMT+09:00) Seoul",
                    'Asia/Tokyo' => "(GMT+09:00) Tokyo",
                    'Australia/Adelaide' => "(GMT+09:30) Adelaide",
                    'Australia/Darwin' => "(GMT+09:30) Darwin",
                    'Asia/Yakutsk' => "(GMT+10:00) Yakutsk",
                    'Australia/Brisbane' => "(GMT+10:00) Brisbane",
                    'Australia/Canberra' => "(GMT+10:00) Canberra",
                    'Pacific/Guam' => "(GMT+10:00) Guam",
                    'Australia/Hobart' => "(GMT+10:00) Hobart",
                    'Australia/Melbourne' => "(GMT+10:00) Melbourne",
                    'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
                    'Australia/Sydney' => "(GMT+10:00) Sydney",
                    'Asia/Vladivostok' => "(GMT+11:00) Vladivostok",
                    'Asia/Magadan' => "(GMT+12:00) Magadan",
                    'Pacific/Auckland' => "(GMT+12:00) Auckland",
                    'Pacific/Fiji' => "(GMT+12:00) Fiji",
                ],
                'default' => 'UTC',
            ]
        );

        $this->add_control(
            'date_align',
            [
                'label' => esc_html__('Alignment', 'cleenday-core'),
                'type' => Controls_Manager::CHOOSE,
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
                'label_block' => false,
                'default' => 'flex-start',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .wgl-header-date' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Text Idle', 'cleenday-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-header-date' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .wgl-header-date',
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();
        extract($settings);

        $UTC = new \DateTimeZone("UTC");
        $newTZ = new \DateTimeZone($time_zone);

        $date = new \DateTime( 'NOW', $UTC );
        $date->setTimezone( $newTZ );

        $date_format = 'l, F j, Y';
        $class_date = '';

        switch ($date_format_select) {
            case 'default':
                $class_date .= ' wgl-default-format';
                $date_format = '<\s\p\a\n>d</\s\p\a\n> <\s\p\a\n><\s\p\a\n>M</\s\p\a\n> Y</\s\p\a\n>';
                break;

            case 'wordpress_format':
                $date_format = get_option( 'date_format' );
                break;

            default:
                $date_format = $date_format_custom;
                break;
        }

        echo '<div class="wgl-header-date'.esc_attr($class_date).'">';
            echo \Cleenday_Theme_Helper::render_html( $date->format( $date_format ) );
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