<?php
/**
   ----------------------------------------------------------------------
   $Id: class_currencies.php,v 1.1 2007/06/08 14:58:10 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: currencies.php,v 1.2 2002/09/01 13:47:06 project3000
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
    public $currencies = [];

    // class constructor
    public function __construct()
    {
        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $query = "SELECT code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value 
                FROM " . $oostable['currencies'];
        $result = $dbconn->Execute($query);

        while ($currencies = $result->fields) {
            $this->currencies[$currencies['code']] = ['title' => $currencies['title'], 'symbol_left' => $currencies['symbol_left'], 'symbol_right' => $currencies['symbol_right'], 'decimal_point' => $currencies['decimal_point'], 'thousands_point' => $currencies['thousands_point'], 'decimal_places' => (int)$currencies['decimal_places'], 'value' => $currencies['value']];
            // Move that ADOdb pointer!
            $result->MoveNext();
        }
    }

    // class methods
    public function format($number, $calculate_currency_value = true, $currency_type = DEFAULT_CURRENCY, $currency_value = null)
    {
        $number = oos_tofloat($number);

        $rate = 1;
        if ($calculate_currency_value === true) {
            $rate = (!empty($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
        }

        $format_string = $this->currencies[$currency_type]['symbol_left'] . ' ' . number_format(oos_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . ' ' . $this->currencies[$currency_type]['symbol_right'];

        return $format_string;
    }

    public function get_value($code)
    {
        return $this->currencies[$code]['value'];
    }

    public function display_price($products_price, $products_tax, $quantity = 1)
    {
        return $this->format(oos_add_tax($products_price, $products_tax) * $quantity);
    }
}
