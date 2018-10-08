<?php

namespace wp_gdpr\model;

use wp_gdpr\lib\Gdpr_Log_Interface;

/**
 * This class is used to communicate with the data register database table.
 *
 * Data requests and deletion will be saved in that table.
 *
 * @package wp_gdpr\model
 *
 * @since 1.6.0
 */
class Data_Register_Model extends Gdpr_Log_Interface {

	/**
	 * Database table name after prefix
	 *
	 * @since 1.6.0
	 */
	CONST TABLE_NAME = 'gdpr_data_register';

	/**
	 * Instance where the log object gets saved
	 *
	 * @var null|Data_Register_Model
	 *
	 * @since 1.6.0
	 */
	private static $instance = null;

	/**
	 * @var \wpdb
	 *
	 * @since 1.6.0
	 */
	private $wpdb;

	/**
	 * Database full table name
	 *
	 * @var
	 *
	 * @since 1.6.0
	 */
	private $table_name;

	/**
	 * The data returned from database query
	 *
	 * @var
	 *
	 * @since 1.6.0
	 */
	public $data;

	/**
	 * Use Log interface to use the Gdpr Log class
	 *
	 * @since 1.6.0
	 */
	public function __construct() {
		parent::__construct();

		global $wpdb;

		$this->wpdb       = $wpdb;
		$this->table_name = $this->wpdb->prefix . self::TABLE_NAME;
	}

	/**
	 * Constructs this class once
	 *
	 * @return null|Data_Register_Model
	 *
	 * @since 1.6.0
	 */
	public static function instance() {
		// Check if instance is already exists
		if ( self::$instance == null ) {
			self::$instance = new Data_Register_Model();
		}

		return self::$instance;
	}

	/**
	 * Creates Data Register Table
	 *
	 * @since 1.6.0
	 */
	public function create_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;

		$query = 'CREATE TABLE ' . $table_name . ' (
				  id INT(11) NOT NULL AUTO_INCREMENT,
				  email VARCHAR(100) DEFAULT NULL,
				  hashed_email varchar(64) NOT NULL,
				  message VARCHAR(255) DEFAULT NULL,
				  ref varchar(30) DEFAULT NULL,
				  ref_id INT(11) DEFAULT NULL,
				  timestamp DATETIME DEFAULT NULL,
				  PRIMARY KEY (id)
				)';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $query );

		$this->log->info( 'Data Register table created' );
	}

	/**
	 * Saves data changes
	 *
	 * @param $email    string  E-mailaddress that took the action
	 * @param $message   string  Description of the action
	 *
	 * @since 1.6.0
	 */
	public function add_message( $email, $message, $ref, $ref_id ) {
		$hashed_email = $this->hash_email( $email );
		$this->insert_row( $email, $hashed_email, $message, $ref, $ref_id );
	}

	/**
	 * Returns hashed email address
	 *
	 * @param $email
	 *
	 * @return bool|string
	 *
	 * @since 1.6.0
	 */
	private function hash_email( $email ) {
		return hash( 'sha256', $email );
	}

	/**
	 * Insert row to data_register table
	 *
	 * @param $email
	 * @param $hashed_email
	 * @param $message
	 *
	 * @since 1.6.0
	 */
	private function insert_row( $email, $hashed_email, $message, $ref, $ref_id ) {
		$query = "INSERT INTO " . $this->table_name . " (email, hashed_email, message, ref, ref_id, timestamp) VALUES ";
		$query .= "('" . $email . "', '" . $hashed_email . "', '" . $message . "', '" . $ref . "', " . $ref_id . ", '" . current_time( 'mysql' ) . "')";

		$this->wpdb->query( $query );
	}

	/**
	 * Returns data by email address
	 *
	 * @param $email
	 * @param $start
	 * @param $per_page
	 *
	 * @return $this
	 *
	 * @since 1.6.0
	 */
	public function search_by_email( $email, $start = false, $per_page = false ) {
		$query = "SELECT * FROM " . $this->table_name . " WHERE hashed_email='" . $this->hash_email( $email ) . "'";

		$query .= " ORDER BY id DESC";

		if( $start !== false && $per_page !== false ) {
			$query .= " LIMIT ".$start.",".$per_page;
		}

		$this->data = $this->wpdb->get_results( $query );

		return $this;
	}

	/**
	 * Returns all data in data register table
	 *
	 * @param $start   integer|boolean  Limit start number
	 * @param $per_page integer|boolean Limit amount of rows to return
	 *
	 * @return $this
	 *
	 * @since 1.6.0
	 */
	public function get_all($start = false, $per_page = false, $order = false) {
		$query = "SELECT * FROM " . $this->table_name;

		$query .= " ORDER BY id DESC";

		if( $start !== false && $per_page !== false ) {
			$query .= " LIMIT ".$start.",".$per_page;
		}

		$this->data = $this->wpdb->get_results( $query );

		return $this;
	}

	/**
	 * Returns number of rows in the data register table
	 *
	 * @return integer  Number of rows in the database
	 *
	 * @since 1.6.0
	 */
	public function get_max_all_data() {
		$query = "SELECT count(*) FROM ".$this->table_name;

		$max = $this->wpdb->get_var( $query );

		return $max;
	}

	/**
	 * Check if the query result is valid
	 *
	 * @return bool
	 *
	 * @since 1.6.0
	 */
	public function data_is_valid() {
		return ( is_array( $this->data ) && count( $this->data ) !== 0 );
	}

	/**
	 * Number of items in data variable
	 *
	 * @return int
	 *
	 * @since 1.6.0
	 */
	public function max_data() {
		return count( $this->data );
	}

	/**
	 * Returns array data from the database or empty array
	 *
	 * @return array
	 *
	 * @since 1.6.0
	 */
	public function get_data() {
		return ( $this->data_is_valid() ) ? $this->data : array();
	}
}