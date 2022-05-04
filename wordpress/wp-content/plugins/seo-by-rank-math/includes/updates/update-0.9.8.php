<?php
/**
 * The Updates routine for version 0.9.8.
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMath\Updates
 * @author     Rank Math <support@rankmath.com>
 */

use MyThemeShop\Helpers\DB;
use RankMath\Redirections\DB as Redirections_DB;

defined( 'ABSPATH' ) || exit;

/**
 * Create and update table schema
 *
 * @since 1.0.0
 */
function rank_math_0_9_8_update_tables() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$max_index_length = 191;
	$redirections     = [];
	$charset_collate  = $wpdb->get_charset_collate();

	// Rename old tables.
	if ( DB::check_table_exists( 'rank_math_redirections' ) ) {
		$redirections = DB::query_builder( 'rank_math_redirections' )->get( ARRAY_A );
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}rank_math_redirections RENAME TO {$wpdb->prefix}rank_math_redirections_old;" ); // phpcs:ignore
	}

	// Create new tables.
	$sql = "CREATE TABLE {$wpdb->prefix}rank_math_redirections (
		id bigint(20) unsigned NOT NULL auto_increment,
		url_to text NOT NULL,
		header_code smallint(4) unsigned NOT NULL,
		times_accessed bigint(20) unsigned NOT NULL default '0',
		last_accessed datetime NOT NULL default '0000-00-00 00:00:00',
		last_edit datetime NOT NULL default '0000-00-00 00:00:00',
		redirection_status varchar(20) NOT NULL default 'active',
		redirection_condition varchar(32) NOT NULL default 'none',
		author bigint(20) unsigned NOT NULL,
		linked_object varchar(16) NOT NULL default '',
		PRIMARY KEY  (id),
		KEY (redirection_status),
		KEY (redirection_condition)
	) $charset_collate;";
	dbDelta( $sql );

	$sql = "CREATE TABLE {$wpdb->prefix}rank_math_redirection_sources (
		id bigint(20) unsigned NOT NULL auto_increment,
		redirection_id bigint(20) unsigned NOT NULL,
		pattern varchar(255) NOT NULL,
		comparison varchar(32) NOT NULL,
		PRIMARY KEY  (id),
		KEY pattern (pattern($max_index_length))
	) $charset_collate;";

	dbDelta( $sql );

	if ( empty( $redirections ) ) {
		return;
	}

	foreach ( $redirections as $redirection ) {
		$sources                 = [];
		$redirection['url_from'] = maybe_unserialize( $redirection['url_from'] );
		foreach ( $redirection['url_from'] as $url_from ) {
			$sources[] = [
				'pattern'    => $url_from['url'],
				'comparison' => $url_from['comparison'],
			];
		}

		$status = 'active';
		$value  = intval( $redirection['is_active'] );
		if ( -1 === $value ) {
			$status = 'trashed';
		} elseif ( 0 === $value ) {
			$status = 'inactive';
		}

		$data = [
			'url_to'                => $redirection['url_to'],
			'header_code'           => $redirection['header_code'],
			'times_accessed'        => '0',
			'last_accessed'         => $redirection['last_accessed'],
			'last_edit'             => current_time( 'mysql' ),
			'redirection_status'    => $status,
			'redirection_condition' => 'none',
			'author'                => 0,
			'linked_object'         => '',
			'sources'               => $sources,
		];

		Redirections_DB::add( $data );
	}
}

rank_math_0_9_8_update_tables();
