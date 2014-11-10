<?php
/* ----------------------------------------------------------------------
   $Id: class_object_info.php,v 1.1 2007/06/08 14:58:10 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: object_info.php,v 1.5 2002/01/30 01:14:20 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  class objectInfo {

// class constructor
    function objectInfo($aObject) {
      foreach ($aObject as $key => $value) {
        $this->$key = $value;
      }
    }
  }
?>