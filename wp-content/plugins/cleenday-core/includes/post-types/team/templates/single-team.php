<?php

use WglAddons\Templates\WglTeam;
use Cleenday_Theme_Helper as Cleenday;

/**
* Template for Team CPT single page
*
* @package cleenday-core\includes\post-types\team
* @author WebGeniusLab <webgeniuslab@gmail.com>
* @since 1.0.0
*/

$sb = Cleenday::get_sidebar_data();
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '';

$defaults = [
    'title' => '',
    'posts_per_line' => '2',
    'grid_gap' => '',
    'info_align' => 'center',
    'single_link_wrapper' => false,
    'single_link_heading' => true,
    'hide_title' => false,
    'hide_meta' => false,
    'hide_soc_icons' => false,
    'grayscale_anim' => false,
    'info_anim' => false,
];
extract($defaults);

$team_image_dims = ['width' => '740', 'height' => '820']; // ratio = 1

// Render
get_header(); ?>

<div class="wgl-container<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row<?php echo apply_filters('cleenday/row/class', $row_class); ?>">
    <div id="main-content" class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>"><?php

        while (have_posts()) :
            the_post(); ?>

            <div class="row single_team_page">
                <div class="wgl_col-12"><?php
                    echo (new WglTeam())->render_wgl_team_item(true, $defaults, $team_image_dims, false); ?>
                </div>
                <div class="wgl_col-12"><?php
                    the_content( esc_html__('READ MORE!', 'cleenday-core') ); ?>
                </div>
            </div><?php
        endwhile;
        
        wp_reset_postdata(); ?>
    </div><?php

    if ($sb) {
        Cleenday::render_sidebar($sb);
    } ?>
</div>
</div><?php

get_footer();
