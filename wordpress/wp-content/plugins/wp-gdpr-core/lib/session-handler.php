<?php

namespace wp_gdpr\lib;

/**
 * Class Session_Handler
 * Helps to handle session.
 */
class Session_Handler {

	/**
	 * Session_Handler constructor.
	 */
	public function __construct() {
	}

	/**
	 * This function is used on start of plugin in wp-gdpr-core.php.
	 */
	public static function start_session() {
		if ( session_id() == "" ) {
			session_start();
		}
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public static function save_in_session( $key, $value ) {
		$_SESSION[ $key ] = $value;
	}

	/**
	 * @param $key
	 * @param $default
	 *
	 * @return mixed
	 */
	public static function get_from_session_by_key( $key, $default ) {
		if ( isset( $_SESSION[ $key ] ) ) {
			return $_SESSION[ $key ];
		} else {
			$default;
		}
	}

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	public static function compare_with_saved_in_session( $key, $value ) {
		if ( isset( $_SESSION[ $key ] ) ) {
			return $_SESSION[ $key ] === $value;
		} else {
			false;
		}
	}

	/**
	 * @param $key
	 */
	public static function delete_in_session_by_key( $key ) {

	}

	/**
	 * @param $value
	 */
	public static function delete_in_session_by_value( $value ) {

	}
}
