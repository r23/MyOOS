<?php
/* ----------------------------------------------------------------------
   $Id: products_options.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_info.php,v 1.92 2003/02/14 05:51:21 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
    if (!isset($sProductsId)) $sProductsId = oos_var_prep_for_os($_GET['products_id']);

    $options = '';
    $number_of_uploads = 0;

    $products_optionstable = $oostable['products_options'];
    $products_attributestable = $oostable['products_attributes'];
    $attributes_sql = "SELECT COUNT(*) AS total
                       FROM $products_optionstable popt,
                            $products_attributestable patrib
                       WHERE patrib.products_id = '" . intval($nProductsId) . "'
                         AND patrib.options_id = popt.products_options_id
                         AND popt.products_options_languages_id = '" . intval($nLanguageID) . "'";
    $products_attributes = $dbconn->Execute($attributes_sql);
    if ($products_attributes->fields['total'] > 0) {

      $options .= '<b>' . $aLang['text_product_options'] . '</b><br />' .
                  '<table border="0" cellpadding="0" cellspacing="0">';


      if (PRODUCTS_OPTIONS_SORT_BY_PRICE == 'true') {
        $options_sort_by = ' ORDER BY pa.options_sort_order, pa.options_values_price';
      } else {
        $options_sort_by = ' ORDER BY pa.options_sort_order, pov.products_options_values_name';
      }

      $products_optionstable = $oostable['products_options'];
      $products_attributestable = $oostable['products_attributes'];
      $options_name_sql = "SELECT DISTINCT popt.products_options_id, popt.products_options_name,
                                  popt.products_options_type, popt.products_options_length,
                                  popt.products_options_comment
                           FROM $products_optionstable popt,
                                $products_attributestable patrib
                           WHERE patrib.products_id='" . intval($nProductsId) . "'
                             AND patrib.options_id = popt.products_options_id
                             AND popt.products_options_languages_id = '" . intval($nLanguageID) . "' 
                           ORDER BY popt.products_options_name";

      $products_options_name_result = $dbconn->Execute($options_name_sql);
      while ($products_options_name = $products_options_name_result->fields) {

        switch ($products_options_name['products_options_type']) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
            $options .= '<tr><td class="main">' . $products_options_name['products_options_name'] . ': </td><td class="main">' . "\n";

            $products_attributestable = $oostable['products_attributes'];
            $products_attribs_sql = "SELECT DISTINCT patrib.options_values_price, patrib.price_prefix
                                     FROM $products_attributestable patrib
                                     WHERE patrib.products_id = '" . intval($nProductsId) . "'
                                       AND patrib.options_id = '" . $products_options_name['products_options_id'] . "'";
            $products_attribs_result = $dbconn->Execute($products_attribs_sql);
            $products_attribs_array = $products_attribs_result->fields;

            $options .= '<input type="text" name ="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']" size="' . $products_options_name['products_options_length'] .'" maxlength="' . $products_options_name['products_options_length'] . '" value="' .  $_SESSION['cart']->contents[$sProductsId]['attributes_values'][$products_options_name['products_options_id']] .'">' . $products_options_name['products_options_comment'];
            if ($products_attribs_array['options_values_price'] > '0') {
              if ($_SESSION['member']->group['show_price'] == 1 ) {
                if ($info_product_discount != 0 ) {
                  $options .= '(' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )';
                } else {
                  $options .= '(' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')';
                }
              }
            }
            $options .= '</td></tr>';
            break;

          case PRODUCTS_OPTIONS_TYPE_RADIO:
            $products_attributestable = $oostable['products_attributes'];
            $products_options_valuestable = $oostable['products_options_values'];
            $products_options_sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
                                            pa.options_values_price, pa.price_prefix, pa.options_sort_order
                                     FROM $products_attributestable pa,
                                          $products_options_valuestable pov
                                     WHERE pa.products_id = '" . intval($nProductsId) . "' 
                                       AND pa.options_id = '" . $products_options_name['products_options_id'] . "' 
                                       AND pa.options_values_id = pov.products_options_values_id 
                                       AND pov.products_options_values_languages_id = '" . intval($nLanguageID) . "'  
                                    " . $options_sort_by;
            $products_options_result = $dbconn->Execute($products_options_sql);
            $row = 0;
            while ($products_options_array = $products_options_result->fields) {
              $row++;

              $options .= '<tr>';
              if ($row == 1) {
                $options .= '<td class="main">' . $products_options_name['products_options_name'] . ': </td>';
              } else {
                $options .= '<td class="main"></td>';
              }
              $options .= '<td class="main">';


              $checked = false;
              if ($_SESSION['cart']->contents[$sProductsId]['attributes'][$products_options_name['products_options_id']] == $products_options_array['products_options_values_id']) {
                $checked = true;
              }
              $options .= oos_draw_radio_field('id[' . $products_options_name['products_options_id'] . ']', $products_options_array['products_options_values_id'], $checked);
              $options .= $products_options_array['products_options_values_name'];
              $options .= $products_options_name['products_options_comment'];

              if ($products_attribs_array['options_values_price'] > '0') {
                if ($_SESSION['member']->group['show_price'] == 1 ) {
                  if ($info_product_discount != 0 ) {
                    $options .= ' (' . $products_options_array['price_prefix'] . $oCurrencies->display_price($products_options_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )&nbsp';
                  } else {
                    $options .= ' (' . $products_options_array['price_prefix'] . $oCurrencies->display_price($products_options_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
                  }
                }
              }

              $options .= '</td></tr>';

              // Move that ADOdb pointer!
              $products_options_result->MoveNext();
            }
            // Close result set
            $products_options_result->Close();

            break;

          case PRODUCTS_OPTIONS_TYPE_CHECKBOX:
            $options .= '<tr><td class="main">'  . "\n";
            $options .= $products_options_name['products_options_name'] . ': </td><td class="main">';

            $products_attributestable = $oostable['products_attributes'];
            $products_options_valuestable = $oostable['products_options_values'];
            $products_attribs_sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
                                            pa.options_values_price, pa.price_prefix, pa.options_sort_order
                                     FROM $products_attributestable pa,
                                          $products_options_valuestable pov
                                     WHERE pa.products_id = '" . intval($nProductsId) . "'
                                       AND pa.options_id = '" . $products_options_name['products_options_id'] . "'
                                       AND pa.options_values_id = pov.products_options_values_id
                                       AND pov.products_options_values_languages_id = '" . intval($nLanguageID) . "'  
                                    " . $options_sort_by;
            $products_attribs_result = $dbconn->Execute($products_attribs_sql);
            $products_attribs_array = $products_attribs_result->fields;

            $checked = false;
            if ($_SESSION['cart']->contents[$sProductsId]['attributes'][$products_options_name['products_options_id']] == $products_attribs_array['products_options_values_id']) {
              $checked = true;
            }
            $options .= oos_draw_checkbox_field('id[' . $products_options_name['products_options_id'] . ']', $products_attribs_array['products_options_values_id'], $checked);

            $options .= $products_attribs_array['products_options_values_name'];
            $options .= $products_options_name['products_options_comment'];

            if ($products_attribs_array['options_values_price'] > '0') {
              if ($_SESSION['member']->group['show_price'] == 1 ) {
                if ($info_product_discount != 0 ) {
                  $options .= ' (' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )&nbsp';
                } else {
                  $options .= ' (' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
                }
              }
            }
            $options .= '</td></tr>';
            break;

          case PRODUCTS_OPTIONS_TYPE_FILE:
            $number_of_uploads++;

            $products_attributestable = $oostable['products_attributes'];
            $products_attribs_sql = "SELECT DISTINCT patrib.options_values_price, patrib.price_prefix
                                     FROM $products_attributestable patrib
                                     WHERE patrib.products_id= '" . intval($nProductsId) . "'
                                       AND patrib.options_id = '" . $products_options_name['products_options_id'] . "'";
            $products_attribs_result = $dbconn->Execute($products_attribs_sql);
            $products_attribs_array = $products_attribs_result->fields;

            $options .= '<tr><td class="main">' . "\n";
            $options .= $products_options_name['products_options_name'];
            $options .= ':&nbsp;';

            if ($products_attribs_array['options_values_price'] > '0') {
              if ($_SESSION['member']->group['show_price'] == 1 ) {
                if ($info_product_discount != 0 ) {
                  $options .= ' (' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )&nbsp';
                } else {
                  $options .= ' (' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
                }
              }
            }

            $options .= '</td><td class="main"><input type="file" name="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']"><br />' . $_SESSION['cart']->contents[$sProductsId]['attributes_values'][$products_options_name['products_options_id']] . oos_draw_hidden_field(UPLOAD_PREFIX . $number_of_uploads, $products_options_name['products_options_id']) . oos_draw_hidden_field(TEXT_PREFIX . UPLOAD_PREFIX . $number_of_uploads, $_SESSION['cart']->contents[$sProductsId]['attributes_values'][$products_options_name['products_options_id']]);
            $options .= oos_draw_hidden_field('number_of_uploads', $number_of_uploads);
            $options .= '</td></tr>';
            break;

          case PRODUCTS_OPTIONS_TYPE_SELECT:
          default:
            $options .= '<tr><td class="main">' . $products_options_name['products_options_name'] . ':</td><td class="main">' . "\n";

            $selected = 0;
            $products_options_array = array();
            $products_attributestable = $oostable['products_attributes'];
            $products_options_valuestable = $oostable['products_options_values'];
            $products_options_sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
                                            pa.options_values_price, pa.price_prefix, pa.options_sort_order
                                     FROM $products_attributestable pa,
                                          $products_options_valuestable pov
                                     WHERE pa.products_id = '" . intval($nProductsId) . "'
                                       AND pa.options_id = '" . $products_options_name['products_options_id'] . "' 
                                       AND pa.options_values_id = pov.products_options_values_id 
                                       AND pov.products_options_values_languages_id = '" .  intval($nLanguageID) . "'
                                    " . $options_sort_by;
            $products_options_result = $dbconn->Execute($products_options_sql);
            while ($products_options = $products_options_result->fields) {
              $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);

              if ($products_options['options_values_price'] > '0') {
                if ($_SESSION['member']->group['show_price'] == 1 ) {
                  if ($info_product_discount != 0 ) {
                    $products_options_array[count($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $oCurrencies->display_price($products_options['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )&nbsp';
                  } else {
                    $products_options_array[count($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $oCurrencies->display_price($products_options['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
                  }
                }
              }
              // Move that ADOdb pointer!
              $products_options_result->MoveNext();
            }
            // Close result set
            $products_options_result->Close();

            $options .= oos_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $_SESSION['cart']->contents[$sProductsId]['attributes'][$products_options_name['products_options_id']]);
            $options .= '</td></tr>';
        }
        // Move that ADOdb pointer!
        $products_options_name_result->MoveNext();
      }
      // Close result set
      $products_options_name_result->Close();

      $options .= '</table>';
    }
  }
