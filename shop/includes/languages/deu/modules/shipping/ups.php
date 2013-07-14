<?php
/* ----------------------------------------------------------------------
   $Id: ups.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ups.php,v 1.5 2002/04/17 16:52:31 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_SHIPPING_UPS_STATUS_TITLE', 'Enable UPS Shipping');
define('MODULE_SHIPPING_UPS_STATUS_DESC', 'Do you want to offer UPS shipping?');

define('MODULE_SHIPPING_UPS_PICKUP_TITLE', 'UPS Pickup Method');
define('MODULE_SHIPPING_UPS_PICKUP_DESC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air');

define('MODULE_SHIPPING_UPS_PACKAGE_TITLE', 'UPS Packaging?');
define('MODULE_SHIPPING_UPS_PACKAGE_DESC', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box');

define('MODULE_SHIPPING_UPS_RES_TITLE', 'Residential Delivery?');
define('MODULE_SHIPPING_UPS_RES_DESC', 'Quote for Residential (RES) or Commercial Delivery (COM)');

define('MODULE_SHIPPING_UPS_HANDLING_TITLE', 'Handling Geb&uuml;hr');
define('MODULE_SHIPPING_UPS_HANDLING_DESC', 'Handling Geb&uuml;hr f&uuml;r diese Versandart.');

define('MODULE_SHIPPING_UPS_TAX_CLASS_TITLE', 'Steuerklasse');
define('MODULE_SHIPPING_UPS_TAX_CLASS_DESC', 'Folgende Steuerklasse an Versandkosten anwenden.');

define('MODULE_SHIPPING_UPS_ZONE_TITLE', 'Erlaubte Versandzonen');
define('MODULE_SHIPPING_UPS_ZONE_DESC', 'Geben Sie <b>einzeln</b> die Zonen an, in welche ein Versand m&ouml;glich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_UPS_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_SHIPPING_UPS_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

$aLang['module_shipping_ups_text_title'] = 'United Parcel Service';
$aLang['module_shipping_ups_text_description'] = 'United Parcel Service';
$aLang['module_shipping_ups_text_opt_gnd'] = 'UPS Ground';
$aLang['module_shipping_ups_text_opt_1dm'] = 'Next Day Air Early AM';
$aLang['module_shipping_ups_text_opt_1da'] = 'Next Day Air';
$aLang['module_shipping_ups_text_opt_1dp'] = 'Next Day Air Saver';
$aLang['module_shipping_ups_text_opt_2dm'] = '2nd Day Air Early AM';
$aLang['module_shipping_ups_text_opt_3ds'] = '3 Day Select';
$aLang['module_shipping_ups_text_opt_std'] = 'Canada Standard';
$aLang['module_shipping_ups_text_opt_xpr'] = 'Worldwide Express';
$aLang['module_shipping_ups_text_opt_xdm'] = 'Worldwide Express Plus';
$aLang['module_shipping_ups_text_opt_xpd'] = 'Worldwide Expedited';
?>
