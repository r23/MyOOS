<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: currencies.php,v 1.14 2003/02/11 00:04:51 hpdl
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

class currencies
{
    public $currencies;

    public function __construct()
    {
        $this->currencies = [];

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $currenciestable = $oostable['currencies'];
        $sql = "SELECT code, title, symbol_left, symbol_right, decimal_point,
                     thousands_point, decimal_places, value
              FROM " . $currenciestable;
        $result = $dbconn->Execute($sql);

        while ($currency = $result->fields) {
            $this->currencies[$currency['code']] = array('title' => $currency['title'],
                                                        'symbol_left' => $currency['symbol_left'],
                                                        'symbol_right' => $currency['symbol_right'],
                                                        'decimal_point' => $currency['decimal_point'],
                                                        'thousands_point' => $currency['thousands_point'],
                                                        'decimal_places' => $currency['decimal_places'],
                                                        'value' => $currency['value']);
            // Move that ADOdb pointer!
            $result->MoveNext();
        }
    }

    public function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = null, $with_symbol = true)
    {
        if (empty($currency_type) || ($this->exists($currency_type) == false)) {
            $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
        }

        $rate = 1;
        if ($calculate_currency_value == true) {
            $rate = (isset($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
        }

        if ($with_symbol == true) {
            $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(oos_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . ' ' . $this->currencies[$currency_type]['symbol_right'];
        } else {
            $format_string = number_format(oos_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], '.', '');
        }
        return $format_string;
    }

    public function calculate_price($products_price, $products_tax, $quantity = 1)
    {
        $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
        return oos_round(oos_add_tax($products_price, $products_tax), $this->currencies[$currency_type]['decimal_places']) * $quantity;
    }

    public function exists($code)
    {
        if (isset($this->currencies[$code])) {
            return true;
        }
        return false;
    }

    public function get_currencies_title($code)
    {
        return $this->currencies[$code]['title'];
    }

    public function get_currencies_symbol_left($code)
    {
        return $this->currencies[$code]['symbol_left'];
    }

    public function get_currencies_symbol_right($code)
    {
        return $this->currencies[$code]['symbol_right'];
    }

    public function get_value($code)
    {
        return $this->currencies[$code]['value'];
    }

    public function get_decimal_places($code)
    {
        return $this->currencies[$code]['decimal_places'];
    }

    public function get_currencies_info($code)
    {
        return $this->currencies[$code];
    }


    public function display_price($products_price, $products_tax, $quantity = 1)
    {
        global $oEvent, $aUser, $aLang;

        if ($oEvent->installed_plugin('down_for_maintenance')) {
            return $aLang['down_for_maintenance_no_prices_display'];
        }

        if (LOGIN_FOR_PRICE == 'true' && ($aUser['show_price'] != 1)) {
            return $aLang['no_login_no_prices_display'];
        }

        return $this->format($this->calculate_price($products_price, $products_tax, $quantity));
    }

    public function schema_price($products_price, $products_tax, $quantity = 1, $with_symbol = true)
    {
        global $oEvent, $aUser;

        if ($oEvent->installed_plugin('down_for_maintenance')) {
            return '';
        }

        if (LOGIN_FOR_PRICE == 'true' && ($aUser['show_price'] != 1)) {
            return '';
        }
        return $this->format($this->calculate_price($products_price, $products_tax, $quantity), true, '', null, $with_symbol);
    }
}
