<?php

if( ! class_exists( "Yoast_API_Request", false ) ) {

	/**
	* Handles requests to the Yoast EDD API
	*/
	class Yoast_API_Request {

		/**
		* @var string Request URL
		*/
		private $url = '';

		/**
		* @var array Request parameters
		*/
		private $args = array(
			'method' => 'GET',
			'timeout' => 20, 
			'sslverify' => false, 
			'headers' => array( 'Accept-Encoding' => '*' ) 
		);

		/**
		* @var boolean
		*/
		private $skipped_curl = false;	

		/**
		* @var boolean
		*/
		private $success = false;

		/**
		* @var mixed
		*/
		private $response;

		/**
		* @var string
		*/
		private $error_message = '';

		/**
		* Constructor
		* 
		* @param string url
		* @param array $args
		*/
		public function __construct( $url, array $args = array() ) {

			// set api url
			$this->url = $url;

			// set request args (merge with defaults)
			$this->args = wp_parse_args( $args, $this->args );

			// maybe add filter to skip curl
			$this->maybe_skip_curl();

			// fire the request
			$this->fire();
		}

		/**
		* Fires the request, automatically called from constructor
		*
		* @return boolean
		*/
		private function fire() {

			// fire request to shop
			$response = wp_remote_request( $this->url, $this->args );

			// validate raw response
			if( $this->validate_raw_response( $response ) === false ) {

				// re-try request but without curl
				if( $this->skip_curl() === true ) {
					$this->fire();
				}

				$this->success = false;
				return false;
			}

			// store transient to tell class to always skip curl
			if( $this->skipped_curl === true ) {
				set_transient( 'yoast_requests_skip_curl', 1, WEEK_IN_SECONDS );
			}		

			// decode the response
			$this->response = json_decode( wp_remote_retrieve_body( $response ) );

			$this->success = true;
			return true;
		}

		/**
		* @param object $response
		* @return boolean
		*/
		private function validate_raw_response( $response ) {

			// make sure response came back okay
			if( is_wp_error( $response ) ) {
				$this->error_message = $response->get_error_message();
				return false;
			}

			// check response code, should be 200
			$response_code = wp_remote_retrieve_response_code( $response );

			if( false === strstr( $response_code, '200' ) ) {

				$response_message = wp_remote_retrieve_response_message( $response );
				$this->error_message = "{$response_code} {$response_message}";

				return false;
			}

			return true;
		}

		/**
		* Was a valid response returned?
		*
		* @return boolean
		*/ 
		public function is_valid() {
			return ( $this->success === true );
		}

		/**
		* @return string
		*/
		public function get_error_message() {
			return $this->error_message;
		}

		/**
		* @return object
		*/
		public function get_response() {
			return $this->response;
		}

		/**
		* @access private
		* @return boolean
		*/
		public function maybe_skip_curl() {

			if( get_transient( 'yoast_requests_skip_curl' ) !== 1 ) {
				return false;
			}

			return $this->skip_curl();
		}

		/**
		* Maybe skip the cURL transport for this request
		* @access private
		* @return boolean
		*/
		private function skip_curl() {

			// if we already skipped curl, don't bother
			if( $this->skipped_curl === true ) {
				return false;
			}

			$transport_used = _wp_http_get_object()->_get_first_available_transport( $this->args, $this->url );
			if( strtolower( $transport_used ) !== 'wp_http_curl' ) {
				return false;
			}

			add_filter( 'http_api_transports', array( $this, 'disable_curl_transport' ) );

			$this->skipped_curl = true;

			return true;
		}

		/**
		* Disables the cURL transport method if a previous cURL request failed
		*
		* @param array $transports
		* @return array
		*/
		public function disable_curl_transport( array $transports ) {

			// find index of 'curl'
			$key = array_search( 'curl', $transports );

			// remove curl
			if( $key !== false ) {
				unset( $transports[ $key ] );
				$transports = array_values( $transports );
			}

			// return available transports
			return $transports;
		}

	}

}

