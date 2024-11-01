<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wp-staffing.com/
 * @since      1.0.0
 *
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/public
 * @author     WP Staffing <info@wp-staffing.com>
 */
class Wp_Staffing_Customizer_Public {

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
	 */
	public function __construct( $plugin_name, $version, $wps_taxonomies, $wps_meta ) {

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->wps_taxonomies = $wps_taxonomies;
		$this->wps_meta       = $wps_meta;

		add_action( 'single_job_listing_start', array( $this, 'wps_job_summary' ), 40 );

		// Add global settings.
		add_filter( 'the_company_name', array( $this, 'global_company_name' ) );
		add_filter( 'the_company_logo', array( $this, 'global_company_logo' ) );
		add_filter( 'job_manager_default_company_logo', array( $this, 'global_company_logo' ) );
		add_filter( 'the_company_website', array( $this, 'global_company_website' ) );

		// Add taxonomy markup.
		add_action( 'job_listing_meta_end', array( $this, 'add_taxonomies_to_meta' ) );
		add_action( 'single_job_listing_meta_end', array( $this, 'add_taxonomies_to_meta' ) );

		// Add Taxonomy Filters.
		add_action( 'job_manager_job_filters_search_jobs_end', array( $this, 'add_taxonomies_to_filters' ) );
		add_filter( 'job_manager_get_listings', array( $this, 'job_manager_get_listings' ), 10, 2 );

		// Add Taxonomies to the [submit_a_job] form.
		add_filter( 'submit_job_form_fields', array( $this, 'submit_job_form_fields' ) );

	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Staffing_Customizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Staffing_Customizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-staffing-customizer-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Staffing_Customizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Staffing_Customizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-staffing-customizer-public.js', array( 'jquery' ), $this->version, false );

	}



	/**
	 * Show the job summary.
	 *
	 * @since    1.0.0
	 */
	public function wps_job_summary() {

		global $post;

		$fields         = array();
		$wps_taxonomies = $this->wps_taxonomies;
		$wps_meta       = $this->wps_meta;

		// Get the taxonomies.
		foreach ( $wps_taxonomies as $key => $taxonomy ) {

			$thetaxonomies = get_the_terms( $post->ID, $key );

			if ( ! empty( $thetaxonomies ) ) {

				$tax_desc  = '';
				$tax_array = array();

				foreach ( $thetaxonomies as $key => $value ) {

					$tax_desc .= $value->name;
					$tax_desc .= ( count( $thetaxonomies ) === $key + 1 ) ? '' : ', ';

				}

				$tax_array = array(
					'tax'  => $taxonomy['name'],
					'desc' => $tax_desc,
				);

				array_push( $fields, $tax_array );

			}
		}

		// Get the meta values.
		foreach ( $wps_meta as $key => $meta ) {

			$meta_array = array();

			if ( empty( $post->$key ) ) {
				continue;
			}

			$meta_array = array(
				'tax'  => $meta['label'],
				'desc' => $post->$key,
			);

			array_push( $fields, $meta_array );

		}

		if ( empty( $fields ) ) {
			return;
		}

		get_job_manager_template( 'job-summary.php', array( 'fields' => $fields ), $this->plugin_name, WPS_STAFFING_CUSTOMIZER_PLUGIN_DIR . '/public/partials/' );

	}


	/**
	 * Add global company name.
	 *
	 * @since    1.0.0
	 * @param  string $name The name.
	 */
	public function global_company_name( $name ) {

		if ( ! empty( get_option( 'wps_global_global_name' ) ) ) {
			$name = get_option( 'wps_global_global_name' );
		}

		return $name;
	}

	/**
	 * Add global company logo.
	 *
	 * @since    1.0.0
	 * @param  string $logo The company logo.
	 */
	public function global_company_logo( $logo ) {

		if ( ! empty( get_option( 'wps_global_global_logo' ) ) ) {
			$logo = get_option( 'wps_global_global_logo' );
		}

		return $logo;
	}

	/**
	 * Add global company website.
	 *
	 * @since    1.0.0
	 * @param  string $website The company website url.
	 */
	public function global_company_website( $website ) {

		if ( ! empty( get_option( 'wps_global_global_website' ) ) ) {
			$website = get_option( 'wps_global_global_website' );
		}

		return $website;
	}

