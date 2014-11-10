<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_spell.php,v 1.1 2007/06/13 15:41:56 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );


  class oos_event_spell {

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
    function oos_event_spell() {

      $this->name          = PLUGIN_EVENT_SPELL_NAME;
      $this->description   = PLUGIN_EVENT_SPELL_DESC;
      $this->uninstallable = true;
      $this->author        = 'OOS Development Team';
      $this->version       = '2.0';
      $this->requirements  = array(
                               'oos'         => '1.7.0',
                               'smarty'      => '2.6.11',
                               'adodb'       => '4.80',
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
