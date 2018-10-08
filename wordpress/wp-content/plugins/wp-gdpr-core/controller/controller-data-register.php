<?php

namespace wp_gdpr\controller;

use wp_gdpr\lib\Gdpr_Data_Register_List_Table;
use wp_gdpr\model\Data_Register_Model;

/**
 * This class is used to communicate data register menu with database and wp list table class.
 *
 * Class Controller_Data_Register
 * @package wp_gdpr\controller
 *
 * @since 1.6.0
 */
class Controller_Data_Register {

	/**
	 * @var \wp_gdpr\model\Data_Register_Model;
	 *
	 * @since 1.6.0
	 */
	private $data_register_model;

	/**
	 * Controller_Data_Register constructor.
	 *
	 * @since 1.6.0
	 */
	public function __construct() {
		$this->data_register_model = Data_Register_Model::instance();
	}

	/**
	 * Displays data register data in a table
	 *
	 * @since 1.6.0
	 */
	public function display() {
		$list_table = new Gdpr_Data_Register_List_Table(array(), $this->data_register_model);
		$list_table->prepare_items();

		$list_table->display();
	}
}