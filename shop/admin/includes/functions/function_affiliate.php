<?php
/* ----------------------------------------------------------------------
   $Id: function_affiliate.php,v 1.1 2007/06/08 14:02:48 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_functions.php,v 1.5 2003/02/17 15:01:47 harley_vb  
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

 /**
  * Affiliate
  *
  * @link http://www.oos-shop.de/
  * @package Affiliate
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:02:48 $
  */

 /**
  * Returns the tax rate for a zone / class
  *
  * @param $class_id
  * @param $country_id
  * @param $zone_id
  * @return string
  */
  function oos_get_affiliate_tax_rate($class_id, $country_id, $zone_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $tax_ratestable = $oostable['tax_rates'];
    $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
    $geo_zonestable = $oostable['geo_zones'];
    $query = "SELECT SUM(tax_rate) AS tax_rate
              FROM $tax_ratestable tr
              LEFT JOIN $zones_to_geo_zonestable za
                     ON tr.tax_zone_id = za.geo_zone_id
              LEFT JOIN $geo_zonestable tz
                     ON tz.geo_zone_id = tr.tax_zone_id
              WHERE (za.zone_country_id IS NULL OR
                     za.zone_country_id = '0' OR
                     za.zone_country_id = '" . $country_id . "')
                AND (za.zone_id IS NULL OR
                     za.zone_id = '0' OR
                     za.zone_id = '" . $zone_id . "')
                AND tr.tax_class_id = '" . $class_id . "' 
           GROUP BY tr.tax_priority";
    $result =& $dbconn->Execute($query);

    if ($result->RecordCount()) {
      $tax_multiplier = 0;
      while ($tax = $result->fields) {
        $tax_multiplier += $tax['tax_rate'];

        // Move that ADOdb pointer!
        $result->MoveNext();
      }

      // Close result set
      $result->Close();

      return $tax_multiplier;
    } else {
      return 0;
    }
  }

?>