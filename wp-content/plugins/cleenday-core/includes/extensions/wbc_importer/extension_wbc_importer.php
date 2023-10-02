<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_extension_wbc_importer' ) ) {
    /**
     * Extension-Boilerplate
     *
     * @link https://github.com/ReduxFramework/extension-boilerplate
     *
     * Radium Importer - Modified For ReduxFramework
     * @link https://github.com/FrankM1/radium-one-click-demo-install
     *
     * @package     WBC_Importer - Extension for Importing demo content
     * @author      Webcreations907
     * @version     1.0.2
     */
    class ReduxFramework_extension_wbc_importer
    {
        public static $instance;

        static $version = "1.0.2";

        protected $parent;

        private $filesystem = [];

        public $extension_url;

        public $extension_dir;

        public $demo_data_dir;

        public $wbc_import_files = [];

        public $active_import_id;

        public $active_import;

        /**
         * Class Constructor
         *
         * @since       1.0
         * @access      public
         * @return      void
         */
        public function __construct( $parent )
        {
            $this->parent = $parent;

            if ( !is_admin() ) return;

            //Hides importer section if anything but true returned. Way to abort :)
            if ( true !== apply_filters( 'wbc_importer_abort', true ) ) {
                return;
            }

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $dir = Redux_Helpers::cleanFilePath( dirname( __FILE__ ) );
                $_dir = trailingslashit( $dir );

                $wp_content_url = trailingslashit( Redux_Helpers::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) ) );

                $wp_content_dir = trailingslashit( Redux_Helpers::cleanFilePath( WP_CONTENT_DIR ) );
                $wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
                $relative_url = str_replace( $wp_content_dir, '', $_dir );
                $this->extension_url = trailingslashit( $wp_content_url . $relative_url );
                $this->demo_data_dir = apply_filters( "wbc_importer_dir_path", $this->extension_dir . 'demo-data/' );
            }

            //Delete saved options of imported demos, for dev/testing purpose
            // delete_option('wbc_imported_demos');

            $this->getImports();

            $this->field_name = 'wbc_importer';

            self::$instance = $this;

            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array( &$this,
                'overload_field_path'
            ) );

            add_action( 'wp_ajax_redux_wbc_importer', array(
                $this,
                'ajax_importer'
            ) );

            /*add_action( 'wp_ajax_redux_partial_importer', array(
                    $this,
                    'ajax_partial_importer'
                ) );*/


            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/wbc_importer_files', array(
                $this,
                'addImportFiles'
            ) );

            // Adds Importer section to panel
            $this->add_importer_section();

            /*include $this->extension_dir.'inc/class-wbc-importer-progress.php';
            $wbc_progress = new Wbc_Importer_Progress( $this->parent );*/

            //Remove Elementor Filter
            remove_filter( 'wp_import_post_meta', 'Elementor\Compatibility::on_wp_import_post_meta');

            add_filter( 'wp_import_post_meta', [ $this, 'wgl_wp_import_post_meta' ] );
        }

        /**
         * Process post meta before WGL WP import.
         *
         * Normalize Elementor post meta on import, We need the `wp_slash` in order
         * to avoid the unslashing during the `add_post_meta`.
         *
         * Fired by `wp_import_post_meta` filter.
         *
         * @since 1.0.0
         * @access public
         *
         * @param array $post_meta Post meta.
         *
         * @return array Updated post meta.
         */
        public function wgl_wp_import_post_meta( $post_meta ) {
            foreach ( $post_meta as &$meta ) {
                if ( '_elementor_data' === $meta['key'] ) {
                    $meta['value'] = wp_slash( $meta['value'] );
                    break;
                }
            }

            return $post_meta;
        }

        /**
         * Get the demo folders/files
         * Provided fallback where some host require FTP info
         *
         * @return array list of files for demos
         */
        public function demoFiles()
        {
            $this->filesystem = $this->parent->filesystem->execute( 'object' );
            $dir_array = $this->filesystem->dirlist( $this->demo_data_dir, false, true );

            if ( !empty( $dir_array ) && is_array( $dir_array ) ) {
                uksort( $dir_array, 'strcasecmp' );

                return $dir_array;
            } else {
                $dir_array = [];

                $demo_directory = array_diff( scandir( $this->demo_data_dir ), array( '..', '.' ) );

                if ( !empty( $demo_directory ) && is_array( $demo_directory ) ) {
                    foreach ( $demo_directory as $key => $value ) {
                        if ( is_dir( $this->demo_data_dir.$value ) ) {

                            $dir_array[$value] = array( 'name' => $value, 'type' => 'd', 'files'=> [] );

                            $demo_content = array_diff( scandir( $this->demo_data_dir.$value ), array( '..', '.' ) );

                            foreach ( $demo_content as $d_key => $d_value ) {
                                if ( is_file( $this->demo_data_dir.$value.'/'.$d_value ) ) {
                                    $dir_array[$value]['files'][$d_value] = array( 'name'=> $d_value, 'type' => 'f' );
                                }
                            }
                        }
                    }

                    uksort( $dir_array, 'strcasecmp' );
                }
            }
            return $dir_array;
        }

        public function getImports()
        {
            if (!empty($this->wbc_import_files)) {
                return $this->wbc_import_files;
            }

            $imports = $this->demoFiles();

            $imported = get_option( 'wbc_imported_demos' );

            if (!empty($imports) && is_array($imports)) {
                $x = 1;
                foreach ( $imports as $import ) {

                    if (empty( $import['files'])) {
                        continue;
                    }

                    if ( $import['type'] == "d" && !empty( $import['name'] ) ) {
                        $this->wbc_import_files['wbc-import-'.$x] = $this->wbc_import_files['wbc-import-'.$x] ?? [];
                        $this->wbc_import_files['wbc-import-'.$x]['directory'] = $import['name'];

                        if (
                            !empty($imported)
                            && is_array($imported)
                            && array_key_exists('wbc-import-'.$x, $imported)
                        ) {
                            $this->wbc_import_files['wbc-import-'.$x]['imported'] = 'imported';
                        }

                        $this->wbc_import_files['wbc-import-'.$x]['content_file'] = 'content.xml';
                        $content_list = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10');
                        foreach ($content_list as $key => $value) {
                            $key_s = $key + 1;
                            $this->wbc_import_files['wbc-import-'.$x]['content_file'.$key_s] = 'content_'.$value.'.xml';
                        }

                        foreach ( $import['files'] as $file ) {
                            switch ( $file['name'] ) {
                                case 'theme-options.txt':
                                case 'theme-options.json':
                                    $this->wbc_import_files['wbc-import-'.$x]['theme_options'] = $file['name'];
                                    break;

                                case 'widgets.json':
                                case 'widgets.txt':
                                    $this->wbc_import_files['wbc-import-'.$x]['widgets'] = $file['name'];
                                    break;

                                case 'screen-image.png':
                                case 'screen-image.jpg':
                                case 'screen-image.gif':
                                    $this->wbc_import_files['wbc-import-'.$x]['image'] = $file['name'];
                                    break;
                            }
                        }

                        if ( !isset( $this->wbc_import_files['wbc-import-'.$x]['content_file'] ) ) {
                            unset( $this->wbc_import_files['wbc-import-'.$x] );
                            if ($x > 1) $x--;
                        }
                    }

                    $x++;
                }
            }
        }

        public function addImportFiles( $wbc_import_files )
        {
            if ( !is_array( $wbc_import_files ) || empty( $wbc_import_files ) ) {
                $wbc_import_files = [];
            }

            $wbc_import_files = wp_parse_args( $wbc_import_files, $this->wbc_import_files );

            return $wbc_import_files;
        }

        public function ajax_importer()
        {
            if (
                !isset($_REQUEST['nonce'])
                || !wp_verify_nonce($_REQUEST['nonce'], "redux_{$this->parent->args['opt_name']}_wbc_importer")
            ) {
                die( 0 );
            }

            if (
                isset($_REQUEST['type'])
                && $_REQUEST['type'] == "import-demo-content"
                && array_key_exists( $_REQUEST['demo_import_id'], $this->wbc_import_files)
            ) {

                $reimporting = false;

                if (
                    isset($_REQUEST['wbc_import'])
                    && $_REQUEST['wbc_import'] == 're-importing'
                ) {
                    $reimporting = true;
                }

                $this->active_import_id = $_REQUEST['demo_import_id'];

                $import_parts = $this->wbc_import_files[$this->active_import_id];
                $import_parts['content_file'] = $import_parts['content_file'.$_REQUEST['content']];

                $this->active_import = array( $this->active_import_id => $import_parts );

                $directory_name = $import_parts['directory'];
                $data_location = $this->demo_data_dir.$directory_name;
                if (
                    !file_exists( $data_location.'/'.$import_parts['content_file'] )
                    && !is_file( $data_location.'/'.$import_parts['content_file'] )
                ) {
                    die();
                }

                if ( !isset( $import_parts['imported'] ) || true === $reimporting ) {
                    include $this->extension_dir.'inc/init-installer.php';
                    $installer = new Radium_Theme_Demo_Data_Importer( $this, $this->parent, $_REQUEST['content'] );
                } else {
                    esc_html_e('Demo Already Imported', 'framework');
                }

                die();
            }

            die();
        }

        public static function get_instance()
        {
            return self::$instance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path( $field )
        {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }

        function add_importer_section()
        {
            // Checks to see if section was set in config of redux.
            for ( $n = 0; $n <= count( $this->parent->sections ); $n++ ) {
                if (
                    isset($this->parent->sections[$n]['id'])
                    && $this->parent->sections[$n]['id'] == 'wbc_importer_section'
                ) {
                    return;
                }
            }

            $wbc_importer_label = trim( esc_html( apply_filters( 'wbc_importer_label', __( 'Demo Importer', 'framework' ) ) ) );

            $wbc_importer_label = ( !empty( $wbc_importer_label ) ) ? $wbc_importer_label : __( 'Demo Importer', 'framework' );

            $this->parent->sections[] = array(
                'id'     => 'wbc_importer_section',
                'title'  => $wbc_importer_label,
                'desc'   => '<p>'. apply_filters( 'wbc_importer_description', __( 'Works best to import on a new install of WordPress. <br><strong>Images do not include in demo import.</strong> If you want to use images from demo content, you should check the license for every image.', 'framework' ) ).'</p>',
                'icon'   => 'el-icon-share',
                'fields' => array(
                    array(
                        'id'   => 'wbc_demo_importer',
                        'type' => 'wbc_importer'
                    )
                )
            );
        }

    } // class
} // if
