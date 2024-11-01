<?php
/**
 * Register meta box(es).
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wishful_ad_manager_register_metaboxes' ) ) {

	/**
	 * Registers metaboxes.
	 */
	function wishful_ad_manager_register_metaboxes() {

		add_meta_box(
			'advertisement-options',
			__( 'Advertisement Options', 'wishful-ad-manager' ),
			'wishful_ad_manager_metabox_advertisement_options',
			WISHFUL_AD_POST_TYPE
		);

	}
	add_action( 'add_meta_boxes', 'wishful_ad_manager_register_metaboxes' );
}

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function wishful_ad_manager_metabox_advertisement_options( $post ) {

	$ad_id      = ! empty( $post->ID ) ? $post->ID : 0;
	$is_publish = ! empty( $post->post_status ) && 'publish' === $post->post_status;

	$tabs = apply_filters( 'wishful_ad_manager_advertisement_options_tabs', array() );

	if ( ! is_array( $tabs ) || empty( $tabs ) ) {
		return;
	}

	/**
	 * Sort tabs according to the provided priorities.
	 */
	asort( $tabs );

	if ( $is_publish ) {
		?>
		<div id="wishful-ad-manager-shortcode-box">
			<span><?php esc_html_e( 'Shortcode:', 'wishful-ad-manager' ); ?></span>
			<code>
				<?php echo esc_html( "[wishful_ad_manager ad={$ad_id}]" ); ?>
			</code>
			<p class="howto"><?php esc_html_e( 'To set visibility, add "hide" parameter in your shortcode. This parameter accepts values as: desktop, tablet and mobile. For ex: [wishful_ad_manager ad=YOUR_AD_ID hide=desktop]', 'wishful-ad-manager' ); ?></p>
		</div>
		<?php
	}
	?>

	<div id="wishful-ad-manager-tab-heads" class="nav-tab-wrapper">
		<?php
		foreach ( $tabs as $tab_id => $tab ) {
			$label       = isset( $tab['label'] ) ? $tab['label'] : '';
			$callback    = isset( $tab['callback'] ) ? $tab['callback'] : '';
			$input_attrs = isset( $tab['input_attrs'] ) && is_array( $tab['input_attrs'] ) ? $tab['input_attrs'] : array();

			?>
			<a href="<?php echo esc_attr( '#' . $tab_id ); ?>" data-tab-content="<?php echo esc_attr( $callback ); ?>" id="<?php echo esc_attr( $tab_id ); ?>" <?php echo esc_attr( implode( ' ', $input_attrs ) ); ?> class="nav-tab"><?php echo esc_html( $label ); ?></a>
			<?php
		}
		?>
	</div>

	<div id="tab-contents-wrapper">
		<?php
		foreach ( $tabs as $tab_id => $tab ) {
			$callback = isset( $tab['callback'] ) ? $tab['callback'] : '';
			?>
			<div id="<?php echo esc_attr( $callback ); ?>" data-tab-id="<?php echo esc_attr( $tab_id ); ?>" class="tab-content hidden">
				<?php call_user_func( $callback, $post, $tab_id ); ?>
			</div>
			<?php
		}

		wp_nonce_field( '_wishful_ad_manager_nonce_action', '_wishful_ad_manager_nonce' );
		?>
	</div>

	<?php

}



if ( ! function_exists( 'wishful_ad_manager_create_default_tab_heads' ) ) {

	/**
	 * Create default tabs.
	 *
	 * @param array $tabs Tabs configs.
	 */
	function wishful_ad_manager_create_default_tab_heads( $tabs ) {

		$tabs = array(

			'ad-contents' => array(
				'label'       => esc_html__( 'Ad Contents', 'wishful-ad-manager' ),
				'callback'    => 'wishful_ad_manager_tab_ad_contents_callback',
				'priority'    => 10,
				'input_attrs' => array(),
			),
			'ad-links'    => array(
				'label'       => esc_html__( 'Ad Links', 'wishful-ad-manager' ),
				'callback'    => 'wishful_ad_manager_tab_ad_links_callback',
				'priority'    => 20,
				'input_attrs' => array(),
			),
			'adjustments' => array(
				'label'       => esc_html__( 'Adjustments', 'wishful-ad-manager' ),
				'callback'    => 'wishful_ad_manager_tab_adjustments_callback',
				'priority'    => 30,
				'input_attrs' => array(),
			),

		);

		return $tabs;
	}
	add_filter( 'wishful_ad_manager_advertisement_options_tabs', 'wishful_ad_manager_create_default_tab_heads' );
}
