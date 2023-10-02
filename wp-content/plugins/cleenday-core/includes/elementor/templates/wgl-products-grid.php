<?php
/**
 * This template can be overridden by copying it to `yourtheme/cleenday-core/elementor/templates/wgl-products-grid.php`.
 */
namespace WglAddons\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WglAddons\Includes\{
    Wgl_Loop_Settings,
    Wgl_Carousel_Settings,
    Wgl_Elementor_Helper
};
use Cleenday_Theme_Helper as Cleenday;

/**
 * WGL Elementor Products Grid Template
 *
 *
 * @package cleenday-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WglProductsGrid
{
    private $attributes;
    private $query;

    public function render($attributes = [], $self = false)
    {
        $this->attributes = $attributes;
        $this->item       = !empty($self) ? $self : $this;
        $this->query      = $this->_formalize_query();

        $_ = $attributes; // assign shorthand for attributes array

        $wgl_def_atts = array(
            'query' => $this->query,
            // General
            'products_layout' => '',
            'products_title' => '',
            'products_subtitle' => '',
            // Content
            'products_columns' => '',
            'items_load'  => '4',
            'products_style' => 'grid',
        );

        global $wgl_products_atts;
        $wgl_products_atts = array_merge($wgl_def_atts ,array_intersect_key($this->attributes, $wgl_def_atts));
        $wgl_products_atts['post_count'] = $this->query->post_count;
        $wgl_products_atts['query_args'] = $this->query->query_vars;
        $wgl_products_atts['atts'] = $this->attributes;

        ob_start();
            get_template_part('templates/shop/products', 'grid');
        $products_items = ob_get_clean();

        echo '<section class="wgl_cpt_section wgl-products-grid woocommerce">';

        if ($_['isotope_filter'] && $_['products_layout'] != 'carousel') {
            echo \Cleenday_Theme_Helper::render_html($this->_render_filter());
        }
        // Load the template orderby

        if((bool) $_['show_header_products']){
            echo '<div class="wgl-woocommerce-sorting">';

                if((bool) $_['show_res_count']){
                    // Load the template result count.
                    wc_get_template( 'addons/addons-result-count.php', array(
                        'query' => $this->query,
                    ) );
                }

                if((bool) $_['show_sorting']){
                    // Load the template orderby
                    wc_get_template( 'addons/addons-orderby.php', array(
                        'query' => $this->query,
                    ) );
                }

            echo '</div>';
        }

        echo '<div class="wgl-products-catalog wgl-products-wrapper', $this->_get_wrapper_classes(), '">';

        echo '<ul class="wgl-products container-grid', $this->_get_isotope_classes(), '">';
            if ('carousel' === $_['products_layout']) {
                ob_start();
            }

            echo \Cleenday_Theme_Helper::render_html($products_items);

            if ('carousel' === $_['products_layout']) {
                $product_items = ob_get_clean();
                $product_items = $this->_apply_carousel_settings($product_items);
                echo \Cleenday_Theme_Helper::render_html($product_items);
            }
        echo '</ul>';

        echo '</div>'; //* wgl-prodcuts-grid

        $this->_render_navigation_section();

        echo '</section>';

        // Clear global var
        unset($wgl_products_atts);
    }

    protected function _formalize_query()
    {
        list($query_args) = Wgl_Loop_Settings::buildQuery($this->attributes);

        $query_args['post_type'] = 'product';

        //* Add Page to Query
        global $paged;
        if (empty($paged)) {
            $paged = get_query_var('page') ?: 1;
        }
        $query_args['paged'] = $paged;

        $tax = array();
        $product_catalog_terms  = wc_get_product_visibility_term_ids();
        $product_not_in = array($product_catalog_terms['exclude-from-catalog']);
        if ( ! empty( $product_not_in ) ) {
            $tax[] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $product_not_in,
                'operator' => 'NOT IN',
            );
        }

        if(isset($_GET['orderby']) && !empty($_GET['orderby'])){
            $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

            // Get order + orderby args from string
            $orderby_value = explode('-', $orderby_value);
            $orderby = esc_attr($orderby_value[0]);
            $order = ! empty( $orderby_value[1] ) ? $orderby_value[1] : '';

            $orderby = strtolower( $orderby );
            $order   = strtoupper( $order );

            $ordering_args = WC()->query->get_catalog_ordering_args( $orderby, $order );
            $meta_query    = WC()->query->get_meta_query();

            $query_args['orderby'] = $ordering_args['orderby'];
            $query_args['order'] = $ordering_args['order'];

            if ( $ordering_args['meta_key'] ) {
                $query_args['meta_key']       = $ordering_args['meta_key'];
            }

            if($_GET['orderby'] === 'price'){
                $query_args['order'] = 'ASC';
            }
        }

        $query_args['tax_query'][] = $tax;

        return Wgl_Loop_Settings::cache_query($query_args);
    }

    protected function _get_wrapper_classes()
    {
        $_ = $this->attributes;

        $class = !empty($_['grid_columns']) ? ' columns-' . $_['grid_columns'] : '';
        $class .= !empty($_['grid_columns_tablet']) ? ' columns-tablet-' . $_['grid_columns_tablet'] : '';
        $class .= !empty($_['grid_columns_mobile']) ? ' columns-mobile-' . $_['grid_columns_mobile'] : '';
        $class .= $_['products_layout'] === 'carousel' ? ' carousel' : '';

        return esc_attr($class);
    }

    protected function _get_isotope_classes()
    {
        $_ = $this->attributes;
        $class = '';
        if ('masonry' === $_['products_layout'] || $_['isotope_filter'] || $_['products_navigation'] == 'load_more') {
            wp_enqueue_script('imagesloaded');
            wp_enqueue_script('isotope', WGL_ELEMENTOR_ADDONS_URL . 'assets/js/isotope.pkgd.min.js', ['imagesloaded']);
            $class = ' isotope';
        }

        return esc_attr($class);
    }

    protected function _render_filter()
    {
        list($query_args) = Wgl_Loop_Settings::buildQuery($this->attributes);
        $data_category = $query_args['tax_query'] ?? [];
        $include = $exclude = [];

        if ( isset($data_category[0]) ) {
            foreach ($data_category[0]['terms'] as $value) {
                $idObj = get_term_by( 'slug', $value, 'product_cat' );
                $id_list[] = $idObj ? $idObj->term_id : '';
            }
            switch ($data_category[0]['operator']) {
                case 'NOT IN':
                    $exclude = implode(',', $id_list);
                    break;
                case 'IN':
                    $include = implode(',', $id_list);
                    break;
            }
        }
        $cats = get_terms( [
            'taxonomy' => 'product_cat',
            'include' => $include,
            'exclude' => $exclude,
            'hide_empty' => true
        ] );
        $filter = '<div class="product__filter isotope-filter acenter">';
        $filter .= '<a href="#" data-filter=".product" class="active">' . esc_html__('All', 'cleenday-core') . '<span class="number_filter"></span></a>';
        foreach ( $cats as $cat ) {
            if ( $cat->count > 0 ) {
                $filter .= '<a href="'.get_term_link($cat->term_id, 'product_cat').'" data-filter=".product_cat-'.$cat->slug.'">';
                $filter .= $cat->name;
                $filter .= '<span class="number_filter"></span>';
                $filter .= '</a>';
            }
        }
        $filter .= '</div>';

        return $filter;
    }

    protected function _apply_carousel_settings($product_items)
    {
        extract($this->attributes);

        $options = [
            'items_per_line' => $grid_columns,
            'resp_tablets_slides' => $grid_columns_tablet,
            'resp_mobile_slides' => $grid_columns_mobile,
            'autoplay' => $autoplay,
            'autoplay_speed' => $autoplay_speed,
            'infinite' => $infinite_loop,
            'slides_to_scroll' => $slide_single,
            'use_pagination' => $use_pagination,
            'use_navigation' => $use_navigation,
            'use_prev_next' => $use_navigation ? true : false,
            'pag_type' => $pag_type,
            'custom_pag_color' => $custom_pag_color,
            'pag_color' => $pag_color,
            'custom_resp' => true,
            'adaptive_height' => true
        ];

        return Wgl_Carousel_Settings::init($options, $product_items);
    }

    protected function _render_navigation_section()
    {
        if ('pagination' === $this->attributes['products_navigation']) {
            echo Cleenday::pagination($this->query, 'center');
        }

        if ('load_more' === $this->attributes['products_navigation']) {
            global $wgl_products_atts;
            Cleenday::load_more($wgl_products_atts, $this->attributes['name_load_more']);
        }
    }
}