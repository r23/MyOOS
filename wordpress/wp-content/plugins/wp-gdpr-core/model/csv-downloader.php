<?php

namespace wp_gdpr\model;

class Csv_Downloader {
	/**
	 * @var
	 * set filename
	 */
	public $filename;
	/**
	 * @var
	 * headers of CSV
	 */
	public $headers;
	/**
	 * @var
	 */
	public $data;
	/**
	 * void function to download csv
	 */
	public function download_csv() {
		// headers for csv
		header( 'Content-Description: File Transfer' );
		header( 'Content-Encoding: UTF-8' );
		header( 'Content-Type: text/csv; charset=UTF-8' );
		header( 'Content-Disposition: attachment; filename=' . $this->filename . '.csv' );
		header( 'Content-Transfer-Encoding: binary' );
		echo "\xEF\xBB\xBF";

		//output on browser
		$output = fopen( 'php://output', 'w' );

		//create csv headers
		fputcsv( $output, $this->headers );

		//foreach array in body array create csv row
		foreach ( $this->data as $fields ) {
			fputcsv( $output, $fields );
		}

		//when creating rows is finished close file
		fclose( $output );

		//in this moment file should appear in browser
		exit;
	}

	public function add_headers( $array_headers ) {
		$this->headers = $array_headers;
	}

	public function set_filename( $name ) {
		$this->filename = $name;
	}

	/**
	 * @param $array_comments
	 * add comments to download
	 */
	public function map_comments_into_csv_data( $array_comments ) {
		$this->set_data( array_map( function ( $data ) {
			return
				array(
					$data->comment_author,
					$data->comment_author_email,
					$data->comment_content,
					$data->comment_author_url,
				);
		}, $array_comments ) );
	}

	/**
	 * @param mixed $data
	 */
	public function set_data( $data ) {
		$this->data = $data;
	}
}
