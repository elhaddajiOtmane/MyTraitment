<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/templates/wgl-blog.php.
*/
namespace WglAddons\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WglAddons\Includes\{
    Wgl_Loop_Settings,
    Wgl_Carousel_Settings
};

/**
 * WGL Elementor Blog Template
 *
 *
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 */
class WGL_Blog
{
    private static $instance;
    private $attributes;
    private $query;

    public function render($attributes)
    {
        $this->attributes = $attributes;
        $this->query = $this->_formalize_query();

        if (!$this->query->have_posts()) {
            // Bailout, if nothing to render
            return;
        }

        wp_enqueue_script('imagesloaded');
        if ('masonry' === $attributes['blog_layout']) {
            wp_enqueue_script('isotope', WGL_ELEMENTOR_ADDONS_URL . 'assets/js/isotope.pkgd.min.js', ['imagesloaded']);
        }

        echo '<section class="wgl_cpt_section">';

        echo '<div class="blog-posts">';

            $this->_render_header_section();

            echo '<div class="container-grid row', $this->get_row_classes(), '">',
                $this->_get_posts_html(),
            '</div>';

        echo '</div>';

        $this->_render_navigation_section();

        echo '</section>';

        unset($wgl_blog_atts); //* clear global var
    }

    private function _formalize_query()
    {
        list($query_args) = Wgl_Loop_Settings::buildQuery($this->attributes);

        // Add Page to Query
        global $paged;
        if (empty($paged)) {
            $paged = get_query_var('page') ?: 1;
        }
        $query_args['paged'] = $paged;

        if ('none' == $this->attributes['navigation_type']) {
            // SQL optimization
            $query_args['no_found_rows'] = true;
        }

        $query_args['update_post_term_cache'] = false; // don't retrieve post terms
        $query_args['update_post_meta_cache'] = false; // don't retrieve post meta

        return Wgl_Loop_Settings::cache_query($query_args);
    }

    protected function _get_posts_html()
    {
        $_ = $this->attributes; // assign shorthand for attributes array

        $blog_defaults = [
            'query' => $this->query,
            'blog_layout' => '',
            'blog_columns' => '',
            'hide_media' => '',
            'media_link' => '',
            'hide_share' => $_['hide_share'],
            'hide_content' => '',
            'hide_blog_title' => '',
            'hide_all_meta' => '',
            'meta_author' => '',
            'meta_comments' => '',
            'meta_categories' => '',
            'meta_date' => '',
            'hide_views' => '',
            'hide_likes' => $_['hide_likes'],
            'read_more_hide' => $_['read_more_hide'],
            'read_more_text' => '',
            'content_letter_count' => '',
            'img_size' => $_['img_size_array'] ?: $_['img_size_string'],
            'img_aspect_ratio' => $_['img_aspect_ratio'],
            'heading_tag' => '',
            'items_load' => $_['items_load'],
            'load_more_text' => $_['load_more_text'],
        ];

        global $wgl_blog_atts;
        $wgl_blog_atts = array_merge($blog_defaults, array_intersect_key($this->attributes, $blog_defaults));

        ob_start();
            get_template_part('templates/post/post', 'standard');
        $posts_html = ob_get_clean();

        if ('carousel' === $_['blog_layout']) {
            $posts_html = $this->_apply_carousel_settings($posts_html);
        }

        return $posts_html;
    }

    protected function _apply_carousel_settings($posts_html)
    {
        $_ = $this->attributes; // assign shorthand for attributes array

        switch ($_['blog_columns']) {
            case '6':
                $_['items_per_line'] = 2;
                break;
            case '3':
                $_['items_per_line'] = 4;
                break;
            case '4':
                $_['items_per_line'] = 3;
                break;
            case '12':
                $_['items_per_line'] = 1;
                break;
            default:
                $_['items_per_line'] = 6;
                break;
        }

        return Wgl_Carousel_Settings::init($_, $posts_html);
    }

    protected function _render_header_section()
    {
        $module_title = $this->attributes['blog_title'] ?? '';
        $module_subtitle = $this->attributes['blog_subtitle'] ?? '';

        if (!$module_title && !$module_subtitle) {
            return;
        }

        echo '<div class="wgl_module_title item_title">';

        if ($module_title) {
            echo '<h3 class="cleenday_module_title blog_title">',
                wp_kses($module_title, self::_get_kses_allowed_html()),
            '</h3>';
        }

        if ($module_subtitle) {
            echo '<p class="blog_subtitle">',
                wp_kses($module_subtitle, self::_get_kses_allowed_html()),
            '</p>';
        }

        if (
            'carousel' === $this->attributes['blog_layout']
            && $this->attributes['use_navigation']
        ) {
            echo '<div class="carousel_arrows">',
                '<span class="left_slick_arrow"><span></span></span>',
                '<span class="right_slick_arrow"><span></span></span>',
            '</div>';
        }

        echo '</div>';
    }

    public function get_row_classes()
    {
        $_ = $this->attributes; // assign shorthand for attributes array
        $row_class = '';

        if ('carousel' === $_['blog_layout']) {
            $row_class = ' blog_carousel';

            !empty($_['blog_title']) && $row_class .= ' blog_carousel_title-arrow';
        }

        if (in_array($_['blog_layout'], ['grid', 'masonry'])) {
            switch ($_['blog_columns']) {
                case '12':
                    $row_class .= ' blog_columns-1';
                    break;
                case '6':
                    $row_class .= ' blog_columns-2';
                    break;
                case '4':
                    $row_class .= ' blog_columns-3';
                    break;
                case '3':
                    $row_class .= ' blog_columns-4';
                    break;
            }
            $row_class .= ' ' . $_['blog_layout'];
        }

        $row_class .= ' blog-style-standard';

        return $row_class;
    }

    protected function _render_navigation_section()
    {
        switch ($this->attributes['navigation_type']) {
            case 'pagination':
                echo \Cleenday_Theme_Helper::pagination($this->query);
                break;
            case 'load_more':
                $this->_get_load_more_button();
                break;
        }
    }

    protected function _get_load_more_button()
    {
        global $wgl_blog_atts;

        $wgl_blog_atts['post_count'] = $this->query->post_count;
        $wgl_blog_atts['query_args'] = $this->query->query_vars;
        $wgl_blog_atts['atts'] = $this->attributes;

        return \Cleenday_Theme_Helper::load_more($wgl_blog_atts, $this->attributes['load_more_text']);
    }

    private static function _get_kses_allowed_html()
    {
        return [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'b' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
        ];
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
