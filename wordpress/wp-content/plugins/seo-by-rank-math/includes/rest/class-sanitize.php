<?php
/**
 * The Global functionality of the plugin.
 *
 * Defines the functionality loaded on admin.
 *
 * @since      1.0.15
 * @package    RankMath
 * @subpackage RankMath\Rest
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Rest;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Sanitize {

	/**
	 * Main instance
	 *
	 * Ensure only one instance is loaded or can be loaded.
	 *
	 * @return Sanitize
	 */
	public static function get() {
		static $instance;

		if ( is_null( $instance ) && ! ( $instance instanceof Sanitize ) ) {
			$instance = new Sanitize;
		}

		return $instance;
	}

	/**
	 * Sanitize value
	 *
	 * @param string $field_id Field id to sanitize.
	 * @param mixed  $value    Field value.
	 *
	 * @return mixed  Sanitized value.
	 */
	public function sanitize( $field_id, $value ) {
		$sanitized_value = '';
		switch ( $field_id ) {
			default:
				$sanitized_value = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( $value );
		}

		return $sanitized_value;
	}
}
