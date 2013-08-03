<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_breadcrumb.php 439 2013-06-24 22:47:03Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class oos_event_breadcrumb {

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
    function oos_event_breadcrumb() {

      $this->name          = PLUGIN_EVENT_BREADCRUMB_NAME;
      $this->description   = PLUGIN_EVENT_BREADCRUMB_DESC;
      $this->uninstallable = false;
      $this->author        = 'OOS Development Team';
      $this->version       = '2.0';
      $this->requirements  = array(
                               'oos'         => '1.7.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {
      global $oBreadcrumb, $aLang, $aCategoryPath;

      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $aContents = oos_get_content();
      

      // include the breadcrumb class and start the breadcrumb trail
      include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_breadcrumb.php';
      $oBreadcrumb = new breadcrumb;

      $oBreadcrumb->add($aLang['header_title_top'], oos_href_link($aContents['main']));

      // add category names or the manufacturer name to the breadcrumb trail
      if (isset($aCategoryPath) && (count($aCategoryPath) > 0)) {
        $nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : DEFAULT_CUSTOMERS_STATUS_ID;
        for ($i=0, $n=count($aCategoryPath); $i<$n; $i++) {
          $categories_descriptiontable = $oostable['categories_description'];
          $categories_sql = "SELECT categories_name
                             FROM $categories_descriptiontable
                             WHERE categories_id = '" . intval($aCategoryPath[$i]) . "'
                             AND categories_languages_id = '" .  intval($nLanguageID) . "'";
          $categories = $dbconn->Execute($categories_sql);
          if ($categories->RecordCount() > 0) {
            $oBreadcrumb->add($categories->fields['categories_name'], oos_href_link($aContents['shop'], 'category=' . implode('_', array_slice($aCategoryPath, 0, ($i+1)))));
          } else {
            break;
          }
        }
      } elseif (isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) {
        $manufacturers_id = intval($_GET['manufacturers_id']);
        $manufacturerstable = $oostable['manufacturers'];
        $manufacturers_sql = "SELECT manufacturers_name
                              FROM $manufacturerstable
                              WHERE manufacturers_id = '" . intval($manufacturers_id) . "'";
        $manufacturers = $dbconn->Execute($manufacturers_sql);

        if ($manufacturers->RecordCount() > 0) {
          $oBreadcrumb->add($aLang['header_title_catalog'], oos_href_link($aContents['shop']));
          $oBreadcrumb->add($manufacturers->fields['manufacturers_name'], oos_href_link($aContents['shop'], 'manufacturers_id=' . $_GET['manufacturers_id']));
        }
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


