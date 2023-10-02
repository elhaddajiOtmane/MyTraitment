<?php

use Cleenday_Theme_Helper as Cleenday;
use WglAddons\Includes\Wgl_Elementor_Helper;

global $wgl_blog_atts;

// Default settings for blog item
$trim = true;
if (!$wgl_blog_atts) {
    global $wp_query;

    $trim = false;

    $wgl_blog_atts = [
        'query' => $wp_query,
        // General
        'blog_layout' => 'grid',
        // Content
        'blog_columns' => Cleenday::get_option('blog_list_columns') ?: '12',
        'hide_media' => Cleenday::get_option('blog_list_hide_media'),
        'hide_content' => Cleenday::get_option('blog_list_hide_content'),
        'hide_blog_title' => Cleenday::get_option('blog_list_hide_title'),
        'hide_all_meta' => Cleenday::get_option('blog_list_meta'),
        'meta_author' => Cleenday::get_option('blog_list_meta_author'),
        'meta_comments' => Cleenday::get_option('blog_list_meta_comments'),
        'meta_categories' => Cleenday::get_option('blog_list_meta_categories'),
        'meta_date' => Cleenday::get_option('blog_list_meta_date'),
        'hide_likes' => !Cleenday::get_option('blog_list_likes'),
        'hide_share' => !Cleenday::get_option('blog_list_share'),
        'hide_views' => !Cleenday::get_option('blog_list_views'),
        'read_more_hide' => Cleenday::get_option('blog_list_read_more'),
        'content_letter_count' => Cleenday::get_option('blog_list_letter_count') ?: '85',
        'heading_tag' => 'h3',
        'read_more_text' => esc_html__('READ MORE', 'cleenday'),
        'items_load' => 4,
    ];
}

// Retrieve arrived|default variables
extract($wgl_blog_atts);

global $wgl_query_vars;
if (!empty($wgl_query_vars)) {
    $query = $wgl_query_vars;
}

$kses_allowed_html = [
    'a' => [
        'href' => true, 'title' => true,
        'class' => true, 'style' => true,
        'rel' => true, 'target' => true,
    ],
    'br' => ['class' => true, 'style' => true],
    'b' => ['class' => true, 'style' => true],
    'em' => ['class' => true, 'style' => true],
    'strong' => ['class' => true, 'style' => true],
    'span' => ['class' => true, 'style' => true],
];

// Variables validation
$img_size = $img_size ?? 'full';
$img_aspect_ratio = $img_aspect_ratio ?? '';
$hide_share = $hide_share && function_exists('wgl_theme_helper');
$media_link = $media_link ?? false;
$hide_views = $hide_views ?? false;

// Meta
$meta_def_post_top = $meta_data_bottom = [];
if (!$hide_all_meta) {
	$meta_def_post_top['category'] = !$meta_categories;
	$meta_def_post_top['share'] = !$hide_share;
    $meta_data_bottom['comments'] = !$meta_comments;
	$meta_data_bottom['views'][0] = !$hide_views;
	$meta_data_bottom['likes'] = !$hide_likes;
	$meta_data_top['category'] = !$meta_categories;
    
    $meta_def_post_top['author'] = !$meta_author;
    $meta_def_post_top['date'] = !$meta_date;
    $meta_data_top['author'] = !$meta_author;
    $meta_data_top['date'] = !$meta_date;
}
$meta_data_top['share'] = !$hide_share;

// Loop through query
while ($query->have_posts()) :
    $query->the_post();

    $post_img_size = class_exists('WglAddons\Includes\Wgl_Elementor_Helper')
        ? Wgl_Elementor_Helper::get_image_dimensions($img_size, $img_aspect_ratio)
        : 'full';

    $single = Cleenday_Single_Post::getInstance();
    $single->set_post_data();
    $single->set_image_data($media_link = true, $post_img_size);
	
	$meta_data_bottom['views'][1] = !$hide_views ? $single->get_post_views(get_the_ID()) : '';
	
    $has_media = $single->meta_info_render;
	
	$blog_post_classes = is_sticky() ? ' sticky-post' : '';
	$blog_post_classes .= !$has_media || $hide_media ? ' format-no_featured format-standard hide_media' : ' format-' . $single->get_pf();

    // Render ?>
    <div class="item wgl_col-<?php echo esc_attr($blog_columns); ?>">
    <div class="blog-post<?php echo esc_attr($blog_post_classes); ?>">
    <div class="blog-post_wrapper"><?php

    // Media
    if (!$hide_media && $has_media) {
	    $single->render_featured([
            'media_link' => $media_link,
            'image_size' => $post_img_size,
            'hide_all_meta' => $hide_all_meta,
            'meta_data' => $meta_data_top,
        ]);
    } ?>
    <div class="blog-post_content"><?php

	    // Media alt (link, quote, audio...)
	    if (!$hide_media && !$has_media) {
	        $single->render_featured([
		        'hide_all_meta' => $hide_all_meta,
		        'meta_data' => $meta_data_top,
	        ]);
	    }
	
	    // Cats
	    if (($hide_media || !$has_media) && !$hide_all_meta) {
		    $single->render_post_meta($meta_def_post_top);
	    }

	    // Title
	    if ( !$hide_blog_title && !empty($title = get_the_title()) ) {
	        printf(
	            '<%1$s class="blog-post_title"><a href="%2$s">%3$s</a></%1$s>',
	            esc_html($heading_tag),
	            esc_url(get_permalink()),
	            wp_kses($title, $kses_allowed_html)
	        );
	    }

	    // Excerpt|Content
	    if (!$hide_content) {
	        $single->render_excerpt($content_letter_count, $trim);
	    }

	    // Read more
	    if (!$read_more_hide && !empty($read_more_text)) { ?>
	        <div class="read-more-wrap">
	            <a href="<?php echo esc_url(get_permalink()); ?>" class="button-read-more">
	            <span><?php
	            echo esc_html($read_more_text); ?>
	            </span>
	            </a>
	        </div><?php
	    }

	    Cleenday::link_pages();

        // Meta wrapper
        if (
            !$hide_all_meta
            || !$hide_likes
            || !$hide_share
        ) {
            $meta_wrap_opened = true; ?>
            <div class="blog-post_meta-wrap"><?php
        }
    
        // Meta Data
        if (!$hide_all_meta) {
            $single->render_post_meta($meta_data_bottom);
        }
    
        if ($meta_wrap_opened ?? false) {
            ?></div><?php
        } ?>
    </div><?php // blog-post_content?>
    </div>
    </div>
    </div><?php

endwhile;
wp_reset_postdata();
