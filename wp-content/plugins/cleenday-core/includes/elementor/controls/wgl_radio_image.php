<?php
namespace WglAddons\Controls;

defined('ABSPATH') || exit;

use Elementor\Base_Data_Control;

/**
 * WGL Elementor Radio Image Control
 *
 *
 * @category Class
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Wgl_Radio_Image extends Base_Data_Control
{
    /**
     * Get radio image control type.
     *
     * Retrieve the control type, in this case `radio-image`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function get_type() {
        return 'wgl-radio-image';
    }

    public function enqueue() {
        // Scripts
        wp_enqueue_script( 'wgl-elementor-extensions', WGL_ELEMENTOR_ADDONS_URL . 'assets/js/wgl_elementor_extenstions.js');

        // Style
        wp_enqueue_style( 'wgl-elementor-extensions', WGL_ELEMENTOR_ADDONS_URL . 'assets/css/wgl_elementor_extenstions.css');
    }

    /**
     * Get radio image control default settings.
     *
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function get_default_settings() {
        return [ 'label_block' => true ];
    }

    /**
     * Render radio image control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     * @access public
     */
    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>

        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <div class="wgl-radio-image">
                    <# _.each( data.options, function( option_params, option_value ) {
                        var value       = data.controlValue;
                        var selected = ( option_value === value ) ? 'selected' : '';

                        #>
                        <label class="{{ selected }}"><img class="select-image" src="{{ option_params.image }}" alt=""/><input id="<?php echo esc_attr( $control_uid ); ?>" type="radio" name="{{  data.name  }}" class="elementor-control-tag-area elementor_param_value display_none"  data-setting="{{ data.name }}" value="{{ option_value }}" /><span>{{option_params.title}}</span></label>
                        <# 

                    }); #>                    
                </div>

            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
