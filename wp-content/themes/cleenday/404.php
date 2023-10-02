<?php

defined('ABSPATH') || exit;

use WglAddons\Cleenday_Global_Variables as Cleenday_Globals;

/**
 * Template for Page 404
 *
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package cleenday
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
$layout_template = Cleenday_Theme_Helper::get_option('404_page_type');
if ($layout_template == 'custom') {
    $page_select = Cleenday_Theme_Helper::get_option('404_template_select');
    if (!empty($page_select)) {
      $page_select = intval($page_select);

      if (class_exists('Polylang') && function_exists('pll_current_language')) {
          $currentLanguage = pll_current_language();
          $translations = PLL()->model->post->get_translations($page_select);

          $polylang_id = $translations[$currentLanguage] ?? '';
          $page_select = !empty($polylang_id) ? $polylang_id : $page_select;
      }

      if (class_exists('SitePress')) {
          $page_select = wpml_object_id_filter($page_select, 'elementor_library', false, ICL_LANGUAGE_CODE);
      }


      if (class_exists('\Elementor\Core\Files\CSS\Post')) {
        $css_file = new \Elementor\Core\Files\CSS\Post($page_select);
        $css_file->enqueue();
      }
    }

  ob_start();
    if (did_action('elementor/loaded')) {
      echo \Elementor\Plugin::$instance->frontend->get_builder_content($page_select);
    }
  $render_template = ob_get_clean();

} else {
  $primary_color = esc_attr(Cleenday_Theme_Helper::get_option('theme-primary-color'));
  $h_font_color = esc_attr(Cleenday_Theme_Helper::get_option('header-font')['color']);

  $bg_color = Cleenday_Theme_Helper::get_option('404_page_main_bg_image')['background-color'];
  $bg_image = Cleenday_Theme_Helper::bg_render('404_page_main');

  $styles = !empty($bg_color) ? 'background-color: ' . $bg_color . ';' : '';
  $styles .= $bg_image ?: '';
  $styles = $styles ? ' style="' . esc_attr($styles) . '"' : '';

  // Particles
  $particles = Cleenday_Theme_Helper::get_option('404_particles');
  if ($particles) {
    wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', ['jquery'], false, true);
  }
}

// Render
get_header();
if ($layout_template == 'custom') {
  echo Cleenday_Theme_Helper::render_html($render_template);
} else { ?>
<div class="wgl-container full-width">
  <div class="row">
    <div class="wgl_col-12">
      <section class="page_404_wrapper" <?php echo isset($styles) ? Cleenday_Theme_Helper::render_html($styles) : '' ?>>
        <div class="page_404_wrapper-container">
          <div class="row">
            <div class="wgl_col-12 wgl_col-md-12">
              <div class="main_404-wrapper">
                <div class="banner_404">
                  <img src="<?php echo esc_url(get_template_directory_uri() . "/img/404.png"); ?>" alt="<?php echo esc_attr__('404', 'cleenday'); ?>">
                </div>
                <h2 class="banner_404_title"><span><?php esc_html_e('Sorry We Can`t Find That Page!', 'cleenday'); ?></span></h2>
                <p class="banner_404_text">
                  <?php esc_html_e('The page you are looking for was moved, removed,', 'cleenday'); ?>
                  <br>
                  <?php esc_html_e(' renamed or never existed.', 'cleenday'); ?>
                </p>
                <div class="cleenday_404_search">
                  <?php get_search_form(); ?>
                </div>
                <div class="cleenday_404__button">
                  <a class="wgl-button btn-size-md" href="<?php echo esc_url(home_url('/')); ?>">
                    <div class="button-content-wrapper">
                      <?php esc_html_e('TAKE ME HOME', 'cleenday'); ?>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php if ($particles) : ?>
          <div id="<?php echo uniqid('page_404'); ?>"
            class="wgl-particles-js particles-js"
            data-particles-colors-type="random_colors"
            data-particles-number="10"
            data-particles-size="7"
            data-particles-speed="2"
            data-particles-line="false"
            data-particles-hover="false"
            data-particles-hover-mode="grab"
            data-particles-color="<?php echo esc_attr(Cleenday_Globals::get_primary_color()), ',', esc_attr(Cleenday_Globals::get_h_font_color());?>"
            data-particles-type="particles"
            style="top: 0; left: 0;"
            >
          </div>
        <?php endif; ?>
      </section>
    </div>
  </div>
</div>
<?php }
get_footer();