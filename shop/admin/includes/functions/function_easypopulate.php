<?php
/* ----------------------------------------------------------------------
   $Id: function_easypopulate.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: easypopulate.php,v 2.75 2005/04/05 AL Exp 
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
  * Easypopulate
  *
  * @link http://www.oos-shop.de/
  * @package Easypopulate
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:02:48 $
  */

 /**
  *
  */ 
  function ep_get_languages() {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =&oosDBGetTables();

    $ep_languages_array = array();

    $query = "SELECT languages_id, iso_639_2
              FROM " . $oostable['languages'] . "
              WHERE status = '1'
              ORDER BY sort_order";
    $result = $dbconn->Execute($query);

    // start array at one, the rest of the code expects it that way
    $ll =1;
    while ($ep_languages = $result->fields) {
      //will be used to return language_id en language code to report in product_name_code instead of product_name_id
      $ep_languages_array[$ll++] = array('id' => $ep_languages['languages_id'],
                                         'code' => $ep_languages['iso_639_2']
                                         );
      // Move that ADOdb pointer!
      $result->MoveNext();

    }

    // Close result set
    $result->Close();

    return $ep_languages_array;
  }


 /**
  *
  */
  function oos_get_tax_class_rate($tax_class_id) { 

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $tax_multiplier = 0;
    $query = "SELECT SUM(tax_rate) as tax_rate
              FROM " . $oostable['tax_rates'] . "
              WHERE  tax_class_id = '" . $tax_class_id . "'
              GROUP BY tax_priority";
    $result = $dbconn->Execute($query);

    if ($result->RecordCount()) {
      while ($tax = $tax_result->fields) {
        $tax_multiplier += $tax['tax_rate'];

        // Move that ADOdb pointer!
        $result->MoveNext();
      }
    }

    // Close result set
    $result->Close();

    return $tax_multiplier;
  }


 /**
  *
  */
  function oos_get_tax_title_class_id($tax_class_title) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT tax_class_id
              FROM " . $oostable['tax_class'] . "
              WHERE tax_class_title = '" . $tax_class_title . "'";
    $result = $dbconn->Execute($query);

    $tax_class_array = $result->fields;
    $tax_class_id = $tax_class_array['tax_class_id'];

    // Close result set
    $result->Close();

    return $tax_class_id ;
  }


 /**
  *
  */
  function print_el( $item2 ) {
    echo " | " . substr(strip_tags($item2), 0, 10);
  }


 /**
  *
  */
  function print_el1( $item2 ) {
    echo sprintf("| %'.4s ", substr(strip_tags($item2), 0, 80));
  }

?>