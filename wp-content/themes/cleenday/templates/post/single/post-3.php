<?php

use Cleenday_Theme_Helper as Cleenday;

$postID = get_the_ID();

$single = Cleenday_Single_Post::getInstance();
$single->set_post_data();
$post_format = $single->get_pf();

$use_author_info = Cleenday::get_option('single_author_info');
$use_tags = Cleenday::get_option('single_meta_tags') && has_tag();
$use_shares = Cleenday::get_option('single_share') && function_exists('wgl_theme_helper');
$use_likes = Cleenday::get_option('single_likes') && function_exists('wgl_simple_likes');

$video_style = $post_format == 'video' && function_exists('rwmb_meta') ? rwmb_meta('post_format_video_style') : '';

// Render ?>
<article class="blog-post blog-post-single-item format-<?php echo esc_attr($single->get_pf()); ?>">
<div <?php post_class('single_meta'); ?>>
<div class="item_wrapper">
<div class="blog-post_content"><?php

    // Media
    if (
        $post_format !== 'standard-image'
        && $post_format !== 'standard'
        && $video_style !== 'bg_video'
    ) {
        // Affected post types: gallery, link, quote, audio, video-popup.
        $single->render_featured();
    }

    the_content();

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
