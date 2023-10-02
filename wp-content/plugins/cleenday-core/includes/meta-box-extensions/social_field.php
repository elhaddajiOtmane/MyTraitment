<?php 

if ( class_exists( 'RWMB_Field' ) )
{
    /**
     * Social field class which uses an input for file URL.
     */
    class RWMB_Social_Field extends RWMB_Field
    {
        /**
         * Get field HTML
         *
         * @param mixed $meta
         * @param array $field
         *
         * @return string
         */
        static public function html( $meta, $field ){
            $return = '<fieldset>';
            
            foreach ($field['options'] as $key => $value) {
                $style = 'display:inline-block; padding: 0 20px 0 0;';
                $meta = is_array($meta) ? $meta : array();
                $meta[$key] = isset($meta[$key]) ? $meta[$key] : ''; 
                
                $return .= '<label style="'.esc_attr($style).'">'.$value["name"].'<br>';
                $return .= '<input '.($field['clone'] ? 'class="rwmb-fieldset_text"' : '').' id="'.$field['id'].'" type="'.$value["type_input"].'" name="'.$field['field_name'].'['.$key.']" value="'.$meta[$key].'">';
                
                $return .= '</label>';
            }

            $return .= '</fieldset>';
            
            return $return;
        }
    }
}

