<?php

defined('ABSPATH') || exit;

use Cleenday_Theme_Helper as Cleenday;

/**
 * The template for displaying image attachments
 *
 * @package cleenday
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

get_header();

$sb = Cleenday::get_sidebar_data();
$row_class = $sb['row_class'] ?? '';
$container_class = $sb['container_class'] ?? '';
$column = $sb['column'] ?? '12';
?>
<div class="wgl-container<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row<?php echo apply_filters('cleenday/row/class', $row_class); ?>">
    <div id="main-content" class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>"><?php
        while (have_posts()) :
            the_post();

            /**
            * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
            * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
            */
            $attachments = array_values(get_children([
                'post_parent' => $post->post_parent,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => 'ASC',
                'orderby' => 'menu_order ID',
            ]));

            foreach ($attachments as $k => $attachment) {
                if ($attachment->ID == $post->ID) {
                    break;
                }
            }
            $k++;

            // If there is more than 1 attachment in a gallery
            if (count($attachments) > 1) {
                if (isset($attachments[$k])) {
                    // get the URL of the next image attachment
                    $next_attachment_url = get_attachment_link($attachments[ $k ]->ID);
                } else {
                    // or get the URL of the first image attachment
                    $next_attachment_url = get_attachment_link($attachments[0]->ID);
                }
            } else {
                // or, if there's only 1 image, get the URL of the image
                $next_attachment_url = wp_get_attachment_url();
            } ?>
            <div class="blog-post">
            <div class="single_meta attachment_media">
            <div class="blog-post_content">
                <h4 class="blog-post_title"><?php echo esc_html(get_the_title()); ?></h4>
                <div class="meta-data"><?php
                    Cleenday::posted_meta_on(); ?>
                </div><?php

                $attachment_size = [1170, 725]; // image size. ?>
                <div class="blog-post_media">
                    <a href="<?php echo esc_url($next_attachment_url); ?>" title="<?php echo the_title_attribute(); ?>" rel="attachment"><?php
                        echo wp_get_attachment_image(get_the_ID(), $attachment_size); ?>
                    </a>
                </div><?php
                the_content();

                Cleenday::link_pages(); ?>
            </div>
            </div>
            </div><?php // blog-post

            if (comments_open() || '0' != get_comments_number()) {
                comments_template();
            }
        endwhile; ?>
    </div><?php // #main-content

    if ($sb) {
        Cleenday::render_sidebar($sb);
    } ?>
</div>
</div><?php // wgl-container

get_footer();
