<?php

defined('ABSPATH') || exit;

use Cleenday_Theme_Helper as Cleenday;

/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package cleenday
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

// Taxonomies
$tax_obj = get_queried_object();
$term_id = $tax_obj->term_id ?? '';
if ($term_id) {
    $taxonomies[] = $tax_obj->taxonomy . ': ' . $tax_obj->slug;
    $tax_description = $tax_obj->description;
}

// Sidebar parameters
$sb = Cleenday::get_sidebar_data('blog_list');
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';

// Render
get_header(); ?>
<div class="wgl-container<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row<?php echo apply_filters('cleenday/row/class', $row_class); ?>">
    <div id="main-content" class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>"><?php

        if ($term_id) { ?>
            <div class="archive__heading">
                <h4 class="archive__tax_title"><?php
                    echo get_the_archive_title(); ?>
                </h4><?php
                echo !empty($tax_description) ? '<div class="archive__tax_description">' . esc_html($tax_description) . '</div>' : ''; ?>
            </div><?php
        }

        // Blog Archive Template
        get_template_part('templates/post/posts-list');

        echo Cleenday::pagination(); ?>
    </div><?php

    $sb && Cleenday::render_sidebar($sb); ?>
</div>
</div><?php

get_footer();
