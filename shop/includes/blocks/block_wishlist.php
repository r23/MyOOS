<?php
/* ----------------------------------------------------------------------
   $Id: block_wishlist.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: wishlist.php,v 1.0 2002/05/08 10:00:00 hpdl  
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!is_numeric(MAX_DISPLAY_WISHLIST_BOX)) return false;

  $wishlist_block = 'false';

  if ($sContent != $aContents['account_my_wishlist']) {
    if (isset($_SESSION['customer_id'])) {

      $wishlist_block = 'true';
      $show_wishlist = 'false';

      $productstable = $oostable['products'];
      $products_descriptiontable = $oostable['products_description'];
      $customers_wishlisttable   = $oostable['customers_wishlist'];
      $query = "SELECT p.products_id, p.products_image, p.products_price,
                       p.products_tax_class_id, p.products_units_id,
                       pd.products_id, pd.products_name
                FROM $productstable AS p,
                     $products_descriptiontable AS pd,
                     $customers_wishlisttable AS wl
                WHERE wl.customers_id = '" . intval($_SESSION['customer_id']) . "'
                  AND wl.products_id = pd.products_id
                  AND pd.products_languages_id = '" . intval($nLanguageID) . "'
                  AND p.products_id = pd.products_id
                ORDER BY pd.products_name";
      $wishlist_result = $dbconn->SelectLimit($query, MAX_DISPLAY_WISHLIST_BOX);

      if ($wishlist_result->RecordCount()) {
        $show_wishlist = 'true';
        $smarty->assign('wishlist_contents', $wishlist_result->GetArray());
      }

      $smarty->assign(
          array(
              'show_wishlist' => $show_wishlist,
              'block_heading_customer_wishlist' => $block_heading
          )
      );
    }
  }
  $smarty->assign('wishlist_block', $wishlist_block);


