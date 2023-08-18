<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_info.php,v 1.92 2003/02/14 05:51:21 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


if (PRODUCTS_OPTIONS_SORT_BY_PRICE == 'true') {
    $options_sort_by = ' ORDER BY pa.options_sort_order, pa.options_values_price';
} else {
    $options_sort_by = ' ORDER BY pa.options_sort_order, pov.products_options_values_name';
}

$options .= '<div class="pb-4">' . "\n";

$products_optionstable = $oostable['products_options'];
$products_attributestable = $oostable['products_attributes'];
$options_name_sql = "SELECT DISTINCT popt.products_options_id, popt.products_options_name,
                                  popt.products_options_type, popt.products_options_length,
                                  popt.products_options_comment
                           FROM $products_optionstable popt,
                                $products_attributestable patrib
                           WHERE patrib.products_id='" . intval($nProductsID) . "'
                             AND patrib.options_id = popt.products_options_id
                             AND popt.products_options_languages_id = '" . intval($nLanguageID) . "' 
                           ORDER BY popt.products_options_name";
$products_options_name_result = $dbconn->Execute($options_name_sql);
while ($products_options_name = $products_options_name_result->fields) {
    switch ($products_options_name['products_options_type']) {
    case PRODUCTS_OPTIONS_TYPE_RADIO:
        $bTypeRadio = true;

        $products_attributestable = $oostable['products_attributes'];
        $products_options_valuestable = $oostable['products_options_values'];
        $products_options_sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
											pa.options_values_model, pa.options_values_image, pa.options_values_base_price,
											pa.options_values_quantity, pa.options_values_base_quantity, pa.options_values_base_unit,	
                                            pa.options_values_price, pa.options_values_status, pa.price_prefix, pa.options_sort_order
                                     FROM $products_attributestable pa,
                                          $products_options_valuestable pov
                                     WHERE pa.products_id = '" . intval($nProductsID) . "' 
                                       AND pa.options_id = '" . intval($products_options_name['products_options_id']) . "' 
                                       AND pa.options_values_id = pov.products_options_values_id 
                                       AND pov.products_options_values_languages_id = '" . intval($nLanguageID) . "'  
                                   ORDER BY pa.options_sort_order, pa.options_values_price";
        $products_options_result = $dbconn->Execute($products_options_sql);

        $row = 0;
        while ($products_options_array = $products_options_result->fields) {
            $row++;

            $options_values_price = '';
            $option_base = '';
            $sChecked = '';

            $checked = false;
            if ($row == 1) {
                $checked = true;
            }

            if ($_SESSION['cart']->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']] == $products_options_array['products_options_values_id']) {
                $checked = true;
            }

            if ($checked == true) {
                if ($products_options_array['options_values_status'] == 0) {
                    $checked = false;
                    $row = ($row == 1) ? 0 : $row;
                }
            }
            if ($checked == true) {
                if ($aUser['show_price'] == 1) {
                    if ($products_options_array['options_values_price'] > '0') {
                        $product_info['products_price'] = $products_options_array['options_values_price'];
                        if ($products_options_array['options_values_base_price'] != 1) {
                            $product_info['products_base_price'] = $products_options_array['options_values_base_price'];
                            $product_info['products_base_unit'] = $products_options_array['options_values_base_unit'];
                        } else {
                            $product_info['products_base_price'] = $products_options_array['options_values_base_price'];
                            $product_info['products_base_unit'] = $products_options_array['options_values_base_unit'];
                        }
                    }
                }

                if ($products_options_array['options_values_model'] != '') {
                    $product_info['products_model'] = $products_options_array['options_values_model'];
                }


                if ($products_options_array['options_values_image'] != '') {
                    $product_info['products_image'] = $products_options_array['options_values_image'];
                }
            }

            $sName = 'id[' . $products_options_name['products_options_id'] . ']';

            $sValue = $products_options_array['products_options_values_id'];


            if (($checked === true) || (isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && (($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value))))) {
                $sChecked = 'checked="checked"';
            }

            if ($aUser['show_price'] == 1) {
                if ($products_options_array['options_values_price'] > '0') {
                    $options_values_price = $oCurrencies->display_price($products_options_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
                }

                if ($products_options_array['options_values_base_price'] != 1) {
                    $option_base_product_price = $oCurrencies->display_price($products_options_array['options_values_price'] * $products_options_array['options_values_base_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
                    $option_base = $products_options_array['options_values_base_unit'] . ' = ' . $option_base_product_price . '"';
                }
            }

            // Image
            if ($products_options_array['options_values_image'] != '') {
                $values_image = $products_options_array['options_values_image'];
            } else {
                $values_image = $product_info['products_image'];
            }
            $change_image = 'images/product/large/' . $values_image;

            // Model
            if ($products_options_array['options_values_model'] != '') {
                $sModel = $products_options_array['options_values_model'];
            } else {
                $sModel = $product_info['products_model'];
            }
            $change_model = $sModel;

            $aSelector[] = ['name' => $sName,
                                 'value' => $sValue,
                                 'checked' => $sChecked,
                                 'image' => $change_image,
                                 'change_model' => $change_model,
                                'options_values_price'     => $options_values_price,
                                'option_base' => $option_base,
                                'products_options_values_name' => $products_options_array['products_options_values_name'],
                                'products_options_comment' => $products_options_name['products_options_comment'],
                                'status' => $products_options_array['options_values_status']];

            // Move that ADOdb pointer!
            $products_options_result->MoveNext();
        }
        break;


    case PRODUCTS_OPTIONS_TYPE_CHECKBOX:
        $options .= '<div class="form-group">' . "\n";
        $options .= '  <div class="pb-2">'  . $products_options_name['products_options_name'] . '</div>' . "\n";

        $products_attributestable = $oostable['products_attributes'];
        $products_options_valuestable = $oostable['products_options_values'];
        $products_attribs_sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
                                            pa.options_values_price, pa.price_prefix, pa.options_sort_order
                                     FROM $products_attributestable pa,
                                          $products_options_valuestable pov
                                     WHERE pa.products_id = '" . intval($nProductsID) . "'
                                       AND pa.options_id = '" . $products_options_name['products_options_id'] . "'
                                       AND pa.options_values_id = pov.products_options_values_id
                                       AND pov.products_options_values_languages_id = '" . intval($nLanguageID) . "'  
                                    " . $options_sort_by;
        $products_attribs_result = $dbconn->Execute($products_attribs_sql);

        $row = 0;
        while ($products_attribs_array = $products_attribs_result->fields) {
            $row++;

            $checked = false;
            if ($_SESSION['cart']->contents[$sProductsId]['attributes'][$products_options_name['products_options_id']] == $products_attribs_array['products_options_values_id']) {
                $checked = true;
            }
            $options .= oos_draw_checkbox_field('id[' . $products_options_name['products_options_id'] . ']', $products_attribs_array['products_options_values_id'], $checked);

            $options .= $products_attribs_array['products_options_values_name'];
            $options .= $products_options_name['products_options_comment'];

            if ($products_attribs_array['options_values_price'] > '0') {
                if ($aUser['show_price'] == 1) {
                    if ($info_product_discount != 0) {
                        $options .= ' (' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )&nbsp';
                    } else {
                        $options .= ' (' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp;';
                    }
                }
            }

            // Move that ADOdb pointer!
            $products_attribs_result->MoveNext();
        }

        $options .= '</div>' . "\n";
        break;


    case PRODUCTS_OPTIONS_TYPE_FILE:
        $number_of_uploads++;

        $products_attributestable = $oostable['products_attributes'];
        $products_attribs_sql = "SELECT DISTINCT patrib.options_values_price, patrib.price_prefix
											FROM $products_attributestable patrib
											WHERE patrib.products_id= '" . intval($nProductsID) . "'
											AND patrib.options_id = '" . $products_options_name['products_options_id'] . "'";
        $products_attribs_result = $dbconn->Execute($products_attribs_sql);
        $products_attribs_array = $products_attribs_result->fields;


        $options .= '<div class="form-group">' . "\n";
        $options .= '  <div class="pb-2">'  . $products_options_name['products_options_name'] . '</div>' . "\n";

        if ($products_attribs_array['options_values_price'] > '0') {
            if ($aUser['show_price'] == 1) {
                if ($info_product_discount != 0) {
                    $options .= ' (' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )&nbsp';
                } else {
                    $options .= ' (' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
                }
            }
        }

        $options .= '<input type="file" name="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']"><br />' . $_SESSION['cart']->contents[$sProductsId]['attributes_values'][$products_options_name['products_options_id']] . oos_draw_hidden_field(UPLOAD_PREFIX . $number_of_uploads, $products_options_name['products_options_id']) . oos_draw_hidden_field(TEXT_PREFIX . UPLOAD_PREFIX . $number_of_uploads, $_SESSION['cart']->contents[$sProductsId]['attributes_values'][$products_options_name['products_options_id']]);
        $options .= oos_draw_hidden_field('number_of_uploads', $number_of_uploads);

        $options .= '</div>' . "\n";
        break;

    case PRODUCTS_OPTIONS_TYPE_TEXT:

        $options .= '<div class="form-group">' . "\n";
        $options .= '  <div class="pb-2">'  . $products_options_name['products_options_name'] . '</div>' . "\n";


        $products_attributestable = $oostable['products_attributes'];
        $products_attribs_sql = "SELECT DISTINCT patrib.options_values_price, patrib.price_prefix
											FROM $products_attributestable patrib
											WHERE patrib.products_id = '" . intval($nProductsID) . "'
											AND patrib.options_id = '" . $products_options_name['products_options_id'] . "'";
        $products_attribs_result = $dbconn->Execute($products_attribs_sql);
        $products_attribs_array = $products_attribs_result->fields;

        $options .= '<input type="text" name ="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']" size="' . $products_options_name['products_options_length'] .'" maxlength="' . $products_options_name['products_options_length'] . '" value="' .  $_SESSION['cart']->contents[$sProductsId]['attributes_values'][$products_options_name['products_options_id']] .'">' . $products_options_name['products_options_comment'];
        if ($products_attribs_array['options_values_price'] > '0') {
            if ($aUser['show_price'] == 1) {
                if ($info_product_discount != 0) {
                    $options .= '(' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )';
                } else {
                    $options .= '(' . $products_attribs_array['price_prefix'] . $oCurrencies->display_price($products_attribs_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')';
                }
            }
        }
        $options .= '</div>' . "\n";
        break;

    case PRODUCTS_OPTIONS_TYPE_SELECT:
    default:
        $options .= '<div class="form-group">' . "\n";
        $options .= '  <div class="pb-2">'  . $products_options_name['products_options_name'] . '</div>' . "\n";

        $selected = 0;
        $products_options_array = [];
        $products_attributestable = $oostable['products_attributes'];
        $products_options_valuestable = $oostable['products_options_values'];
        $products_options_sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
                                            pa.options_values_price, pa.price_prefix, pa.options_sort_order
                                     FROM $products_attributestable pa,
                                          $products_options_valuestable pov
                                     WHERE pa.products_id = '" . intval($nProductsID) . "'
                                       AND pa.options_id = '" . $products_options_name['products_options_id'] . "' 
                                       AND pa.options_values_id = pov.products_options_values_id 
                                       AND pov.products_options_values_languages_id = '" .  intval($nLanguageID) . "'
                                    " . $options_sort_by;
        $products_options_result = $dbconn->Execute($products_options_sql);
        while ($products_options = $products_options_result->fields) {
            $products_options_array[] = ['id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']];

            if ($products_options['options_values_price'] > '0') {
                if ($aUser['show_price'] == 1) {
                    if ($info_product_discount != 0) {
                        $products_options_array[count($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $oCurrencies->display_price($products_options['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) . ' -' . number_format($info_product_discount, 2) . '% )&nbsp;';
                    } else {
                        $products_options_array[count($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $oCurrencies->display_price($products_options['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp;';
                    }
                }
            }

            // Move that ADOdb pointer!
            $products_options_result->MoveNext();
        }


        $options .= oos_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $_SESSION['cart']->contents[$sProductsId]['attributes'][$products_options_name['products_options_id']]);
        $options .= '</div>' . "\n";
    }

    // Move that ADOdb pointer!
    $products_options_name_result->MoveNext();
}

$options .= '</div>' . "\n";
