<?php
/* ----------------------------------------------------------------------
   $Id: block_order_history.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: order_history.php,v 1.4 2003/02/10 22:31:02 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!is_numeric(MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX)) return false;

  $order_history_block = 'false';

  if (isset($_SESSION['customer_id'])) { // retreive the last x products purchased

    $orderstable = $oostable['orders'];
    $orders_productstable = $oostable['orders_products'];
    $productstable = $oostable['products'];
    $query = "SELECT DISTINCT op.products_id
              FROM $orderstable o,
                   $orders_productstable op,
                   $productstable p
              WHERE o.customers_id = '" . intval($_SESSION['customer_id']) . "'
                AND o.orders_id = op.orders_id
                AND op.products_id = p.products_id
                AND p.products_status >= '1'
              GROUP BY products_id
              ORDER BY o.date_purchased DESC";
    $orders_result = $dbconn->SelectLimit($query, MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);

    if ($orders_result->RecordCount()) {

      $order_history_block = 'true';
      $product_ids = '';
      while ($orders = $orders_result->fields) {
        $product_ids .= $orders['products_id'] . ',';

        // Move that ADOdb pointer!
        $orders_result->MoveNext();
      }
      // Close result set
      $orders_result->Close();

      $product_ids = substr($product_ids, 0, -1);

      $products_descriptiontable = $oostable['products_description'];
      $products_sql = "SELECT products_id, products_name
                       FROM $products_descriptiontable
                       WHERE products_id IN ($product_ids)
                         AND products_languages_id = '" .  intval($nLanguageID) . "'
                       ORDER BY products_name";
      $smarty->assign('order_history', $dbconn->GetAll($products_sql));

      if (!isset($block_get_parameters)) {
        $block_get_parameters = oos_get_all_get_parameters(array('action'));
        $block_get_parameters = oos_remove_trailing($block_get_parameters);
        $smarty->assign('get_params', $block_get_parameters);
      }

      $smarty->assign('block_heading_customer_orders', $block_heading);

    }

  }
  $smarty->assign('order_history_block', $order_history_block);

