<?php

use Cleenday_Theme_Helper as Cleenday;

/**
* Template for Side Panel CPT single page
*
* @package cleenday-core\includes\post-types
* @author WebGeniusLab <webgeniuslab@gmail.com>
* @since 1.0.0
*/

get_header();
the_post();

$sb = Cleenday::get_sidebar_data();
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '';
$container_class = $sb['container_class'] ?? '';

?>
<div class="wgl-container<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row <?php echo apply_filters('cleenday/row/class', $row_class); ?>">
    <div id='main-content' class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>">
        <?php

        the_content(esc_html__('READ MORE!', 'cleenday-core'));

        Cleenday::link_pages();

        if (comments_open() || get_comments_number()) {
            comments_template();
        }

    echo '</div>';

    if ($sb) {
        Cleenday::render_sidebar($sb);
    }

echo '</div>';
echo '</div>';

get_footer();
