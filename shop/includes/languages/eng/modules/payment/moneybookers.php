<?php
/* ----------------------------------------------------------------------
   $Id: moneybookers.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: moneybookers.php,v 1.01 2003/01/20 12:00:00 gbunte 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_MONEYBOOKERS_STATUS_TITLE', 'Enable moneybookers Module');
define('MODULE_PAYMENT_MONEYBOOKERS_STATUS_DESC', 'Do you want to accept moneybookers payments?');

define('MODULE_PAYMENT_MONEYBOOKERS_ID_TITLE', 'E-Mail Address');
define('MODULE_PAYMENT_MONEYBOOKERS_ID_DESC', 'The eMail address to use for the moneybookers service');

define('MODULE_PAYMENT_MONEYBOOKERS_REFID_TITLE', 'Referral ID');
define('MODULE_PAYMENT_MONEYBOOKERS_REFID_DESC', 'Your personal Referral ID from moneybookers.com');

define('MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_MONEYBOOKERS_CURRENCY_TITLE', 'Transaction Currency');
define('MODULE_PAYMENT_MONEYBOOKERS_CURRENCY_DESC', 'The default currency for the payment transactions');

define('MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE_TITLE', 'Transaction Language');
define('MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE_DESC', 'The default language for the payment transactions');


$aLang['module_payment_moneybookers_text_title'] = 'Moneybookers.com';
$aLang['module_payment_moneybookers_text_description'] = 'Moneybookers.com';
?>
