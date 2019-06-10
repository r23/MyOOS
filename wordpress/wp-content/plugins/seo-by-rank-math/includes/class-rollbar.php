<?php
/**
 * The Rollbar loader.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

use MyThemeShop\Helpers\Str;

defined( 'ABSPATH' ) || exit;

/**
 * Rollbar class.
 */
class Rollbar {

	/**
	 * Load rollbar
	 */
	public function __construct() {
		$settings = get_option( 'rank-math-options-general' );
		if ( defined( 'WC_DOING_PHPUNIT' ) || ! isset( $settings['usage_tracking'] ) || 'off' === $settings['usage_tracking'] ) {
			return;
		}

		\Rollbar\Rollbar::init([
			'access_token' => '020f63d75296413da4ea438e6eed0d04',
			'environment'  => 'development',
			'code_version' => RANK_MATH_VERSION,
			'check_ignore' => [ $this, 'filter_rollbar_items' ],
		]);
	}

	/**
	 * Check what to send to rollbar
	 *
	 * @param  boolean          $uncaught Set to true if the error was an uncaught exception.
	 * @param  RollbarException $log      RollbarException instance that will allow you to get the message or exception; or a string if you're logging a simple message.
	 * @param  array            $payload  Array of payload.
	 * @return boolean
	 */
	public function filter_rollbar_items( $uncaught, $log, $payload ) {
		$payload = $payload->serialize();
		foreach ( [ 'data', 'body', 'trace', 'frames' ] as $key ) {
			if ( is_null( $payload ) ) {
				return false;
			}

			$payload = isset( $payload[ $key ] ) ? $payload[ $key ] : null;
		}

		return $this->filter_rollbar_frames( $payload );
	}

	/**
	 * Filter rollbar frames.
	 *
	 * @param  array $frames Frames to filter.
	 * @return bool
	 */
	private function filter_rollbar_frames( $frames ) {
		foreach ( $frames as $frame ) {
			if ( isset( $frame['filename'] ) && Str::contains( 'rank-math', $frame['filename'] ) ) {
				return false;
			}
		}

		return true;
	}
}
