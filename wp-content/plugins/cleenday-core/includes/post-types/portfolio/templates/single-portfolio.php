<?php

defined('ABSPATH') || exit;

use WglAddons\{Cleenday_Global_Variables as Cleenday_Globals};
use WglAddons\Templates\WglPortfolio;
use Cleenday_Theme_Helper as Cleenday;

/**
* Template for Portfolio CPT single page
*
* @package cleenday-core\includes\post-types
* @author WebGeniusLab <webgeniuslab@gmail.com>
* @since 1.0.0
*/

get_header();

$sb = Cleenday::get_sidebar_data('portfolio_single');
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '';

$defaults = [
    'items_per_line' => '1',
    'portfolio_layout' => '',
    'portfolio_icon_pack' => '',
];

echo '<div class="wgl-portfolio-single_wrapper">';
echo '<div class="wgl-container single_portfolio', apply_filters('cleenday/container/class', $container_class), '">';
echo '<div class="row', apply_filters('cleenday/row/class', $row_class), '">';
    echo '<div id="main-content" class="wgl_col-', apply_filters('cleenday/column/class', $column), '">';

        while (have_posts()) :
            the_post();
            echo (new WglPortfolio())->wgl_portfolio_single_item($defaults, $item_class = '');
        endwhile;
        wp_reset_postdata();

        //* Navigation
        get_template_part('templates/post/post-navigation');

        //* ↓ Related
        $related_switch = Cleenday::get_option('portfolio_related_switch');
        if (class_exists('RWMB_Loader')) {
            $mb_related_switch = rwmb_meta('mb_portfolio_related_switch');
            if ($mb_related_switch == 'on') {
                $related_switch = true;
            } elseif ($mb_related_switch == 'off') {
                $related_switch = false;
            }
        }

        if (
            $related_switch
            && class_exists('Cleenday_Core')
            && class_exists('Elementor\Plugin')
        ) {
            $mb_pf_cat_r = [];

            $mb_pf_carousel_r = Cleenday::get_mb_option('pf_carousel_r', 'mb_portfolio_related_switch', 'on');
            $mb_pf_title_r = Cleenday::get_mb_option('pf_title_r', 'mb_portfolio_related_switch', 'on');
            $mb_pf_column_r = Cleenday::get_mb_option('pf_column_r', 'mb_portfolio_related_switch', 'on');
            $mb_pf_number_r = Cleenday::get_mb_option('pf_number_r', 'mb_portfolio_related_switch', 'on');
            $mb_pf_number_r = !empty($mb_pf_number_r) ? $mb_pf_number_r : '12';

            if (class_exists('RWMB_Loader')) {
                $mb_pf_cat_r = get_post_meta(get_the_id(), 'mb_pf_cat_r'); // store terms’ IDs in the post meta and doesn’t set post terms.
            }

            if (!$mb_pf_carousel_r) {
                wp_enqueue_script('isotope');
            }

            $cats = get_the_terms(get_the_id(), 'portfolio-category') ?: [];
            $cat_slugs = [];
            foreach ($cats as $cat) {
                $cat_slugs[] = 'portfolio-category:' . $cat->slug;
            }

            if (!empty($mb_pf_cat_r[0])) {
                $cat_slugs = [];
                $list = get_terms('portfolio-category', ['include' => $mb_pf_cat_r[0]]);
                foreach ($list as $value) {
                    $cat_slugs[] = 'portfolio-category:' . $value->slug;
                }
            }

            $related_atts = [
                'portfolio_layout' => 'related',
                'info_anim' => 'offset',
                'info_visibility' => 'on_hover',
                'info_visibility_hybrid' => 'on_hover',
                'link_destination' => 'single',
                'linked_image' => true,
                'gallery_mode' => false,
                'linked_title' => 'yes',
                'add_animation' => '',
                'show_filter' => '',
                'info_position' => 'inside_image',
	            'show_portfolio_title' => Cleenday::get_option('portfolio_list_show_title'),
	            'show_meta_categories' => Cleenday::get_option('portfolio_list_show_cat'),
	            'show_content' => Cleenday::get_option('portfolio_list_show_content'),
                'grid_gap' => '30px',
                'featured_render' => '1',
                'items_load' => $mb_pf_column_r,
                'img_size_string' => '740x740',
                'img_size_array' => '',
                'img_aspect_ratio' => '',
                'sec_overlay_svg_color' => false,
	            'filter_all_text' => esc_html__('ALL', 'cleenday-core'),
                // Carousel
                'autoplay' => true,
                'autoplay_speed' => '5000',
                'c_infinite_loop' => true,
                'c_slide_per_single' => 1,
                'mb_pf_carousel_r' => $mb_pf_carousel_r,
                'items_per_line' => $mb_pf_column_r,
                'use_pagination' => false,
                'arrows_center_mode' => '',
                'center_info' => '',
                'use_prev_next' => '',
                'center_mode' => '',
                'variable_width' => '',
                'navigation' => '',
                'pag_type' => 'circle',
                'pag_offset' => '',
                'pag_color' => '',
                'custom_resp' => true,
                'resp_medium' => '',
                'custom_pag_color' => '',
                'resp_tablets_slides' => '',
                'resp_tablets' => '992',
                'resp_medium_slides' => '2',
                'resp_mobile' => '600',
                'resp_mobile_slides'=> '1',
                // Query
                'number_of_posts' => $mb_pf_number_r,
                'order_by' => 'menu_order',
                'post_type' => 'portfolio',
                'taxonomies' => $cat_slugs,
            ];

            $related_posts = new WglPortfolio();

            $featured_post = $related_posts->render($related_atts);
            if ($related_posts->post_count > 0) {
                echo '<section class="related_portfolio">';
                    if (!empty($mb_pf_title_r)) {
                        echo '<div class="cleenday_module_title">',
                            '<h4>', esc_html($mb_pf_title_r), '</h4>',
                        '</div>';
                    }
                    echo $featured_post;
                echo '</section>';
            }
        }
        //* ↑ related

        //* Comments
        if (comments_open() || get_comments_number()) {
            echo '<div class="row">';
            echo '<div class="wgl_col-12">';
                comments_template('', true);
            echo '</div>';
            echo '</div>';
        }

    echo '</div>';

    $sb && Cleenday::render_sidebar($sb);

echo '</div>';
echo '</div>';
echo '</div>';


get_footer();
