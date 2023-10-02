<?php

defined('ABSPATH') || exit;

use WglAddons\{Cleenday_Global_Variables as Cleenday_Globals};
use WglAddons\Templates\WglPortfolio;
use Cleenday_Theme_Helper as Cleenday;

/**
* Template for Portfolio CPT archive page
*
* @package cleenday-core\includes\post-types
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

$defaults = [
    'portfolio_layout' => 'masonry',
    'link_destination' => 'single',
    'linked_image' => true,
    'linked_title' => true,
    'add_animation' => null,
    'navigation' => 'pagination',
    'nav_align' => 'center',
    'items_per_line' => Cleenday::get_option('portfolio_list_columns'),
    'show_portfolio_title' => Cleenday::get_option('portfolio_list_show_title'),
    'show_meta_categories' => Cleenday::get_option('portfolio_list_show_cat'),
    'show_content' => Cleenday::get_option('portfolio_list_show_content'),
    'show_filter' => false,
    'filter_align' => 'center',
    'items_load' => '4',
    'grid_gap' => '30px',
    'info_position' => 'inside_image',
    'info_anim' => 'offset',
	'info_visibility' => 'on_hover',
	'info_visibility_hybrid' => 'on_hover',
    'gallery_mode' => false,
    'img_size_string' => '740x840',
    'img_size_array' => '',
    'img_aspect_ratio' => '',
	'sec_overlay_svg_color' => false,
	'filter_all_text' => esc_html__('ALL', 'cleenday-core'),
    // Query
    'number_of_posts' => '12',
    'order_by' => 'menu_order',
    'order' => 'DSC',
    'post_type' => 'portfolio',
    'taxonomies' => $taxonomies ?? [],
];

// Sidebar parameters
$sb = Cleenday::get_sidebar_data('portfolio_list');
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '';
$container_class = $sb['container_class'] ?? '';

// Render
get_header();

echo '<div class="wgl-container', apply_filters('cleenday/container/class', $container_class), '">';
echo '<div class="row', apply_filters('cleenday/row/class', $row_class), '">';
    echo '<div id="main-content" class="wgl_col-', apply_filters('cleenday/column/class', $column), '">';

        if ($term_id) {
            echo '<div class="archive__heading">',
                '<h4 class="archive__tax_title">',
                    get_the_archive_title(),
                '</h4>',
                $tax_description ? '<div class="archive__tax_description">' . esc_html($tax_description) . '</div>' : '',
            '</div>';
        }

        echo (new WglPortfolio())->render($defaults);

    echo '</div>';

    $sb && Cleenday::render_sidebar($sb);

echo '</div>';
echo '</div>';

get_footer();
