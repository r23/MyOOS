<?php
namespace wp_gdpr\lib;

class Gdpr_Customtables {

	/**
	 * name of table without prefix
	 */
	const REQUESTS_TABLE_NAME = 'gdpr_requests';
	const DELETE_REQUESTS_TABLE_NAME = 'gdpr_del_requests';
	public static function create_custom_tables()
	{
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		self::create_delete_request_table();
		self::create_request_table();
	}
	/**
	 * create custom table
	 * use dbDelta()
	 */
	public static function create_request_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . self::REQUESTS_TABLE_NAME;

		$query      = "CREATE TABLE " . $table_name . " (
			ID INT(10) NOT NULL AUTO_INCREMENT,
			email VARCHAR(60) DEFAULT NULL,
			status INT(2) DEFAULT NULL,
			timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			language VARCHAR(10) DEFAULT NULL,
			PRIMARY KEY (ID)
		)";

		dbDelta( $query );
	}

	public static function create_delete_request_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . self::DELETE_REQUESTS_TABLE_NAME;

		$query      = "CREATE TABLE " . $table_name . " (
			ID INT(10) NOT NULL AUTO_INCREMENT,
			email VARCHAR(60) DEFAULT NULL,
			data VARCHAR(800) DEFAULT NULL,
			status INT(2) DEFAULT NULL,
			r_type INT(2) DEFAULT NULL,
			timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (ID)
		)";

		dbDelta( $query );
	}
}
