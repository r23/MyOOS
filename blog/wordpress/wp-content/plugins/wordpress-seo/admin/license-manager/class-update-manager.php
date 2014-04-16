<?php

if( ! class_exists( "Yoast_Update_Manager", false ) ) {

	class Yoast_Update_Manager {

		/**
		 * @var Yoast_Product
		 */
		protected $product;

		/**
		 * @var string
		 */
		protected $license_key;

		/**
		 * @var string
		 */
		protected $error_message = '';

		/**
		 * Constructor
		 *
		 * @param string $api_url     The url to the EDD shop
		 * @param string $item_name   The item name in the EDD shop
		 * @param string $license_key The (valid) license key
		 * @param string $slug        The slug. This is either the plugin main file path or the theme slug.
		 * @param string $version     The current plugin or theme version
		 * @param string $author      (optional) The item author.
		 */
		public function __construct( Yoast_Product $product, $license_key ) {
			$this->product = $product;
			$this->license_key = $license_key;
		}

		/**
		 * If the update check returned a WP_Error, show it to the user
		 */
		public function show_update_error() {

			if ( $this->error_message === '' ) {
				return;
			}

			?>
			<div class="error">
			<p><?php printf( __( '%s failed to check for updates because of the following error: <em>%s</em>', $this->product->get_text_domain() ), $this->product->get_item_name(), $this->error_message ); ?></p>
			</div>
			<?php
			}

		/**
		 * Calls the API and, if successfull, returns the object delivered by the API.
		 *
		 * @uses         get_bloginfo()
		 * @uses         wp_remote_post()
		 * @uses         is_wp_error()
		 *
		 * @return false||object
		 */
		protected function call_remote_api() {

			// only check if a transient is not set (or if it's expired)
			if( get_transient( $this->product->get_slug() . '-update-check-error' ) !== false ) {
				return false;
			}

			// setup api parameters
			$api_params = array(
				'edd_action' => 'get_version',
				'license'    => $this->license_key,
				'name'       => $this->product->get_item_name(),
				'slug'       => $this->product->get_slug(),
				'author'     => $this->product->get_author()
			);

			// setup request parameters
			$request_params = array(
				'method' => 'POST',
				'body'      => $api_params
			);

			require_once dirname( __FILE__ ) . '/class-api-request.php';
			$request = new Yoast_API_Request( $this->product->get_api_url(), $request_params );

			if( $request->is_valid() !== true ) {

				// show error message
				$this->error_message = $request->get_error_message();
				add_action( 'admin_notices', array( $this, 'show_update_error' ) );

				// set a transient to prevent checking for updates on every page load
				set_transient( $this->product->get_slug() . '-update-check-error', 1, DAY_IN_SECONDS ); // 30 mins

				return false;
			}

			// decode response
			$response = $request->get_response();
			$response->sections = maybe_unserialize( $response->sections );

			return $response;
		}

	}
	
}