<?php
/**
 * Template Status
 *
 *
 * @package cleenday\core\dashboard
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

global $wpdb;

if (!function_exists('Wgl_Status_Info_Helper')) {
    function Wgl_Status_Info_Helper() {
        return Wgl_Status_Info_Helper::instance();
    }
}

if (!class_exists('Wgl_Status_Info_Helper')) {
    class Wgl_Status_Info_Helper
    {
        protected static $instance = null;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function let_to_num($v)
        {
            $l = substr($v, -1);
            $ret = substr($v, 0, -1);
            switch(strtoupper($l)) {
                case 'P': $ret *= 1024;
                case 'T': $ret *= 1024;
                case 'G': $ret *= 1024;
                case 'M': $ret *= 1024;
                case 'K': $ret *= 1024;
                break;
            }
            return $ret;
         }

        public function memory_limit() {
            $limit = $this->let_to_num( WP_MEMORY_LIMIT );
            if ( function_exists( 'memory_get_usage' ) ) {
                $limit = max( $limit, $this->let_to_num( @ini_get( 'memory_limit' ) ) );
            }

            return $limit;
        }

    }
}

?>
<div class="wrap wgl-wrap-status_panel">
    <table class="wgl-status-table_panel info_table widefat" cellspacing="0" style="margin-bottom: 30px;">
        <thead>
            <tr>
                <th colspan="4"><?php esc_html_e( 'Theme Config', 'cleenday' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
              <td><?php esc_html_e( 'Theme Name', 'cleenday' ); ?>:</td>
              <td><?php echo esc_html(wp_get_theme()->get('Name')); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Version', 'cleenday' ); ?>:</td>
              <td><?php echo esc_html(wp_get_theme()->get('Version')); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Author URL', 'cleenday' ); ?>:</td>
              <td>
                      <a href="<?php echo esc_url_raw(wp_get_theme()->get('AuthorURI'))?>">
                          <?php echo esc_html(wp_get_theme()->get('AuthorURI')); ?>
                      </a>
              </td>
            </tr>

          </tbody>
    </table>

    <table class="wgl-status-table_panel info_table widefat" cellspacing="0" style="margin-bottom: 30px;">
        <thead>
            <tr>
                <th colspan="4"><?php esc_html_e( 'Server Settings', 'cleenday' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
              <td><?php esc_html_e( 'PHP Version', 'cleenday' ); ?>:</td>
              <td>

                  <?php
                      $php_requirements = 7.0;
                    if ( version_compare( phpversion(), $php_requirements, '<' ) ) {
                        ?>
                        <span class="message_info message_info-error">
                            <span class="dashicons dashicons-warning"></span>
                                <?php
                                    echo esc_html( phpversion() );
                                    esc_html_e( '- We recommend a minimum PHP version of ', 'cleenday');
                                    echo esc_html($php_requirements);
                                ?>
                        </span>
                        <?php
                    } else {
                        ?>
                            <span class="message_info message_info-success"><?php echo esc_html( phpversion() ); ?></span>
                        <?php
                    }
                  ?>

                  </td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'PHP Post Max Size', 'cleenday' ); ?>:</td>
              <td>
                      <span class="message_info message_info-info info">
                        <span class="dashicons dashicons-warning"></span>
                            <?php
                                esc_html_e( ' You cannot upload images, themes and plugins that have a size bigger than this value: ', 'cleenday');

                                echo esc_html(size_format(Wgl_Status_Info_Helper()->let_to_num((ini_get('post_max_size')))));
                            ?>
                            <br/>
                            <a target="_blank" href="http://www.wpbeginner.com/wp-tutorials/how-to-increase-the-maximum-file-upload-size-in-wordpress/">
                                <?php
                                esc_html_e( ' To see how you can change this please check this guide', 'cleenday');
                                ?>
                            </a>
                    </span>

                  </td>
            </tr>

            <tr>
              <td><?php esc_html_e( 'PHP Max Execution Time Limit', 'cleenday' ); ?>:</td>
              <td>

                  <?php
                  $max_execution_time_requirements = 600;

                  if ( $max_execution_time_requirements > ini_get('max_execution_time') ) {
                      ?>
                      <span class="message_info message_info-error">
                          <span class="dashicons dashicons-warning"></span>
                          <?php
                              echo esc_html( ini_get('max_execution_time') );
                              esc_html_e( '- We recommend setting max execution time to at least ', 'cleenday');
                              echo esc_html($max_execution_time_requirements);
                          ?>
                          <br/>
                          <a target="_blank" href="http://www.wpbeginner.com/wp-tutorials/how-to-increase-the-maximum-file-upload-size-in-wordpress/">
                              <?php
                              esc_html_e( ' To see how you can change this please check this guide', 'cleenday');
                              ?>
                          </a>
                      </span>
                      <?php
                  }
                  else{
                      ?>
                      <span class="message_info message_info-success"><?php echo esc_html(ini_get('max_execution_time')); ?></span>
                      <?php
                  }
                  ?>
                  </td>
            </tr>

            <tr>
              <td><?php esc_html_e( 'PHP Max Input Time', 'cleenday' ); ?>:</td>
              <td>

                  <?php
                  $max_input_time_requirements = 600;
                  if ( $max_input_time_requirements > ini_get('max_input_time') ) {
                      ?>
                      <span class="message_info message_info-error">
                          <span class="dashicons dashicons-warning"></span>
                          <?php
                              echo esc_html(ini_get('max_input_time'));
                              esc_html_e('- We recommend setting max input time to at least ', 'cleenday');
                              echo esc_html($max_input_time_requirements);
                          ?>
                          <br/>
                          <a target="_blank" href="http://www.wpbeginner.com/wp-tutorials/how-to-increase-the-maximum-file-upload-size-in-wordpress/">
                              <?php
                              esc_html_e( ' To see how you can change this please check this guide', 'cleenday');
                              ?>
                          </a>
                      </span>
                      <?php
                  }
                  else{
                      ?>
                      <span class="message_info message_info-success"><?php echo esc_html(ini_get('max_input_time')); ?></span>
                      <?php
                  }
                  ?>
                  </td>
            </tr>

            <tr>
              <td><?php esc_html_e( 'PHP Max Input Vars', 'cleenday' ); ?>:</td>
              <td>
                  <?php
                  $max_input_vars_requirements = 3000;

                  if ( $max_input_vars_requirements > ini_get('max_input_vars') ) {
                      ?>
                      <span class="message_info message_info-error">
                          <span class="dashicons dashicons-warning"></span>
                          <?php
                              echo esc_html(ini_get('max_input_vars'));
                              echo sprintf( esc_html__( '. We recommend minimum value: %d. Max input vars limitation will truncate POST data such as menus.' , 'cleenday' ) ,$max_input_vars_requirements );
                          ?>
                          <br/>
                          <a target="_blank" href="https://premium.wpmudev.org/forums/topic/increase-wp-memory-limit-php-max-input-vars/">
                              <?php
                              esc_html_e( ' To see how you can change this please check this guide', 'cleenday');
                              ?>
                          </a>
                      </span>
                      <?php
                  }
                  else{
                      ?>
                      <span class="message_info message_info-success"><?php echo esc_html(ini_get('max_input_vars')); ?></span>
                      <?php
                  }
                  ?>
                  </td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'MySql Version', 'cleenday' ); ?>:</td>
              <td><?php echo (!empty( $wpdb->is_mysql ) ? $wpdb->db_version() : ''); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Max upload size', 'cleenday' ); ?>:</td>
              <td>
                  <?php
                      $max_upload_size = 134217728;
                      if ( $max_upload_size > wp_max_upload_size() ) {
                      ?>
                          <span class="message_info message_info-error">
                              <span class="dashicons dashicons-warning"></span>
                              <?php
                                  echo esc_html(size_format(wp_max_upload_size()));
                                  esc_html_e( '. We recommend minimum value: 128 MB.' , 'cleenday')
                              ?>
                              <br/>
                              <a target="_blank" href="https://premium.wpmudev.org/forums/topic/increase-wp-memory-limit-php-max-input-vars/">
                                  <?php
                                  esc_html_e( ' To see how you can change this please check this guide', 'cleenday');
                                  ?>
                              </a>
                          </span>
                          <?php
                      }
                      else{
                          ?>
                          <span class="message_info message_info-success"><?php echo esc_html(size_format(wp_max_upload_size())); ?></span>
                          <?php
                      }
                  ?>
              </td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'SimpleXML', 'cleenday' ); ?>:</td>
              <td>
                  <?php
                      if ( !extension_loaded('simplexml') ) {
                      ?>
                          <span class="message_info message_info-error">
                              <span class="dashicons dashicons-warning"></span>
                              <?php
                                  esc_html_e( 'To ensure successful installation of demo content The SimpleXML extension should be installed on your web server. Please contact your hosting provider to install and activate SimpleXML extension.' , 'cleenday')
                              ?>
                          </span>
                          <?php
                      }
                      else{
                          ?>
                          <span class="message_info message_info-success"><?php echo esc_html__('Enabled','cleenday'); ?></span>
                          <?php
                      }
                  ?>
              </td>
            </tr>

          </tbody>
    </table>

    <table class="wgl-status-table_panel info_table widefat" cellspacing="0" style="margin-bottom: 30px;">
        <thead>
            <tr>
                <th colspan="4"><?php esc_html_e( 'WordPress Settings', 'cleenday' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
              <td><?php esc_html_e( 'Home Url', 'cleenday' ); ?>:</td>
              <td><?php echo esc_html(home_url( '/' )); ?></td>
            </tr>
             <tr>
              <td><?php esc_html_e( 'Site Url', 'cleenday' ); ?>:</td>
              <td><?php echo esc_html(site_url( '/' )); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Version', 'cleenday' ); ?>:</td>
              <td><?php echo esc_html(get_bloginfo( 'version' )); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Memory Limit', 'cleenday' ); ?>:</td>
              <td>
                  <?php
                  $memory_limit_requirements = 134217728;

                  if ( $memory_limit_requirements > Wgl_Status_Info_Helper()->memory_limit() ) {
                      ?>
                      <span class="message_info message_info-error">
                          <span class="dashicons dashicons-warning"></span>
                          <?php
                              echo esc_html(size_format(Wgl_Status_Info_Helper()->memory_limit()));
                              esc_html_e( ' .We recommend setting memory to be at least 128MB.' , 'cleenday')
                          ?>
                          <br/>
                          <a target="_blank" href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP">
                              <?php
                              esc_html_e( ' To see how you can change this please check this guide', 'cleenday');
                              ?>
                          </a>
                      </span>
                      <?php
                  }
                  else{
                      ?>
                      <span class="message_info message_info-success">
                          <?php echo esc_html(size_format(Wgl_Status_Info_Helper()->memory_limit())); ?>
                      </span>
                      <?php
                  }
                  ?>
              </td>
            </tr>
            <tr>
                <td>
                    <?php echo esc_html('WP_DEBUG'); ?>
                </td>

                <td>
                    <?php
                    if (defined('WP_DEBUG') and WP_DEBUG === true):
                        echo esc_html__('WP_DEBUG is enabled.','cleenday');?>
                        <a target="_blank" href="https://codex.wordpress.org/Debugging_in_WordPress">
                            <?php echo esc_html__(' How to disable WP_DEBUG mode.','cleenday'); ?>
                        </a>
                        <?php
                    else:
                        echo esc_html__('WP_DEBUG is disabled.','cleenday');
                    endif;
                    ?>
                </td>
            </tr>
          </tbody>
    </table>
</div>
