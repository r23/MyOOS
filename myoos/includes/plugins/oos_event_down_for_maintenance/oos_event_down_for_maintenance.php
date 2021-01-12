<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_down_for_maintenance.php,v 1.1 2007/06/08 15:02:12 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2021 by the MyOOS Development Team.
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

  class oos_event_down_for_maintenance {

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
    public function __construct() {

      $this->name          = PLUGIN_EVENT_DOWN_FOR_MAINTENANCE_NAME;
      $this->description   = PLUGIN_EVENT_DOWN_FOR_MAINTENANCE_DESC;
      $this->uninstallable = TRUE;
      $this->author        = 'MyOOS Development Team';
      $this->version       = '1.0';
      $this->requirements  = array(
                               'oos'         => '1.5.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '5.9.0'
      );
    }

    static function create_plugin_instance() {

		$aContents = oos_get_content();
	  
	    $bRedirect = TRUE;
		if ($_GET['content'] == $aContents['info_down_for_maintenance']) {
			$bRedirect = FALSE;
		}
		// newsletter
		if ($_GET['content'] == $aContents['newsletter']) {
			$bRedirect = FALSE;
		}
		// imprint 
		if ($_GET['content'] == $aContents['information']) {
			$bRedirect = FALSE;
		}	 
	  
		if ($bRedirect == TRUE) {
			oos_redirect(oos_href_link($aContents['info_down_for_maintenance'], '', TRUE, FALSE));
		}

      return TRUE;
    }

    function install() {
      return TRUE;
    }

    function remove() {
      return TRUE;
    }

    function config_item() {
      return FALSE;
    }
  }


