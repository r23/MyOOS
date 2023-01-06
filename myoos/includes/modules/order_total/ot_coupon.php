<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_coupon.php,v 1.4 2007/12/23 22:59:27 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_coupon.php,v 1.1.2.36 2003/05/14 22:52:59 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class ot_coupon
{
    public $title;
    public $coupon_code;
    public $output;
    public $enabled = false;

    public function __construct()
    {
        global $aLang;

        $this->code = 'ot_coupon';
        $this->header = $aLang['module_order_total_coupon_header'];
        $this->title = $aLang['module_order_total_coupon_title'];
        $this->description = $aLang['module_order_total_coupon_description'];
        $this->user_prompt = '';
        $this->enabled = (defined('MODULE_ORDER_TOTAL_COUPON_STATUS') && (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') ? true : false);
        $this->sort_order = (defined('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER') ? MODULE_ORDER_TOTAL_COUPON_SORT_ORDER : null);
        $this->include_shipping = (defined('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING') ? MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING : null);
        $this->tax_class = null; // todo remove
        $this->credit_class = true;
        $this->coupon_code = '';

        $this->output = [];
    }

    public function process()
    {
        global $oOrder, $oCurrencies;

        if (isset($_SESSION['cc_id'])) {
            $order_total = $oOrder->info['total'];
            $od_amount = $this->calculate_credit($order_total);

            if ($od_amount > 0) {
                $this->calculate_tax_deduction_order($order_total, $od_amount);
                $oOrder->info['total'] = $oOrder->info['total'] - $od_amount;
                $this->output[] = array('title' => '<span class="text-danger">' . $this->title . ' <strong>' . $this->coupon_code . '</strong>:</span>',
                                        'text' => '<span class="text-danger"><strong>-' . $oCurrencies->format($od_amount) . '</strong></span>',
                                        'info' => '',
                                        'value' => $od_amount);
            }
        }
    }

    public function shopping_cart_process()
    {
        global $oCurrencies;

        if (isset($_SESSION['cc_id'])) {
            $od_amount = 0;

            $total = $_SESSION['cart']->info['total'];
            $od_amount = $this->calculate_credit($total);

            if ($od_amount > 0) {
                $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
                $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

                $this->calculate_tax_deduction($total, $od_amount);
                $_SESSION['cart']->info['total'] = $_SESSION['cart']->info['total'] - $od_amount;

                $this->output[] = array('title' => '<span class="text-danger">' . $this->title . ':</span>',
                                'text' => '<span class="text-danger"><strong>-' .  $oCurrencies->format($od_amount, true, $currency_type, $currency_value) . '</strong></span>',
                                'info' => '',
                                'value' => $od_amount);
            }
        }
    }

    public function selection_test()
    {
        return false;
    }


    public function pre_confirmation_check($order_total)
    {
        return $this->calculate_credit($order_total);
    }

    public function use_credit_amount()
    {
        return $output_string;
    }


    public function credit_selection()
    {
        global $aLang;

        $selection_string .= '<div class="form-group">' . "\n";
        $selection_string .= '	<input class="form-control" type="text" name="gv_redeem_code" id="cart-promocode" placeholder="' . $aLang['text_apply_coupon'] . '">' . "\n";
        $selection_string .= '	<div class="invalid-feedback">' . $aLang['text_invalid_feedback'] . '</div>' . "\n";
        $selection_string .= '	</div>' . "\n";
        $selection_string .= '<button class="btn btn-outline-primary btn-block" type="submit">' . $aLang['button_apply_coupon'] . '</button>' . "\n";
        $selection_string .= '<div class="clearfix" style="height: 35px;"></div>' . "\n";

        return $selection_string;
    }


    public function collect_posts()
    {
        global $oOrder, $oCurrencies, $oMessage, $aLang;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $aContents = oos_get_content();

        if (isset($_POST['gv_redeem_code'])) {
            $gv_redeem_code = oos_prepare_input($_POST['gv_redeem_code']);

            if (empty($gv_redeem_code) || !is_string($gv_redeem_code)) {
                return;
            }


            // get some info from the coupon table
            $couponstable = $oostable['coupons'];
            $sql = "SELECT coupon_id, coupon_amount, coupon_type, coupon_minimum_order,
						uses_per_coupon, uses_per_user, restrict_to_products,
						restrict_to_categories 
					FROM $couponstable
					WHERE coupon_code = '" . oos_db_input($gv_redeem_code). "'
						AND coupon_active = 'Y'";
            $coupon_query = $dbconn->Execute($sql);

            if ($coupon_query->RecordCount() == 0) {
                $oMessage->add_session('danger', $aLang['error_no_invalid_redeem_coupon']);
                oos_redirect(oos_href_link($aContents['checkout_payment']));
            } else {
                $coupon_result = $coupon_query->fields;

                if ($coupon_result['coupon_type'] != 'G') {
                    $couponstable = $oostable['coupons'];
                    $sql = "SELECT coupon_start_date
						FROM $couponstable
						WHERE coupon_start_date <= now()
						AND   coupon_code= '" . oos_db_input($gv_redeem_code) . "'";
                    $date_query = $dbconn->Execute($sql);
                    if ($date_query->RecordCount() == 0) {
                        $oMessage->add_session('danger', $aLang['error_invalid_startdate_coupon']);
                    }
                }


                $couponstable = $oostable['coupons'];
                $sql = "SELECT coupon_expire_date
						FROM $couponstable
						WHERE coupon_expire_date >= now()
						AND   coupon_code= '" . oos_db_input($gv_redeem_code) . "'";
                $date_query = $dbconn->Execute($sql);
                if ($date_query->RecordCount() == 0) {
                    $oMessage->add_session('danger', $aLang['error_invalid_finisdate_coupon']);
                }


                $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
                $sql = "SELECT coupon_id
						FROM $coupon_redeem_tracktable
						WHERE coupon_id = '" . $coupon_result['coupon_id']."'";
                $coupon_count = $dbconn->Execute($sql);

                if ($coupon_count->RecordCount()>=$coupon_result['uses_per_coupon'] && $coupon_result['uses_per_coupon'] > 0) {
                    $couponstable = $oostable['coupons'];
                    $gv_update = $dbconn->Execute(
                        "UPDATE $couponstable
														SET coupon_active = 'N'
														WHERE coupon_code = '" . oos_db_input($gv_redeem_code). "'"
                    );
                    $oMessage->add_session('danger', sprintf($aLang['error_invalid_uses_coupon'], $coupon_result['uses_per_coupon']));
                }


                /*
                                // For this type of voucher the customer would need to be logged in. But we must allow guest orders in the store.
                                $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
                                $sql = "SELECT coupon_id
                                        FROM $coupon_redeem_tracktable
                                        WHERE coupon_id = '" . $coupon_result['coupon_id']."'
                                        AND   customer_id = '" . intval($_SESSION['customer_id']) . "'";
                                $coupon_count_customer = $dbconn->Execute($sql);

                                if ($coupon_count_customer->RecordCount()>=$coupon_result['uses_per_user'] && $coupon_result['uses_per_user'] > 0) {
                                    $_SESSION['error_message'] = $aLang['error_invalid_uses_user_coupon'] . $coupon_result['uses_per_user'] . $aLang['times'];
                                    oos_redirect(oos_href_link($aContents['checkout_payment']));
                                }
                */
                $order_total = $oOrder->info['total'];
                if ($coupon_result['coupon_minimum_order'] > $order_total) {
                    $missing = $coupon_result['coupon_minimum_order'] - $order_total;

                    $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
                    $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

                    $coupon_minimum_order =    $oCurrencies->format($coupon_result['coupon_minimum_order'], true, $currency, $currency_value);
                    $coupon_missing = $oCurrencies->format($missing, true, $currency, $currency_value);

                    $oMessage->add('danger', sprintf($aLang['error_coupon_minimum_order'], $coupon_minimum_order, $coupon_missing));
                }

                if ($oMessage->size('danger') == 0) {
                    $_SESSION['cc_id'] = $coupon_result['coupon_id'];
                } else {
                    oos_redirect(oos_href_link($aContents['checkout_payment']));
                }
            }
        }
    }


    public function shopping_cart_collect_posts()
    {
        global $oCurrencies, $oMessage, $aLang;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $aContents = oos_get_content();

        if (isset($_POST['gv_redeem_code'])) {
            $gv_redeem_code = oos_prepare_input($_POST['gv_redeem_code']);

            if (empty($gv_redeem_code) || !is_string($gv_redeem_code)) {
                return;
            }

            // get some info from the coupon table
            $couponstable = $oostable['coupons'];
            $sql = "SELECT coupon_id, coupon_amount, coupon_type, coupon_minimum_order,
						uses_per_coupon, uses_per_user, restrict_to_products,
						restrict_to_categories 
					FROM $couponstable
					WHERE coupon_code = '" . oos_db_input($gv_redeem_code). "'
						AND coupon_active = 'Y'";
            $coupon_query = $dbconn->Execute($sql);

            if ($coupon_query->RecordCount() == 0) {
                $oMessage->add('danger', $aLang['error_no_invalid_redeem_coupon']);
            } else {
                $coupon_result = $coupon_query->fields;

                if ($coupon_result['coupon_type'] != 'G') {
                    $couponstable = $oostable['coupons'];
                    $sql = "SELECT coupon_start_date
						FROM $couponstable
						WHERE coupon_start_date <= now()
						AND   coupon_code= '" . oos_db_input($gv_redeem_code) . "'";
                    $date_query = $dbconn->Execute($sql);
                    if ($date_query->RecordCount() == 0) {
                        $oMessage->add('danger', $aLang['error_invalid_startdate_coupon']);
                    }
                }


                $couponstable = $oostable['coupons'];
                $sql = "SELECT coupon_expire_date
						FROM $couponstable
						WHERE coupon_expire_date >= now()
						AND   coupon_code= '" . oos_db_input($gv_redeem_code) . "'";
                $date_query = $dbconn->Execute($sql);
                if ($date_query->RecordCount() == 0) {
                    $oMessage->add('danger', $aLang['error_invalid_finisdate_coupon']);
                }


                $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
                $sql = "SELECT coupon_id
						FROM $coupon_redeem_tracktable
						WHERE coupon_id = '" . $coupon_result['coupon_id']."'";
                $coupon_count = $dbconn->Execute($sql);

                if ($coupon_count->RecordCount()>=$coupon_result['uses_per_coupon'] && $coupon_result['uses_per_coupon'] > 0) {
                    $couponstable = $oostable['coupons'];
                    $gv_update = $dbconn->Execute(
                        "UPDATE $couponstable
														SET coupon_active = 'N'
														WHERE coupon_code = '" . oos_db_input($gv_redeem_code). "'"
                    );
                    $oMessage->add('danger', sprintf($aLang['error_invalid_uses_coupon'], $coupon_result['uses_per_coupon']));
                }

                /*
                                // For this type of voucher the customer would need to be logged in. But we must allow guest orders in the store.
                                $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
                                $sql = "SELECT coupon_id
                                        FROM $coupon_redeem_tracktable
                                        WHERE coupon_id = '" . $coupon_result['coupon_id']."'
                                        AND customer_id = '" . intval($_SESSION['customer_id']) . "'";
                                $coupon_count_customer = $dbconn->Execute($sql);

                                if ($coupon_count_customer->RecordCount()>=$coupon_result['uses_per_user'] && $coupon_result['uses_per_user'] > 0) {
                                    $oMessage->add('danger', sprintf($aLang['error_invalid_uses_user_coupon'], $coupon_result['uses_per_coupon']));
                                }
                */


                $total = $_SESSION['cart']->info['total'];
                if ($coupon_result['coupon_minimum_order'] > $total) {
                    $missing = $coupon_result['coupon_minimum_order'] - $total;

                    $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
                    $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

                    $coupon_minimum_order =    $oCurrencies->format($coupon_result['coupon_minimum_order'], true, $currency, $currency_value);
                    $coupon_missing = $oCurrencies->format($missing, true, $currency, $currency_value);

                    $oMessage->add('danger', sprintf($aLang['error_coupon_minimum_order'], $coupon_minimum_order, $coupon_missing));
                }

                if ($oMessage->size('danger') == 0) {
                    $_SESSION['cc_id'] = $coupon_result['coupon_id'];
                }
            }
        }
    }


    public function calculate_credit($amount)
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $od_amount = 0;

        if (isset($_SESSION['cc_id'])) {
            $cc_id = intval($_SESSION['cc_id']);

            $couponstable = $oostable['coupons'];
            $sql = "SELECT coupon_code, coupon_id, coupon_amount, coupon_type, coupon_minimum_order,
						uses_per_coupon, uses_per_user, restrict_to_products, restrict_to_categories 
					FROM $couponstable
					WHERE coupon_id = '" . intval($cc_id). "'
						AND coupon_active = 'Y'";
            $coupon_query = $dbconn->Execute($sql);

            if ($coupon_query->RecordCount() !=0) {
                $coupon_result = $coupon_query->fields;

                $couponstable = $oostable['coupons'];
                $sql = "SELECT coupon_start_date
					FROM $couponstable
					WHERE coupon_start_date <= now()
					AND   coupon_code= '" . oos_db_input($coupon_result['coupon_code']) . "'";
                $date_query = $dbconn->Execute($sql);
                if ($date_query->RecordCount() == 0) {
                    unset($_SESSION['cc_id']);
                    return 0;
                }

                $couponstable = $oostable['coupons'];
                $sql = "SELECT coupon_expire_date
						FROM $couponstable
						WHERE coupon_expire_date >= now()
						AND   coupon_code= '" . oos_db_input($coupon_result['coupon_code']) . "'";
                $date_query = $dbconn->Execute($sql);
                if ($date_query->RecordCount() == 0) {
                    unset($_SESSION['cc_id']);
                    return 0;
                }

                $this->coupon_code = $coupon_result['coupon_code'];

                if ($coupon_result['coupon_minimum_order'] <= $amount) {
                    $c_deduct = $coupon_result['coupon_amount'];
                    if ($coupon_result['coupon_type'] == 'S') {
                        $c_deduct = $_SESSION['shipping']['cost'];
                    }


                    if ($coupon_result['restrict_to_products'] || $coupon_result['restrict_to_categories']) {
                        $products = $_SESSION['cart']->get_products();
                        $n = count($products);
                        for ($i=0, $n; $i<$n; $i++) {
                            if ($coupon_result['restrict_to_products']) {
                                $pr_ids = preg_split("/[,]/", $coupon_result['restrict_to_products']);
                                for ($ii = 0; $ii < count($pr_ids); $ii++) {
                                    if ($pr_ids[$ii] == oos_get_product_id($products[$i]['id'])) {
                                        if ($coupon_result['coupon_type'] == 'P') {
                                            $pr_c = $products[$i]['final_price']*$products[$i]['quantity'];
                                            $od_amount = round($pr_c*10)/10*$c_deduct/100;
                                        } else {
                                            $od_amount = $c_deduct;
                                        }
                                    }
                                }
                            } else {
                                $cat_ids = preg_split("/[,]/", $coupon_result['restrict_to_categories']);
                                $products = $_SESSION['cart']->get_products();
                                $n = count($products);
                                for ($i=0, $n; $i<$n; $i++) {
                                    $my_path = oos_get_product_path(oos_get_product_id($products[$i]['id']));
                                    $sub_cat_ids = preg_split("/[_]/", $my_path);
                                    for ($iii = 0; $iii < count($sub_cat_ids); $iii++) {
                                        for ($ii = 0; $ii < count($cat_ids); $ii++) {
                                            if ($sub_cat_ids[$iii] == $cat_ids[$ii]) {
                                                if ($coupon_result['coupon_type'] == 'P') {
                                                    $pr_c = $products[$i]['final_price']*$products[$i]['qty'];
                                                    $od_amount = round($pr_c*10)/10*$c_deduct/100;
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
                        if ($coupon_result['coupon_type'] !='P') {
                            $od_amount = $c_deduct;
                        } else {
                            $od_amount = $amount * $coupon_result['coupon_amount'] / 100;
                        }
                    }
                }
                if ($od_amount>$amount) {
                    $od_amount = $amount;
                }
            }
        }
        return $od_amount;
    }


    public function calculate_tax_deduction_order($amount, $od_amount)
    {
        global $oOrder;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        if (isset($_SESSION['cc_id'])) {
            $cc_id = intval($_SESSION['cc_id']);

            $couponstable = $oostable['coupons'];
            $sql = "SELECT coupon_code, coupon_id, coupon_amount, coupon_type, coupon_minimum_order,
							uses_per_coupon, uses_per_user, restrict_to_products, restrict_to_categories 
					FROM $couponstable
					WHERE coupon_id = '" . intval($cc_id). "'
					AND coupon_active = 'Y'";
            $coupon_query = $dbconn->Execute($sql);

            if ($coupon_query->RecordCount() !=0) {
                $coupon_result = $coupon_query->fields;

                $this->coupon_code = $coupon_result['coupon_code'];

                if ($coupon_result['coupon_minimum_order'] <= $amount) {
                    // Sales tax recalculation
                    $subtotal = 0;
                    reset($oOrder->info['net_total']);
                    foreach ($oOrder->info['net_total'] as $key => $value) {
                        if ($value > 0) {
                            $subtotal += $value;
                        }
                    }

                    reset($oOrder->info['net_total']);
                    foreach ($oOrder->info['net_total'] as $key => $value) {
                        if ($value > 0) {
                            $share =  $value * 100 / $subtotal;

                            $cost = $od_amount * $share / 100;

                            $tax = $cost - oos_round((($cost * 100) / (100 + $key)), 2);

                            $oOrder->info['tax'] -= $tax;
                            $oOrder->info['tax_groups']["$key"] -= $tax;
                        }
                    }
                }
            }
        }
    }

    public function calculate_tax_deduction($amount, $od_amount)
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        if (isset($_SESSION['cc_id'])) {
            $cc_id = intval($_SESSION['cc_id']);

            $couponstable = $oostable['coupons'];
            $sql = "SELECT coupon_code, coupon_id, coupon_amount, coupon_type, coupon_minimum_order,
							uses_per_coupon, uses_per_user, restrict_to_products, restrict_to_categories 
					FROM $couponstable
					WHERE coupon_id = '" . intval($cc_id). "'
					AND coupon_active = 'Y'";
            $coupon_query = $dbconn->Execute($sql);

            if ($coupon_query->RecordCount() !=0) {
                $coupon_result = $coupon_query->fields;

                $this->coupon_code = $coupon_result['coupon_code'];

                if ($coupon_result['coupon_minimum_order'] <= $amount) {
                    // Sales tax recalculation
                    $subtotal = 0;
                    reset($_SESSION['cart']->info['net_total']);
                    foreach ($_SESSION['cart']->info['net_total'] as $key => $value) {
                        if ($value > 0) {
                            $subtotal += $value;
                        }
                    }

                    reset($_SESSION['cart']->info['net_total']);
                    foreach ($_SESSION['cart']->info['net_total'] as $key => $value) {
                        if ($value > 0) {
                            $share =  $value * 100 / $subtotal;

                            $cost = $od_amount * $share / 100;

                            $tax = $cost - oos_round((($cost * 100) / (100 + $key)), 2);

                            $_SESSION['cart']->info['tax'] -= $tax;
                            $_SESSION['cart']->info['tax_groups']["$key"] -= $tax;
                        }
                    }
                }
            }
        }




        /*

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
                  $valid_product = false;

                  if ($get_result['restrict_to_products']) {
                    $pr_ids = preg_split("/[,]/", $get_result['restrict_to_products']);
                    for ($p = 0; $p < count($pr_ids); $p++) {
                      if ($pr_ids[$p] == $t_prid) $valid_product = true;
                    }
                  }
                  if ($get_result['restrict_to_categories']) {
                    $cat_ids = preg_split("/[,]/", $get_result['restrict_to_categories']);
                    for ($c = 0; $c < count($cat_ids); $c++) {

                      $products_to_categoriestable = $oostable['products_to_categories'];
                      $cat_query = $dbconn->Execute("SELECT products_id FROM $products_to_categoriestable WHERE products_id = '" . (int)$products_id . "' AND categories_id = '" . (int)$cat_ids[$i] . "'");
                      if ($cat_query->RecordCount() !=0 ) $valid_product = true;
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
                    foreach($oOrder->info['tax_groups'] as $key => $value) {
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
                    foreach($oOrder->info['tax_groups'] as $key => $value) {
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
        */
    }



    public function update_credit_account($i)
    {
        return false;
    }


    public function apply_credit()
    {
        global $insert_id;

        if (isset($_SESSION['cc_id'])) {
            $cc_id = intval($_SESSION['cc_id']);
            $remote_addr = oos_server_get_remote();

            // Get database information
            $dbconn =& oosDBGetConn();
            $oostable =& oosDBGetTables();

            $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
            $dbconn->Execute("INSERT INTO $coupon_redeem_tracktable (coupon_id, redeem_date, redeem_ip, customer_id, order_id) VALUES ('" . oos_db_input($cc_id) . "', now(), '" . oos_db_input($remote_addr) . "', '" . intval($_SESSION['customer_id']) . "', '" . intval($insert_id) . "')");
        }
        unset($_SESSION['cc_id']);
    }



    public function get_product_price($product_id)
    {
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
            // $total_price += ($products_price + oos_calculate_tax($products_price, $products_tax)) * $qty;
            $total_price += $products_price * $qty;

            // attributes price
            if (isset($_SESSION['cart']->contents[$product_id]['attributes'])) {
                reset($_SESSION['cart']->contents[$product_id]['attributes']);
                foreach ($_SESSION['cart']->contents[$product_id]['attributes'] as $option => $value) {
                    $products_attributestable = $oostable['products_attributes'];
                    $attribute_price_query = $dbconn->Execute("SELECT options_values_price, price_prefix FROM $products_attributestable WHERE products_id = '" . (int)$prid . "' AND options_id = '" . oos_db_input($option) . "' AND options_values_id = '" . oos_db_input($value) . "'");
                    $attribute_price = $attribute_price_query->fields;
                    if ($attribute_price['price_prefix'] == '+') {
                        // $total_price += $qty * ($attribute_price['options_values_price'] + oos_calculate_tax($attribute_price['options_values_price'], $products_tax));
                        $total_price += $qty * ($attribute_price['options_values_price']);
                    } else {

                        // $total_price -= $qty * ($attribute_price['options_values_price'] + oos_calculate_tax($attribute_price['options_values_price'], $products_tax));
                        $total_price -= $qty * ($attribute_price['options_values_price']);
                    }
                }
            }
        }
        if ($this->include_shipping == 'true') {
            $total_price += $oOrder->info['shipping_cost'];
        }
        return $total_price;
    }

    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_ORDER_TOTAL_COUPON_STATUS');
        }

        return $this->_check;
    }

    public function keys()
    {
        return array('MODULE_ORDER_TOTAL_COUPON_STATUS', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', 'MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING');
    }

    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '5', '6', '2', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) VALUES ('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING', 'true', '6', '5', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    public function remove()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
}
