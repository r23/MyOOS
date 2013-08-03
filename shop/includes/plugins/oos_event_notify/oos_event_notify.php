<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_notify.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class oos_event_notify {

    var $name;
    var $description;
    var $uninstallable;
    var $depends;
    var $preceeds = 'session';
    var $author;
    var $version;
    var $requirements;


   /**
    *  class constructor
    */
    function oos_event_notify() {

      $this->name          = PLUGIN_EVENT_NOTIFY_NAME;
      $this->description   = PLUGIN_EVENT_NOTIFY_DESC;
      $this->uninstallable = true;
      $this->preceeds      = 'session';
      $this->author        = 'OOS Development Team';
      $this->version       = '1.0';
      $this->requirements  = array(
                               'oos'         => '1.8.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {
      return true;
    }


    function install() {
      return true;
    }

    function remove() {
      return true;
    }

    function config_item() {
      return false;
    }
  }

?>
