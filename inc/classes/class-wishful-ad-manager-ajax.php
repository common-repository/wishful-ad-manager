<?php
/**
 * Ajax class file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wishful_Ad_Manager_Ajax' ) ) {

	class Wishful_Ad_Manager_Ajax {

		public function __construct() {
			add_action( 'wp_ajax_wishful_ad_manager_ajax', array( $this, 'do_ajax' ) );
			add_action( 'wp_ajax_nopriv_wishful_ad_manager_ajax', array( $this, 'do_ajax' ) );
		}

		public function do_ajax() {
			$stats = new Wishful_Ad_Manager_Stats( $_POST );
			$stats->set();

			wp_send_json_success();
		}
	}

	new Wishful_Ad_Manager_Ajax();
}
