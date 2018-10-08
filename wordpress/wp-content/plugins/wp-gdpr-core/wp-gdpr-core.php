<?php
/**
 * WP GDPR
 *
 * Make your website GDPR compliant and automate the process of handling personal data while integrating with plugins.
 *
 * @package   WP GDPR CORE
 * @author    AppSaloon
 * @license   GPLv2
 * @link      https://wp-gdpr.eu
 * @copyright 2018 wp-gdpr
 *
 * @wordpress-plugin
 * Plugin Name:       WP GDPR
 * Description:       Make your website GDPR compliant and automate the process of handling personal data while integrating with plugins.
 * Version:           2.0.8
 * Text Domain:       wp_gdpr
 * Domain Path:       /languages
 * Author:            AppSaloon
 * Author URI:        https://www.appsaloon.be
 */

namespace wp_gdpr;

define( 'GDPR_DIR', plugin_dir_path( __FILE__ ) );
define( 'GDPR_URL', plugin_dir_url( __FILE__ ) );
define( 'GDPR_BASE_NAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'GDPR_VERSION', '2.0.8' );



require_once GDPR_DIR . 'lib/gdpr-autoloader.php';

//include to register custom table on plugin activation
include_once GDPR_DIR . 'lib/gdpr-customtables.php';

//include functions to use outside of this plugin
include_once GDPR_DIR . 'lib/gdpr-functions.php';

include_once GDPR_DIR . 'lib/wpcli-custom-commands.php';


use wp_gdpr\config\Activation_Config;
use wp_gdpr\config\Deactivation_Config;
use wp_gdpr\lib\Gdpr_Container;
use wp_gdpr\lib\Gdpr_Customtables;
use wp_gdpr\lib\Session_Handler;
use wp_gdpr\lib\Gdpr_Log;
use wp_gdpr\model\Data_Register_Model;

class Wp_Gdpr_Core {

	const FORM_SHORTCODE_NAME = 'REQ_CRED_FORM';

	public $request_form_inputs;

	public function __construct() {
		//list of inputs in request form
		add_action( 'admin_init', array( new Gdpr_Customtables(), 'create_custom_tables' ) );
		$this->request_form_inputs = array(
			'email'         => 'required',
			'gdpr_req'      => 'required',
			'checkbox_gdpr' => 'required'
		);

		Session_Handler::start_session();
		$this->run();

		// clear flags when the plugin is deactivated
		register_deactivation_hook( __FILE__, array( new Deactivation_Config(), 'deactivate' ) );
	}

	public function run() {
		Gdpr_Container::make( 'wp_gdpr\config\Activation_Config' );
		Gdpr_Container::make( 'wp_gdpr\config\Startup_Config' );
		Gdpr_Container::make( 'wp_gdpr\controller\Controller_Credentials_Request', self::FORM_SHORTCODE_NAME );
		Gdpr_Container::make( 'wp_gdpr\controller\Controller_Comments' );
		Gdpr_Container::make( 'wp_gdpr\controller\Controller_Form_Submit', $this->request_form_inputs );
		Gdpr_Container::make( 'wp_gdpr\controller\Controller_Menu_Page' );
        Gdpr_Container::make( 'wp_gdpr\controller\Controller_Frontend' );
	}
}

new Wp_Gdpr_Core();
