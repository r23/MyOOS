<?php
/* ----------------------------------------------------------------------
   $Id: slavery_products.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_listing.php,v 1.2 2003/01/09 09:40:08 elarifr
   orig: product_listing.php,v 1.41 2003/02/12 23:55:58 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

// create column list
  $define_list = array('PRODUCT_LIST_MODEL' => SLAVE_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => SLAVE_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => SLAVE_LIST_MANUFACTURER,
                       'PRODUCT_LIST_UVP' => PRODUCT_LIST_UVP,
                       'PRODUCT_LIST_PRICE' => SLAVE_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => SLAVE_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => SLAVE_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => SLAVE_LIST_IMAGE,
                       'PRODUCT_SLAVE_BUY_NOW' => SLAVE_LIST_BUY_NOW);
  asort($define_list);

  $column_list = array();
  reset($define_list);
  while (list($column, $value) = each($define_list)) {
    if ($value) $column_list[] = $column;
  }

  $select_column_list = '';

  for ($col=0, $n=count($column_list); $col<$n; $col++) {
    if ( ($column_list[$col] == 'PRODUCT_SLAVE_BUY_NOW')
        || ($column_list[$col] == 'PRODUCT_LIST_PRICE')
        || ($column_list[$col] == 'PRODUCT_LIST_UVP') ) {
      continue;
    }
    if (oos_is_not_null($select_column_list)) {
      $select_column_list .= ', ';
    }

    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model';
        break;

      case 'PRODUCT_LIST_NAME':
        $select_column_list .= 'pd.products_name';
        break;

      case 'PRODUCT_LIST_MANUFACTURER':
        $select_column_list .= 'm.manufacturers_name';
        break;

      case 'PRODUCT_LIST_QUANTITY':
        $select_column_list .= 'p.products_quantity';
        break;

      case 'PRODUCT_LIST_IMAGE':
        $select_column_list .= 'p.products_image';
        break;

      case 'PRODUCT_LIST_WEIGHT':
        $select_column_list .= 'p.products_weight';
        break;

      default:
       $select_column_list .= "pd.products_name";
        break;

    }
  }

  if (oos_is_not_null($select_column_list)) {
    $select_column_list .= ', ';
  }

  if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);

  $productstable = $oostable['products'];
  $products_to_mastertable = $oostable['products_to_master'];
  $products_descriptiontable = $oostable['products_description'];
  $manufacturerstable = $oostable['manufacturers'];
  $specialstable = $oostable['specials'];
  $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id,
                         p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit,
                         p.products_discount_allowed, p.products_discount1, p.products_discount2,
                         p.products_discount3, p.products_discount4, p.products_discount1_qty,
                         p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty,
                         p.products_tax_class_id, p.products_units_id, p.products_quantity_order_min,
                         IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
                         IF(s.status, s.specials_new_products_price, p.products_price) AS final_price,
                         pm.master_id, pm.slave_id
                  FROM $productstable p LEFT JOIN
                       $manufacturerstable m ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                       $specialstable s ON p.products_id = s.products_id,
                       $products_to_mastertable pm,
                       $products_descriptiontable pd
                  WHERE p.products_status >= '1'
                    AND pd.products_id = p.products_id
                    AND pd.products_languages_id = '" . intval($nLanguageID) . "'
                    AND p.products_id = pm.slave_id AND
                      pm.master_id = '" . intval($nProductsId) . "'";

  if ( (!isset($_GET['sort'])) || (!preg_match('/[1-8][ad]/', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > count($column_list)) ) {
    for ($col=0, $n=count($column_list); $col<$n; $col++) {
      if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
        $_GET['sort'] = $col+1 . 'a';
        $listing_sql .= " ORDER BY pd.products_name";
        break;
      }
    }
  } else {
    $sort_col = substr($_GET['sort'], 0 , 1);
    $sort_order = substr($_GET['sort'], 1);
    $listing_sql .= ' ORDER BY ';

    switch ($column_list[$sort_col-1]) {
      case 'PRODUCT_LIST_MODEL':
        $listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;

      case 'PRODUCT_LIST_NAME':
        $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
        break;

      case 'PRODUCT_LIST_MANUFACTURER':
        $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;

      case 'PRODUCT_LIST_QUANTITY':
        $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;

      case 'PRODUCT_LIST_IMAGE':
        $listing_sql .= "pd.products_name";
        break;

      case 'PRODUCT_LIST_WEIGHT':
        $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;

      case 'PRODUCT_LIST_PRICE':
        $listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;

      default:
        $listing_sql .= "pd.products_name";
        break;

    }
  }

  $aTemplate['slavery_products'] = $sTheme . '/products/slavery_product_listing.html';
  $aTemplate['slavery_page_navigation'] = $sTheme . '/heading/page_navigation.html';

  include_once MYOOS_INCLUDE_PATH . '/includes/modules/slavery_listing.php';

  $smarty->assign('slavery_products', $smarty->fetch($aTemplate['slavery_products']));
  $smarty->assign('oosPageHeading', $smarty->fetch($aTemplate['page_heading']));

