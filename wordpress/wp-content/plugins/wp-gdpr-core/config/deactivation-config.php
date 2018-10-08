<?php

namespace wp_gdpr\config;


/**
 * This class will run after plugin deactivation
 *
 * @package wp_gdpr\config
 *
 * @since 1.5.3
 */
class Deactivation_Config {

	public function deactivate() {
		$this->clear_activation_script_status();
	}

	/**
	 * Clear activation script flag to re-rerun database table creation when it is activated back.
	 *
	 * @since 1.5.3
	 */
	public function clear_activation_script_status() {
		delete_option( 'gdpr_activation_script' );
	}
}