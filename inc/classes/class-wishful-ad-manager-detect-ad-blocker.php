<?php
/**
 * Class file for detecting the ad blocker on client side.
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wishful_Ad_Manager_Detect_Ad_Blocker' ) ) {

	/**
	 * Detects the ad blocker in client side.
	 */
	class Wishful_Ad_Manager_Detect_Ad_Blocker {

		/**
		 * Whether or not display notice in admin side.
		 */
		public $display_notice = false;

		public function __construct() {

			$this->current_screen();

			if ( ! $this->display_notice ) {
				return;
			}

			$options  = get_option( 'wishful_ad_manager_options' );
			$settings = isset( $options['settings'] ) ? $options['settings'] : array();

			$enable_ad_blocker_notice = isset( $settings['enable_ad_blocker_notice'] ) ? $settings['enable_ad_blocker_notice'] : 'no';

			if ( 'yes' !== $enable_ad_blocker_notice && ! is_admin() ) {
				return;
			}

			$this->ad_blocker_notice = isset( $settings['ad_blocker_notice'] ) ? $settings['ad_blocker_notice'] : __( 'Hi! Please support us by deactivating your AdBlocker extension..' );

			if ( is_admin() ) {
				$this->ad_blocker_notice = esc_html__( 'Please deactivate the Ad blocker extension so that Wishful Ad Manager plugin can work correctly.', 'wishful-ad-manager' );
			}

			add_action( 'wp_body_open', array( $this, 'set_notice' ), 1 );
			add_action( 'in_admin_header', array( $this, 'set_notice' ), 1 );

			add_action( 'wp_footer', array( $this, 'check_blocker' ), 99 );
			add_action( 'admin_footer', array( $this, 'check_blocker' ), 99 );
		}

		public function current_screen() {

			if ( ! is_admin() ) {
				$this->display_notice = true;
				return;
			}
			$current_screen = get_current_screen();

			$this->display_notice = isset( $current_screen->post_type ) && 'wishful-ads' === $current_screen->post_type;
		}

		public function set_notice() {

			?>
			<style>
			.wishfuladmanager-notice-modal {
					display: none; /* Hidden by default */
					position: fixed; /* Stay in place */
					z-index: 9999; /* Sit on top */
					left: 0;
					top: 0;
					width: 100%; /* Full width */
					height: 100%; /* Full height */
					overflow: auto; /* Enable scroll if needed */
					background-color: rgb(0,0,0); /* Fallback color */
					background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
				}

				/* Modal Content/Box */
				.wishfuladmanager-notice-modal-content {
					position: relative;
					background-color: #fefefe;
					margin: 15% auto; /* 15% from the top and centered */
					padding: 20px;
					border: 1px solid #888;
					width: 80%; /* Could be more or less, depending on screen size */
				}

				/* The Close Button */
				.close {
					color: #aaa;
					float: right;
					font-size: 28px;
					font-weight: bold;
					position: absolute;
					right: 15px;
					top: 10px;
				}

				.close:hover,
				.close:focus {
					color: black;
					text-decoration: none;
					cursor: pointer;
				}
			</style>
			<div id="wishfuladmanager-notice" class="wishfuladmanager-notice-modal">
				<!-- Modal content -->
				<div class="wishfuladmanager-notice-modal-content">
					<span class="close" onclick="wishfulAdManagerHideNotice()">&times;</span>
					<p><?php echo esc_html( $this->ad_blocker_notice ); ?></p>
				</div>
			</div>
			<?php
		}

		public function check_blocker() {
			?>
			<!-- Just to check for ad blocker, nothing fancy. -->
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" onerror="wishfulAdManagerDisplayNotice()"></script>
			<script>
			function wishfulAdManagerDisplayNotice(){
				jQuery(function($){
					var isOnline = window.navigator.onLine;
					if ( isOnline === true ) {
						$('#wishfuladmanager-notice').attr('style','display:block');
					}
				});
			}
			function wishfulAdManagerHideNotice(){
				jQuery(function($){
					$('#wishfuladmanager-notice').attr('style','display:none');
				});
			}
			</script>
			<?php
		}
	}

	if ( is_admin() ) {
		add_action(
			'admin_head',
			function() {
				new Wishful_Ad_Manager_Detect_Ad_Blocker();
			},
			1
		);
	} else {
		new Wishful_Ad_Manager_Detect_Ad_Blocker();
	}
}
