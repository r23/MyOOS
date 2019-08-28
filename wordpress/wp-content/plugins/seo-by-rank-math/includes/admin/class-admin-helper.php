<?php
/**
 * Admin helper Functions.
 *
 * This file contains functions needed on the admin screens.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin;

use RankMath\Helper;
use MyThemeShop\Helpers\Param;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Admin_Helper class.
 */
class Admin_Helper {

	/**
	 * Get .htaccess related data.
	 *
	 * @return array
	 */
	public static function get_htaccess_data() {
		$wp_filesystem = WordPress::get_filesystem();
		$htaccess_file = get_home_path() . '.htaccess';

		return ! $wp_filesystem->exists( $htaccess_file ) ? false : [
			'content'  => $wp_filesystem->get_contents( $htaccess_file ),
			'writable' => $wp_filesystem->is_writable( $htaccess_file ),
		];
	}

	/**
	 * Get tooltip HTML.
	 *
	 * @param  string $message Message to show in tooltip.
	 * @return string
	 */
	public static function get_tooltip( $message ) {
		return '<span class="rank-math-tooltip"><em class="dashicons-before dashicons-editor-help"></em><span>' . $message . '</span></span>';
	}

	/**
	 * Get admin view file.
	 *
	 * @param  string $view View filename.
	 * @return string Complete path to view
	 */
	public static function get_view( $view ) {
		return rank_math()->admin_dir() . "views/{$view}.php";
	}

	/**
	 * Get taxonomies as choices.
	 *
	 * @param array $args (Optional) Arguments passed to filter list.
	 * @return array|bool
	 */
	public static function get_taxonomies_options( $args = [] ) {
		global $wp_taxonomies;

		$args       = wp_parse_args( $args, [ 'public' => true ] );
		$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, 'and', 'label' );

