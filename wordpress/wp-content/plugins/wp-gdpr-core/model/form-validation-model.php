<?php

namespace wp_gdpr\model;

/**
 * Class Form_Validation_Model
 * @package wp_gdpr\model
 * reusable form validation abstract class
 *
 * other class should  extend functions after successful and failure validation of submited data
 * function to extend
 * after_failure_validation
 * after_successful_validation
 */
abstract class Form_Validation_Model {
	/**
	 * @var list of inputs in form
	 */
	public $list_of_inputs;

	public function __construct( array $list_of_inputs, $hook = 'init' ) {
		$this->list_of_inputs = $list_of_inputs;
		if ( ! has_action( 'init', array( $this, 'post_request' ) ) ) {
			add_action( $hook, array( $this, 'post_request' ), 10 );
		}
	}

	/**
	 * validate submitted data via POST
	 */
	public function post_request() {
		$list_of_inputs = $this->list_of_inputs;

		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			return;
		}

		if ( ! $this->validate_data( $list_of_inputs ) ) {
			$this->after_failure_validation( $list_of_inputs );
			return;
		}

		$this->after_successful_validation( $list_of_inputs );
	}

	/**
	 * @param $list_of_inputs
	 *
	 * @return bool
	 * if You want to sanitize input add custom filter with name gdpr_sanitize_INPUTNAME
	 * this filter has to return false or true
	 */
	public function validate_data( $list_of_inputs ) {

		foreach ( $list_of_inputs as $key => $input ) {
			if ( 'required' == $input ) {
				//if variable doesnt exist
				if ( ! isset( $_REQUEST[ $key ] ) ) {
					return false;
				}
				//if is empty
				if ( empty( $_REQUEST[ $key ] ) ) {
					return false;
				}
			}

			//for every input we can add custom sanitation
			if ( ! apply_filters( 'gdpr_sanitize_' . $key, $_REQUEST[ $key ] ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param $list_of_inputs
	 * this function is extended in request_form model
	 */
	public function after_failure_validation( $list_of_inputs ) {
		//do something
	}

	/**
	 * @param $list_of_inputs
	 * this function is extended in request_form model
	 */
	public function after_successful_validation( $list_of_inputs ) {
		//do something
	}
}
