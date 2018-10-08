<?php

namespace wp_gdpr\lib;

final class Gdpr_Options_Helper {
	public static function add_option( $name, $value ) {
		update_option( $name, $value );
	}

	public static function switch_option( $name ) {
		$value = get_option( $name, null );
		if ( empty( $value ) || 0 === $value ) {
			update_option( $name, 1 );
		} else {
			update_option( $name, 0 );
		}
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 * Checks if function is switched on.
	 */
	public static function is_option_on( $name ) {
		$value = get_option( $name, null );
		if ( empty( $value ) || 0 == $value ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 * Checks if function is switched off.
	 */
	public static function is_option_off( $name ) {
		$value = get_option( $name, null );
		if ( empty( $value ) || 0 == $value ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *
	 * @return bool
	 * Get PDO email address if is not set than use website administrator email.
	 */
	public static function get_dpo_email() {
		$value       = get_option( 'dpo_email', null );
		if ( empty( $value ) ) {
			return  get_option( 'admin_email', true );
		} else {
			return $value;
		}
	}
}
