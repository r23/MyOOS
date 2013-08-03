<?php
/* ----------------------------------------------------------------------
   $Id: class_currencies.php 446 2013-06-27 15:00:29Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: currencies.php,v 1.14 2003/02/11 00:04:51 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class currencies {
    var $currencies;

    function currencies() {

      $this->currencies = array();

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $currenciestable = $oostable['currencies'];
      $sql = "SELECT code, title, symbol_left, symbol_right, decimal_point,
                     thousands_point, decimal_places, value
              FROM " . $currenciestable;
      if (USE_DB_CACHE == 'true') {
        $this->currencies = $dbconn->CacheGetAssoc(3600*24, $sql);
      } else {
        $this->currencies = $dbconn->GetAssoc($sql);
      }
    }

    function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {

      if (empty($currency_type) || ($this->exists($currency_type) == false)) {
        $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
      }

      if ($calculate_currency_value == true) {
        $rate = (oos_is_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format($number * $rate, $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . ' ' . $this->currencies[$currency_type]['symbol_right'];
      } else {
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format($number, $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . ' ' . $this->currencies[$currency_type]['symbol_right'];
      }

      return $format_string;
    }


    function exists($code) {
      if (isset($this->currencies[$code])) {
        return true;
      }

      return false;
    }

    function get_value($code) {
      return $this->currencies[$code]['value'];
    }

    function get_decimal_places($code) {
      return $this->currencies[$code]['decimal_places'];
    }

    function get_currencies_info($code) {
      return $this->currencies[$code];
    }

    function display_price($products_price, $products_tax, $quantity = 1) {
      global $oEvent, $aLang;

      $show_what_price = '';
      switch (true) {
        case ($oEvent->installed_plugin('down_for_maintenance')):
           $show_what_price = $aLang['down_for_maintenance_no_prices_display'];
           break;

        case ($_SESSION['member']->group['show_price'] != 1) :
            $show_what_price = $aLang['no_login_no_prices_display'];
            break;

        default:
            $show_what_price = $this->format(oos_add_tax($products_price, $products_tax) * $quantity);
            break;
      }
      return $show_what_price;
    }
  }
