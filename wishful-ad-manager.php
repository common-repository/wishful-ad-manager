<?php
/**
 * Plugin Name: Wishful Ad Manager
 * Plugin URI: https://www.wishfulthemes.com/
 * Description: Your best advertisement management WordPress plugin.
 * Author: Wishful Themes
 * Author URI: https://www.wishfulthemes.com/
 * Version: 1.0.1
 * Text Domain: wishful-ad-manager
 * License: GPLv2 or later
 * License URI: LICENSE
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

! defined( 'WISHFUL_AD_MANAGER_ROOT' ) ? define( 'WISHFUL_AD_MANAGER_ROOT', plugin_dir_path( __FILE__ ) ) : '';
! defined( 'WISHFUL_AD_MANAGER_ROOT_URL' ) ? define( 'WISHFUL_AD_MANAGER_ROOT_URL', plugin_dir_url( __FILE__ ) ) : '';


if ( ! function_exists( 'wishful_ad_manager' ) ) {

	/**
	 * Init plugin.
	 */
	function wishful_ad_manager() {
		require_once WISHFUL_AD_MANAGER_ROOT . 'inc/classes/class-wishful-ad-manager.php';
		Wishful_Ad_Manager::init();
	}
	add_action( 'plugins_loaded', 'wishful_ad_manager' );
}

