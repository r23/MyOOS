<?php

namespace wp_gdpr\lib;

use wp_gdpr\model\Request_Form;

class Gdpr_Email {

	/**
	 * Sends requester email to check their data
	 *
	 * @param $data_to_process
	 * @param $processed_data
	 *
	 * @since 1.6.0
	 */
	public static function send_confirmation_email_to_requester( $data_to_process, $processed_data ) {
		$to      = $data_to_process['email'];
		$subject = __( 'We confirm your data deletion request', 'wp_gdpr' );
		$content = static::get_delete_confirmation_email_content( $data_to_process, $processed_data );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		wp_mail( $to, $subject, $content, $headers );
	}

	/**
	 * Sends email to requester to confirm data delete
	 *
	 * @param $comment_to_delete    array
	 * @param $processed_data       array
	 *
	 * @return string
	 *
	 * @since 1.6.0
	 */
	public static function get_delete_confirmation_email_content( $comment_to_delete, $processed_data ) {
		ob_start();
		$date_of_request = $comment_to_delete['timestamp'];

		if ( static::is_email_template_overriden_in_theme( 'delete-confirmation-email.php' ) ) {
			include static::load_email_template_from_theme( 'delete-confirmation-email.php' );
		} else {
			include GDPR_DIR . 'view/email/delete-confirmation-email.php';
		}

		$email_template = ob_get_clean();

		/**
		 *  Gives an option to developers to create their own mail template for delete confirmation.
		 *
		 *  Parameters:
		 *  string  Email template
		 *  string  Timestamp
		 *  array   Processed data
		 */
		return apply_filters( 'wp_gdpr_delete_confirmation', $email_template, $date_of_request, $processed_data );
	}

	/**
	 * The dpo gets emailed about the data deletion
	 *
	 * @param $data_to_process
	 * @param $processed_data
	 *
	 * @since 1.6.0
	 */
	public static function send_confirmation_email_to_dpo( $data_to_process, $processed_data ) {
		$to      = Gdpr_Options_Helper::get_dpo_email();
		$subject = __( 'Confirmation data deletion', 'wp_gdpr' );
		$content = static::get_delete_confirmation_email_content_for_dpo( $data_to_process, $processed_data );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		wp_mail( $to, $subject, $content, $headers );
	}

	/**
	 * Sends email to dpo to confirm data delete
	 *
	 * @param $comment_to_delete    array
	 * @param $processed_data       array
	 *
	 * @return string
	 *
	 * @since 1.6.0
	 */
	public static function get_delete_confirmation_email_content_for_dpo( $comment_to_delete, $processed_data ) {
		ob_start();
		$date_of_request = $comment_to_delete['timestamp'];

		if ( static::is_email_template_overriden_in_theme( 'delete-confirmation-email-dpo.php' ) ) {
			include static::load_email_template_from_theme( 'delete-confirmation-email-dpo.php' );
		} else {
			include GDPR_DIR . 'view/email/delete-confirmation-email-dpo.php';
		}

		$email_template = ob_get_clean();

		/**
		 *  Gives an option to developers to create their own mail template for delete confirmation.
		 *
		 *  Parameters:
		 *  string  Email template
		 *  string  Timestamp
		 *  array   Processed data
		 */
		return apply_filters( 'wp_gdpr_delete_confirmation_dpo', $email_template, $date_of_request, $processed_data );
	}

