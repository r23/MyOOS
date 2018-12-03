<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
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

	public function __construct() {

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

	public function format($number, $calculate_currency_value = TRUE, $currency_type = '', $currency_value = NULL, $with_symbol = TRUE) {

		if (empty($currency_type) || ($this->exists($currency_type) == FALSE)) {
			$currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
		}

		$rate = 1;
		if ($calculate_currency_value == TRUE) {
			$rate = (oos_is_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
		}
		if ($with_symbol == TRUE) {		
			$format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(oos_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . ' ' . $this->currencies[$currency_type]['symbol_right'];
		} else {
			$format_string = number_format(oos_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], '.', '');
		}
		return $format_string;
	}
	
	public function calculate_price($products_price, $products_tax, $quantity = 1) {
		$currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
		return oos_round(oos_add_tax($products_price, $products_tax), $this->currencies[$currency_type]['decimal_places']) * $quantity;
	}

	public function exists($code) {
		if (isset($this->currencies[$code])) {
			return TRUE;
		}
		return FALSE;
	}

	public function get_value($code) {
		return $this->currencies[$code]['value'];
	}

	public function get_decimal_places($code) {
		return $this->currencies[$code]['decimal_places'];
	}

	public function get_currencies_info($code) {
		return $this->currencies[$code];
	}

	public function display_price($products_price, $products_tax, $quantity = 1) {
		global $oEvent, $aUser, $aLang;

		if ($oEvent->installed_plugin('down_for_maintenance')) {
			return $aLang['down_for_maintenance_no_prices_display'];
		}
		
		if ( LOGIN_FOR_PRICE == 'true' && ($aUser['show_price'] != 1) ) {
			return $aLang['no_login_no_prices_display'];
		}
		return $this->format($this->calculate_price($products_price, $products_tax, $quantity));
    }
	
	public function schema_price($products_price, $products_tax, $quantity = 1, $with_symbol = TRUE) {
		global $oEvent, $aUser;

		if ($oEvent->installed_plugin('down_for_maintenance')) {
			return '';
		}
		
		if ( LOGIN_FOR_PRICE == 'true' && ($aUser['show_price'] != 1) ) {
			return '';
		}
		return $this->format($this->calculate_price($products_price, $products_tax, $quantity), TRUE, '', NULL, $with_symbol);
    }	
	
	
}

