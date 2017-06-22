<?php
/**
 * Plugin Name: Frontuser Integration
 * Description: Frontuser is a easiest platform to maximize user engagement, retention & conversion. It allows your WordPress site to send desktop and mobile personalized push notifications and customizable popups.
 * Version: 1.0.0
 * Author: Frontuser
 * Author URI: https://frontuser.com
 * License: Open Software License (OSL 3.0)
 * License URI: http://opensource.org/licenses/osl-3.0.php
 */

/**
 * Prevent Direct Access
 *
 * @since 1.0
 */
defined('ABSPATH') or die("Restricted access!");


/**
 * Define global constants
 *
 * @since 1.0
 */

// Ex: frontuser
defined('FRONTUSER_DIR') or define('FRONTUSER_DIR', dirname(plugin_basename(__FILE__)));

// Ex: frontuser/plugin.php
defined('FRONTUSER_BASE') or define('FRONTUSER_BASE', plugin_basename(__FILE__));

// Ex: http://wordpress.local/wp-content/plugins/frontuser/
defined('FRONTUSER_URL') or define('FRONTUSER_URL', plugin_dir_url(__FILE__));

// Ex: /var/www/html/wordpress.local/wp-content/plugins/frontuser/
defined('FRONTUSER_PATH') or define('FRONTUSER_PATH', plugin_dir_path(__FILE__));

// Ex: 1.0
defined('FRONTUSER_VERSION') or define('FRONTUSER_VERSION', '1.0');


// Include the dependencies needed to instantiate the plugin.
foreach ( glob( FRONTUSER_PATH . 'admin/*.php' ) as $file ) {
	include_once $file;
}

// Include the dependencies needed to instantiate the plugin.
foreach ( glob( FRONTUSER_PATH . 'frontend/*.php' ) as $file ) {
	include_once $file;
}


/**
 * Starts the plugin.
 *
 * @since 1.0
 */
function frontuser_admin_settings() {

	$plugin = new FrontuserSetting( new FrontuserSetting_Page() );
	$plugin->init();

	$plugin = new FrontuserMatrixData( new FrontuserMatrixData_Page() );
	$plugin->init();

	add_action('wp_head', 'frontuser_website_smartcode');
	add_action('wp_footer', 'frontuser_website_matrixcode');
	register_uninstall_hook(__FILE__,'frontuser_uninstall');
}

add_action( 'plugins_loaded', 'frontuser_admin_settings' );


/**
 * Check WooCommerce plugin is enable or not.
 *
 * @since 1.0
 */
function frontuser_is_woocommerce_enabled() {
    global $woocommerce;

    if ( ! isset( $woocommerce ) || ! function_exists('WC') ) {
        return false;
    }
    return true;
}


/**
 * Inject scripts in the frontend head
 * Outputs the given setting, if conditions are met
 *
 * @return output
 * @since 1.0
 */
function frontuser_website_smartcode() {
    FrontuserSmartCode::render();
}


/**
 * Inject scripts in the frontend footer
 *
 * @return output
 * @since 1.0
 */
function frontuser_website_matrixcode() {
    FrontuserSmartCode::matrix();
}


/**
 * Delete options on uninstall
 *
 * @since 0.1
 */
function frontuser_uninstall() {
	Setting::delete();
}