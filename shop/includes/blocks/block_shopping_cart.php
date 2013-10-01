<?php
/* ----------------------------------------------------------------------
   $Id: block_shopping_cart.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: shopping_cart.php,v 1.2.2.9 2003/05/13 22:49:08 wilt
   orig: shopping_cart.php,v 1.18 2003/02/10 22:31:06 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  $cart_products = array();
  if ($_SESSION['cart']->count_contents() > 0) {
    $cart_products = $_SESSION['cart']->get_products();
  }

  $gv_amount_show = 0;
  if (isset($_SESSION['customer_id'])) {
    $coupon_gv_customertable = $oostable['coupon_gv_customer'];
    $query = "SELECT amount
              FROM $coupon_gv_customertable
              WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'";
    $gv_result = $dbconn->GetRow($query);
    if ($gv_result['amount'] > 0 ) {
      $gv_amount_show = $oCurrencies->format($gv_result['amount']);
    }
  }

  $gv_coupon_show = 0;
  if (isset($_SESSION['gv_id'])) {
    $couponstable = $oostable['coupons'];
    $query = "SELECT coupon_amount
              FROM $couponstable
              WHERE coupon_id = '" . oos_db_input($_SESSION['gv_id']) . "'";
    $coupon = $dbconn->GetRow($query);
    $gv_coupon_show = $oCurrencies->format($coupon['coupon_amount']);
  }

  $smarty->assign(
      array(
          'block_heading_shopping_cart' => $block_heading,
          'cart_products'  => $cart_products,
          'gv_amount_show' => $gv_amount_show,
          'gv_coupon_show' => $gv_coupon_show
     )
  );


