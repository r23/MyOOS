<?php
/**
 * Prevent Lazy Loading Primary Featured Image plugin bootstrap.
 *
 * @package   Google\PreventLazyLoadingPrimaryFeaturedImage
 * @author    Weston Ruter, Google
 * @license   GPL-2.0-or-later
 * @copyright 2021 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: Prevent Lazy Loading Primary Featured Image
 * Plugin URI: https://gist.github.com/westonruter/e8d5778843005b7e0d6ce4049b3ec29d
 * Description: The featured image for the primary post on a template should probably not be lazy-loaded, just as the custom logo and header image are not (see <a href="https://core.trac.wordpress.org/ticket/50425">#50425</a>).
 * Version: 0.1
 * Author: Weston Ruter, Google
 * Author URI: https://weston.ruter.net/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Gist Plugin URI: https://gist.github.com/westonruter/e8d5778843005b7e0d6ce4049b3ec29d
 */

namespace Google\PreventLazyLoadingPrimaryFeaturedImage;

use WP_Query;

/**
 * Filters whether to add the `loading` attribute to the specified tag in the specified context.
 *
 * @param bool   $enabled  Whether lazy-loading is enabled.
 * @param string $tag_name The tag name.
 * @param string $context  Additional context, like the current filter name
 *                         or the function name from where this was called.
 * @return bool Whether enabled.
 */
function disable_lazy_loading_enabled_for_attachment_image( $enabled, $tag_name, $context ) {
	if ( $enabled && 'img' === $tag_name && 'wp_get_attachment_image' === $context ) {
		$enabled = false;
	}
	return $enabled;
}

/**
 * Determine whether the provided post is primary.
 *
 * Primary means the queried object on a singular template, or else the first post in the loop on an archive template.
 *
 * @param int $post_id Post ID.
 * @return bool Whether post is primary.
 */
function is_primary_post( $post_id ) {
	global $wp_query;
	return (
		( is_singular() && get_queried_object_id() === $post_id )
		||
		(
			$wp_query instanceof WP_Query
			&&
			$wp_query->is_main_query()
			&&
			isset( $wp_query->posts[0] )
			&&
			$wp_query->posts[0]->ID === $post_id
		)
	);
}

/**
 * Add filter to prevent lazy loading for featured image of primary post.
 *
 * @param int $post_id           Post ID.
 * @param int $post_thumbnail_id Attachment ID.
 */
function on_begin_fetch_post_thumbnail_html( $post_id, $post_thumbnail_id ) {
	if ( is_primary_post( $post_id ) && get_post_thumbnail_id( $post_id ) === $post_thumbnail_id ) {
		add_filter( 'wp_lazy_loading_enabled', __NAMESPACE__ . '\disable_lazy_loading_enabled_for_attachment_image', 10, 3 );
	}
}

if ( empty( $_GET['disable_primary_featured_image_lazy_load_prevention'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	// Prepare disabling lazy-loading.
	add_action( 'begin_fetch_post_thumbnail_html', __NAMESPACE__ . '\on_begin_fetch_post_thumbnail_html', 10, 2 );

	// Undo on_begin_fetch_post_thumbnail_html() after the featured image has been printed.
	add_action(
		'end_fetch_post_thumbnail_html',
		static function () {
			remove_filter( 'wp_lazy_loading_enabled', __NAMESPACE__ . '\disable_lazy_loading_enabled_for_attachment_image' );
		}
	);
}
