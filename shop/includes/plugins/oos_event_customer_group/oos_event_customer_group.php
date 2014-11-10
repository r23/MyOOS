<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_customer_group.php,v 1.1 2007/06/07 17:29:24 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  class oos_event_customer_group {

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
    function oos_event_customer_group() {

      $this->name         = PLUGIN_EVENT_CUSTOMER_GROUP_NAME;
      $this->description  = PLUGIN_EVENT_CUSTOMER_GROUP_DESC;
      $this->uninstallable = false;
      $this->author       = 'OOS Development Team';
      $this->version      = '1.0';
      $this->requirements = array(
                              'oos'         => '1.5.0',
                              'smarty'      => '2.6.9',
                              'adodb'       => '4.62',
                              'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {

      if (!isset($_SESSION['member'])) {
        $_SESSION['member'] = new oosMember;
        $_SESSION['member']->default_member();
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

?>
