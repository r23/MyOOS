<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_gv.php,v 1.2 2007/12/23 22:59:27 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_gv.php,v 1.4.2.12 2003/05/14 22:52:59 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class ot_gv
{
    public $title;
    public $output = [];
    public $enabled = false;

    public function __construct()
    {
        global $aLang;

        $this->code = 'ot_gv';
        $this->title = $aLang['module_order_total_gv_title'];
        $this->header = $aLang['module_order_total_gv_header'];
        $this->description = $aLang['module_order_total_gv_description'];
        $this->user_prompt = $aLang['module_order_total_gv_user_prompt'];
        $this->enabled = (defined('MODULE_ORDER_TOTAL_GV_STATUS') && (MODULE_ORDER_TOTAL_GV_STATUS == 'true') ? true : false);
        $this->sort_order = (defined('MODULE_ORDER_TOTAL_GV_SORT_ORDER') ? MODULE_ORDER_TOTAL_GV_SORT_ORDER : null);
        $this->include_tax = null; // todo remove
        $this->calculate_tax = null; // todo remove
        $this->credit_tax = null; // todo remove
        $this->tax_class = null; // todo remove
        $this->credit_class = true;
        $this->checkbox = $this->user_prompt . '<input type="checkbox" onClick="submitFunction()" name="' . 'c' . $this->code . '">';
    }

    public function process()
    {
        global $oOrder, $oCurrencies;

        if (isset($_SESSION['cot_gv']) && $_SESSION['cot_gv'] == true) {
            $currency = $_SESSION['currency'];
            $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

            $order_total = $oOrder->info['total'];

            $od_amount = $this->calculate_credit($order_total);
            if ($this->calculate_tax != "none") {
                $tod_amount = $this->calculate_tax_deduction($order_total, $od_amount, $this->calculate_tax);
                $od_amount = $this->calculate_credit($order_total);
            }
            $this->deduction = $od_amount;
            $oOrder->info['total'] = $oOrder->info['total'] - $od_amount;
            if ($od_amount > 0) {
                $this->output[] = ['title' => '<font color="#FF0000">' . $this->title . ':</font>', 'text' => '<strong><font color="#FF0000"> - ' . $oCurrencies->format($od_amount) . '</font></strong>', 'info' => '', 'value' => $sod_amount];
            }
        }
    }

    public function shopping_cart_process()
    {
        global $oCurrencies;

        if (isset($_SESSION['cot_gv']) && $_SESSION['cot_gv'] == true) {
            $currency = $_SESSION['currency'];
            $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

            $order_total = $_SESSION['cart']->info['total'];


            if ($this->calculate_tax != "none") {
                $tod_amount = $this->calculate_tax_deduction($order_total, $od_amount, $this->calculate_tax);
                $od_amount = $this->calculate_credit($order_total);
            }
            $this->deduction = $od_amount;
            $_SESSION['cart']->info['total'] = $_SESSION['cart']->info['total'] - $od_amount;
            if ($od_amount > 0) {
                $this->output[] = ['title' => '<font color="#FF0000">' . $this->title . ':</font>', 'text' => '<strong><font color="#FF0000"> - ' . $oCurrencies->format($od_amount) . '</font></strong>', 'info' => '', 'value' => $sod_amount];
            }
        }
    }

    public function selection_test()
    {
        if ($this->user_has_gv_account($_SESSION['customer_id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function pre_confirmation_check($order_total)
    {
        $gv_payment_amount = $this->calculate_credit($order_total);

        return $gv_payment_amount;
    }

    public function use_credit_amount()
    {
        $_SESSION['cot_gv'] = false;
        $output_string = '';
        if ($this->selection_test()) {
            $output_string .=  '    <td colspan="2" align="right" class="main">';
            $output_string .= '<strong>' . $this->checkbox . '</strong>' . '</td>' . "\n";
        }
        return $output_string;
    }

    public function update_credit_account($i)
    {
        global $oOrder, $insert_id;

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        if (preg_match('/^GIFT/', addslashes((string) $oOrder->products[$i]['model']))) {
            $gv_order_amount = ($oOrder->products[$i]['final_price'] * $oOrder->products[$i]['qty']);
            if ($this->credit_tax == 'true') {
                $gv_order_amount = $gv_order_amount * (100 + $oOrder->products[$i]['tax']) / 100;
            }
            $gv_order_amount = $gv_order_amount * 100 / 100;
            /*
                    if (MODULE_ORDER_TOTAL_GV_QUEUE == 'false') {
                      // GV_QUEUE is true so release amount to account immediately

                      $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                      $gv_query = $dbconn->Execute("SELECT amount FROM $coupon_gv_customertable WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'");
                      $customer_gv = false;
                      $total_gv_amount = 0;

                      if ($gv_result = $gv_query->fields) {
                        $total_gv_amount = $gv_result['amount'];
                        $customer_gv = true;
                      }
                      $total_gv_amount = $total_gv_amount + $gv_order_amount;
                      if ($customer_gv) {

                        $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                        $gv_update=$dbconn->Execute("UPDATE $coupon_gv_customertable
                                                     SET amount = '" . oos_db_input($total_gv_amount) . "'
                                                     WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'");
                      } else {

                        $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                        $gv_insert=$dbconn->Execute("INSERT INTO $coupon_gv_customertable
                                                    (customer_id,
                                                    amount) VALUES ('" . intval($_SESSION['customer_id']) . "',
                                                                    '" . oos_db_input($total_gv_amount) . "')");
                      }
                    } else {
            */
            // GV_QUEUE is true - so queue the gv for release by store owner
            $remote_addr = oos_server_get_remote();

            $coupon_gv_queuetable = $oostable['coupon_gv_queue'];
            $gv_insert = $dbconn->Execute(
                "INSERT INTO $coupon_gv_queuetable
                                 (customer_id,
                                  order_id,
                                  amount,
                                  date_created,
                                  ipaddr) VALUES ('" . intval($_SESSION['customer_id']) . "',
                                                  '" . intval($insert_id) . "',
                                                  '" . oos_db_input($gv_order_amount) . "',
                                                  now(),
                                                  '" . oos_db_input($remote_addr) . "')"
            );
        }
    }

    public function credit_selection()
    {
        global $oCurrencies, $aLang;

        $selection_string = '';

        $sTheme = oos_var_prep_for_os($_SESSION['theme']);
        $sLanguage = isset($_SESSION['language']) ? oos_var_prep_for_os($_SESSION['language']) : DEFAULT_LANGUAGE;

        $image_submit = '<input type="image" name="submit_redeem" onClick="submitFunction()" src="themes/' . $sTheme . '/images/buttons/' . $sLanguage . '/redeem.gif" border="0" alt="' . $aLang['image_button_redeem_voucher'] . '" title = "' . $aLang['image_button_redeem_voucher'] . '">';

        $selection_string = '';
        $selection_string .= '<tr>' . "\n";
        $selection_string .= '  <td width="10"></td>';
        $selection_string .= '  <td class="main">' . "\n";
        $selection_string .= $aLang['text_enter_gv_code'] . oos_draw_input_field('gv_redeem_code') . '</td>';
        $selection_string .= '  <td align="right">' . $image_submit . '</td>';
        $selection_string .= '  <td width="10"></td>';
        $selection_string .= '</tr>' . "\n";

        return $selection_string;
    }

    public function apply_credit()
    {
        global $oOrder;

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        if (isset($_SESSION['cot_gv']) && $_SESSION['cot_gv'] == true) {
            $coupon_gv_customertable = $oostable['coupon_gv_customer'];
            $gv_query = $dbconn->Execute("SELECT amount FROM $coupon_gv_customertable WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'");
            $gv_result = $gv_query->fields;
            $gv_payment_amount = $this->deduction;
            $gv_amount = $gv_result['amount'] - $gv_payment_amount;

            $coupon_gv_customertable = $oostable['coupon_gv_customer'];
            $gv_update = $dbconn->Execute(
                "UPDATE $coupon_gv_customertable
                                       SET amount = '" . $gv_amount . "'
                                       WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'"
            );
        }
        return $gv_payment_amount;
    }

    public function collect_posts()
    {
        global $oCurrencies, $oMessage, $aLang;

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $aContents = oos_get_content();

        if (isset($_POST['gv_redeem_code'])) {
            $gv_redeem_code = filter_string_polyfill(filter_input(INPUT_POST, 'gv_redeem_code'));
            $couponstable = $oostable['coupons'];
            $gv_query = $dbconn->Execute("SELECT coupon_id, coupon_type, coupon_amount FROM $couponstable WHERE coupon_code = '" . oos_db_input($gv_redeem_code) . "'");
            $gv_result = $gv_query->fields;
            if ($gv_query->RecordCount() != 0) {
                $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
                $redeem_query = $dbconn->Execute("SELECT * FROM $coupon_redeem_tracktable WHERE coupon_id = '" . $gv_result['coupon_id'] . "'");
                if (($redeem_query->RecordCount() != 0) && ($gv_result['coupon_type'] == 'G')) {

                    //e Discount Coupons
                    (defined('MODULE_ORDER_TOTAL_GV_STATUS') && (MODULE_ORDER_TOTAL_GV_STATUS == 'true') ? true : false);
                    $_SESSION['error_message'] = $aLang['error_no_invalid_redeem_gv'];
                    // todo remove?
                    $oMessage->add_session('checkout_payment', $aLang['error_no_invalid_redeem_gv'], 'error');
                    oos_redirect(oos_href_link($aContents['checkout_payment']));
                }
            }

            if ($gv_result['coupon_type'] == 'G') {
                $gv_amount = $gv_result['coupon_amount'];
                // Things to set
                // ip address of claimant
                // customer id of claimant
                // date
                // redemption flag
                // now update customer account with gv_amount

                $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                $gv_amount_query = $dbconn->Execute("SELECT amount FROM $coupon_gv_customertable WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'");
                $customer_gv = false;
                $total_gv_amount = $gv_amount;

                if ($gv_amount_result = $gv_amount_query->fields) {
                    $total_gv_amount = $gv_amount_result['amount'] + $gv_amount;
                    $customer_gv = true;
                }

                $couponstable = $oostable['coupons'];
                $gv_update = $dbconn->Execute(
                    "UPDATE $couponstable
                                         SET coupon_active = 'N' 
                                         WHERE coupon_id = '" . $gv_result['coupon_id'] . "'"
                );
                $remote_addr = oos_server_get_remote();

                $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
                $gv_redeem = $dbconn->Execute(
                    "INSERT INTO  $coupon_redeem_tracktable
                                        (coupon_id,
                                         customer_id,
                                         redeem_date,
                                         redeem_ip) VALUES ('" . $gv_result['coupon_id'] . "',
                                                            '" . intval($_SESSION['customer_id']) . "',
                                                            now(),
                                                            '" . oos_db_input($remote_addr) . "')"
                );
                if ($customer_gv) {
                    $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                    // already has gv_amount so update
                    $gv_update = $dbconn->Execute(
                        "UPDATE $coupon_gv_customertable
                                           SET amount = '" . $total_gv_amount . "'
                                           WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'"
                    );
                } else {
                    // no gv_amount so insert
                    $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                    $gv_insert = $dbconn->Execute(
                        "INSERT INTO $coupon_gv_customertable
                                           (customer_id,
                                            amount) VALUES ('" . intval($_SESSION['customer_id']) . "',
                                                            '" . $total_gv_amount . "')"
                    );
                }
                $oMessage->add_session('checkout_payment', $aLang['error_redeemed_amount'] . $oCurrencies->format($gv_amount), 'error');
                // oos_redirect(oos_href_link($aContents['checkout_payment']));
            }
        }


        if ($_POST['submit_redeem_x'] && $gv['coupon_type'] == 'G') {
            // $oMessage->add_session('checkout_payment', $aLang['error_no_redeem_code'], 'error');
        }

        if ($oMessage->size('checkout_payment') > 0) {
            // oos_redirect(oos_href_link($aContents['checkout_payment']));
        }
    }

    public function shopping_cart_collect_posts()
    {
        global $oCurrencies, $oMessage, $aLang;

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $aContents = oos_get_content();

        if (isset($_POST['gv_redeem_code'])) {
            $gv_redeem_code = filter_string_polyfill(filter_input(INPUT_POST, 'gv_redeem_code'));
            $couponstable = $oostable['coupons'];
            $gv_query = $dbconn->Execute("SELECT coupon_id, coupon_type, coupon_amount FROM $couponstable WHERE coupon_type = 'G' AND coupon_code = '" . oos_db_input($gv_redeem_code) . "'");
            $gv_result = $gv_query->fields;
            if ($gv_query->RecordCount() != 0) {
                $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
                $redeem_query = $dbconn->Execute("SELECT * FROM $coupon_redeem_tracktable WHERE coupon_id = '" . intval($gv_result['coupon_id']) . "'");
                if (($redeem_query->RecordCount() != 0) && ($gv_result['coupon_type'] == 'G')) {
                    $oMessage->add_session('checkout_payment', $aLang['error_no_invalid_redeem_gv'], 'error');
                }
            }

            if ($gv_result['coupon_type'] == 'G') {
                $gv_amount = $gv_result['coupon_amount'];
                // Things to set
                // ip address of claimant
                // customer id of claimant
                // date
                // redemption flag
                // now update customer account with gv_amount

                $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                $gv_amount_query = $dbconn->Execute("SELECT amount FROM $coupon_gv_customertable WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'");
                $customer_gv = false;
                $total_gv_amount = $gv_amount;

                if ($gv_amount_result = $gv_amount_query->fields) {
                    $total_gv_amount = $gv_amount_result['amount'] + $gv_amount;
                    $customer_gv = true;
                }

                $couponstable = $oostable['coupons'];
                $gv_update = $dbconn->Execute(
                    "UPDATE $couponstable
                                         SET coupon_active = 'N' 
                                         WHERE coupon_id = '" . $gv_result['coupon_id'] . "'"
                );
                $remote_addr = oos_server_get_remote();

                $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
                $gv_redeem = $dbconn->Execute(
                    "INSERT INTO  $coupon_redeem_tracktable
                                        (coupon_id,
                                         customer_id,
                                         redeem_date,
                                         redeem_ip) VALUES ('" . $gv_result['coupon_id'] . "',
                                                            '" . intval($_SESSION['customer_id']) . "',
                                                            now(),
                                                            '" . oos_db_input($remote_addr) . "')"
                );
                if ($customer_gv) {
                    $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                    // already has gv_amount so update
                    $gv_update = $dbconn->Execute(
                        "UPDATE $coupon_gv_customertable
                                           SET amount = '" . $total_gv_amount . "'
                                           WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'"
                    );
                } else {
                    // no gv_amount so insert
                    $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                    $gv_insert = $dbconn->Execute(
                        "INSERT INTO $coupon_gv_customertable
                                           (customer_id,
                                            amount) VALUES ('" . intval($_SESSION['customer_id']) . "',
                                                            '" . $total_gv_amount . "')"
                    );
                }
                $oMessage->add_session('shopping_cart', $aLang['error_redeemed_amount'] . $oCurrencies->format($gv_amount), 'error');
                // oos_redirect(oos_href_link($aContents['checkout_payment']));
            }
        }


        if ($_POST['submit_redeem_x'] && $gv['coupon_type'] == 'G') {
            // $oMessage->add_session('checkout_payment', $aLang['error_no_redeem_code'], 'error');
        }

        if ($oMessage->size('checkout_payment') > 0) {
            // oos_redirect(oos_href_link($aContents['checkout_payment']));
        }
    }



    public function calculate_credit($amount)
    {
        global $oOrder;

        $gv_payment_amount = 0;

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $coupon_gv_customertable = $oostable['coupon_gv_customer'];
        $query = "SELECT amount
                FROM $coupon_gv_customertable
                WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'";
        $gv_query = $dbconn->Execute($query);
        if ($gv_query->RecordCount()) {
            $gv_result = $gv_query->fields;
            $gv_payment_amount = $gv_result['amount'];
            $save_total_cost = $amount;
            $full_cost = $save_total_cost - $gv_payment_amount;
            if ($full_cost < 0) {
                $full_cost = 0;
                $gv_payment_amount = $save_total_cost;
            }
        }
        return round($gv_payment_amount, 2);
    }

    public function calculate_tax_deduction($amount, $od_amount, $method)
    {
        global $oOrder;

        switch ($method) {
            case 'Standard':
                $ratio1 = round($od_amount / $amount, 2);
                $tod_amount = 0;
                reset($oOrder->info['tax_groups']);
                foreach ($oOrder->info['tax_groups'] as $key => $value) {
                    $tax_rate = oos_get_tax_rate_from_desc($key);
                    $total_net += $tax_rate * $oOrder->info['tax_groups'][$key];
                }
                if ($od_amount > $total_net) {
                    $od_amount = $total_net;
                }
                reset($oOrder->info['tax_groups']);
                foreach ($oOrder->info['tax_groups'] as $key => $value) {
                    $tax_rate = oos_get_tax_rate_from_desc($key);
                    $net = $tax_rate * $oOrder->info['tax_groups'][$key];
                    if ($net > 0) {
                        $god_amount = $oOrder->info['tax_groups'][$key] * $ratio1;
                        $tod_amount += $god_amount;
                        $oOrder->info['tax_groups'][$key] = $oOrder->info['tax_groups'][$key] - $god_amount;
                    }
                }
                $oOrder->info['tax'] -= $tod_amount;
                $oOrder->info['total'] -= $tod_amount;
                break;

            case 'Credit Note':
                $tax_rate = oos_get_tax_rate($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
                $tax_desc = oos_get_tax_description($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
                $tod_amount = $this->deduction / (100 + $tax_rate) * $tax_rate;
                $oOrder->info['tax_groups'][$tax_desc] -= $tod_amount;
                //          $oOrder->info['total'] -= $tod_amount;
                break;
            default:
        }
        return $tod_amount;
    }

    public function user_has_gv_account($c_id)
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $coupon_gv_customertable = $oostable['coupon_gv_customer'];
        $query = "SELECT amount
                FROM $coupon_gv_customertable
                WHERE customer_id = '" . oos_db_input($c_id) . "'";
        $gv_result = $dbconn->Execute($query);

        if ($gv_result->fields['amount'] > 0) {
            return true;
        }
        return false;
    }

    public function get_order_total()
    {
        global $oOrder;

        $order_total = $oOrder->info['total'];
        if ($this->include_tax == 'false') {
            $order_total = $order_total - $oOrder->info['tax'];
        }
        if ($this->include_shipping == 'false') {
            $order_total = $order_total - $oOrder->info['shipping_cost'];
        }

        return $order_total;
    }

    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_ORDER_TOTAL_GV_STATUS');
        }

        return $this->_check;
    }

    public function keys()
    {
        return ['MODULE_ORDER_TOTAL_GV_STATUS', 'MODULE_ORDER_TOTAL_GV_SORT_ORDER'];
    }

    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_GV_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_GV_SORT_ORDER', '9', '6', '2', now())");
    }

    public function remove()
    {
        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
}
