<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wp-staffing.com/
 * @since      1.0.0
 *
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/includes
 * @author     WP Staffing <info@wp-staffing.com>
 */
class Wp_Staffing_Customizer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-staffing-customizer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
