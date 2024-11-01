<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wp-staffing.com/
 * @since      1.0.0
 *
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/includes
 * @author     WP Staffing <info@wp-staffing.com>
 */
class Wp_Staffing_Customizer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Staffing_Customizer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * New taxonomy array.
	 *
	 * @var [type]
	 */
	protected $wps_taxonomies;

	/**
	 * New meta fields.
	 *
	 * @var [type]
	 */
	protected $wps_meta;

	/**
	 * New global company fields.
	 *
	 * @var [type]
	 */
	protected $global_elements;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_STAFFING_CUSTOMIZER_VERSION' ) ) {
			$this->version = WP_STAFFING_CUSTOMIZER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-staffing-customizer';

		$this->wps_taxonomies = array(
			'profession' => array(
				'name'   => __( 'Profession', 'wp-job-manager' ),
				'plural' => __( 'Professions', 'wp-job-manager' ),
			),
			'specialty'  => array(
				'name'   => __( 'Specialty', 'wp-job-manager' ),
				'plural' => __( 'Specialties', 'wp-job-manager' ),
			),
		);

		$this->wps_meta = array(
			'_recruiter' => array(
				'label'       => __( 'Recruiter', 'wp-job-manager' ),
				'placeholder' => __( 'Recruiter', 'wp-job-manager' ),
				'priority'    => 20,
			),
		);

		$this->global_elements = array(
			'global_name'    => 'Global Company Name',
			'global_logo'    => 'Global Company Logo URL',
			'global_website' => 'Global Company Website URL',
		);

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Staffing_Customizer_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Staffing_Customizer_i18n. Defines internationalization functionality.
	 * - Wp_Staffing_Customizer_Admin. Defines all hooks for the admin area.
	 * - Wp_Staffing_Customizer_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-staffing-customizer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-staffing-customizer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-staffing-customizer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-staffing-customizer-public.php';

		$this->loader = new Wp_Staffing_Customizer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Staffing_Customizer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Staffing_Customizer_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Staffing_Customizer_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_wps_taxonomies(), $this->get_wps_meta(), $this->global_elements() );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Staffing_Customizer_Public( $this->get_plugin_name(), $this->get_version(), $this->get_wps_taxonomies(), $this->get_wps_meta() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Staffing_Customizer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Get new staffing taxonomies.
	 *
	 * @since     1.0.0
	 * @return    Wp_Staffing_Customizer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_wps_taxonomies() {

		return apply_filters( 'wps_taxonomies', $this->wps_taxonomies );
	}

	/**
	 * Get new staffing meta fields.
	 *
	 * @since     1.0.0
	 * @return    Wp_Staffing_Customizer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_wps_meta() {
		return apply_filters( 'wps_meta', $this->wps_meta );
	}

	/**
	 * Get WP Job Manager elements to hide.
	 *
	 * @since     1.0.0
	 * @return    Wp_Staffing_Customizer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function global_elements() {
		return apply_filters( 'wps_global_elements', $this->global_elements );
	}

}
