<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Cookie_Notice_Welcome_API class.
 * 
 * @class Cookie_Notice_Welcome_API
 */
class Cookie_Notice_Welcome_API {
	// api urls
	private $account_api_url = '';
	private $designer_api_url = '';
	private $x_api_key = '';
	
	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_cn_api_request', array( $this, 'api_request' ) );
		
		$this->account_api_url = 'https://account-api.hu-manity.co';
		$this->designer_api_url = 'https://designer-api.hu-manity.co';
		$this->x_api_key = 'hudft60djisdusdjwek';
	}

	/**
	 * Ajax API request
	 */
	public function api_request() {
		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			wp_die( _( 'You do not have permission to access this page.', 'cookie-notice' ) );

		if ( ! check_ajax_referer( 'cookie-notice-welcome', 'nonce' ) )
			wp_die( _( 'You do not have permission to access this page.', 'cookie-notice' ) );

		if ( empty( $_POST['request'] ) )
			wp_die( _( 'You do not have permission to access this page.', 'cookie-notice' ) );

		if ( ( $_POST['request'] === 'payment' && ! empty( $_POST['cn_payment_nonce'] ) && ! wp_verify_nonce( $_POST['cn_payment_nonce'], 'cn_api_payment' ) ) || ( ! empty( $_POST['cn_nonce'] ) && ! wp_verify_nonce( $_POST['cn_nonce'], 'cn_api_' . $_POST['request'] ) ) )
			wp_die( __( 'You do not have permission to access this page.', 'cookie-notice' ) );

		$request = in_array( $_POST['request'], array( 'register', 'login', 'configure', 'select_plan', 'payment', 'get_bt_init_token' ), true ) ? $_POST['request'] : '';
		$errors = array();
		$response = false;

		if ( ! $request )
			return false;

		// get app token data
		$data_token = get_transient( 'cookie_notice_app_token' );
		$api_token = ! empty( $data_token->token ) ? $data_token->token : '';
		$admin_id = ! empty( $data_token->email ) ? $data_token->email : '';
		$app_id = Cookie_Notice()->options['general']['app_id'];

		$params = array();

		switch ( $request ) {
			case 'get_bt_init_token':
				$result = $this->request( 'get_token' );

				// is token available?
				if ( ! empty( $result->token ) )
					$response = array( 'token' => $result->token );
				break;

			case 'payment':
				$error = array( 'error' => __( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) );

				// empty data?
				if ( empty( $_POST['payment_nonce'] ) || empty( $_POST['plan'] ) || empty( $_POST['method'] ) ) {
					$response = $error;
					break;
				}

				// validate plan and payment method
				$plan = in_array( $_POST['plan'], array( 'compliance_monthly', 'compliance_yearly' ), true ) ? $_POST['plan'] : false;
				$method = in_array( $_POST['method'], array( 'credit_card', 'paypal' ), true ) ? $_POST['method'] : false;

				// valid plan and payment method?
				if ( empty( $plan ) || empty( $method ) ) {
					$response = array( 'error' => __( 'Empty plan or payment method data.', 'cookie-notice' ) );
					break;
				}

				$result = $this->request( 'get_customer', array( 'AppID' => $app_id ) );

				// user found?
				if ( ! empty( $result->id ) ) {
					$customer = $result;
				// create user
				} else {
					$result = $this->request(
						'create_customer',
						array(
							'AppID'					=> $app_id,
							'AdminID'				=> $admin_id, // remove later - AdminID from API response
							'paymentMethodNonce'	=> esc_attr( $_POST['payment_nonce'] )
						)
					);
					
					if ( ! empty( $result->success ) ) {
						$customer = $result->customer;
					} else {
						$customer = $result;
					}
				}

				// file_put_contents( plugin_dir_path( __FILE__ ) . "bt-customer.txt", print_r( $customer, true ) . PHP_EOL, FILE_APPEND );

				// user created/received?
				if ( empty( $customer->id ) ) {
					$response = array( 'error' => __( 'Unable to create customer data.', 'cookie-notice' ) );
					break;
				}
				
				// @todo: check if subscribtion exists
				$subscription = $this->request(
					'create_subscription',
					array(
						'AppID'					=> $app_id,
						'PlanId'				=> $plan,
						'paymentMethodToken'	=> $customer->paymentMethods[0]->token
					)
				);

				// file_put_contents( plugin_dir_path( __FILE__ ) . "bt-subscription.txt", print_r( $subscription, true ) . PHP_EOL, FILE_APPEND );

				// subscription assigned?
				if ( ! empty( $subscription->error ) ) {
					$response = $subscription->error;
					break;
				}

				// get options
				$app_config = get_transient( 'cookie_notice_app_config' );

				// create quick config
				$params = ! empty( $app_config ) && is_array( $app_config ) ? $app_config : array();

				// cast to objects
				foreach ( $params as $key => $array ) {
					$object = new stdClass();

					foreach ( $array as $subkey => $value ) {
						$new_params[$key] = $object;
						$new_params[$key]->{$subkey} = $value;	
					}
				}

				$params = $new_params;
				$params['AppID'] = $app_id;
				// @todo When mutliple default languages are supported
				$params['DefaultLanguage'] = 'en';

				$response = $this->request( 'quick_config', $params );

				if ( $response->status === 200 ) {
					// notify publish app
					$params = array( 
						'AppID' => $app_id 
					);

					$response = $this->request( 'notify_app', $params );

					if ( $response->status === 200 ) {
						$response = true;
						
						// update app status
						update_option( 'cookie_notice_status', 'active' );
					} else {
						// errors?
						if ( ! empty( $response->error ) ) {
							break;
						}

						// errors?
						if ( ! empty( $response->message ) ) {
							$response->error = $response->message;
							break;
						}
					}
				} else {
					// errors?
					if ( ! empty( $response->error ) ) {
						$response->error = $response->error;
						break;
					}

					// errors?
					if ( ! empty( $response->message ) ) {
						$response->error = $response->message;
						break;
					}
				}
				break;

			case 'register':
				$email = is_email( $_POST['email'] );
				$pass = ! empty( $_POST['pass'] ) ? esc_attr( $_POST['pass'] ) : '';
				$pass2 = ! empty( $_POST['pass2'] ) ? esc_attr( $_POST['pass2'] ) : '';
				$terms = isset( $_POST['terms'] );
				$language = ! empty( $_POST['language'] ) ? esc_attr( $_POST['language'] ) : 'en';
				
				if ( ! $terms ) {
					$response = array( 'error' => __( "Please accept the Terms of Service to proceed.", 'cookie-notice' ) );
					break;
				}

				if ( ! $email ) {
					$response = array( 'error' => __( 'Email is not allowed to be empty.', 'cookie-notice' ) );
					break;
				}
				
				if ( ! $pass ) {
					$response = array( 'error' => __( 'Password is not allowed to be empty.', 'cookie-notice' ) );
					break;
				}
				
				if ( $pass !== $pass2 ) {
					$response = array( 'error' => __( "Passwords do not match.", 'cookie-notice' ) );
					break;
				}

				$params = array(
					'AdminID' => $email,
					'Password' => $pass,
					'Language' => $language
				);
				
				$response = $this->request( $request, $params );

				// errors?
				if ( ! empty( $response->error ) ) {
					break;
				}
				
				// errors?
				if ( ! empty( $response->message ) ) {
					$response->error = $response->message;
					break;
				}
				
				// ok, so log in now
				$params = array(
					'AdminID' => $email,
					'Password' => $pass
				);
				
				$response = $this->request( 'login', $params );
				
				// errors?
				if ( ! empty( $response->error ) ) {
					break;
				}

				// errors?
				if ( ! empty( $response->message ) ) {
					$response->error = $response->message;
					break;
				}
				// token in response?
				if ( empty( $response->data->token ) ) {
					$response = array( 'error' => __( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) );
					break;
				}
				
				// set token
				set_transient( 'cookie_notice_app_token', $response->data, 24 * HOUR_IN_SECONDS );
				
				// multisite?
				if ( is_multisite() ) {
					switch_to_blog( 1 );
					$site_title = get_bloginfo( 'name' );
					$site_url = network_site_url();
					$site_description = get_bloginfo( 'description' );
					restore_current_blog();
				} else {
					$site_title = get_bloginfo( 'name' );
					$site_url = get_home_url();
					$site_description = get_bloginfo( 'description' );
				}
				
				// create new app, no need to check existing
				$params = array(
					'DomainName' => $site_title,
					'DomainUrl' => $site_url,
				);
				
				if ( ! empty( $site_description ) )
					$params['DomainDescription'] = $site_description;
				
				$response = $this->request( 'app_create', $params );
				
				// errors?
				if ( ! empty( $response->message ) ) {
					$response->error = $response->message;
					break;
				}
				
				// data in response?
				if ( empty( $response->data->AppID ) || empty( $response->data->SecretKey ) ) {
					$response = array( 'error' => __( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) );
					break;
				}
				
				// update options: app ID and secret key
				Cookie_Notice()->options['general'] = wp_parse_args( array( 'app_id' => $response->data->AppID, 'app_key' => $response->data->SecretKey ), Cookie_Notice()->options['general'] );

				update_option( 'cookie_notice_options', Cookie_Notice()->options['general'] );
				
				// update app status
				update_option( 'cookie_notice_status', 'pending' );
				
				break;

			case 'login':
				$email = is_email( $_POST['email'] );
				$pass = ! empty( $_POST['pass'] ) ? esc_attr( $_POST['pass'] ) : '';
				
				if ( ! $email ) {
					$response = array( 'error' => __( 'Email is not allowed to be empty.', 'cookie-notice' ) );
					break;
				}
				
				if ( ! $pass ) {
					$response = array( 'error' => __( 'Password is not allowed to be empty.', 'cookie-notice' ) );
					break;
				}
				
				$params = array(
					'AdminID' => $email,
					'Password' => $pass
				);
				
				$response = $this->request( $request, $params );

				// errors?
				if ( ! empty( $response->error ) ) {
					break;
				}
				
				// errors?
				if ( ! empty( $response->message ) ) {
					$response->error = $response->message;
					break;
				}
				
				// token in response?
				if ( empty( $response->data->token ) ) {
					$response = array( 'error' => __( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) );
					break;
				}

				// set token
				set_transient( 'cookie_notice_app_token', $response->data, 24 * HOUR_IN_SECONDS );
				
				// get apps and check if one for the current domain already exists	
				$response = $this->request( 'list_apps', array() );
				
				// echo '<pre>'; print_r( $response ); echo '</pre>'; exit;
				
				// errors?
				if ( ! empty( $response->message ) ) {
					$response->error = $response->message;
					break;
				}

				$apps_list = array();
				$app_exists = false;
				
				// multisite?
				if ( is_multisite() ) {
					switch_to_blog( 1 );
					$site_title = get_bloginfo( 'name' );
					$site_url = network_site_url();
					$site_description = get_bloginfo( 'description' );
					restore_current_blog();
				} else {
					$site_title = get_bloginfo( 'name' );
					$site_url = get_home_url();
					$site_description = get_bloginfo( 'description' );
				}
				
				// apps added, check if current one exists
				if ( ! empty( $response->data ) ) {
					$apps_list = (array) $response->data;
					
					foreach ( $apps_list as $index => $app ) {
						$site_without_http = trim( str_replace( array( 'http://', 'https://' ), '', $site_url ), '/' );
						
						if ( $app->DomainUrl === $site_without_http ) {
							$app_exists = $app;
							
							continue;
						}
					}
				}

				// if no app, create one
				if ( ! $app_exists ) {
					// create new app
					$params = array(
						'DomainName' => $site_title,
						'DomainUrl' => $site_url,
					);

					if ( ! empty( $site_description ) )
						$params['DomainDescription'] = $site_description;

					$response = $this->request( 'app_create', $params );
					
					// errors?
					if ( ! empty( $response->message ) ) {
						$response->error = $response->message;
						break;
					}
					
					$app_exists = $response->data;
				}
				
				// check if we have the valid app data
				if ( empty( $app_exists->AppID ) || empty( $app_exists->SecretKey ) ) {
					$response = array( 'error' => __( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) );
					break;
				}
				
				// update options: app ID and secret key
				Cookie_Notice()->options['general'] = wp_parse_args( array( 'app_id' => $app_exists->AppID, 'app_key' => $app_exists->SecretKey ), Cookie_Notice()->options['general'] );

				update_option( 'cookie_notice_options', Cookie_Notice()->options['general'] );
				
				// update app status
				update_option( 'cookie_notice_status', 'pending' );
				
				// check if the app has an active subscription
				if ( $app_exists->PaymentStatus === 'Active' ) {
					$response = array( 'error' => sprintf( __( 'You have an active subscription for %s', 'cookie-notice' ), $site_url ) );
					
					// update app status
					update_option( 'cookie_notice_status', 'active' );
					break;
				}
				break;

			case 'configure':
				$fields = array(
					'cn_position',
					'cn_color_primary',
					'cn_color_background',
					'cn_color_border',
					'cn_color_text',
					'cn_color_heading',
					'cn_color_button_text',
					'cn_laws',
					'cn_purposes'
				);
				
				$options = array();
				
				// loop through potential config form fields
				foreach ( $fields as $field ) {
					if ( isset( $_POST[$field] ) ) {
						switch ( $field ) {
							case 'cn_position':
								$options['design']['position'] = esc_attr( $_POST[$field] );
								break;
							case 'cn_color_primary':
								$options['design']['primaryColor'] = esc_attr( $_POST[$field] );
								break;
							case 'cn_color_background':
								$options['design']['bannerColor'] = esc_attr( $_POST[$field] );
								break;
							case 'cn_color_border':
								$options['design']['borderColor'] = esc_attr( $_POST[$field] );
								break;
							case 'cn_color_text':
								$options['design']['textColor'] = esc_attr( $_POST[$field] );
								break;
							case 'cn_color_heading':
								$options['design']['headingColor'] = esc_attr( $_POST[$field] );
								break;
							case 'cn_color_button_text':
								$options['design']['btnTextColor'] = esc_attr( $_POST[$field] );
								break;
							case 'cn_laws':
								$options['laws'] = array_map( 'esc_attr', $_POST[$field] );
								$new_options = array();

								foreach ( $options['laws'] as $law ) {
									$new_options[$law] = true;
								}

								$options['laws'] = $new_options;

								// GDPR
								if ( in_array( 'gdpr', $options['laws'] ) ) {
									$options['config']['privacyPolicyLink'] = true;
								} else {
									$options['config']['privacyPolicyLink'] = false;
								}

								// CCPA
								if ( in_array( 'ccpa', $options['laws'] ) ) {
									$options['config']['dontSellLink'] = true;
								} else {
									$options['config']['dontSellLink'] = false;
								}
								break;
							case 'cn_purposes':
								$options['text']['bodyText'] = Cookie_Notice()->settings->cookie_messages[absint( $_POST[$field] )];
								break;
						}
					}
				}
				
				// set options
				set_transient( 'cookie_notice_app_config', $options, 24 * HOUR_IN_SECONDS );
				
				break;

			case 'select_plan':
				break;
		}

		echo json_encode( $response );
		exit;
	}
	
	/**
	 * Youzign API request.
	 *
	 * @param string  $action The requested action.
	 * @param array   $_data   Parameters for the API action.
	 * @return false|object
	 */
	private function request( $request = '', $params = '' ) {
		$api_args = array( 
			'timeout' => 60, 
			'sslverify' => false, 
			'headers' => array( 'x-api-key' => $this->x_api_key )
		);
		$api_params = array();
		$json = false;

		// get app token data
		$data_token = get_transient( 'cookie_notice_app_token' );
		$api_token = ! empty( $data_token->token ) ? $data_token->token : '';
		$admin_id = ! empty( $data_token->email ) ? $data_token->email : '';

		switch ( $request ) {
			case 'register':
				$api_url = $this->account_api_url . '/api/account/account/registration';
				$api_args['method'] = 'POST';
				break;

			case 'login':
				$api_url = $this->account_api_url . '/api/account/account/login';
				$api_args['method'] = 'POST';
				break;

			case 'list_apps':
				$api_url = $this->account_api_url . '/api/account/app/list';
				$api_args['method'] = 'GET';
				$api_args['headers'] = array_merge(
					$api_args['headers'],
					array(
						'Authorization' => 'Bearer ' . $api_token
					)
				);
				break;

			case 'app_create':
				$api_url = $this->account_api_url . '/api/account/app/add';
				$api_args['method'] = 'POST';
				$api_args['headers'] = array_merge(
					$api_args['headers'],
					array(
						'Authorization' => 'Bearer ' . $api_token
					)
				);
				break;
			
			case 'get_config':
				$api_url = $this->designer_api_url . '/api/designer/user-design-live';
				$api_args['method'] = 'GET';
				break;
			
			case 'quick_config':
				$json = true;
				$api_url = $this->designer_api_url . '/api/designer/user-design/quick';
				$api_args['method'] = 'POST';
				$api_args['headers'] = array_merge(
					$api_args['headers'],
					array(
						'Authorization' => 'Bearer ' . $api_token,
						'Content-Type'	=> 'application/json; charset=utf-8'
					)
				);
				break;
			
			case 'notify_app':
				$json = true;
				$api_url = $this->account_api_url . '/api/account/app/notifyAppPublished';
				$api_args['method'] = 'POST';
				$api_args['headers'] = array_merge(
					$api_args['headers'],
					array(
						'Authorization' => 'Bearer ' . $api_token,
						'Content-Type'	=> 'application/json; charset=utf-8'
					)
				);
				break;

			// braintree init token
			case 'get_token':
				$api_url = $this->account_api_url . '/api/account/braintree';
				$api_args['method'] = 'GET';
				$api_args['headers'] = array_merge(
					$api_args['headers'],
					array(
						'Authorization'	=> 'Bearer ' . $api_token
					)
				);
				break;

			// braintree get customer
			case 'get_customer':
				$json = true;
				$api_url = $this->account_api_url . '/api/account/braintree/findcustomer';
				$api_args['method'] = 'POST';
				$api_args['data_format'] = 'body';
				$api_args['headers'] = array_merge(
					$api_args['headers'],
					array(
						'Authorization'	=> 'Bearer ' . $api_token,
						'Content-Type'	=> 'application/json; charset=utf-8'
					)
				);
				break;

			// braintree create customer in vault
			case 'create_customer':
				$json = true;
				$api_url = $this->account_api_url . '/api/account/braintree/createcustomer';
				$api_args['method'] = 'POST';
				$api_args['headers'] = array_merge(
					$api_args['headers'],
					array(
						'Authorization' => 'Bearer ' . $api_token,
						'Content-Type'	=> 'application/json; charset=utf-8'
					)
				);
				break;

			// braintree assign subscription to the customer
			case 'create_subscription':
				$json = true;
				$api_url = $this->account_api_url . '/api/account/braintree/createsubscription';
				$api_args['method'] = 'POST';
				$api_args['headers'] = array_merge(
					$api_args['headers'],
					array(
						'Authorization' => 'Bearer ' . $api_token,
						'Content-Type'	=> 'application/json; charset=utf-8'
					)
				);
				break;
		}

		if ( ! empty( $params ) && is_array( $params ) ) {
			foreach ( $params as $key => $param ) {
				if ( is_object( $param ) )
					$api_params[$key] = $param;
				else
					$api_params[$key] = esc_attr( $param );
			}

			if ( $json )
				$api_args['body'] = json_encode( $api_params );
			else
				$api_args['body'] = $api_params;
		}
		
		$response = wp_remote_request( $api_url, $api_args );

		if ( is_wp_error( $response ) )
			$result = array( 'error' => $response->get_error_message() );
		else {
			$content_type = wp_remote_retrieve_header( $response, 'Content-Type' );

			// HTML response, means error
			if ( $content_type == 'text/html' ) {
				$result = array( 'error' => __( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ) );
			} else {
				$result = wp_remote_retrieve_body( $response );
				// detect json or array
				$result = is_array( $result ) ? $result : json_decode( $result );
			}
		}

		return $result;
	}
	
	/**
	 * Ajax API request
	 */
	public function get_app_status( $app_id ) {
		$result = '';
		
		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			return false;

		$params = array( 
			'AppID' => $app_id 
		);
		
		$response = $this->request( 'get_config', $params );
		
		if ( ! empty( $response->data ) ) {
			$result = 'active';
		} else {
			if ( ! empty( $response->error ) ) {
				if ( $response->error == 'App is not puplised yet' )
					$result = 'pending';
			}
		}
		
		return $result;
	}

	/**
	 * Defines the function used to initial the cURL library.
	 *
	 * @param string $url To URL to which the request is being made
	 * @param string $params The URL query parameters
	 * @return string $response The response, if available; otherwise, null
	 */
	private function curl( $url, $args ) {
		$curl = curl_init( $url );

		$headers = array();

		foreach ( $args['headers'] as $header => $value ) {
			$headers[] = $header . ': ' . $value;
		}

		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_HEADER, false );
		curl_setopt( $curl, CURLOPT_USERAGENT, '' );
		curl_setopt( $curl, CURLOPT_HTTPGET, true );
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'GET' );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $args['body'] );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );

		$response = curl_exec( $curl );

		if ( 0 !== curl_errno( $curl ) || 200 !== curl_getinfo( $curl, CURLINFO_HTTP_CODE ) )
			$response = null;
		
		curl_close( $curl );

		return $response;
	}
}