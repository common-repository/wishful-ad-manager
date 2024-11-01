<?php
/**
 * This file has the required codes to create columns in ad listing at admin side.
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create columns in ad post types.
 *
 * @param array $columns Column array lists.
 */
function wishful_ad_manager_set_ad_columns( $columns ) {
	$date_col = '';
	if ( isset( $columns['date'] ) ) {
		$date_col = $columns['date'];
		unset( $columns['date'] );
	}

	$columns['shortcode']     = esc_html__( 'Shortcode', 'wishful-ad-manager' );
	$columns['total_views']   = esc_html__( 'Total Views', 'wishful-ad-manager' );
	$columns['unique_views']  = esc_html__( 'Unique Views', 'wishful-ad-manager' );
	$columns['total_clicks']  = esc_html__( 'Total Clicks', 'wishful-ad-manager' );
	$columns['unique_clicks'] = esc_html__( 'Unique Clicks', 'wishful-ad-manager' );

	$columns['date'] = $date_col;
	return $columns;
}
add_filter( 'manage_' . WISHFUL_AD_POST_TYPE . '_posts_columns', 'wishful_ad_manager_set_ad_columns' );

/**
 * Set data to respected custom column.
 */
function wishful_ad_manager_set_ad_column_data( $column, $ad_id ) {

	$stats = new Wishful_Ad_Manager_Stats(
		array(
			'ad_id' => $ad_id,
		)
	);

	switch ( $column ) {
		case 'shortcode':
			echo esc_html( "[wishful_ad_manager ad={$ad_id}]" );
			break;

		case 'total_views':
			echo esc_html( $stats->get( 'total_views', 0 ) );
			break;

		case 'unique_views':
			echo esc_html( $stats->get( 'unique_views', 0 ) );
			break;

		case 'total_clicks':
			echo esc_html( $stats->get( 'total_clicks', 0 ) );
			break;

		case 'unique_clicks':
			echo esc_html( $stats->get( 'unique_clicks', 0 ) );
			break;

		default:
			// code...
			break;
	}
}
add_action( 'manage_' . WISHFUL_AD_POST_TYPE . '_posts_custom_column', 'wishful_ad_manager_set_ad_column_data', 10, 2 );
