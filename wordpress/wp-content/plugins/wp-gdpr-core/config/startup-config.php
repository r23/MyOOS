<?php

namespace wp_gdpr\config;


use wp_gdpr\lib\Gdpr_Container;
use wp_gdpr\lib\Gdpr_Log;
use wp_gdpr\lib\Gdpr_Translation;

class Startup_Config {

	/**
	 * Base configuration for this plugin
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
	    $this->include_translation();
		$this->execute_on_script_shutdown();
		$this->action_to_remove_old_rows_in_logtable();
		$this->basic_config();
	}

    /**
     * Includes translations
     *
     * @since 1.5.0
     */
	public function include_translation()
    {
        new Gdpr_Translation();
    }

	/**
	 * Writes logging messages to the database
	 *
	 * @since 1.5.3
	 */
	public function execute_on_script_shutdown() {
		add_action( 'shutdown', array( Gdpr_Log::instance(), 'log_to_database' ) );
	}

	/**
	 * Creates settings page in the backend
	 *
	 * @since 1.0.0
	 */
	public function basic_config() {
		Gdpr_Container::make( 'wp_gdpr\lib\Gdpr_Menu_Backend' );

		add_action('admin_init', array(  $this, 'create_page'), 1);
	}

	/**
	 * Connects gdpr_clear_log cron hook with the correct function
	 *
	 * @since 1.5.3
	 */
	public function action_to_remove_old_rows_in_logtable() {
		add_action( 'gdpr_clear_log', array( Gdpr_Log::instance(), 'remove_old_rows' ) );
	}

	/**
	 * create page with shortcode
	 *
	 * @since 1.0.0
	 */
	public function create_page() {
		if ( false === get_option( 'gdpr_page' ) ) {
			add_action( 'admin_init', function () {
				wp_insert_post( array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => __('GDPR - Request personal data', 'wp_gdpr'),
					'post_content' => '[REQ_CRED_FORM]'
				) );
			}, 100 );

			update_option( 'gdpr_page', 1 );
		}
	}
}


