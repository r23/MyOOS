<?php
/**
   ----------------------------------------------------------------------
   $Id: oos_event_notify.php,v 1.1 2007/06/12 17:11:55 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class oos_event_notify
{
    public $name = PLUGIN_EVENT_NOTIFY_NAME;
    public $description = PLUGIN_EVENT_NOTIFY_DESC;
    public $uninstallable = true;
    public $depends;
    public $preceeds = 'session';
    public $author = 'MyOOS Development Team';
    public $version = '1.0';
    public $requirements = ['oos'         => '1.8.0', 'smarty'      => '2.6.9', 'adodb'       => '4.62', 'php'         => '5.9.0'];


    /**
     *  class constructor
     */
    public function __construct()
    {
    }

    public static function create_plugin_instance()
    {
        return true;
    }


    public function install()
    {
        return true;
    }

    public function remove()
    {
        return true;
    }

    public function config_item()
    {
        return false;
    }
}
