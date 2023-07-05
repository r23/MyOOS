<?php

/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   WebMakers.com Added: Discount Quantity
   Written by Linda McGrath osCOMMERCE@WebMakers.com
   http://www.thewebmakerscorner.com

   BOF: WebMakes.com Added: Discount Quantity
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

if (!isset($nProductsID)) {
    $nProductsID = oos_get_product_id($_GET['products_id']);
}
  $the_special = oos_get_products_special_price($nProductsID);

  $q0 = $product_info['products_quantity_order_min'];
  $q1 = $product_info['products_discount1_qty'];
  $q2 = $product_info['products_discount2_qty'];
  $q3 = $product_info['products_discount3_qty'];
  $q4 = $product_info['products_discount4_qty'];

  $col_cnt = 1;
if ($product_info['products_discount1'] > 0) {
    $col_cnt = $col_cnt+1;
}
if ($product_info['products_discount2'] > 0) {
    $col_cnt = $col_cnt+1;
}
if ($product_info['products_discount3'] > 0) {
    $col_cnt = $col_cnt+1;
}
if ($product_info['products_discount4'] > 0) {
    $col_cnt = $col_cnt+1;
}
  $discount_table = 120*$col_cnt;

if ($max_product_discount != 0) {
    $td0 = '<span class="smallText"><s>' . $oCurrencies->display_price($product_info['products_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . '</s><br />-' . number_format($max_product_discount, 2) . '%</span><br /><span class="productDiscountPrice">' . $oCurrencies->display_price($product_info['products_price']*(100-$max_product_discount)/100, oos_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    $td1 = '<span class="smallText"><s>' . $oCurrencies->display_price($product_info['products_discount1'], oos_get_tax_rate($product_info['products_tax_class_id'])) . '</s><br />-' . number_format($max_product_discount, 2) . '%</span><br /><span class="productDiscountPrice">' . $oCurrencies->display_price($product_info['products_discount1']*(100-$max_product_discount)/100, oos_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    $td2 = '<span class="smallText"><s>' . $oCurrencies->display_price($product_info['products_discount2'], oos_get_tax_rate($product_info['products_tax_class_id'])) . '</s><br />-' . number_format($max_product_discount, 2) . '%</span><br /><span class="productDiscountPrice">' . $oCurrencies->display_price($product_info['products_discount2']*(100-$max_product_discount)/100, oos_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    $td3 = '<span class="smallText"><s>' . $oCurrencies->display_price($product_info['products_discount3'], oos_get_tax_rate($product_info['products_tax_class_id'])) . '</s><br />-' . number_format($max_product_discount, 2) . '%</span><br /><span class="productDiscountPrice">' . $oCurrencies->display_price($product_info['products_discount3']*(100-$max_product_discount)/100, oos_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    $td4 = '<span class="smallText"><s>' . $oCurrencies->display_price($product_info['products_discount4'], oos_get_tax_rate($product_info['products_tax_class_id'])) . '</s><br />-' . number_format($max_product_discount, 2) . '%</span><br /><span class="productDiscountPrice">' . $oCurrencies->display_price($product_info['products_discount4']*(100-$max_product_discount)/100, oos_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
} else {
    $td0 = $oCurrencies->display_price($product_info['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
    $td1 = $oCurrencies->display_price($product_info['products_discount1'], oos_get_tax_rate($product_info['products_tax_class_id']));
    $td2 = $oCurrencies->display_price($product_info['products_discount2'], oos_get_tax_rate($product_info['products_tax_class_id']));
    $td3 = $oCurrencies->display_price($product_info['products_discount3'], oos_get_tax_rate($product_info['products_tax_class_id']));
    $td4 = $oCurrencies->display_price($product_info['products_discount4'], oos_get_tax_rate($product_info['products_tax_class_id']));
}

  $col0 = (($q1-1) > $q0 ? $q0 . '-' . ($q1-1) : $q0);
  $col1 = ($q2 > 0 ? (($q2-1) > $q1 ? $q1 . '-' . ($q2-1) : $q1) : $q1 . '+');
  $col2 = ($q3 > 0 ? (($q3-1) > $q2 ? $q2 . '-' . ($q3-1) : $q2) : $q2 . '+');
  $col3 = ($q4 > 0 ? (($q4-1) > $q3 ? $q3 . '-' . ($q4-1) : $q3) : $q3 . '+');
  $col4 = ($q4 > 0 ? $q4 . '+' : '');

$smarty->assign(
    ['discount_table' => $discount_table,
                        'colspan' => $col_cnt,
                        'q0' => $q0,
                        'q1' => $q1,
                        'q2' => $q2,
                        'q3' => $q3,
                        'q4' => $q4,
                        'col0' => $col0,
                        'col1' => $col1,
                        'col2' => $col2,
                        'col3' => $col3,
                        'col4' => $col4,
                        'td0' => $td0,
                        'td1' => $td1,
                        'td2' => $td2,
                        'td3' => $td3,
                        'td4' => $td4,
    'td5' => $td5]
);
