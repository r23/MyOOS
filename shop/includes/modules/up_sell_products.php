<?php
/* ----------------------------------------------------------------------
   $Id: up_sell_products.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File:  xsell_products.php, v1  2002/09/11
   ----------------------------------------------------------------------
   Cross-Sell

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (isset($_GET['products_id']) && is_numeric(MAX_DISPLAY_XSELL_PRODUCTS)) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);

    $products_up_selltable = $oostable['products_up_sell'];
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $sql = "SELECT DISTINCT p.products_id, p.products_image, pd.products_name
            FROM $products_up_selltable xp,
                 $productstable p,
                 $products_descriptiontable pd
            WHERE xp.products_id = '" . intval($nProductsId) . "'
              AND xp.up_sell_id = p.products_id
              AND p.products_id = pd.products_id
              AND pd.products_languages_id = '" . intval($nLanguageID) . "'
              AND p.products_status >= '1'
            ORDER BY xp.products_id ASC";
    $up_sell_products_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_XSELL_PRODUCTS);

    $nUp_sell = $up_sell_products_result->RecordCount();
    if ($nUp_sell >=  MIN_DISPLAY_XSELL_PRODUCTS) {
      $smarty->assign('oos_up_sell_products_array', $up_sell_products_result->GetArray());
    }
  }

