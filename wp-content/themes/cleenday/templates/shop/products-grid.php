<?php
global $wgl_products_atts;

extract($wgl_products_atts);

$widget_image_size = [
    'img_size_string' => $wgl_products_atts['atts']['img_size_string'],
    'img_size_array' => $wgl_products_atts['atts']['img_size_array'] ?? [],
    'img_aspect_ratio' => $wgl_products_atts['atts']['img_aspect_ratio'],
];

global $wgl_query_vars;

if(!empty($wgl_query_vars)){
    $query = $wgl_query_vars;
}

while ($query->have_posts()) : $query->the_post();
    global $product;

        ob_start();
            wc_product_class('item', $product);
        $product_class = ob_get_clean();

        echo '<li '.$product_class.'>';
        $single = new Cleenday_Woocoommerce();

        $single->woocommerce_template_loop_product_thumbnail($widget_image_size);

        /**
         * Hook: woocommerce_shop_loop_item_title.
         *
         * @hooked woocommerce_template_loop_product_title - 10
         */
        echo '<div class="woo_product_content">';
            $single->template_loop_product_title();
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );
        echo '</div>';

        echo '</li>';

endwhile;
wp_reset_postdata();
