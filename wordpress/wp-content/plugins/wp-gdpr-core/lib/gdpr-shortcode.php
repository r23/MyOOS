<?php

namespace wp_gdpr\lib;

class Gdpr_Shortcode {
	/**
	 * shortcode arguments
	 */
	protected $shortcode_arguments;
	/**
	 * shortcode name
	 */
	protected $shortcode_name;
	/**
	 * content of shortcode
	 */
	protected $content;

	/**
	 * allows to register shortcode name and arguments
	 */
	public function __construct( $args ) {
		extract( $args );

		if ( isset( $arguments ) ) {
			$this->shortcode_arguments = $arguments;
		}
		if ( isset( $name ) ) {
			$this->shortcode_name = $name;
		}
	}

	/**
	 * add content that should be showd in shortcode
	 */
	public function add_content( $content ) {
		$this->content = $content;
	}

	/**
	 * register shortcode
	 */
	public function register_shortcode() {

		add_shortcode( $this->shortcode_name, array( $this, 'get_content' ) );
	}

	/**
	 * get content for shortcode
	 */
	public function get_content() {
		return $this->content;
	}

}
