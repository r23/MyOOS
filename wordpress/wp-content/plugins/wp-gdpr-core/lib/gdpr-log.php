<?php
namespace wp_gdpr\lib;

class Gdpr_Log {

	/**
	 * table name without prefix
	 */
	const TABLE_NAME = 'gdpr_log';

	/**
	 * Array where the log data gets saved
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Instance where the log object gets saved
	 *
	 * @var null|Gdpr_Log
	 */
	private static $instance = null;

	public static function instance() {
		// Check if instance is already exists
		if ( self::$instance == null ) {
			self::$instance = new Gdpr_Log();
		}

		return self::$instance;
	}

	/**
	 * Creating of logging table
	 */
	public function create_log_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;

		$query = 'CREATE TABLE ' . $table_name . ' (
				  id INT(11) NOT NULL AUTO_INCREMENT,
				  message_type VARCHAR(20) DEFAULT NULL,
				  message TEXT NOT NULL,
				  file VARCHAR(255) DEFAULT NULL,
				  function VARCHAR(40) DEFAULT NULL,
				  line VARCHAR(40) DEFAULT NULL,
				  timestamp DATETIME DEFAULT NULL,
				  PRIMARY KEY (id)
				)';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $query );

		$this->info( 'Log table created' );
	}

	/**
	 * Save message with type debug
	 *
	 * @param       $msg      string  Message to save
	 * @param bool $file string  In which file did the call came from
	 * @param bool $function string  In which function
	 * @param bool $line string  In which line
	 */
	public function debug( $msg, $file = false, $function = false, $line = false ) {
		$this->add( 'debug', $msg, $file, $function, $line );
	}

	/**
	 * Save message with type info
	 *
	 * @param       $msg      string  Message to save
	 * @param bool $file string  In which file did the call came from
	 * @param bool $function string  In which function
	 * @param bool $line string  In which line
	 */
	public function info( $msg, $file = false, $function = false, $line = false ) {
		$this->add( 'info', $msg, $file, $function, $line );
	}

	/**
	 * Save message with type warn
	 *
	 * @param       $msg      string  Message to save
	 * @param bool $file string  In which file did the call came from
	 * @param bool $function string  In which function
	 * @param bool $line string  In which line
	 */
	public function warn( $msg, $file = false, $function = false, $line = false ) {
		$this->add( 'warn', $msg, $file, $function, $line );
	}

	/**
	 * Save message with type error
	 *
	 * @param       $msg      string  Message to save
	 * @param bool $file string  In which file did the call came from
	 * @param bool $function string  In which function
	 * @param bool $line string  In which line
	 */
	public function error( $msg, $file = false, $function = false, $line = false ) {
		$this->add( 'error', $msg, $file, $function, $line );
	}

	/**
	 * Save message with type fatal
	 *
	 * @param       $msg      string  Message to save
	 * @param bool $file string  In which file did the call came from
	 * @param bool $function string  In which function
	 * @param bool $line string  In which line
	 */
	public function fatal( $msg, $file = false, $function = false, $line = false ) {
		$this->add( 'fatal', $msg, $file, $function, $line );
	}

	/**
	 * Save message
	 *
	 * @param       $msg_type string  The message type (debug, info, warn, error or fatal)
	 * @param       $msg      string  Message to save
	 * @param       $file     string  In which file did the call came from
	 * @param bool $function string  In which function
	 * @param       $line     string  In which line
	 */
	public function add( $msg_type, $msg, $file, $function, $line ) {
		$backtrace = debug_backtrace();

		$file      = ( $file === false ) ? $backtrace[1]['file'] : $file;
		$line      = ( $line === false ) ? $backtrace[1]['line'] : $line;
		$function  = ( $function === false ) ? $backtrace[2]['function'] : $function;
		$timestamp = current_time( 'mysql' );

		$this->log_to_data( $msg_type, $msg, $file, $function, $line, $timestamp );
	}

	/**
	 * Save message to session
	 *
	 * @param       $msg_type   string  The message type (debug, info, warn, error or fatal)
	 * @param       $msg        string  Message to save
	 * @param       $file       string  In which file did the call came from
	 * @param bool $function string  In which function
	 * @param       $line       string  In which line
	 * @param       $timestamp  string  Timestamp of the log
	 */
	public function log_to_data( $msg_type, $msg, $file, $function, $line, $timestamp ) {
		$this->data[] = array(
			'message_type' => $msg_type,
			'message'      => $msg,
			'file'         => $file,
			'function'     => $function,
			'line'         => $line,
			'timestamp'    => $timestamp
		);
	}

	/**
	 * Saving log records to database
	 * This function will be executed as the last PHP function.
	 */
	public function log_to_database() {
		if ( isset( $this->data ) && is_array( $this->data ) && defined('WP_DEBUG') && count($this->data) != 0 ) {
			global $wpdb;
			$values = array();

			foreach ( $this->data as $log ) {
				$values[] = $wpdb->prepare( "(%s, %s, %s, %s, %s, %s)",
					$log['message_type'],
					( is_array( $log['message'] ) ) ? serialize( $log['message'] ) : $log['message'],
					$log['file'],
					$log['function'],
					$log['line'],
					$log['timestamp'] );
			}

			$table_name = $wpdb->prefix . self::TABLE_NAME;

			$query = "INSERT INTO " . $table_name . " (message_type, message, file, function, line, timestamp) VALUES ";
			$query .= implode( ", ", $values );

			$wpdb->query( $query );
			//clear data
			$this->data = array();
		}
	}

	/**
	 * Remove logs older than a week
	 *
	 * TODO add WP_DEUG_DAYS to user end documentation
	 */
	public function remove_old_rows() {
		global $wpdb;

		$days = ( defined('WP_DEBUG_DAYS' ) ) ? WP_DEBUG_DAYS : 30;

		$timestamp = date( 'Y-m-d H:i:s', strtotime( '-'.$days.' days' ) );

		$query = "DELETE FROM " . $wpdb->prefix . static::TABLE_NAME . " WHERE timestamp < '" . $timestamp . "'";

		$deleted = $wpdb->query( $query );

		if ( $deleted > 1 ) {
			$this->info( $deleted . ' logs older than ' . $timestamp . ' are deleted' );
		} elseif ( $deleted == 1 ) {
			$this->info( $deleted . ' log older than ' . $timestamp . ' is deleted' );
		}
	}

}
