<?php
/**
   ----------------------------------------------------------------------
   $Id: oos_event_down_for_maintenance.php,v 1.1 2007/06/08 15:02:12 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
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

class oos_event_down_for_maintenance
{
    public $name = PLUGIN_EVENT_DOWN_FOR_MAINTENANCE_NAME;
    public $description = PLUGIN_EVENT_DOWN_FOR_MAINTENANCE_DESC;
    public $uninstallable = true;
    public $depends;
    public $preceeds;
    public $author = 'MyOOS Development Team';
    public $version = '1.0';
    public $requirements = ['oos'         => '1.5.0', 'smarty'      => '2.6.9', 'adodb'       => '4.62', 'php'         => '5.9.0'];


    /**
     *  class constructor
     */
    public function __construct()
    {
    }

    public static function create_plugin_instance()
    {
        $aContents = oos_get_content();

        $bRedirect = true;
        if ($_GET['content'] == $aContents['info_down_for_maintenance']) {
            $bRedirect = false;
        }
        // newsletter
        if ($_GET['content'] == $aContents['newsletter']) {
            $bRedirect = false;
        }
        // imprint
        if ($_GET['content'] == $aContents['information']) {
            $bRedirect = false;
        }

        if ($bRedirect == true) {
            oos_redirect(oos_href_link($aContents['info_down_for_maintenance'], '', true, false));
        }

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
