<?php
namespace wp_gdpr\lib;

class Gdpr_Log_Interface {

	/**
	 * @var \wp_gdpr\lib\Gdpr_Log
	 */
	protected $log;

	public function __construct() {
		$this->log = Gdpr_Log::instance();
	}

}