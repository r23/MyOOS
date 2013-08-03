<?php
/* ----------------------------------------------------------------------
   $Id: class_shopping_cart.php 439 2013-06-24 22:47:03Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: shopping_cart.php,v 1.2 2003/01/09 09:40:08 elarifr
         shopping_cart.php,v 1.3.2.6 2003/05/12 23:11:20 wilt
   orig: shopping_cart.php,v 1.32 2003/02/11 00:04:53 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class shoppingCart {
    var $contents;
    var $total;
    var $weight;
    var $cartID;
    var $content_type;

    function shoppingCart() {
      $this->reset();
    }

    function restore_contents() {

      if (!isset($_SESSION['customer_id'])) return false;

      // insert current cart contents in database
      if (is_array($this->contents)) {
        reset($this->contents);

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        while (list($products_id, ) = each($this->contents)) {
          $qty = $this->contents[$products_id]['qty'];
          $towlid = $this->contents[$products_id]['towlid'];

          if ($_SESSION['customer_wishlist_link_id'] == $towlid) {
            $towlid = '';
            $customers_wishlisttable = $oostable['customers_wishlist'];
            $dbconn->Execute("DELETE FROM $customers_wishlisttable WHERE customers_id= '" . intval($_SESSION['customer_id']) . "'  AND products_id = '" . oos_db_input($products_id) . "'");
            $customers_wishlist_attributestable = $oostable['customers_wishlist_attributes'];
            $dbconn->Execute("DELETE FROM $customers_wishlist_attributestable WHERE customers_id= '" . intval($_SESSION['customer_id']) . "'  AND products_id = '" . oos_db_input($products_id) . "'");
          }

          $customers_baskettable = $oostable['customers_basket'];
          $product_sql = "SELECT products_id
                          FROM $customers_baskettable
                          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
                          AND products_id = '" . intval($products_id) . "'";
          $product_result = $dbconn->Execute($product_sql);
          if (!$product_result->RecordCount()) {
            $customers_baskettable = $oostable['customers_basket'];
            $dbconn->Execute("INSERT INTO $customers_baskettable
                       (customers_id,
                        to_wishlist_id,
                        products_id,
                        customers_basket_quantity,
                        customers_basket_date_added) VALUES ('" . intval($_SESSION['customer_id']) . "',
                                                             '" . oos_db_input($towlid) . "',
                                                             '" . oos_db_input($products_id) . "',
                                                             '" . oos_db_input($qty) . "',
                                                             '" . oos_db_input(date('Ymd')) . "')");
            if (isset($this->contents[$products_id]['attributes'])) {
              reset($this->contents[$products_id]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
                $attr_value = $this->contents[$products_id]['attributes_values'][$option];
                $customers_basket_attributestable = $oostable['customers_basket_attributes'];
                $dbconn->Execute("INSERT INTO $customers_basket_attributestable
                            (customers_id,
                             products_id,
                             products_options_id,
                             products_options_value_id,
                             products_options_value_text) VALUES ('" . intval($_SESSION['customer_id']) . "',
                                                                  '" . oos_db_input($products_id) . "',
                                                                  '" . oos_db_input($option) . "',
                                                                  '" . oos_db_input($value) . "',
                                                                  '" . oos_db_input($attr_value) . "')");

              }
            }
          } else {
            $customers_baskettable = $oostable['customers_basket'];
            $dbconn->Execute("UPDATE $customers_baskettable
                          SET customers_basket_quantity = '" . intval($qty) . "'
                          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' AND
                                products_id = '" . oos_db_input($products_id) . "'");
          }
        }
        if (isset($_SESSION['gv_id'])) {
          $remote = oos_server_get_remote();
          $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
          $gv_result = $dbconn->Execute("INSERT INTO $coupon_redeem_tracktable
                                  (coupon_id,
                                   customer_id,
                                   redeem_date,
                                   redeem_ip) VALUES ('" . oos_db_input($_SESSION['gv_id']) . "',
                                                      '" . intval($_SESSION['customer_id']) . "',
                                                      now(),
                                                      '" . oos_db_input($remote) . "')");

          $couponstable = $oostable['coupons'];
          $gv_update = $dbconn->Execute("UPDATE $couponstable
                                     SET coupon_active = 'N'
                                     WHERE coupon_id = '" . oos_db_input($_SESSION['gv_id']) . "'");

          oos_gv_account_update($_SESSION['customer_id'], $_SESSION['gv_id']);
          unset($_SESSION['gv_id']);
        }
      }

      // reset per-session cart contents, but not the database contents
      $this->reset(false);

      $customers_baskettable = $oostable['customers_basket'];
      $sql = "SELECT products_id, to_wishlist_id, customers_basket_quantity
              FROM $customers_baskettable
              WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
      $products_result = $dbconn->Execute($sql);
      while ($products = $products_result->fields) {
        $this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity'],
                                                          'towlid' => $products['to_wishlist_id']);
        // attributes
        $customers_basket_attributestable = $oostable['customers_basket_attributes'];
        $sql = "SELECT products_options_id, products_options_value_id, products_options_value_text
                FROM $customers_basket_attributestable
                WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
                AND products_id = '" . $products['products_id'] . "'";
        $attributes_result = $dbconn->Execute($sql);
        while ($attributes = $attributes_result->fields) {
          $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
          if ($attributes['products_options_value_id'] == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
            $this->contents[$products['products_id']]['attributes_values'][$attributes['products_options_id']] = $attributes['products_options_value_text'];
          }

          // Move that ADOdb pointer!
          $attributes_result->MoveNext();
        }

        // Move that ADOdb pointer!
        $products_result->MoveNext();
      }

      $this->cleanup();
    }

    function reset($reset_database = false) {

      $this->contents = array();
      $this->total = 0;
      $this->weight = 0;
      $this->content_type = false;

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      if (isset($_SESSION['customer_id']) && ($reset_database == true)) {
        $customers_baskettable = $oostable['customers_basket'];
        $dbconn->Execute("DELETE FROM $customers_baskettable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'");
        $customers_basket_attributestable = $oostable['customers_basket_attributes'];
        $dbconn->Execute("DELETE FROM $customers_basket_attributestable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'");
      }

      unset($this->cartID);
      if (isset($_SESSION['cartID'])) unset($_SESSION['cartID']);
    }


    function add_cart($products_id, $nQuantity = '1', $attributes = '', $notify = true, $towlid = '') {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $sProductsId = oos_get_uprid($products_id, $attributes);
      $nProductsId = oos_get_product_id($sProductsId);

      if (is_numeric($nProductsId) && is_numeric($nQuantity)) {
        $productstable = $oostable['products'];
        $check_product_sql = "SELECT products_status
                              FROM $productstable
                              WHERE products_id = '" . intval($nProductsId) . "'";
        $products_status = $dbconn->GetOne($check_product_sql);
        if ($products_status >= '1') {

          if (DECIMAL_CART_QUANTITY == 'true') {
            $productstable = $oostable['products'];
            $decimal_sql = "SELECT products_quantity_decimal
                              FROM $productstable
                             WHERE products_id = '" . intval($nProductsId) . "'";
            $products_quantity_decimal = $dbconn->GetOne($decimal_sql);
            if ($products_quantity_decimal == 0) {
              $nQuantity = intval($nQuantity);
            }
          }


          if ($notify == true) {
            $_SESSION['new_products_id_in_cart'] = $sProductsId;
          }

          if (isset($_SESSION['customer_wishlist_link_id']) && ($_SESSION['customer_wishlist_link_id'] == $towlid)) {
            $towlid = '';
            $customers_wishlisttable = $oostable['customers_wishlist'];
            $dbconn->Execute("DELETE FROM $customers_wishlisttable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'  AND products_id = '" . oos_db_input($sProductsId) . "'");
            $customers_wishlist_attributestable = $oostable['customers_wishlist_attributes'];
            $dbconn->Execute("DELETE FROM $customers_wishlist_attributestable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'  AND products_id = '" . oos_db_input($sProductsId) . "'");
          }

          if ($this->in_cart($sProductsId)) {
            $this->update_quantity($sProductsId, $nQuantity, $attributes, $towlid);
          } else {
            $this->contents[] = array($sProductsId);
            $this->contents[$sProductsId] = array('qty' => $nQuantity,
                                                  'towlid' => $towlid);

            // insert into database
            if (isset($_SESSION['customer_id'])) {
              $customers_baskettable = $oostable['customers_basket'];
              $dbconn->Execute("INSERT INTO $customers_baskettable
                            (customers_id,
                             to_wishlist_id,
                             products_id,
                             customers_basket_quantity,
                             customers_basket_date_added) VALUES (" . $dbconn->qstr($_SESSION['customer_id']) . ','
                                                                    . $dbconn->qstr($towlid) . ','
                                                                    . $dbconn->qstr($sProductsId) . ','
                                                                    . $dbconn->qstr($nQuantity) . ','
                                                                    . $dbconn->qstr(date('Ymd')) . ")");
            }
            if (is_array($attributes)) {
              reset($attributes);
              while (list($option, $value) = each($attributes)) {
                $attr_value = NULL;
                $blank_value = FALSE;
                if (strstr($option, TEXT_PREFIX)) {
                  if (trim($value) == NULL) {
                    $blank_value = TRUE;
                  } else {
                    $option = substr($option, strlen(TEXT_PREFIX));

                    $attr_value = htmlspecialchars(stripslashes($value), ENT_QUOTES);
                    $value = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
                    $this->contents[$sProductsId]['attributes_values'][$option] = $attr_value;
                  }
                }

                if (!$blank_value) {

                  $this->contents[$sProductsId]['attributes'][$option] = $value;
                  // insert into database
                  if (isset($_SESSION['customer_id'])) {
                    $customers_basket_attributestable = $oostable['customers_basket_attributes'];
                    $dbconn->Execute("INSERT INTO $customers_basket_attributestable
                                  (customers_id,
                                   products_id,
                                   products_options_id,
                                   products_options_value_id,
                                   products_options_value_text) VALUES ("  . $dbconn->qstr($_SESSION['customer_id']) . ','
                                                                           . $dbconn->qstr($sProductsId) . ','
                                                                           . $dbconn->qstr($option) . ','
                                                                           . $dbconn->qstr($value) . ','
                                                                           . $dbconn->qstr($attr_value) . ")");
                  }
                }
              }
            }
          }

          $this->cleanup();

          // assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
          $this->cartID = $this->generate_cart_id();
        }
      }
    }


    function update_quantity($products_id, $nQuantity = '', $attributes = '', $towlid = '') {

      $sProductsId = oos_get_uprid($products_id, $attributes);
      $nProductsId = oos_get_product_id($sProductsId);

      if (is_numeric($nProductsId) && isset($this->contents[$sProductsId]) && is_numeric($nQuantity)) {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        if (DECIMAL_CART_QUANTITY == 'true') {
          $productstable = $oostable['products'];
          $decimal_sql = "SELECT products_quantity_decimal
                            FROM $productstable
                           WHERE products_id = '" . intval($nProductsId) . "'";
          $products_quantity_decimal = $dbconn->GetOne($decimal_sql);
          if ($products_quantity_decimal == 0) {
            $nQuantity = intval($nQuantity);
          }
        }


        $this->contents[$sProductsId] = array('qty' => $nQuantity,
                                              'towlid' => $towlid);

        if (isset($_SESSION['customer_id'])) {
          $customers_baskettable = $oostable['customers_basket'];
          $dbconn->Execute("UPDATE $customers_baskettable
                            SET customers_basket_quantity = '" . oos_db_input($nQuantity) . "'
                            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' AND
                                  products_id = '" . oos_db_input($sProductsId) . "'");
        }

        if (is_array($attributes)) {
          reset($attributes);
          while (list($option, $value) = each($attributes)) {
            $attr_value = NULL;
            $blank_value = FALSE;
            if (strstr($option, TEXT_PREFIX)) {
              if (trim($value) == NULL) {
                $blank_value = TRUE;
              } else {
                $option = substr($option, strlen(TEXT_PREFIX));
                // $attr_value = htmlspecialchars(stripslashes($value), ENT_QUOTES);
                $attr_value = stripslashes($value);
                $value = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
                $this->contents[$sProductsId]['attributes_values'][$option] = $attr_value;
              }
            }

            if (!$blank_value) {
              $this->contents[$sProductsId]['attributes'][$option] = $value;
              // update database
              if (isset($_SESSION['customer_id'])) {
                 $customers_basket_attributestable = $oostable['customers_basket_attributes'];
                 $dbconn->Execute("UPDATE $customers_basket_attributestable
                               SET products_options_value_id = '" . oos_db_input($value) . "',
                                   products_options_value_text = '" .  oos_db_input($attr_value) . "'
                               WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
                                 AND products_id = '" . oos_db_input($sProductsId) . "'
                                 AND products_options_id = '" . oos_db_input($option) . "'");
              }
            }
          }
        }
      }
    }

    function cleanup() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      if (DECIMAL_CART_QUANTITY == 'true') {
        $check_quantity = 0.01;
      } else {
        $check_quantity = 1;
      }

      reset($this->contents);
      while (list($key,) = each($this->contents)) {
        if ($this->contents[$key]['qty'] < $check_quantity) {
          unset($this->contents[$key]);
          // remove from database
          if (isset($_SESSION['customer_id'])) {
            $customers_baskettable = $oostable['customers_basket'];
            $dbconn->Execute("DELETE FROM $customers_baskettable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' AND products_id = '" . oos_db_input($key) . "'");
            $customers_basket_attributestable = $oostable['customers_basket_attributes'];
            $dbconn->Execute("DELETE FROM $customers_basket_attributestable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' AND products_id = '" . oos_db_input($key) . "'");
          }
        }
      }
    }


    function count_contents() {  // get total number of items in cart
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $total_items += $this->get_quantity($products_id);
        }
      }

      return $total_items;
    }


    function get_quantity($products_id) {
      if (isset($this->contents[$products_id])) {
        $nQuantity = $this->contents[$products_id]['qty'];
        if (DECIMAL_CART_QUANTITY != 'true') {
          $nQuantity = intval($nQuantity);
        }
        return $nQuantity;
      } else {
        return 0;
      }
    }

    function in_cart($products_id) {
      if (isset($this->contents[$products_id])) {
        return true;
      } else {
        return false;
      }
    }

    function remove($products_id) {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      unset($this->contents[$products_id]);
      // remove from database
      if (isset($_SESSION['customer_id'])) {
        $customers_baskettable = $oostable['customers_basket'];
        $dbconn->Execute("DELETE FROM $customers_baskettable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' AND products_id = '" . oos_db_input($products_id) . "'");
        $customers_basket_attributestable = $oostable['customers_basket_attributes'];
        $dbconn->Execute("DELETE FROM $customers_basket_attributestable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' AND products_id = '" . oos_db_input($products_id) . "'");
      }

      // assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function remove_all() {
      $this->reset();
    }

    function get_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $product_id_list .= ', ' . $products_id;
        }
      }

      return substr($product_id_list, 2);
    }

    function get_numeric_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $product_id_list .= ', ' . oos_get_product_id($products_id);
        }
      }

      return substr($product_id_list, 2);
    }

    function calculate() {

      $this->total_virtual = 0; // Gift Voucher System
      $this->weight_virtual = 0;
      $this->total = 0;
      $this->weight = 0;
      if (!is_array($this->contents)) return 0;

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
        $nQuantity = $this->contents[$products_id]['qty'];

        // products price
        $productstable = $oostable['products'];
        $product_sql = "SELECT products_id, products_model, products_price, products_discount_allowed,
                               products_tax_class_id, products_weight
                       FROM $productstable
                       WHERE products_id='" . oos_get_product_id($products_id) . "'";
        $product_result = $dbconn->Execute($product_sql);
        if ($product = $product_result->fields) {
          $no_count = 1;
          if (preg_match('/^GIFT/', $product['products_model'])) {
            $no_count = 0;
          }

          $prid = $product['products_id'];
          $products_tax = oos_get_tax_rate($product['products_tax_class_id']);
          if ($_SESSION['member']->group['qty_discounts'] == 1) {
            $products_price = $this->products_price_actual($prid, $product['products_price'], $nQuantity);
          } else {
            $products_price = $product['products_price'];
          }
/*
			remove $_SESSION['member']->group['discount']
          $max_product_discount = min($product['products_discount_allowed'], $_SESSION['member']->group['discount']);
          $products_price = $products_price*(100-$max_product_discount)/100;
*/
          $products_weight = $product['products_weight'];
          $bSpezialPrice = false;

          $specialstable = $oostable['specials'];
          $sql = "SELECT specials_new_products_price
                  FROM $specialstable
                  WHERE products_id = '" . intval($prid) . "'
                  AND   status = '1'";
          $specials_result = $dbconn->Execute($sql);
          if ($specials_result->RecordCount()) {
            $specials = $specials_result->fields;
            $products_price = $specials['specials_new_products_price'];
            $bSpezialPrice = true;
          }

          $this->total_virtual +=  oos_add_tax($products_price, $products_tax) * $nQuantity * $no_count;
          $this->weight_virtual += ($nQuantity * $products_weight) * $no_count;
          $this->total += oos_add_tax($products_price, $products_tax) * $nQuantity;
          $this->weight += ($nQuantity * $products_weight);
        }

        // attributes price
        if (isset($this->contents[$products_id]['attributes'])) {
          reset($this->contents[$products_id]['attributes']);
          while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
            $products_attributestable = $oostable['products_attributes'];
            $sql = "SELECT options_values_price, price_prefix
                    FROM $products_attributestable
                    WHERE products_id = '" . intval($prid) . "'
                    AND options_id = '" . intval($option) . "'
                    AND options_values_id = '" . intval($value) . "'";
            $attribute_price = $dbconn->GetRow($sql);

            $sAttributesPrice = $attribute_price['options_values_price'];
            if ($bSpezialPrice === false) {
              $sAttributesPrice = $sAttributesPrice*(100-$max_product_discount)/100;
            }

            if ($attribute_price['price_prefix'] == '+') {
              $this->total += $nQuantity * oos_add_tax($sAttributesPrice, $products_tax);
            } else {
              $this->total -= $nQuantity * oos_add_tax($sAttributesPrice, $products_tax);
            }
          }
        }
      }
    }


    function products_price_actual($product_id, $actual_price, $products_qty) {

      $new_price = $actual_price;

      if ($new_special_price = oos_get_products_special_price($product_id)) {
        $new_price = $new_special_price;
      }

      if ($new_discounts_price = oos_get_products_price_quantity_discount($product_id, $products_qty, $new_price)){
        $new_price = $new_discounts_price;
      }
      return $new_price;
    }


    function attributes_price($products_id) {

      $attributes_price = 0;

      if (isset($this->contents[$products_id]['attributes'])) {
        reset($this->contents[$products_id]['attributes']);

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
          $products_attributestable = $oostable['products_attributes'];
          $attribute_price_sql = "SELECT options_values_price, price_prefix
                                  FROM $products_attributestable
                                  WHERE products_id = '" . intval($products_id) . "'
                                  AND options_id = '" . intval($option) . "'
                                  AND options_values_id = '" . intval($value) . "'";
          $attribute_price = $dbconn->GetRow($attribute_price_sql);

          if ($attribute_price['price_prefix'] == '+') {
            $attributes_price += $attribute_price['options_values_price'];
          } else {
            $attributes_price -= $attribute_price['options_values_price'];
          }
        }
      }

      return $attributes_price;
    }

    function get_products() {

      if (!is_array($this->contents)) return false;

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : DEFAULT_CUSTOMERS_STATUS_ID;

      $products_array = array();
      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
        $nQuantity = $this->contents[$products_id]['qty'];
        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $sql = "SELECT p.products_id, pd.products_name, p.products_image, p.products_model, p.products_ean,
                       p.products_price, p.products_discount_allowed, p.products_weight, p.products_tax_class_id
                FROM $productstable p,
                     $products_descriptiontable pd
                WHERE p.products_id = '" . oos_get_product_id($products_id) . "' AND
                      pd.products_id = p.products_id AND
                      pd.products_languages_id = '" .  intval($nLanguageID) . "'";
        $products_result = $dbconn->Execute($sql);
        if ($products = $products_result->fields) {
          $prid = $products['products_id'];
          if ($_SESSION['member']->group['qty_discounts'] == 1) {
            $products_price = $this->products_price_actual($prid, $products['products_price'], $nQuantity);
          } else {
            $products_price = $products['products_price'];
          }
/*
			remove $_SESSION['member']->group['discount']
          $max_product_discount = min($products['products_discount_allowed'], $_SESSION['member']->group['discount']);
          $products_price = $products_price*(100-$max_product_discount)/100;
*/

          $spezial_price = 'false';
          $specialstable = $oostable['specials'];
          $sql = "SELECT specials_new_products_price
                  FROM $specialstable
                  WHERE products_id = '" . intval($prid) . "' AND
                        status = '1'";
          $specials_result = $dbconn->Execute($sql);
          if ($specials_result->RecordCount()) {
            $spezial_price = 'true';
            $specials = $specials_result->fields;
            $products_price = $specials['specials_new_products_price'];
          }

          $attributes_price = $this->attributes_price($products_id);
          if ($spezial_price == 'false') {
            $attributes_price = $attributes_price*(100-$max_product_discount)/100;
          }


          $products_array[] = array('id' => $products_id,
                                    'name' => $products['products_name'],
                                    'model' => $products['products_model'],
                                    'image' => $products['products_image'],
                                    'ean' => $products['products_ean'],
                                    'price' => $products_price,
                                    'spezial' => $spezial_price,
                                    'discount_allowed' => $products['products_discount_allowed'],
                                    'quantity' => $this->contents[$products_id]['qty'],
                                    'weight' => $products['products_weight'],
                                    'final_price' => ($products_price + $attributes_price),
                                    'tax_class_id' => $products['products_tax_class_id'],
                                    'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''),
                                    'attributes_values' => (isset($this->contents[$products_id]['attributes_values']) ? $this->contents[$products_id]['attributes_values'] : ''),
                                    'towlid' => $this->contents[$products_id]['towlid']);
        }
      }

      return $products_array;
    }

    function show_total() {
      $this->calculate();

      return $this->total;
    }

    function show_weight() {
      $this->calculate();

      return $this->weight;
    }

    function show_total_virtual() {
      $this->calculate();

      return $this->total_virtual;
    }

    function show_weight_virtual() {
      $this->calculate();

      return $this->weight_virtual;
    }


    function generate_cart_id($length = 5) {
      return oos_create_random_value($length, 'digits');
    }

    function get_content_type() {

      $this->content_type = false;

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) || ($this->show_weight() == 0 )&& ($this->count_contents() > 0) ) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          if (isset($this->contents[$products_id]['attributes'])) {
            reset($this->contents[$products_id]['attributes']);
            while (list(, $value) = each($this->contents[$products_id]['attributes'])) {
              $products_attributestable = $oostable['products_attributes'];
              $products_attributes_downloadtable = $oostable['products_attributes_download'];
              $sql = "SELECT COUNT(*) AS total
                      FROM $products_attributestable pa,
                           $products_attributes_downloadtable pad
                      WHERE pa.products_id = '" . intval($products_id) . "'
                      AND pa.options_values_id = '" . intval($value) . "'
                      AND pa.products_attributes_id = pad.products_attributes_id";
              $virtual_check = $dbconn->GetRow($sql);

              if ($virtual_check['total'] > 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }

          } elseif ($this->show_weight() == 0) {
            reset($this->contents);
            while (list($products_id, ) = each($this->contents)) {
              $productstable = $oostable['products'];
              $sql = "SELECT products_weight
                      FROM $productstable
                      WHERE products_id = '" . intval($products_id) . "'";
              $virtual_check_result = $dbconn->Execute($sql);
              $virtual_check = $virtual_check_result->fields;
              if ($virtual_check['products_weight'] == 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual_weight';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
          } else {
            switch ($this->content_type) {
              case 'virtual':
                $this->content_type = 'mixed';

                return $this->content_type;
                break;
              default:
                $this->content_type = 'physical';
                break;
            }
          }
        }
      } else {
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }

    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }

   /**
    * ICWILSON CREDIT CLASS Gift Voucher Addittion Start
    * amend count_contents to show nil contents for shipping
    * as we don't want to quote for 'virtual' item
    * GLOBAL CONSTANTS if NO_COUNT_ZERO_WEIGHT is true then we don't count any product with a weight
    * which is less than or equal to MINIMUM_WEIGHT
    * otherwise we just don't count gift certificates
    */
    function count_contents_virtual() {  // get total number of items in cart disregard gift vouchers

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $no_count = false;
          $productstable = $oostable['products'];
          $sql = "SELECT products_model
                  FROM $productstable
                  WHERE products_id = '" . intval($products_id) . "'";
          $gv_result  = $dbconn->GetRow($sql);

          if (preg_match('/^GIFT/', $gv_result['products_model'])) {
            $no_count = true;
          }
          if (NO_COUNT_ZERO_WEIGHT == 1) {
            $productstable = $oostable['products'];
            $sql = "SELECT products_weight
                    FROM $productstable
                    WHERE products_id = '" . oos_get_product_id($products_id) . "'";
            $gv_result  = $dbconn->GetRow($sql);

            if ($gv_result['products_weight']<=MINIMUM_WEIGHT) {
              $no_count = true;
            }
          }
          if (!$no_count) $total_items += $this->get_quantity($products_id);
        }
      }
      return $total_items;
    }
  }

