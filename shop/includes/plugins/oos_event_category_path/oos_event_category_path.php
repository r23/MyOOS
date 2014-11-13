<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_category_path.php,v 1.1 2007/06/07 17:29:24 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  class oos_event_category_path {

    var $name;
    var $description;
    var $uninstallable;
    var $depends;
    var $preceeds;
    var $author;
    var $version;
    var $requirements;


   /**
    *  class constructor
    */
    function oos_event_category_path() {

      $this->name          = PLUGIN_EVENT_CATEGORY_PATH_NAME;
      $this->description   = PLUGIN_EVENT_CATEGORY_PATH_DESC;
      $this->uninstallable = false;
      $this->author        = 'OOS Development Team';
      $this->version       = '1.0';
      $this->requirements  = array(
                               'oos'         => '1.5.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {
      global $cPath, $aCategoryPath, $nCurrentCategoryId;

      include 'includes/classes/class_category_tree.php';

      if (isset($_GET['cPath'])) {
        $cPath = oos_var_prep_for_os($_GET['cPath']);
      } elseif (isset($_GET['products_id']) && !isset($_GET['manufacturers_id'])) {
        $cPath = oos_get_product_path($_GET['products_id']);
      } else {
        $cPath = '';
      }

      if (!empty($cPath)) {
        $aCategoryPath = oos_parse_category_path($cPath);
        $cPath = implode('_', $aCategoryPath);

        $nCurrentCategoryId = end($aCategoryPath);
      } else {
        $nCurrentCategoryId = 0;
      }

      return true;
    }

    function install() {
      return false;
    }

    function remove() {
      return false;
    }

    function config_item() {
      return false;
    }
  }


