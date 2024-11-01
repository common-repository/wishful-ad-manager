<?php

/**
 * Callback content function for tab.
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wishful_ad_manager_tab_ad_contents_callback' ) ) {

	/**
	 * Callback function.
	 *
	 * @param WP_Post $post Current post object.
	 */
	function wishful_ad_manager_tab_ad_contents_callback( $post ) {

		$post_data         = wishful_ad_manager_get_post_data();
		$ad_contents_data  = ! empty( $post_data['wishful_ads']['ad_contents'] ) ? $post_data['wishful_ads']['ad_contents'] : array();
		$custom_script     = ! empty( $ad_contents_data['custom_script'] ) ? $ad_contents_data['custom_script'] : '';
		$use_custom_script = isset( $ad_contents_data['use_custom_script'] ) ? $ad_contents_data['use_custom_script'] : '';

		$editor_settings = array(
			'teeny'         => true,
			'tinymce'       => false,
			'media_buttons' => false,
			'textarea_name' => wishful_ad_manager_generate_field_name( 'ad_contents[custom_script]', false ),
		);

		?>
		<div class="container">
			<p class="howto"><?php esc_html_e( 'Select if you want to use custom script or ad banner for your advertisement contents.', 'wishful-ad-manager' ); ?></p>

			<div class="field-wrap">
				<label>
					<input <?php checked( $use_custom_script, 'yes' ); ?> type="checkbox" name="<?php wishful_ad_manager_generate_field_name( 'ad_contents[use_custom_script]' ); ?>" value="yes" id="use-custom-scripts">
					<span class="field-label right"><?php esc_html_e( 'Use Custom Script', 'wishful-ad-manager' ); ?></span>
				</label>
			</div>

			<div class="field-wrap" id="custom-script-box-wrapper">
				<h3><?php esc_html_e( 'Custom Script', 'wishful-ad-manager' ); ?></h3>
				<?php wp_editor( $custom_script, 'custom-script-box', $editor_settings ); ?>
			</div>

			<div class="field-wrap" id="ad-banner-box-wrapper">
				<h3><?php esc_html_e( 'Ad Banner', 'wishful-ad-manager' ); ?></h3>
			</div>

		</div>
		<?php
	}
}



if ( ! function_exists( 'wishful_ad_manager_tab_ad_links_callback' ) ) {

	/**
	 * Callback function.
	 *
	 * @param WP_Post $post Current post object.
	 */
	function wishful_ad_manager_tab_ad_links_callback( $post ) {

		$post_data    = wishful_ad_manager_get_post_data();
		$ad_links     = ! empty( $post_data['wishful_ads']['ad_links'] ) ? $post_data['wishful_ads']['ad_links'] : array();
		$link         = ! empty( $ad_links['link'] ) ? $ad_links['link'] : '';
		$open_new_tab = isset( $ad_links['open_new_tab'] ) ? $ad_links['open_new_tab'] : '';

		?>
		<div class="container">
			<p class="howto"><?php esc_html_e( 'You can provide custom link for your ad content, leave empty to disable.', 'wishful-ad-manager' ); ?></p>

			<div class="field-wrap">
				<label>
					<span class="field-label"><?php esc_html_e( 'Link', 'wishful-ad-manager' ); ?></span>
					<input type="url" value="<?php echo esc_url( $link ); ?>" name="<?php wishful_ad_manager_generate_field_name( 'ad_links[link]' ); ?>">
				</label>
			</div>

			<div class="field-wrap">
				<label>
					<input <?php checked( $open_new_tab, 'yes' ); ?> type="checkbox" name="<?php wishful_ad_manager_generate_field_name( 'ad_links[open_new_tab]' ); ?>" value="yes">
					<span class="field-label right"><?php esc_html_e( 'Open new tab?', 'wishful-ad-manager' ); ?></span>
				</label>
			</div>

		</div>
		<?php
	}
}




