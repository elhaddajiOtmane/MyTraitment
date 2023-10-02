<?php

namespace WglAddons\Includes;

defined('ABSPATH') || exit;

if (!class_exists('WGL_WPML_Settings')) {
    /**
     * WGL Elementor module settings for WPML compatibility
     *
     *
     * @package cleenday-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.4
     * @version 1.0.0
     */
    class WGL_WPML_Settings
    {
        private static $instance;

		public static function wpml_type_field($type) {

			switch ($type) {
				case 'text':
					$editor_type = 'LINE';
					break;
				case 'textarea':
					$editor_type = 'AREA';
					break;
				case 'wysiwyg':
					$editor_type = 'VISUAL';
					break;
				case 'url':
						$editor_type = 'LINK';
						break;
				default:
					$editor_type = 'LINE';
			}

			return $editor_type;
		}

		public static function get_translate($self, $widgets ) {

			if (!$self) {
                // Bailout.
                return;
            }

			$wpml_settings = [];
			$controls_data = [];

			foreach ( $self->get_controls() as $name => $control ) {
				if( is_array($control) && 'advanced' !== $control['tab'] && (!isset($control['of_type'])) ){					
					if( 'repeater' === $control['type']
					|| 'text' === $control['type']
					|| 'textarea' === $control['type']
					|| 'wysiwyg' === $control['type']
					|| 'url' === $control['type']
					){
						if('repeater' === $control['type']){
							$wpml_settings['name'] = $control['name'];
							foreach( $control['fields'] as $key => $value ){
								if('text' === $value['type']
								|| 'textarea' === $value['type']
								|| 'wysiwyg' === $value['type']
								|| 'url' === $value['type']
								){
									$wpml_settings['fields'][] = [
										'name'  => 'url' === $value['type'] ? array($value['name']) : $value['name'],
										'label' => $value['label'] ?? esc_html__('WGL Module', 'cleenday-core'),
										'type'  => self::wpml_type_field($value['type']),
										'module_name' => $self->get_title()
									];										
								}
							}
						}else{ 
							$editor_type = self::wpml_type_field($control['type']);
							if('url' === $control['type']){
								$controls_data[$control['name']] = [
									'field'       => 'url',
									'type'        => $control['label'] ?? esc_html__('WGL Module', 'cleenday-core'),
									'editor_type' => $editor_type,
								];		
							}else{
								$controls_data[] = [
									'field'       => $control['name'],
									'type'        => $control['label'] ?? esc_html__('WGL Module', 'cleenday-core'),
									'editor_type' => $editor_type,
								];								
							}

						}
					}
				}
			}

			if(!empty($controls_data) || !empty($wpml_settings)){
				$widgets[ $self->get_name() ] = [
					'conditions' => [ 'widgetType' => $self->get_name() ],
					'fields'     => $controls_data,
				];

				if(!empty($wpml_settings)){
					$widgets[$self->get_name() ]['integration-class'] = new \WglAddons\Includes\WGL_Elementor_Translate($wpml_settings);
				}
			}
			
			return $widgets;
		}


        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }
    }
}

if (!class_exists('WGL_Elementor_Translate')) {
    class WGL_Elementor_Translate extends \WPML_Elementor_Module_With_Items {

		private $wgl_name;
		private $wgl_fields;

		public function __construct($params){
			$this->wgl_name = !empty($params) && isset($params['name']) ? $params['name'] : '';
			$this->wgl_fields['fields'] = !empty($params) && isset($params['fields']) ? $params['fields'] : [];
		}

        /**
         * @return string
         */
        public function get_items_field() {
            return $this->wgl_name;
        }

        /**
         * @return array
         */
        public function get_fields() {
			$name = [];

			if(!empty($this->wgl_fields['fields'])){
				foreach( $this->wgl_fields['fields'] as $key => $value ){	
					if(is_array($value['name'])){
						$name[$value['name'][0]] = array( 'url' );
					}else{
						$name[] = $value['name'];
					}
				}
			}
			return $name;
        }

        /**
         * @param string $field
         *
         * @return string
         */
        protected function get_title( $field ) {
			if(!empty($this->wgl_fields['fields'])){
				foreach( $this->wgl_fields['fields'] as $key => $value ){
					$name = is_array($value['name']) ? $value['name'][0] : $value['name'];
					if($field === $name){
						return $value['label'];
					}elseif('url' === $field){
						return esc_html__( $value['module_name'] . ': link URL', 'cleenday-core' );
					}
				}
			}

			return '';
        }

        /**
         * @param string $field
         *
         * @return string
         */
        protected function get_editor_type( $field ) {
			if(!empty($this->wgl_fields['fields'])){
				foreach( $this->wgl_fields['fields'] as $key => $value ){
					$name = is_array($value['name']) ? $value['name'][0] : $value['name'];
					if($field === $name){
						return $value['type'];
					}elseif('url' === $field){
						return 'LINK';
					}
				}
			}

			return '';
        }
    }

}
