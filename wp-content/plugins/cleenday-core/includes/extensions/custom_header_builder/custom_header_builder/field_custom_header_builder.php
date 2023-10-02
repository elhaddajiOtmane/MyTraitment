<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys
 * @version     3.1.5
 */

defined('ABSPATH') || exit; // Abort, if accessed directly.

// Don't duplicate me!
if ( !class_exists('ReduxFramework_custom_header_builder') ) {

    /**
     * Main ReduxFramework_custom_header_builder class
     *
     * @since 1.0.0
     */
    class ReduxFramework_custom_header_builder
    {
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        function __construct($field = [], $value = '', $parent = null)
        {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            if (empty($this->extension_dir)) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $dir = Redux_Helpers::cleanFilePath( dirname( __FILE__ ) );
                $_dir = trailingslashit( $dir );

                $wp_content_url = trailingslashit( Redux_Helpers::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) ) );

                $wp_content_dir = trailingslashit( Redux_Helpers::cleanFilePath( WP_CONTENT_DIR ) );
                $wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
                $relative_url = str_replace( $wp_content_dir, '', $_dir );
                $this->extension_url = trailingslashit( $wp_content_url . $relative_url );
            }

            // Set default args for this field to avoid bad indexes. Change this to anything you use.
            $defaults = [
                'options' => [],
                'stylesheet' => '',
                'output' => true,
                'enqueue' => true,
                'enqueue_frontend' => true
            ];
            $this->field = wp_parse_args($this->field, $defaults);

        }

        public function render() {
            // HTML output goes here
            if ( !is_array($this->value) && isset($this->field['options']) ) {
                $this->value = $this->field['options'];
            }

            // Make sure to get list of all the default blocks first
            $all_blocks = ! empty( $this->field['options'] ) ? $this->field['options'] : array();
            $temp = array(); // holds default blocks
            $temp2 = array(); // holds saved blocks

           foreach ( $all_blocks as $blocks ) {
                $temp = array_merge( $temp, $blocks );
            }

            $sortlists = $this->value;
            //Add Thickbox https://codex.wordpress.org/ThickBox
            add_thickbox();

            foreach ( $sortlists as $sortlist ) {
                $temp2 = array_merge( $temp2, $sortlist );
            }

            // now let's compare if we have anything missing
           foreach ( $temp as $k => $v ) {
                        // k = id/slug
                        // v = name
                if ( ! empty( $temp2 ) ) {
                    if ( ! array_key_exists( $k, $temp2 ) ) {
                        $sortlists['items'][ $k ] = $v;
                    }
                }
            }

            if ( $sortlists ) {
                echo '<fieldset id="' . esc_attr($this->field['id']) . '" class="redux-sorter-container redux-sorter">';
                $index = 0;

                foreach ( $sortlists as $group => $sortlist ) {

                    if($index == 0){
                    echo '<div class="wgl_header_items" style="display: none;">';
                        ?>

                    <div id="wgl_modal_builder_items" style="display:none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2><?php
                                echo apply_filters( "wgl-changed-text-modal-window-{$this->parent->args['opt_name']}", __( 'WGL Select Item', 'cleenday-core' ) );
                                ?></h2>
                            </div>
                            <div class="modal-body">


                        <?php
                    }elseif($index % 3 == 1 || $index == 1){

                        $is_dis = !isset($sortlist['disable_row']) ? 'false' : $sortlist['disable_row'];
                        $class_row = $is_dis == 'false'  ? ' enabled_row' : ' disabled_row';
                        echo '<div class="wgl_header_row'.esc_attr($class_row).'">';

                        // Row id options
                        $row_opt_id = strtolower($group);
                        switch (true) {
                            case strrpos($row_opt_id, "top") !== false:
                                $row_opt_id = 'top';
                                break;
                            case strrpos($row_opt_id, "middle") !== false:
                                $row_opt_id = 'middle';
                                break;
                            case strrpos($row_opt_id, "bottom") !== false:
                                $row_opt_id = 'bottom';
                                break;
                        }

                        echo '<div class="wgl_header_row-btn">';

                            echo '<div class="wgl_header_row-toggle"><a href="#" title="'.apply_filters("wgl-toggle-row-text", __( 'Toggle row', 'cleenday-core' )).'"><i class="wgl_arrow"></i></a></div>';

                            echo '<div class="wgl_header_row-disabled"><a href="#" title="'.apply_filters("wgl-disabled-row-text", __( 'Disabled row', 'cleenday-core' )).'"><i class="fa fa-desktop"></i></a>';
                            $params = $sortlist['disable_row'] ?? 'false';
                            echo '<input type="hidden" name="' . esc_attr($this->field['name']) . '[' . $group . '][disable_row]' . esc_attr($this->field['name_suffix']) . '" value="'.$params.'">';
                            echo '</div>';

                            echo '<div class="wgl_header_row-opt" data-section-opt="'.$row_opt_id.'"><a href="#" title="'.apply_filters("wgl-edit-row-text", __( 'Edit this row', 'cleenday-core' )).'"><i class="fas fa-pen fa"></i></a></div>';
                        echo '</div>';
                    }

                    echo '<div class="wgl_header_column-container">';

                    echo '<ul id="' . esc_attr($this->field['id'] . '_' . $group) . '" class="sortlist_' . esc_attr($this->field['id']) . '" data-id="' . esc_attr($this->field['id']) . '" data-group-id="' . esc_attr($group) . '">';

                    echo '<h3>' . esc_html($group) . '</h3>';


                    if ( ! isset( $sortlist['placebo'] ) ) {
                        array_unshift( $sortlist, array( "placebo" => "placebo" ) );
                    }

                    foreach ( $sortlist as $key => $list ) {

                        echo '<input class="sorter-placebo" type="hidden" name="' . esc_attr($this->field['name']) . '[' . $group . '][placebo]' . esc_attr($this->field['name_suffix']) . '" value="placebo">';
                        if ( $key != "placebo" && $key != 'disable_row') {

                            if(!is_array($list)){
                                $temp_list = $list;
                                $temp_list = json_decode( $temp_list, true );

                                $list = array();
                                $list['title'] = $temp_list['title'];
                                $list['settings'] = $temp_list['settings'];
                            }

                            echo '<li id="sortee-' . esc_attr($key) . '" class="sortee" data-id="' . esc_attr($key) . '">';
                            echo '<input class="position ' . esc_attr($this->field['class']) . '" type="hidden" name="' . esc_attr($this->field['name'] . '[' . $group . '][' . $key . ']' . $this->field['name_suffix']) . '" value="' . htmlspecialchars( json_encode( $list ), ENT_QUOTES, 'UTF-8' ) . '">';

                            echo esc_html($list['title']);
                            echo '<span class="icon_wrapper">';
                                if((bool) $list['settings']){
                                    echo '<i class="edit-item fas fa-pen fa fa-6" data-opt-id="' . esc_attr($key) . '"></i>';
                                }

                                echo '<i class="trash-item fas fa-trash fa fa-6"></i>';
                            echo '</span>';

                            if($group == 'items'){
                                 echo '<span class="add-item_icon"></span>';
                            }

                            echo '</li>';
                        }
                    }


                    if($index != 0){
                        /**
                        * Modal Window
                        *
                        */
                        $id = sanitize_html_class(esc_attr($this->field['name']) . '[' . $group . '][select]' . esc_attr($this->field['name_suffix']));
                        $column_opt_id = '';

                        switch ($id) {
                            case strpos($id, 'header_layoutTopLeftareaselect') !== false :
                                $column_opt_id = 'top-left';
                                break;
                            case strpos($id, 'header_layoutTopCenterareaselect') !== false :
                                $column_opt_id = 'top-center';
                                break;
                            case strpos($id, 'header_layoutTopRightareaselect') !== false:
                                $column_opt_id = 'top-right';
                                break;
                            case strpos($id, 'header_layoutMiddleLeftareaselect') !== false:
                                $column_opt_id = 'middle-left';
                                break;
                            case strpos($id, 'header_layoutMiddleCenterareaselect') !== false:
                                $column_opt_id = 'middle-center';
                                break;
                            case strpos($id, 'header_layoutMiddleRightareaselect') !== false:
                                $column_opt_id = 'middle-right';
                                break;
                            case strpos($id, 'header_layoutBottomLeftareaselect') !== false:
                                $column_opt_id = 'bottom-left';
                                break;
                            case strpos($id, 'header_layoutBottomCenterareaselect') !== false:
                                $column_opt_id = 'bottom-center';
                                break;
                            case strpos($id, 'header_layoutBottomRightareaselect') !== false:
                                $column_opt_id = 'bottom-right';
                                break;
                        }

                        echo '<span class="edit_column" data-opt-col-id="'.esc_attr($column_opt_id).'"><a href="#" title="'.apply_filters("wgl-edit-column-text", __( 'Edit this column', 'cleenday-core' )).'"><i class="fas fa fa-pen" aria-hidden="true"></i></a></span>';

                        echo '<span class="add_item"><a href="#" title="'.apply_filters("wgl-add-item", __( 'Add Item', 'cleenday-core' )).'"><i class="fas fa-plus" aria-hidden="true"></i></a></span>';
                    }

                    echo '</ul>';
                    echo '</div>';

                    if($index == 0){
                        ?>
                            <!-- .close modal window select item -->
                            </div>
                            </div>
                        </div>
                        <?php
                        echo '</div>';
                    }elseif($index % 3 == 0 && $index != 0){
                        echo '</div><!-- .wgl_header_row -->';
                    }

                    $index++;

                }

                echo '</fieldset>';
                // Modal Options items
                ?>
                <div id="wgl_modal_builder" style="display:none;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close close-btn"><?php
                                echo apply_filters( "wgl-close-text-modal-window-{$this->parent->args['opt_name']}", __( 'Save Changes', 'cleenday-core' ) );
                            ?></span>
                            <h2><?php
                            echo apply_filters( "wgl-changed-text-modal-window-{$this->parent->args['opt_name']}", __( 'WGL Header Options', 'cleenday-core' ) );
                            ?></h2>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
                <?php
            }
        }

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {
            wp_enqueue_script(
                'redux-field-icon-select-js',
                $this->extension_url . 'field_custom_header_builder.js',
                array( 'jquery' ),
                time(),
                true
            );

            $vars = array(
                'delete' => __( 'Are you sure want to delete the element?', 'cleenday-core' ),
            );

            wp_localize_script( 'redux-field-icon-select-js', 'wglBuilderVars', $vars );

            wp_enqueue_style(
                'redux-field-icon-select-css',
                $this->extension_url . 'field_custom_header_builder.css',
                time(),
                true
            );

        }

        /**
         * Output Function.
         *
         * Used to enqueue to the front-end
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function output() {

            if ( $this->field['enqueue_frontend'] ) {

            }

        }

    }
}