	/**
	 * Add global company website.
	 *
	 * @since    1.0.0
	 */
	public function add_taxonomies_to_meta() {

		global $post;
		$wps_taxonomies = $this->wps_taxonomies;

		echo '<li class="sn-staffing-customizer__taxonomies">';

		// Get the taxonomies.
		foreach ( $wps_taxonomies as $key => $taxonomy ) {

			$thetaxonomies = get_the_terms( $post->ID, $key );

			if ( ! empty( $thetaxonomies ) ) {
				foreach ( $thetaxonomies as $key => $value ) {
					echo '<span>' . esc_html( $value->name ) . '</span> ';
				}
			}
		}

		echo '</li>';

	}


	/**
	 * Add taxonomies to filters.
	 *
	 * @since    1.0.0
	 */
	public function add_taxonomies_to_filters() {

		$wps_taxonomies = $this->wps_taxonomies;

		foreach ( $wps_taxonomies as $key => $taxonomy ) {

			echo '<div class="search_' . esc_attr( $key ) . '">';
				echo '<label for="search_' . esc_attr( $key ) . '">';
					echo esc_html_e( $taxonomy['name'], 'wp-job-manager' );
				echo '</label>';

				job_manager_dropdown_categories(
					array(
						'taxonomy'        => $key,
						'hierarchical'    => 1,
						'show_option_all' => __( 'Any ' . $taxonomy['name'], 'wp-job-manager' ),
						'name'            => 'search_' . $key,
						'orderby'         => 'name',
						'multiple'        => false,
						'hide_empty'      => true,
					)
				);

			echo '</div>';

		}

	}


	/**
	 * Filter jobs by taxonomy input.
	 *
	 * @since    1.0.0
	 * @param  array $query_args Query args.
	 * @param  array $args       Query args.
	 */
	public function job_manager_get_listings( $query_args, $args ) {

		$wps_taxonomies = $this->wps_taxonomies;

		if ( isset( $_REQUEST['form_data'] ) ) { // Input var okay.

			$params = array();

			parse_str( wp_unslash( $_REQUEST['form_data'] ), $params ); // Input var okay.

			foreach ( $wps_taxonomies as $key => $taxonomy ) {

				if ( ! empty( $params[ 'search_' . $key ][0] ) ) {

					$query_args['tax_query'][] = array(
						'taxonomy'         => $key,
						'field'            => 'term_id',
						'terms'            => $params[ 'search_' . $key ],
						'include_children' => 1,
						'operator'         => 'IN',
					);

					add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );

				}
			}

			add_filter( 'job_manager_get_listings_custom_filter_text', array( $this, 'job_manager_get_listings_custom_filter_text' ) );
			add_filter( 'job_manager_get_listings_custom_filter_rss_args', array( $this, 'job_manager_get_listings_custom_filter_rss_args' ) );
		}
		return $query_args;
	}


	/**
	 * Append 'showing' text.
	 *
	 * @param  string $text Search messaging.
	 * @return string
	 */
	public function job_manager_get_listings_custom_filter_text( $text ) {

		$wps_taxonomies = $this->wps_taxonomies;
		$params         = array();

		parse_str( wp_unslash( $_REQUEST['form_data'] ), $params ); // Input var okay.

		foreach ( $wps_taxonomies as $key => $taxonomy ) {

			if ( ! empty( $params[ 'search_' . $key ][0] ) ) {
				$term_name = get_term( $params[ 'search_' . $key ][0] );

				$text .= ' (' . $taxonomy['name'] . ': ' . $term_name->name . ') ';
			}
		}
		return $text;
	}

	/**
	 * Filter RSS.
	 *
	 * @param  array $args RSS link args.
	 * @return array
	 */
	public function job_manager_get_listings_custom_filter_rss_args( $args ) {
		$wps_taxonomies = $this->wps_taxonomies;
		$params         = array();

		parse_str( wp_unslash( $_REQUEST['form_data'] ), $params ); // Input var okay.

		foreach ( $wps_taxonomies as $key => $taxonomy ) {
			$args[ 'search_' . $key ] = implode( ',', array_filter( $params[ 'search_' . $key ] ) );
		}

		return $args;
	}


	/**
	 * Add form fields.
	 *
	 * @param  array $fields RSS link args.
	 * @return array
	 */
	public function submit_job_form_fields( $fields ) {

		$wps_taxonomies   = $this->wps_taxonomies;
		$new_fields_array = array();

		foreach ( $wps_taxonomies as $key => $taxonomy ) {

			$fields['job'][ $key ] = array(
				'label'       => __( $taxonomy['name'], 'wp-job-manager' ),
				'type'        => 'term-multiselect',
				'required'    => true,
				'placeholder' => __( 'Choose ' . $taxonomy['name'], 'wp-job-manager' ),
				'priority'    => 3,
				'default'     => '',
				'taxonomy'    => $key,
			);

		}

		return $fields;
	}
}
