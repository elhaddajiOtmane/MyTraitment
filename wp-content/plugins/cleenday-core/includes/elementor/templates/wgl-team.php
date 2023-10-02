<?php
/*
 * This template can be overridden by copying it to yourtheme/cleenday-core/elementor/templates/wgl-team.php.
*/
namespace WglAddons\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WglAddons\Includes\{
    Wgl_Loop_Settings,
    Wgl_Carousel_Settings,
    Wgl_Elementor_Helper
};

/**
 * WGL Elementor Team Template
 *
 *
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WglTeam
{
    private static $instance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function render($atts, $content = null)
    {
        extract($atts);

        if ($use_carousel) {
            wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js');
        }

        $wrapper_classes = ' team-col_' . $posts_per_line;
        $wrapper_classes .= ' a' . $info_align;

        ob_start();
            $this->render_wgl_team($atts);
        $team_items = ob_get_clean();

        ob_start();
        ?>
        <div class="wgl_module_team<?php echo esc_attr($wrapper_classes); ?>">
            <div class="team-items_wrap">
                <?php
                switch ($use_carousel) {
                    case true:
	                    $atts['items_per_line'] = $posts_per_line;
                        echo Wgl_Carousel_Settings::init($atts, $team_items, false);
                        break;
                    default:
                        echo \Cleenday_Theme_Helper::render_html($team_items);
                        break;
                }
                ?>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    public function render_wgl_team($atts)
    {
        extract($atts);

//        $compile = '';

        list($query_args) = Wgl_Loop_Settings::buildQuery($atts);
        $query_args['post_type'] = 'team';
        $wgl_posts = new \WP_Query($query_args);

        while ($wgl_posts->have_posts()) {
            $wgl_posts -> the_post();
            $this->render_wgl_team_item(false, $atts, false);
        }
        wp_reset_postdata();
    }

	public function render_wgl_team_item($single_member, $item_atts, $team_image_dims)
    {
        extract($item_atts);

	    $kses_allowed_html = [
		    'a' => [
			    'id' => true,
			    'href' => true, 'title' => true,
			    'class' => true, 'style' => true,
			    'rel' => true, 'target' => true,
		    ],
		    'br' => ['id' => true, 'class' => true, 'style' => true],
		    'b' => ['id' => true, 'class' => true, 'style' => true],
		    'em' => ['id' => true, 'class' => true, 'style' => true],
		    'strong' => ['id' => true, 'class' => true, 'style' => true],
		    'span' => ['id' => true, 'class' => true, 'style' => true],
	    ];

        $info_array = $info_bg_url = $author_overlay_img = null;
        $t_info = $t_icons = $featured_image = $title = $icons_wrap = '';

        $id = get_the_ID();
        $post = get_post($id);
        $permalink = esc_url(get_permalink($id));
        $department = get_post_meta($id, 'department', true);
	    $description = get_post_meta($id, 'description', true);
        $social_array = get_post_meta($id, 'soc_icon', true);
        $wp_get_attachment_url = wp_get_attachment_url(get_post_thumbnail_id($id));

        if ($single_member) {
            $info_array = get_post_meta($id, 'info_items', true);
            $author_overlay = get_post_meta($id, 'mb_author_overlay', true);
            $info_bg_id = get_post_meta($id, 'mb_info_bg', true);
	        $author_overlay_img = wp_get_attachment_image($author_overlay, 'full');
            $info_bg_url = wp_get_attachment_url($info_bg_id);
        } else {
            $team_image_dims = Wgl_Elementor_Helper::get_image_dimensions(
                $img_size_array ?: $img_size_string,
                $img_aspect_ratio
            );

            if (is_null($team_image_dims)) {
                return;
            }
        }

        // Info
        if ($info_array) {
            for ($i = 0, $count = count($info_array); $i < $count; $i++) {
                $info = $info_array[$i];
                $info_name = ! empty($info['name']) ? $info['name'] : '';
                $info_description = ! empty($info['description']) ? $info['description'] : '';
                $info_link = ! empty($info['link']) ? $info['link'] : '';

                if (
                    $single_member
                    && (!empty($info_name) || !empty($info_description))
                ) {
                    $t_info .= '<div class="team-info_item">';
                        $t_info .= ! empty($info_name) ? '<h5>' . esc_html($info_name) . '</h5>' : '';
                        $t_info .= ! empty($info_link) ? '<a href="'.esc_url($info_link).'">' : '';
                            $t_info .= '<span>' . esc_html($info_description) . '</span>';
                        $t_info .= ! empty($info_link) ? '</a>' : '';
                    $t_info .= '</div>';
                }
            }
        }

        // Social icons
        if (!$hide_soc_icons && $social_array) {
            for ($i = 0, $count = count($social_array); $i < $count; $i++) {
                $icon = $social_array[$i];
                $icon_name = $icon['select'] ?: '';
                $icon_link = $icon['link'] ?: '#';
                if ($icon['select']) {
                    $t_icons .= '<a href="' . $icon_link . '" class="team-icon ' . $icon_name . '"></a>';
                }
            }
            if ($t_icons) {
                $icons_wrap  = '<div class="team__icons">';
                    $icons_wrap .= '<span class="team-icon flaticon-close"></span>';
                    $icons_wrap .= $t_icons;
                $icons_wrap .= '</div>';
            }
        }

        // Featured Image
        if ($wp_get_attachment_url) {
            $img_url = aq_resize($wp_get_attachment_url, $team_image_dims['width'], $team_image_dims['height'], true, true, true);
            $img_alt = get_post_meta(get_post_thumbnail_id($id), '_wp_attachment_image_alt', true);

            $featured_image = sprintf('<%s class="team__image"><img src="%s" alt="%s" /></%s>',
                $single_link_wrapper && ! $single_member ? 'a href="' . $permalink . '"' : 'div',
                esc_url($img_url),
                $img_alt ?: '',
                $single_link_wrapper && ! $single_member ? 'a' : 'div'
            );
        }

        // Title
        if (! $hide_title) {
            $title .= '<h2 class="team-title">';
                $title .= $single_link_heading && ! $single_member ? '<a href="' . $permalink . '">' : '';
                    $title .= get_the_title();
                $title .= $single_link_heading && ! $single_member ? '</a>' : '';
            $title .= '</h2>';
        }

        // Excerpt
        if (! $single_member && ! $hide_content) {
            $excerpt = $post->post_excerpt ?: $post->post_content;
            $excerpt = preg_replace( '~\[[^\]]+\]~', '', $excerpt);
            $excerpt = strip_tags($excerpt);
            $excerpt = \Cleenday_Theme_Helper::modifier_character($excerpt, $letter_count, '');
        }

        // Render grid & single
        if (!$single_member) { ?>
            <div class="team-item">
            <div class="team-item_wrap"><?php
                if ($featured_image) { ?>
                    <div class="team__media-wrapper">
                        <div class="team__image-wrapper"><?php
                            echo $icons_wrap,
                            $featured_image; ?>
                        </div>
                    </div><?php
                }

                if (!$featured_image && !$hide_soc_icons) {
                    echo $icons_wrap;
                }

                if (!$hide_content || !$hide_title || !$hide_meta) { ?>
                    <div class="team-item_info"><?php
                    echo $title;

                    if (!$hide_meta && $department) { ?>
                        <div class="team-department"><?php
                            echo esc_html($department); ?>
                        </div><?php
                    }

                    if (!$hide_content && $excerpt) {?>
                        <div class="team-item_excerpt"><?php
                            echo $excerpt; ?>
                        </div><?php
                    }?>
                    </div><?php
                }?>
            </div>
            </div><?php

        } else { ?>
            <div class="team-single_wrapper"<?php echo ($info_bg_url ? ' style="background-image: url('.esc_url($info_bg_url).')"':''); ?>><?php
                if ($featured_image) { ?>
                    <div class="team-image_wrap"><?php
                        echo $author_overlay_img ? '<div class="author_overlay_image">'.$author_overlay_img.'</div>' : '';
                        echo $featured_image ?>
                    </div><?php
                } ?>
                <div class="team-info_wrapper">
                    <div class="team-info_title_wrap"><?php
                        echo $title,
                        ($department ? '<div class="team-info_item department"><span>' . esc_html($department) . '</span></div>' : ''); ?>
                    </div><?php
                    echo ($description ? '<div class="description"><span>' . wp_kses($description, $kses_allowed_html) . '</span></div>' : ''),
                    $t_info,
                    $icons_wrap; ?>
                </div>
            </div><?php
        }
    }
}
