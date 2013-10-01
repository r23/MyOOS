<?php
/* ----------------------------------------------------------------------
   $Id: block_specials.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: specials.php,v 1.30 2003/02/10 22:31:07 hpdl 
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

  $specials_block = 'false';

  if ($file != $aFilename['specials']) {

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $specialstable = $oostable['specials'];
    $query = "SELECT p.products_id, pd.products_name, p.products_price, p.products_base_price,
                     p.products_base_unit, p.products_tax_class_id, p.products_units_id,
                     p.products_image, s.specials_new_products_price
              FROM $productstable p,
                   $products_descriptiontable pd,
                   $specialstable s
              WHERE p.products_status >= '1'
                AND p.products_id = s.products_id
                AND pd.products_id = s.products_id
                AND pd.products_languages_id = '" . intval($nLanguageID) . "'
                AND s.status = '1'
              ORDER BY s.specials_date_added DESC";
    if ($specials_random_product = oos_random_select($query, MAX_RANDOM_SELECT_SPECIALS)) {
      $specials_block = 'true';

      $specials_random_product_price = '';
      $specials_random_product_special_price = '';
      $specials_random_base_product_price = '';
      $specials_random_base_product_special_price = '';

      if ($_SESSION['member']->group['show_price'] == 1 ) {
        $specials_random_product_price = $oCurrencies->display_price($specials_random_product['products_price'], oos_get_tax_rate($specials_random_product['products_tax_class_id']));    
        $specials_random_product_special_price = $oCurrencies->display_price($specials_random_product['specials_new_products_price'], oos_get_tax_rate($specials_random_product['products_tax_class_id']));

        if ($specials_random_product['products_base_price'] != 1) {
          $specials_random_base_product_price = $oCurrencies->display_price($specials_random_product['products_price'] * $specials_random_product['products_base_price'], oos_get_tax_rate($specials_random_product['products_tax_class_id']));
          $specials_random_base_product_special_price = $oCurrencies->display_price($specials_random_product['specials_new_products_price'] * $specials_random_product['products_base_price'], oos_get_tax_rate($specials_random_product['products_tax_class_id']));
        }
      }

      $smarty->assign(
          array(
              'specials_random_product'                    => $specials_random_product,
              'specials_random_product_price'              => $specials_random_product_price,
              'specials_random_product_special_price'      => $specials_random_product_special_price,
              'specials_random_base_product_price'         => $specials_random_base_product_price,
              'specials_random_base_product_special_price' => $specials_random_base_product_special_price
          )
      );
      $smarty->assign(array('block_heading_specials' => $block_heading));

    }
  }

  $smarty->assign('specials_block', $specials_block);

