<?php

defined('ABSPATH') || exit;

use WglAddons\Templates\WGL_Blog;
use Cleenday_Theme_Helper as Cleenday;

/**
 * The dedault template for single posts rendering
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cleenday
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

get_header();
the_post();

$sb = Cleenday::get_sidebar_data('single');
$column = $sb['column'] ?? '12';
$row_class = $sb['row_class'] ?? '';
$container_class = $sb['container_class'] ?? '';
$layout = $sb['layout'] ?? '';

$single_type = Cleenday::get_mb_option('single_type_layout', 'mb_post_layout_conditional', 'custom') ?: 2;

$row_class .= ' single_type-' . $single_type;

if ('3' === $single_type) { ?>
    <div class="post_featured_bg" style="background-color: <?php echo Cleenday::get_option('post_single_layout_3_bg_image')['background-color']; ?>"><?php
        get_template_part('templates/post/single/post', $single_type . '_image'); ?>
    </div><?php
}

//* Render ?>
<div class="wgl-container<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row<?php echo apply_filters('cleenday/row/class', $row_class); ?>">
	<div id="main-content" class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>"><?php

        get_template_part('templates/post/single/post', $single_type);

        //* Navigation
        get_template_part('templates/post/post-navigation');

        //* ↓ Related Posts
        $show_post_related = Cleenday::get_option('single_related_posts');

        if (
            class_exists('RWMB_Loader')
            && !empty($mb_blog_show_r = rwmb_meta('mb_blog_show_r'))
            && $mb_blog_show_r != 'default'
        ) {
            $show_post_related = $mb_blog_show_r === 'off' ? null : $mb_blog_show_r;
        }

        if (
            $show_post_related
            && class_exists('Cleenday_Core')
            && class_exists('\Elementor\Plugin')
        ) {
            global $wgl_related_posts;
            $wgl_related_posts = true;

            $mb_blog_carousel_r = Cleenday::get_mb_option('blog_carousel_r', 'mb_blog_show_r', 'custom');
            $mb_blog_title_r = Cleenday::get_mb_option('blog_title_r', 'mb_blog_show_r', 'custom');

            $mb_blog_cat_r = [];

            $cats = Cleenday::get_option('blog_cat_r');
            if (!empty($cats)) {
                $mb_blog_cat_r[] = implode([','], $cats);
            }

            if (
                class_exists('RWMB_Loader')
                && get_queried_object_id() !== 0
                && $mb_blog_show_r == 'custom'
            ) {
                $mb_blog_cat_r = get_post_meta(get_the_id(), 'mb_blog_cat_r');
            }

            $mb_blog_column_r = Cleenday::get_mb_option('blog_column_r', 'mb_blog_show_r', 'custom');
            $mb_blog_number_r = Cleenday::get_mb_option('blog_number_r', 'mb_blog_show_r', 'custom');

	        //* Get Cats_Slug
	        $posts_quantity_confirmed = false;
	        if ($categories = get_the_category()) {
		        $post_categ = $post_category_compile = '';
		        foreach ($categories as $category) {
			        $post_categ = $post_categ . $category->slug . ',';
			        if($category->count > 1){
				        $posts_quantity_confirmed = true;
			        }
		        }
		        $post_category_compile .= trim($post_categ, ',') ;

		        if (!empty($mb_blog_cat_r[0])) {
			        $categories = get_categories(['include' => $mb_blog_cat_r[0]]);
			        $post_categ = $post_category_compile = '';
			        foreach ($categories as $category) {
				        $post_categ = $post_categ . $category->slug . ',';
			        }
			        $post_category_compile .= trim($post_categ, ',');
		        }

		        $mb_blog_cat_r = $post_category_compile;
	        }

	        if ($posts_quantity_confirmed) :
	        //* Render ?>
            <section class="single related_posts">
                <div class="cleenday_module_title">
                    <h4><?php
                        echo !empty($mb_blog_title_r) ? esc_html($mb_blog_title_r) : esc_html__('Related Posts', 'cleenday'); ?>
                    </h4>
                </div><?php

                $related_posts_atts = [
                    'blog_layout' => !empty($mb_blog_carousel_r) ? 'carousel' : 'grid',
                    'navigation_type' => 'none',
                    'use_navigation' => null,
                    'hide_content' => true,
                    'hide_share' => false,
                    'hide_likes' => false,
                    'hide_views' => true,
                    'meta_author' => false,
                    'meta_comments' => '',
                    'read_more_hide' => false,
                    'read_more_text' => esc_html__('READ MORE', 'cleenday'),
                    'heading_tag' => 'h4',
                    'content_letter_count' => 90,
                    'img_size_string' => '840x620',
                    'img_size_array' => '',
                    'img_aspect_ratio' => '',
                    'items_load' => 4,
                    'load_more_text' => esc_html__('LOAD MORE', 'cleenday'),
                    'blog_columns' => $mb_blog_column_r ?? (($layout == 'none') ? '4' : '6'),
                    //* Carousel
                    'autoplay' => '',
                    'autoplay_speed' => 3000,
                    'slides_to_scroll' => '',
                    'infinite_loop' => false,
                    'use_pagination' => '',
                    'pag_type' => 'circle',
                    'pag_offset' => '',
                    'custom_resp' => true,
                    'resp_medium' => '',
                    'pag_color' => '',
                    'custom_pag_color' => '',
                    'resp_tablets_slides' => '',
                    'resp_tablets' => '',
                    'resp_medium_slides' => '',
                    'resp_mobile' => '767',
                    'resp_mobile_slides' => '1',
                    //* Query
                    'number_of_posts' => (int) $mb_blog_number_r,
                    'categories' => $mb_blog_cat_r,
                    'order_by' => 'rand',
                    'exclude_any' => 'yes',
                    'by_posts' => [$post->post_name => $post->post_title] //* exclude current post
                ];

                (new WGL_Blog())->render($related_posts_atts); ?>
            </section><?php
            endif;

            unset($wgl_related_posts); //* destroy global var
        }
        //* ↑ related posts

        //* Comments
        if (comments_open() || get_comments_number()) { ?>
            <div class="row">
            <div class="wgl_col-12"><?php
                comments_template(); ?>
            </div>
            </div><?php
        } ?>
    </div><?php //* #main-content

    $sb && Cleenday::render_sidebar($sb); ?>
</div>
</div><?php

get_footer();
