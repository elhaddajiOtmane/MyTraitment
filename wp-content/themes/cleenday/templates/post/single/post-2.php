<?php

use Cleenday_Theme_Helper as Cleenday;

$postID = get_the_ID();

$single = Cleenday_Single_Post::getInstance();
$single->set_post_data();
$single->set_image_data();
$single->set_post_views($postID);

$hide_featured = Cleenday::get_mb_option('post_hide_featured_image', 'mb_post_hide_featured_image', true);

$hide_all_meta = Cleenday::get_option('single_meta');
$use_author_info = Cleenday::get_option('single_author_info');
$use_tags = Cleenday::get_option('single_meta_tags') && has_tag();
$use_shares = Cleenday::get_option('single_share') && function_exists('wgl_theme_helper');
$use_likes = Cleenday::get_option('single_likes') && function_exists('wgl_simple_likes');
$use_views = Cleenday::get_option('single_views');

$has_media = $single->meta_info_render;

$meta_cats = $meta_data = [];
if (!$hide_all_meta) {
    $meta_cats['category'] = !Cleenday::get_option('single_meta_categories');
    $meta_data['date'] = !Cleenday::get_option('single_meta_date');
    $meta_data['author'] = !Cleenday::get_option('single_meta_author');
    $meta_data['comments'] = !Cleenday::get_option('single_meta_comments');
	$meta_data['views'][0] = $use_views;
}

$meta_data['views'][1] = $use_views ? $single->get_post_views($postID) : '';

// Render ?>
<article class="blog-post blog-post-single-item format-<?php echo esc_attr($single->get_pf()); ?>">
<div <?php post_class('single_meta'); ?>>
<div class="item_wrapper">
<div class="blog-post_content"><?php

    // Media
    $single->render_featured();

    // Categories
    if (!$hide_all_meta) {
        $single->render_post_meta($meta_cats);
    }

    // Meta Data
    if (!$hide_all_meta) {
        $single->render_post_meta($meta_data);
    }

    // Title ?>
    <h1 class="blog-post_title"><?php echo get_the_title(); ?></h1><?php

    // Content
    the_content();

    // Pagination
    Cleenday::link_pages();

    if (
        $use_tags
        || $use_shares
        || $use_likes
    ) { ?>
        <div class="single_post_info"><?php

            // Tags
            if ($use_tags) {
                the_tags('<div class="tagcloud-wrapper"><div class="tagcloud">', ' ', '</div></div>');
            }
            
	        // Socials
	        if ($use_shares) {
		        wgl_theme_helper()->render_post_list_share();
	        }
	        
            // Likes
            if ($use_likes) {
                echo wgl_simple_likes()->get_likes_button($postID, 0);
            }?>
        </div><?php
    }
	
	// Author Info
	if ($use_author_info) {
		$single->render_author_info();
	}else {?>
        <div class="post_info-divider"></div><?php
	}?>

    <div class="clear"></div>
</div>
</div>
</div>
</article><?php
