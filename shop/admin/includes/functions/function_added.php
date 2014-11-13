<?php
/* ----------------------------------------------------------------------
   $Id: function_added.php,v 1.1 2007/06/08 14:02:48 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   WebMakers.com Added: Additional Functions
   Written by Linda McGrath osCOMMERCE@WebMakers.com
   http://www.thewebmakerscorner.com

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

 /**
  * Return a product's manufacturer
  *
  * @param $products_id
  * @return string
  */
  function oos_get_manufacturers_name($product_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $manufacturerstable = $oostable['manufacturers'];
    $manufacturers_infotable = $oostable['manufacturers_info'];
    $productstable = $oostable['products'];
    $query = "SELECT m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url
              FROM $manufacturerstable m,
                   $manufacturers_infotable mi,
                   $productstable p
              WHERE p.products_id = '" . (int)$product_id . "'
                AND p.manufacturers_id = m.manufacturers_id
                AND mi.manufacturers_id = m.manufacturers_id";
    $result = $dbconn->Execute($query);

    $manufacturers_name = $result->fields['manufacturers_name'];

    // Close result set
    $result->Close();

    return $manufacturers_name;
  }



 /**
  * Return a product's 'base price' - the lowest attribute price is the 'base price'
  *
  * @param $products_id
  * @return string
  */
  function oos_get_products_base_price($products_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =&oosDBGetTables();

    $products_attributestable = $oostable['products_attributes'];
    $query = "SELECT options_id, price_prefix, options_values_price
              FROM $products_attributestable
              WHERE products_id = '" . (int)$products_id . "'
              ORDER BY options_id,price_prefix,options_values_price";
    $result = $dbconn->Execute($query);

    $count1 = 0;
    $the_options_id = 'x';
    $the_base_price = 0;

    while ( $product_att = $result->fields) {
      if ( $the_options_id != $product_att['options_id']){
        $the_options_id = $product_att['options_id'];
        $the_base_price += $product_att['options_values_price'];
      }
      $count1++;

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    return $the_base_price;
  }


 /**
  * Return the base price plus the special price
  *
  * @param $products_id
  * @return string
  */
   function oos_get_products_base_price_special_total($products_id) {
     if ( oos_get_products_special_price($products_id) > 0 ) { 
       $the_final_price = ( (oos_get_products_special_price($products_id) + oos_get_products_base_price($products_id)) );
     } else {
       $the_final_price = 0;
     }
     return $the_final_price;
   }


 /**
  * Return the base price plus the normal price
  *
  * @param $products_id
  * @return string
  */
   function oos_get_products_base_price_normal_total($products_id) {
     $the_final_price = ( (oosGetProductsNormalPrice($products_id) + oos_get_products_base_price($products_id)) );
     return $the_final_price;
   }


 /**
  * Return Products Special Price
  *
  * @param $nProductID
  * @return string
  */
  function oos_get_products_special_price($nProductID) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $specialstable = $oostable['specials'];
    $query = "SELECT specials_new_products_price
              FROM $specialstable
              WHERE products_id = '" . intval($nProductID) . "'
                AND status";
    $specials_new_products_price = $dbconn->GetOne($query);

    return $specials_new_products_price;
  }


 /**
  * Find a Categories Name
  *
  * @param $who_am_i
  * @return string
  */
  function oos_get_categories_name($who_am_i) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_name
              FROM $categories_descriptiontable
              WHERE categories_id = '" . (int)$who_am_i . "'
                AND categories_languages_id = '" . intval($_SESSION['language_id']) . "'";
    $result = $dbconn->Execute($query);

    $categories_name = $result->fields['categories_name'];

    // Close result set
    $result->Close();

    return $categories_name;
  }

?>