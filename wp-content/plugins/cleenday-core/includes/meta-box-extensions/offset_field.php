<?php
if ( class_exists( 'RWMB_Text_Field' ) ){
    /**
     * The text fieldset field, which allows users to enter content for a list of text fields.
     *
     * @package Meta Box
     */

    /**
     * Fieldset text class.
     */
    class RWMB_Wgl_Offset_Field extends RWMB_Text_Field {
        /**
         * Enqueue field scripts and styles.
         */
        public static function admin_enqueue_scripts() {
            wp_enqueue_style( 'rwmb-fieldset-text', RWMB_CSS_URL . 'fieldset-text.css', '', RWMB_VER );
        }

        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html( $meta, $field ) {
            $input = '';
            $attributes = self::get_attributes( $field );
            $field_mode = $field['options']['mode'];

            $meta_top = isset($meta[$field_mode.'-top']) ? $meta[$field_mode.'-top'] : '';
            $meta_right = isset($meta[$field_mode.'-right']) ? $meta[$field_mode.'-right'] : '';
            $meta_bottom = isset($meta[$field_mode.'-bottom']) ? $meta[$field_mode.'-bottom'] : '';
            $meta_left = isset($meta[$field_mode.'-left']) ? $meta[$field_mode.'-left'] : '';

            if ($field['options']['top'] == true ) {
                $input .= '<div class="rwmb-offset_field"><i class="fa fa-arrow-up"></i><input type="text" class="rwmb-wgl_offset-input" placeholder="' . esc_html__( 'Top', 'meta-box' ) . '" name="' . $field['id'] . '['.$field_mode.'-top]" value="' . $meta_top . '"><span>' . esc_html__( 'px', 'meta-box' ) . '</span></div>';
            }
            if ($field['options']['right'] == true ) {
                $input .= '<div class="rwmb-offset_field"><i class="fa fa-arrow-right"></i><input type="text" class="rwmb-wgl_offset-input" placeholder="' . esc_html__( 'Right', 'meta-box' ) . '" name="' . $field['id'] . '['.$field_mode.'-right]" value="' . $meta_right . '"><span>' . esc_html__( 'px', 'meta-box' ) . '</span></div>';
            }
            if ($field['options']['bottom'] == true ) {
                $input .= '<div class="rwmb-offset_field"><i class="fa fa-arrow-down"></i><input type="text" class="rwmb-wgl_offset-input" placeholder="' . esc_html__( 'Bottom', 'meta-box' ) . '" name="' . $field['id'] . '['.$field_mode.'-bottom]" value="' . $meta_bottom . '"><span>' . esc_html__( 'px', 'meta-box' ) . '</span></div>';
            }
            if ($field['options']['left'] == true ) {
                $input .= '<div class="rwmb-offset_field"><i class="fa fa-arrow-left"></i><input type="text" class="rwmb-wgl_offset-input" placeholder="' . esc_html__( 'Left', 'meta-box' ) . '" name="' . $field['id'] . '['.$field_mode.'-left]" value="' . $meta_left . '"><span>' . esc_html__( 'px', 'meta-box' ) . '</span></div>';
            }
                 
            $out = '<fieldset '.self::render_attributes( $attributes ).'>' . $input . '</fieldset>';

            return $out;
        }

        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize( $field ) {
            $field                       = parent::normalize( $field );
            $field['attributes']['value']   = false;
            $field['attributes']['type']   = false;
            $field['attributes']['name']   = false;
            $field['attributes']['size']   = false;
            $field['attributes']['id']   = false;
            $field['attributes']['type'] = 'text';
            return $field;
        }
    }
}

?>