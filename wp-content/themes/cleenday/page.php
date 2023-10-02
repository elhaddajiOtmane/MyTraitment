<?php

defined('ABSPATH') || exit;

use Cleenday_Theme_Helper as Cleenday;

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package cleenday
 * @since 1.0.0
 */

get_header();
the_post();

$sb = Cleenday::get_sidebar_data();
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';
$container_class = $sb['container_class'] ?? '';

// Render ?>
<div class="wgl-container<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row <?php echo apply_filters('cleenday/row/class', $row_class); ?>">
    <div id="main-content" class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>"><?php

        the_content(esc_html__('READ MORE!', 'cleenday'));

        Cleenday::link_pages();

        // Comments
        if (comments_open() || get_comments_number()) {
            comments_template();
        } ?>
    </div><?php // #main-content

    if ($sb) {
        Cleenday::render_sidebar($sb);
    } ?>
</div>
</div><?php

get_footer();
