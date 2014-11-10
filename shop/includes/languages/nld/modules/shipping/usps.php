<?php
/* ----------------------------------------------------------------------
   $Id: usps.php,v 1.3 2007/08/04 04:52:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: usps.php,v 1.8 2003/02/14 12:54:37 dgw_ 
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

define('MODULE_SHIPPING_USPS_HANDLING_TITLE', 'Handling Fee');
define('MODULE_SHIPPING_USPS_HANDLING_DESC', 'Handling fee for this shipping method.');

define('MODULE_SHIPPING_USPS_TAX_CLASS_TITLE', 'Tax Class');
define('MODULE_SHIPPING_USPS_TAX_CLASS_DESC', 'Use the following tax class on the shipping fee.');

define('MODULE_SHIPPING_USPS_ZONE_TITLE', 'Shipping Zone');
define('MODULE_SHIPPING_USPS_ZONE_DESC', 'If a zone is selected, only enable this shipping method for that zone.');

define('MODULE_SHIPPING_USPS_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_SHIPPING_USPS_SORT_ORDER_DESC', 'Sort order of display.');

$aLang['module_shipping_usps_text_title'] = 'United States Postal Service';
$aLang['module_shipping_usps_text_description'] = 'United States Postal Service<br /><br />You will need to have registered an account with USPS at http://www.uspsprioritymail.com/et_regcert.html to use this module<br /><br />USPS expects you to use pounds as weight measure for your products.';
$aLang['module_shipping_usps_text_opt_pp'] = 'Parcel Post';
$aLang['module_shipping_usps_text_opt_pm'] = 'Priority Mail';
$aLang['module_shipping_usps_text_opt_ex'] = 'Express Mail';
$aLang['module_shipping_usps_text_error'] = 'An error occured with the USPS shipping calculations.<br />If you prefer to use USPS as your shipping method, please contact the store owner.';
?>
