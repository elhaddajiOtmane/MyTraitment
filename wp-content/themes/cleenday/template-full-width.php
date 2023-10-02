<?php

defined('ABSPATH') || exit;

use Cleenday_Theme_Helper as Cleenday;

/**
 * The Full-width template
 *
 * @package cleenday
 * @since 1.0.0
 */

get_header();
the_post();

$sb = Cleenday::get_sidebar_data();
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';

// Render ?>
<div class="wgl-container full-width<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row<?php echo apply_filters('cleenday/row/class', $row_class); ?>">
	<div id="main-content" class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>"><?php

        the_content(esc_html__('READ MORE!', 'cleenday'));

        Cleenday::link_pages();

        if (comments_open() || get_comments_number()) {
            comments_template();
        } ?>
    </div><?php

    if ($sb) {
        Cleenday::render_sidebar($sb);
    } ?>
</div>
</div><?php

get_footer();
