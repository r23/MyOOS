<?php

namespace wp_gdpr\lib;

/**
 * This class is used to export data to csv format
 *
 * Class Gdpr_Csv_Export
 * @package wp_gdpr\lib
 *
 * @since 1.6.0
 */
class Gdpr_Csv_Export {

	/**
	 * @var array   csv headers
	 *
	 * @since 1.6.0
	 */
	private $headers;

	/**
	 * @var array   csv body
	 *
	 * @since 1.6.0
	 */
	private $body;

	/**
	 * @var string  csv file name
	 *
	 * @since 1.6.0
	 */
	private $filename;

	/**
	 * Gdpr_Csv_Export constructor.
	 *
	 * @param $headers  array
	 * @param $body     array
	 * @param $filename string
	 */
	public function __construct( $headers, $body, $filename ) {
		$this->headers  = $headers;
		$this->body     = $body;
		$this->filename = $filename;
	}

	/**
	 * Export to csv file
	 *
	 * @since 1.6.0
	 */
	public function export() {
		// output headers so that the file is downloaded rather than displayed
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $this->filename . '.csv' );

		// create a file pointer connected to the output stream
		$output = fopen( 'php://output', 'w' );

		// add BOM to fix UTF-8 in Excel
		fputs( $output, $bom = ( chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) ) );

		// output the column headings
		fputcsv( $output, $this->headers );

		// output the body
		foreach ( $this->body as $row ) {
			fputcsv( $output, (array) $row );
		}

		fclose( $output );
		die;
	}
}