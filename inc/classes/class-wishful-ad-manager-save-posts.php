<?php
/**
 * This handles the saving the custom post types data to post meta.
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Wishful_Ad_Manager_Save_Posts' ) ) {

	/**
	 * Handles the saving of custom post type post meta data.
	 */
	class Wishful_Ad_Manager_Save_Posts {

		/**
		 * Init class.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'save_options' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );
		}

		/**
		 * Returns the santized submitted data.
		 */
		public function get_submitted_data( $sanitize = true ) {

			if ( ! isset( $_POST['_wishful_ad_manager_nonce'] ) || ! isset( $_POST['wishful_ad_manager'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( sanitize_key( $_POST['_wishful_ad_manager_nonce'] ), '_wishful_ad_manager_nonce_action' ) ) {
				return;
			}

			/**
			 * Here we are using sanitize_text_field function wrapped with our custom function.
			 * This custom function recursively iterates the sanitize_text_field function to every string element of provided array
			 * and returns the array with sanitized string elements.
			 */
			$submitted_data = $sanitize ? wishful_ad_manager_data_sanitize( $_POST ) : $_POST;

			return isset( $submitted_data['wishful_ad_manager'] ) ? $submitted_data['wishful_ad_manager'] : array();
		}

		/**
		 * Save header footer scripts.
		 *
		 * @return void
		 */
		public function save_options() {
			$submitted_data = $this->get_submitted_data( false );

			if ( empty( $submitted_data['action'] ) ) {
				return;
			}

			if ( 'options' !== $submitted_data['action'] ) {
				return;
			}

			$options = get_option( 'wishful_ad_manager_options', array() );

			unset( $submitted_data['action'] );

			$data = array_merge( $options, $submitted_data );

			if ( is_array( $submitted_data ) && ! empty( $submitted_data ) ) {
				update_option( 'wishful_ad_manager_options', $data );
			}
		}

		/**
		 * Save wishful posts.
		 *
		 * @param int $post_id Current post id.
		 */
		public function save_post( $post_id ) {

			if ( empty( $post_id ) ) {
				return $post_id;
			}

			if ( WISHFUL_AD_POST_TYPE !== get_post_type( $post_id ) ) {
				return;
			}

			$submitted_data = $this->get_submitted_data();

			if ( ! empty( $submitted_data ) ) {
				update_post_meta( $post_id, 'wishful_ad_manager', $submitted_data );
			}

		}

	}

	new Wishful_Ad_Manager_Save_Posts();

}

