<?php
/**
 * This file has all the required helpers functions and definitions.
 *
 * @package wishful-ad-manager
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Recursive sanitation for an array/
 *
 * Here we are using sanitize_text_field function wrapped with our custom function.
 * This custom function iterates the sanitize_text_field function to every string element of provided array
 * and returns the array with sanitized string elements.
 *
 * Credit: @link https://wordpress.stackexchange.com/a/255238
 *
 * @param array $array submitted $_POST data array.
 * @return mixed
 */
function wishful_ad_manager_data_sanitize( $array ) {
	foreach ( $array as $key => &$value ) {
		if ( is_array( $value ) ) {
			$value = wishful_ad_manager_data_sanitize( $value );
		} else {
			$value = sanitize_text_field( wp_unslash( $value ) );
		}
	}

	return $array;
}



if ( ! function_exists( 'wishful_ad_manager_generate_field_name' ) ) {

	/**
	 * Generates the key for the fields name attribute.
	 *
	 * @param string $name Name for the current field.
	 * @param bool   $echo Whether to echo or return the result.
	 */
	function wishful_ad_manager_generate_field_name( $name, $echo = true ) {
		$post_type = get_post_type();
		$post_type = str_replace( array( '-', ' ' ), '_', $post_type );

		$name = str_replace( ']', '', $name );
		$name = explode( '[', $name );
		$name = implode( '][', $name );

		$field_name = "wishful_ad_manager[{$post_type}][{$name}]";

		if ( ! $post_type ) {
			$field_name = "wishful_ad_manager[{$name}]";
		}

		if ( $echo ) {
			echo esc_attr( $field_name );
		}

		return $field_name;
	}
}


if ( ! function_exists( 'wishful_ad_manager_get_post_data' ) ) {

	/**
	 * Returns the saved post meta value.
	 *
	 * @param int $ad_id Advertiesment ID.
	 */
	function wishful_ad_manager_get_post_data( $ad_id = '' ) {

		if ( empty( $ad_id ) ) {
			$ad_id = get_the_ID();
		}

		return get_post_meta( $ad_id, 'wishful_ad_manager', true );

	}
}


if ( ! function_exists( 'wishful_ad_manager_get_ad_content' ) ) {

	/**
	 * Returns the custom script if "Use custom script" is checked else
	 * returns the ad banner with img tag.
	 *
	 * @param int $ad_id Advertiesment ID.
	 */
	function wishful_ad_manager_get_ad_content( $ad_id ) {

		$content = '';
		if ( ! $ad_id ) {
			return $content;
		}
		$post_data = wishful_ad_manager_get_post_data( $ad_id );

		// Ad Contents.
		$ad_contents_data  = ! empty( $post_data['wishful_ads']['ad_contents'] ) ? $post_data['wishful_ads']['ad_contents'] : array();
		$custom_script     = ! empty( $ad_contents_data['custom_script'] ) ? $ad_contents_data['custom_script'] : '';
		$use_custom_script = isset( $ad_contents_data['use_custom_script'] ) ? $ad_contents_data['use_custom_script'] : '';

		// Ad Links.
		$ad_links     = ! empty( $post_data['wishful_ads']['ad_links'] ) ? $post_data['wishful_ads']['ad_links'] : array();
		$link         = ! empty( $ad_links['link'] ) ? $ad_links['link'] : '';
		$open_new_tab = isset( $ad_links['open_new_tab'] ) && 'yes' === $ad_links['open_new_tab'] ? '_blank' : '_self';

		if ( 'yes' === $use_custom_script ) {
			$content = ! empty( $custom_script ) ? $custom_script : '';
		} else {
			$ad_banner_url = get_the_post_thumbnail_url( $ad_id, 'full' );
			$content       = $ad_banner_url ? sprintf( '<img src="%s" >', esc_url( $ad_banner_url ) ) : '';
		}

		$output = $content && $link ? sprintf( '<a href="%1$s" target="%2$s">%3$s</a>', $link, $open_new_tab, $content ) : $content;

		return "<div class='wishful-ad-manager-{$ad_id} wishful-ad-manager-content' data-id='{$ad_id}'>{$output}</div>";
	}
}


if ( ! function_exists( 'wishful_ad_manager_print_ad' ) ) {

	/**
	 * Prints the ad.
	 *
	 * @param int    $ad_id Advertiesment ID.
	 * @param string $before Html before ad content.
	 * @param string $after Html after ad content.
	 */
	function wishful_ad_manager_print_ad( $ad_id, $before = '', $after = '' ) {
		$ad_content = wishful_ad_manager_get_ad_content( $ad_id );
		echo $ad_content ? wp_kses_post( $before . $ad_content . $after ) : '';
	}
}


if ( ! function_exists( 'wishful_ad_manager_switch_control' ) ) {

	/**
	 * Creates switch button.
	 */
	function wishful_ad_manager_switch_control( $name, $value = 'no', $id = null ) {
		?>
		<label class="wishful-ad-manager-switch-control">
			<input <?php checked( $value, 'yes' ); ?> id="<?php echo esc_attr( $id ); ?>" type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="yes">
			<span class="slider"></span>
		</label>
		<?php
	}
}
