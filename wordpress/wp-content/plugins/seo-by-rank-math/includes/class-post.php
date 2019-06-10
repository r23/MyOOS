<?php
/**
 * The Post Class
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

use WP_Post;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * Post class.
 */
class Post extends Metadata {

	/**
	 * Type of object metadata is for
	 *
	 * @var string
	 */
	protected $meta_type = 'post';

	/**
	 * Retrieve Post instance.
	 *
	 * @param  WP_Post|object|int $post Post to get either (int) post id or (WP_Post|object) post.
	 * @return Post|false Post object, false otherwise.
	 */
	public static function get( $post = 0 ) {
		$post = self::get_post_id( $post );
		if ( false === $post ) {
			return null;
		}

		if ( isset( self::$objects[ $post ] ) ) {
			return self::$objects[ $post ];
		}

		$_post                  = new self( WP_Post::get_instance( $post ) );
		$_post->object_id       = $post;
		self::$objects[ $post ] = $_post;

		return $_post;
	}

	/**
	 * Get post id
	 *
	 * @param  integer $post Post ID.
	 * @return integer
	 */
	private static function get_post_id( $post = 0 ) {
		if ( is_object( $post ) && isset( $post->ID ) ) {
			return $post->ID;
		}

		$post = absint( $post );
		if ( $post > 0 ) {
			return $post;
		}

		if ( 0 === $post ) {
			$post = get_post();
		}

		return ! is_null( $post ) ? $post->ID : false;
	}

	/**
	 * Get post meta value.
	 *
	 * @param  string  $key     Internal key of the value to get (without prefix).
	 * @param  integer $post_id Post ID of the post to get the value for.
	 * @return mixed
	 */
	public static function get_meta( $key, $post_id = 0 ) {
		$post = self::get( $post_id );

		if ( is_null( $post ) || ! $post->is_found() || 'auto-draft' === $post->post_status ) {
			return '';
		}

		return $post->get_metadata( $key );
	}

	/**
	 * Returns the id of the currently opened page.
	 *
	 * @return int The id of the currently opened page.
	 */
	public static function get_simple_page_id() {
		/**
		 * Filter: Allow changing the default page id. Short-circuit if 3rd party set page id.
		 *
		 * @param unsigned int $page_id The default page id.
		 */
		$page_id = apply_filters( 'rank_math/pre_simple_page_id', false );
		if ( false !== $page_id ) {
			return $page_id;
		}

		if ( is_singular() ) {
			return get_the_ID();
		}

		if ( self::is_posts_page() ) {
			return get_option( 'page_for_posts' );
		}

		if ( self::is_shop_page() ) {
			return self::get_shop_page_id();
		}

		/**
		 * Filter: Allow changing the default page id.
		 *
		 * @param unsigned int $page_id The default page id.
		 */
		return apply_filters( 'rank_math/simple_page_id', 0 );
	}

	/**
	 * Returns the id of the set WooCommerce shop page.
	 *
	 * @return int The ID of the set page.
	 */
	public static function get_shop_page_id() {
		static $shop_page_id;
		if ( ! $shop_page_id ) {
			$shop_page_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : ( -1 );
		}

		return $shop_page_id;
	}

	/**
	 * Checks if the currently opened page is a simple page.
	 *
	 * @return bool Whether the currently opened page is a simple page.
	 */
	public static function is_simple_page() {
		return self::get_simple_page_id() > 0;
	}

	/**
	 * Checks if the current page is the shop page.
	 *
	 * @return bool Whether the current page is the WooCommerce shop page.
	 */
	public static function is_shop_page() {
		if ( function_exists( 'is_shop' ) && function_exists( 'wc_get_page_id' ) ) {
			return is_shop() && ! is_search();
		}

		return false;
	}

	/**
	 * Checks if the current page is one of the woocommerce page.
	 *
	 * @return bool Whether the current page is the WooCommerce Cart/Account/Checkout page.
	 */
	public static function is_woocommerce_page() {
		if ( Conditional::is_woocommerce_active() ) {
			return is_cart() || is_checkout() || is_account_page();
		}

		return false;
	}

	/**
	 * Determine whether this is the homepage and shows posts.
	 *
	 * @return bool
	 */
	public static function is_home_posts_page() {
		return ( is_home() && 'posts' === get_option( 'show_on_front' ) );
	}

	/**
	 * Determine whether the this is the static frontpage.
	 *
	 * @return bool
	 */
	public static function is_home_static_page() {
		return ( is_front_page() && 'page' === get_option( 'show_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
	}

	/**
	 * Determine whether this is the posts page, when it's not the frontpage.
	 *
	 * @return bool
	 */
	public static function is_posts_page() {
		return ( is_home() && 'page' === get_option( 'show_on_front' ) );
	}
}
