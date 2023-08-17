<?php
/**
   ----------------------------------------------------------------------
   $Id: oos_event_category_path.php,v 1.1 2007/06/07 17:29:24 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class oos_event_category_path
{
    public $name = PLUGIN_EVENT_CATEGORY_PATH_NAME;
    public $description = PLUGIN_EVENT_CATEGORY_PATH_DESC;
    public $uninstallable = false;
    public $depends;
    public $preceeds;
    public $author = 'MyOOS Development Team';
    public $version = '1.0';
    public $requirements = array(
                         'oos'         => '1.5.0',
                         'smarty'      => '2.6.9',
                         'adodb'       => '4.62',
                         'php'         => '5.9.0'
    );


    /**
     *  class constructor
     */
    public function __construct()
    {
    }

    public static function create_plugin_instance()
    {
        global $sCategory, $aCategoryPath, $nCurrentCategoryID;

        include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_category_tree.php';

        if (isset($_GET['category'])) {
            $sCategory = filter_string_polyfill(filter_input(INPUT_GET, 'category'));
        } elseif (isset($_GET['products_id']) && !isset($_GET['manufacturers_id'])) {
            $sProductsId = filter_string_polyfill(filter_input(INPUT_GET, 'products_id'));
            $sCategory = oos_get_product_path($sProductsId);
        } else {
            $sCategory = '';
        }

        if (!empty($sCategory)) {
            $aCategoryPath = oos_parse_category_path($sCategory);
            $sCategory = implode('_', $aCategoryPath);

            $nCurrentCategoryID = end($aCategoryPath);
        } else {
            $nCurrentCategoryID = 0;
        }

        return true;
    }

    public function install()
    {
        return false;
    }

    public function remove()
    {
        return false;
    }

    public function config_item()
    {
        return false;
    }
}
