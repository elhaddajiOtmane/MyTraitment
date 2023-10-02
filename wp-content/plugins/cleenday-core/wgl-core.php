<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeforest.net/user/webgeniuslab
 * @since             1.0.0
 * @package           cleenday-core
 *
 * @wordpress-plugin
 * Plugin Name:       Cleenday Core
 * Plugin URI:        https://themeforest.net/user/webgeniuslab
 * Description:       Core plugin for Cleenday Theme.
 * Version:           1.0.8
 * Author:            WebGeniusLab
 * Author URI:        https://themeforest.net/user/webgeniuslab
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cleenday-core
 * Domain Path:       /languages
 */

defined('WPINC') || die;  // Abort, if called directly.


/**
 * Current version of the plugin.
 */
define('WGL_CORE_VERSION', '1.0.4');

class Cleenday_CorePlugin
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'check_version']);
        if (!self::compatible_version()) {
            return;
        }
    }

    public static function activation_check()
    {
        if (!self::compatible_version()) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(__('"Cleenday-core" Plugin compatible with "Cleenday" theme only!', 'cleenday-core'));
        }
    }

    /**
     * The backup sanity check, in case the plugin is activated in a weird way,
     * or the theme change after activation.
     */
    public function check_version()
    {
        if (
            !self::compatible_version()
            && is_plugin_active(plugin_basename(__FILE__))
        ) {
            deactivate_plugins(plugin_basename(__FILE__));
            add_action('admin_notices', [$this, 'disabled_notice']);
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
    }

    public function disabled_notice()
    {
        echo '<strong>', esc_html__('"Cleenday-core" Plugin compatible with "Cleenday" theme only!', 'cleenday-core'), '</strong>';
    }

    public static function compatible_version()
    {
        return false !== stripos(trim(dirname(plugin_basename(__FILE__))), self::get_theme_slug());
    }

    public static function get_theme_slug()
    {
        return str_replace('-child', '', wp_get_theme()->get('TextDomain'));
    }
}

new Cleenday_CorePlugin();

register_activation_hook(__FILE__, ['Cleenday_CorePlugin', 'activation_check']);


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wgl-core-activator.php
 */
function activate_cleenday_core()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wgl-core-activator.php';
    Cleenday_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wgl-core-deactivator.php
 */
function deactivate_cleenday_core()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wgl-core-deactivator.php';
    Cleenday_Core_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_cleenday_core');
register_deactivation_hook(__FILE__, 'deactivate_cleenday_core');


/**
 * The code that runs during plugin activation.
 * admin-specific hooks add theme option preset hooks.
 */
function add_defaults_preset()
{
    $name = wp_get_theme()->get('TextDomain');
    $name = str_replace('-child', '', $name);
    if (function_exists($name . '_default_preset')) {
        $presets =  call_user_func($name . '_default_preset');
        $options_presets = array();
        if (is_array($presets)) {
            foreach ($presets as $key => $value) {
                $options_presets[$key] = json_decode($presets[$key], true);
            }
        }

        $default_option = get_option($name . '_set_preset');
        $default_option['default'] = $options_presets;

        update_option($name . '_set_preset', $default_option);
    }
}

register_activation_hook(__FILE__, 'add_defaults_preset');

add_action('after_setup_theme', 'wgl_role_preset');

function wgl_role_preset()
{
    $name = wp_get_theme()->get('TextDomain');
    $name = str_replace('-child', '', $name);
    $default_option = get_option($name . '_set_preset');
    if (!$default_option) {
        add_defaults_preset();
    }
}
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wgl-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_cleenday_core()
{
    (new Cleenday_Core())->run();
}

run_cleenday_core();
