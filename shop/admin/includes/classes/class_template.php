<?php
/* ----------------------------------------------------------------------
   $Id: class_template.php 305 2013-04-14 21:36:08Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

/**
 * @see libs/Smarty/Smarty.class.php
 * @link http://smarty.net
 */
require_once OOS_ABSOLUTE_PATH . '/includes/lib/smarty/libs/Smarty.class.php';

/**
 * Smarty class
 *
 * @package myOOS
 * @subpackage myOOS_Smarty
 * @see Smarty, libs/Smarty/Smarty.class.php
 * @link http://smarty.net/manual/en/
 */
class myOOS_Smarty extends Smarty 
{

	function trigger_error($error_msg, $error_type = E_USER_WARNING)
	{
		throw new SmartyException($error_msg);
	}

	public function __construct()
	{

		// Class Constructor.
		// These automatically get set with each new instance.

        parent::__construct();

		$this->left_delimiter =  '{';
		$this->right_delimiter =  '}';

		$dir = OOS_ABSOLUTE_PATH . OOS_ADMIN;
		if (substr($dir, -1) != "/")
		{
			$dir = $dir."/";
		}

        $this->setTemplateDir($dir . 'themes/');
        $this->setCompileDir($dir . 'temp/templates_c/');
        $this->setCacheDir($dir . 'temp/cache/');
		

		// set multiple directoríes where plugins are stored
		$this->setPluginsDir(array(
			OOS_ABSOLUTE_PATH . '/includes/lib/smarty/libs/plugins',
			OOS_ABSOLUTE_PATH . OOS_ADMIN . '/includes/lib/smarty-plugins'
		));


		$this->use_sub_dirs = false;
		$this->error_reporting = E_ALL & ~E_NOTICE; 
		
    }
}
