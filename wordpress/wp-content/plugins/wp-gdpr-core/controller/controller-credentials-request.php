<?php

namespace wp_gdpr\controller;

use wp_gdpr\lib\Gdpr_Container;
use wp_gdpr\lib\Gdpr_Language;

class Controller_Credentials_Request {

	/**
	 * Controller_Credentials_Request constructor.
	 */
	public function __construct( $name ) {
		$this->add_form_shortcode( $name );

	}

	/**
	 * add shortcode to show form ( allows to send request for users )
	 *
	 * @param $name
	 */
	public function add_form_shortcode( $name ) {
		add_shortcode( $name, array( $this, 'get_form_html' ) );
	}

	/**
	 * @return string
	 */
	public function get_form_html() {
	    // get gdpr string
        $controller_menu_page = new \wp_gdpr\controller\Controller_Menu_Page();
        $privacy_policy_strings = $controller_menu_page->get_privacy_policy_strings();

        // get language
        $pieces = new Gdpr_Language();
        $pieces = $pieces->get_language();

        // Request page filters
        $submit_custom_text = apply_filters( 'wp-gdpr-submit-text', __("Submit", 'wp_gdpr') );
        $warning_custom_text = apply_filters( 'wp-gdpr-warning-text', __("Warning:", 'wp_gdpr') );
        $link_custom_text = apply_filters( 'wp-gdpr-link-text', __("This link will become deprecated after 48 hours.", 'wp_gdpr') );
//
//		//Privacy Cneter page filters
//        $wp_comments = apply_filters('wp_gdpr_wp_comments', __('WordPress Comments', 'wp_gdpr' ) );
//        $wp_condolance_manager = apply_filters('wp_gdpr_wp_condolance_manager', __('Condolence manager', 'wp_gdpr' ) );



		ob_start();
		include_once GDPR_DIR . 'view/front/form.php';
		return ob_get_clean();
	}


}