		return empty( $taxonomies ) ? false : [ 'off' => esc_html__( 'None', 'rank-math' ) ] + $taxonomies;
	}

	/**
	 * Registration data get/update.
	 *
	 * @param array|bool|null $data Array of data to save.
	 * @return array
	 */
	public static function get_registration_data( $data = null ) {
		$key = 'rank_math_connect_data';

		// Setter.
		if ( ! is_null( $data ) ) {
			if ( false === $data ) {
				update_option( 'rank_math_registration_skip', 1 );
				return delete_option( $key );
			}

			return update_option( $key, $data );
		}

		// Getter.
		$options = Helper::is_plugin_active_for_network() ? get_blog_option( get_main_site_id(), $key, false ) : get_option( $key, false );
		return empty( $options ) ? false : $options;
	}

	/**
	 * Register product routine.
	 *
	 * @param  string $username Username for registration.
	 * @param  string $password Password for registration.
	 * @return bool
	 */
	public static function register_product( $username, $password ) {
		$error = false;

		if ( empty( $username ) ) {
			$error = true;
			Helper::add_notification( esc_html__( 'Username is not entered.', 'rank-math' ), [ 'type' => 'error' ] );
		}

		if ( empty( $password ) ) {
			$error = true;
			Helper::add_notification( esc_html__( 'Password is not entered.', 'rank-math' ), [ 'type' => 'error' ] );
		}

		if ( $error ) {
			return false;
		}

		$body = self::authenticate_user( $username, $password );

		if ( false !== $body && isset( $body['token'] ) ) {
			self::get_registration_data([
				'username'  => $body['login'],
				'api_key'   => $body['token'],
				'connected' => true,
			]);
			Helper::add_notification( esc_html__( 'Thank you for connecting your Rank Math account.', 'rank-math' ), 'success' );

			return true;
		}

		return false;
	}

	/**
	 * Authenticate user on RankMath.com.
	 *
	 * @param  string $username Username for registration.
	 * @param  string $password Password for registration.
	 * @return bool
	 */
	private static function authenticate_user( $username, $password ) {
		$response = wp_remote_post( 'https://rankmath.com/wp-json/rankmath/v1/token', [
			'timeout'    => 10,
			'user-agent' => 'RankMath/' . md5( esc_url( home_url( '/' ) ) ),
			'body'       => [
				'username' => $username,
				'password' => $password,
				'site_url' => esc_url( site_url() ),
			],
		]);

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

		if ( is_wp_error( $response ) || isset( $body['code'] ) ) {
			$message = is_wp_error( $response ) ? $response->get_error_message() : $body['message'];

			foreach ( (array) $message as $e ) {
				Helper::add_notification( $e, [ 'type' => 'error' ] );
			}

			return false;
		}

		return $body;
	}

	/**
	 * Change tracking status.
	 */
	public static function allow_tracking() {
		$settings                   = get_option( 'rank-math-options-general' );
		$settings['usage_tracking'] = Param::post( 'rank-math-usage-tracking', false, FILTER_VALIDATE_BOOLEAN ) ? 'on' : 'off';

		update_option( 'rank-math-options-general', $settings );
	}

	/**
	 * Compare values.
	 *
	 * @param  integer $value1     Old value.
	 * @param  integer $value2     New Value.
	 * @param  bool    $percentage Treat as percentage.
	 * @return float
	 */
	public static function compare_values( $value1, $value2, $percentage = false ) {
		$diff = round( ( $value2 - $value1 ), 2 );

		if ( ! $percentage ) {
			return (float) $diff;
		}

		if ( $value1 ) {
			$diff = round( ( ( $diff / $value1 ) * 100 ), 2 );
			if ( ! $value2 ) {
				$diff = -100;
			}
		} elseif ( $value2 ) {
			$diff = 100;
		}

		return (float) $diff;
	}

	/**
	 * Check if current page is post create/edit screen.
	 *
	 * @return bool
	 */
	public static function is_post_edit() {
		global $pagenow;

		return in_array( $pagenow, [ 'post.php', 'post-new.php' ], true );
	}

	/**
	 * Check if current page is term create/edit screen.
	 *
	 * @return bool
	 */
	public static function is_term_edit() {
		global $pagenow;
		return 'term.php' === $pagenow;
	}

	/**
	 * Check if current page is user create/edit screen.
	 *
	 * @return bool
	 */
	public static function is_user_edit() {
		global $pagenow;

		return in_array( $pagenow, [ 'profile.php', 'user-edit.php' ], true );
	}

	/**
	 * Check if current page is user or term create/edit screen.
	 *
	 * @return bool
	 */
	public static function is_term_profile_page() {
		global $pagenow;

		return in_array( $pagenow, [ 'term.php', 'profile.php', 'user-edit.php' ], true );
	}

	/**
	 * Get Social Share buttons.
	 *
	 * @codeCoverageIgnore
	 */
	public static function get_social_share() {
		if ( Helper::is_whitelabel() ) {
			return;
		}

		$tw_link = 'https://s.rankmath.com/twitter';
		$fb_link = urlencode( 'https://s.rankmath.com/suite-free' );
		/* translators: sitename */
		$tw_message = urlencode( sprintf( esc_html__( 'I just installed @RankMathSEO #WordPress Plugin. It looks great! %s', 'rank-math' ), $tw_link ) );
		/* translators: sitename */
		$fb_message = urlencode( esc_html__( 'I just installed Rank Math SEO WordPress Plugin. It looks promising!', 'rank-math' ) );

		$tweet_url = add_query_arg([
			'text'     => $tw_message,
			'hashtags' => 'SEO',
		], 'https://twitter.com/intent/tweet' );

		$fb_share_url = add_query_arg([
			'u'       => $fb_link,
			'quote'   => $fb_message,
			'caption' => esc_html__( 'SEO by Rank Math', 'rank-math' ),
		], 'https://www.facebook.com/sharer/sharer.php' );
		?>
		<div class="wizard-share">
			<a href="#" onclick="window.open('<?php echo $tweet_url; ?>', 'sharewindow', 'resizable,width=600,height=300'); return false;" class="share-twitter">
				<span class="dashicons dashicons-twitter"></span> <?php esc_html_e( 'Tweet', 'rank-math' ); ?>
			</a>
			<a href="#" onclick="window.open('<?php echo $fb_share_url; ?>', 'sharewindow', 'resizable,width=600,height=300'); return false;" class="share-facebook">
				<span class="dashicons dashicons-facebook-alt"></span> <?php esc_html_e( 'Share', 'rank-math' ); ?>
			</a>
		</div>
		<?php
	}
}
