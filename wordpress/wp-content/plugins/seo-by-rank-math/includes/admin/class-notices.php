<?php
/**
 * The admin notices.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin;

use RankMath\Runner;
use RankMath\Helper;
use RankMath\Traits\Ajax;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

/**
 * Notices class.
 */
class Notices implements Runner {

	use Hooker, Ajax;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'admin_init', 'notices' );
		$this->action( 'wp_helpers_notification_dismissed', 'notice_dismissible' );
	}

	/**
	 * Run all notices routine
	 */
	public function notices() {
		$this->is_plugin_configured();
		$this->new_post_type();
	}

	/**
	 * Set known post type after notice dismissal.
	 *
	 * @param string $notification_id Notification id.
	 */
	public function notice_dismissible( $notification_id ) {
		if ( 'new_post_type' !== $notification_id ) {
			return;
		}

		$current = get_post_types( [ 'public' => true ] );
		update_option( 'rank_math_known_post_types', $current );

		if ( Helper::is_module_active( 'sitemap' ) ) {
			\RankMath\Sitemap\Cache::invalidate_storage();
		}
	}

	/**
	 * If plugin configuration not done
	 */
	private function is_plugin_configured() {
		if ( 'mts-install-plugins' === Param::get( 'page' ) ) {
			return;
		}

		if ( rank_math()->notification->get_notification_by_id( 'plugin_not_setup' ) && ! Helper::is_configured() ) {
			$message = sprintf(
				'<b>Warning!</b> You didn\'t set up your Rank Math SEO plugin yet, which means you\'re missing out on essential settings and tweaks! <a href="%s">Complete your setup by clicking here.</a>',
				Helper::get_admin_url( 'wizard' )
			);
			Helper::add_notification(
				$message,
				[
					'type' => 'warning',
					'id'   => 'plugin_not_setup',
				]
			);
		}
	}

	/**
	 * If any new post type detected
	 */
	private function new_post_type() {
		$known   = get_option( 'rank_math_known_post_types', [] );
		$current = Helper::get_accessible_post_types();
		$new     = array_diff( $current, $known );

		if ( empty( $new ) ) {
			return;
		}

		$list = implode( ', ', $new );
		/* translators: post names */
		$message = $this->do_filter( 'admin/notice/new_post_type', __( 'We detected new post type(s) (%1$s), and you would want to check the settings of <a href="%2$s">Titles &amp; Meta page</a>.', 'rank-math' ) );
		$message = sprintf( wp_kses_post( $message ), $list, Helper::get_admin_url( 'options-titles#setting-panel-post-type-' . key( $new ) ), Helper::get_admin_url( 'options-sitemap#setting-panel-sitemap-post-type-' . key( $new ) ) );
		Helper::add_notification(
			$message,
			[
				'type' => 'info',
				'id'   => 'new_post_type',
			]
		);
	}
}
