<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wp-staffing.com/
 * @since             1.0.0
 * @package           Wp_Staffing_Customizer
 *
 * @wordpress-plugin
 * Plugin Name:       WP Staffing Customizer for WP Job Manager
 * Plugin URI:        http://wp-staffing.com/wp-staffing-customizer/
 * Description:       Create a job board fit for a staffing agency on your WordPress site. Add Profession and Specialty taxonomies to WP Job Manager along with many other customizations.
 * Version:           1.0.0
 * Author:            WP Staffing
 * Author URI:        http://wp-staffing.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-staffing-customizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_STAFFING_CUSTOMIZER_VERSION', '1.0.0' );
define( 'WPS_STAFFING_CUSTOMIZER_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-staffing-customizer-activator.php
 */
function activate_wp_staffing_customizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-staffing-customizer-activator.php';
	Wp_Staffing_Customizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-staffing-customizer-deactivator.php
 */
function deactivate_wp_staffing_customizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-staffing-customizer-deactivator.php';
	Wp_Staffing_Customizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_staffing_customizer' );
register_deactivation_hook( __FILE__, 'deactivate_wp_staffing_customizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-staffing-customizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_staffing_customizer() {

	$plugin = new Wp_Staffing_Customizer();
	$plugin->run();

}
run_wp_staffing_customizer();
