<?php
/* ----------------------------------------------------------------------
   $Id: paypal.php,v 1.3 2007/06/14 16:15:58 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: paypal.php,v 1.7 2002/11/01 05:39:27 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_PAYPAL_STATUS_TITLE', 'Enable PayPal Module');
define('MODULE_PAYMENT_PAYPAL_STATUS_DESC', 'Do you want to accept PayPal payments?');

define('MODULE_PAYMENT_PAYPAL_ID_TITLE', 'E-Mail Address');
define('MODULE_PAYMENT_PAYPAL_ID_DESC', 'The e-mail address to use for the PayPal service');

define('MODULE_PAYMENT_PAYPAL_CURRENCY_TITLE', 'Transaction Currency');
define('MODULE_PAYMENT_PAYPAL_CURRENCY_DESC', 'The currency to use for credit card transactions');

define('MODULE_PAYMENT_PAYPAL_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_PAYPAL_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_PAYPAL_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_PAYPAL_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

$aLang['module_payment_paypal_text_title'] = 'PayPal';
$aLang['module_payment_paypal_text_description'] = 'PayPal';

