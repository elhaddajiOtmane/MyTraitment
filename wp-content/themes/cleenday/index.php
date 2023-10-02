<?php

defined('ABSPATH') || exit;

use Cleenday_Theme_Helper as Cleenday;

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package cleenday
 * @since 1.0.0
 */

get_header();

$sb = Cleenday::get_sidebar_data();
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';
$container_class = $sb['container_class'] ?? '';

// Render ?>
<div class="wgl-container<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row<?php echo apply_filters('cleenday/row/class', $row_class); ?>">
    <div id="main-content" class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>"><?php

        // Posts list
        get_template_part('templates/post/posts-list');

        // Pagination
        echo Cleenday::pagination(); ?>
    </div><?php

    if ($sb) {
        Cleenday::render_sidebar($sb);
    }?>
</div>
</div><?php

get_footer();
