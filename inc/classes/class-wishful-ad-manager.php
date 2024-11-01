<?php
/**
 * Main class file for initializing this plugin.
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wishful_Ad_Manager' ) ) {

	/**
	 * Main class for plugin.
	 */
	class Wishful_Ad_Manager {

		/**
		 * Init class.
		 */
		public static function init() {
			self::includes();
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_assets' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'public_assets' ) );
		}

		public static function localized_data() {

			$data = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wishful_ad_manager_ajax_nonce' ),
			);

			$data['editor'] = wp_enqueue_code_editor(
				array(
					'type' => 'text/css',
				)
			);

			return apply_filters( 'wishful_ad_manager_filter_localized_data', $data );
		}

		/**
		 * Enqueues admin side assets.
		 */
		public static function admin_assets( $hook ) {
			$root_url = WISHFUL_AD_MANAGER_ROOT_URL;

			wp_enqueue_style( 'wishful-ad-manager-admin-styles', "{$root_url}assets/css/admin.css", array(), '1.0.0', 'all' );

			/**
			 * Settings for codemirror library.
			 *
			 * This is used by admin.js file.
			 */
			if ( 'wishful-ads_page_wishfuladmngr-header-footer-scripts' === $hook && function_exists( 'wp_enqueue_code_editor' ) ) {
				wp_enqueue_style( 'wp-codemirror' );
				wp_enqueue_script( 'wp-theme-plugin-editor' );
				$cm_settings['editorHeaderScripts'] = wp_enqueue_code_editor(
					array(
						'type' => 'text/html',
					)
				);
				$cm_settings['editorFooterScripts'] = wp_enqueue_code_editor(
					array(
						'type' => 'text/html',
					)
				);
				wp_localize_script( 'jquery', 'wishfulAdManagerCMSettings', $cm_settings );
			}

			wp_register_script( 'wishful-ad-manager-admin-script', "{$root_url}assets/js/admin.js", array( 'jquery' ), '1.0.0', true );
			wp_enqueue_script( 'wishful-ad-manager-admin-script' );
		}

		/**
		 * Enqueues admin side assets.
		 */
		public static function public_assets() {
			$root_url = WISHFUL_AD_MANAGER_ROOT_URL;
			wp_register_script( 'wishful-ad-manager-public-script', "{$root_url}assets/js/public.js", array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'wishful-ad-manager-public-script', 'wishfulAdManagerData', self::localized_data() );
			wp_enqueue_script( 'wishful-ad-manager-public-script' );
		}

		/**
		 * Include all the files.
		 */
		public static function includes() {
			$root_dir = WISHFUL_AD_MANAGER_ROOT;

			$files_path = array(
				'inc/helpers.php',
				'inc/admin/register-custom-post-types.php',
				'inc/admin/register-metaboxes.php',
				'inc/admin/admin-columns.php',

				// Tabs.
				'inc/admin/tab-callbacks.php',

				'inc/classes/class-wishful-ad-manager-detect-ad-blocker.php',
				'inc/classes/class-wishful-ad-manager-save-posts.php',
				'inc/classes/class-wishful-ad-manager-stats.php',
				'inc/classes/class-wishful-ad-manager-dynamic-css.php',
				'inc/classes/class-wishful-ad-manager-widget.php',
				'inc/classes/class-wishful-ad-manager-shortcodes.php',
				'inc/classes/class-wishful-ad-manager-ajax.php',
				'inc/classes/class-wishful-ad-manager-header-footer-scripts.php',
				'inc/classes/class-wishful-ad-manager-settings.php',
			);

			foreach ( $files_path as $file ) {
				require_once $root_dir . $file;
			}

		}
	}
}
