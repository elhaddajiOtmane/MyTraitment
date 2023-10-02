<?php

defined('ABSPATH') || exit; // Abort, if called directly.

/**
 * Class Side Panel
 * @package PostType
 */
class SidePanel
{
    /**
     * @var string
     *
     * Set post type params
     */
    private $type = 'side_panel';
    private $slug;
    private $name;
    private $plural_name;

    /**
     * Team constructor.
     *
     * When class is instantiated
     */
    public function __construct()
    {
        $this->name = esc_html__('Side Panel', 'cleenday-core');
        $this->plural_name = esc_html__('Side Panels', 'cleenday-core');
        $this->slug = 'side-panel';

        add_action('init', [$this, 'register']);

        add_filter('single_template', [$this, 'get_custom_pt_single_template']);
    }

    /**
     * Register post type
     */
    public function register()
    {
        $labels = [
            'name' => $this->name,
            'singular_name' => $this->name,
            'add_new' => sprintf( __('Add New %s', 'cleenday-core' ), $this->name ),
            'add_new_item' => sprintf( __('Add New %s', 'cleenday-core' ), $this->name ),
            'edit_item' => sprintf( __('Edit %s', 'cleenday-core'), $this->name ),
            'new_item' => sprintf( __('New %s', 'cleenday-core'), $this->name ),
            'all_items' => sprintf( __('All %s', 'cleenday-core'), $this->plural_name ),
            'view_item' => sprintf( __('View %s', 'cleenday-core'), $this->name ),
            'search_items' => sprintf( __('Search %s', 'cleenday-core'), $this->name ),
            'not_found' => sprintf( __('No %s found' , 'cleenday-core'), strtolower($this->name) ),
            'not_found_in_trash' => sprintf( __('No %s found in Trash', 'cleenday-core'), strtolower($this->name) ),
            'parent_item_colon' => '',
            'menu_name' => $this->name
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'rewrite' => ['slug' => $this->slug],
            'menu_position' => 11,
            'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
            'menu_icon' => 'dashicons-admin-page',
        ];

        register_post_type($this->type, $args);
    }


    public function wrapper_single_side_panel_open()
    {
        // Get options
        $pos = Cleenday_Theme_Helper::get_mb_option('side_panel_position', 'mb_customize_side_panel', 'custom');
        $side_panel_spacing = Cleenday_Theme_Helper::get_mb_option('side_panel_spacing', 'mb_customize_side_panel', 'custom');
        $icon_bg = Cleenday_Theme_Helper::get_option('bottom_header_side_panel_background') ?? '';
        $class = $pos ? ' side-panel_position_'.$pos : ' side-panel_position_right';

        $sb_style = !empty($side_panel_spacing['padding-top']) ? ' padding-top:'.(int)$side_panel_spacing['padding-top'].'px;' : '' ;
        $sb_style .= !empty($side_panel_spacing['padding-bottom']) ? ' padding-bottom:'.(int)$side_panel_spacing['padding-bottom'].'px;' : '' ;
        $sb_style .= !empty($side_panel_spacing['padding-left']) ? ' padding-left:'.(int)$side_panel_spacing['padding-left'].'px;' : '' ;
        $sb_style .= !empty($side_panel_spacing['padding-right']) ? ' padding-right:'.(int)$side_panel_spacing['padding-right'].'px;' : '' ;
        $sb_style = $sb_style ? ' style="'.$sb_style.'"' : '';

        $icon_style = $icon_bg ? 'background-color: '.esc_attr($icon_bg).';' : '';
        $icon_style = $icon_style ? ' style="'.$icon_style.'"' : ''; ?>
        <section id="side-panel" class="side-panel_widgets<?php echo esc_attr($class); ?>"<?php echo $this->side_panel_style(); ?>>
            <a href="#" class="side-panel_close"<?php echo $icon_style; ?>>
                <span class="side-panel_close_icon">
                    <span></span><span></span><span></span>
                </span>
            </a>
            <div class="side-panel_sidebar"<?php echo $sb_style; ?>><?php
    }


    public function wrapper_single_side_panel_close()
    { ?>
            </div>
        </section><?php
    }


    public function side_panel_style()
    {
        $bg = Cleenday_Theme_Helper::get_option('side_panel_bg')['rgba'] ?? '';
        $color = Cleenday_Theme_Helper::get_option('side_panel_text_color')['rgba'] ?? '';
        $width = Cleenday_Theme_Helper::get_option('side_panel_width')['width'] ?? '';

        $align = Cleenday_Theme_Helper::get_mb_option('side_panel_text_alignment', 'mb_customize_side_panel', 'custom');

        if (class_exists('RWMB_Loader')
            && get_queried_object_id() !== 0
            && rwmb_meta('mb_customize_side_panel') === 'custom'
        ) {
            $bg = rwmb_meta('mb_side_panel_bg');
            $color = rwmb_meta('mb_side_panel_text_color');
            $width = rwmb_meta('mb_side_panel_width');
        }

        $style = '';
        if ($bg) $style .= 'background-color: '.esc_attr($bg).';';
        if ($color) $style .= ' color: '.esc_attr($color).';';
        if ($width) $style .= ' width: '.esc_attr((int) $width ).'px;';
        $style .= $align ? ' text-align: '.esc_attr($align).';' : ' text-align: center;';
        $style .= ' position: static; height: 100vh;';

        return $style ? ' style="'.$style.'"' : '';
    }

    /**
    * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
    */
    function get_custom_pt_single_template($single_template)
    {
        global $post;

        if ($post->post_type == $this->type) {

            if (defined('ELEMENTOR_PATH')) {
                $elementor_template = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

                if (file_exists($elementor_template)) {
                    add_action('elementor/page_templates/canvas/before_content', [$this, 'wrapper_single_side_panel_open']);
                    add_action('elementor/page_templates/canvas/after_content', [$this, 'wrapper_single_side_panel_close']);

                    return $elementor_template;
                }
            }

            if (file_exists(get_template_directory().'/single-side-panel.php')) return $single_template;

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'side_panel/templates/single-side-panel.php';
        }

        return $single_template;
    }
}