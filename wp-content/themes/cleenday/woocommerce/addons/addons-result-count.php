<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><p class="woocommerce-result-count"><?php

		$paged    = max( 1, $query->get( 'paged' ) );
		$per_page = $query->get( 'posts_per_page' );
		$total    = $query->found_posts;
		$first    = ( $per_page * $paged ) - $per_page + 1;
		$last     = min( $total, $query->get( 'posts_per_page' ) * $paged );

		if ( 1 == $total ) {
		    _e( 'Showing the single result', 'cleenday' );
		} elseif ( $total <= $per_page || -1 == $per_page ) {
		    printf( esc_html__( 'Showing all %d results', 'cleenday' ), $total );
		} else {
		    printf( _x( 'Showing %1$d&ndash;%2$d of %3$d results', '%1$d = first, %2$d = last, %3$d = total', 'cleenday' ), $first, $last, $total );
		}

?></p>