if ( ! function_exists( 'wishful_ad_manager_tab_adjustments_callback' ) ) {

	/**
	 * Callback function.
	 *
	 * @param WP_Post $post Current post object.
	 */
	function wishful_ad_manager_tab_adjustments_callback( $post ) {

		$post_data        = wishful_ad_manager_get_post_data();
		$adjustments_data = ! empty( $post_data['wishful_ads']['adjustments'] ) ? $post_data['wishful_ads']['adjustments'] : array();

		$box_width  = ! empty( $adjustments_data['box_width'] ) ? $adjustments_data['box_width'] : '';
		$box_height = ! empty( $adjustments_data['box_height'] ) ? $adjustments_data['box_height'] : '';

		$box_margin_top    = ! empty( $adjustments_data['box_margin']['top'] ) ? $adjustments_data['box_margin']['top'] : '';
		$box_margin_right  = ! empty( $adjustments_data['box_margin']['right'] ) ? $adjustments_data['box_margin']['right'] : '';
		$box_margin_bottom = ! empty( $adjustments_data['box_margin']['bottom'] ) ? $adjustments_data['box_margin']['bottom'] : '';
		$box_margin_left   = ! empty( $adjustments_data['box_margin']['left'] ) ? $adjustments_data['box_margin']['left'] : '';

		$box_padding_top    = ! empty( $adjustments_data['box_padding']['top'] ) ? $adjustments_data['box_padding']['top'] : '';
		$box_padding_right  = ! empty( $adjustments_data['box_padding']['right'] ) ? $adjustments_data['box_padding']['right'] : '';
		$box_padding_bottom = ! empty( $adjustments_data['box_padding']['bottom'] ) ? $adjustments_data['box_padding']['bottom'] : '';
		$box_padding_left   = ! empty( $adjustments_data['box_padding']['left'] ) ? $adjustments_data['box_padding']['left'] : '';

		?>
		<div class="container">
			<p class="howto"><?php esc_html_e( 'You can do some minor appearance adjustments to your ad box.', 'wishful-ad-manager' ); ?></p>

			<div class="field-wrap">
				<label>
					<span class="field-label"><?php esc_html_e( 'Ad Box Width', 'wishful-ad-manager' ); ?></span>
					<input min=0 type="number" value="<?php echo esc_attr( $box_width ); ?>" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_width]' ); ?>" id="adjustment-box-width">
					<span class="description">px</span>
				</label>
			</div>

			<div class="field-wrap">
				<label>
					<span class="field-label"><?php esc_html_e( 'Ad Box Height', 'wishful-ad-manager' ); ?></span>
					<input min=0 type="number" value="<?php echo esc_attr( $box_height ); ?>" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_height]' ); ?>" id="adjustment-box-height">
					<span class="description">px</span>
				</label>
			</div>

			<div class="field-wrap">
				<label>
					<span class="field-label"><?php esc_html_e( 'Margin', 'wishful-ad-manager' ); ?></span>
					<input value="<?php echo esc_attr( $box_margin_top ); ?>" placeholder="<?php esc_attr_e( 'Top', 'wishful-ad-manager' ); ?>" type="number" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_margin][top]' ); ?>" id="adjustment-box-margin-top">
					<input value="<?php echo esc_attr( $box_margin_right ); ?>" placeholder="<?php esc_attr_e( 'Right', 'wishful-ad-manager' ); ?>" type="number" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_margin][right]' ); ?>" id="adjustment-box-margin-right">
					<input value="<?php echo esc_attr( $box_margin_bottom ); ?>" placeholder="<?php esc_attr_e( 'Bottom', 'wishful-ad-manager' ); ?>" type="number" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_margin][bottom]' ); ?>" id="adjustment-box-margin-bottom">
					<input value="<?php echo esc_attr( $box_margin_left ); ?>" placeholder="<?php esc_attr_e( 'Left', 'wishful-ad-manager' ); ?>" type="number" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_margin][left]' ); ?>" id="adjustment-box-margin-left">
					<span class="description">px</span>
				</label>
			</div>

			<div class="field-wrap">
				<label>
					<span class="field-label"><?php esc_html_e( 'Padding', 'wishful-ad-manager' ); ?></span>
					<input value="<?php echo esc_attr( $box_padding_top ); ?>" placeholder="<?php esc_attr_e( 'Top', 'wishful-ad-manager' ); ?>" type="number" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_padding][top]' ); ?>" id="adjustment-box-padding-top">
					<input value="<?php echo esc_attr( $box_padding_right ); ?>" placeholder="<?php esc_attr_e( 'Right', 'wishful-ad-manager' ); ?>" type="number" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_padding][right]' ); ?>" id="adjustment-box-padding-right">
					<input value="<?php echo esc_attr( $box_padding_bottom ); ?>" placeholder="<?php esc_attr_e( 'Bottom', 'wishful-ad-manager' ); ?>" type="number" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_padding][bottom]' ); ?>" id="adjustment-box-padding-bottom">
					<input value="<?php echo esc_attr( $box_padding_left ); ?>" placeholder="<?php esc_attr_e( 'Left', 'wishful-ad-manager' ); ?>" type="number" name="<?php wishful_ad_manager_generate_field_name( 'adjustments[box_padding][left]' ); ?>" id="adjustment-box-padding-left">
					<span class="description">px</span>
				</label>
			</div>

		</div>
		<?php
	}
}
