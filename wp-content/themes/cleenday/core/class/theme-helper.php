<?php

defined('ABSPATH') || exit;

if (!class_exists('Cleenday_Theme_Helper')) {
    /**
     * Cleenday Theme Helper
     *
     *
     * @package cleenday\core\class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Cleenday_Theme_Helper
    {
        private static $instance;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Retrieves Redux option.
         *
         * @param string $name  Desired option name.
         * @return string|null  Option value or `null` if value wasn't set.
         */
        public static function get_option(String $name)
        {
            if (class_exists('Redux')) {
                // Customizer
                if (!empty($GLOBALS['cleenday_set'])) {
                    $theme_options = $GLOBALS['cleenday_set'];
                } else {
                    $theme_options = get_option('cleenday_set');
                }
            }

            if (empty($theme_options)) {
                $theme_options = get_option('cleenday_default_options');
            }

            return $theme_options[$name] ?? null;
        }

        /**
         * Retrieves Metabox option.
         *
         * Assumes that all RWMB options share same name
         * as their redux analogues, prefixed with `mb_` string.
         *
         * @param string $name              Desired option name.
         * @param string $dependency_key    Optional. Key of related metabox option,
         *                                  on which desired option depends.
         * @param string $dependency_value  Optional. Value of related metabox option,
         *                                  on which desired option depends.
         *
         * @return string rwmb value.
         * @return string redux value, if condition isn't met or rwmb value wasn't set.
         */
        public static function get_mb_option(
            String $name,
            $dependency_key = false,
	        $dependency_value = false
        ) {
	        $mb_option = self::get_option($name);

            if (
                class_exists('RWMB_Loader')
                && get_queried_object_id() !== 0
            ) {
                if (
                    $dependency_key
                    && $dependency_value
                ) {
                    if ($dependency_value == rwmb_meta($dependency_key)) {
                        $mb_option = rwmb_meta('mb_' . $name);
                    }
                } else {
                    $mb_option = rwmb_meta('mb_' . $name);
                }
            }

            return '' !== $mb_option
                ? $mb_option
                : self::get_option($name);
        }

        public static function bg_render(
            String $name,
            $dependency_key = false
        ) {
            $id = !is_archive() ? get_queried_object_id() : 0;

            if (
                class_exists('RWMB_Loader')
                && 0 !== $id
            ) {
                if (
                    $dependency_key
                && 'on' === rwmb_meta($dependency_key)
            ) {
                    $mb_image = rwmb_meta('mb_' . $name . '_bg');
                } elseif ('on' === rwmb_meta('mb_page_title_switch')) {
                    $mb_image = rwmb_meta('mb_page_title_bg');
                }
            }

            $redux_image = self::get_option($name . '_bg_image');

            $src = !empty($mb_image['image'])
                ? $mb_image['image']
                : ($redux_image['background-image'] ?? '');

            $repeat = !empty($mb_image['repeat'])
                ? $mb_image['repeat']
                : ($redux_image['background-repeat'] ?? '');

            $size = !empty($mb_image['size'])
                ? $mb_image['size']
                : ($redux_image['background-size'] ?? '');

            $attachment = !empty($mb_image['attachment'])
                ? $mb_image['attachment']
                : ($redux_image['background-attachment'] ?? '');

            $position = !empty($mb_image['position'])
                ? $mb_image['position']
                : ($redux_image['background-position'] ?? '');

            // Collect attributes
            if ($src) {
                $style = 'background-image: url(' . esc_url($src) . ');';
                $style .= !empty($size) ? ' background-size:' . esc_attr($size) . ';' : '';
                $style .= !empty($repeat) ? ' background-repeat:' . esc_attr($repeat) . ';' : '';
                $style .= !empty($attachment) ? ' background-attachment:' . esc_attr($attachment) . ';' : '';
                $style .= !empty($position) ? ' background-position:' . esc_attr($position) . ';' : '';
            }

            return $style ?? '';
        }

        public static function preloader()
        {
            if (!self::get_option('preloader')) {
                return;
            }

            $wrapper_bg = self::get_option('preloader_background');
            $element_bg = self::get_option('preloader_color'); ?>
            <div id="preloader-wrapper" <?php echo (!empty($wrapper_bg) ? 'style="background-color: ' . esc_attr($wrapper_bg) . ';"' : ''); ?>>
                <div class="preloader-container" <?php echo (!empty($element_bg) ? 'style="color: ' . esc_attr($element_bg) . ';"' : ''); ?>>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div><?php
        }

        public static function pagination($query = false, $alignment = 'left')
        {
            if ($query != false) {
                $wp_query = $query;
            } else {
                global $paged, $wp_query;
            }
            if (empty($paged)) {
                $query_vars = $wp_query->query_vars;
                $paged = $query_vars['paged'] ?? 1;
            }

            $max_page = $wp_query->max_num_pages;

            if ($max_page < 2) {
                // Abort, if no need for pagination
                return false;
            }

            switch ($alignment) {
                case 'right':
                    $class_alignment = ' aright';
                    break;
                case 'center':
                    $class_alignment = ' acenter';
                    break;
                default:
                case 'left':
                    $class_alignment = '';
                    break;
            }

            $big = 999999999;

            $test_pag = paginate_links([
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'type' => 'array',
                'current' => max(1, $paged),
                'total' => $max_page,
                'prev_text' => '<i class="flaticon-arrow"></i>',
                'next_text' => '<i class="flaticon-arrow"></i>',
            ]);
            $test_comp = '';
            foreach ($test_pag as $value) {
                $test_comp .= "<li class='page'>{$value}</li>";
            }

            return '<ul class="wgl-pagination' . esc_attr($class_alignment) . '">' . $test_comp . '</ul>';
        }

        /**
         * The formatted output of a list of pages.
         */
        public static function link_pages()
        {
            return wp_link_pages([
                'before' => '<div class="wgl-pagination">',
                'after' => '</div>',
                'link_before' => '<span class="page-link post-page-link">',
                'link_after' => '</span>'
            ]);
        }

        public static function hexToRGB($hex = "#ffffff")
        {
            if (strlen($hex) < 1) {
                $hex = "#ffffff";
            }

            $color['r'] = hexdec(substr($hex, 1, 2));
            $color['g'] = hexdec(substr($hex, 3, 2));
            $color['b'] = hexdec(substr($hex, 5, 2));

            return $color['r'] . "," . $color['g'] . "," . $color['b'];
        }

	    /**
	     * Given a HEX string returns a HSL array equivalent.
	     *
	     * @param string $color
	     *
	     * @return array HSL associative array
	     * @throws Exception
	     */
        public static function hexToHsl($color)
        {
            // Sanity check
            $color = self::_checkHex($color);

            // Convert HEX to DEC
            $R = hexdec($color[0].$color[1]);
            $G = hexdec($color[2].$color[3]);
            $B = hexdec($color[4].$color[5]);

            $HSL = [];

            $var_R = ($R / 255);
            $var_G = ($G / 255);
            $var_B = ($B / 255);

            $var_Min = min($var_R, $var_G, $var_B);
            $var_Max = max($var_R, $var_G, $var_B);
            $del_Max = $var_Max - $var_Min;

            $L = ($var_Max + $var_Min)/2;

            if ($del_Max == 0) {
                $H = 0;
                $S = 0;
            } else {
                if ($L < 0.5) $S = $del_Max / ($var_Max + $var_Min);
                else $S = $del_Max / (2 - $var_Max - $var_Min);

                $del_R = ( ( ($var_Max - $var_R) / 6 ) + ($del_Max / 2) ) / $del_Max;
                $del_G = ( ( ($var_Max - $var_G) / 6 ) + ($del_Max / 2) ) / $del_Max;
                $del_B = ( ( ($var_Max - $var_B) / 6 ) + ($del_Max / 2) ) / $del_Max;

                if ($var_R == $var_Max) $H = $del_B - $del_G;
                else if ($var_G == $var_Max) $H = (1 / 3) + $del_R - $del_B;
                else if ($var_B == $var_Max) $H = (2 / 3) + $del_G - $del_R;

                if ($H < 0) $H++;
                if ($H > 1) $H--;
            }

            $HSL['H'] = ($H*360);
            $HSL['S'] = $S;
            $HSL['L'] = $L;

            return $HSL;
        }

        /**
         * You need to check if you were given a good hex string
         *
         * @param string $hex
         * @return string Color
         * @throws Exception "Bad color format"
         */
        private static function _checkHex($hex)
        {
            // Strip # sign if present
            $color = str_replace('#', '', $hex);

            // Make sure it's 6 digits
            if (strlen($color) == 3) {
                $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
            } else if (strlen($color) != 6) {
                throw new Exception( esc_html__('HEX color needs to be 6 or 3 digits long', 'cleenday') );
            }

            return $color;
        }

        /**
         * Given a Hue, returns corresponding RGB value
         *
         * @param int $v1
         * @param int $v2
         * @param int $vH
         * @return int
         */
        private static function _huetorgb($v1, $v2, $vH)
        {
            if ($vH < 0) $vH += 1;
            if ($vH > 1) $vH -= 1;

            if ((6 * $vH) < 1) {
                return ($v1 + ($v2 - $v1) * 6 * $vH);
            }

            if ((2 * $vH) < 1) return $v2;

            if ((3 * $vH) < 2) {
                return ($v1 + ($v2 - $v1) * ((2 / 3) - $vH) * 6);
            }

            return $v1;
        }

        /**
         *  Given a HSL associative array returns the equivalent HEX string
         *
         * @param array $hsl
         * @return string HEX string
         * @throws Exception "Bad HSL Array"
         */
        public static function hslToHex($hsl = [])
        {
            list($H, $S, $L) = [$hsl['H'] / 360, $hsl['S'], $hsl['L']];

            if ($S == 0) {
                $r = $L * 255;
                $g = $L * 255;
                $b = $L * 255;
            } else {
                if ($L < 0.5) {
                    $var_2 = $L * (1 + $S);
                } else {
                    $var_2 = ($L + $S) - ($S * $L);
                }

                $var_1 = 2 * $L - $var_2;

                $r = round(255 * self::_huetorgb($var_1, $var_2, $H + (1 / 3)));
                $g = round(255 * self::_huetorgb($var_1, $var_2, $H));
                $b = round(255 * self::_huetorgb($var_1, $var_2, $H - (1 / 3)));
            }

            // Convert to hex
            $r = dechex($r);
            $g = dechex($g);
            $b = dechex($b);

            // Make sure we get 2 digits for decimals
            $r = (strlen('' . $r) === 1) ? '0' . $r : $r;
            $g = (strlen('' . $g) === 1) ? '0' . $g : $g;
            $b = (strlen('' . $b) === 1) ? '0' . $b : $b;

            return $r . $g . $b;
        }

	    /**
	     * Given a HEX value, returns a darker color. If no desired amount provided, then the color halfway between
	     * given HEX and black will be returned.
	     *
	     * @param int $amount
	     *
	     * @return string Darker HEX value
	     * @throws Exception
	     */
        public static function shaded($_hsl, $amount)
        {
            // Darken
            $darkerHSL = self::_shaded($_hsl, $amount);
            // Return as HEX
            return '#'.self::hslToHex($darkerHSL);
        }

        private static function _shaded($hsl, $amount)
        {
            // Check if we were provided a number
            if ($amount) {
                $hsl['L'] = ($hsl['L'] * 100) - $amount;
                $hsl['L'] = ($hsl['L'] < 0) ? 0:$hsl['L']/100;
            } else {
                // We need to find out how much to darken
                $hsl['L'] = $hsl['L']/2 ;
            }

            return $hsl;
        }

        /**
         * @link https://github.com/opensolutions/smarty/blob/master/plugins/modifier.truncate.php
         */
        public static function modifier_character(
            $string,
            $length = 80,
            $etc = '... ',
            $break_words = false
        ) {
            if ($length == 0) {
                return '';
            }

            if (mb_strlen($string, 'utf8') > $length) {
                if (!$break_words) {
                    $string = preg_replace('/\s+\S+\s*$/su', '', mb_substr($string, 0, $length + 1, 'utf8'));
                }

                return mb_substr($string, 0, $length, 'utf8') . $etc;
            } else {
                return $string;
            }
        }

        public static function load_more(
            $query = false,
            $load_more_text = ''
        ) {
            $load_more_text = !empty($load_more_text) ? $load_more_text : esc_html__('Load More', 'cleenday');

            $uniq = uniqid();
            $ajax_data_str = htmlspecialchars(json_encode($query), ENT_QUOTES, 'UTF-8'); ?>
            <div class="clear"></div>
                <div class="load_more_wrapper">
                <div class="button_wrapper">
                    <button class="load_more_item">
                        <span><?php echo esc_html($load_more_text); ?></span>
                    </button>
                </div>
                <form class="posts_grid_ajax">
                    <input type='hidden' class='ajax_data' name='<?php echo esc_attr($uniq); ?>"_ajax_data' value='<?php echo esc_attr($ajax_data_str); ?>' />
                </form>
            </div><?php
        }

        public static function render_html($args)
        {
            return $args ?? '';
        }

        public static function in_array_r($needle, $haystack, $strict = false)
        {
            if (is_array($haystack)) {
                foreach ($haystack as $item) {
                    if (
                        ($strict ? $item === $needle : $item == $needle)
                        || (is_array($item) && self::in_array_r($needle, $item, $strict))
                    ) {
                        return true;
                    }
                }
            }

            return false;
        }

        public static function get_sidebar_data($name = 'page')
        {
	        $sb_data = [];
	        $id = get_the_ID();
	        if ( is_front_page() && is_home() ) {
		        $id = 0;
		        $name = 'blog_list';
	        } elseif ( is_front_page()){
	        } elseif ( is_home()){
		        $name = 'blog_list';
		        $id = (int) get_option( 'page_for_posts' );
	        }

	        $layout = self::get_option($name . '_sidebar_layout');
            $sidebar = self::get_option($name . '_sidebar_def');
            $sidebar_width = self::get_option($name . '_sidebar_def_width');
            $sticky_sidebar = self::get_option($name . '_sidebar_sticky');
            $sidebar_gap = self::get_option($name . '_sidebar_gap');

            if (!class_exists('Cleenday_Core')) {
                list(
                    $sidebar,
                    $layout,
                    $sidebar_width
                ) = self::get_sidebar_defaults();
            }

	        $page_id = !is_archive() ? get_queried_object_id() : 0;
            if (
                class_exists('RWMB_Loader')
                && 0 !== $page_id
            ) {
	            $mb_layout = rwmb_meta('mb_page_sidebar_layout', array(), $id);
	            if (!empty($mb_layout) && $mb_layout != 'default') {
		            $layout = $mb_layout;
		            $sidebar = rwmb_meta('mb_page_sidebar_def', array(), $id);
		            $sidebar_width = rwmb_meta('mb_page_sidebar_def_width', array(), $id);
		            $sticky_sidebar = rwmb_meta('mb_sticky_sidebar', array(), $id);
		            $sidebar_gap = rwmb_meta('mb_sidebar_gap', array(), $id);
	            }
            }

            if ($sticky_sidebar) {
                wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js');
                $sidebar_class = ' sticky-sidebar';
            }

            if (
                isset($sidebar_gap)
                && $sidebar_gap != 'def'
                && $layout != 'default'
            ) {
                $layout_pos = $layout == 'left' ? 'right' : 'left';
                $sidebar_style = 'style="padding-' . $layout_pos . ': ' . $sidebar_gap . 'px;"';
            }

            $column = 12;
            if ($layout == 'left' || $layout == 'right') {
                $column = (int) $sidebar_width;
            } else {
                $sidebar = '';
            }

            //* GET Params sidebar
            if (!empty($_GET['shop_sidebar'])) {
                $layout = $_GET['shop_sidebar'];
                $sidebar = 'shop_products';
                $column = 9;
            }

            if (!is_active_sidebar($sidebar)) {
                $column = 12;
                $sidebar = '';
                $layout = 'none';
            }

            if (
                is_active_sidebar($sidebar)
                && ($layout == 'left' || $layout == 'right')
            ) {
                $sb_data['column'] = $column;
                $sb_data['row_class'] = $layout != 'none' ? ' sidebar_' . esc_attr($layout) : '';
                $sb_data['container_class'] = $layout != 'none' ? ' wgl-content-sidebar' : '';
                $sb_data['layout'] = $layout;
                $sb_data['id'] = $sidebar;
                $sb_data['class'] = $sidebar_class ?? '';
                $sb_data['style'] = $sidebar_style ?? '';
            }

            return $sb_data ?? [];
        }

        public static function get_sidebar_defaults()
        {
            if (is_active_sidebar('sidebar_main-sidebar')) {
                $sidebar = 'sidebar_main-sidebar';
                $layout = 'right';
                $sidebar_width = 9;
            }

            if (
                class_exists('WooCommerce')
                && is_woocommerce()
            ) {
                if (is_shop()) {
                    if (is_active_sidebar('shop_products')) {
                        $sidebar = 'shop_products';
                        $layout = 'right';
                        $sidebar_width = 9;
                    } else {
                        $sidebar = '';
                        $layout = 'none';
                        $sidebar_width = 0;
                    }
                } elseif (
                    is_product()
                    && is_active_sidebar('shop_single')
                ) {
                    $sidebar = 'shop_single';
                    $layout = 'right';
                    $sidebar_width = 9;
                } else{
	                $sidebar = '';
	                $layout = 'none';
	                $sidebar_width = 0;
                }
            }

            return [
                $sidebar ?? '',
                $layout ?? 'none',
                $sidebar_width ?? 0
            ];
        }

        public static function render_sidebar(Array $sb_data)
        {
            $class = $sb_data['class'] ?? '';
            $class .= ' wgl_col-' . (12 - ((int) $sb_data['column'] ?? 0));

            echo '<div class="sidebar-container', $class, '" ', ($sb_data['style'] ?? ''), '>';

            if (is_active_sidebar($sb_data['id'])) { ?>
                <aside class="sidebar"><?php
                    dynamic_sidebar($sb_data['id']); ?>
                </aside><?php
            }

            echo '</div>';
        }

        public static function posted_meta_on()
        {
            global $post;

            printf(
                '<span><time class="entry-date published" datetime="%1$s">%2$s</time></span><span>' . esc_html__('Published in', 'cleenday') . ' <a href="%3$s" rel="gallery">%4$s</a></span>',
                esc_attr(get_the_date('c')),
                esc_html(get_the_date()),
                esc_url(get_permalink($post->post_parent)),
                esc_html(get_the_title($post->post_parent))
            );

            printf(
                '<span class="author vcard">%1$s</span>',
                sprintf(
                    '<a class="url fn n" href="%1$s">%2$s</a>',
                    esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                    esc_html(get_the_author())
                )
            );

            $metadata = wp_get_attachment_metadata();

            if ($metadata) {
                printf(
                    '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s" title="%2$s">%1$s %3$s &times; %4$s</a></span>',
                    esc_html_x('Full size', 'Used before full size attachment link.', 'cleenday'),
                    esc_url(wp_get_attachment_url()),
                    esc_attr(absint($metadata['width'])),
                    esc_attr(absint($metadata['height']))
                );
            }

            $kses_allowed_html = [
                'span' => ['id' => true, 'class' => true, 'style' => true],
                'br' => ['id' => true, 'class' => true, 'style' => true],
                'em' => ['id' => true, 'class' => true, 'style' => true],
                'b' => ['id' => true, 'class' => true, 'style' => true],
                'strong' => ['id' => true, 'class' => true, 'style' => true],
            ];

            edit_post_link(
                /* translators: %s: Name of current post */
                sprintf(
                    wp_kses(__('Edit<span class="screen-reader-text"> "%s"</span>', 'cleenday'), $kses_allowed_html),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
        }

        public static function enqueue_css($style)
        {
            ob_start();
            echo self::render_html($style);
            $css = ob_get_clean();
            $css = apply_filters('cleenday/enqueue_shortcode_css', $css, $style);
        }

        public static function render_html_attributes(array $attributes)
        {
            $rendered_attributes = [];

            foreach ($attributes as $attribute_key => $attribute_values) {
                if (is_array($attribute_values)) {
                    $attribute_values = implode(' ', $attribute_values);
                }

                $rendered_attributes[] = sprintf(
                    '%1$s="%2$s"',
                    $attribute_key,
                    esc_attr($attribute_values)
                );
            }

            return implode(' ', $rendered_attributes);
        }

        /**
         * Check licence activation
         */
        public static function wgl_theme_activated()
        {
            $licence_key = get_option('wgl_licence_validated');
            $licence_key = empty($licence_key) ? get_option(Wgl_Theme_Verify::get_instance()->item_id) : $licence_key;

            if (!empty($licence_key)) {
                return $licence_key;
            }

            return false;
        }
    }

    new Cleenday_Theme_Helper();
}
