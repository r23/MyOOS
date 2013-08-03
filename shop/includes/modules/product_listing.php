<?php
/* ----------------------------------------------------------------------
   $Id: product_listing.php 431 2013-06-21 22:03:17Z r23 $

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

  // split-page-results
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';
  
  // define our listing functions
  include_once MYOOS_INCLUDE_PATH . '/includes/functions/function_listing.php';

  $listing_numrows_sql = $listing_sql;
  $listing_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $listing_sql, $listing_numrows);
  // fix counted products
  $listing_numrows = $dbconn->Execute($listing_numrows_sql);
  $listing_numrows = $listing_numrows->RecordCount();

  $list_box_contents = array();
  $list_box_contents[] = array('params' => 'class="productListing-even"');
  $cur_row = count($list_box_contents) - 1;

  for ($col=0, $n=count($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $lc_text = $aLang['table_heading_model'];
        $lc_align = '';
        break;

      case 'PRODUCT_LIST_NAME':
        $lc_text = $aLang['table_heading_products'];
        $lc_align = '';
        break;

      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = $aLang['table_heading_manufacturer'];
        $lc_align = '';
        break;

      case 'PRODUCT_LIST_UVP':
        if ($_SESSION['member']->group['show_price'] != 1) {
          $lc_text = '';
        } else {
          $lc_text = $aLang['table_heading_list_price'];
        }
        $lc_align = 'right';
        break;


      case 'PRODUCT_LIST_PRICE':
        if ($_SESSION['member']->group['show_price'] != 1) {
          $lc_text = '';
        } else {
          $lc_text = $aLang['table_heading_price'];
        }
        $lc_align = 'right';
        break;

      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = $aLang['table_heading_quantity'];
        $lc_align = 'right';
        break;

      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = $aLang['table_heading_weight'];
        $lc_align = 'right';
        break;

      case 'PRODUCT_LIST_IMAGE':
        $lc_text = $aLang['table_heading_image'];
        $lc_align = 'center';
        break;

      case 'PRODUCT_LIST_BUY_NOW':
        if ($_SESSION['member']->group['show_price'] != 1) {
          $lc_text='';
        } else {
          $lc_text = $aLang['table_heading_buy_now'];
        }
        $lc_align = 'center';
        break;

      case 'PRODUCT_LIST_SORT_ORDER':
        $lc_text = $aLang['table_heading_product_sort'];
        $lc_align = 'center';
        break;
    }

    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = oos_create_sort_heading($_GET['sort'], $col+1, $lc_text);
    }

    $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                           'params' => 'class="productListing-even"',
                                           'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }


  if ($listing_numrows > 0) {
    if (!isset($all_get_listing)) $all_get_listing = oos_get_all_get_parameters(array('action'));
    $number_of_products = 0;
    $listing_result = $dbconn->Execute($listing_sql);
    while ($listing = $listing_result->fields) {
      $number_of_products++;

      if (($number_of_products/2) == floor($number_of_products/2)) {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = count($list_box_contents) - 1;

      for ($col=0, $n=count($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing['products_model'] . '&nbsp;';
            break;

          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<a href="' . oos_href_link($aContents['product_info'], 'manufacturers_id=' . $_GET['manufacturers_id'] . '&amp;products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
            } else {
              if ($oEvent->installed_plugin('sefu')) {
                $lc_text = '&nbsp;<a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>&nbsp;';
              } else {
                $lc_text = '&nbsp;<a href="' . oos_href_link($aContents['product_info'], ($category ? 'category=' . $category . '&amp;' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>&nbsp;';
              }
            }
            break;

          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . oos_href_link($aContents['shop'], 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;

          case 'PRODUCT_LIST_UVP':
            if ($listing['products_price_list'] > 0) {
              $pl_products_price_list = $oCurrencies->display_price($listing['products_price_list'], oos_get_tax_rate($listing['products_tax_class_id']));
              $lc_align = 'right';
              $lc_text = '&nbsp;' . $pl_products_price_list . '&nbsp;';
            } else {
              $lc_text = '&nbsp;';
            }
            break;


          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';

            $sUnits = UNITS_DELIMITER . $products_units[$listing['products_units_id']];
            $pl_product_price = $oCurrencies->display_price($listing['products_price'], oos_get_tax_rate($listing['products_tax_class_id']));

            unset($pl_price_discount);
            if ( $listing['products_discount4'] > 0 ) {
              $pl_price_discount = $oCurrencies->display_price($listing['products_discount4'], oos_get_tax_rate($listing['products_tax_class_id']));
            } elseif ( $listing['products_discount3'] > 0 ) {
              $pl_price_discount = $oCurrencies->display_price($listing['products_discount3'], oos_get_tax_rate($listing['products_tax_class_id']));
            } elseif ( $listing['products_discount2'] > 0 ) {
              $pl_price_discount = $oCurrencies->display_price($listing['products_discount2'], oos_get_tax_rate($listing['products_tax_class_id']));
            } elseif ( $listing['products_discount1'] > 0 ) {
              $pl_price_discount = $oCurrencies->display_price($listing['products_discount1'], oos_get_tax_rate($listing['products_tax_class_id']));
            }

            unset($pl_special_price);
            unset($pl_max_product_discount);
            if (oos_is_not_null($listing['specials_new_products_price'])) {
              $pl_special_price = $listing['specials_new_products_price'];
              $pl_product_special_price = $oCurrencies->display_price($pl_special_price, oos_get_tax_rate($listing['products_tax_class_id']));
            }


            unset($pl_base_product_price);
            unset($pl_base_product_special_price);
            if ($listing['products_base_price'] != 1) {
              $pl_base_product_price = $oCurrencies->display_price($listing['products_price'] * $listing['products_base_price'], oos_get_tax_rate($listing['products_tax_class_id']));

              if ($pl_special_price != '') {
                $pl_base_product_special_price = $oCurrencies->display_price($pl_special_price * $listing['products_base_price'], oos_get_tax_rate($listing['products_tax_class_id']));
              }
            }

            if (oos_is_not_null($listing['specials_new_products_price'])) {
              $lc_text = '&nbsp;<s>' . $pl_product_price . $sUnits . '</s><br />';
              if ($listing['products_base_price'] != 1)  $lc_text .= '<s><span class="base_price">' . $listing['products_base_unit'] . ' = ' . $pl_base_product_price . '</span></s><br />';

              $lc_text .= '&nbsp;<span class="special_price">' . $pl_product_special_price . $sUnits . '</span>';
              if ($listing['products_base_price'] != 1)  $lc_text .= '<br /><span class="special_base_price">' . $listing['products_base_unit'] . ' = ' . $pl_base_product_special_price . '</span></s><br />';
            } else {
              if ($pl_max_product_discount != 0 ) {
                $lc_text = '&nbsp;<s>' . $pl_product_price .  $sUnits . '</s>&nbsp;-' . number_format($pl_max_product_discount, 2) . '%<br />';
                $lc_text .= '&nbsp;<span class="discount_price">' . $pl_product_special_price . $sUnits . '</span>';
                if ($listing['products_base_price'] != 1)  $lc_text .= '<br /><span class="special_base_price">' . $listing['products_base_unit'] . ' = ' . $pl_base_product_special_price . '</span></s><br />';

              } else {
                if (isset($pl_price_discount)) {
                  $lc_text = $aLang['price_from'] . '&nbsp;' . $pl_price_discount . $sUnits . '<br />';
                } else {
                  $lc_text = '&nbsp;' . $pl_product_price .  $sUnits . '<br />';
                  if ($listing['products_base_price'] != 1)  $lc_text .= '<span class="base_price">' . $listing['products_base_unit'] . ' = ' . $pl_base_product_price . '</span><br />';
                }
              }
            }
            $lc_text .= '&nbsp;<span class="pangv">' . $sPAngV . '</span><br />';
            break;

          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_quantity'] . '&nbsp;';
            break;

          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_weight'] . '&nbsp;';
            break;

          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) {
              $lc_text = '<a href="' . oos_href_link($aContents['product_info'], 'manufacturers_id=' . $_GET['manufacturers_id'] . '&amp;products_id=' . $listing['products_id']) . '">';
            } else {
              if ($oEvent->installed_plugin('sefu')) {
                $lc_text = '&nbsp;<a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $listing['products_id']) . '">';
              } else {
                $lc_text = '&nbsp;<a href="' . oos_href_link($aContents['product_info'], ($category ? 'category=' . $category . '&amp;' : '') . 'products_id=' . $listing['products_id']) . '">';
              }
            }

            $lc_image = 'no_picture.gif';
            if (oos_is_not_null($listing['products_image'])) {
              $lc_image = $listing['products_image'];
            } else {
              if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'no_picture_' . $sLanguage . '.gif')) {
                $lc_image = 'no_picture_' . $sLanguage . '.gif';
              }
            }
            $lc_text .= oos_image(OOS_IMAGES . $lc_image, $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
            break;

          case 'PRODUCT_LIST_BUY_NOW':
            $lc_align = 'right';
            if ($_SESSION['member']->group['show_price'] == 1) {

               if (DECIMAL_CART_QUANTITY == 'true') {
                 $order_min = number_format($listing['products_quantity_order_min'], 2);
               } else {
                 $order_min = number_format($listing['products_quantity_order_min']);
               }

               if (PRODUCT_LISTING_WITH_QTY == 'true') {
                 $lc_text = '<form name="buy_now" action="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php" method="post">';
                 $lc_text .= '<input type="hidden" name="action" value="buy_now">';
                 $lc_text .= '<input type="hidden" name="products_id" value="' . $listing['products_id'] .'">';
                 $lc_text .= '<input type="hidden" name="content" value="' . $sContent .'">';
                 $lc_text .= '<input type="hidden" name="category" value="' . $category .'">';
                 $lc_text .= oos_hide_session_id();
                 $lc_text .= oos_get_all_as_hidden_field(array('action'));
                 $lc_text .= $aLang['products_order_qty_text'];
                 $lc_text .= ' <input type="text" name="cart_quantity" value="' . $order_min . '" size="3" /><br />';
                 $lc_text .= oos_image_submit('buy_now.gif', $aLang['text_buy'] . $listing['products_name'] . $aLang['text_now']);
                 $lc_text .= '</form>';
               } else {
                 $lc_text = '<a href="' . oos_href_link($sContent, $all_get_listing . 'action=buy_now&amp;products_id=' . $listing['products_id'] . '&amp;cart_quantity=' . $order_min ) . '">' . oos_image_button('buy_now.gif', $aLang['text_buy'] . $listing['products_name'] . $aLang['text_now']) . '</a>&nbsp;';
               }

            } else {
              $lc_text = '&nbsp;';
            }
            break;

          case 'PRODUCT_LIST_SORT_ORDER';
            $lc_align = 'center';
            $lc_text = '&nbsp;' . $listing['products_sort_order'] . '&nbsp;';
            break;
        }

        $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => 'class="productListing-data"',
                                               'text'  => $lc_text);
      }

      // Move that ADOdb pointer!
      $listing_result->MoveNext();
    }
    // Close result set
    $listing_result->Close();
  }

  $smarty->assign(array('oos_page_split' => $listing_split->display_count($listing_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], $aLang['text_display_number_of_products']),
                        'oos_display_links' => $listing_split->display_links($listing_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_parameters(array('page', 'info'))),
                        'oos_page_numrows' => $listing_numrows));
  $smarty->assign('list_box_contents', $list_box_contents);

