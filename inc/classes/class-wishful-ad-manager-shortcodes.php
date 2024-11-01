<?php
/**
 * Class file for generating required shortcodes.
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Wishful_Ad_Manager_Shortcodes' ) ) {

	/**
	 * Generates shortcodes.
	 */
	class Wishful_Ad_Manager_Shortcodes {

		/**
		 * Init class.
		 */
		public function __construct() {

			$shortcodes = get_class_methods( $this );

			/**
			 * Unset construct method.
			 */
			if ( isset( $shortcodes[0] ) ) {
				unset( $shortcodes[0] );
			}

			if ( is_array( $shortcodes ) && ! empty( $shortcodes ) ) {
				foreach ( $shortcodes as $shortcode ) {
					add_shortcode( $shortcode, array( $this, $shortcode ) );
				}
			}

		}

		/**
		 * Main shortcode for displaying the ad.
		 */
		public function wishful_ad_manager( $atts ) {
			$options = shortcode_atts(
				array(
					'ad'   => 0,
					'hide' => '',
				),
				$atts
			);

			$ad_id      = $options['ad'];
			$visibility = $options['hide'] ? "hide-{$options['hide']}" : '';

			if ( ! $ad_id ) {
				return;
			}

			$content = wishful_ad_manager_get_ad_content( $ad_id );

			return '<div class="wishful-ad-manager widget-visibility ' . esc_attr( $visibility ) . '">' . $content . '</div>';
		}
	}

	new Wishful_Ad_Manager_Shortcodes();
}
