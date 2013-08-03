<?php
/* ----------------------------------------------------------------------
   $Id: function_spezials.php 425 2013-06-16 07:05:28Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.231 2003/07/09 01:15:48 hpdl
         general.php,v 1.212 2003/02/17 07:55:54 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


 /**
  * Sets the status of a special product
  *
  * @param $specials_id
  * @param $status
  */
  function oos_set_specials_status($nSpecialsId, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $specialstable = $oostable['specials'];
    return $dbconn->Execute("UPDATE $specialstable
                             SET status = '" . oos_db_input($status) . "',
                                 date_status_change = now()
                              WHERE specials_id = '" . intval($nSpecialsId) . "'");
  }


 /**
  * Auto expire products on special
  */
  function oos_expire_spezials() {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $specialstable = $oostable['specials'];
    $query = "SELECT specials_id
              FROM $specialstable
              WHERE status = '1' 
                AND now() >= expires_date
                AND expires_date > 0";
    if (USE_DB_CACHE == 'true') {
      $result = $dbconn->CacheExecute(3600, $query);
    } else {
      $result = $dbconn->Execute($query);
    }
    if (!$result) {return;}

    if ($result->RecordCount() > 0) {
      while ($specials = $result->fields) {
        oos_set_specials_status($specials['specials_id'], '0');

        // Move that ADOdb pointer!
        $result->MoveNext();
      }

      // Close result set
      $result->Close();
    }
  }

