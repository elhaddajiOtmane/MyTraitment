<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
	'menu_order' => esc_html__( 'Default sorting', 'cleenday' ),
	'popularity' => esc_html__( 'Sort by popularity', 'cleenday' ),
	'rating'     => esc_html__( 'Sort by average rating', 'cleenday' ),
	'date'       => esc_html__( 'Sort by latest', 'cleenday' ),
	'price'      => esc_html__( 'Sort by price: low to high', 'cleenday' ),
	'price-desc' => esc_html__( 'Sort by price: high to low', 'cleenday' ),
) );

$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.

if ( wc_get_loop_prop( 'is_search' ) ) {
	$catalog_orderby_options = array_merge( array( 'relevance' => esc_html__( 'Relevance', 'cleenday' ) ), $catalog_orderby_options );

	unset( $catalog_orderby_options['menu_order'] );
}

if ( ! $show_default_orderby ) {
	unset( $catalog_orderby_options['menu_order'] );
}

if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
	unset( $catalog_orderby_options['rating'] );
}

if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
	$orderby = current( array_keys( $catalog_orderby_options ) );
}
?>
<form class="woocommerce-ordering" method="get">
	<select name="orderby" class="orderby">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="hidden" name="paged" value="1" />
	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>
