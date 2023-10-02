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
    class RWMB_Wgl_Font_Field extends RWMB_Text_Field {
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
            $input = $out = '';
            $attributes = self::get_attributes( $field );
            
            $meta_font_size = isset($meta['font-size']) ? $meta['font-size'] : '';
            $meta_line_height = isset($meta['line-height']) ? $meta['line-height'] : '';
            $meta_font_weight = isset($meta['font-weight']) ? $meta['font-weight'] : '';
            $meta_color = isset($meta['color']) ? $meta['color'] : '';

            if ($field['options']['font-size'] == true ) {
                $input .= '<div class="rwmb-font_field"><label>' . esc_html__( 'Font Size', 'meta-box' ) . '</label><input type="text" class="rwmb-wgl_font-input" placeholder="' . esc_html__( 'Size', 'meta-box' ) . '" name="' . $field['id'] . '[font-size]" value="' . $meta_font_size . '"><span>' . esc_html__( 'px', 'meta-box' ) . '</span></div>';
            }
            if ($field['options']['line-height'] == true ) {
                $input .= '<div class="rwmb-font_field"><label>' . esc_html__( 'Line Height', 'meta-box' ) . '</label><input type="text" class="rwmb-wgl_font-input" placeholder="' . esc_html__( 'Height', 'meta-box' ) . '" name="' . $field['id'] . '[line-height]" value="' . $meta_line_height . '"><span>' . esc_html__( 'px', 'meta-box' ) . '</span></div>';
            }

            $repeat = RWMB_Select_Field::normalize( array(
                'type'        => 'select',
                'field_name'  => "{$field['field_name']}[font-weight]",
                'options'     => array(
                    '100' => esc_html__( '100', 'meta-box' ),
                    '300' => esc_html__( '300', 'meta-box' ),
                    '400' => esc_html__( '400', 'meta-box' ),
                    '500' => esc_html__( '500', 'meta-box' ),
                    '600' => esc_html__( '600', 'meta-box' ),
                    '700' => esc_html__( '700', 'meta-box' ),
                ),
            ) );

            if ($field['options']['font-weight'] == true ) {
                $input .= '<div class="rwmb-font_field"><label>' . esc_html__( 'Font Weight', 'meta-box' ) . '</label><div class="rwmb-select_wrap">';
                $input .= RWMB_Select_Field::html( $meta_font_weight, $repeat );
                $input .= '</div></div>';
            }
            
            // Color.
            $color  = RWMB_Color_Field::normalize( array(
                'type'       => 'color',
                'field_name' => "{$field['field_name']}[color]",
            ) );

            if ($field['options']['color'] == true ) {
                $input .= '<div class="rwmb-font_color"><label>' . esc_html__( 'Font Color', 'meta-box' ) . '</label>';
                $input .= RWMB_Color_Field::html( $meta_color, $color ); 
                $input .= '</div>';
            }
                
            $out .= '<fieldset '.self::render_attributes( $attributes ).'>' . $input . '</fieldset>';
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