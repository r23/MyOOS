<?php

/**
 * Adds new message to data register
 *
 * @param $email
 * @param $message
 * @param $ref
 * @param $ref_id
 *
 * @since 1.6.0
 */
function add_data_register_message( $email, $message, $ref, $ref_id ) {
	$data_register = \wp_gdpr\model\Data_Register_Model::instance();

	$data_register->add_message( $email, $message, $ref, $ref_id );
}

/**
 * Export data to csv file
 *
 * @param $headers
 * @param $body
 *
 * @since 1.6.0
 */
function export_data_to_csv( $headers, $body, $filename ) {
	$csv_export = new \wp_gdpr\lib\Gdpr_Csv_Export( $headers, $body, $filename );
	$csv_export->export();
}