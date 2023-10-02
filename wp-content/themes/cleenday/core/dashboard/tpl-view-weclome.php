<?php
/**
 * Template Welcome
 *
 *
 * @package cleenday\core\dashboard
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

$theme = wp_get_theme();

$allowed_html = [
    'a' => [
        'href' => true,
        'target' => true,
    ],
];

?>
<div class="wgl-welcome_page">
    <div class="wgl-welcome_title">
        <h1><?php esc_html_e('Welcome to', 'cleenday');?>
            <?php echo esc_html(wp_get_theme()->get('Name')); ?>
        </h1>
    </div>
    <div class="wgl-version_theme">
        <?php esc_html_e('Version - ', 'cleenday');?>
        <?php echo esc_html(wp_get_theme()->get('Version')); ?>
    </div>
    <div class="wgl-welcome_subtitle">
            <?php
                echo sprintf(esc_html__('%s is already installed and ready to use! Let\'s build something impressive.', 'cleenday'), esc_html(wp_get_theme()->get('Name'))) ;
            ?>
    </div>

    <div class="wgl-welcome-step_wrap">
        <div class="wgl-welcome_sidebar left_sidebar">
            <div class="theme-screenshot">
                <img src="<?php echo esc_url(get_template_directory_uri() . "/screenshot.png"); ?>">

            </div>
        </div>
        <div class="wgl-welcome_content">
            <div class="step-subtitle">
                <?php
                    echo sprintf(esc_html__('Just complete the steps below and you will be able to use all functionalities of %s theme by WebGeniusLab:', 'cleenday'), esc_html(wp_get_theme()->get('Name')));
                ?>
            </div>
            <ul>
              <li>
                  <span class="step">
                      <?php esc_html_e('Step 1', 'cleenday');?>
                  </span>
                  <?php esc_html_e('Activate your license.', 'cleenday');?>
                  <span class="attention-title">
                      <strong>
                          <?php esc_html_e('Important:', 'cleenday');?>
                      </strong>
                      <?php esc_html_e('one license  only for one website', 'cleenday');?>
                  </span>
              </li>
              <li>
                  <span class="step">
                      <?php esc_html_e('Step 2', 'cleenday');?>
                  </span>
                  <?php
                echo sprintf( wp_kses( __( 'Check <a target="_blank" href="%s">requirements</a> to avoid errors with your WordPress.', 'cleenday' ), $allowed_html), esc_url( admin_url( 'admin.php?page=wgl-status-panel' ) ) );

                  ?>
              </li>
              <li>
                  <span class="step">
                      <?php esc_html_e('Step 3', 'cleenday');?>
                  </span>
                  <?php esc_html_e('Install Required and recommended plugins.', 'cleenday');?>
              </li>
              <li>
                  <span class="step">
                      <?php esc_html_e('Step 4', 'cleenday');?>
                  </span>
                  <?php esc_html_e('Import demo content', 'cleenday');?>
              </li>
            </ul>
        </div>

    </div>


</div>
