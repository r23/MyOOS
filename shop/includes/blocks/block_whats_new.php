<?php
/* ----------------------------------------------------------------------
   $Id: block_whats_new.php,v 1.1 2007/06/07 11:55:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: whats_new.php,v 1.2 2003/01/09 09:40:07 elarifr
   orig: whats_new.php,v 1.31 2003/02/10 22:31:09 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if ($oEvent->installed_plugin('customer_must_login')) return false;

  $whats_new_block = 'false';

  $productstable = $oostable['products'];
  $query = "SELECT products_id, products_image, products_tax_class_id, products_units_id, products_price,
                   products_base_price, products_base_unit, products_discount_allowed
            FROM $productstable
            WHERE products_status >= '1'
            ORDER BY products_date_added DESC";
  if ($random_product = oos_random_select($query, MAX_RANDOM_SELECT_NEW)) {

    $whats_new_block = 'true';

    $random_product['products_name'] = oos_get_products_name($random_product['products_id']);
    $whats_new_product_price = '';
    $whats_new_product_special_price = '';
    $whats_new_max_product_discount = 0;
    $whats_new_product_discount_price = '';
    $whats_new_base_product_price = '';
    $whats_new_base_product_special_price = '';
    $whats_new_special_price = '';

    if ($_SESSION['member']->group['show_price'] == 1 ) {
      $whats_new_special_price = oos_get_products_special_price($random_product['products_id']);
      $whats_new_product_price = $oCurrencies->display_price($random_product['products_price'], oos_get_tax_rate($random_product['products_tax_class_id']));

      if (oos_is_not_null($whats_new_product_price)) {
        $whats_new_product_special_price = $oCurrencies->display_price($whats_new_special_price, oos_get_tax_rate($random_product['products_tax_class_id']));
      } else {
        $whats_new_max_product_discount = min($random_product['products_discount_allowed'],$_SESSION['member']->group['discount']);
        if ($whats_new_max_product_discount != 0 ) {
          $whats_new_special_price = $random_product['products_price']*(100-$whats_new_max_product_discount)/100;
          $whats_new_product_discount_price = $oCurrencies->display_price($whats_new_special_price, oos_get_tax_rate($random_product['products_tax_class_id']));
        }
      }

      if ($random_product['products_base_price'] != 1) {
        $whats_new_base_product_price = $oCurrencies->display_price($random_product['products_price'] * $random_product['products_base_price'], oos_get_tax_rate($random_product['products_tax_class_id']));

        if ($whats_new_special_price != '') {
          $whats_new_base_product_special_price = $oCurrencies->display_price($whats_new_special_price * $random_product['products_base_price'], oos_get_tax_rate($random_product['products_tax_class_id']));
        }
      }
    }
    $oSmarty->assign(
        array(
            'whats_new_product_price'              => $whats_new_product_price,
            'whats_new_product_special_price'      => $whats_new_product_special_price,
            'whats_new_max_product_discount'       => $whats_new_max_product_discount,
            'whats_new_product_discount_price'     => $whats_new_product_discount_price,
            'whats_new_base_product_price'         => $whats_new_base_product_price,
            'whats_new_base_product_special_price' => $whats_new_base_product_special_price,
            'whats_new_special_price'              => $whats_new_special_price,

            'random_product'          => $random_product,
            'block_heading_whats_new' => $block_heading
        )
    );
  }
  $oSmarty->assign('whats_new_block', $whats_new_block);

?>
