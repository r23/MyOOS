<?php
/**
 * REST api helper.
 *
 * @since      1.0.15
 * @package    RankMath
 * @subpackage RankMath\Rest
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Rest;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class.
 */
class Helper {

	/**
	 * REST namespace.
	 *
	 * @var string
	 */
	const BASE = 'rankmath/v1';

	/**
	 * Determines if the current user can manage options.
	 *
	 * @return true
	 */
	public static function can_manage_options() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Param emptiness validate callback.
	 *
	 * @param mixed $param Param to validate.
	 *
	 * @return boolean
	 */
	public static function is_param_empty( $param ) {
		if ( empty( $param ) ) {
			return new WP_Error(
				'param_value_empty',
				esc_html__( 'Sorry, field is empty which is not allowed.', 'rank-math' )
			);
		}
		return true;
	}
}
