<?php
/**
   ----------------------------------------------------------------------
   $Id: zones.php,v 1.4 2008/08/12 16:30:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: zones.php,v 1.3 2002/11/19 01:48:08 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('NUMBER_OF_ZONES', 8);

define('MODULE_SHIPPING_ZONES_STATUS_TITLE', 'Enable Zones Method');
define('MODULE_SHIPPING_ZONES_STATUS_DESC', 'Do you want to offer zone rate shipping?');

define('MODULE_SHIPPING_ZONES_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_SHIPPING_ZONES_SORT_ORDER_DESC', 'Sort order of display.');

for ($j = 0;$j < NUMBER_OF_ZONES;$j++) {
    define('MODULE_SHIPPING_ZONES_COUNTRIES_'.$j.'_TITLE', 'Zone '.$j.' Countries');
    define('MODULE_SHIPPING_ZONES_COUNTRIES_'.$j.'_DESC', 'Comma separated list of two character ISO country codes that are part of Zone '.$j.'.');

    define('MODULE_SHIPPING_ZONES_COST_'.$j.'_TITLE', 'Zone '.$j.' Shipping Table');
    define('MODULE_SHIPPING_ZONES_COST_'.$j.'_DESC', 'Shipping rates to Zone '.$j.' destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone '.$j.' destinations.');

    define('MODULE_SHIPPING_ZONES_HANDLING_'.$j.'_TITLE', 'Zone '.$j.' Handling Fee');
    define('MODULE_SHIPPING_ZONES_HANDLING_'.$j.'_DESC', 'Handling Fee for this shipping zone');
}

$aLang['module_shipping_zones_text_title'] = 'Zone Rates';
$aLang['module_shipping_zones_text_description'] = 'Zone Based Rates';
$aLang['module_shipping_zones_text_way'] = 'Shipping to';
$aLang['module_shipping_zones_text_units'] = 'lb(s)';
$aLang['module_shipping_zones_invalid_zone'] = 'No shipping available to the selected country';
$aLang['module_shipping_zones_undefined_rate'] = 'The shipping rate cannot be determined at this time';
