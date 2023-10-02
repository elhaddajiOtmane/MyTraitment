<?php

if (!function_exists('wbc_extended_example')) {
    /**
     * Add menu and rev slider to demo content.
     * Set defaults settings.
     *
     * @package     WBC_Importer - Extension for Importing demo content
     * @author      Webcreations907
     * @version     1.0
     */
    function wbc_extended_example($demo_active_import, $demo_directory_path)
    {
        reset($demo_active_import);
        $current_key = key($demo_active_import);

        /**
         * Slider(s) Import
         */
        if (class_exists('RevSlider')) {
            $slider_array = [
                // Set sliders zip name
                'demo' => [
                    '1' => 'slider-1.zip',
                    '2' => 'slider-2.zip',
                ]
            ];
            if (
                !empty($demo_active_import[$current_key]['directory'])
                && array_key_exists($demo_active_import[$current_key]['directory'], $slider_array)
            ) {
                $slider_import = $slider_array[$demo_active_import[$current_key]['directory']];
                if (is_array($slider_import)) {
                    foreach ($slider_import as $value) {
                        if (file_exists($demo_directory_path . $value)) {
                            (new RevSlider())->importSliderFromPost(true, true, $demo_directory_path . $value);
                        }
                    }
                } elseif (file_exists($demo_directory_path . $slider_import)) {
                    (new RevSlider())->importSliderFromPost(true, true, $demo_directory_path . $slider_import);
                }
            }
        }


        /**
         * Menu(s)
         */

        // Set menu name
        $menu_array = [
            'demo' => 'main'
        ];

        if (
            !empty($demo_active_import[$current_key]['directory'])
            && array_key_exists($demo_active_import[$current_key]['directory'], $menu_array)
        ) {
            $top_menu = get_term_by('name', $menu_array[$demo_active_import[$current_key]['directory']], 'nav_menu');
            isset($top_menu->term_id) && set_theme_mod('nav_menu_locations', ['main_menu' => $top_menu->term_id]);
        }


        /**
         * Home Page(s)
         */

        // Array of `demos => homepages` to select from
        $home_pages = [
            'demo' => 'Home 1',
        ];

        if (
            !empty($demo_active_import[$current_key]['directory'])
            && array_key_exists($demo_active_import[$current_key]['directory'], $home_pages)
        ) {
            $page = get_page_by_title($home_pages[$demo_active_import[$current_key]['directory']]);
            if (isset($page->ID)) {
                update_option('page_on_front', $page->ID);
                update_option('show_on_front', 'page');
            }
        }


        /**
         * Elementor plugin
         */

        // Support all Custom Post Types
        $cpt_support = get_option('elementor_cpt_support');
        if (!$cpt_support) {
            $cpt_support = ['page', 'post', 'portfolio', 'team', 'footer', 'side_panel', 'header'];
            update_option('elementor_cpt_support', $cpt_support);
        } else {
            $include_cpt = ['portfolio', 'team', 'footer', 'side_panel', 'header'];
            foreach ($include_cpt as $cpt) {
                if (!in_array($cpt, $cpt_support)) {
                    $cpt_support[] = $cpt;
                }
            }
            update_option('elementor_cpt_support', $cpt_support);
        }
        update_option('elementor_container_width', 1170);
        // Font Awesome Styles
        update_option('elementor_load_fa4_shim', 'yes');

        /**
         * Cleenday Theme Additionals
         */

        // Permalink Structure
        /* Doesn't work with ajax */
        //update_option('permalink_structure', "/%postname%/");

        global $wgl_elementor_page_settings;
        global $wpdb;
        if(!empty($GLOBALS['wgl_elementor_page_settings'])){
            $like = '%'.$GLOBALS['wgl_elementor_page_settings'].'%';
            $result = $wpdb->get_row($wpdb->prepare("SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key='_elementor_page_settings' AND meta_value LIKE %s", $like), ARRAY_N);
            if (!empty($result)) {
                if (
                    defined('ELEMENTOR_VERSION')
                    && version_compare(ELEMENTOR_VERSION, '3.0', '>=')
                ) {
                    if(isset($result[0])){
                        update_option( 'elementor_active_kit', $result[0] );
                        \Elementor\Plugin::$instance->files_manager->clear_cache();
                    }
                }
            }
            unset($GLOBALS['wgl_elementor_page_settings']);
        }
    }

    add_action('wbc_importer_after_content_import', 'wbc_extended_example', 10, 2);
}
