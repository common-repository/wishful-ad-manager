<?php
/**
 * Register admin menus.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Wishful_Ad_Manager_Settings' ) ) {

	/**
	 * Register admin menus.
	 */
	class Wishful_Ad_Manager_Settings {

		/**
		 * Init class.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
		}

		/**
		 * Register submenu
		 *
		 * @return void
		 */
		public function register_sub_menu() {
			add_submenu_page(
				'edit.php?post_type=' . WISHFUL_AD_POST_TYPE,
				__( 'Settings', 'wishful-ad-manager' ),
				__( 'Settings', 'wishful-ad-manager' ),
				'manage_options',
				'wishfuladmngr-settings',
				array( $this, 'settings_cb' )
			);
		}

		/**
		 * Callback function for header/footer scripts
		 *
		 * @return void
		 */
		public function settings_cb() {

			$options  = get_option( 'wishful_ad_manager_options' );
			$settings = isset( $options['settings'] ) ? $options['settings'] : array();

			$enable_ad_blocker_notice = isset( $settings['enable_ad_blocker_notice'] ) ? $settings['enable_ad_blocker_notice'] : 'no';
			$ad_blocker_notice        = isset( $settings['ad_blocker_notice'] ) ? $settings['ad_blocker_notice'] : __( 'Hi! Please support us by deactivating your AdBlocker extension..' );

			?>
				<div class="wrap">
					<h1 class="wp-heading-inline"><?php echo wp_kses_post( get_admin_page_title() ); ?></h1>

					<div class="container">
						<div id="wishful-ad-manager-settings">
							<form method="POST">

								<table class="form-table" role="presentation">
									<tbody>

										<tr>
											<th scope="row">
												<label for="enable_ad_blocker_notice"><?php esc_html_e( 'Enable Ad Blocker Notice?', 'wishful-ad-manager' ); ?></label>
											</th>
											<td>
												<?php
												wishful_ad_manager_switch_control(
													wishful_ad_manager_generate_field_name( 'settings[enable_ad_blocker_notice]', false ),
													$enable_ad_blocker_notice,
													'enable_ad_blocker_notice'
												);
												?>
											</td>
										</tr>

										<tr id="ad_blocker_notice-tr" style="display: none;">
											<th scope="row">
												<label for="ad_blocker_notice"><?php esc_html_e( 'Ad Blocker Notice', 'wishful-ad-manager' ); ?></label>
											</th>
											<td>
												<textarea name="<?php wishful_ad_manager_generate_field_name( 'settings[ad_blocker_notice]' ); ?>" id="ad_blocker_notice" cols="60" rows="10"><?php echo esc_html( $ad_blocker_notice ); ?></textarea>
											</td>
										</tr>

									</tbody>
								</table>
								<input type="hidden" name="<?php wishful_ad_manager_generate_field_name( 'action' ); ?>" value="options">
								<?php
								wp_nonce_field( '_wishful_ad_manager_nonce_action', '_wishful_ad_manager_nonce' );

								submit_button( __( 'Save Settings', 'wishful-ad-manager' ) );
								?>

							</form>
						</div>
					</div>
					<script>
						jQuery(function($){
							$(document).on('change', '#enable_ad_blocker_notice', function(){
								if ( $(this).is(':checked') ) {
									$('#ad_blocker_notice-tr').show();
								} else {
									$('#ad_blocker_notice-tr').hide();
								}
							});
							$('#enable_ad_blocker_notice').trigger('change');
						});
					</script>
				</div>
			<?php
		}
	}

	new Wishful_Ad_Manager_Settings();
}
