<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/templates/wgl-portfolio.php.
*/
namespace WglAddons\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use Cleenday_Theme_Helper;
use Cleenday_Dynamic_Styles as Styles;
use WglAddons\Includes\{
    Wgl_Loop_Settings,
    Wgl_Elementor_Helper,
    Wgl_Carousel_Settings
};

/**
 * WGL Elementor Portfolio Template
 *
 *
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WglPortfolio
{
    private static $instance;

    public function render($_s, $self = false)
    {
        $this->item = $self;
	    $portfolio_layout = $_s['portfolio_layout'];

	    // * Build Query
        list($query_args) = Wgl_Loop_Settings::buildQuery($_s);

        $query_args['paged'] = get_query_var('paged') ?: 1;
        $query_args['post_type'] = 'portfolio';

        // * Add Query Not In Post in the Related Posts(Metaboxes)
        if (!empty($_s['featured_render'])) {
            $query_args['post__not_in'] = [get_the_id()];
        }

        $query_results = new \WP_Query($query_args);

        $_s['post_count'] = $this->post_count = $query_results->post_count;
        $_s['found_posts'] = $query_results->found_posts;
        $_s['query_args'] = $query_args;

        // * Unique id
	    $_s['item_id'] = $item_id = uniqid('portfolio_module_');

        // Metaxobes Related Items
        if (!empty($_s['featured_render'])) {
            $_s['portfolio_layout'] = 'related';
        }
        if (!empty($_s['featured_render']) && !empty($_s['mb_pf_carousel_r'])) {
            $_s['portfolio_layout'] = 'carousel';
        }

        if (
            !empty($_s['show_filter'])
            || 'masonry2' === $_s['portfolio_layout']
            || 'masonry3' === $_s['portfolio_layout']
            || 'masonry4' === $_s['portfolio_layout']
        ) {
            $portfolio_layout = 'masonry';
        }

        // Classes
        $container_classes = $_s['grid_gap'] == '0px' ? ' no_gap' : '';
        $container_classes .= $_s['add_animation'] ? ' appear-animation' : '';
        $container_classes .= $_s['add_animation'] && !empty($_s['appear_animation'] )? ' anim-' . $_s['appear_animation'] : '';

        $out = '<section class="wgl_cpt_section">';
        $out .= '<div class="wgl-portfolio" id="' . esc_attr($item_id) . '">';

        wp_enqueue_script('imagesloaded');
        if ($_s['add_animation']) {
            wp_enqueue_script('jquery-appear', get_template_directory_uri() . '/js/jquery.appear.js');
        }
        if ('masonry' === $portfolio_layout) {
            wp_enqueue_script('isotope', WGL_ELEMENTOR_ADDONS_URL . 'assets/js/isotope.pkgd.min.js', ['imagesloaded']);
        }

        if ($_s['show_filter']) {
	        wp_enqueue_script('wgl-swiper', get_template_directory_uri() . '/js/swiper-bundle.min.js');

	        $query_args['filter_all_text'] = $_s['filter_all_text'];
            $filter_class = $portfolio_layout != 'carousel' ? 'isotope-filter' : '';
            $filter_class .= $_s['filter_align'] ? ' filter-' . $_s['filter_align'] : '';
            $filter_class .= $_s['add_number_cats'] ? ' show_number_of_cats' : '';
            $out .= '<div class="wgl-filter_wrapper portfolio__filter ' . esc_attr($filter_class) . '">';
            $out .= '<div class="wgl-filter_swiper_wrapper">';
            $out .= '<div class="swiper-wrapper">';
            $out .= $this->getCategories($query_args);
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
        }

        $style_gap = !empty($_s['grid_gap']) ? ' style="margin-right:-' . ((int) $_s['grid_gap'] / 2) . 'px; margin-left:-' . ((int) $_s['grid_gap'] / 2) . 'px; margin-bottom:-' . $_s['grid_gap'] . ';"' : '';

	    // Custom styles
	    if ( 'inside_image' === $_s['info_position'] && 'offset' === $_s['info_anim'] && $svg_color = $_s['sec_overlay_svg_color']) {
		    $svg_code = "url(\"data:image/svg+xml; utf8, <svg version='1.1' xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' viewBox='0 0 297 97' style='enable-background:new 0 0 297 97;' xml:space='preserve' preserveAspectRatio='none'><path fill='".str_replace('#','%23',$svg_color)."' d='M52 69.78C49.39 70.1 46.77 70.4 44.16 70.78C41.55 71.16 43.16 96.78 43.66 96.72C122.71 85.53 202.88 94.52 281.94 83.33C283.94 83.05 284.1 57.61 282.45 57.39C268.53 55.58 254.6 54.04 240.65 52.69L294.72 48.95C296.72 48.82 296.72 23.08 295.23 23.01L259.5 21.4C260.1 13.83 259.7 0.589973 258.79 0.629973L9.99999 10.12C8.15999 10.2 7.88998 35.99 9.48998 36.06L67.89 38.7L1.61998 43.28C-0.380017 43.42 -0.270027 69.2 1.10997 69.22C18.0366 69.3733 35 69.56 52 69.78Z' /></svg>\")";

		    $styles = '.elementor-element-' . esc_attr($this->item->get_id() ) . ' .inside_image.offset_animation:before { background-image: '.$this->wgl_svg_to_data( $svg_code ).'; }';
		    Wgl_Elementor_Helper::enqueue_css( $styles, false );
	    }

	    $out .= '<div class="wgl-portfolio_wrapper">';
        $out .= '<div class="wgl-portfolio_container container-grid row '
            . esc_attr($this->row_class($_s, $portfolio_layout))
            . esc_attr($container_classes) . '" '
            . $style_gap
            . '>';
        $out .= $this->output_loop_query($query_results, $_s);
        $out .= '</div>';
        $out .= '</div>';

        wp_reset_postdata();

        if ('pagination' === $_s['navigation']) {
            global $paged;
            if (empty($paged)) {
                $paged = get_query_var('page') ?: 1;
            }

            $out .= Cleenday_Theme_Helper::pagination($query_results, $_s['nav_align']);
        }

        if (
            'load_more' === $_s['navigation']
            && ($_s['post_count'] < $_s['found_posts'])
        ) {
            $out .= $this->loadMore($_s, $_s['load_more_text']);
        }

        if (
            'infinite' === $_s['navigation']
            && ($_s['post_count'] < $_s['found_posts'])
        ) {
            $out .= $this->infinite_more($_s);
        }

        $out .= '</div>';
        $out .= '</section>';

        return $out;
    }

    public function output_loop_query($q, $_s)
    {
        $out = '';
        $count = 0;
        $i = 0;

        switch ($_s['portfolio_layout']) {
            default:
            case 'masonry4':
                $max_count = 6;
                break;

            case 'masonry2':
            case 'masonry3':
                $max_count = 8;
                break;
        }
        // Metaxobes Related Items
        if (!empty($_s['featured_render'])) {
            $_s['portfolio_layout'] = 'related';
        }
        if (!empty($_s['featured_render']) && !empty($_s['mb_pf_carousel_r'])) {
            $_s['portfolio_layout'] = 'carousel';
        }

        $per_page = $q->query['posts_per_page'];

        if ($q->have_posts()) :
            ob_start();
            if (
                'masonry2' === $_s['portfolio_layout']
                || 'masonry3' === $_s['portfolio_layout']
                || 'masonry4' === $_s['portfolio_layout']
            ) {
                echo '<div class="wgl-portfolio-list_item-size" style="width: 25%;"></div>';
            }

            while ($q->have_posts()) :
                $q->the_post();

                if ($count < $max_count) {
                    $count++;
                } else {
                    $count = 1;
                }

                $item_class = $this->grid_class($_s, $count);

                switch ($_s['portfolio_layout']) {
                    case 'single':
                        $this->wgl_portfolio_single_item($_s);
                        break;

                    default:
                        $i++;
                        if (
                            'custom_link' === $_s['navigation']
                            && 'below_items' === $_s['link_position']
                            && 1 === $i
                        ) {
                            $class = $this->grid_class($_s, $i, true);
                            $this->wgl_portfolio_item($_s, $class, $i, $_s['grid_gap'], true);
                        }

                        $this->wgl_portfolio_item($_s, $item_class, $count, $_s['grid_gap']);

                        if (
                            'custom_link' === $_s['navigation']
                            && 'after_items' === $_s['link_position']
                            && $i === $per_page
                        ) {
                            $class = $this->grid_class($_s, $i, true);
                            $this->wgl_portfolio_item($_s, $class, $i, $_s['grid_gap'], true);
                        }
                        break;
                }

            endwhile;
            $render = ob_get_clean();

            $out .= $_s['portfolio_layout'] === 'carousel' ? $this->wgl_portfolio_carousel_item($_s, $render) : $render;
        endif;

        return $out;
    }

    public function wgl_portfolio_carousel_item($_s, $items)
    {
	    $_s['extra_class'] = $_s['center_info'] ? ' center_info' : '';

        wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js');

        ob_start();
        echo Wgl_Carousel_Settings::init($_s, $items);

        return ob_get_clean();
    }

    private function row_class($_s, $pf_layout)
    {
        switch ($pf_layout) {
            case 'carousel':
                $class = 'carousel';
                break;
            case 'related':
                $class = !empty($_s['mb_pf_carousel_r']) ? 'carousel' : 'isotope';
                break;
            case 'masonry':
                $class = 'isotope';
                break;
            default:
                $class = 'grid';
                break;
        }
        if ($_s['items_per_line']) {
            $class .= ' col-' . $_s['items_per_line'];
        }

        return $class;
    }

    public function grid_class($_s, $count, $link = false)
    {
        $class = '';

        switch ($_s['portfolio_layout']) {
	        case 'masonry2':
		        if (1 == $count || 7 == $count) {
			        $class .= 'wgl_col-6 square';
		        } else if (2 == $count || 6 == $count) {
			        $class .= 'wgl_col-3 vertical';
		        } else {
			        $class .= 'wgl_col-3';
		        }
		        break;

	        case 'masonry3':
		        if (1 == $count || 6 == $count) {
			        $class .= 'wgl_col-6 square';
		        } else if (2 == $count || 5 == $count) {
			        $class .= 'wgl_col-6 horizontal';
		        } else {
			        $class .= 'wgl_col-3';
		        }
		        break;

	        case 'masonry4':
		        if (1 == $count || 6 == $count) {
			        $class .= 'wgl_col-6 horizontal';
		        } else {
			        $class .= 'wgl_col-3';
		        }
		        break;
        }

        if (!$link) {
            $class .= $this->post_cats_class();
        }

        return $class;
    }

    private function post_cats_links($cat)
    {
        if (!$cat) {
            return false;
        }

        $p_cats = wp_get_post_terms(get_the_id(), 'portfolio-category');
        $p_cats_str = $p_cats_links = '';
        if (!empty($p_cats)) {
            $p_cats_links = '<span class="post_cats">';
            for ($i = 0, $count = count($p_cats); $i < $count; $i++) {
                $p_cat_term = $p_cats[$i];
                $p_cat_name = $p_cat_term->name;
                $p_cats_str .= ' ' . $p_cat_name;
                $p_cats_link = get_category_link($p_cat_term->term_id);
                $p_cats_links .= '<a href=' . esc_html($p_cats_link) . ' class="portfolio-category">' . esc_html($p_cat_name) . '</a>';
            }
            $p_cats_links .= '</span>';
        }

        return $p_cats_links;
    }

    private function post_cats_class()
    {
        $p_cats = wp_get_post_terms(get_the_id(), 'portfolio-category');
        $p_cats_class = '';
        for ($i = 0, $count = count($p_cats); $i < $count; $i++) {
            $p_cat_term = $p_cats[$i];
            $p_cats_class .= ' ' . $p_cat_term->slug;
        }

        return $p_cats_class;
    }

    private function chars_count($cols = null)
    {
        switch ($cols) {
            case '1':
                $number = 300;
                break;
            default:
            case '2':
                $number = 145;
                break;
            case '3':
                $number = 70;
                break;
            case '4':
                $number = 55;
                break;
        }

        return $number;
    }

    private function post_content($_s)
    {

        if (!$_s['show_content']) {
            return false;
        }

        $post = get_post(get_the_id());

        $out = '';
        $chars_count = !empty($content_letter_count) ? $content_letter_count : $this->chars_count($_s['items_per_line']);
        $content = !empty($post->post_excerpt) ? $post->post_excerpt : $post->post_content;
        $content = preg_replace('~\[[^\]]+\]~', '', $content);
        $content = strip_tags($content);
        $content = Cleenday_Theme_Helper::modifier_character($content, $chars_count, '');

        if ($content) {
            $out .= '<div class="wgl-portfolio-item_content">';
            $out .= sprintf('<div class="content">%s</div>', $content);
            $out .= '</div>';
        }

        return $out;
    }

    public function wgl_portfolio_item(
	    $_s,
        $class,
        $count,
        $grid_gap,
        $custom_link = false
    ) {

        $post_meta = $this->post_cats_links($_s['show_meta_categories']);

        $wrapper_class = isset($_s['info_position']) ? ' ' . $_s['info_position'] : '';
        $wrapper_class .= $_s['gallery_mode'] ? ' gallery_mode' : '';
        if($_s['info_position'] === 'inside_image'){
	        $wrapper_class .= ' ' . $_s['info_anim'] . '_animation';

	        if ( 'outline' === $_s['info_anim'] || 'offset' === $_s['info_anim'] ){
		        $wrapper_class .= ' visibility_' . $_s['info_visibility_hybrid'];
	        }else{
		        $wrapper_class .= ' visibility_' . $_s['info_visibility'];
            }
        }

        $style_gap = !empty($grid_gap) ? ' style="padding-right:' . ((int) $grid_gap / 2) . 'px; padding-left:' . ((int) $grid_gap / 2) . 'px; padding-bottom:' . $grid_gap . '"' : '';

        $link_params['link_destination'] = $_s['link_destination'] ?? '';
        $link_params['link_target'] = $_s['link_target'] ?? '';
        $link_params['additional_class'] = ' portfolio_link';
        $link = $this->render_link($link_params);

	    echo '<article class="wgl-portfolio-list_item item ', esc_attr($class), '" ', $style_gap, '>';

        if ($custom_link) {
            $this->custom_link_item($_s);
        } else {
            echo '<div class="wgl-portfolio-item_wrapper', esc_attr($wrapper_class), '">';


	        echo 'offset' == $_s['info_anim'] ? '<div class="wgl-portfolio-item_offset">' : '';
            echo '<div class="wgl-portfolio-item_image">';

            $img_id = get_post_thumbnail_id(get_the_ID());
            $img_url = wp_get_attachment_image_url($img_id, 'full');
            if ($img_url) {
                $img_alt = trim(strip_tags(get_post_meta($img_id, '_wp_attachment_image_alt', true)));
                $img_dims = Wgl_Elementor_Helper::get_image_dimensions(
	                $_s['img_size_array'] ?: $_s['img_size_string'],
	                $_s['img_aspect_ratio'] ?: ''
                );

                echo self::getImgUrl($_s, $img_url, $count, $grid_gap, $img_dims, $img_alt);
            }

            if ('under_image' === $_s['info_position']) {
                echo '<div class="overlay"></div>';

                echo $_s['linked_image'] ? $link : '';
            }

            echo '</div>';

            if ($_s['gallery_mode']) {
                echo '<a',
                ' href="', esc_url($img_url), '"',
                ' class="overlay swipebox"',
                ' data-elementor-open-lightbox="yes"',
                ' data-elementor-lightbox-slideshow="'.esc_attr($_s['item_id']).'"',
                '>',
                '</a>';
            } else {
                $this->standard_mode_enabled($_s, $link, $post_meta);
            }

            if (
                'under_image' !== $_s['info_position']
                && 'sub_layer' !== $_s['info_anim']
                && !$_s['gallery_mode']
            ) {
                echo '<div class="overlay"></div>';
            }

            if ($_s['info_anim'] == 'sub_layer' && $_s['linked_image']) {
                echo $link;
            }

            echo $_s['info_anim'] == 'offset' ? '</div>' : '';

            echo '</div>';

        }

        echo '</article>';
    }

    public function custom_link_item($_s)
    {

        $this->item->add_render_attribute('link', 'class', 'wgl-portfolio_item_link');

        if (!empty($item_link['url'])) {
            $this->item->add_link_attributes('link', $item_link);
        }

        $wrapper_class = ' align_' . $_s['link_align'];

        echo '<div class="wgl-portfolio-link_wrapper', esc_attr($wrapper_class), '">',
            '<a ', $this->item->get_render_attribute_string('link'), '>',
            esc_html($_s['load_more_text']),
            '</a>',
        '</div>';
    }

    public function render_link($_s)
    {

        $href = $_s['href'] ?? get_permalink();
        $target = !empty($_s['link_target']) ? 'target="_blank"' : '';
        $additional_class = $_s['additional_class'] ?? '';
        $link_content = $_s['link_content'] ?? '';

        switch ($_s['link_destination']) {
            case 'popup':
                $attachment_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                $link = '<a href="' . $attachment_url . '" class="swipebox' . $additional_class . '" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="'.esc_attr($_s['item_id']).'">'
                    . $link_content
                    . '</a>';
                break;

            default:
            case 'single':
                $link = '<a href="' . esc_url($href) . '" ' . $target . ' class="single_link' . $additional_class . '">'
                    . $link_content
                    . '</a>';
                break;

            case 'custom':
                if (
                    class_exists('RWMB_Loader')
                    && rwmb_meta('mb_portfolio_link')
                    && !empty(rwmb_meta('portfolio_custom_url'))
                ) {
                    $href = rwmb_meta('portfolio_custom_url');
                }
                $link = '<a href="' . esc_url($href) . '" ' . $target . ' class="custom_link' . $additional_class . '">'
                    . $link_content
                    . '</a>';
                break;
        }

        return $link;
    }

    public function standard_mode_enabled($_s, $link, $post_meta)
    {

        $link_params['link_destination'] = $_s['link_destination'] ?? '';
        $link_params['link_target'] = $_s['link_target'] ?? '';
        $linked_title = $_s['linked_title'] ?? '';
        ?><div class="wgl-portfolio-item_description"><?php

        if ( $_s['show_portfolio_title']
            || $_s['post_meta']
            || $_s['show_content']
        ) { ?>
            <div class="portfolio__description"><?php

	        if ($post_meta) { ?>
		        <div class="portfolio__item-meta"><?php echo $post_meta; ?></div><?php
	        }

            if ($_s['show_portfolio_title']) {
                $link_params['link_content'] = get_the_title(); ?>
                <div class="portfolio-item__title">
                    <h4 class="title"><?php
                    echo $linked_title ? $this->render_link($link_params) : '<span>' . get_the_title() . '</span>'; ?>
                    </h4>
                </div><?php
            }

            if ($_s['show_content']) {
                echo $this->post_content($_s['params']);
            } ?>

            </div><?php
        }

        // Image link
        if (
            $_s['linked_image']
            && 'under_image' !== $_s['info_position']
            && 'sub_layer' !== $_s['info_anim']
        ) {
           echo $link;
        } ?>
        </div><?php
    }

    private function single_post_date()
    {
        if (rwmb_meta('mb_portfolio_single_meta_date') != 'default') {
            $date_enable = rwmb_meta('mb_portfolio_single_meta_date');
        } else {
            $date_enable = Cleenday_Theme_Helper::get_option('portfolio_single_meta_date');
        }

        if ($date_enable) {
            return '<span class="post_date"><i class="post_date-icon flaticon-calendar"></i>'
                . get_the_time('F')
                . ' '
                . get_the_time('d')
                . '</span>';
        }
	    return false;
    }

    private function single_post_likes()
    {
        if (
            Cleenday_Theme_Helper::get_option('portfolio_single_meta_likes')
            && function_exists('wgl_simple_likes')
        ) {
            return wgl_simple_likes()->get_likes_button(get_the_ID(), 0);
        }
        return false;
    }

    private function single_post_author()
    {
        if ( Cleenday_Theme_Helper::get_option('portfolio_single_meta_author')) {
            return '<span class="post_author">'
                . esc_html__('by ', 'cleenday-core')
                . '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">'
                . esc_html(get_the_author_meta('display_name'))
                . '</a>'
                . '</span>';
        }
	    return false;
    }

    private function single_post_comments()
    {
        if ( Cleenday_Theme_Helper::get_option('portfolio_single_meta_comments')) {
            $comments_num = get_comments_number(get_the_ID());

            return '<span class="comments_post"><a href="'.esc_url(get_comments_link()).'" title="'.esc_attr__('Leave a reply', 'cleenday-core').'"><i class="flaticon flaticon-chat"></i>'.esc_html($comments_num).'</a></span>';
        }
	    return false;
    }

    private function single_post_cats()
    {
        if (
            class_exists('RWMB_Loader')
            && rwmb_meta('mb_portfolio_single_meta_categories') != 'default'
        ) {
            $cats_enable = rwmb_meta('mb_portfolio_single_meta_categories');
        } else {
            $cats_enable = Cleenday_Theme_Helper::get_option('portfolio_single_meta_categories');
        }

        if ($cats_enable && $cats = wp_get_post_terms(get_the_id(), 'portfolio-category')) {
            $result = '<span class="post_categories">';
            for ($i = 0, $count = count($cats); $i < $count; $i++) {
                $term = $cats[$i];
                $name = $term->name;
                $post_cats_link = get_category_link($term->term_id);
                $result .= '<span><a href=' . esc_html($post_cats_link) . ' class="portfolio-category">' . esc_html($name) . '</a></span>';
            }
            $result .= '</span>';

            return $result;
        }
	    return false;
    }

    private function single_portfolio_info()
    {
        $mb_info = rwmb_meta('mb_portfolio_info_items');

        if (!$mb_info) {
	        return false;
        }
	    $p_info = '';

        for ($i = 0, $count = count($mb_info); $i < $count; $i++) {
            $info = $mb_info[$i];
            $name = !empty($info['name']) ? $info['name'] : '';
            $description = !empty($info['description']) ? $info['description'] : '';
            $link = !empty($info['link']) ? $info['link'] : '';

            if ($name && $description) {
                $p_info .= '<div class="portfolio__custom-meta">';
                $p_info .= '<h5>' . $name . '</h5>';
                $p_info .= $link ? '<a href="' . esc_url($link) . '">' : '';
                $p_info .= '<span>' . $description . '</span>';
                $p_info .= $link ? '</a>' : '';
                $p_info .= '</div>';
            }
        }

        return $p_info ?? '';
    }

    public function wgl_portfolio_single_item($parameters)
    {
        $social_share = $p_meta = $p_annotation = '';

        // MetaBoxes
        $p_id = get_the_ID();
        $featured_image = $mb_hide_all_meta = $mb_show_title = true;
        $featured_image_replace = false;
        $img_data = wp_get_attachment_image_src(get_post_thumbnail_id($p_id), 'full');
        $img_dims = [
            'width' => $img_data[1] ?? '1170',
            'height' => $img_data[2] ?? '650'
        ];

        $tags_enable = $shares_enable = '';
        if (class_exists('RWMB_Loader')) {
            $featured_image = Cleenday_Theme_Helper::get_mb_option('portfolio_featured_image_type', 'mb_portfolio_featured_image_conditional', 'custom');
            if ('replace' === $featured_image) {
                $featured_image_replace = Cleenday_Theme_Helper::get_mb_option('portfolio_featured_image_replace', 'mb_portfolio_featured_image_conditional', 'custom');
            }

            $mb_show_title = rwmb_meta('mb_portfolio_title');

            $mb_hide_all_meta = Cleenday_Theme_Helper::get_option('portfolio_single_meta');

            if (rwmb_meta('mb_portfolio_above_content_cats') == 'default') {
                $tags_enable = Cleenday_Theme_Helper::get_option('portfolio_above_content_cats');
            } else {
                $tags_enable = rwmb_meta('mb_portfolio_above_content_cats');
            }
            if (rwmb_meta('mb_portfolio_above_content_share') != 'default') {
                $shares_enable = rwmb_meta('mb_portfolio_above_content_share');
            } else {
                $shares_enable = Cleenday_Theme_Helper::get_option('portfolio_above_content_share');
            }
        }

        $post_date = $this->single_post_date();
        $post_comments = $this->single_post_comments();
        $post_cats = $this->single_post_cats();
        $post_author = $this->single_post_author();
        $post_likes = $this->single_post_likes();
        $portfolio_info = $this->single_portfolio_info();

        $meta_data = $post_author . $post_date . $post_comments;

        if (
            $featured_image_replace
            && 'custom' === rwmb_meta('mb_portfolio_featured_image_conditional')
        ) {
            $image_id = array_values( (array) $featured_image_replace );
            $image_id = $image_id[0]['ID'];

            $wp_get_attachment_url = wp_get_attachment_url($image_id);
        } else {
            $wp_get_attachment_url = wp_get_attachment_url(get_post_thumbnail_id($p_id));
        }

        // Shares
        if ($shares_enable && function_exists('wgl_theme_helper')) {
            ob_start();
                wgl_theme_helper()->render_post_list_share();
            $social_share = ob_get_clean();
        }

        // Featured image
        ob_start();
        if ('off' !== $featured_image) {
            echo '<div class="wgl-portfolio-item_image">',
                self::getImgUrl($parameters, $wp_get_attachment_url, false, false, $img_dims),
            '</div>';
        }
        $p_featured_image = ob_get_clean();

        // Title
        $p_title = $mb_show_title ? '<h1 class="portfolio-item__title">' . get_the_title() . '</h1>' : '';

        // Meta
        if (!$mb_hide_all_meta && $meta_data) {
            $p_meta = '<div class="meta-data">';
            $p_meta .= $meta_data;
            $p_meta .= '</div>';
        }

        // Custom meta fields
        if ($portfolio_info) {
            $p_annotation = '<div class="wgl-portfolio__item-info">'
                . '<div class="portfolio__custom-annotation">' . $portfolio_info . '</div>'
                . '</div>';
        }

        // Content
        $content = apply_filters('the_content', get_post_field('post_content', get_the_id()));

        // Tags
        $post_tags = $tags_enable ? $this->getTags('<div class="tagcloud-wrapper"><div class="tagcloud">', ' ', '</div></div>') : '';

        switch ( Cleenday_Theme_Helper::get_mb_option('portfolio_single_type_layout', 'mb_portfolio_post_conditional', 'custom')) {
            case '1':
                $layout_sequence = $post_cats;
                $layout_sequence .= $p_meta;
                $layout_sequence .= $p_title;
                $layout_sequence .= $p_annotation;
                $layout_sequence .= $p_featured_image;
                break;
            default:
            case '2':
                $layout_sequence = $p_featured_image;
                $layout_sequence .= $post_cats;
                $layout_sequence .= $p_meta;
                $layout_sequence .= $p_title;
                $layout_sequence .= $p_annotation;
                break;
        }

        // Render ?>
        <article class="wgl-portfolio-single_item">
        <div class="wgl-portfolio-item_wrapper">
            <div class="portfolio-item__meta-wrap">
                <?php echo $layout_sequence; ?>
            </div><?php

            if ($content) { ?>
                <div class="wgl-portfolio-item_content">
                    <div class="content">
                    <div class="wrapper"><?php
                    echo $content; ?>
                    </div>
                    </div>
                </div><?php
            }

            // Post_info
            if ($tags_enable || $shares_enable || $post_likes) { ?>
                <div class="single_post_info post_info-portfolio"><?php
                    echo $post_tags,
                    $social_share,
                    $post_likes; ?>
                </div>
                <div class="clear"></div><?php
            }?>
        </div>
        </article>
        <div class="post_info-divider"></div><?php
    }

    static public function getImgUrl(
        $_s,
        $url,
        $count,
        $grid_gap,
        $dims,
        $alt = ''
    ) {
        if (!$url) {
            return '';
        }

	    $elementor_container_width = ( new Styles )->get_elementor_container_width();
	    /* 30 - Default Elementor Columns Gap */
	    $full = apply_filters( 'elementor/templates/wgl-portfolio/img_size', (int)$elementor_container_width - 30 ) - (int)$grid_gap;
        $half = ($full / 2) - ((int)$grid_gap );

	    if ('masonry2' == $_s['portfolio_layout']) {
		    switch ($count) {
			    case '2':
			    case '6':
	                $dims = ['width' => $half, 'height' => $full];
				    break;
			    default:
                $dims = ['width' => $full, 'height' => $full];
		    }
	    } elseif ('masonry3' == $_s['portfolio_layout']) {
		    switch ($count) {
			    case '2':
			    case '5':
                    $dims = [ 'width' => $full, 'height' => $half ];
				    break;
			    default:
                $dims = ['width' => $full, 'height' => $full];
		    }
	    } elseif ('masonry4' == $_s['portfolio_layout']) {
		    switch ($count) {
			    case '1':
			    case '6':
                    $dims = ['width' => $full, 'height' => $half];
				    break;
			    default:
                    $dims = ['width' => $full, 'height' => $full];
		    }
	    }

	    $src = aq_resize($url, $dims['width'], $dims['height'], true, true, true) ?: $url;

	    return '<img src="' . esc_url($src) . '" alt="' . $alt . '">';
    }

    public function getTags(
        $before = null,
        $sep = ', ',
        $after = ''
    ) {
        if (is_null($before)) {
            $before = __('Tags: ', 'cleenday-core');
        }

        $the_tags = $this->get_the_tag_list($before, $sep, $after);

        return !is_wp_error($the_tags) ? $the_tags : false;
    }

	/**
	 * Filters the tags list for a given post.
	 *
	 * @param string $before
	 * @param string $sep
	 * @param string $after
	 *
	 * @return mixed|void
	 */
    private function get_the_tag_list(
        $before = '',
        $sep = '',
        $after = ''
    ) {
        global $post;

        return apply_filters(
            'the_tags',
            get_the_term_list(
                $post->ID,
                'portfolio_tag',
                $before,
                $sep,
                $after
            ),
            $before,
            $sep,
            $after,
            $post->ID
        );
    }

    public function getCategories($_s)
    {
        $data_category = $_s['tax_query'] ?? [];
        $include = $exclude = $id_list = [];

        if (!is_tax() && !empty($data_category[0])) {
            if ('IN' === $data_category[0]['operator']) {
                foreach ($data_category[0]['terms'] as $value) {
                    $idObj = get_term_by('slug', $value, 'portfolio-category');
                    $id_list[] = $idObj->term_id;
                }

                $include = implode(',', $id_list);
            } elseif ('NOT IN' === $data_category[0]['operator']) {
                foreach ($data_category[0]['terms'] as $value) {
                    $idObj = get_term_by('slug', $value, 'portfolio-category');
                    $id_list[] = $idObj->term_id;
                }

                $exclude = implode(',', $id_list);
            }
        }

        $cats = get_terms([
            'taxonomy' => 'portfolio-category',
            'include' => $include,
            'exclude' => $exclude,
            'hide_empty' => true
        ]);

        $out = '<a href="#" data-filter=".item"  class="swiper-slide active">' . $_s['filter_all_text'] . '<span class="number_filter"></span></a>';
        foreach ($cats as $cat) if ($cat->count > 0) {
            $out .= '<a class="swiper-slide" href="' . get_term_link($cat->term_id, 'portfolio-category') . '" data-filter=".' . $cat->slug . '">';
            $out .= $cat->name;
            $out .= '<span class="number_filter"></span>';
            $out .= '</a>';
        }

        return $out;
    }

    public function loadMore($_s, $load_more_text)
    {
        if (empty($load_more_text)) {
	        return false;
        }

        $uniq = uniqid();
        $ajax_data_str = htmlspecialchars(json_encode($_s), ENT_QUOTES, 'UTF-8');

        return '<div class="clear"></div>'
            . '<div class="load_more_wrapper">'
            . '<div class="button_wrapper">'
            . '<a href="#" class="load_more_item"><span>' . $load_more_text . '</span></a>'
            . '</div>'
            . '<form class="posts_grid_ajax">'
            . "<input type='hidden' class='ajax_data' name='{$uniq}_ajax_data' value='$ajax_data_str' />"
            . '</form>'
            . '</div>';

    }

    public function infinite_more($_s)
    {
        $uniq = uniqid();
        wp_enqueue_script('waypoints');
        $ajax_data_str = htmlspecialchars(json_encode($_s), ENT_QUOTES, 'UTF-8');

        return '<div class="clear"></div>'
            . '<div class="text-center load_more_wrapper">'
            . '<div class="infinity_item">'
            . '<span class="wgl-ellipsis">'
            . '<span></span><span></span>'
            . '<span></span><span></span>'
            . '</span>'
            . '</div>'
            . '<form class="posts_grid_ajax">'
            . "<input type='hidden' class='ajax_data' name='{$uniq}_ajax_data' value='${ajax_data_str}' />"
            . '</form>'
            . '</div>';
    }

	public function wgl_svg_to_data( $wgl_svg ) {
		return str_replace( [ '<', '>', '#' ], [ '%3C', '%3E', '%23' ], $wgl_svg );
	}

	public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
