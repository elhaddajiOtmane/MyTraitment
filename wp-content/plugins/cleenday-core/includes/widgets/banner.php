<?php

class Banner extends WP_Widget
{
	function __construct()
	{
		parent::__construct(
			'combined_image_banner_widget', // Base ID
			esc_html__('WGL Banner', 'cleenday-core' ), // Name
			[ 'description' => esc_html__('WGL Widget', 'cleenday-core' ), ] // Args
		);

		if (is_admin() === TRUE) {
			add_action('admin_enqueue_scripts', array($this, 'enqueue_backend_scripts') );
		}
	}

	public function enqueue_backend_scripts()
	{
		wp_enqueue_media(); //Enable the WP media uploader
		wp_enqueue_script('cleenday-upload-img', get_template_directory_uri() . '/core/admin/js/img_upload.js', array('jquery'), false, true);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see   WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		$title_name = 'title';
		$text_name = 'text';
		
		$padding_top_value = 'padding_top_value';
		$padding_bottom_value = 'padding_bottom_value';
		
		$image_name = 'image';
		$image_name_2 = 'image_2';

		$attachment_id = attachment_url_to_postid ($instance[$image_name]);
		// if no alt attribute is filled out then echo "Featured Image of article: Article Name"
		if ('' === get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) {
			$alt = the_title_attribute( ['before' => esc_html__('Featured image: ', 'cleenday-core' ), 'echo' => false] );
		} else {
			$alt = trim(strip_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)));
		}

		$widgetImg = ! empty($instance[$image_name]) ? '<img class="banner-widget_img" src="' .esc_url($instance[$image_name]). '" alt="' .esc_attr($alt). '">' : '';
		$widgetImg_2 = ! empty($instance[$image_name_2]) ? 'background-image: url('.$instance[$image_name_2].');' : '';

		$button_text = $instance[$title_name] ?? '';
		$text = $instance[$text_name] ?? '';

		$padding_top = isset($instance[$padding_top_value]) && $instance[$padding_top_value] !== '' ? 'padding-top: ' . (int)$instance[$padding_top_value]. 'px;' : '';
		$padding_bottom = isset($instance[$padding_bottom_value]) && $instance[$padding_bottom_value] !== ''  ? 'padding-bottom: '. (int)$instance[$padding_bottom_value]. 'px;' : '';

		$widgetImg_2 .= $padding_top . $padding_bottom;

		$text_sub_title = $instance['text_sub_title'] ?? '';
		$button_link = $instance['button_link'] ?? '';

		$widgetClass = empty($widgetImg) ? ' without_logotype' : ''; ?>
		<div class="cleenday_banner-widget cleenday_widget widget<?php echo esc_attr($widgetClass); ?>">
        <div class="banner_widget" style="<?php echo esc_attr($widgetImg_2); ?>"><?php

			if ($button_link) { ?>
				<a href="<?php echo esc_url($button_link); ?>" class="banner-widget__link"></a><?php
			}

			if ($widgetImg) { ?>
				<div class="banner-widget_img-wrapper"><?php
					 echo $widgetImg; ?>
				</div><?php
			}

			if ($text_sub_title) { ?>
				<p class="banner-widget_text_sub"><?php
					 echo $text_sub_title; ?>
				</p><?php
			}

			if ($text) { ?>
				<h2 class="banner-widget_text"><?php
					 echo $text; ?>
				</h2><?php
			}

			if ($button_text) { ?>
				<div class="banner-widget_button">
					<span><?php echo esc_html($button_text); ?></span>
				</div><?php
			} ?>
		</div>
		</div><?php
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		/* Default widget settings. */
		$defaults = [
			'padding_top_value' => '32',
			'padding_bottom_value' => '40',
		];

		$instance = wp_parse_args((array) $instance, $defaults);

		$title_name = 'title';
		$title = $instance[$title_name] ?? '';

		$padding_top = 'padding_top_value';
		$padding_top_value = $instance[$padding_top] ?? '';

		$padding_bottom = 'padding_bottom_value';
		$padding_bottom_value = $instance[$padding_bottom] ?? '';

		$text_name = 'text';
		$text = $instance[$text_name] ?? '';

		$text_sub_title = 'text_sub_title';
		$text_sub = $instance[$text_sub_title] ?? '';

		$image_name = 'image';
		$image = $instance[$image_name] ?? '';

		$image_name_2 = 'image_2';
		$image_2 = $instance[$image_name_2] ?? '';

		$button_link = $instance['button_link'] ?? '';
		
		?>
		<p>
		  <label for="<?php echo esc_attr($this->get_field_id($image_name) ); ?>"><?php esc_html_e('Add Logo image:', 'cleenday-core')?></label><br />
			<img class="cleenday_media_image" src="<?php if(! empty($instance[$image_name])){echo esc_url( $instance[$image_name] );} ?>" style="max-width: 100%" />
			<input type="text" class="widefat cleenday_media_url" name="<?php echo esc_attr($this->get_field_name($image_name) ); ?>" id="<?php echo esc_attr($this->get_field_id($image_name) ); ?>" value="<?php echo esc_attr($image ); ?>">
			<a href="#" class="button cleenday_media_upload"><?php esc_html_e('Upload', 'cleenday-core'); ?></a>
		</p>

		<p>
		  <label for="<?php echo esc_attr($this->get_field_id($image_name_2)); ?>"><?php esc_html_e('Add background image:', 'cleenday-core')?></label><br />
			<img class="cleenday_media_image" src="<?php if(! empty($instance[$image_name_2])){echo esc_url( $instance[$image_name_2] );} ?>" style="max-width: 100%" />
			<input type="text" class="widefat cleenday_media_url" name="<?php echo esc_attr($this->get_field_name($image_name_2) ); ?>" id="<?php echo esc_attr($this->get_field_id($image_name_2) ); ?>" value="<?php echo esc_attr($image_2 ); ?>">
			<a href="#" class="button cleenday_media_upload"><?php esc_html_e('Upload', 'cleenday-core'); ?></a>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( $padding_top )); ?>"><?php esc_html_e('Padding Top: ', 'cleenday-core')?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( $padding_top ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $padding_top ) ); ?>" type="number" value="<?php echo esc_attr($padding_top_value ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( $padding_bottom )); ?>"><?php esc_html_e('Padding Bottom: ', 'cleenday-core')?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( $padding_bottom ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $padding_bottom ) ); ?>" type="number" value="<?php echo esc_attr($padding_bottom_value ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( $text_sub_title )); ?>"><?php esc_html_e('Sub Title:', 'cleenday-core')?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id( $text_sub_title ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $text_sub_title ) ); ?>" row="1"><?php echo Cleenday_Theme_Helper::render_html($text_sub); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( $text_name )); ?>"><?php esc_html_e('Title:', 'cleenday-core')?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id( $text_name ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $text_name ) ); ?>" row="2"><?php echo Cleenday_Theme_Helper::render_html($text); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( $title_name )); ?>"><?php esc_html_e('Button Text:', 'cleenday-core')?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( $title_name ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $title_name ) ); ?>" type="text" value="<?php echo esc_attr($title ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'button_link' ) ); ?>"><?php esc_html_e('Banner Link:', 'cleenday-core'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'button_link' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'button_link' ) ); ?>" type="text" value="<?php echo esc_attr($button_link ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) 
	{
		return $new_instance;
	}
}

function banner_register_widgets() {
	register_widget('banner');
}

add_action('widgets_init', 'banner_register_widgets');
