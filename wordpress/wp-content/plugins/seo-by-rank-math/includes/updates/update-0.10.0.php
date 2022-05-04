<?php
/**
 * The Updates routine for version 0.10.0.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath\Updates
 * @author     Rank Math <support@rankmath.com>
 */

use MyThemeShop\Helpers\DB;
use RankMath\Redirections\Redirection;

defined( 'ABSPATH' ) || exit;

/**
 * Create and update table schema
 *
 * @since 0.10.0
 */
function rank_math_0_10_0_update_redirections() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$charset_collate = $wpdb->get_charset_collate();

	if ( DB::check_table_exists( 'rank_math_redirections' ) ) {
		$redirections         = DB::query_builder( 'rank_math_redirections' )->get( ARRAY_A );
		$redirections_sources = DB::query_builder( 'rank_math_redirection_sources' )->get( ARRAY_A );

		// Save old redirections as backup.
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}rank_math_redirections RENAME TO {$wpdb->prefix}rank_math_redirections_0_9_17;" ); // phpcs:ignore
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}rank_math_redirection_sources RENAME TO {$wpdb->prefix}rank_math_redirection_sources_0_9_17;" ); // phpcs:ignore
	}

	if ( ! DB::check_table_exists( 'rank_math_redirections' ) ) {
		$sql = "CREATE TABLE {$wpdb->prefix}rank_math_redirections (
			id bigint(20) unsigned NOT NULL auto_increment,
			sources text NOT NULL,
			url_to text NOT NULL,
			header_code smallint(4) unsigned NOT NULL,
			hits bigint(20) unsigned NOT NULL default '0',
			status varchar(25) NOT NULL default 'active',
			created datetime NOT NULL default '0000-00-00 00:00:00',
			updated datetime NOT NULL default '0000-00-00 00:00:00',
			last_accessed datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (id),
			KEY (status)
		) $charset_collate;";

		dbDelta( $sql );
	}

	if ( ! DB::check_table_exists( 'rank_math_redirections_cache' ) ) {
		$sql = "CREATE TABLE {$wpdb->prefix}rank_math_redirections_cache (
			id bigint(20) unsigned NOT NULL auto_increment,
			from_url text NOT NULL,
			redirection_id bigint(20) unsigned NOT NULL,
			object_id bigint(20) unsigned NOT NULL default '0',
			object_type varchar(10) NOT NULL default 'post',
			is_redirected tinyint(1) NOT NULL default '0',
			PRIMARY KEY  (id),
			KEY (redirection_id)
		) $charset_collate;";

		dbDelta( $sql );
	}

	if ( empty( $redirections ) ) {
		return;
	}

	// Merge redirections and redirections sources to one array.
	$old_redirections = [];
	foreach ( $redirections as $redirection ) {
		$redirection['sources']                 = [];
		$old_redirections[ $redirection['id'] ] = $redirection;
	}

	foreach ( $redirections_sources as $redirections_source ) {
		if ( ! isset( $old_redirections[ $redirections_source['redirection_id'] ] ) ) {
			continue;
		}

		$old_redirections[ $redirections_source['redirection_id'] ]['sources'][] = $redirections_source;
	}

	// Convert to the new version structure.
	foreach ( $old_redirections as $old_redirection ) {
		// Sources column.
		$new_sources = [];
		foreach ( $old_redirection['sources'] as $source ) {
			$new_sources[] = [
				'pattern'    => $source['pattern'],
				'comparison' => $source['comparison'],
			];
		}

		Redirection::from(
			[
				'sources'       => $new_sources,
				'url_to'        => $old_redirection['url_to'],
				'header_code'   => $old_redirection['header_code'],
				'hits'          => $old_redirection['times_accessed'],
				'status'        => $old_redirection['redirection_status'],
				'updated'       => $old_redirection['last_edit'],
				'last_accessed' => $old_redirection['last_accessed'],
			]
		)->save();
	}
}

/**
 * Create links module table
 *
 * @since 0.10.0
 */
function rank_math_0_10_0_create_links_table() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$charset_collate = $wpdb->get_charset_collate();
	if ( ! DB::check_table_exists( 'rank_math_internal_links' ) ) {
		$sql = "CREATE TABLE {$wpdb->prefix}rank_math_internal_links (
			id bigint(20) unsigned NOT NULL auto_increment,
			url varchar(255) NOT NULL,
			post_id bigint(20) unsigned NOT NULL,
			target_post_id bigint(20) unsigned NOT NULL,
			type varchar(8) NOT NULL,
			PRIMARY KEY  (id),
			KEY link_direction (post_id, type)
		) $charset_collate;";
		dbDelta( $sql );
	}

	if ( ! DB::check_table_exists( 'rank_math_internal_meta' ) ) {
		$sql = "CREATE TABLE {$wpdb->prefix}rank_math_internal_meta (
			object_id bigint(20) unsigned NOT NULL,
			internal_link_count int(10) unsigned NULL default 0,
			external_link_count int(10) unsigned NULL default 0,
			incoming_link_count int(10) unsigned NULL default 0,
			UNIQUE KEY object_id (object_id)
		) $charset_collate;";
		dbDelta( $sql );
	}
}

/**
 * Init Upgrade.
 */
rank_math_0_10_0_create_links_table();
rank_math_0_10_0_update_redirections();
