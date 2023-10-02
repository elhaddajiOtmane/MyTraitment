<?php

defined('ABSPATH') || exit;

use Cleenday_Theme_Helper as Cleenday;

if (!class_exists('Cleenday_Get_Header')) {
    class Cleenday_Get_Header
    {
        public static $search_form_counter = 0;

        protected $html_render = 'bottom';
        protected $id;
        protected $side_area_enabled;

        protected $header_type;
        protected $header_page_select_id;
        protected $header_sticky_page_select_id;

        private static $instance = null;
        private $get_menu_logo;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            $this->init();
        }

        public function init()
        {
            $this->header_vars();
            $this->require_components();
            $this->header_render_html();
        }

        public function header_vars()
        {
            $this->id = !is_archive() ? get_queried_object_id() : 0;

            /** Header Template */
            $this->header_type = Cleenday::get_option('header_type');

            if ('custom' === $this->header_type) {

                $header_page_select = Cleenday::get_option('header_page_select');

                if ($header_page_select) {
                    $this->header_page_select_id = intval($header_page_select);
                    $this->header_page_select_id = $this->multiLanguageSupport( $this->header_page_select_id );
                }
            }

            /** Sticky Header Template */
            if (Cleenday::get_mb_option('header_sticky', 'mb_customize_header_layout', 'custom') == '1') {
                $header_sticky_page_select = Cleenday::get_option('header_sticky_page_select');

                if (!empty($header_sticky_page_select)) {
                    $this->header_sticky_page_select_id = intval($header_sticky_page_select);
                    $this->header_sticky_page_select_id = $this->multiLanguageSupport( $this->header_sticky_page_select_id );
                }
            }

            if (
                class_exists('RWMB_Loader')
                && 0 !== $this->id
                && rwmb_meta('mb_customize_header_layout') == 'custom'
            ) {
                if (rwmb_meta('mb_header_content_type') !== 'default') {
                    $this->header_type = 'custom';
                    $this->header_page_select_id = (int) rwmb_meta('mb_customize_header');
                    $this->header_page_select_id = $this->multiLanguageSupport( $this->header_page_select_id );
                }

                if (rwmb_meta('mb_sticky_header_content_type') !== 'default') {
                    $this->header_sticky_page_select_id = (int) rwmb_meta('mb_customize_sticky_header');
                    $this->header_sticky_page_select_id = $this->multiLanguageSupport( $this->header_sticky_page_select_id );
                }
            }
        }

        public function require_components()
        {
            require_once get_theme_file_path('/templates/header/components/logo.php');
        }

        /**
         * Multi Language Support
         *
         *
         * @access public
         * @since 1.0.0
         */
        public function multiLanguageSupport($id)
        {
            if (class_exists('Polylang') && function_exists('pll_current_language')) {
                $currentLanguage = pll_current_language();
                $translations = PLL()->model->post->get_translations($id);

                $polylang_id = $translations[$currentLanguage] ?? '';
                $id = !empty($polylang_id) ? $polylang_id : $id;
            }

            if (class_exists('SitePress')) {
                $id = wpml_object_id_filter($id, 'header', false, ICL_LANGUAGE_CODE);
            }

            return $id;
        }

        /**
         * Generate header class
         */
        public function header_class()
        {
            $header_shadow = Cleenday::get_option('header_shadow');
            $header_on_bg = Cleenday::get_option('header_on_bg');
            $header_class = '';

            if ('custom' === $this->header_type) {
                if (
                    !empty($this->header_page_select_id)
                    && did_action('elementor/loaded')
                ) {
                    // Get the page settings manager
                    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');

                    // Get the settings model for header post
                    $page_settings_model = $page_settings_manager->get_model($this->header_page_select_id);

                    $header_on_bg = $page_settings_model->get_settings('header_on_bg');
                }
            } elseif ($header_shadow) {
                $header_class .= ' header_shadow';
            }

            if (
                get_option('show_on_front') === 'posts'
                && (is_home() || is_front_page())
            ) {
                $header_on_bg = false;
            }

            if ($header_on_bg) {
                $header_class .= ' header_overlap';
            }

            return $header_class;
        }

        /**
         * Generate header editor
         */
        public function header_bar_editor($location = null, $position = null)
        {
            if (!$position) {
                return;
            }

            // Define Theme options and field configurations
            ${'header_' . $position . '_editor'} = Cleenday::get_option($location . '_header_bar_' . $position . '_editor');
            $html_render = ${'header_' . $position . '_editor'};
            // Header Bar HTML Editor render
            $html = '';
            if (!empty($html_render)) {
                $html .= "<div class='".esc_attr($location)."_header ".esc_attr($position)."_editor header_render_editor header_render'>";
                    $html .= '<div class="wrapper">';
                            $html .= do_shortcode( $html_render );
                    $html .= '</div>';
                $html .= '</div>';
            }

            return $html;
        }

        /**
         * Generate header delimiter
         */
        public function header_bar_delimiter($k = null)
        {
            if (!$k) {
                return;
            }

            /*
            * Define Theme options and field configurations.
            */

            $get_number = (int) filter_var($k, FILTER_SANITIZE_NUMBER_INT);
            $height = Cleenday::get_option('bottom_header_delimiter' . $get_number . '_height');
            $width = Cleenday::get_option('bottom_header_delimiter' . $get_number . '_width');

            $bg_color = Cleenday::get_option('bottom_header_delimiter' . $get_number . '_bg');

            $margin = Cleenday::get_option('bottom_header_delimiter' . $get_number . '_margin');

            $margin_left = !empty($margin['margin-left']) ? (int) $margin['margin-left'] : '';
            $margin_right = !empty($margin['margin-right']) ? (int) $margin['margin-right'] : '';

            $custom_sticky = '';
            if ($this->html_render === 'sticky') {
                $custom_sticky = Cleenday::get_option('bottom_header_delimiter'.$get_number.'_sticky_custom');
                if (!empty($custom_sticky)) {
                    $bg_color = Cleenday::get_option('bottom_header_delimiter'.$get_number.'_sticky_color');
                    $height  = Cleenday::get_option('bottom_header_delimiter'.$get_number.'_sticky_height');
                }
            }

            // Header Bar Delimiter render
            $style = '';
            if (is_array($height)) {
                $style .= 'height: ' . esc_attr((int) $height['height']) . 'px;';
            }

            if (is_array($width)) {
                $style .= 'width: ' . esc_attr((int) $width['width']) . 'px;';
            }

            if (!empty($bg_color['rgba'])) {
                $style .= 'background-color: ' . esc_attr($bg_color['rgba']) . ';';
            }

            if (!empty($margin_left)) {
                $style .= 'margin-left:' . esc_attr((int) $margin_left) . 'px;';
            }

            if (!empty($margin_right)) {
                $style .= 'margin-right:' . esc_attr((int) $margin_right) . 'px;';
            }

            echo '<div class="delimiter-wrapper">',
                '<div class="delimiter"' . (!empty($style) ? ' style="' . $style . '"' : '') . '></div>',
            '</div>';
        }

        /**
         * Generate header button
         */
        public function header_bar_button($k = null)
        {
            if (!$k) {
                return;
            }

            /*
             * Define Theme options and field configurations.
             */

            $get_number = (int) filter_var($k, FILTER_SANITIZE_NUMBER_INT);
            $button_text = Cleenday::get_option('bottom_header_button' . $get_number . '_title');

            $link = Cleenday::get_option('bottom_header_button' . $get_number . '_link');
            $target = Cleenday::get_option('bottom_header_button' . $get_number . '_target');

            $size = Cleenday::get_option('bottom_header_button' . $get_number . '_size') ?? 'md';

            $customize = Cleenday::get_option('bottom_header_button' . $get_number . '_custom');
            $customize = empty($customize) ? 'def' : 'color';

            $text_color_idle = Cleenday::get_option('bottom_header_button' . $get_number . '_color_txt')['rgba'] ?? '';
            $text_color_hover = Cleenday::get_option('bottom_header_button' . $get_number . '_hover_color_txt')['rgba'] ?? '';

            $border_color = Cleenday::get_option('bottom_header_button' . $get_number . '_border')['rgba'] ?? '';
            $border_color_hover = Cleenday::get_option('bottom_header_button' . $get_number . '_hover_border')['rgba'] ?? '';
            $border_radius = Cleenday::get_option('bottom_header_button' . $get_number . '_radius') ?? false;

            $bg_color = Cleenday::get_option('bottom_header_button' . $get_number . '_bg')['rgba'] ?? '';
            $bg_color_hover = Cleenday::get_option('bottom_header_button' . $get_number . '_hover_bg')['rgba'] ?? '';

            $button_css_id = uniqid('cleenday_button_');

            $settings = [
                'link' => [
                    'url' => $link,
                    'is_external' => $target,
                    'nofollow' => '',
                ],
                'button_css_id' => $button_css_id,
            ];

            // Start Custom CSS
            ob_start();
            if ($customize == 'color') {
                echo "#$button_css_id {
                        color: ".($text_color_idle ? esc_attr($text_color_idle) : 'transparent').";
                    }";
                echo "#$button_css_id:hover {
                        color: ".($text_color_hover ? esc_attr($text_color_hover) : 'transparent').";
                    }";
                $border_color = $border_color ? esc_attr($border_color) : 'transparent';
                echo "#$button_css_id {
                        border-color: $border_color;
                        background-color: $bg_color;
                    }";
                echo "#$button_css_id:hover {
                        border-color: ".($border_color_hover ? esc_attr($border_color_hover) : 'transparent').";
                        background-color: $bg_color_hover;
                    }";
            }
            $styles = ob_get_clean();

            // Register css
            if ($styles) Cleenday::enqueue_css($styles);

            unset($this->render_attributes);


            // Attributes
            $this->add_render_attribute('wrapper', 'class', 'button-wrapper');

            if (!empty($settings['link']['url'])) {
                $this->add_render_attribute('button', 'href', $settings['link']['url']);
                $this->add_render_attribute('button', 'class', 'elementor-button-link');
                if ($settings['link']['is_external']) {
                    $this->add_render_attribute('button', 'target', '_blank');
                }
                if ($settings['link']['nofollow']) {
                    $this->add_render_attribute('button', 'rel', 'nofollow');
                }
            }

            $this->add_render_attribute('button', 'id', $button_css_id);
            $this->add_render_attribute('button', 'class', 'wgl-button');
            $this->add_render_attribute('button', 'class', 'btn-size-' . $size);
            $this->add_render_attribute('button', 'role', 'button');

            if (isset($settings['hover_animation'])) {
                $this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
            }

            if ($border_radius = (int) $border_radius) {
                $this->add_render_attribute('button', 'style', 'border-radius: '.$border_radius.';' );
            }

            // Render ?>
            <div class="header_button">
            <div class="wrapper">
                <div <?php echo Cleenday::render_html($this->get_render_attribute_string('wrapper')); ?>>
                    <a <?php echo Cleenday::render_html($this->get_render_attribute_string('button')); ?>><?php
                        $this->render_text($button_text); ?>
                    </a>
                </div>
            </div>
            </div><?php
        }


        public function render_text($button_text)
        {
            $this->add_render_attribute( [
                'content-wrapper' => [
                    'class' => 'button-content-wrapper',
                ],
                'text' => [
                    'class' => 'wgl-button-text',
                ],
            ] ); ?>

            <span <?php echo Cleenday::render_html($this->get_render_attribute_string('content-wrapper')); ?>>
                <span <?php echo Cleenday::render_html($this->get_render_attribute_string('text')); ?>><?php
                    echo esc_html($button_text); ?>
                </span>
            </span><?php
        }


        /**
         * Add render attribute.
         *
         * Used to add attributes to a specific HTML element.
         *
         * The HTML tag is represented by the element parameter, then you need to
         * define the attribute key and the attribute key. The final result will be:
         * `<element attribute_key="attribute_value">`.
         *
         * Example usage:
         *
         * `$this->add_render_attribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
         * `$this->add_render_attribute( 'widget', 'id', 'custom-widget-id' );`
         * `$this->add_render_attribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );`
         *
         * @since 1.0.0
         * @access public
         *
         * @param array|string $element   The HTML element.
         * @param array|string $key       Optional. Attribute key. Default is null.
         * @param array|string $value     Optional. Attribute value. Default is null.
         * @param bool         $overwrite Optional. Whether to overwrite existing
         *                                attribute. Default is false, not to overwrite.
         *
         * @return Cleenday_Get_Header Current instance of the element.
         */
        public function add_render_attribute($element, $key = null, $value = null, $overwrite = false)
        {
            if (is_array($element)) {
                foreach ($element as $element_key => $attributes) {
                    $this->add_render_attribute($element_key, $attributes, null, $overwrite);
                }

                return $this;
            }

            if (is_array($key)) {
                foreach ($key as $attribute_key => $attributes) {
                    $this->add_render_attribute($element, $attribute_key, $attributes, $overwrite);
                }

                return $this;
            }

            if (empty( $this->render_attributes[ $element ][ $key ] )) {
                $this->render_attributes[ $element ][ $key ] = [];
            }

            settype($value, 'array');

            if ($overwrite) {
                $this->render_attributes[ $element ][ $key ] = $value;
            } else {
                $this->render_attributes[ $element ][ $key ] = array_merge( $this->render_attributes[ $element ][ $key ], $value );
            }

            return $this;
        }

        public function get_render_attribute_string($element)
        {
            if (empty($this->render_attributes[$element])) {
                return '';
            }

            return ' ' . Cleenday::render_html_attributes( $this->render_attributes[ $element ] );
        }

        /**
        * Generate header spacer
        *
        *
        * @access public
        * @since 1.0.0
        */
        public function header_bar_spacer($location = null, $key = null)
        {
            if (!$key) return;

            /*
             * Define Theme options and field configurations.
             */

            $get_number = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
            $spacer = Cleenday::get_option($location . '_header_spacer' . $get_number);
            // Header Bar Spacer render
            $html = '';
            if (is_array($spacer)) {
                $html .= "<div class='header_spacing spacer_".$get_number."' style='width:".esc_attr( (int) $spacer['width'] )."px;'>";
                $html .= '</div>';
            }

            return $html;
        }

        public function header_bar_spacer_height($location = null, $key = null)
        {
            if (!$key) {
                return;
            }

            /*
             * Define Theme options and field configurations.
             */

            $get_number = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
            $spacer = Cleenday::get_option($location.'_header_spacer'.$get_number);
            // Header Bar Spacer render
            $html = '';
            if (is_array($spacer)) {
                $html .= "<div class='header_spacing spacer_".$get_number."' style='display:block;height:".esc_attr( (int) $spacer['width'] )."px;'>";
                $html .= '</div>';
            }

            return $html;
        }

        /**
         * Generate header builder layout
         *
         * @access public
         * @since 1.0.0
         */
        public function build_header_layout($section = 'bottom')
        {
            $sticky = '';

            if ('sticky' === $this->html_render) {
                if (!empty($this->header_sticky_page_select_id)) {
                    $sticky = '_sticky';
                    $this->header_type = 'custom';
                }
                $section = 'bottom';
            }

            if (
                'custom' === $this->header_type
                && 'bottom' === $section
            ) {
                require_once get_theme_file_path('/templates/header/elementor-builder/header-builder' . $sticky . '.php');
            } else {
                $this->header_default($section);
            }
        }

        public function header_default($section = 'bottom')
        {
            $header_layout = Cleenday::get_option($section . '_header_layout');
            $lavalamp_active = Cleenday::get_option('lavalamp_active');

            // Get item from recycle bin
            $j = 0;
            $header_layout_top = $header_layout_middle = $header_layout_bottom = [];

            // Build Row Item
            $counter = 1;
            if ($section == 'bottom') {
                $header_layout = array_slice($header_layout, 1);
                // $count = count($header_layout);
                $half = 3;
                for ($i = 0; $i < 3; $i++) {
                    switch ($i) {
                        case 0:
                            $header_layout_top = array_slice($header_layout, $j, $half);
                            break;
                        case 1:
                            $header_layout_middle = array_slice($header_layout, $j, $half);
                            break;
                        case 2:
                            $header_layout_bottom = array_slice($header_layout, $j, $half);
                            break;
                    }

                    $j = $j+$half;
                }

                // WGL Header Builder Row
                $counter = 3;
            }

            /**
            * Generate sticky builder(default)
            */
            $inc_sticky = 0;
            $sticky_present_element = false;
            $sticky_last_row = '';
            $sticky_key_last_row = [];

            for ($i = 1; $i <= $counter; $i++) {
                if ($section == 'bottom') {
                    switch ($i) {
                        case 1:
                            $sticky_loc = '_top';
                            break;
                        case 2:
                            $sticky_loc = '_middle';
                            break;
                        case 3:
                            $sticky_loc = '_bottom';
                            break;
                    }
                    $sticky_header_layout = ${"header_layout" . $sticky_loc};

                    // Disabled Sticky Options
                    $disabled_sticky = false;
                    foreach ($sticky_header_layout as $s => $d) {
                        if (
                            isset($sticky_header_layout[$s]['disable_row'])
                            && $sticky_header_layout[$s]['disable_row'] == 'true'
                        ) {
                            $disabled_sticky = true;
                            continue;
                        }
                    }
                    if (!$disabled_sticky) {
                        foreach ($sticky_header_layout as $key => $v) {
                            if (isset($sticky_header_layout[$key]['disable_row'])) {
                                unset($sticky_header_layout[$key]['disable_row']);
                            }
                            if (
                                count($sticky_header_layout[$key]) == 1
                                && empty($sticky_header_layout[$key]['placebo'])
                                || count($sticky_header_layout[$key]) > 1
                            ) {
                                $sticky_present_element = true;
                                $sticky_key_last_row[] = $key;
                            }
                        }
                    }

                } else {
                    $sticky_present_element = true;
                }

                if (
                    !empty($sticky_header_layout)
                    && $sticky_present_element
                    && $this->html_render == 'sticky'
                ) {
                    $inc_sticky++;
                    $sticky_present_element = false;
                }
            }

            if (is_array($sticky_key_last_row)) {
                $last_element = end($sticky_key_last_row);
                if ($last_element) {
                    switch ($last_element) {
                        case array_key_exists($last_element, $header_layout_top):
                            $sticky_last_row = '_top';
                            break;
                        case array_key_exists($last_element, $header_layout_middle):
                            $sticky_last_row = '_middle';
                            break;
                        case array_key_exists($last_element, $header_layout_bottom):
                            $sticky_last_row = '_bottom';
                            break;
                    }
                }
            }
            /**
            * End Generate sticky builder(default)
            */

            $location = '';
            $has_element = false;
            $counter = $inc_sticky > 1 ? 1 : $counter;

            for ($i = 1; $i <= $counter; $i++) {
                if ($section == 'bottom') {
                    switch ($i) {
                        case 1: $location = '_top'; break;
                        case 2: $location = '_middle'; break;
                        case 3: $location = '_bottom'; break;
                    }

                    if ($inc_sticky > 1) {
                        $location = $sticky_last_row;
                    }

                    $header_layout = ${"header_layout" . $location};

                    // Disabled Row Options
                    $disabled_row = false;
                    foreach ($header_layout as $s => $d) {
                        if (
                            isset($header_layout[$s]['disable_row'])
                            && 'true' == $header_layout[$s]['disable_row']
                        ) {
                            $disabled_row = true;
                            continue;
                        }
                    }

                    if (!$disabled_row) {
                        foreach ($header_layout as $key => $v) {
                            if (isset($header_layout[$key]['disable_row'])) {
                                unset($header_layout[$key]['disable_row']);
                            }
                            if (
                                count($header_layout[$key]) == 1 && empty($header_layout[$key]['placebo'])
                                || count($header_layout[$key]) > 1
                            ) {
                                $has_element = true;
                            }
                        }
                    }

                } else {
                    $has_element = true;
                }

                if (!empty($header_layout) && $has_element) {
                    switch ($section) {
                        case 'mobile_content':
                            foreach ($header_layout as $part => $value) if ('items' != $part) {
                                if (
                                    !empty($header_layout[$part])
                                    && count($header_layout[$part]) == 1
                                    && empty($header_layout[$part]['placebo'])
                                    || count($header_layout[$part]) > 1
                                ) {
                                    foreach ($header_layout[$part] as $key => $value) if ('placebo' != $key) {
                                        switch ($key) {
                                            case 'item_search':
                                                echo '<div class="header_search search_mobile_menu">';
                                                    echo '<div class="header_search-field">';
                                                        get_search_form();
                                                    echo '</div>';
                                                echo '</div>';
                                                break;

                                            case 'logo':
                                                $menu_condition = $this->get_menu_logo ?? '';
                                                if ($menu_condition) unset($this->get_menu_logo);
                                                $logo_render = $this->get_logo($menu_condition, '_menu');
                                                echo !empty($logo_render) ? $logo_render : '';
                                                break;

                                            case 'menu':
                                                $menu = 'main_menu';
                                                if (Cleenday::get_option('custom_mobile_menu')) {
                                                    $custom_menu = true;
                                                    $menu = Cleenday::get_option('mobile_menu');
                                                }
                                                if (
                                                    class_exists('RWMB_Loader')
                                                    && $this->id !== 0
                                                    && rwmb_meta('mb_customize_header_layout') == 'custom'
                                                    && rwmb_meta('mb_mobile_menu_custom') == 'custom'
                                                ) {
                                                    $custom_menu = true;
                                                    $menu = rwmb_meta('mb_mobile_menu_header');
                                                }
                                                if (has_nav_menu($menu) || isset($custom_menu)) {
                                                    echo '<nav class="primary-nav">';
                                                        cleenday_main_menu($menu, null);
                                                    echo '</nav>';
                                                }
                                                break;

                                            case stripos($key, 'html') !== false:
                                                $this_header_bar_editor = $this->header_bar_editor('mobile', $key);
                                                echo !empty($this_header_bar_editor) ? $this->header_bar_editor('mobile', $key) : '';
                                                break;

                                            case 'wpml':
                                                if (class_exists('SitePress')) {
                                                    echo '<div class="sitepress_container">';
                                                        do_action('wpml_add_language_selector');
                                                    echo '</div>';
                                                }
                                                break;

                                            case stripos($key, 'spacer') !== false:
                                                $this_header_bar_spacer = $this->header_bar_spacer_height('mobile', $key);
                                                echo !empty($this_header_bar_spacer) ? $this->header_bar_spacer_height('mobile', $key) : '';
                                                break;
                                        }
                                    }
                                }
                            }
                            break;

                        default:
                            echo '<div class="wgl-header-row wgl-header-row-section', esc_attr($location), '"', $this->row_style_color($location), '>';
                            echo '<div class="', esc_attr($this->row_width_class($location)), '">';
                            echo '<div class="wgl-header-row_wrapper"', $this->row_style_height($location), '>';
                                foreach ($header_layout as $part => $value) {
                                    if (!empty($header_layout[$part]) && $part != 'items') {

                                        $area_name = '';
                                        switch ($part) {
                                            case stripos($part, 'center') !== false:
                                                $area_name = 'center';
                                                break;
                                            case stripos($part, 'left') !== false:
                                                $area_name = 'left';
                                                break;
                                            case stripos($part, 'right') !== false:
                                                $area_name = 'right';
                                                break;
                                        }
                                        $column_class = $this->column_class($location, $area_name);

                                        $class_area = 'position_' . $area_name . $location;

                                        echo "<div class='", esc_attr(sanitize_html_class($class_area)), " header_side", esc_attr($column_class), "'>";

                                        if (
                                            count($header_layout[$part]) == 1
                                            && empty($header_layout[$part]['placebo'])
                                            || count($header_layout[$part]) > 1
                                        ) { ?>
                                            <div class="header_area_container"><?php
                                            foreach ($header_layout[$part] as $key => $value) {
                                                if ($key != 'placebo' && $key != 'pos_column') {
                                                    switch ($key) {
                                                        case 'item_search':
                                                            $this->search($this->html_render, $location);
                                                            $this->html_render == 'mobile' || self::$search_form_counter++; // mobile header forms doesn't count
                                                            break;

                                                        case 'cart':
                                                            if (class_exists('WooCommerce')) {
                                                                $this->cart($location, $section);
                                                            }
                                                            break;

                                                        case 'login':
                                                            if (class_exists('WooCommerce')) {
                                                                $this->login_in($location, $section);
                                                            }
                                                            break;

                                                        case 'side_panel':
                                                            $this->side_panel_enabled = true;
                                                            $this->get_side_panel($location, $section);
                                                            break;

                                                        case 'logo':
                                                            $logo_render = $this->get_logo();
                                                            echo !empty($logo_render) ? $logo_render : '';
                                                            break;

                                                        case 'menu':
	                                                        $nav_classes = '1' === $lavalamp_active ?  'menu_line_enable' : '';
	                                                        $nav_classes .= ' children-counter-disable'; ?>
                                                            <nav class="primary-nav <?php echo esc_attr($nav_classes); ?>" <?php
                                                                $this->row_style_height($location); ?> ><?php
                                                            if (has_nav_menu('main_menu')) {
                                                                cleenday_main_menu('main_menu', null);
                                                            } ?>
                                                            </nav>
                                                            <div class="hamburger-box">
                                                                <div class="hamburger-inner">
		                                                            <span></span>
		                                                            <span></span>
		                                                            <span></span>
	                                                            </div>
                                                            </div><?php
                                                            break;

                                                        case stripos($key, 'html') !== false:
                                                            $this_header_bar_editor = $this->header_bar_editor($section, $key);
                                                            echo !empty($this_header_bar_editor) ? $this->header_bar_editor($section, $key) : '';
                                                            break;

                                                        case 'wpml':
                                                            if (class_exists('SitePress')) {
                                                                echo '<div class="sitepress_container" ', $this->row_style_height($location), '>';
                                                                    do_action('wpml_add_language_selector');
                                                                echo '</div>';
                                                            }
                                                            break;

                                                        case stripos($key, 'delimiter') !== false:
                                                            $this->header_bar_delimiter($key);
                                                            break;

                                                        case stripos($key,'button') !== false:
                                                            $this->header_bar_button($key);
                                                            break;

                                                        case stripos($key,'spacer') !== false:
                                                            $this_header_bar_spacer = $this->header_bar_spacer($section, $key);
                                                            echo !empty($this_header_bar_spacer) ? $this->header_bar_spacer($section, $key) : '';
                                                            break;
                                                    }
                                                }
                                            } ?>
                                            </div><?php
                                        }
                                        echo '</div>';
                                    }
                                }
                            echo '</div>';
                            echo '</div>';
                            echo '</div>'; // wgl-header-row wgl-header-row-section
                        break;
                    }
                    $has_element = false;
                }
            }
        }

        /**
        * Loop Header Row Style Color
        */
        private function row_width_class($s = '_middle')
        {
            $width_container = '';

            switch ($section) {
                case 'bottom':
                    $width_container = Cleenday::get_option('header'.$s.'_full_width');
                    break;
                case 'sticky':
                    $width_container = Cleenday::get_option('header_custom_sticky_full_width');
                    break;
                case 'mobile':
                    $width_container = false;
                    break;
            }

            return $width_container ? 'fullwidth-wrapper' : 'wgl-container';
        }

        private function row_style_color($s = '_middle')
        {
            $style = '';

            switch ($this->html_render) {
                case 'bottom':
                case 'sticky':
                    $header_background = Cleenday::get_option('header' . $s . '_background');
                    $header_bg_image = Cleenday::get_option('header' . $s . '_background_image')['url'] ?? '';

                    $header_color = Cleenday::get_option('header' . $s . '_color');
                    $header_bottom_border = Cleenday::get_option('header' . $s . '_bottom_border');
                    $header_border_height = Cleenday::get_option('header' . $s . '_border_height')['height'] ?? '';
                    $header_bottom_border_color = Cleenday::get_option('header' . $s . '_bottom_border_color');

                    $style = !empty($header_background['rgba']) ? 'background-color: '.esc_attr($header_background['rgba']).';' : '';

                    if ($header_bg_image) {
                        $style .= 'background-size: cover;'
                        . ' background-repeat: no-repeat;'
                        . ' background-image: url(' . esc_attr($header_bg_image) . ');';
                    }

                    if (!empty($header_bottom_border)) {
                        $style .= $header_border_height ? 'border-bottom-width: '.(int) (esc_attr($header_border_height)).'px;' : '';
                        if (!empty($header_bottom_border_color['rgba'])) {
                            $style .= 'border-bottom-color: '.esc_attr($header_bottom_border_color['rgba']).';';
                        }

                        $style .= 'border-bottom-style: solid;';
                    }

                    $style .= $header_color ? 'color: ' . esc_attr($header_color) . ';' : '';

                    $customize_width = Cleenday::get_option('header'.$s.'_max_width_custom');

                    if ('1' == $customize_width) {
                        $max_width = Cleenday::get_option('header'.$s.'_max_width');
                        $max_width = $max_width['width'];

                        $style .= 'max-width: '.esc_attr((int) $max_width ).'px; margin-left: auto; margin-right: auto;';
                    }

                    break;
            }

            return !empty($style) ? ' style="' . $style . '"' : '';
        }

        /**
        * Loop Row Style Height
        */
        private function row_style_height($s = '_middle')
        {
            $header_mobile_height = Cleenday::get_option('header_mobile_height')['height'] ?? false;
            $header_height = Cleenday::get_option('header'.$s.'_height')['height'] ?? false;

            $style = '';

            switch ($this->html_render) {
                case 'mobile':
                    if ($header_mobile_height) {
                        $style = 'height: '. (int) esc_attr($header_mobile_height) .'px;';
                    }
                    break;

                default:
                    if ($header_height) {
                        $style = 'height: '. (int) esc_attr($header_height) .'px;';
                    }
                    break;
            }

            return $style ? ' style="'. $style .'"' : '';
        }

        protected function side_panel_style_icon()
        {
            $icon_bg = Cleenday::get_option('bottom_header_side_panel_background') ?? '';
            $icon_color = Cleenday::get_option('bottom_header_side_panel_color')['rgba'] ?? '';

            $style = $icon_bg ? 'background-color: ' . esc_attr($icon_bg).';' : '';
            $style .= $icon_color ? 'color: ' . esc_attr($icon_color) . ';' : '';

            return $style ? ' style="' . $style . '"' : '';
        }

        /**
         * Loop column class
         */
        private function column_class($s = '_middle', $area = '')
        {
            $dispay = Cleenday::get_option('header_column' . $s . '_' . $area . '_display');
            $v_align = Cleenday::get_option('header_column' . $s . '_' . $area . '_vert');
            $h_align = Cleenday::get_option('header_column' . $s . '_' . $area . '_horz');

            $column_class = !empty($dispay) ? ' display_' . $dispay : '';
            $column_class .= !empty($v_align) ? ' v_align_' . $v_align : '';
            $column_class .= !empty($h_align) ? ' h_align_' . $h_align : '';

            return $column_class;
        }

        /**
         * Generate header mobile menu
         *
         *
         * @access public
         * @since 1.0.0
         */
        public function build_header_mobile_menu()
        {
            $header_queries = Cleenday::get_option('header_mobile_queris');

            if (
                $this->header_type === 'custom'
                && !empty($this->header_page_select_id)
                && did_action('elementor/loaded')
            ) {
                // Get the page settings manager
                $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');

                // Get the settings model for header post
                $page_settings_model = $page_settings_manager->get_model($this->header_page_select_id);

                $header_queries = $page_settings_model->get_settings('mobile_breakpoint') ?? $header_queries;
            }

            $mobile_header_custom = Cleenday::get_option('mobile_header');
            $menu_occurrence = $mobile_header_custom ? Cleenday::get_option('mobile_position') : ''; ?>

            <div class='mobile_nav_wrapper' data-mobile-width='<?php echo Cleenday::render_html($header_queries); ?>'>
            <div class="container-wrapper">
                <div class="wgl-menu_overlay"></div>
                <div class="wgl-menu_outer <?php echo (!empty($menu_occurrence) ? ' menu-position_'.esc_attr($menu_occurrence) : ''); ?>">
                    <div class="wgl-menu-outer_header">
                        <div class="mobile-hamburger-close">
                            <div class="hamburger-box">
                                <div class="hamburger-inner">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wgl-menu-outer_content"><?php
                        if (!empty($mobile_header_custom)) {
                            $this->get_menu_logo = true;
                            $this->build_header_layout('mobile_content');
                        } else {?>
                            <nav class="primary-nav"><?php
                                $logo = $this->get_logo(true, '_menu');
                                echo !empty($logo) ? $logo : '';
                                if (has_nav_menu('main_menu')) {
                                    cleenday_main_menu('main_menu', null);
                                } ?>
                            </nav><?php
                        }?>
                    </div>
                </div>
            </div>
            </div><?php
        }

        /**
         * Build Header HTML
         */
        public function header_render_html()
        {
            $mobile_header_custom = Cleenday::get_option('mobile_header'); ?>
            <header class="wgl-theme-header<?php echo esc_attr($this->header_class()); ?>"><?php

                // Default header ?>
                <div class="wgl-site-header<?php echo (!empty($mobile_header_custom) ? ' mobile_header_custom' : ''); ?>">
                    <div class="container-wrapper"><?php
                    $this->build_header_layout(); ?>
                    </div>
                </div><?php

                // Sticky header
                get_template_part('templates/header/block', 'sticky');

                // Mobile header
                get_template_part('templates/header/block', 'mobile'); ?>
            </header><?php

            // Side panel
            get_template_part('templates/header/block', 'side_area');
        }

        /**
         * Get header Logotype
         */
        public function get_logo($menu_condition = false, $_prefix = '')
        {
            $location = $this->html_render;

            if (
                'custom' === $this->header_type
                && !empty($this->header_page_select_id)
                && did_action('elementor/loaded')
            ) {
                // Settings manager
                $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');

                // Settings model for header page
                $page_settings_model = $page_settings_manager->get_model($this->header_page_select_id);

                if ($page_settings_model->get_settings('use_custom'.$_prefix.'_logo')) {
                    $logo = $page_settings_model->get_settings('custom'.$_prefix.'_logo');

                    if ($page_settings_model->get_settings('enable'.$_prefix.'_logo_height')) {
                        $custom_size = $page_settings_model->get_settings('logo'.$_prefix.'_height');
                    }

                    if (!empty($logo)) $location = 'bottom';

                    $menu_condition = !empty($logo) ? false : true;
                }
            }

            new Cleenday_Get_Logo($location, $menu_condition, $logo ?? '', $custom_size ?? '');
        }

        /**
         * Get Header Search
         */
        public function search($html_render = '', $location = '')
        {
            $description = esc_html__('Type To Search', 'cleenday');
            $search_style = Cleenday::get_option('search_style') ?: 'standard';

            $render_search = true;
            if ('alt' === $search_style) {
                if ('sticky' != $this->html_render) {
                    $render_search = true;
                } else {
                    $render_search = false;
                }
            }
            if (
                'alt' === $search_style
                && self::$search_form_counter > 0
                && 'mobile' !== $this->html_render
            ) {
                $render_search = false;
            }

            $search_class = ' search_' . $search_style;

            $customize = Cleenday::get_option('bottom_header_item_search_custom');
            $customize = empty($customize) ? 'def' : 'color';

            $text_color = Cleenday::get_option('bottom_header_item_search_color_txt')['rgba'] ?? '';
            $text_color_hover = Cleenday::get_option('bottom_header_item_search_hover_color_txt')['rgba'] ?? '';

            $search_css_id = uniqid('cleenday_search_');

            // Start Custom CSS
            $styles = '';
            if ('mobile' !== $this->html_render) {
                ob_start();
                if ('color' == $customize) {
                    echo "#$search_css_id:hover {
                        color: " . ($text_color_hover ? esc_attr($text_color_hover) : 'transparent') . "  !important;
                    }";
                }
                $styles .= ob_get_clean();
            }

            // Register css
            if (!empty($styles)) {
                Cleenday::enqueue_css($styles);
            }

            unset($this->render_attributes);

            $this->add_render_attribute('search', 'class', [
                'wgl-search',
                'elementor-search',
                'header_search-button-wrapper'
            ]);

            $this->add_render_attribute('search', 'role', 'button');

            if (
                'mobile' !== $this->html_render
                && 'color' === $customize
            ) {
                $this->add_render_attribute('search', 'style', [
                    'color: ' . (!empty($text_color) ? esc_attr($text_color) : 'transparent') . ';'
                ]);
            }

            $this->add_render_attribute('search', 'id', $search_css_id); ?>

            <div class="header_search<?php echo esc_attr($search_class); ?>" <?php echo Cleenday::render_html($this->row_style_height($location)); ?>>
                <div <?php echo Cleenday::render_html($this->get_render_attribute_string('search')); ?>>
                    <div class="header_search-button flaticon-null-2"></div>
                    <div class="header_search-close flaticon-close"></div>
                </div><?php

                if ($render_search) { ?>
                    <div class="header_search-field"><?php
                        if ('alt' === $search_style) { ?>
                            <div class="header_search-wrap">
                                <div class="cleenday_module_double_headings">
                                    <h3 class="header_search-heading_description heading_title"><?php
                                        echo apply_filters('cleenday/search/description', $description); ?>
                                    </h3>
                                </div>
                                <div class="header_search-close flaticon-close"></div>
                            </div><?php
                        } else { ?>
	                        <div class="header_search-close flaticon-close"></div><?php
                        }
                        echo get_search_form(false); ?>
                    </div><?php
                }?>
            </div><?php
        }

        /**
        * Get Side Panel Icon
        */
        public function get_side_panel($location, $section)
        { ?>
            <div class="side_panel" <?php echo Cleenday::render_html($this->row_style_height($location)); ?>>
                <div class="side_panel_inner" <?php echo Cleenday::render_html($this->side_panel_style_icon()); ?>>
                    <a href="#" class="side_panel-toggle">
                        <span class="side_panel-toggle-inner">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </a>
                </div>
            </div><?php
        }

        /**
         * Get Header Login
         */
        public function login_in($location, $section)
        {
            $link = get_permalink( get_option('woocommerce_myaccount_page_id') );
            $query_args = [
                'action' => urlencode('signup_form'),
            ];
            $url = add_query_arg($query_args, $link);

            $link_logout = wp_logout_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>
            <div class='login-in woocommerce'<?php Cleenday::render_html($this->row_style_height($location)); ?>>
                <span class="login-in_wrapper"><?php
                if (is_user_logged_in()) {?>
                    <a class='login-in_link-logout' href='<?php echo esc_url($link_logout); ?>'><?php esc_html_e('Logout', 'cleenday'); ?></a><?php
                } else { ?>
                    <a class='login-in_link' href='<?php echo esc_url_raw($url); ?>'><?php esc_html_e('Login', 'cleenday'); ?></a><?php
                } ?>
                </span>
	            <div class="login-modal wgl_modal-window">
		            <div class="overlay"></div>
		            <div class="modal-dialog modal_window-login">
			            <div class="modal_header"></div>
			            <div class="modal_content"><?php
	                        wc_get_template('myaccount/form-login.php'); ?>
			            </div>
		            </div>
	            </div>
	        </div><?php
        }

        /**
         * Get Header Cart
         */
        public function cart($location, $section)
        { ?>
            <div class="wgl-mini-cart_wrapper">
                <div class="mini-cart woocommerce"<?php echo Cleenday::render_html($this->row_style_height($location)); ?>><?php
                    echo Cleenday::render_html($this->icon_cart()), self::woo_cart(); ?>
                </div>
            </div><?php
        }

        public function icon_cart()
        {
            $customize = Cleenday::get_option('bottom_header_cart_custom');
            $customize = empty($customize) ? 'def' : 'color';

            $text_color_idle = Cleenday::get_option('bottom_header_cart_color_txt')['rgba'] ?? '';
            $text_color_hover = Cleenday::get_option('bottom_header_cart_hover_color_txt')['rgba'] ?? '';

            $cart_css_id = uniqid('cleenday_woo_');

            // Enqueue CSS
            if (
                'mobile' !== $this->html_render
                && 'color' === $customize
            ) {
                ob_start();
                if ($text_color_idle) {
                    echo "#$cart_css_id {
                        color: ", esc_attr($text_color_idle), ";
                    }";
                }
                if ($text_color_hover) {
                    echo "#$cart_css_id:hover {
                        color: ", esc_attr($text_color_hover), ";
                    }";
                }
                $styles = ob_get_clean();
            }
            if (!empty($styles)) {
                Cleenday::enqueue_css($styles);
            }

            unset($this->render_attributes);

            $this->add_render_attribute('cart', 'id', $cart_css_id);
            $this->add_render_attribute('cart', 'class', 'wgl-cart woo_icon elementor-cart');
            $this->add_render_attribute('cart', 'role', 'button');
            $this->add_render_attribute('cart', 'title', esc_attr__('Click to open Shopping Cart', 'cleenday'));

            ob_start(); ?>
            <a <?php echo Cleenday::render_html($this->get_render_attribute_string('cart')); ?>>
            <span class="woo_mini-count">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="510px" height="510px" viewBox="0 0 510 510" xml:space="preserve">
					<path d="M153,408c-28.05,0-51,22.95-51,51s22.95,51,51,51s51-22.95,51-51S181.05,408,153,408z M0,0v51h51l91.8,193.8L107.1,306
			c-2.55,7.65-5.1,17.85-5.1,25.5c0,28.05,22.95,51,51,51h306v-51H163.2c-2.55,0-5.1-2.55-5.1-5.1v-2.551l22.95-43.35h188.7
			c20.4,0,35.7-10.2,43.35-25.5L504.9,89.25c5.1-5.1,5.1-7.65,5.1-12.75c0-15.3-10.2-25.5-25.5-25.5H107.1L84.15,0H0z M408,408
			c-28.05,0-51,22.95-51,51s22.95,51,51,51s51-22.95,51-51S436.05,408,408,408z"/>
				</svg><?php
                echo WC()->cart->cart_contents_count > 0 ? '<span>' . esc_html(WC()->cart->cart_contents_count) . '</span>' : ''; ?>
            </span>
            </a><?php

            return ob_get_clean();
        }

        public static function woo_cart()
        {
            ob_start(); ?>
                <div class="wgl-woo_mini_cart"><?php
                    woocommerce_mini_cart(); ?>
                </div><?php

            return ob_get_clean();
        }

        public function in_array_r($needle, $haystack, $strict = false)
        {
            if (is_array($haystack)) {
                foreach ($haystack as $item) {
                    if (
                        ($strict ? $item === $needle : $item == $needle)
                        || (is_array($item) && $this->in_array_r($needle, $item, $strict))
                    ) {
                        return true;
                    }
                }
            }

            return false;
        }

    }

    new Cleenday_Get_Header();
}
