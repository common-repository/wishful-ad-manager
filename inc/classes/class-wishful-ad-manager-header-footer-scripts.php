<?php
/**
 * Register admin menus.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Wishful_Ad_Manager_Header_Footer_Scripts' ) ) {

	/**
	 * Register admin menus.
	 */
	class Wishful_Ad_Manager_Header_Footer_Scripts {

		/**
		 * Init class.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
			add_action( 'wp_head', array( $this, 'hook_wp_head' ), 999 );
			add_action( 'wp_footer', array( $this, 'hook_wp_footer' ), 999 );
		}

		private function get_scripts() {
			$options = get_option( 'wishful_ad_manager_options' );
			return isset( $options['header_footer_scripts'] ) ? $options['header_footer_scripts'] : array();
		}

		private function echo_script( $script ) {

			if ( empty( $script ) ) {
				return;
			}

			if ( is_admin() ) {
				echo esc_html( wp_unslash( $script ) );
			} else {
				echo wp_unslash( $script );
			}
		}

		/**
		 * Hook scripts related to header.
		 *
		 * @return void
		 */
		public function hook_wp_head() {
			$scripts        = $this->get_scripts();
			$header_scripts = ! empty( $scripts['header_scripts'] ) ? $scripts['header_scripts'] : '';

			$this->echo_script( $header_scripts );

		}

		/**
		 * Hook scripts related to footer.
		 *
		 * @return void
		 */
		public function hook_wp_footer() {
			$scripts        = $this->get_scripts();
			$footer_scripts = ! empty( $scripts['footer_scripts'] ) ? $scripts['footer_scripts'] : '';

			$this->echo_script( $footer_scripts );
		}

		/**
		 * Register submenu
		 *
		 * @return void
		 */
		public function register_sub_menu() {
			add_submenu_page(
				'edit.php?post_type=' . WISHFUL_AD_POST_TYPE,
				__( 'Header/Footer Scripts', 'wishful-ad-manager' ),
				__( 'Header/Footer Scripts', 'wishful-ad-manager' ),
				'manage_options',
				'wishfuladmngr-header-footer-scripts',
				array( $this, 'header_footer_scripts_cb' )
			);
		}

		/**
		 * Callback function for header/footer scripts
		 *
		 * @return void
		 */
		public function header_footer_scripts_cb() {
			$scripts = $this->get_scripts();

			$header_scripts = ! empty( $scripts['header_scripts'] ) ? $scripts['header_scripts'] : '';
			$footer_scripts = ! empty( $scripts['footer_scripts'] ) ? $scripts['footer_scripts'] : '';
			?>
			<div class="wrap">
				<h1 class="wp-heading-inline"><?php echo wp_kses_post( get_admin_page_title() ); ?></h1>

				<div class="container">
					<div id="wishful-ad-manager-header-footer-scripts">
						<form method="post">
							<div class="header-scripts-wrapper">
								<h2><?php esc_html_e( 'Header Scripts', 'wishful-ad-manager' ); ?></h2>
								<p class="description"><?php esc_html_e( 'These scripts will be hooked in Header ( wp_head ).', 'wishful-ad-manager' ); ?></p>
								<textarea name="wishful_ad_manager[header_footer_scripts][header_scripts]" id="header-scripts"><?php $this->echo_script( $header_scripts ); ?></textarea>
							</div>

							<div class="footer-scripts-wrapper">
								<h2><?php esc_html_e( 'Footer Scripts', 'wishful-ad-manager' ); ?></h2>
								<p class="description"><?php esc_html_e( 'These scripts will be hooked in Footer ( wp_footer ).', 'wishful-ad-manager' ); ?></p>
								<textarea name="wishful_ad_manager[header_footer_scripts][footer_scripts]" id="footer-scripts"><?php $this->echo_script( $footer_scripts ); ?></textarea>
							</div>
							<input type="hidden" name="<?php wishful_ad_manager_generate_field_name( 'action' ); ?>" value="options">
							<?php
							wp_nonce_field( '_wishful_ad_manager_nonce_action', '_wishful_ad_manager_nonce' );

							submit_button( __( 'Save Scripts', 'wishful-ad-manager' ) );
							?>
						</form>
					</div>
				</div>
			</div>
			<?php
		}
	}

	new Wishful_Ad_Manager_Header_Footer_Scripts();
}
