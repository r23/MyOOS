<?php

namespace wp_gdpr\controller;

use wp_gdpr\lib\Gdpr_Log_Interface;
use wp_gdpr\model\Request_Form;

class Controller_Frontend extends Gdpr_Log_Interface {


	/**
	 * Controller_Frontend is used for frontend Privacy Center
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_style' ) );
	}

	/**
	 * Loads javascript file according to current hook
	 *
	 * @param $hook
	 *
	 * @since 1.6.0
	 */
	public function frontend_script() {
		$pageid = Request_Form::get_page_id();
		if ( $pageid != false && is_page( $pageid ) ) {
			if ( ! isset( $_REQUEST['req'] ) ) {
				return;
			}
			wp_enqueue_script( 'popper_min_js', GDPR_URL . 'assets/js/popper.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'bootstrap_min_js', GDPR_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'jquery_slimscroll_js', GDPR_URL . 'assets/js/jquery.slimscroll.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'modernizr_min_js', GDPR_URL . 'assets/js/modernizr.min.js', array( 'jquery' ), null, false );
			wp_enqueue_script( 'jquery_scrollTo_min_js', GDPR_URL . 'assets/js/jquery.scrollTo.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'dataTables_min_js', GDPR_URL . 'vendor/datatables/jquery.dataTables.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'dataTables_bootstrap_min_js', GDPR_URL . 'vendor/datatables/dataTables.bootstrap4.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'dataTables_responsive_min_js', GDPR_URL . 'vendor/datatables/dataTables.responsive.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'responsive_bootstrap4_min_js', GDPR_URL . 'vendor/datatables/responsive.bootstrap4.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'jquery_core_js', GDPR_URL . 'assets/js/jquery.core.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'jquery_app_js', GDPR_URL . 'assets/js/jquery.app.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'dataTables_buttons_min_js', GDPR_URL . 'vendor/datatables/dataTables.buttons.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'buttons_buttons_min_js', GDPR_URL . 'vendor/datatables/buttons.bootstrap4.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'jszip_min_js', GDPR_URL . 'vendor/datatables/jszip.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'pdfmake_min_js', GDPR_URL . 'vendor/datatables/pdfmake.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'vfs_fonts_min_js', GDPR_URL . 'vendor/datatables/vfs_fonts.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'buttons_html5_min_js', GDPR_URL . 'vendor/datatables/buttons.html5.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'buttons_print_min_js', GDPR_URL . 'vendor/datatables/buttons.print.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'sweetalert2_min_js', GDPR_URL . 'vendor/sweet-alert/sweetalert2.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'jquery_sweet_alert_init_js', GDPR_URL . 'assets/js/jquery.sweet-alert.init.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'gdpr_frontend', GDPR_URL . 'assets/js/gdpr.frontend.js', array( 'jquery' ), null, true );

			$table_names = apply_filters( 'gdpr_tables_names', array() );

			wp_localize_script( 'gdpr_frontend', 'localized_object_frontend', array(
				'tables' => $table_names,
			) );
		}

	}

	/**
	 * Loads css file according to current hook
	 *
	 * @param $hook
	 *
	 * @since 1.6.0
	 */
	public function frontend_style() {
		$pageid = Request_Form::get_page_id();
		if ( $pageid != false && is_page( $pageid ) ) {
			if ( ! isset( $_REQUEST['req'] ) ) {
				return;
			}
			wp_enqueue_style( 'sweet_alert_min', GDPR_URL . 'vendor/sweet-alert/sweetalert2.min.css' );
			wp_enqueue_style( 'bootstrap_min', GDPR_URL . 'assets/css/wp-gdpr_bootstrap.css' );
			wp_enqueue_style( 'gdpr_style', GDPR_URL . 'assets/css/style.css' );
			wp_enqueue_style( 'datatables_bootstrap', GDPR_URL . 'vendor/datatables/dataTables.bootstrap4.min.css' );
			wp_enqueue_style( 'responsive_bootstrap', GDPR_URL . 'vendor/datatables/responsive.bootstrap4.min.css' );
		}
	}
}
