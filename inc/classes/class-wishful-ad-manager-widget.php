<?php
/**
 * Class file for creating the ad widget.
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Wishful_Ad_Manager_Widget' ) ) {

	/**
	 * Class for creating the ad widget.
	 */
	class Wishful_Ad_Manager_Widget extends WP_Widget {

		/**
		 * Sets up the widgets name etc.
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'Wishful_Ad_Manager_Widget',
				'description' => __( 'Display your ads using using widget', 'wishful-ad-manager' ),
			);
			parent::__construct( 'Wishful_Ad_Manager_Widget', 'Wishful Ads', $widget_ops );
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			$title         = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$visibility    = ! empty( $instance['visibility'] ) ? $instance['visibility'] : ''; // Visibility class.
			$advertisement = ! empty( $instance['advertisement'] ) ? $instance['advertisement'] : 0; // $ad_id

			$content  = $args['before_widget'];
			$content .= '<div class="wishful-ad-manager widget-visibility ' . esc_attr( $visibility ) . '">';

			if ( ! empty( $title ) ) {
				$content .= $args['before_title'];
				$content .= apply_filters( 'widget_title', $title );
				$content .= $args['after_title'];
			}

			$content .= wishful_ad_manager_get_ad_content( $advertisement );
			$content .= '</div>';
			$content .= $args['after_widget'];

			echo $content; //phpcs:ignore
		}

		/**
		 * Returns the array of ad posts.
		 */
		private function get_ads() {

			$ads = array();

			$the_query = new WP_Query(
				array(
					'post_type'      => WISHFUL_AD_POST_TYPE,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				)
			);

			if ( $the_query->have_posts() ) {
				$ads[0] = __( '--Select--', 'wishful-ad-manager' );
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$ads[ get_the_ID() ] = get_the_title();
				}
			}

			wp_reset_postdata();

			return $ads;
		}

		/**
		 * Prints the select options for the ad posts.
		 */
		private function ads_dropdown( $value = '' ) {

			$ads = $this->get_ads();

			if ( is_array( $ads ) && ! empty( $ads ) ) {
				foreach ( $ads as $ad_id => $ad_title ) {
					$selected = $value === $ad_id ? 'selected' : '';
					?>
					<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $ad_id ); ?>"><?php echo esc_html( $ad_title ); ?></option>
					<?php
				}
			}

		}

		/**
		 * Prints the visibility options.
		 */
		private function visibility_dropdown( $value = '' ) {

			$options = array(
				''             => __( 'Do not hide', 'wishful-ad-manager' ),
				'hide-desktop' => __( 'Hide in desktop', 'wishful-ad-manager' ),
				'hide-tablet'  => __( 'Hide in tablet', 'wishful-ad-manager' ),
				'hide-mobile'  => __( 'Hide in mobile', 'wishful-ad-manager' ),
			);

			if ( is_array( $options ) && ! empty( $options ) ) {
				foreach ( $options as $opt_value => $opt_label ) {
					$selected = $value === $opt_value ? 'selected' : '';
					?>
					<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $opt_value ); ?>"><?php echo esc_html( $opt_label ); ?></option>
					<?php
				}
			}
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$title         = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$visibility    = ! empty( $instance['visibility'] ) ? $instance['visibility'] : '';
			$advertisement = ! empty( $instance['advertisement'] ) ? $instance['advertisement'] : 0;
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
					<?php esc_attr_e( 'Title:', 'wishful-ad-manager' ); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'advertisement' ) ); ?>">
					<?php esc_attr_e( 'Advertisement:', 'wishful-ad-manager' ); ?>
				</label>
				<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'advertisement' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'advertisement' ) ); ?>">
					<?php $this->ads_dropdown( $advertisement ); ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'visibility' ) ); ?>">
					<?php esc_attr_e( 'Visibility:', 'wishful-ad-manager' ); ?>
				</label>
				<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'visibility' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'visibility' ) ); ?>">
					<?php $this->visibility_dropdown( $visibility ); ?>
				</select>
			</p>
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance                  = array();
			$instance['title']         = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
			$instance['visibility']    = ( ! empty( $new_instance['visibility'] ) ) ? sanitize_text_field( $new_instance['visibility'] ) : '';
			$instance['advertisement'] = ( ! empty( $new_instance['advertisement'] ) ) ? absint( $new_instance['advertisement'] ) : 0;

			return $instance;
		}

	}

}


if ( ! function_exists( 'wishful_ad_manager_widget' ) ) {

	/**
	 * Init widget.
	 */
	function wishful_ad_manager_widget() {
		register_widget( 'Wishful_Ad_Manager_Widget' );
	}
	add_action( 'widgets_init', 'wishful_ad_manager_widget' );
}
