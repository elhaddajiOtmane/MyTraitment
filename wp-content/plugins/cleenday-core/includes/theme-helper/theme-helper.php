<?php

class WglThemeHelper
{
    protected static $instance;

    /**
     * @var \WP_Post
     */
    private $post_id;

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct ()
    {
        $this->post_id = get_the_ID();
    }

    /*-----------------------------------------------------------------------------------*/
    /* WGL Fix FTP FileSystem
	/*-----------------------------------------------------------------------------------*/
    public function exists($file)
    {
        return file_exists($file) ? true : false;
    }

    public function get_file_system()
    {
        return new WglThemeHelper();
    }

    public function get_contents($file)
    {
        return @file_get_contents($file);
    }
	/*-----------------------------------------------------------------------------------*/
	/* \End WGL Fix FTP FileSystem
	/*-----------------------------------------------------------------------------------*/

    public function render_post_share($show_share = false)
    {
	    $img_url = wp_get_attachment_image_src(get_post_thumbnail_id($this->post_id), 'single-post-thumbnail')[0] ?? '';
	    $shares_arr = Cleenday_Theme_Helper::get_option('post_shares');
	    $def_shares = [
		    'telegram' => '0',
		    'reddit' => '0',
		    'whatsapp' => '0',
		    'twitter' => '1',
		    'facebook' => '1',
		    'pinterest' => '1',
		    'linkedin' => '1',
	    ];
	    $shares = array_merge($def_shares ,array_intersect_key( (array) $shares_arr, $def_shares));
	
	    if ($show_share == "1" || $show_share == "yes") : ?>
            <div class="share_social-wpapper">
			    <?php if ((bool)$shares['telegram']) { ?>
                    <!-- Telegram -->
                    <a class="share_link share_telegram" target="_blank" href="<?php echo esc_url('https://telegram.me/share/url?url=<URL>&amp;text='. get_the_title() .'&amp;url='. get_permalink()); ?>"><span class="fab fa-telegram-plane"><span class="share_name"><?php esc_html_e('Telegram', 'cleenday-core'); ?></span></span></a>
			    <?php }
			    
			    if ((bool)$shares['reddit']) { ?>
                    <!-- Reddit -->
                    <a class="share_link share_reddit" target="_blank" href="<?php echo esc_url('https://reddit.com/submit?url=<URL>&amp;title='. get_the_title() .'&amp;url='. get_permalink()); ?>"><span class="fab fa-reddit-alien"><span class="share_name"><?php esc_html_e('Reddit', 'cleenday-core'); ?></span></span></a>
			    <?php }
			    
			    if ((bool)$shares['whatsapp']) { ?>
                    <!-- WhatsApp -->
                    <a class="share_link share_whatsapp" target="_blank" href="<?php echo esc_url('https://wa.me/?text='. get_permalink().' - '.get_the_title()); ?>"><span class="fab fa-whatsapp"><span class="share_name"><?php esc_html_e('Whatsapp', 'cleenday-core'); ?></span></span></a>

                <?php }
			    
			    if ((bool)$shares['twitter']) { ?>
                    <!-- Twitter -->
                    <a class="share_link share_twitter" target="_blank" href="<?php echo esc_url('https://twitter.com/intent/tweet?text='. get_the_title() .'&amp;url='. get_permalink()); ?>"><span class="fab fa-twitter"><span class="share_name"><?php esc_html_e('Twitter', 'cleenday-core'); ?></span></span></a>
			    <?php }
			    
			    if ((bool)$shares['facebook']) { ?>
                    <!-- Facebook -->
                    <a class="share_link share_facebook" target="_blank" href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u='. get_permalink()); ?>"><span class="fab fa-facebook-f"><span class="share_name"><?php esc_html_e('Facebook', 'cleenday-core'); ?></span></span></a>
			    <?php }
			    
			    if (strlen($img_url) > 0 && (bool)$shares['pinterest']) { ?>
				    <!-- Pinterest -->
				    <a class="share_link share_pinterest" target="_blank" href="<?php echo esc_url('https://pinterest.com/pin/create/button/?url='. get_permalink() .'&media='. $img_url); ?>"><span class="fab fa-pinterest-p"><span class="share_name"><?php esc_html_e('Pinterest', 'cleenday-core'); ?></span></span></a><?php
			    }
			    
			    if ((bool)$shares['linkedin']) { ?>
                    <!-- LinkedIn -->
                    <a class="share_link share_linkedin" href="<?php echo esc_url('http://www.linkedin.com/shareArticle?mini=true&url='.substr(urlencode( get_permalink() ),0,1024));?>&title=<?php echo esc_attr(substr(urlencode(html_entity_decode(get_the_title())),0,200));?>" target="_blank" ><span class="fab fa-linkedin-in fa-linkedin"><span class="share_name"><?php esc_html_e('Linkedin', 'cleenday-core'); ?></span></span></a>
			    <?php } ?>
            </div>
	    <?php
	    endif;
    }

