<?php
/**
 * The User Class
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

use WP_User;

defined( 'ABSPATH' ) || exit;

/**
 * User class.
 */
class User extends Metadata {

	/**
	 * Type of object metadata is for
	 *
	 * @var string
	 */
	protected $meta_type = 'user';

	/**
	 * Retrieve User instance.
	 *
	 * @param WP_User|object|int $user User to get either (int) user id or (WP_User|object) user.
	 * @return User|false User object, false otherwise.
	 */
	public static function get( $user = 0 ) {
		if ( is_int( $user ) && 0 === absint( $user ) ) {
			$user = $GLOBALS['wp_query']->get_queried_object();
		}
		if ( is_object( $user ) && isset( $user->ID ) ) {
			$user = $user->ID;
		}
		if ( empty( $user ) ) {
			return null;
		}

		if ( isset( self::$objects[ $user ] ) ) {
			return self::$objects[ $user ];
		}

		$_user                  = new self( get_user_by( 'id', $user ) );
		$_user->object_id       = $user;
		self::$objects[ $user ] = $_user;

		return $_user;
	}

	/**
	 * Get user meta value.
	 *
	 * @param string $key  Internal key of the value to get (without prefix).
	 * @param mixed  $user User to get the meta value for either (string) term name, (int) term id or (object) term.
	 * @return mixed
	 */
	public static function get_meta( $key, $user = 0 ) {
		$user = self::get( $user );

		if ( is_null( $user ) || ! $user->is_found() ) {
			return '';
		}

		return $user->get_metadata( $key );
	}
}
