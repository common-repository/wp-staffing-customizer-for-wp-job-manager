<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wp-staffing.com/
 * @since      1.0.0
 *
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/admin
 * @author     WP Staffing <info@wp-staffing.com>
 */
class Wp_Staffing_Customizer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param string $plugin_name      The name of the plugin.
	 * @param string $version          The version of the plugin.
	 * @param array  $wps_taxonomies    New taxonomies.
	 * @param array  $wps_meta          New meta values.
	 * @param array  $global_elements  Global job listing settings.
	 */
	public function __construct( $plugin_name, $version, $wps_taxonomies, $wps_meta, $global_elements ) {

		$this->plugin_name     = $plugin_name;
		$this->version         = $version;
		$this->wps_taxonomies  = $wps_taxonomies;
		$this->wps_meta        = $wps_meta;
		$this->global_elements = $global_elements;

		add_action( 'admin_notices', array( $this, 'add_admin_notices' ) );
		add_filter( 'wps_pro_meta', array( $this, 'wps_pro_meta' ) );
		add_filter( 'job_manager_settings', array( $this, 'wps_global_values' ) );
		add_filter( 'job_manager_job_listing_data_fields', array( $this, 'wps_add_meta' ) );
		add_action( 'init', array( $this, 'create_wps_taxonomies' ) );

	}


	/**
	 * Add admin notice to the admin.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_notices() {

		if ( ! is_plugin_active( 'wp-job-manager/wp-job-manager.php' ) ) {
			echo '<div class="notice notice-error">';
			echo '<p>';
			echo _e( '<em>WP Staffing Customizer for WP Job Manager</em> requires WP Job Manager to be installed and activated.', 'wp-job-manager' );
			echo '</p>';
			echo '</div>';
		}

	}


	/**
	 * Add meta fields to the admin.
	 *
	 * @since    1.0.0
	 * @param  array $output Returns meta data.
	 */
	public function wps_add_meta( $output ) {

		$output = array_merge( $output, $this->wps_meta );

		return $output;
	}

	/**
	 * Add meta fields to pro fields.
	 *
	 * @since    1.0.0
	 * @param  array $output Returns meta data.
	 */
	public function wps_pro_meta( $output ) {

		$output = array_merge( $output, $this->wps_meta );

		return $output;
	}

	/**
	 * Admin to use global values for a company.
	 *
	 * @since    1.0.0
	 * @param  array $output Returns settings.
	 */
	public function wps_global_values( $output ) {

		$new_meta_array  = array();
		$global_elements = $this->global_elements;
		$count           = 1;
		$desc            = 'Enter a value to use everywhere.';

		// Loop through WP Job Manager Elements.
		foreach ( $this->global_elements as $key => $the_meta ) {

			if ( count( $global_elements ) === $count ) {

				$desc .= '<br>';
				$desc .= '<div class="card">';
				$desc .= '<h4 class="title">Thanks for using WP Staffing Customizer</h4>';

				$desc .= '<p>Go to <a href="http://wp-staffing.com/" >wp-staffing.com</a> for a Pro version of the <em>WP Staffing Customizer</em> plugin. <strong>WP Staffing</strong> builds WordPress tools and does custom web development for the staffing industry.</p>';
				$desc .= '<p><strong><a href="http://wp-staffing.com/" style="text-decoration: none;">Visit wp-staffing.com <span class="dashicons dashicons-external"></span></a></strong></p>';
				$desc .= '</div>';

			}

			$new_meta = array(
				'name'       => 'wps_global_' . $key,
				'std'        => '',
				'label'      => __( $the_meta, 'wp-job-manager' ),
				'desc'       => $desc,
				'attributes' => array(),
			);

			array_push( $new_meta_array, $new_meta );

			$count++;
		}

		$new_tab = array(
			'wp_staffing_customizer' => array(
				__( 'WP Staffing Customizer', 'wp-job-manager' ),
				$new_meta_array,
			),
		);

		$output = array_merge( $output, $new_tab );

		return $output;
	}


	/**
	 * Creates a new taxonomy for a Job post type
	 *
	 * @since  1.0.0
	 * @access public
	 * @uses   register_taxonomy()
	 */
	public function create_wps_taxonomies() {

		foreach ( $this->wps_taxonomies as $key => $the_tax ) {

			$plural   = $the_tax['plural'];
			$single   = $the_tax['name'];
			$tax_name = $key;

			$opts['hierarchical']                         = true;
			$opts['public']                               = true;
			$opts['query_var']                            = $tax_name;
			$opts['show_admin_column']                    = false;
			$opts['show_in_nav_menus']                    = true;
			$opts['show_tag_cloud']                       = true;
			$opts['show_ui']                              = true;
			$opts['sort']                                 = '';
			$opts['capabilities']['assign_terms']         = 'edit_posts';
			$opts['capabilities']['delete_terms']         = 'manage_categories';
			$opts['capabilities']['edit_terms']           = 'manage_categories';
			$opts['capabilities']['manage_terms']         = 'manage_categories';
			$opts['labels']['add_new_item']               = esc_html__( "Add New {$single}", 'wp-job-manager' );
			$opts['labels']['add_or_remove_items']        = esc_html__( "Add or remove {$plural}", 'wp-job-manager' );
			$opts['labels']['all_items']                  = esc_html__( $plural, 'wp-job-manager' );
			$opts['labels']['choose_from_most_used']      = esc_html__( "Choose from most used {$plural}", 'wp-job-manager' );
			$opts['labels']['edit_item']                  = esc_html__( "Edit {$single}" , 'wp-job-manager' );
			$opts['labels']['menu_name']                  = esc_html__( $plural, 'wp-job-manager' );
			$opts['labels']['name']                       = esc_html__( $plural, 'wp-job-manager' );
			$opts['labels']['new_item_name']              = esc_html__( "New {$single} Name", 'wp-job-manager' );
			$opts['labels']['not_found']                  = esc_html__( "No {$plural} Found", 'wp-job-manager' );
			$opts['labels']['parent_item']                = esc_html__( "Parent {$single}", 'wp-job-manager' );
			$opts['labels']['parent_item_colon']          = esc_html__( "Parent {$single}:", 'wp-job-manager' );
			$opts['labels']['popular_items']              = esc_html__( "Popular {$plural}", 'wp-job-manager' );
			$opts['labels']['search_items']               = esc_html__( "Search {$plural}", 'wp-job-manager' );
			$opts['labels']['separate_items_with_commas'] = esc_html__( "Separate {$plural} with commas", 'wp-job-manager' );
			$opts['labels']['singular_name']              = esc_html__( $single, 'wp-job-manager' );
			$opts['labels']['update_item']                = esc_html__( "Update {$single}", 'wp-job-manager' );
			$opts['labels']['view_item']                  = esc_html__( "View {$single}", 'wp-job-manager' );
			$opts['rewrite']['ep_mask']                   = EP_NONE;
			$opts['rewrite']['hierarchical']              = false;
			$opts['rewrite']['slug']                      = esc_html__( strtolower( $tax_name ), 'wp-job-manager' );
			$opts['rewrite']['with_front']                = false;

			$opts = apply_filters( 'wps_taxonomy_options', $opts );

			register_taxonomy( $tax_name, 'job_listing', $opts );

		}

	}


}
