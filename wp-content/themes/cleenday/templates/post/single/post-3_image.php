<?php

use Cleenday_Theme_Helper as Cleenday;

$postID = get_the_ID();

$single = Cleenday_Single_Post::getInstance();
$single->set_post_data();
$single->set_image_data();
$single->set_post_views($postID);

$hide_all_meta = Cleenday::get_option('single_meta');
$use_views = Cleenday::get_option('single_views');

$has_media = $single->meta_info_render;

$meta_cats = $meta_data = [];
if (!$hide_all_meta) {
	$meta_cats['category'] = ! Cleenday::get_option( 'single_meta_categories' );
	$meta_data['date'] = ! Cleenday::get_option( 'single_meta_date' );
	$meta_data['author'] = ! Cleenday::get_option( 'single_meta_author' );
	$meta_data['comments'] = ! Cleenday::get_option( 'single_meta_comments' );
	$meta_data['views'][0] = $use_views;
}

$meta_data['views'][1] = $use_views ? $single->get_post_views($postID) : '';

$page_title_padding = Cleenday::get_mb_option('single_padding_layout_3', 'mb_post_layout_conditional', 'custom');
$page_title_padding_top = !empty($page_title_padding['padding-top']) ? (int)$page_title_padding['padding-top'] : '';
$page_title_padding_bottom = !empty($page_title_padding['padding-bottom']) ? (int)$page_title_padding['padding-bottom'] : '';
$page_title_styles = !empty($page_title_padding_top) ? 'padding-top: '.esc_attr((int) $page_title_padding_top).'px;' : '';
$page_title_styles .= !empty($page_title_padding_bottom) ? 'padding-bottom: '.esc_attr((int) $page_title_padding_bottom).'px;' : '';
$page_title_top = $page_title_padding_top ?: 200;

$apply_animation = Cleenday::get_mb_option('single_apply_animation', 'mb_post_layout_conditional', 'custom');
$data_attr_image = $data_attr_content = $post_class = '';

if ($apply_animation) {
    wp_enqueue_script('skrollr', get_template_directory_uri() . '/js/skrollr.min.js', [], false, false);

    $data_attr_image = ' data-center="background-position: 50% 0px;" data-top-bottom="background-position: 50% -100px;" data-anchor-target=".blog-post-single-item"';
    $data_attr_content = ' data-center="opacity: 1" data-'.esc_attr($page_title_top).'-top="opacity: 1" data-0-top="opacity: 0.15" data-anchor-target=".blog-post-single-item .blog-post_content"';
    $post_class = ' blog_skrollr_init';
}

// Render ?>
<div class="blog-post blog-post-single-item format-<?php echo esc_attr($single->get_pf()) . esc_attr($post_class); ?>" <?php echo (!empty($page_title_styles) ? ' style="' . esc_attr($page_title_styles) . '"' : ''); ?>>
<div <?php post_class('single_meta'); ?>>
<div class="item_wrapper">
<div class="blog-post_content"><?php

    // Media
    $single->render_featured_image_as_background( false, 'full', $data_attr_image); ?>
    <div class="wgl-container">
    <div class="row">
    <div class="content-container wgl_col-12" <?php echo !empty($data_attr_content) ? $data_attr_content : ''; ?>><?php

        // Categories
        if (!$hide_all_meta) $single->render_post_meta($meta_cats);

        // Title ?>
        <h1 class="blog-post_title"><?php echo get_the_title(); ?></h1><?php

        // Meta Data
        if (!$hide_all_meta) $single->render_post_meta($meta_data); ?>

    </div>
    </div>
    </div>

</div>
</div>
</div>
</div><?php
