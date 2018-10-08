<?php

namespace wp_gdpr\config;


use wp_gdpr\lib\Gdpr_Log;
use wp_gdpr\model\Data_Register_Model;

class Activation_Config {

	public function __construct() {
		$this->install();
	}

	/**
	 * These functions are run when the plugin activates
	 *
	 * @since 1.5.3
	 */
	public function install() {
		$did_the_script_already_run = get_option( 'gdpr_activation_script', true );

		if ( $did_the_script_already_run !== '1' ) {
			$this->create_data_register_table();
			$this->create_logtable();
			$this->delete_old_rows_in_logtable();

			update_option( 'gdpr_activation_script', 1 );
		}
	}

	/**
	 * Creates log table in the database
	 *
	 * @since 1.5.3
	 */
	private function create_logtable() {
		$log = Gdpr_Log::instance();

		$log->create_log_table();
	}

	/**
	 * Creates data register table
	 *
	 * @since 1.5.3
	 */
	private function create_data_register_table() {
		$data_register = Data_Register_Model::instance();

		$data_register->create_table();
	}

	/**
	 * Deletes old rows in the log database table
	 *
	 * @since 1.5.3
	 */
	private function delete_old_rows_in_logtable() {
		if ( ! wp_next_scheduled( 'gdpr_clear_log' ) ) {
			wp_schedule_event( time(), 'daily', 'gdpr_clear_log' );
		}
	}
}