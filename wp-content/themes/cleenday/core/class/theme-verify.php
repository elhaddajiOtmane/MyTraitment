<?php
defined('ABSPATH') || exit;

if (!class_exists('Wgl_Theme_Verify')) {
    /**
     * Verify Theme through author's API
     *
     *
     * @category Class
     * @package cleenday\core\class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Wgl_Theme_Verify
    {
        public $item_id = 'envato_purchase_code_22734275';
        public $api = 'https://api.webgeniuslab.net/wp-json/api/v1/';
        /**
        * @access      private
        * @var         \Wgl_Theme_Verify $instance
        * @since       1.0.0
        */
        private static $instance;

        /**
        * Get active instance
        *
        * @access      public
        * @since       1.0.0
        * @return      self::$instance
        */
        public static function instance() {
            if ( ! self::$instance ) {
                self::$instance = new self;
                self::$instance->hooks();
            }

            return self::$instance;
        }

        // Shim since we changed the function name. Deprecated.
        public static function get_instance() {
            if ( ! self::$instance ) {
                self::$instance = new self;
                self::$instance->hooks();
            }

            return self::$instance;
        }

        private function hooks(){

            add_action( 'admin_notices', array( $this, 'notices_validation' ), 90);
            add_action( 'admin_notices', array( $this, 'notices_support_until' ), 90);

            add_action( 'wp_ajax_purchase_activation', array( $this, 'purchase_activation' ) );
            add_action( 'wp_ajax_nopriv_purchase_activation', array( $this, 'purchase_activation' ));

            add_action('admin_init',array($this,'deactivate_theme'));
            add_action('admin_init',array($this,'js_activate_theme'));
			add_action('admin_init',array($this,'js_deactivate_theme'));

            add_action('admin_init',array($this,'handle_tracker_actions'));
        }

        public function handle_tracker_actions(){
            if (!isset( $_GET['hide_notice'])) {
                return;
            }

            if ('remind_me_later' === $_GET['hide_notice']){
                check_admin_referer('remind_me_later');
                set_transient('hide_notice', 1, 72 * HOUR_IN_SECONDS);
            }

            if ('delete_notice' === $_GET['hide_notice']){
                check_admin_referer('delete_notice');
                update_option('delete_support_notice', '1');
            }

            wp_redirect(remove_query_arg( 'hide_notice' ));
            exit;
        }

        public function notices_validation(){

            if( Cleenday_Theme_Helper::wgl_theme_activated() ){
                return;
            }
            $page_purchase = admin_url( 'admin.php?page=wgl-activate-theme-panel' ); ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <?php echo wp_get_theme()->get('Name');
                        echo sprintf( esc_html__( ' Theme is not activated! Please activate your copy of the %s Theme.', 'cleenday'), wp_get_theme()->get('Name') );
                        ?>
                </p>
                <p>
                    <strong style="color:red"><?php esc_html_e( 'Please activate the theme!', 'cleenday' ); ?></strong> -
                    <a href="<?php echo esc_url(( $page_purchase )); ?>">
                        <?php esc_html_e( 'Activate Now','cleenday' ); ?>
                    </a>
                </p>
            </div>

        <?php
        }

        public function notices_support_until()
        {
            if( !Cleenday_Theme_Helper::wgl_theme_activated()
            || (bool) ( $wgl_transient = get_transient('hide_notice'))
            || (bool) get_option('delete_support_notice')){
                return;
            }
            $theme_support = get_option('wgl_licence_validated');
            $item_url = isset($theme_support['item_url']) ? $theme_support['item_url'] : '#';
            $theme_support = isset($theme_support['supported_until']) ? $theme_support['supported_until'] : '';

            if(empty($theme_support)){
                return;
            }

            $until_date = new DateTime($theme_support);
            $now = new DateTime();
            $interval = $until_date->diff($now);

            if($now < $until_date && $interval->days > 30){
                return;
            } ?>
            <div class="notice notice-warning is-dismissible">
                <p class="support-until_text">
                    <?php
                        $allowed_html = array(
                            'a' => array(
                                'href' => true,
                            ),
                            'strong' => array()
                        );
                        if($now > $until_date){
                            echo sprintf( wp_kses( __( '<strong>Your support period expired. <a href="%s">You can prolong support</a> services as you need.</strong>', 'cleenday' ), $allowed_html), esc_url($item_url));
                        }elseif($now < $until_date && $interval->days < 30){
                            echo sprintf( wp_kses( __( '<strong>Your support period will be expired after %d days. <a href="%s">You can prolong support</a> services as you need.</strong>', 'cleenday' ), $allowed_html), $interval->days, esc_url($item_url));
                        }

                    ?>
                </p>
                <p>
                    <?php
                        $remind_url = wp_nonce_url( add_query_arg('hide_notice', 'remind_me_later'), 'remind_me_later' );
                        $delete_url = wp_nonce_url( add_query_arg('hide_notice', 'delete_notice'), 'delete_notice' );
                    ?>
                    <a href="<?php echo esc_url($remind_url); ?>" class="button button-primary"><?php echo esc_html__( 'Remind me later', 'cleenday' ); ?></a>&nbsp;
                    <a href="<?php echo esc_url($delete_url); ?>" class="button button-primary"><?php echo esc_html__( 'Hide and disable further reminders', 'cleenday' ); ?></a>
                </p>
            </div>

        <?php
        }

        public function purchase_activation(){
            $output = array( 'success'   => 0, 'message'   => '', 'error'     => '');

            if ( ! isset( $_POST['email'] ) ||  ! isset( $_POST['purchase_code'] ) || ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'purchase-activation' ) ) {
                $output['error'] = 1;
                $output['message'] = esc_html__( 'Please enter a valid field', 'cleenday' );
                echo json_encode( $output );
                wp_die();
            }else{

                $email      = $_POST['email'];
                $purchase   = $_POST['purchase_code'];

                if( ! is_email( $email ) ){
                    $output['error'] = 1;
                    $output['message'] = esc_html__( 'Please enter a valid email address.', 'cleenday' );
                    echo json_encode( $output );
                    wp_die();
                }

                if(empty($purchase)){
                    $output['error'] = 1;
                    $output['message'] = esc_html__( 'Purchase code is empty ', 'cleenday' );
                    echo json_encode( $output );
                    wp_die();
                }

                $return = self::check_activation($email, $purchase);
                if ($return !== false) {
                    $result = json_decode($return['body'], true);

                    if (!empty($result['success'])) {

                        $output['purchase'] = $purchase;
                        $output['success'] = 1;
                        $output['email'] = $email;
                        $output['error'] = '';

                        $output['supported_until'] = $result['content']['supported_until'] ?? '';
                        $output['item_url'] = $result['content']['item']['url'] ?? '';
                        $output['message'] = esc_html__('Thank you, your license has been validated', 'cleenday');

                        update_option('wgl_licence_validated', $output);
                        update_option(Wgl_Theme_Verify::get_instance()->item_id, $purchase);
                        update_option('wgl_js_activation', '');
                        echo json_encode($output);
                    } else {
                        $output['success'] = '';
                        $output['message'] = $result['message'];
                        $output['error'] = 1;
                        update_option('wgl_licence_validated', '');
                        update_option(Wgl_Theme_Verify::get_instance()->item_id, '');
                        update_option('wgl_js_activation', '');
                        echo json_encode($output);
                    }

                }else{
                    wp_send_json( '' );
                }
            }

            wp_die();
        }

        public static function check_activation($email, $purchase){

            $url = Wgl_Theme_Verify::get_instance()->api. 'verification';

            global $wp_version;

            $currentTheme = wp_get_theme();
            $theme_name = $currentTheme->parent() == false ? wp_get_theme()->get( 'Name' ) : wp_get_theme()->parent()->get( 'Name' );

            $args = array(
                'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url() ),
                'body' => json_encode( array(
                    'purchase_code'   => $purchase,
                    'email'     => $email,
                    'domain_url' => esc_url(site_url( '/' )),
                    'theme_name' => trim($theme_name)
                ) )
            );

            $request = wp_remote_post( $url, $args );
            if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
                return false;
            }

            return $request;

        }

        public function deactivate_theme(){
            if( !Cleenday_Theme_Helper::wgl_theme_activated() ){
                return false;
            }

            $deactivate_theme = isset($_POST['deactivate_theme']) && !empty($_POST['deactivate_theme']) && !isset($_POST['js_deactivate_theme']) ? TRUE : FALSE;

            if((bool) $deactivate_theme){

                $url = Wgl_Theme_Verify::get_instance()->api . 'deactivate';

                global $wp_version;

                $theme_details = get_option('wgl_licence_validated');
                $purchase_code = $theme_details['purchase'];
                $email = $theme_details['email'];

                $currentTheme = wp_get_theme();
                $theme_name = $currentTheme->parent() == false ? wp_get_theme()->get( 'Name' ) : wp_get_theme()->parent()->get( 'Name' );

                $args = array(
                    'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url() ),
                    'body' => json_encode( array(
                        'purchase_code'   => $purchase_code,
                        'email'     => $email,
                        'domain_url' => esc_url(site_url( '/' )),
                        'theme_name' => trim($theme_name)
                    ) )
                );

                $request = wp_remote_post( $url, $args );
                if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
                    return false;
                }

                update_option( 'wgl_licence_validated', '' );
                update_option( Wgl_Theme_Verify::get_instance()->item_id, '' );
                return $request;
            }
            return false;
        }

        public function js_activate_theme(){

			$alternative_activation = isset($_POST['js_activation']) && !empty($_POST['js_activation']) ? TRUE : FALSE;

			if($alternative_activation){
				$output['purchase'] = $_POST['purchase_item'];
				$output['success'] = 1;
				$output['email'] = $_POST['user_email'];
				$output['error'] = '';
				$content = json_decode(stripslashes($_POST['content']), true);
 				$output['supported_until'] =  isset($content['supported_until']) ? $content['supported_until'] : '';
				$output['item_url'] =  isset($content['item']['url']) ? $content['item']['url'] : '';
				$output['message'] =  esc_html__( 'Thank you, your license has been validated', 'cleenday' );

				update_option( 'wgl_licence_validated', $output );
				update_option( 'wgl_js_activation', '1' );
				update_option( Wgl_Theme_Verify::get_instance()->item_id, $_POST['purchase_item'] );
			}
		}

		public function js_deactivate_theme(){

			$js_deactivation = isset($_POST['js_deactivate_theme']) && !empty($_POST['js_deactivate_theme']) ? TRUE : FALSE;

			if($js_deactivation){
				update_option( 'wgl_licence_validated', '' );
				update_option( 'wgl_js_activation', '' );
				update_option( Wgl_Theme_Verify::get_instance()->item_id, '' );
			}
        }
    }
}

Wgl_Theme_Verify::get_instance();