    public function render_post_list_share()
	{
		$img_url = wp_get_attachment_image_src(get_post_thumbnail_id($this->post_id), 'single-post-thumbnail')[0] ?? '';
		$shares_arr = Cleenday_Theme_Helper::get_option('post_shares');
		$def_shares = [
			'telegram' => '0',
			'reddit' => '0',
			'whatsapp' => '0',
			'twitter' => '1',
			'facebook' => '1',
			'pinterest' => '1',
			'linkedin' => '1',
		];
		$shares = array_merge($def_shares ,array_intersect_key( (array) $shares_arr, $def_shares));
		
		?>
        <div class="share_post-container">
            <a href="#"></a>
            <div class="share_social-wpapper">
                <ul>
					<?php if ((bool)$shares['telegram']) { ?>
                        <!-- Telegram -->
                        <li>
                            <a class="share_post share_telegram" target="_blank" href="<?php echo esc_url('https://telegram.me/share/url?url=<URL>&amp;text='. get_the_title() .'&amp;url='. get_permalink()); ?>">
                                <span class="fab fa-telegram-plane"><span class="share_name"><?php esc_html_e('Telegram', 'cleenday-core'); ?></span></span>
                            </a>
                        </li>
					<?php }
					
					if ((bool)$shares['reddit']) { ?>
                        <!-- Reddit -->
                        <li>
                            <a class="share_post share_reddit" target="_blank" href="<?php echo esc_url('https://reddit.com/submit?url=<URL>&amp;title='. get_the_title() .'&amp;url='. get_permalink()); ?>">
                                <span class="fab fa-reddit-alien"><span class="share_name"><?php esc_html_e('Reddit', 'cleenday-core'); ?></span></span>
                            </a>
                        </li>
					<?php }
					
					if ((bool)$shares['whatsapp']) { ?>
                        <!-- WhatsApp -->
                        <li>
                            <a class="share_post share_whatsapp" target="_blank" href="<?php echo esc_url('https://wa.me/?text='. get_permalink().' - '.get_the_title()); ?>">
                                <span class="fab fa-whatsapp"><span class="share_name"><?php esc_html_e('Whatsapp', 'cleenday-core'); ?></span></span>
                            </a>
                        </li>
					<?php }
					
					if ((bool)$shares['twitter']) { ?>
                        <!-- Twitter -->
                        <li>
                            <a class="share_post share_twitter" target="_blank" href="<?php echo esc_url('https://twitter.com/intent/tweet?text='. get_the_title() .'&amp;url='. get_permalink()); ?>">
                                <span class="fab fa-twitter"><span class="share_name"><?php esc_html_e('Twitter', 'cleenday-core'); ?></span></span>
                            </a>
                        </li>
					<?php }
					
					if ((bool)$shares['facebook']) { ?>
                        <!-- Facebook -->
                        <li>
                            <a class="share_post share_facebook" target="_blank" href="<?php echo  esc_url('https://www.facebook.com/share.php?u='. get_permalink()); ?>">
                                <span class="fab fa-facebook-f"><span class="share_name"><?php esc_html_e('Facebook', 'cleenday-core'); ?></span></span>
                            </a>
                        </li>
					<?php }
					
					if (strlen($img_url) > 0 && (bool)$shares['pinterest']) { ?>
						<!-- Pinterest -->
						<li>
						<a class="share_post share_pinterest" target="_blank" href="<?php echo esc_url('https://pinterest.com/pin/create/button/?url='. get_permalink() .'&media='. $img_url); ?>"><span class="fab fa-pinterest-p"><span class="share_name"><?php esc_html_e('Pinterest', 'cleenday-core'); ?></span></span></a>
						</li><?php
					}
					
					if ((bool)$shares['linkedin']) { ?>
                        <!-- LinkedIn -->
                        <li>
                            <a class="share_post share_linkedin" target="_blank" href="<?php echo esc_url('http://www.linkedin.com/shareArticle?mini=true&url='.substr(urlencode( get_permalink() ),0,1024));?>&title=<?php echo esc_attr(substr(urlencode(html_entity_decode(get_the_title())),0,200));?>">
                                <span class="fab fa-linkedin-in fa-linkedin"><span class="share_name"><?php esc_html_e('Linkedin', 'cleenday-core'); ?></span></span>
                            </a>
                        </li>
					<?php } ?>
                </ul>
            </div>
        </div>
		<?php
	}

