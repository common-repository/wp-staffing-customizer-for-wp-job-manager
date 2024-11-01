<?php
/**
 * Single view Job Summary
 *
 * Hooked into single_job_listing_start priority 40
 *
 * This template can be overridden by copying it to yourtheme/sn-staffing-customizer/job-summary.php.
 *
 * @link       http://wp-staffing.com/
 * @since      1.0.0
 *
 * @package    Wp_Staffing_Customizer
 * @subpackage Wp_Staffing_Customizer/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

echo '<div class="sn-staffing-customizer__job-summary" >';
	echo '<h2>' . esc_html_e( 'Job Summary', 'wp-job-manager' ) . '</h2>';
	echo '<ul class="sn-staffing-customizer__job-summary-list" >';

if ( ! empty( $fields ) ) {

	foreach ( $fields as $key => $value ) {
		echo '<li>';
			echo '<span class="sn-staffing-customizer__job-summary-list-title">';
				echo esc_html( $value['tax'] );
			echo ':</span> ';
			echo '<span class="sn-staffing-customizer__job-summary-list-desc">';
				echo esc_html( $value['desc'] );
			echo '</span>';
		echo '</li>';
	}
}

	echo '</ul>';
echo '</div>';

