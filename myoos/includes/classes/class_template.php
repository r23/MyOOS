<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/**
 * @see  libs/Smarty/Smarty.class.php
 * @link http://smarty.net
 */

/**
 * Smarty class
 *
 * @package    myOOS
 * @subpackage myOOS_Smarty
 * @see        Smarty, libs/Smarty/Smarty.class.php
 * @link       http://smarty.net/manual/en/
 */
class myOOS_Smarty extends Smarty
{
    public function trigger_error($error_msg, $error_type = E_USER_WARNING)
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

        $dir = OOS_TEMP_PATH;
        if (substr($dir, -1) != "/") {
            $dir = $dir."/";
        }

        $this->setTemplateDir(MYOOS_INCLUDE_PATH . '/templates/')
            ->setCompileDir($dir . 'shop/templates_c/')
            ->setCacheDir($dir . 'shop/cache/');


        // set multiple directories where plugins are stored
        $this->setPluginsDir(
            array(
            MYOOS_INCLUDE_PATH . '/vendor/smarty/smarty/libs/plugins',
            MYOOS_INCLUDE_PATH . '/includes/lib/smarty-plugins'
            )
        );


        $this->use_sub_dirs = false;
    }
}
