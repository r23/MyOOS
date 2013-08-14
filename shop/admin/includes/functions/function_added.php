<?php
/* ----------------------------------------------------------------------
   $Id: function_added.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

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

    return $manufacturers_name;
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

    return $categories_name;
  }

