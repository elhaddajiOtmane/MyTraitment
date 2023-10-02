<?php

class WglPostTypesRegister
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            return new self();
        }

        return self::$instance;
    }

    public function init()
    {
        if (!class_exists('Cleenday_Theme_Helper')) {
            return;
        }

        // Create post type
        new Team();
        new Footer();
        new Header();
        new SidePanel();
        new Portfolio();
    }
}

require_once plugin_dir_path(dirname(__FILE__)) . 'post-types/team/class-pt-team.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'post-types/footer/class-pt-footer.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'post-types/side_panel/class-pt-side-panel.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'post-types/header/class-pt-header.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'post-types/portfolio/class-pt-portfolio.php';
