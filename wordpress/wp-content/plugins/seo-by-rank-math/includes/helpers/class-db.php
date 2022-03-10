<?php
/**
 * DB helpers.
 *
 * @since      1.0.9
 * @package    RankMath
 * @subpackage RankMath\Helpers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * DB class.
 */
trait DB {

	/**
	 * Check and fix collation of table and columns.
	 *
	 * @param string $table         Table name (without prefix).
	 * @param array  $columns       Columns.
	 * @param string $set_collation Collation.
	 */
	public static function check_collation( $table, $columns = 'all', $set_collation = null ) {
		global $wpdb;

		$prefixed = $wpdb->prefix . $table;

		$sql = "SHOW TABLES LIKE '{$wpdb->prefix}%'";
		$res = $wpdb->get_col( $sql ); // phpcs:ignore
		if ( ! in_array( $prefixed, $res, true ) ) {
			return;
		}

		// Collation to set.
		$collate = $set_collation ? $set_collation : self::get_default_collation();

		$sql = "SHOW CREATE TABLE `{$prefixed}`";
		$res = $wpdb->get_row( $sql ); // phpcs:ignore

		$table_collate = $res->{'Create Table'};

		// Determine current collation value.
		$current_collate = '';
		if ( preg_match( '/COLLATE=([a-zA-Z0-9_-]+)/', $table_collate, $matches ) ) {
			$current_collate = $matches[1];
		}

		// If collation is not set or is incorrect, fix it.
		if ( ! $current_collate || $current_collate !== $collate ) {
			$sql = "ALTER TABLE `{$prefixed}` COLLATE={$collate}";
			$wpdb->query( $sql ); // phpcs:ignore
		}

		// Now handle columns if needed.
		if ( ! $columns ) {
			return;
		}

		$sql = "SHOW FULL COLUMNS FROM {$prefixed}";
		$res = $wpdb->get_results( $sql, ARRAY_A ); // phpcs:ignore
		if ( ! $res ) {
			return;
		}

		$columns = 'all' === $columns ? wp_list_pluck( $res, 'Field' ) : $columns;

		foreach ( $res as $col ) {
			if ( ! in_array( $col['Field'], $columns, true ) ) {
				continue;
			}

			$current_collate = $col['Collation'];
			if ( ! $current_collate || $current_collate === $collate ) {
				continue;
			}

			$null    = 'NO' === $col['Null'] ? 'NOT NULL' : 'NULL';
			$default = ! empty( $col['Default'] ) ? "DEFAULT '{$col['Default']}'" : '';

			$sql = "ALTER TABLE `{$prefixed}` MODIFY `{$col['Field']}` {$col['Type']} COLLATE {$collate} {$null} {$default}";
			error_log( $sql );
			$wpdb->query( $sql ); // phpcs:ignore
		}
	}

	/**
	 * Get default collation.
	 *
	 * @return string
	 */
	public static function get_default_collation() {
		if ( defined( 'DB_COLLATE' ) && DB_COLLATE ) {
			return DB_COLLATE;
		}

		global $wpdb;

		$collate = 'utf8mb4_unicode_ci';

		// Get default collation by looking at the posts table.
		$sql = 'SHOW CREATE TABLE ' . $wpdb->posts;
		$row = $wpdb->get_row( $sql ); // phpcs:ignore
		if ( ! $row ) {
			return $collate;
		}

		if ( ! preg_match( '/COLLATE=([a-zA-Z0-9_-]+)/', $row->{'Create Table'}, $matches ) ) {
			return $collate;
		}

		return $matches[1];
	}

}
