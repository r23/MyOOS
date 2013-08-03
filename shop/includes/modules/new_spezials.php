<?php
/* ----------------------------------------------------------------------
   $Id: new_spezials.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: new_products.php,v 1.33 2003/02/12 23:55:58 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('spezials')) return false;
  if (!is_numeric(MAX_DISPLAY_NEW_SPEZILAS)) return false;

  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $specialstable = $oostable['specials'];
  $sql = "SELECT p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_units_id,
                 substring(pd.products_description, 1, 150) AS products_description,
                 p.products_image, p.products_base_price, p.products_base_unit, s.specials_new_products_price 
          FROM $productstable p,
               $products_descriptiontable pd,
               $specialstable s
          WHERE p.products_status >= '1'
            AND s.products_id = p.products_id
            AND p.products_id = pd.products_id
            AND pd.products_languages_id = '" . intval($nLanguageID) . "'
            AND s.status = '1'
          ORDER BY s.specials_date_added DESC";
  $new_spezials_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_NEW_SPEZILAS);
  if ($new_spezials_result->RecordCount() >= MIN_DISPLAY_NEW_SPEZILAS) {
    $new_spezials_array = array();
    while ($new_spezials = $new_spezials_result->fields) {

      $new_spezials_base_product_price = '';
      $new_spezials_base_product_special_price = '';

      $new_spezials_units = UNITS_DELIMITER . $products_units[$new_spezials['products_units_id']];

      $new_spezials_product_price = $oCurrencies->display_price($new_spezials['products_price'], oos_get_tax_rate($new_spezials['products_tax_class_id']));
      $new_spezials_product_special_price = $oCurrencies->display_price($new_spezials['specials_new_products_price'], oos_get_tax_rate($new_spezials['products_tax_class_id']));

      if ($new_spezials['products_base_price'] != 1) {
        $new_spezials_base_product_price = $oCurrencies->display_price($new_spezials['products_price'] * $new_spezials['products_base_price'], oos_get_tax_rate($new_spezials['products_tax_class_id']));
        $new_spezials_base_product_special_price = $oCurrencies->display_price($new_spezials['specials_new_products_price'] * $new_spezials['products_base_price'], oos_get_tax_rate($new_spezials['products_tax_class_id']));
      }

      $new_spezials_array[] = array('products_id' => $new_spezials['products_id'],
                                    'products_image' => $new_spezials['products_image'],
                                    'products_name' => $new_spezials['products_name'],
                                    'products_description' => oos_remove_tags($new_spezials['products_description']),
                                    'products_base_unit' => $new_spezials['products_base_unit'],
                                    'products_base_price' => $new_spezials['products_base_price'],
                                    'products_units' => $new_spezials_units,
                                    'products_price' => $new_spezials_product_price,
                                    'products_special_price' => $new_spezials_product_special_price,
                                    'base_product_price' => $new_spezials_base_product_price,
                                    'base_product_special_price' => $new_spezials_base_product_special_price);

      // Move that ADOdb pointer!
      $new_spezials_result->MoveNext();
    }

    // Close result set
    $new_spezials_result->Close();

    $smarty->assign('new_spezials_array', $new_spezials_array);
  }

