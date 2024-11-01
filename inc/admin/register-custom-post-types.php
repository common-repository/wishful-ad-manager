<?php
/**
 * Here we register all the custom post types for this plugin.
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

! defined( 'WISHFUL_AD_POST_TYPE' ) ? define( 'WISHFUL_AD_POST_TYPE', 'wishful-ads' ) : '';


if ( ! function_exists( 'wishful_ad_manager_custom_post_type' ) ) {

	/**
	 * Register custom post type.
	 */
	function wishful_ad_manager_custom_post_type() {

		$labels = array(
			'name'                  => _x( 'Ads', 'Post Type General Name', 'wishful-ad-manager' ),
			'singular_name'         => _x( 'Ad', 'Post Type Singular Name', 'wishful-ad-manager' ),
			'menu_name'             => __( 'Wishful Ads', 'wishful-ad-manager' ),
			'name_admin_bar'        => __( 'Wishful Ads', 'wishful-ad-manager' ),
			'archives'              => __( 'Ad Archives', 'wishful-ad-manager' ),
			'attributes'            => __( 'Ad Attributes', 'wishful-ad-manager' ),
			'parent_item_colon'     => __( 'Parent Ad:', 'wishful-ad-manager' ),
			'all_items'             => __( 'All Ads', 'wishful-ad-manager' ),
			'add_new_item'          => __( 'New Ad', 'wishful-ad-manager' ),
			'add_new'               => __( 'New Ad', 'wishful-ad-manager' ),
			'new_item'              => __( 'New Item', 'wishful-ad-manager' ),
			'edit_item'             => __( 'Edit Item', 'wishful-ad-manager' ),
			'update_item'           => __( 'Update Item', 'wishful-ad-manager' ),
			'view_item'             => __( 'View Ad', 'wishful-ad-manager' ),
			'view_items'            => __( 'View Ads', 'wishful-ad-manager' ),
			'search_items'          => __( 'Search Ad', 'wishful-ad-manager' ),
			'not_found'             => __( 'Not found', 'wishful-ad-manager' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wishful-ad-manager' ),
			'featured_image'        => __( 'Ad Banner', 'wishful-ad-manager' ),
			'set_featured_image'    => __( 'Set Ad Banner', 'wishful-ad-manager' ),
			'remove_featured_image' => __( 'Remove Ad Banner', 'wishful-ad-manager' ),
			'use_featured_image'    => __( 'Use as ad banner', 'wishful-ad-manager' ),
			'insert_into_item'      => __( 'Insert into ad', 'wishful-ad-manager' ),
			'uploaded_to_this_item' => __( 'Uploaded to this ad', 'wishful-ad-manager' ),
			'items_list'            => __( 'Ads list', 'wishful-ad-manager' ),
			'items_list_navigation' => __( 'Ads list navigation', 'wishful-ad-manager' ),
			'filter_items_list'     => __( 'Filter ads list', 'wishful-ad-manager' ),
		);
		$args   = array(
			'label'               => __( 'Ad', 'wishful-ad-manager' ),
			'description'         => __( 'Advertisements to display on your website.', 'wishful-ad-manager' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-megaphone',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'post',
		);
		register_post_type( WISHFUL_AD_POST_TYPE, $args );

	}
	add_action( 'init', 'wishful_ad_manager_custom_post_type', 10 );

}


/**
 * Modifies the post update message.
 *
 * @param array $messages Post update messages.
 */
function wishful_ad_manager_alter_post_update_messages( $messages ) {

	if ( WISHFUL_AD_POST_TYPE === get_post_type() ) {
		$messages['post'][1] = esc_html__( 'Ad updated.', 'wishful-ad-manager' );
		$messages['post'][6] = esc_html__( 'Ad published.', 'wishful-ad-manager' );
		$messages['post'][7] = esc_html__( 'Ad saved.', 'wishful-ad-manager' );
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'wishful_ad_manager_alter_post_update_messages' );
