<?php
/**
   ----------------------------------------------------------------------
   $Id: weight.php,v 1.3 2007/06/13 16:42:27 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: weight.php,v 1.02 2003/02/18 03:33:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_SHIPPING_WEIGHT_STATUS_TITLE', 'Enable weight-dependent shipping costs?');
define('MODULE_SHIPPING_WEIGHT_STATUS_DESC', 'Would you like to offer weight-based shipping costs?');

define('MODULE_SHIPPING_WEIGHT_HANDLING_TITLE', 'Handling Fee');
define('MODULE_SHIPPING_WEIGHT_HANDLING_DESC', 'Handling Fee for this shipping zone');

define('MODULE_SHIPPING_WEIGHT_ZONE_TITLE', 'Shipping Zone');
define('MODULE_SHIPPING_WEIGHT_ZONE_DESC', 'If you select a zone, this shipping method will be offered only in this zone.');

define('MODULE_SHIPPING_WEIGHT_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_SHIPPING_WEIGHT_SORT_ORDER_DESC', 'Sort order of display.');

define('MODULE_SHIPPING_WEIGHT_COST_TITLE', 'Shipping cost table');
define('MODULE_SHIPPING_WEIGHT_COST_DESC', 'Shipping costs staggered by any weight. e.g.: 31:15,40:28,50:30.5,100:33 up to 31kg->15 EUR, from 31-40kg->28 EUR, from 40-50kg->30.5 EUR and from 50-100kg->33 EUR. From then on the \"increase step\" is used!');

define('MODULE_SHIPPING_WEIGHT_STEP_TITLE', 'Increase step');
define('MODULE_SHIPPING_WEIGHT_STEP_DESC', 'Increase step per exceeding kg in EUR');

define('MODULE_SHIPPING_WEIGHT_MODE_TITLE', 'Table Method');
define('MODULE_SHIPPING_WEIGHT_MODE_DESC', 'Is the shipping table based on total Weight or Total amount of order.');

$aLang['module_shipping_weight_text_title'] = 'Weight-dependent Shipping Cost';
$aLang['module_shipping_weight_text_description'] = 'Weight-dependent Shipping Cost';
$aLang['module_shipping_weight_text_way'] = 'Shipping Cost';
$aLang['module_shipping_weight_text_weight'] = 'Weight';
$aLang['module_shipping_weight_text_amount'] = 'Amount';
