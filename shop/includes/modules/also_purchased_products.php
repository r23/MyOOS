<?php
/* ----------------------------------------------------------------------
   $Id: also_purchased_products.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: also_purchased_products.php,v 1.21 2003/02/12 23:55:58 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  $aPurchased = array();

  if (isset($_GET['products_id']) && is_numeric(MAX_DISPLAY_ALSO_PURCHASED)) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);

    $orders_productstable = $oostable['orders_products'];
    $orderstable = $oostable['orders'];
    $productstable = $oostable['products'];
    $sql = "SELECT p.products_id, p.products_image
            FROM $orders_productstable opa,
                 $orders_productstable opb,
                 $orderstable o,
                 $productstable p
            WHERE opa.products_id = '" . intval($nProductsId) . "'
              AND opa.orders_id = opb.orders_id
              AND opb.products_id != '" . intval($nProductsId) . "'
              AND opb.products_id = p.products_id
              AND opb.orders_id = o.orders_id
              AND p.products_status >= '1'
            GROUP BY p.products_id
            ORDER BY o.date_purchased DESC";
    $orders_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_ALSO_PURCHASED);

    $num_products_ordered = $orders_result->RecordCount();
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
      while ($orders = $orders_result->fields) {
        $aPurchased[] = array('products_name' => oos_get_products_name($orders['products_id']),
                              'products_id' => $orders['products_id'],
                              'products_image' => $orders['products_image']);

        // Move that ADOdb pointer!
        $orders_result->MoveNext();
      }
      // Close result set
      $orders_result->Close();
    }
  }