	/**
	 * Sends new delete request email to admin
	 *
	 * @param $requested_email
	 *
	 * @since 1.6.0
	 */
	public static function send_new_delete_request_email_to_admin( $requested_email ) {
		$site_name = get_bloginfo( 'name' );
		$subject   = '[' . $site_name . '] ' . __( 'New delete request', 'wp_gdpr' );
		$to        = Gdpr_Options_Helper::get_dpo_email();
		$content   = static::get_admin_new_delete_request_content( $requested_email );
		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $content, $headers );
	}

	/**
	 * Returns new delete request email content
	 *
	 * Admin gets an email when the requester ask for data delete
	 *
	 * @param $requested_email
	 *
	 * @return string
	 *
	 * @since 1.6.0
	 */
	public static function get_admin_new_delete_request_content( $requested_email ) {
		ob_start();

		if ( static::is_email_template_overriden_in_theme( 'admin-new-delete-request.php' ) ) {
			include static::load_email_template_from_theme( 'admin-new-delete-request.php' );
		} else {
			include GDPR_DIR . 'view/email/admin-new-delete-request.php';
		}

		$email_template = ob_get_clean();

		/**
		 * Gives an option to developers to create their own mail template for new delete request (admin).
		 *
		 * Parameters:
		 * string   Email template
		 * string   Requesters email
		 */
		return apply_filters( 'wp_gdpr_admin_new_delete_request', $email_template, $requested_email );
	}

	/**
	 * Sends email to requester
	 *
	 * @param $single_address
	 * @param $time_of_insertion
	 *
	 * @since 1.6.0
	 */
	public static function send_request_email_to_requester( $single_address, $time_of_insertion, $language ) {
		$to        = $single_address;
		$site_name = get_bloginfo( 'name' );
		$subject   = '[' . $site_name . '] ' . __( 'Your data request', 'wp_gdpr' );
		$content   = static::get_request_email_content( $single_address, $time_of_insertion, $language );
		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $content, $headers );
	}

	/**
	 * Returns request email template + content
	 *
	 * Requester receives an email when he/she asks for the data
	 *
	 * @param $email        string
	 * @param $timestamp    string|integer
	 *
	 * @return string content of email for requester
	 *
	 * @since 1.6.0
	 */
	public static function get_request_email_content( $email, $timestamp, $language = 'en' ) {
		ob_start();
		$url = static::create_unique_url( $email, $timestamp );

		if ( static::is_email_template_overriden_in_theme( 'request-email.php' ) ) {
			include static::load_email_template_from_theme( 'request-email.php' );
		} else {
			include GDPR_DIR . 'view/email/request-email.php';
		}

		$email_template = ob_get_clean();

		/**
		 *  Gives an option to developers to create their own mail template for data request.
		 *
		 *  Parameters:
		 *  string  Email template
		 *  string  Email address
		 *  string  Url link to view the data
		 */
		return apply_filters( 'wp_gdpr_request_email', $email_template, $email, $url );
	}

	/**
	 * Sends email to DPO
	 *
	 * @param $single_address
	 * @param $time_of_insertion
	 *
	 * @since 1.6.0
	 */
	public static function send_request_email_to_dpo( $single_address, $time_of_insertion, $language ) {
		$to        = $single_address;
		$site_name = get_bloginfo( 'name' );
		$subject   = '[' . $site_name . '] ' . __( 'New data request', 'wp_gdpr' );
		$content   = static::get_request_email_dpo_content( $single_address, $time_of_insertion, $language );
		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $content, $headers );
	}

	/**
	 * Returns email template + content
	 *
	 * DPO gets an email when the requester ask for the data
	 *
	 * @param $email
	 * @param $timestamp
	 * @param string $language
	 *
	 * @return string content of request email for dpo
	 *
	 * @since 1.6.0
	 */
	public static function get_request_email_dpo_content( $email, $timestamp, $language = 'en' ) {
		ob_start();
		$url = admin_url() . '?page=wp_gdpr&page_type=datarequest';

		if ( static::is_email_template_overriden_in_theme( 'request-email-dpo.php' ) ) {
			include static::load_email_template_from_theme( 'request-email-dpo.php' );
		} else {
			include GDPR_DIR . 'view/email/request-email-dpo.php';
		}

		$email_template = ob_get_clean();

		/**
		 *  Gives an option to developers to create their own mail template for data request.
		 *
		 *  Parameters:
		 *  string  Email template for dpo
		 *  string  Email address
		 *  string   Url link to view the data
		 */
		return apply_filters( 'wp_gdpr_request_email_dpo', $email_template, $email, $url );
	}

	/**
	 * Returns true if the email template exists in the theme
	 *
	 * @param $path
	 *
	 * @return bool
	 *
	 * @since 1.6.0
	 */
	private static function is_email_template_overriden_in_theme( $path ) {
		if ( is_file( get_template_directory() . DIRECTORY_SEPARATOR . GDPR_BASE_NAME . DIRECTORY_SEPARATOR . 'email' . DIRECTORY_SEPARATOR . $path ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Load email template from the theme
	 *
	 * @param $path
	 *
	 * @since 1.6.0
	 */
	private static function load_email_template_from_theme( $path ) {
		return get_template_directory() . DIRECTORY_SEPARATOR . GDPR_BASE_NAME . DIRECTORY_SEPARATOR . 'email' . DIRECTORY_SEPARATOR . $path;
	}

	/**
	 * @param $email
	 * @param $timestamp
	 *
	 * @return string
	 * create url
	 * encode gdpr#example@email.com into base64
	 *
	 * @since 1.6.0
	 */
	public static function create_unique_url( $email, $timestamp ) {
		return Request_Form::get_personal_data_page_url( '?req=' . base64_encode( 'gdpr#' . $email . '#' . base64_encode( $timestamp ) ) );
	}
}