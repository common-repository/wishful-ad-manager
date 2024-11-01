<?php
/**
 * Process the ad statistics.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wishful_Ad_Manager_Stats' ) ) {

	/**
	 * Class for working with ad statistics.
	 */
	class Wishful_Ad_Manager_Stats {

		/**
		 * Ad data.
		 *
		 * @var array
		 */
		private $data;

		/**
		 * Ad ID.
		 *
		 * @var int
		 */
		private $ad_id;

		/**
		 * Ad interaction type. Either `view` or `click`
		 *
		 * @var string
		 */
		private $type;

		/**
		 * Accepts ad data parameter, which is then used by other methods accordingly.
		 *
		 * @param array $data
		 */
		public function __construct( array $data ) {
			$data['ip'] = ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

			$ad_data = wishful_ad_manager_data_sanitize( $data );

			$this->data  = $ad_data ? $ad_data : array();
			$this->ad_id = isset( $ad_data['ad_id'] ) ? (int) $ad_data['ad_id'] : 0;
			$this->type  = isset( $ad_data['type'] ) ? $ad_data['type'] : '';
		}

		/**
		 * Sets the ad statistics according to the data passed to the __construct.
		 *
		 * @return void
		 */
		public function set() {
			$data  = $this->data;
			$ad_id = $this->ad_id;
			$nonce = isset( $data['nonce'] ) ? $data['nonce'] : '';

			if ( ! wp_verify_nonce( $nonce, 'wishful_ad_manager_ajax_nonce' ) ) {
				return;
			}

			$stats = (array) $this->get();

			$stats = $this->set_stats( $stats );

			$stats = $this->set_by_date( $stats );

			if ( ! empty( $stats ) && is_array( $stats ) ) {
				update_post_meta( $ad_id, 'wishful_ad_manager_stats', $stats );
			}

		}

		/**
		 * Sets the stats by date. Uses `set_stats` method.
		 */
		private function set_by_date( $stats ) {
			$date = date_i18n( 'd-m-Y' );

			if ( ! isset( $stats['by_date'] ) ) {
				$stats['by_date'] = array();
			}

			$by_date = $stats['by_date'];

			$today_stat = isset( $by_date[ $date ] ) ? $by_date[ $date ] : array();

			$by_date[ $date ] = $this->set_stats( $today_stat );

			$stats['by_date'] = $by_date;

			return $stats;
		}

		/**
		 * Main powerhouse for setting up statistics according to the type.
		 */
		private function set_stats( $stats ) {

			$type = $this->type;

			if ( ! $type ) {
				return $stats;
			}

			switch ( $type ) {
				case 'view':
					$stats['total_views']  = $this->get_total_views( $stats );
					$stats['viewed_by']    = $this->get_viewed_by( $stats );
					$stats['unique_views'] = $this->get_unique_views( $stats );
					break;

				case 'click':
					$stats['total_clicks']  = $this->get_total_clicks( $stats );
					$stats['clicked_by']    = $this->get_clicked_by( $stats );
					$stats['unique_clicks'] = $this->get_unique_clicks( $stats );
					break;

				default:
					break;
			}

			return $stats;

		}

		private function get_total_views( $stats ) {
			$total_views = isset( $stats['total_views'] ) ? (int) $stats['total_views'] : 0;
			$total_views++;
			return $total_views;
		}

		private function get_viewed_by( $stats ) {
			$ip        = isset( $this->data['ip'] ) ? $this->data['ip'] : '';
			$viewed_by = isset( $stats['viewed_by'] ) ? $stats['viewed_by'] : array();

			if ( $ip ) {
				array_push( $viewed_by, $ip );
			}

			$viewed_by = array_unique( $viewed_by );
			$viewed_by = array_values( $viewed_by );

			return $viewed_by;
		}

		private function get_unique_views( $stats ) {
			$unique_views = isset( $stats['viewed_by'] ) ? count( $stats['viewed_by'] ) : 0;
			return $unique_views;
		}

		private function get_total_clicks( $stats ) {
			$total_clicks = isset( $stats['total_clicks'] ) ? (int) $stats['total_clicks'] : 0;
			$total_clicks++;
			return $total_clicks;
		}

		private function get_clicked_by( $stats ) {
			$ip         = isset( $this->data['ip'] ) ? $this->data['ip'] : '';
			$clicked_by = isset( $stats['clicked_by'] ) ? $stats['clicked_by'] : array();

			if ( $ip ) {
				array_push( $clicked_by, $ip );
			}

			$clicked_by = array_unique( $clicked_by );
			$clicked_by = array_values( $clicked_by );

			return $clicked_by;
		}

		private function get_unique_clicks( $stats ) {
			$unique_clicks = isset( $stats['clicked_by'] ) ? count( $stats['clicked_by'] ) : 0;
			return $unique_clicks;
		}

		/**
		 * Returns the ad statistics according to the data passed to class.
		 *
		 * @return array
		 */
		public function get( $key = null, $default = null ) {
			$ad_id = $this->ad_id;
			$stats = get_post_meta( $ad_id, 'wishful_ad_manager_stats', true );

			if ( is_null( $key ) && is_null( $default ) ) {
				return $stats;
			}

			return isset( $stats[ $key ] ) ? $stats[ $key ] : $default;
		}

	}
}
