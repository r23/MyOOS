<?php
/* ----------------------------------------------------------------------
   $Id: oos_counter.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: counter.php,v 1.5 2003/02/10 22:30:52 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

//counter
  $countertable = $oostable['counter'];
  $query = "SELECT startdate, counter
            FROM $countertable";
  $counter_result =& $dbconn->Execute($query);
  if (!$counter_result->RecordCount()) {
    $date_now = date('Ymd');
    $counter_now = 1;
    $countertable = $oostable['counter'];
    $query = "INSERT INTO $countertable
             (startdate,
              counter) VALUES (" . $dbconn->qstr($date_now) . ','
                                 . $dbconn->qstr($counter_now) . ")";
    $dbconn->Execute($query);
    $counter_startdate = $date_now;
  } else {
    $counter = $counter_result->fields;
    $counter_result->Close();

    $counter_startdate = $counter['startdate'];
    $counter_now = ($counter['counter'] + 1);

    $countertable = $oostable['counter'];
    $query = "UPDATE $countertable"
        . " SET counter = ?";
    $result =& $dbconn->Execute($query, array((int)$counter_now));
  }

  $counter_startdate_formatted = strftime(DATE_FORMAT_LONG, mktime(0, 0, 0, substr($counter_startdate, 4, 2), substr($counter_startdate, -2), substr($counter_startdate, 0, 4)));

  $oSmarty->assign(
      array(
          'counter_now' => $counter_now,
          'counter_startdate_formatted' => $counter_startdate_formatted
      )
  );

?>
