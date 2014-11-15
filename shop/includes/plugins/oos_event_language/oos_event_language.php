<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_language.php,v 1.1 2007/06/12 17:11:55 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2005 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  class oos_event_language {

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
    function oos_event_language() {

      $this->name          = PLUGIN_EVENT_LANGUAGE_NAME;
      $this->description   = PLUGIN_EVENT_LANGUAGE_DESC;
      $this->uninstallable = FALSE;
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
      global $oLang, $aLang;

      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $aContents = oos_get_content();

      if (!isset($_SESSION['language']) || isset($_GET['language'])) {
        // include the language class
        include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_language.php';
        $oLang = new language;

        if (isset($_GET['language']) && oos_is_not_null($_GET['language'])) {
          $oLang->set($_GET['language']);
        } else {
          $oLang->get_browser_language();
        }
      }

      $sLanguage = isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;
      include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '.php';

      return true;

    }

    function install() {
      return FALSE;
    }

    function remove() {
      return FALSE;
    }

    function config_item() {
      return FALSE;
    }
  }