    public static function render_social_shares()
    {
        $visibility = Cleenday_Theme_Helper::get_mb_option('soc_icon_style', 'mb_customize_soc_shares', 'on');
        $facebook = Cleenday_Theme_Helper::get_mb_option('soc_icon_facebook', 'mb_customize_soc_shares', 'on');
        $twitter = Cleenday_Theme_Helper::get_mb_option('soc_icon_twitter', 'mb_customize_soc_shares', 'on');
        $linkedin = Cleenday_Theme_Helper::get_mb_option('soc_icon_linkedin', 'mb_customize_soc_shares', 'on');
        $tumblr = Cleenday_Theme_Helper::get_mb_option('soc_icon_tumblr', 'mb_customize_soc_shares', 'on');

        $pinterest = Cleenday_Theme_Helper::get_mb_option('soc_icon_pinterest', 'mb_customize_soc_shares', 'on');
        if ($pinterest) {
            $img_url = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
            $pinterest = $pinterest && $img_url;
        }

        $offset = Cleenday_Theme_Helper::get_option('soc_icon_offset');
        if (
            class_exists('RWMB_Loader')
            && get_queried_object_id() !== 0
            && rwmb_meta('mb_customize_soc_shares') == 'on'
        ) {
            $offset = [];
            $offset['margin-top'] = rwmb_meta('mb_soc_icon_offset');
            $offset['units'] = rwmb_meta('mb_soc_icon_offset_units') == 'percent' ? '%' : 'px';
        }

        $share_class = !empty($offset['units']) && $offset['units'] == '%' ? ' fixed' : '';
        $share_class .= $visibility == 'hovered' ? ' appearence-hovered' : '';

        $style = isset($offset['margin-top']) && $offset['margin-top'] !== '' ? 'top: '.(int)$offset['margin-top'].$offset['units'].';' : '';
        $style = $style ? ' style="' . $style . '"' : '';

        echo '<section class="wgl-page-socials', esc_attr($share_class), '"', $style, '>';
            if ($visibility == 'hovered') {
                echo '<div class="socials__desc">',
                    '<span class="social__icon fas fa-share-alt"></span>',
                    '<span class="social__name">',
                        apply_filters('cleenday/socials/description', esc_html__('Socials', 'cleenday-core')),
                    '</span>',
                '</div>';
            }

            echo '<ul class="socials__list">';

                if ($twitter) :
                    echo '<li>',
                        '<a class="social__link twitter"',
                            ' href="', esc_url('https://twitter.com/intent/tweet?text=' . get_the_title() . '&amp;url=' . get_permalink()), '"',
                            ' target="_blank"',
                            '>',
                            '<span class="social__icon fab fa-twitter"></span>',
                            '<span class="social__name">',
                                esc_html__('Twitter', 'cleenday-core'),
                            '</span>',
                        '</a>',
                    '</li>';
                endif;

                if ($facebook) :
                    echo '<li>',
                        '<a class="social__link facebook"',
                            ' href="', esc_url('https://www.facebook.com/share.php?u=' . get_permalink()), '"',
                            ' target="_blank"',
                            '>',
                            '<span class="social__icon fab fa-facebook-f"></span>',
                            '<span class="social__name">',
                                esc_html__('Facebook', 'cleenday-core'),
                            '</span>',
                        '</a>',
                    '</li>';
                endif;

                if ($pinterest) :
                    echo '<li>',
                        '<a class="social__link pinterest"',
                            ' href="' . esc_url('https://pinterest.com/pin/create/button/?url=' . get_permalink() .'&media='. $img_url) .'"',
                            ' target="_blank"',
                            '>',
                            '<span class="social__icon fab fa-pinterest-p"></span>',
                            '<span class="social__name">',
                                esc_html__('Pinterest', 'cleenday-core'),
                            '</span>',
                        '</a>',
                    '</li>';
                endif;

                if ($linkedin) {
                    echo '<li>',
                        '<a class="social__link linkedin"',
                            ' target="_blank"',
                            ' href="', esc_url('http://www.linkedin.com/shareArticle?mini=true&url=' . substr(urlencode( get_permalink() ),0,1024));?>&title=<?php echo esc_attr(substr(urlencode(html_entity_decode(get_the_title())),0,200)), '"',
                            '>',
                            '<span class="social__icon fab fa-linkedin-in"></span>',
                            '<span class="social__name">',
                                esc_html__('Linkedin', 'cleenday-core'),
                            '</span>',
                        '</a>',
                    '</li>';
                }

                if ($tumblr) {
                    echo '<li>',
                        '<a class="social__link tumblr"',
                            ' target="_blank"',
                            ' href="', esc_url('http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()). '&amp;name=' . urlencode(get_the_title()) .'&amp;description='.urlencode(get_the_excerpt()) ), '"',
                            '>',
                            '<span class="social__icon fab fa-tumblr"></span>',
                            '<span class="social__name">',
                                esc_html__('Tumblr', 'cleenday-core'),
                            '</span>',
                        '</a>',
                    '</li>';
                }

                $custom_share = Cleenday_Theme_Helper::get_option('add_custom_share');
                if ($custom_share) {
                    for ($i = 1; $i <= 6; $i++) {
                        ${'share_name'.$i} = Cleenday_Theme_Helper::get_option('share_name-'.$i);
                        ${'share_link'.$i} = Cleenday_Theme_Helper::get_option('share_link-'.$i);
                        ${'share_icon'.$i} = Cleenday_Theme_Helper::get_option('share_icons-'.$i);

                        if (!empty(${'share_link'.$i})) {
                            echo '<li>',
                                '<a class="social__link custom"',
                                    ' href="', esc_url(${'share_link'.$i}), '"',
                                    '>',
                                    '<span class="social__icon ', esc_attr(${'share_icon'.$i}), '"></span>',
                                    '<span class="social__name">',
                                        esc_html(${'share_name'.$i}),
                                    '</span>',
                                '</a>',
                            '</li>';
                        }
                    }
                }

            echo '</ul>';
        echo '</section>';
    }
}

function wgl_theme_helper() {
    return WglThemeHelper::instance();
}
