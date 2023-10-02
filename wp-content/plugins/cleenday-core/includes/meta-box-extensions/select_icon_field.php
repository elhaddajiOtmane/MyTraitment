<?php 

if ( class_exists( 'RWMB_Field' ) )
{
    /**
     * Extends Field Metabox io https://github.com/wpmetabox/meta-box/blob/master/inc/fields/select-advanced.php
     *
     * @since    1.0.0
     * @access   public
     */    
    
    /**
     * Select Icon advanced field
     */
    class RWMB_Select_Icon_Field extends RWMB_Select_Field {

        /**
         * Enqueue scripts and styles
         */
        public static function admin_enqueue_scripts() {
            parent::admin_enqueue_scripts();
            wp_enqueue_style( 'rwmb-select2', RWMB_CSS_URL . 'select2/select2.css', array(), '4.0.1' );
            wp_enqueue_style( 'rwmb-select-advanced', RWMB_CSS_URL . 'select-advanced.css', array(), RWMB_VER );

            wp_register_script( 'rwmb-select2', RWMB_JS_URL . 'select2/select2.min.js', array( 'jquery' ), '4.0.2', true );

            // Localize
            $dependencies = array( 'rwmb-select2', 'rwmb-select' );
            $locale       = str_replace( '_', '-', get_locale() );
            $locale_short = substr( $locale, 0, 2 );
            $locale       = file_exists( RWMB_DIR . "js/select2/i18n/$locale.js" ) ? $locale : $locale_short;

            if ( file_exists( RWMB_DIR . "js/select2/i18n/$locale.js" ) ) {
                wp_register_script( 'rwmb-select2-i18n', RWMB_JS_URL . "select2/i18n/$locale.js", array( 'rwmb-select2' ), '4.0.2', true );
                $dependencies[] = 'rwmb-select2-i18n';
            }

            wp_enqueue_script( 'rwmb-select-advanced', RWMB_JS_URL . 'select-advanced.js', $dependencies, RWMB_VER, true );
        }

        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html( $meta, $field ) {
            $meta = is_array($meta) ? $meta : array();
            $options                     = self::transform_options( $field['options'] );
            $attributes                  = self::call( 'get_attributes', $field, $meta );
            $attributes['data-selected'] = $meta;
            $attributes_select = $attributes;
            $attributes_select['name'] = $attributes['name'].'[select]';
            $walker                      = new RWMB_Walker_Select( $field, $meta );
            $output                      = sprintf(
                '<div class="clone-select">'.esc_html__('Icon', 'cleenday-core').'<br /><select %s>',
                self::render_attributes( $attributes_select )
            );
            if ( ! $field['multiple'] && $field['placeholder'] ) {
                $output .= '<option value="">' . esc_html( $field['placeholder'] ) . '</option>';
            }
            $output .= $walker->walk( $options, $field['flatten'] ? -1 : 0 );
            $output .= '</select></div>';
            $meta['link'] = empty($meta['link']) ? '' : $meta['link'];
            $output .= '<label class="select-label">'.esc_html__('Link', 'cleenday-core').'<br />';
            $output .= '<input type="text" '.($field['clone'] ? 'class="rwmb-fieldset_text"' : '').' id="'.$field['id'].'" name="'.$attributes['name'].'[link]'.'" value="'.$meta['link'].'">';
            $output .= '</label>';
            $output .= self::get_select_all_html( $field );
            return $output;
        }

        /**
         * Normalize parameters for field
         *
         * @param array $field
         * @return array
         */
        public static function normalize( $field ) {
            $field = wp_parse_args( $field, array(
                'js_options'  => array(),
                'placeholder' => __( 'Select an icon', 'cleenday-core' ),
            ) );

            $field = parent::normalize( $field );

            $field['js_options'] = wp_parse_args( $field['js_options'], array(
                'allowClear'  => true,
                'width'       => 'none',
                'placeholder' => $field['placeholder'],
            ) );

            return $field;
        }

        /**
         * Get the attributes for a field
         *
         * @param array $field
         * @param mixed $value
         * @return array
         */
        public static function get_attributes( $field, $value = null ) {
            $attributes = parent::get_attributes( $field, $value );
            $attributes['id']   = false;
            $attributes = wp_parse_args( $attributes, array(
                'data-options' => wp_json_encode( $field['js_options'] ),
            ) );

            return $attributes;
        }
    }
}

?>