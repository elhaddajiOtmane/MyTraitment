<?php
defined('ABSPATH') || exit;

if (! class_exists('Cleenday_Header_Side_Area')) {
    class Cleenday_Header_Side_Area extends Cleenday_Get_Header
    {
        public function __construct()
        {
            $this->header_vars();

            $side_panel_enable = Cleenday_Theme_Helper::get_option('side_panel_enable');

            if (empty($side_panel_enable)) {
                return;
            }

            $pos = Cleenday_Theme_Helper::get_mb_option('side_panel_position', 'mb_customize_side_panel', 'custom');

            $content_type = Cleenday_Theme_Helper::get_mb_option('side_panel_content_type','mb_customize_side_panel','custom');
            $class = !empty($pos) ? ' side-panel_position_'.$pos : ' side-panel_position_right';

            // Get options
            $side_panel_spacing = Cleenday_Theme_Helper::get_mb_option('side_panel_spacing','mb_customize_side_panel','custom');

            $style = !empty($side_panel_spacing['padding-top']) ? ' padding-top:'.(int)$side_panel_spacing['padding-top'].'px;' : '' ;
            $style .= !empty($side_panel_spacing['padding-bottom']) ? ' padding-bottom:'.(int)$side_panel_spacing['padding-bottom'].'px;' : '' ;
            $style .= !empty($side_panel_spacing['padding-left']) ? ' padding-left:'.(int)$side_panel_spacing['padding-left'].'px;' : '' ;
            $style .= !empty($side_panel_spacing['padding-right']) ? ' padding-right:'.(int)$side_panel_spacing['padding-right'].'px;' : '' ;
            $style = !empty($style) ? ' style="'.$style.'"' : ''; ?>
            <div class="side-panel_overlay"></div>
            <section id="side-panel" class="side-panel_widgets<?php echo esc_attr($class); ?>" <?php echo Cleenday_Theme_Helper::render_html($this->side_panel_style()); ?>>
                <a href="#" class="side-panel_close"<?php echo Cleenday_Theme_Helper::render_html($this->side_panel_style_icon()); ?>>
                    <span class="side-panel_close_icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>
                <div class="side-panel_sidebar"<?php echo Cleenday_Theme_Helper::render_html($style); ?>><?php
                    switch ($content_type) {
                        case 'pages': $this->side_panel_get_pages(); break;
                        case 'widgets':
                        default: dynamic_sidebar( 'side_panel' ); break;
                    } ?>
                </div>
            </section><?php
        }

        public function side_panel_style()
        {
            $bg = Cleenday_Theme_Helper::get_option('side_panel_bg')['rgba'] ?? '';
            $color = Cleenday_Theme_Helper::get_option('side_panel_text_color')['rgba'] ?? '';
            $width = Cleenday_Theme_Helper::get_option('side_panel_width')['width'] ?? '';

            $align = Cleenday_Theme_Helper::get_mb_option('side_panel_text_alignment', 'mb_customize_side_panel', 'custom');
            $style = '';

            if (class_exists('RWMB_Loader') && $this->id !== 0) {
                $side_panel_switch = rwmb_meta('mb_customize_side_panel');
                if ($side_panel_switch === 'custom') {
                    $bg = rwmb_meta('mb_side_panel_bg');
                    $color = rwmb_meta('mb_side_panel_text_color');
                    $width = rwmb_meta('mb_side_panel_width');
                }
            }

            if ($bg) $style .= 'background-color: '.esc_attr($bg).';';
            if ($color) $style .= 'color: '.esc_attr($color).';';
            if ($width) $style .= 'width: '.esc_attr((int) $width).'px;';

            $style .= $align ? 'text-align: '.esc_attr($align).';' : 'text-align: center;';

            return $style ? ' style="'.$style.'"' : '';
        }

        public function side_panel_get_pages()
        {
            $page_select = Cleenday_Theme_Helper::get_mb_option('side_panel_page_select', 'mb_customize_side_panel', 'custom');

            if ($page_select) {
                $page_select = intval($page_select);

                if (class_exists('Polylang') && function_exists('pll_current_language')) {
					$currentLanguage = pll_current_language();
					$translations = PLL()->model->post->get_translations($page_select);
					
					$polylang_id = $translations[$currentLanguage] ?? '';
					$page_select = !empty($polylang_id) ? $polylang_id : $page_select;
				}

                if (class_exists('SitePress')) {
                    $page_select = wpml_object_id_filter($page_select, 'side-panel', false, ICL_LANGUAGE_CODE);
                }

                if (did_action('elementor/loaded')) {
                    echo \Elementor\Plugin::$instance->frontend->get_builder_content( $page_select );
                }
            }
        }
    }

    new Cleenday_Header_Side_Area();

}
