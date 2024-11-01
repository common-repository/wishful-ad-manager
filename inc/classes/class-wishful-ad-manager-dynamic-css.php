<?php
/**
 * Class file for generating dynamic css.
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Wishful_Ad_Manager_Dynamic_CSS' ) ) {

	/**
	 * Class for generating dynamic css.
	 */
	class Wishful_Ad_Manager_Dynamic_CSS {

		/**
		 * Init class
		 */
		public static function init() {
			add_action( 'wp_head', array( __CLASS__, 'print_css' ), 20 );
		}

		/**
		 * Sets the ads posts.
		 */
		public static function print_css() {

			$ads = get_posts(
				array(
					'post_type'   => WISHFUL_AD_POST_TYPE,
					'numberposts' => -1,
				)
			);

			if ( is_array( $ads ) && ! empty( $ads ) > 0 ) {
				?>
				<style id="wishful-ad-manager-dynamic-css">
					<?php
					foreach ( $ads as $ad ) {
						$ad_id = $ad->ID;
						echo self::array_to_css( self::get_adjustments( $ad_id ), $ad_id ); //phpcs:ignore
					}
					?>
				</style>
				<?php
			}

		}

		/**
		 * Retuns the valid adjustments css properties.
		 *
		 * @param int $ad_id Advertisement ID.
		 */
		public static function get_adjustments( $ad_id ) {

			$adjustments = array();

			$post_data        = wishful_ad_manager_get_post_data( $ad_id );
			$adjustments_data = ! empty( $post_data['wishful_ads']['adjustments'] ) ? $post_data['wishful_ads']['adjustments'] : array();

			$adjustments['width']  = ! empty( $adjustments_data['box_width'] ) ? $adjustments_data['box_width'] : '';
			$adjustments['height'] = ! empty( $adjustments_data['box_height'] ) ? $adjustments_data['box_height'] : '';

			$adjustments['margin-top']    = ! empty( $adjustments_data['box_margin']['top'] ) ? $adjustments_data['box_margin']['top'] : '';
			$adjustments['margin-right']  = ! empty( $adjustments_data['box_margin']['right'] ) ? $adjustments_data['box_margin']['right'] : '';
			$adjustments['margin-bottom'] = ! empty( $adjustments_data['box_margin']['bottom'] ) ? $adjustments_data['box_margin']['bottom'] : '';
			$adjustments['margin-left']   = ! empty( $adjustments_data['box_margin']['left'] ) ? $adjustments_data['box_margin']['left'] : '';

			$adjustments['padding-top']    = ! empty( $adjustments_data['box_padding']['top'] ) ? $adjustments_data['box_padding']['top'] : '';
			$adjustments['padding-right']  = ! empty( $adjustments_data['box_padding']['right'] ) ? $adjustments_data['box_padding']['right'] : '';
			$adjustments['padding-bottom'] = ! empty( $adjustments_data['box_padding']['bottom'] ) ? $adjustments_data['box_padding']['bottom'] : '';
			$adjustments['padding-left']   = ! empty( $adjustments_data['box_padding']['left'] ) ? $adjustments_data['box_padding']['left'] : '';

			$result = array_filter( $adjustments, 'strlen' );

			return $result;

		}

		/**
		 * Converts provided adjustment values to css.
		 *
		 * @param array $adjustments Array of adjustments that needs to be converted to css.
		 * @param int   $ad_id Advertisement ID.
		 */
		public static function array_to_css( $adjustments, $ad_id ) {
			$css = '';

			if ( is_array( $adjustments ) && ! empty( $adjustments ) > 0 ) {
				foreach ( $adjustments as $property => $css_value ) {
					$css .= "{$property}:{$css_value}px; \n";
				}
			}

			return $css ? '.wishful-ad-manager-' . $ad_id . ' { ' . $css . ' } ' : '';
		}

	}

	Wishful_Ad_Manager_Dynamic_CSS::init();
}

