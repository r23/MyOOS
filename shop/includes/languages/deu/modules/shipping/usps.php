<?php
/* ----------------------------------------------------------------------
   $Id: usps.php,v 1.3 2007/08/04 04:53:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: usps.php,v 1.10 2003/02/16 00:52:41 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_SHIPPING_USPS_STATUS_TITLE', 'Enable USPS Shipping');
define('MODULE_SHIPPING_USPS_STATUS_DESC', 'Do you want to offer USPS shipping?');

define('MODULE_SHIPPING_USPS_USERID_TITLE', 'Enter the USPS User ID');
define('MODULE_SHIPPING_USPS_USERID_DESC', 'Enter the USPS USERID assigned to you.');

define('MODULE_SHIPPING_USPS_PASSWORD_TITLE', 'Enter the USPS Password');
define('MODULE_SHIPPING_USPS_PASSWORD_DESC', 'See USERID, above.');

define('MODULE_SHIPPING_USPS_SERVER_TITLE', 'hich server to use');
define('MODULE_SHIPPING_USPS_SERVER_DESC', 'An account at USPS is needed to use the Production server');

define('MODULE_SHIPPING_USPS_HANDLING_TITLE', 'Handling Geb&uuml;hr');
define('MODULE_SHIPPING_USPS_HANDLING_DESC', 'Handling Geb&uuml;hr f&uuml;r diese Versandart.');

define('MODULE_SHIPPING_USPS_TAX_CLASS_TITLE', 'Steuerklasse');
define('MODULE_SHIPPING_USPS_TAX_CLASS_DESC', 'Folgende Steuerklasse an Versandkosten anwenden.');

define('MODULE_SHIPPING_USPS_ZONE_TITLE', 'Erlaubte Versandzonen');
define('MODULE_SHIPPING_USPS_ZONE_DESC', 'If a zone is selected, only enable this shipping method for that zone.');

define('MODULE_SHIPPING_USPS_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_SHIPPING_USPS_SORT_ORDER_DESC', 'Reihenfolge der Anzeige');

$aLang['module_shipping_usps_text_title'] = 'United States Postal Service';
$aLang['module_shipping_usps_text_description'] = 'United States Postal Service<br /><br />Sie ben&ouml;tigen einen Account bei USPS unter http://www.uspsprioritymail.com/et_regcert.html um dieses Modul nutzen zu k&ouml;nnen<br /><br />USPS erwartet, dass Sie <b>lbs</b> als Gewichtseinheit bei Ihren Produkten verwenden.';
$aLang['module_shipping_usps_text_opt_pp'] = 'Parcel Post';
$aLang['module_shipping_usps_text_opt_pm'] = 'Priority Mail';
$aLang['module_shipping_usps_text_opt_ex'] = 'Express Mail';
$aLang['module_shipping_usps_text_error'] = 'Es ist ein Fehler bei der Berechnung der USPS Versandkosten aufgetreten.<br />Wenn Sie USPS als Ihre gew&uuml;nschte Versandart verwenden wollen, nehmen Sie bitte Kontakt mit uns auf.';
?>
