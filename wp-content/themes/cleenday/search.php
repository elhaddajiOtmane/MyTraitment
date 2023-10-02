<?php

defined('ABSPATH') || exit;

use Cleenday_Theme_Helper as Cleenday;

/**
 * The template for displaying search result page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package cleenday
 * @since 1.0.0
 */

get_header();

$sb = Cleenday::get_sidebar_data('blog_list');
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';

?>
<div class="wgl-container<?php echo apply_filters('cleenday/container/class', $container_class); ?>">
<div class="row<?php echo apply_filters('cleenday/row/class', $row_class); ?>">
    <div id='main-content' class="wgl_col-<?php echo apply_filters('cleenday/column/class', $column); ?>">
        <?php
        if (have_posts()) :
            echo '<header class="searсh-header">',
                '<h1 class="page-title">',
                    esc_html__('Search Results for: ', 'cleenday'),
                    '<span>', get_search_query(), '</span>',
                '</h1>',
            '</header>';

            global $wgl_blog_atts;
            global $wp_query;

            $wgl_blog_atts = [
                'query' => $wp_query,
                // Layout
                'blog_layout' => 'grid',
                'blog_columns' => Cleenday::get_option('blog_list_columns') ?: '12',
                // Appearance
                'hide_media' => true,
                'hide_content' => Cleenday::get_option('blog_list_hide_content'),
                'hide_blog_title' => Cleenday::get_option('blog_list_hide_title'),
                'hide_all_meta' => Cleenday::get_option('blog_list_meta'),
                'meta_author' => Cleenday::get_option('blog_list_meta_author'),
                'meta_comments' => Cleenday::get_option('blog_list_meta_comments'),
                'meta_categories' => Cleenday::get_option('blog_list_meta_categories'),
                'meta_date' => Cleenday::get_option('blog_list_meta_date'),
                'hide_likes' => !Cleenday::get_option('blog_list_likes'),
                'hide_views' => !Cleenday::get_option('blog_list_views'),
                'hide_share' => !Cleenday::get_option('blog_list_share'),
                'read_more_hide' => Cleenday::get_option('blog_list_read_more'),
                'content_letter_count' => Cleenday::get_option('blog_list_letter_count') ?: '85',
                'read_more_text' => esc_html__('READ MORE', 'cleenday'),
                'heading_tag' => 'h3',
                'items_load' => 4,
            ];

            // Blog Archive Template
            get_template_part('templates/post/posts-list');
            echo Cleenday::pagination();

        else :
            echo '<div class="page_404_wrapper">';
                echo '<header class="searсh-header">',
                    '<h1 class="page-title">',
                    esc_html__('Nothing Found', 'cleenday'),
                    '</h1>',
                '</header>';

                echo '<div class="page-content">';
                    if (is_search()) :
                        echo '<p class="banner_404_text">';
                        esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'cleenday');
                        echo '</p>';
                    else : ?>
                        <p class="banner_404_text"><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'cleenday'); ?></p>
                        <?php
                    endif;
                    ?>
                    <div class="search_result_form">
                        <?php get_search_form(); ?>
                    </div>
                    <div class="cleenday_404__button">
                        <a class="wgl-button btn-size-lg" href="<?php echo esc_url(home_url('/')); ?>">
                            <div class="button-content-wrapper">
                            <?php esc_html_e('TAKE ME HOME', 'cleenday'); ?>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
            <?php
        endif;
    echo '</div>';

    if ($sb) {
        Cleenday::render_sidebar($sb);
    }

echo '</div>';
echo '</div>';

get_footer();
