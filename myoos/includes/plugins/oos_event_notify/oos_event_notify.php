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
    public $name;
    public $description;
    public $uninstallable;
    public $depends;
    public $preceeds = 'session';
    public $author;
    public $version;
    public $requirements;


    /**
     *  class constructor
     */
    public function __construct()
    {
        $this->name          = PLUGIN_EVENT_NOTIFY_NAME;
        $this->description   = PLUGIN_EVENT_NOTIFY_DESC;
        $this->uninstallable = true;
        $this->preceeds      = 'session';
        $this->author        = 'MyOOS Development Team';
        $this->version       = '1.0';
        $this->requirements  = array(
                             'oos'         => '1.8.0',
                             'smarty'      => '2.6.9',
                             'adodb'       => '4.62',
                             'php'         => '5.9.0'
        );
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
