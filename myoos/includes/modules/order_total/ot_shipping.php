<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_shipping.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_shipping.php,v 1.15 2003/02/07 22:01:57 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class ot_shipping
{
    public $title;
    public $output = [];
    public $enabled = false;

    public function __construct()
    {
        global $aLang;

        $this->code = 'ot_shipping';
        $this->title = $aLang['module_order_total_shipping_title'];
        $this->info = $aLang['shopping_cart_shipping_info'];
        $this->description = $aLang['module_order_total_shipping_description'];
        $this->enabled = (defined('MODULE_ORDER_TOTAL_SHIPPING_STATUS') && (MODULE_ORDER_TOTAL_SHIPPING_STATUS == 'true') ? true : false);
        $this->sort_order = (defined('MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER') ? MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER : null);
    }

    public function process()
    {
        global $oOrder, $oCurrencies, $aUser, $aLang;

        $content_type = $_SESSION['cart']->get_content_type();

        // if the order contains only virtual products
        if (($content_type == 'virtual') || ($_SESSION['cart']->show_subtotal() == 0)) {
            $_SESSION['shipping'] = false;
            $_SESSION['sendto'] = false;
            $pass = true;
        }


        // if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
        if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER > 0)) {
            switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
            case 'national':
                if ($oOrder->delivery['country_id'] == STORE_COUNTRY) {
                    $pass = true;
                }
                break;
            case 'international':
                if ($oOrder->delivery['country_id'] != STORE_COUNTRY) {
                    $pass = true;
                }
                break;
            case 'both':
                $pass = true;
                break;
            default:
                $pass = false;
                break;
            }

            if (($pass == true) && (($oOrder->info['total'] - $oOrder->info['shipping_cost']) >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) {
                $oOrder->info['shipping_method'] = $this->title;
                $oOrder->info['shipping_cost'] = 0;
            }
        }

        $currency_type = $oOrder->info['currency'];
        $currency_value = $oOrder->info['currency_value'];

        if ($oOrder->info['shipping_cost'] > 0) {
            $oOrder->info['total'] += $oOrder->info['shipping_cost'];

            $subtotal = $oOrder->info['subtotal'];

            $tax = $oOrder->info['tax'];
            if ($aUser['price_with_tax'] == 1) {
                $subtotal = $subtotal - $tax;
            }

            $sales_tax_rates = count($oOrder->info['net_total']);

            reset($oOrder->info['net_total']);

            foreach ($oOrder->info['net_total'] as $key => $value) {
                if ($value > 0) {
                    $share =  $value * 100 / $subtotal;
                    $shipping_cost = $oOrder->info['shipping_cost'] * $share / 100;

                    $tax = $shipping_cost - oos_round((($shipping_cost * 100) / (100 + $key)), 2);

                    $oOrder->info['tax'] += $tax;
                    $oOrder->info['tax_groups']["$key"] += $tax;

                    if ($sales_tax_rates > 1) {
                        $info = sprintf($aLang['tax_incl_available_from'], number_format($key, 2).'%');
                        $info = '<br><small> (' . $info . '): </small>';
                    } else {
                        $info = '';
                    }

                    $this->output[] = array('title' => $oOrder->info['shipping_method'] . $info,
                                      'text' => $oCurrencies->format($shipping_cost, true, $currency_type, $currency_value),
                                      'info' => '',
                                      'value' => $shipping_cost);
                }
            }
        } else {
            $this->output[] = array('title' => $oOrder->info['shipping_method'] . ':',
                                  'text' => $oCurrencies->format($oOrder->info['shipping_cost'], true, $currency_type, $currency_value),
                                  'info' => '',
                                  'value' => $oOrder->info['shipping_cost']);
        }
    }

    public function shopping_cart_process()
    {
        global $oCurrencies, $aUser, $aLang;

        $content_type = $_SESSION['cart']->get_content_type();

        // if the order contains only virtual products
        if (($content_type == 'virtual') || ($_SESSION['cart']->show_subtotal() == 0)) {
            $_SESSION['shipping'] = false;
            $_SESSION['sendto'] = false;
            $pass = true;
        }


        $delivery_country_id = isset($_SESSION['delivery_country_id']) ? intval($_SESSION['delivery_country_id']) : STORE_COUNTRY;
        $delivery_zone_id = isset($_SESSION['zone_id']) ? intval($_SESSION['zone_id']) : STORE_ZONE;

        if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER > 0)) {
            switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
            case 'national':
                if ($delivery_country_id == STORE_COUNTRY) {
                    $pass = true;
                }
                break;

            case 'international':
                if ($delivery_country_id != STORE_COUNTRY) {
                    $pass = true;
                }
                break;

            case 'both':
                $pass = true;
                break;

            default:
                $pass = false;
                break;
            }

            if (($pass == true) && (($_SESSION['cart']->info['total'] - $_SESSION['cart']->info['shipping_cost']) >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) {
                $_SESSION['shipping']['title'] = $this->title;
                $_SESSION['shipping']['cost'] = 0;
            }
        }


        $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
        $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

        if ($_SESSION['shipping']['cost'] > 0) {
            $_SESSION['cart']->info['total'] += $_SESSION['shipping']['cost'];

            $subtotal = $_SESSION['cart']->info['subtotal'];

            $tax = $_SESSION['cart']->info['tax'];
            if ($aUser['price_with_tax'] == 1) {
                $subtotal = $subtotal - $tax;
            }

            $sales_tax_rates = count($_SESSION['cart']->info['net_total']);
            reset($_SESSION['cart']->info['net_total']);

            foreach ($_SESSION['cart']->info['net_total'] as $key => $value) {
                if ($value > 0) {
                    $share =  $value * 100 / $subtotal;
                    $shipping_cost = $_SESSION['shipping']['cost'] * $share / 100;
                    $tax = $shipping_cost - oos_round((($shipping_cost * 100) / (100 + $key)), 2);

                    $_SESSION['cart']->info['tax'] += $tax;
                    $_SESSION['cart']->info['tax_groups']["$key"] += $tax;

                    if ($sales_tax_rates > 1) {
                        $info = sprintf($aLang['tax_incl_available_from'], number_format($key, 2).'%');
                        $info = '<br><small> (' . $info . '): </small>';
                    } else {
                        $info = '';
                    }

                    $this->output[] = array('title' => $_SESSION['shipping']['title'] . $info,
                                      'text' => $oCurrencies->format($shipping_cost, true, $currency_type, $currency_value),
                                      'info' => '',
                                      'value' => $shipping_cost);
                }
            }

            $this->output[] = array('title' => '',
                                  'text' => '',
                                  'info' => $this->info,
                                  'value' => '');
        } else {
            $this->output[] = array('title' => $_SESSION['shipping']['title'] . ':',
                              'text' => $oCurrencies->format($_SESSION['shipping']['cost'], true, $currency, $currency_value),
                              'info' => '',
                              'value' => $_SESSION['shipping']['cost']);
        }
    }


    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_ORDER_TOTAL_SHIPPING_STATUS');
        }

        return $this->_check;
    }

    public function keys()
    {
        return array('MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION');
    }

    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '5', '6', '2', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, date_added) VALUES ('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '50', '6', '4', 'currencies->format', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national', '6', '5', 'oos_cfg_select_option(array(\'national\', \'international\', \'both\'), ', now())");
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
