<?php

namespace wp_gdpr\config\custom;

class Text_Config {

//Privacy Center page filters
	public static function filter_wp_comments() {
		echo apply_filters( 'wp_gdpr_wp_comments', __( 'WordPress Comments', 'wp_gdpr' ) );
	}

	public static function filter_wp_condolance_manager() {
		echo apply_filters( 'wp_gdpr_wp_condolance_manager', __( 'Condolence Manager', 'wp_gdpr' ) );
	}

	public static function filter_wp_cfdb7() {
		echo apply_filters( 'wp_gdpr_wp_cfdb7', __( 'Contact Form DB7', 'wp_gdpr' ) );
	}

	public static function filter_wp_flamingo() {
		echo apply_filters( 'wp_gdpr_wp_flamingo', __( 'Flamingo', 'wp_gdpr' ) );
	}

	public static function filter_wp_mailchimp() {
		echo apply_filters( 'wp_gdpr_wp_mailchimp', __( 'Mailchimp', 'wp_gdpr' ) );
	}

	public static function filter_wp_grafity_form() {
		echo apply_filters( 'wp_gdpr_wp_grafity_form', __( 'Gravity Forms', 'wp_gdpr' ) );
	}

	public static function filter_wp_woocommerce() {
		echo apply_filters( 'wp_gdpr_wp_woocommerce', __( 'WooCommerce', 'wp_gdpr' ) );
	}
}