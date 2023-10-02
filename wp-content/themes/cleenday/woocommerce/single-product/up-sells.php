<?php
/**
 * Single Product Up-Sells
 *
 * This template is overriden by WebGeniusLab team for fine customizing.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

defined('ABSPATH') || exit;

$columns = (int) Cleenday_Theme_Helper::get_option('shop_related_columns');

if ( $upsells ) : ?>

	<section class="up-sells upsells wgl-products-wrapper products columns-<?php echo esc_attr($columns);?>">

		<h2><?php esc_html_e( 'You may also like&hellip;', 'cleenday' ); ?></h2>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $upsells as $upsell ) : ?>

				<?php
					$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

<?php endif;

wp_reset_postdata();
