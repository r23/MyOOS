<?php


namespace wp_gdpr\controller;

use wp_gdpr\lib\Gdpr_Customtables;
use wp_gdpr\lib\Gdpr_Email;
use wp_gdpr\lib\Gdpr_Language;
use wp_gdpr\lib\Gdpr_Options_Helper;
use wp_gdpr\lib\Gdpr_Table_Builder;
use wp_gdpr\lib\Gdpr_Container;
use wp_gdpr\lib\Gdpr_Form_Builder;
use wp_gdpr\model\Request_Form;
use wp_gdpr\lib\Gdpr_Log_Interface;

class Controller_Menu_Page extends Gdpr_Log_Interface {
	const PRIVACY_POLICY_LABEL = 'privacy_policy_label';
	const PRIVACY_POLICY_TEXT = 'privacy_policy_text';
	const PRIVACY_POLICY_CHECKBOX = 'privacy_policy_checkbox';
	const PRIVACY_POLICY_TEXT_DATA_REQUEST = 'privacy_policy_text_data_request';


	/**
	 * Controller_Menu_Page constructor.
	 */
	public function __construct() {
		parent::__construct();
		if ( ! has_action( 'init', array( $this, 'send_email' ) ) ) {
			add_action( 'init', array( $this, 'send_email' ) );
		}
		if ( ! has_action( 'init', array( $this, 'save_settings' ) ) ) {
			add_action( 'init', array( $this, 'save_settings' ) );
		}
		if ( ! has_action( 'init', array( $this, 'post_delete_comments' ) ) ) {
			add_action( 'init', array( $this, 'post_delete_comments' ) );
		}
		if ( ! has_action( 'init', array( $this, 'request_add_on' ) ) ) {
			add_action( 'init', array( $this, 'request_add_on' ) );
		}
		if ( ! has_action( 'init', array( $this, 'update_privacy_policy_settings' ) ) ) {
			add_action( 'init', array( $this, 'update_privacy_policy_settings' ) );
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_script' ) );
	}

	/**
	 * Loads javascript file according to current hook
	 *
	 * @param $hook
	 *
	 * @since 1.5.0
	 */
	public function admin_script( $hook ) {
		$this->log->info( 'Admin help.js loaded' );
		switch ( $hook ) {
			case 'toplevel_page_wp_gdpr':
				$this->log->info( 'Admin help.js loaded for page wp-gdpr' );
			case 'wp-gdpr_page_help':
				$this->log->info( 'Admin help.js loaded for page help' );
			case 'wp-gdpr_page_addon':
				$this->log->info( 'Admin help.js loaded for page addon' );
			case 'wp-gdpr_page_deletelist':
				$this->log->info( 'Admin help.js loaded for page deletelist' ); //??
			case 'wp-gdpr_page_datareg':
				$this->log->info( 'Admin help.js loaded for page data reguests' );
			case 'wp-gdpr_page_pluginlist':
				$this->log->info( 'Admin help.js loaded for page pluginlist' );
				wp_enqueue_script( 'help_js', GDPR_URL . 'assets/js/help.js', array(
					'jquery',
					'jquery-ui-accordion',
					'jquery-ui-core'
				), null, false );
				if ( $hook == 'wp-gdpr_page_help' ) {
					wp_enqueue_script( 'carousel_gdpr', GDPR_URL . 'assets/js/slick.min.js', array( 'jquery' ), null, true );
					$this->log->info( 'Admin slick.min.js loaded for help page' );
					break;
				}
		}
	}

	/**
	 * Update privacy policy after form submit
	 *
	 * @since 1.5.0
	 */
	public function update_privacy_policy_settings() {
		$lang = new Gdpr_Language();
		$lang = $lang->get_language();
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_REQUEST['gdpr_save_priv_pol_settings'] ) ) {
			$this->log->info( 'Privacy policy update when url is submited' );
			update_option( self::PRIVACY_POLICY_LABEL . $lang, $_REQUEST['gdpr_priv_pov_label'] );
			update_option( self::PRIVACY_POLICY_TEXT . $lang, $_REQUEST['gdpr_priv_pov_text'] );
			update_option( self::PRIVACY_POLICY_CHECKBOX . $lang, $_REQUEST['gdpr_priv_pov_checkbox'] );
			update_option( self::PRIVACY_POLICY_TEXT_DATA_REQUEST . $lang, $_REQUEST['gdpr_priv_pov_text_data_request'] );

			do_action( 'gdpr_save_custom_privacy_policy', $lang );
		}
	}


	/**
	 * delete all comments selected in admin menu in form
	 */
	public function post_delete_comments() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_REQUEST['gdpr_requests'] ) && is_array( $_REQUEST['gdpr_requests'] ) ) {
			$this->log->info( 'Deleted all comments selected in admin menu' );
			foreach ( $_REQUEST['gdpr_requests'] as $single_request_id ) {
				//get all selected comments
				//unserialize
				$single_request_id = sanitize_text_field( $single_request_id );
				$data_to_process   = $this->find_delete_request_by_id( $single_request_id );
				$unserialized_data = $this->unserialize( $data_to_process['data'] );
				//check post request
				if ( isset( $_REQUEST['gdpr_delete_comments'] ) ) {
					//check type of request
					$this->log->info( 'Check type of request' );
					if ( 0 == $this->get_type_of_request( $data_to_process ) ) {
						//get all comments before process to show info in email
						$original_comments = $this->get_original_comments( $unserialized_data );
						//delete
						//change status in delete
						$this->log->info( 'Change status in delete and delete' );
						$this->delete_comments( $unserialized_data );
						$this->update_status( $single_request_id, 1 );
						//change comment object into one row string for email table
						$processed_data = array_map( array( $this, 'map_comments_for_email' ), $original_comments );
						$message        = __( 'Comments deleted', 'wp_gdpr' );
					} else {
						$type_number    = $data_to_process['r_type'];
						$processed_data = apply_filters( 'gdpr_map_data_for_email_' . $type_number, $unserialized_data, $data_to_process );
						$message        = apply_filters( 'gdpr_get_del_message_' . $type_number, $unserialized_data, $data_to_process );
						do_action( 'gdpr_execute_del_req_' . $type_number, $unserialized_data, $data_to_process );
					}
					$this->set_notice( $message );
				}

				//check post request
				if ( isset( $_REQUEST['gdpr_anonymous_comments'] ) ) {
					//if comments
					$this->log->info( 'Change status into anonymous and make anonymous' );
					if ( 0 == $this->get_type_of_request( $data_to_process ) ) {
						//check type of request
						//make anonymous
						//change status into anonymous
						$this->make_anonymous( $unserialized_data );
						$this->update_status( $single_request_id, 2 );
						$message = __( 'Comments are anonymous', 'wp_gdpr' );
					} else {
						/**
						 * if addons
						 */
						//check type of request
						//make anonymous
						//change status into anonymous
						$type_number = $data_to_process['r_type'];
						do_action( 'gdpr_execute_anonymous_req_' . $type_number, $unserialized_data, $data_to_process );
						$message = apply_filters( 'gdpr_get_anonymous_message_' . $type_number, $unserialized_data, $data_to_process );
					}

					//happends always for anonymous request no matter where
					$this->set_notice( $message );
					//TODO create data about making anonymous entries or comments to send in email as content
					$processed_data = array();
				}

				Gdpr_Email::send_confirmation_email_to_requester( $data_to_process, $processed_data );
				Gdpr_Email::send_confirmation_email_to_dpo( $data_to_process, $processed_data );
			}
		}
	}

	/**
	 * @param $id
	 *
	 * @return array
	 *
	 * search for request by id in del_request table in db
	 */
	public function find_delete_request_by_id( $id ) {
		$this->log->info( 'Search for request by id in table del_request in the database' );
		global $wpdb;

		$table_name = $wpdb->prefix . Gdpr_Customtables::DELETE_REQUESTS_TABLE_NAME;

		$query  = "SELECT * FROM $table_name WHERE ID='$id'";
		$result = $wpdb->get_results( $query, ARRAY_A );

		//check if record with this id exists in database
		if ( isset( $result[0] ) ) {
			return $result[0];
		} else {
			return array();
		}
	}

	/**
	 * unserialize comments
	 *
	 * @param $serialized_comments
	 *
	 * @return mixed
	 *
	 * @since 1.5.0
	 */
	public function unserialize( $serialized_comments ) {
		$comments_to_delete = unserialize( $serialized_comments );

		return $comments_to_delete;
	}

	/**
	 * Returns type of request
	 *
	 * @param $request_data
	 *
	 * @return mixed
	 *
	 * @since 1.5.0
	 */
	public function get_type_of_request( $request_data ) {
		return $request_data['r_type'];
	}

	/**
	 * Returns all comments
	 *
	 * @return array
	 *
	 * @since 1.5.0
	 */
	public function get_original_comments( $comments ) {
		return get_comments( array( 'comment__in' => $comments ) );
	}

	/**
	 * @param $comments
	 *
	 * unserialize serialized array with comments_ids
	 */
	public function delete_comments( $comments ) {
		$this->log->info( 'Comment delete' );
		foreach ( $comments as $comment_id ) {
			wp_delete_comment( $comment_id, true );
		}
	}

	/**
	 * delete row by id from table with delete_requests
	 */
	public function update_status( $request_id, $status ) {
		$this->log->info( 'Delete row by id table with delete_requests' );
		global $wpdb;
		$table_name = $wpdb->prefix . Gdpr_Customtables::DELETE_REQUESTS_TABLE_NAME;
		$where      = array( 'ID' => $request_id );
		$data       = array( 'status' => $status );
		$wpdb->update( $table_name, $data, $where );
	}

	public function set_notice( $message ) {
		/**
		 * set notice
		 */
		$notice = Gdpr_Container::make( 'wp_gdpr\lib\Gdpr_Notice' );
		$notice->set_message( $message );
		$notice->register_notice();
	}

	/**
	 * @param $comments
	 * make comments anonymous
	 */
	public function make_anonymous( $comments ) {
		$this->log->info( 'Make comments anonymous' );
		foreach ( $comments as $comment_id ) {
			$args = array(
				'comment_ID'           => $comment_id,
				'comment_author'       => 'anonymous',
				'comment_author_email' => 'anonymous@anony.eu',
				'comment_author_url'   => ''
			);

			wp_update_comment( $args );
		}
	}

	public function map_comments_for_email( $data ) {

		return __( 'Comment author', 'wp_gdpr' ) . ': ' . $data->comment_author . ' ' . __( 'content', 'wp_gdpr' ) . ': ' . $data->comment_content;
	}

	public function request_add_on() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_REQUEST['request_add_on'] ) ) {
			$this->log->info( 'Request add-on email send to info@wp-gdpr.eu' );
			$to      = 'info@wp-gdpr.eu';
			$subject = 'request wp-gdpr add-on';
			$content = '<p>Request develop add-on for plugin: ' . $_POST["request_add_on"] . '</p><p>Email: ' . $_POST["email"] . '</p><p>' . $_POST["gdpr"] . '</p>';
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( $to, $subject, $content, $headers );

			$this->set_notice( __( 'Request sent', 'wp_gdpr' ) );
		}
	}

	public function build_form_to_add_privacy_policy_setting() {
		$this->log->info( 'Form build for privacy policy' );
		$privacy_policy_strings = $this->get_privacy_policy_strings();

		include GDPR_DIR . '/view/admin/privacy-policy-form.php';
	}

	/**
	 * Get privacy policy strings.
	 */
	public function get_privacy_policy_strings() {
		$lang = new Gdpr_Language();
		$lang = $lang->get_language();

		$privacy_policy_label             = get_option( self::PRIVACY_POLICY_LABEL . $lang, null );
		$privacy_policy_text              = get_option( self::PRIVACY_POLICY_TEXT . $lang, null );
		$privacy_policy_checkbox          = get_option( self::PRIVACY_POLICY_CHECKBOX . $lang, null );
		$privacy_policy_text_data_request = get_option( self::PRIVACY_POLICY_TEXT_DATA_REQUEST . $lang, null );

		if ( ! isset( $privacy_policy_label ) ) {
			$privacy_policy_label = __( 'Checkbox GDPR is required', 'wp_gdpr' );
			update_option( self::PRIVACY_POLICY_LABEL . $lang, $privacy_policy_label );
		}
		if ( ! isset( $privacy_policy_text ) ) {
			$privacy_policy_text = __( 'This form collects your name, email and content so that we can keep track of the comments placed on the website. For more info check our privacy policy where you\'ll get more info on where, how and why we store your data.', 'wp_gdpr' );
			update_option( self::PRIVACY_POLICY_TEXT . $lang, $privacy_policy_text );
		}
		if ( ! isset( $privacy_policy_checkbox ) ) {
			$privacy_policy_checkbox = __( 'I agree', 'wp_gdpr' );
			update_option( self::PRIVACY_POLICY_CHECKBOX . $lang, $privacy_policy_checkbox );
		}

		if ( ! isset( $privacy_policy_text_data_request ) ) {
			$string                           = __( 'I consent to having %s collect my email so that they can send me my requested info.
            For more info check our privacy policy where you\'ll get more info on where, how and why we store your data.', 'wp_gdpr' );
			$blog_name                        = get_bloginfo( 'name' );
			$privacy_policy_text_data_request = sprintf( $string, $blog_name );
			update_option( self::PRIVACY_POLICY_TEXT_DATA_REQUEST . $lang, $privacy_policy_text_data_request );
		}

		$privacy_policy_strings = array(
			$privacy_policy_label,
			$privacy_policy_text,
			$privacy_policy_checkbox,
			$privacy_policy_text_data_request,
		);

		return $privacy_policy_strings;
	}

	/**
	 *
	 */
	public function build_settings_table() {
		$this->log->info( 'Build settings table' );
		$options = $this->get_settings();
		include GDPR_DIR . '/view/admin/menu/settings-list.php';
	}

	/**
	 * Get settings for admin menu
	 *
	 * @return mixed
	 *
	 * @since 1.0 ?
	 */
	public function get_settings() {
		$this->log->info( 'Get settings for admin menu' );
		$settings = array(
			'switch_on_comments' => array(
				'label' => __( 'Don\'t show comments', 'wp_gdpr' ),
				'type'  => 'checkbox',
				'value' => 'checked',
			),
			'dpo_email'          => array(
				'label' => __( 'Set your DPO e-mail address', 'wp_gdpr' ),
				'type'  => 'email',
				'value' => '',
			),
			'gdpr_mc_api_key'    => array(
				'label' => __( 'Mailchimp API Key', 'wp_gdpr' ),
				'type'  => 'text',
				'value' => '',
			),
		);

		foreach ( $settings as $option_name => $option ) {
			switch ( $option['type'] ) {
				case 'checkbox':
					$value = get_option( $option_name, 0 ) == 1 ? 'checked' : null;
					break;
				case 'text':
					$value = get_option( $option_name, '' );
					break;
				case 'email':
					$value = get_option( $option_name, '' );
					break;
			}
			$updated_settings[ $option_name ] = array(
				'label' => $option['label'],
				'type'  => $option['type'],
				'value' => $value,
			);
		}

		return $updated_settings;
	}

	/**
	 * build table in menu admin
	 *
	 * @since   1.0 ?
	 */
	public function build_table_with_requests() {
		$this->log->info( 'Build table in admin menu' );
		$requesting_users = $this->get_requests_from_gdpr_table();

		if ( ! is_array( $requesting_users ) ) {
			return;
		}

		$form_content = $this->get_form_content( $requesting_users );

		//map status from number to string
		$requesting_users = array_map( array( $this, 'map_request_status' ), $requesting_users );
		//add checkbox input in every element with e-mail address
		$requesting_users = array_map( array( $this, 'map_checkboxes_send_email' ), $requesting_users );
		//show table object
		$table = new Gdpr_Table_Builder(
			array(
				__( 'id', 'wp_gdpr' ),
				__( 'email', 'wp_gdpr' ),
				__( 'requested at', 'wp_gdpr' ),
				__( 'status', 'wp_gdpr' ),
				__( 'language', 'wp_gdpr' ),
				__( 'resend email', 'wp_gdpr' ),
			),
			$requesting_users
			, array( $form_content ) );

		//execute
		$table->print_table();
	}

	/**
	 * @return array|null|object
	 *
	 * get all records from gdpr_requests table
	 *
	 * @since   1.0 ?
	 */
	public function get_requests_from_gdpr_table() {
		$this->log->info( 'Get all records from gdpr_request table' );
		global $wpdb;

		$table_name = $wpdb->prefix . Gdpr_Customtables::REQUESTS_TABLE_NAME;

		$query = "SELECT * FROM $table_name";

		return $wpdb->get_results( $query, ARRAY_A );
	}

	/**
	 * @param $requesting_users
	 *
	 * @return string
	 *
	 * @since   1.0 ?
	 */
	public function get_form_content( $requesting_users ) {
		ob_start();
		$controller = $this;
		include_once GDPR_DIR . 'view/admin/small-form.php';

		return ob_get_clean();
	}

	/**
	 * @param $data array
	 *
	 * @return mixed array
	 *
	 * @since   1.0 ?
	 */
	public function map_type_status( $data ) {

		if ( ! isset( $data['r_type'] ) || empty( $data['r_type'] ) ) {
			$data['r_type'] = 0;
		}

		switch ( $data['r_type'] ) {
			case 0:
				$data['r_type'] = __( 'comments', 'wp_gdpr' );
				break;
			case 1:
				$data['r_type'] = __( 'gravity form entries', 'wp_gdpr' );
				break;
			case 2:
				$data['r_type'] = __( 'cfdb7 db entries', 'wp_gdpr' );
				break;
			case 3:
				$data['r_type'] = __( 'woocommerce', 'wp_gdpr' );
				break;
			case 4:
				$data['r_type'] = __( 'flamingo', 'wp_gdpr' );
				break;
			case 5:
				$data['r_type'] = __( 'mailchimp', 'wp_gdpr' );
				break;
			case 6:
				$data['r_type'] = __( 'condolance manager', 'wp_gdpr' );
				break;

		}

		return $data;
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 * add checkbox element in array
	 */
	public function map_checkboxes_send_email( $data ) {

		$data['checkbox'] = $this->create_single_input_with_email( $data['email'] );

		return $data;
	}

	/**
	 *  create checkbox as delegate of gdpr_form
	 */
	public function create_single_input_with_email( $email ) {

		return '<input type="checkbox" form="gdpr_form"  name="gdpr_emails[]" value="' . $email . '">';
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 *
	 * callback to map status from int to string
	 */
	public function map_request_status( $data ) {

		switch ( $data['status'] ) {
			case 0:
				$data['status'] = __( 'waiting for email', 'wp_gdpr' );
				break;
			case 1:
				$data['status'] = __( 'email sent', 'wp_gdpr' );
				break;
			case 2:
				$data['status'] = __( 'url is visited', 'wp_gdpr' );
				break;
		}

		return $data;
	}

	/**
	 * this function is not in use
	 */
	public function print_inputs_with_emails() {
		global $wpdb;

		$table_name = $wpdb->prefix . Gdpr_Customtables::REQUESTS_TABLE_NAME;

		$query = "SELECT * FROM $table_name";

		$requesting_users = $wpdb->get_results( $query, ARRAY_A );

		foreach ( $requesting_users as $user ) {
			/**
			 * if status is 0
			 * email is not send
			 *
			 */
			if ( $user['status'] == 0 ) {
				echo '<input hidden name="gdpr_emails[]" value="' . $user['email'] . '">';
			}
		}

	}

	/**
	 * send emails when POST request
	 */
	public function send_email() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_REQUEST['gdpr_emails'] ) && is_array( $_REQUEST['gdpr_emails'] ) ) {
			$this->log->info( 'Email sends when POST request' );
			foreach ( $_REQUEST['gdpr_emails'] as $single_address ) {
				$single_address = sanitize_email( $single_address );
				$to             = $single_address;
				$to             = $this->add_administrator_to_receivers( $to );
				$subject        = __( 'Data request', 'wp_gdpr' );
				$request        = $this->get_request_gdpr_by_email( $single_address );
				$headers        = array( 'Content-Type: text/html; charset=UTF-8' );

				if ( ! $request ) {
					return;
				}

				$content = Gdpr_Email::get_request_email_content( $request[0]['email'], $request[0]['timestamp'] );

				$this->set_notice( __( 'Email sent', 'wp_gdpr' ) );

				wp_mail( $to, $subject, $content, $headers );

				$this->update_gdpr_request_status( $single_address );
			}
		}
	}

	public function add_administrator_to_receivers( $to ) {
		$admin_email = get_option( 'admin_email', true );
		if ( $admin_email ) {
			return $to . ',' . $admin_email;
		} else {
			return $to;
		}
	}

	/**
	 * @return array|null|object
	 * get all records from gdpr_requests table
	 */
	public function get_request_gdpr_by_email( $email ) {
		global $wpdb;

		$email = sanitize_email( $email );
		if ( empty( $email ) ) {
			return;
		}

		$query = "SELECT * FROM {$wpdb->prefix}gdpr_requests WHERE email='$email'";

		return $wpdb->get_results( $query, ARRAY_A );
	}

	public function update_gdpr_request_status( $email ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'gdpr_requests';

		$wpdb->update( $table_name, array( 'status' => 1 ), array( 'email' => $email ) );
	}

	/**
	 * search for plugins
	 */
	public function build_table_with_delete_requests() {
		$this->log->info( 'Build table with delete requests' );
		global $wpdb;
		$table_name = $wpdb->prefix . Gdpr_Customtables::DELETE_REQUESTS_TABLE_NAME;

		$query = "SELECT * FROM $table_name";

		$requests = $wpdb->get_results( $query, ARRAY_A );
		$requests = array_map( array( $this, 'add_delete_checkbox' ), $requests );
		$requests = array_map( array( $this, 'reduce_comments_to_string' ), $requests );
		//map type of data
		$requests = array_map( array( $this, 'map_type_status' ), $requests );
		$requests = array_map( array( $this, 'map_status' ), $requests );

		$table = new Gdpr_Table_Builder(
			array(
				__( 'id', 'wp_gdpr' ),
				__( 'email', 'wp_gdpr' ),
				__( 'data(ID)', 'wp_gdpr' ),
				__( 'status', 'wp_gdpr' ),
				__( 'type', 'wp_gdpr' ),
				__( 'requested at', 'wp_gdpr' ),
				__( 'select', 'wp_gdpr' )
			),
			$requests
			, array( $this->get_delete_form_content() ) );

		$table->print_table();

	}

	/**
	 *
	 * @return string
	 */
	public function get_delete_form_content() {
		$this->log->info( 'Get delete form content' );
		ob_start();
		$controller = $this;
		include_once GDPR_DIR . 'view/admin/delete-comments-form.php';

		return ob_get_clean();
	}

	public function map_status( $request ) {
		switch ( $request['status'] ) {
			case 0:
				$request['status'] = __( 'waiting to process', 'wp_gdpr' );
				break;
			case 1:
				$request['status'] = __( 'deleted', 'wp_gdpr' );
				break;
			case 2:
				$request['status'] = __( 'anonymous', 'wp_gdpr' );
				break;
		}

		return $request;
	}

	public function add_delete_checkbox( $request ) {
		if ( '0' === $request['status'] ) {
			$request['checkbox'] = $this->create_checkbox_for_single_delete_row( $request['ID'] );
		} else {
			$request['checkbox'] = __( 'processed', 'wp_gdpr' );
		}

		return $request;
	}

	public function create_checkbox_for_single_delete_row( $id ) {
		return '<input type="checkbox" form="gdpr_admin_del_comments_form"  name="gdpr_requests[]" value="' . $id . '">';
	}

	public function reduce_comments_to_string( $item ) {
		$unserialized_data = unserialize( $item['data'] );
		if ( empty( $unserialized_data ) ) {
			$item['data'] = '';

			return $item;
		}
		$item['data'] = array_reduce( $unserialized_data, function ( $carry, $item ) {
			return $carry . $item . ",";
		} );
		$item['data'] = substr( $item['data'], 0, - 1 );

		return $item;

	}

	/**
	 * search for plugins
	 */
	public function build_table_with_plugins() {
		$this->log->info( 'Table build with plugins for addonlist page' );
		$plugins = $this->get_plugins_array();

		$table = new Gdpr_Table_Builder(
			array(
				__( 'Plugin name', 'wp_gdpr' ),
				__( 'Plugin status', 'wp_gdpr' ),
				__( 'Personal data', 'wp_gdpr' ),
				__( 'WP-GDPR add-on status', 'wp_gdpr' )
			),
			$plugins
			, array() );

		$table->print_table();
	}

	/**
	 * @return array|bool|mixed|object|string
	 */
	public function get_plugins_array() {
		if ( is_file( GDPR_DIR . 'assets/json/plugins.json' ) ) {
			$plugins = file_get_contents( GDPR_DIR . 'assets/json/plugins.json' );
			$plugins = json_decode( $plugins, true );
		} else {
			$plugins = array();
		}

		$plugins = $this->filter_plugins( $plugins );

		return $plugins;
	}

	/**
	 * @param array $plugins
	 *
	 * @return array
	 */
	public function filter_plugins( $plugins ) {

		return array_map( function ( $data ) {
			$status_active  = " ";
			$status_wp_gdpr = "";
			$all_plugins    = get_plugins();
			if ( isset( $data['name'], $data['data_stored_in'] ) ) {
				if ( is_plugin_active( $data['plugin_name'] ) === true ) {
					$status_active = 'Active';
				} else {
					$status_active = 'Inactive';
				}
				if ( isset( $all_plugins[ $data['plugin_wp_gdpr'] ] ) ) {
					if ( is_plugin_active( $data['plugin_wp_gdpr'] ) === true ) {
						$status_wp_gdpr = '<p class="wp-gdpr_active"><b>Active</b></p>';
					} else {
						$status_wp_gdpr = '<p class="wp-gdpr_inactive"><b>Inactive</b></p>';
					}
				} else {
					$status_wp_gdpr = "<a class='wp-gdpr_get_add_on' target='_blank' href='" . $data['plugin_link'] . " '><b>Get add-on</b></a>";
				}

				return array(
					$data['name'],
					$status_active,
					$data['data_stored_in'],
					$status_wp_gdpr
				);
			} else {
				if ( empty( $plugin_data['name'] ) ) {
					return array();
				} else {
					return array( 'empty' );
				}
			}

		}, $plugins );

	}

	/**
	 * Load all admin styles
	 *
	 * @param $hook
	 *
	 * @since   1.5.0
	 */
	public function admin_style( $hook ) {
		switch ( $hook ) {
			case 'wp-gdpr_page_help':
				wp_enqueue_style( 'gdpr-slider', GDPR_URL . 'assets/css/slick.css' );
				$this->log->info( 'Admin styles are loaded gdpr-slider for hook -> ' . $hook );
				wp_enqueue_style( 'gdpr-admin-css', GDPR_URL . 'assets/css/admin.css' );
				$this->log->info( 'Admin styles are loaded gdpr-admin-css for hook -> ' . $hook );
				wp_enqueue_style( 'gdpr-theme-slick', GDPR_URL . 'assets/css/slick-theme.css' );
				$this->log->info( 'Admin styles are loaded gdpr-theme-slick for hook -> ' . $hook );
				break;
			case 'toplevel_page_wp_gdpr':
			case 'wp-gdpr_page_addon':
			case 'wp-gdpr_page_deletelist':
			case 'wp-gdpr_page_datareg':
			case 'wp-gdpr_page_pluginlist':
			case 'wp-gdpr_page_settings_wp-gdpr':
				wp_enqueue_style( 'gdpr-admin-css', GDPR_URL . 'assets/css/admin.css' );
				$this->log->info( 'Admin styles are loaded gdpr-admin-css default case for hook -> ' . $hook );
				break;
		}
	}

	/**
	 * Dynamic save functions for settings
	 *
	 * @since 1.5.0
	 */
	public function save_settings() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_REQUEST['gdpr_save_global_settings'] ) ) {
			$this->log->info( 'Saved DPO settings' );
			$settings = $this->get_settings();
			foreach ( $settings as $option_name => $setting ) {
				switch ( $setting['type'] ) {
					case 'checkbox':
						$value = isset( $_REQUEST[ $option_name ] ) && sanitize_text_field( $_REQUEST[ $option_name ] ) === 'on' ? 1 : 0;
						break;
					case 'text':
						$value = isset( $_REQUEST[ $option_name ] ) && sanitize_text_field( $_REQUEST[ $option_name ] ) ? $_REQUEST[ $option_name ] : '';
						break;
					case 'email':
						$value = isset( $_REQUEST[ $option_name ] ) && sanitize_email( $_REQUEST[ $option_name ] ) ? $_REQUEST[ $option_name ] : '';
						break;
				}
				update_option( $option_name, $value );
			}
			$this->set_notice( __( 'Settings saved', 'wp_gdpr' ) );
		}
	}
}
