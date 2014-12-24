<?php
/* ----------------------------------------------------------------------
   $Id: ot_coupon.php,v 1.4 2007/12/23 22:59:27 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_coupon.php,v 1.1.2.36 2003/05/14 22:52:59 wilt 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class ot_coupon {
    var $title, $output, $enabled = FALSE;

    function ot_coupon() {
      global $aLang;

      $this->code = 'ot_coupon';
      $this->header = $aLang['module_order_total_coupon_header'];
      $this->title =$aLang['module_order_total_coupon_title'];
      $this->description = $aLang['module_order_total_coupon_description'];
      $this->user_prompt = '';
      $this->enabled = (defined('MODULE_ORDER_TOTAL_COUPON_STATUS') && (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') ? true : false);
      $this->sort_order = (defined('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER') ? MODULE_ORDER_TOTAL_COUPON_SORT_ORDER : null);
      $this->include_shipping = (defined('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING') ? MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING : null);
      $this->include_tax = (defined('MODULE_ORDER_TOTAL_COUPON_INC_TAX') ? MODULE_ORDER_TOTAL_COUPON_INC_TAX : null);
      $this->calculate_tax = (defined('MODULE_ORDER_TOTAL_COUPON_CALC_TAX') ? MODULE_ORDER_TOTAL_COUPON_CALC_TAX : null);
      $this->tax_class  = (defined('MODULE_ORDER_TOTAL_COUPON_TAX_CLASS') ? MODULE_ORDER_TOTAL_COUPON_TAX_CLASS : null);
      $this->credit_class = TRUE;

      $this->output = array();

    }

  function process() {
    global $oOrder, $oCurrencies;

    $order_total = $this->get_order_total();
    $od_amount = $this->calculate_credit($order_total);

    $this->deduction = $od_amount;
    if ($this->calculate_tax != 'none') {
      $tod_amount = $this->calculate_tax_deduction($order_total, $this->deduction, $this->calculate_tax);
    }
    if ($od_amount > 0) {
      $oOrder->info['total'] = $oOrder->info['total'] - $od_amount;
      $this->output[] = array('title' => '<font color="#FF0000">' . $this->title . ':' . $this->coupon_code .':</font>',
                              'text' => '<strong><font color="#FF0000"> - ' . $oCurrencies->format($od_amount) . '</font></strong>',
                              'value' => $od_amount);
    }
  }

  function selection_test() {
    return FALSE;
  }


  function pre_confirmation_check($order_total) {
    return $this->calculate_credit($order_total);
  }

  function use_credit_amount() {
    return $output_string;
  }


  function credit_selection() {
    global $aLang;
    global $oCurrencies;

    $sTheme = oos_var_prep_for_os($_SESSION['theme']);
    $sLanguage = isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;
    $image_submit = '<input type="image" name="submit_redeem" onClick="submitFunction()" src="' . 'themes/' . $sTheme . '/images/buttons/' . $sLanguage . '/redeem.gif" border="0" alt="' . $aLang['image_button_redeem_voucher'] . '" title = "' . $aLang['image_button_redeem_voucher'] . '">';

    $selection_string = '';
    $selection_string .= '<tr>' . "\n";
    $selection_string .= '  <td width="10"></td>';
    $selection_string .= '  <td class="main">' . "\n";
    $selection_string .= $aLang['text_enter_coupon_code'] . oos_draw_input_field('gv_redeem_code') . '</td>';
    $selection_string .= '  <td align="right">' . $image_submit . '</td>';
    $selection_string .= '  <td width="10"></td>';
    $selection_string .= '</tr>' . "\n";

    return $selection_string;
  }


  function collect_posts() {
    global $oCurrencies, $aLang;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aContents = oos_get_content();

    if ($_POST['gv_redeem_code']) {
      // get some info from the coupon table
      $couponstable = $oostable['coupons'];
      $sql = "SELECT coupon_id, coupon_amount, coupon_type, coupon_minimum_order,
                     uses_per_coupon, uses_per_user, restrict_to_products,
                     restrict_to_categories 
              FROM $couponstable
              WHERE coupon_code = '" . oos_db_input($_POST['gv_redeem_code']). "'
                AND coupon_active = 'Y'";
      $coupon_query = $dbconn->Execute($sql);
      $coupon_result = $coupon_query->fields;

      if ($coupon_result['coupon_type'] != 'G') {

        if ($coupon_query->RecordCount() == 0) {
          oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode(decode($aLang['error_no_invalid_redeem_coupon'])), 'SSL'));
        }

        $couponstable = $oostable['coupons'];
        $sql = "SELECT coupon_start_date
                FROM $couponstable
                WHERE coupon_start_date <= now()
                AND   coupon_code= '" . oos_db_input($_POST['gv_redeem_code']) . "'";
        $date_query = $dbconn->Execute($sql);
        if ($date_query->RecordCount() == 0) {
          oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode(decode($aLang['error_invalid_startdate_coupon'])), 'SSL'));
        }

        $couponstable = $oostable['coupons'];
        $sql = "SELECT coupon_expire_date
                FROM $couponstable
                WHERE coupon_expire_date >= now()
                AND   coupon_code= '" . oos_db_input($_POST['gv_redeem_code']) . "'";
        $date_query = $dbconn->Execute($sql);
        if ($date_query->RecordCount() == 0) {
          oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode(decode($aLang['error_invalid_finisdate_coupon'])), 'SSL'));
        }

        $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
        $sql = "SELECT coupon_id
                FROM $coupon_redeem_tracktable
                WHERE coupon_id = '" . $coupon_result['coupon_id']."'";
        $coupon_count = $dbconn->Execute($sql);

        $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
        $sql = "SELECT coupon_id
                FROM $coupon_redeem_tracktable
                WHERE coupon_id = '" . $coupon_result['coupon_id']."'
                AND   customer_id = '" . intval($_SESSION['customer_id']) . "'";
        $coupon_count_customer = $dbconn->Execute($sql);

        if ($coupon_count->RecordCount()>=$coupon_result['uses_per_coupon'] && $coupon_result['uses_per_coupon'] > 0) {
          oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode($aLang['error_invalid_uses_coupon'] . $coupon_result['uses_per_coupon'] . $aLang['times'] ), 'SSL'));
        }

        if ($coupon_count_customer->RecordCount()>=$coupon_result['uses_per_user'] && $coupon_result['uses_per_user'] > 0) {
          oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode($aLang['error_invalid_uses_user_coupon'] . $coupon_result['uses_per_user'] . $aLang['times'] ), 'SSL'));
        }
        if ($coupon_result['coupon_type'] == 'S') {
          $coupon_amount = $oOrder->info['shipping_cost'];
        } else {
          $coupon_amount = $oCurrencies->format($coupon_result['coupon_amount']) . ' ';
        }
        if ($coupon_result['type']=='P') $coupon_amount = $coupon_result['coupon_amount'] . '% ';
        if ($coupon_result['coupon_minimum_order']>0) $coupon_amount .= 'on orders greater than ' .  $coupon_result['coupon_minimum_order'];
        $_SESSION['cc_id'] = $coupon_result['coupon_id'];
      }
      if ($_POST['submit_redeem_coupon_x'] && !$_POST['gv_redeem_code']) oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode(decode($aLang['error_no_invalid_redeem_coupon'])), 'SSL'));
    }
  }


  function calculate_credit($amount) {
    global $oOrder;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $od_amount = 0;
    if (isset($_SESSION['cc_id'])) {
      $cc_id = intval($_SESSION['cc_id']);

      $couponstable = $oostable['coupons'];
      $coupon_query = $dbconn->Execute("SELECT coupon_code FROM $couponstable WHERE coupon_id = '" . intval($cc_id) . "'");

      if ($coupon_query->RecordCount() !=0 ) {
        $coupon_result = $coupon_query->fields;
        $this->coupon_code = $coupon_result['coupon_code'];

        $couponstable = $oostable['coupons'];
        $coupon_get = $dbconn->Execute("SELECT coupon_amount, coupon_minimum_order, restrict_to_products, restrict_to_categories, coupon_type FROM $couponstable WHERE coupon_code = '". $coupon_result['coupon_code'] . "'");

        $get_result = $coupon_get->fields;
        $c_deduct = $get_result['coupon_amount'];

        if ($get_result['coupon_type'] == 'S') $c_deduct = $oOrder->info['shipping_cost'];
        if ($get_result['coupon_minimum_order'] <= $this->get_order_total()) {
          if ($get_result['restrict_to_products'] || $get_result['restrict_to_categories']) {
            for ($i=0; $i<count($oOrder->products); $i++) {
              if ($get_result['restrict_to_products']) {
                $pr_ids = explode("[,]", $get_result['restrict_to_products']);
                for ($ii = 0; $ii < count($pr_ids); $ii++) {
                  if ($pr_ids[$ii] == oos_get_product_id($oOrder->products[$i]['id'])) {
                    if ($get_result['coupon_type'] == 'P') {
                      $od_amount = round($amount*10)/10*$c_deduct/100;
                      $pr_c = $oOrder->products[$i]['final_price']*$oOrder->products[$i]['qty'];
                      $pod_amount = round($pr_c*10)/10*$c_deduct/100;
                    } else {
                      $od_amount = $c_deduct;
                    }
                  }
                }
              } else {
                $cat_ids = explode("[,]", $get_result['restrict_to_categories']);
                for ($i=0; $i<count($oOrder->products); $i++) {
                  $my_path = oos_get_product_path(oos_get_product_id($oOrder->products[$i]['id']));
                  $sub_cat_ids = explode("[_]", $my_path);
                  for ($iii = 0; $iii < count($sub_cat_ids); $iii++) {
                    for ($ii = 0; $ii < count($cat_ids); $ii++) {
                      if ($sub_cat_ids[$iii] == $cat_ids[$ii]) {
                        if ($get_result['coupon_type'] == 'P') {
                          $od_amount = round($amount*10)/10*$c_deduct/100;
                          $pr_c = $oOrder->products[$i]['final_price']*$oOrder->products[$i]['qty'];
                          $pod_amount = round($pr_c*10)/10*$c_deduct/100;
                        } else {
                          $od_amount = $c_deduct;
                        }
                      }
                    }
                  }
                }
              }
            }
          } else {
            if ($get_result['coupon_type'] !='P') {
              $od_amount = $c_deduct;
            } else {
              $od_amount = $amount * $get_result['coupon_amount'] / 100;
            }
          }
        }
      }
      if ($od_amount>$amount) $od_amount = $amount;
    }
    return $od_amount;
  }

  function calculate_tax_deduction($amount, $od_amount, $method) {
    global $oOrder, $cc_id;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $cc_id = intval($_SESSION['cc_id']);

    $couponstable = $oostable['coupons'];
    $coupon_query = $dbconn->Execute("SELECT coupon_code FROM $couponstable WHERE coupon_id = '" . intval($cc_id) . "'");

    if ($coupon_query->RecordCount() !=0 ) {
      $coupon_result = $coupon_query->fields;
      $coupon_get = $dbconn->Execute("SELECT coupon_amount, coupon_minimum_order, restrict_to_products, restrict_to_categories, coupon_type FROM " . $oostable['coupons'] . " WHERE coupon_code = '". $coupon_result['coupon_code'] . "'");
      $get_result = $coupon_get->fields;
      if ($get_result['coupon_type'] != 'S') {
      if ($get_result['restrict_to_products'] || $get_result['restrict_to_categories']) {
        // What to do here.
        // Loop through all products and build a list of all product_ids, price, tax class
        // at the same time create total net amount.
        // then
        // for percentage discounts. simply reduce tax group per product by discount percentage
        // or
        // for fixed payment amount
        // calculate ratio based on total net 
        // for each product reduce tax group per product by ratio amount.
        $products = $_SESSION['cart']->get_products();
        for ($i=0; $i<count($products); $i++) {
          $t_prid = oos_get_product_id($products[$i]['id']);

          $productstable = $oostable['products'];
          $cc_query = $dbconn->Execute("SELECT products_tax_class_id FROM $productstable WHERE products_id = '" . (int)$t_prid . "'");
          $cc_result = $cc_query->fields;
          $valid_product = FALSE;

          if ($get_result['restrict_to_products']) {
            $pr_ids = explode("[,]", $get_result['restrict_to_products']);
            for ($p = 0; $p < count($pr_ids); $p++) {
              if ($pr_ids[$p] == $t_prid) $valid_product = TRUE;
            }
          }
          if ($get_result['restrict_to_categories']) {
            $cat_ids = explode("[,]", $get_result['restrict_to_categories']);
            for ($c = 0; $c < count($cat_ids); $c++) {

              $products_to_categoriestable = $oostable['products_to_categories'];
              $cat_query = $dbconn->Execute("SELECT products_id FROM $products_to_categoriestable WHERE products_id = '" . (int)$products_id . "' AND categories_id = '" . (int)$cat_ids[$i] . "'");
              if ($cat_query->RecordCount() !=0 ) $valid_product = TRUE;
            }
          }

          if ($valid_product) {
            $valid_array[] = array('product_id' => $t_prid,
                                   'products_price' => $products[$i]['final_price'] * $products[$i]['quantity'],
                                   'products_tax_class' => $cc_result['products_tax_class_id']);
            $total_price += $products[$i]['final_price'] * $products[$i]['quantity'];
          }
        }
        if ($valid_product) {
        if ($get_result['coupon_type'] == 'P') {
          $ratio = $get_result['coupon_amount']/100;
        } else {
          $ratio = $od_amount / $total_price;
        }
        if ($get_result['coupon_type'] == 'S') $ratio = 1; 
          if ($method=='Credit Note') {
            $tax_rate = oos_get_tax_rate($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
            $tax_desc = oos_get_tax_description($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
            if ($get_result['coupon_type'] == 'P') {
              $tod_amount = $od_amount / (100 + $tax_rate)* $tax_rate;
            } else {
              $tod_amount = $oOrder->info['tax_groups'][$tax_desc] * $od_amount/100;
            }
            $oOrder->info['tax_groups'][$tax_desc] -= $tod_amount;
            $oOrder->info['total'] -= $tod_amount;
          } else {     
            for ($p=0; $p<count($valid_array); $p++) {
              $tax_rate = oos_get_tax_rate($valid_array[$p]['products_tax_class'], $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
              $tax_desc = oos_get_tax_description($valid_array[$p]['products_tax_class'], $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
              if ($tax_rate > 0) {
                $tod_amount[$tax_desc] += ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio;
                $oOrder->info['tax_groups'][$tax_desc] -= ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio;
                $oOrder->info['total'] -= ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio;
              }
            }
          }
        }
      } else {
        if ($get_result['coupon_type'] =='F') {
          $tod_amount = 0;
          if ($method=='Credit Note') {
            $tax_rate = oos_get_tax_rate($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
            $tax_desc = oos_get_tax_description($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
            $tod_amount = $od_amount / (100 + $tax_rate)* $tax_rate;
            $oOrder->info['tax_groups'][$tax_desc] -= $tod_amount;
          } else {  
            $ratio1 = $od_amount/$amount;
            reset($oOrder->info['tax_groups']);
            while (list($key, $value) = each($oOrder->info['tax_groups'])) {
              $tax_rate = oos_get_tax_rate_from_desc($key);
              $net = $tax_rate * $oOrder->info['tax_groups'][$key];
              if ($net>0) {
                $god_amount = $oOrder->info['tax_groups'][$key] * $ratio1;
                $tod_amount += $god_amount;
                $oOrder->info['tax_groups'][$key] = $oOrder->info['tax_groups'][$key] - $god_amount;
              }
            }
          }
          $oOrder->info['total'] -= $tod_amount;
        }
        if ($get_result['coupon_type'] =='P') {
          $tod_amount=0;
          if ($method=='Credit Note') {
            $tax_desc = oos_get_tax_description($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
            $tod_amount = $oOrder->info['tax_groups'][$tax_desc] * $od_amount/100;
            $oOrder->info['tax_groups'][$tax_desc] -= $tod_amount;
          } else {
            reset($oOrder->info['tax_groups']);
            while (list($key, $value) = each($oOrder->info['tax_groups'])) {
              $god_amout=0;
              $tax_rate = oos_get_tax_rate_from_desc($key);
              $net = $tax_rate * $oOrder->info['tax_groups'][$key];
              if ($net>0) {
                $god_amount = $oOrder->info['tax_groups'][$key] * $get_result['coupon_amount']/100;
                $tod_amount += $god_amount;
                $oOrder->info['tax_groups'][$key] = $oOrder->info['tax_groups'][$key] - $god_amount;
              }
            }
          }
          $oOrder->info['tax'] -= $tod_amount;
        }
      }
    }
    }
    return $tod_amount;
  }

 function update_credit_account($i) {
  return FALSE;
 }

 function apply_credit() {
   global $insert_id;

   $cc_id = intval($_SESSION['cc_id']);
   $remote_addr = oos_server_get_remote();

   if ($this->deduction !=0) {
      // Get database information
     $dbconn =& oosDBGetConn();
     $oostable =& oosDBGetTables();

     $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
     $dbconn->Execute("INSERT INTO $coupon_redeem_tracktable (coupon_id, redeem_date, redeem_ip, customer_id, order_id) VALUES ('" . oos_db_input($cc_id) . "', now(), '" . oos_db_input($remote_addr) . "', '" . intval($_SESSION['customer_id']) . "', '" . intval($insert_id) . "')");
   }
   unset($_SESSION['cc_id']);
 }


  function get_order_total() {
    global  $oOrder;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $cc_id = $_SESSION['cc_id'];
    $order_total = $oOrder->info['total'];
// Check if gift voucher is in cart and adjust total
    $products = $_SESSION['cart']->get_products();
    for ($i=0; $i<count($products); $i++) {
      $t_prid = oos_get_product_id($products[$i]['id']);

      $productstable = $oostable['products'];
      $gv_query = $dbconn->Execute("SELECT products_price, products_tax_class_id, products_model FROM $productstable WHERE products_id = '" . intval($t_prid) . "'");
      $gv_result = $gv_query->fields;
      if (preg_match('/^GIFT/', addslashes($gv_result['products_model']))) {
        $qty = $_SESSION['cart']->get_quantity($t_prid);
        $products_tax = oos_get_tax_rate($gv_result['products_tax_class_id']);
        if ($this->include_tax =='false') {
           $gv_amount = $gv_result['products_price'] * $qty;
        } else {
          $gv_amount = ($gv_result['products_price'] + oos_calculate_tax($gv_result['products_price'],$products_tax)) * $qty;
        }
        $order_total=$order_total - $gv_amount;
      }
    }
    if ($this->include_tax == 'false') $order_total=$order_total-$oOrder->info['tax'];
    if ($this->include_shipping == 'false') $order_total=$order_total-$oOrder->info['shipping_cost'];
    // OK thats fine for global coupons but what about restricted coupons 
    // where you can only redeem against certain products/categories.
    // and I though this was going to be easy !!!

   $couponstable = $oostable['coupons'];
   $coupon_query=$dbconn->Execute("SELECT coupon_code  FROM $couponstable WHERE coupon_id = '" . oos_db_input($cc_id) . "'");
   if ($coupon_query->RecordCount() !=0) {
     $coupon_result = $coupon_query->fields;

     $couponstable = $oostable['coupons'];
     $coupon_get = $dbconn->Execute("SELECT coupon_amount, coupon_minimum_order,restrict_to_products,restrict_to_categories, coupon_type FROM $couponstable WHERE coupon_code = '" . $coupon_result['coupon_code'] . "'");
     $get_result = $coupon_get->fields;
     $in_cat = TRUE;
     if ($get_result['restrict_to_categories']) {
       $cat_ids = explode("[,]", $get_result['restrict_to_categories']);
       $in_cat=false;
       for ($i = 0; $i < count($cat_ids); $i++) {
         if (is_array($this->contents)) {
           reset($this->contents);
           while (list($products_id, ) = each($this->contents)) {

            $products_to_categoriestable = $oostable['products_to_categories'];
            $cat_query = $dbconn->Execute("SELECT products_id FROM $products_to_categoriestable WHERE products_id = '" . (int)$products_id . "' AND categories_id = '" . (int)$cat_ids[$i] . "'");
             if ($cat_query->RecordCount() !=0 ) {
               $in_cat = TRUE;
               $total_price += $this->get_product_price($products_id);
             }
           }
         }
       }
     }
     $in_cart = TRUE;
     if ($get_result['restrict_to_products']) {

       $pr_ids = explode("[,]", $get_result['restrict_to_products']);

       $in_cart=false;
       $products_array = $_SESSION['cart']->get_products();

       for ($i = 0; $i < count($pr_ids); $i++) {
         for ($ii = 1; $ii<=count($products_array); $ii++) {
           if (oos_get_product_id($products_array[$ii-1]['id']) == $pr_ids[$i]) {
             $in_cart=true;
             $total_price += $this->get_product_price($products_array[$ii-1]['id']);
           }
         } 
       }
       $order_total = $total_price;
     }
   }
   return $order_total;
  }

function get_product_price($product_id) {
    global $oOrder;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_id = oos_get_product_id($product_id);
 // products price
    $qty = $_SESSION['cart']->contents[$product_id]['qty'];

    $productstable = $oostable['products'];
    $product_query = $dbconn->Execute("SELECT products_id, products_price, products_tax_class_id, products_weight FROM $productstable WHERE products_id='" . (int)$product_id . "'");
    if ($product = $product_query->fields) {
      $prid = $product['products_id'];
      $products_tax = oos_get_tax_rate($product['products_tax_class_id']);
      $products_price = $product['products_price'];

      $specialstable = $oostable['specials'];
      $specials_query = $dbconn->Execute("SELECT specials_new_products_price FROM $specialstable WHERE products_id = '" . (int)$prid . "' AND status = '1'");
      if ($specials_query->RecordCount()) {
        $specials = $specials_query->fields;
        $products_price = $specials['specials_new_products_price'];
      }
      if ($this->include_tax == 'true') {
        $total_price += ($products_price + oos_calculate_tax($products_price, $products_tax)) * $qty;
      } else {
        $total_price += $products_price * $qty;
      }

// attributes price
      if (isset($_SESSION['cart']->contents[$product_id]['attributes'])) {
        reset($_SESSION['cart']->contents[$product_id]['attributes']);
        while (list($option, $value) = each($_SESSION['cart']->contents[$product_id]['attributes'])) {

          $products_attributestable = $oostable['products_attributes'];
          $attribute_price_query = $dbconn->Execute("SELECT options_values_price, price_prefix FROM $products_attributestable WHERE products_id = '" . (int)$prid . "' AND options_id = '" . oos_db_input($option) . "' AND options_values_id = '" . oos_db_input($value) . "'");
          $attribute_price = $attribute_price_query->fields;
          if ($attribute_price['price_prefix'] == '+') {
            if ($this->include_tax == 'true') {
              $total_price += $qty * ($attribute_price['options_values_price'] + oos_calculate_tax($attribute_price['options_values_price'], $products_tax));
            } else {
              $total_price += $qty * ($attribute_price['options_values_price']);
            }
          } else {
            if ($this->include_tax == 'true') {
              $total_price -= $qty * ($attribute_price['options_values_price'] + oos_calculate_tax($attribute_price['options_values_price'], $products_tax));
            } else {
              $total_price -= $qty * ($attribute_price['options_values_price']);
            }
          }
        }
      }
    }
    if ($this->include_shipping == 'true') $total_price += $oOrder->info['shipping_cost'];
    return $total_price;
}

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_ORDER_TOTAL_COUPON_STATUS');
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_COUPON_STATUS', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', 'MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING', 'MODULE_ORDER_TOTAL_COUPON_INC_TAX', 'MODULE_ORDER_TOTAL_COUPON_CALC_TAX', 'MODULE_ORDER_TOTAL_COUPON_TAX_CLASS');
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '8', '6', '2', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING', 'true', '6', '5', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_INC_TAX', 'true', '6', '6','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_CALC_TAX', 'None', '6', '7','oos_cfg_select_option(array(\'None\', \'Standard\', \'Credit Note\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_TAX_CLASS', '0', '6', '0', 'oos_cfg_get_tax_class_title', 'oos_cfg_pull_down_tax_classes(', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }


