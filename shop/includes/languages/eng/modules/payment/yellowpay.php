<?php
/* ----------------------------------------------------------------------
   $Id: yellowpay.php,v 1.1 2007/09/23 21:49:30 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to 
      lang@oos-shop.de 
   the translated file. 
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_YELLOWPAY_STATUS_TITLE', 'Activate PostFinance module');
define('MODULE_PAYMENT_YELLOWPAY_STATUS_DESC', 'Do you want to accept PostFinance payments?');

define('MODULE_PAYMENT_YELLOWPAY_ID_TITLE', 'ID Shop (You get it from Postfinance)');
define('MODULE_PAYMENT_YELLOWPAY_ID_DESC', 'ID Shop (You get it from Postfinance)');

define('MODULE_PAYMENT_HASH_SEED_TITLE', 'Hash seed(You get it from Postfinance)');
define('MODULE_PAYMENT_HASH_SEED_DESC', 'Hash seed - You get it from Postfinance');

define('MODULE_PAYMENT_YELLOWPAY_CURRENCY_TITLE', 'Transaction Currency');
define('MODULE_PAYMENT_YELLOWPAY_CURRENCY_DESC', 'The currency to use for credit card transactions');

define('MODULE_PAYMENT_YELLOWPAY_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_YELLOWPAY_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_YELLOWPAY_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_YELLOWPAY_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_YELLOWPAY_LANGUAGE_TITLE', 'What default language should we use for the yellowpay mask?');
define('MODULE_PAYMENT_YELLOWPAY_LANGUAGE_DESC', 'Please choose your default language for the Postfinancemask, French (4108), English (2057), Italian (2064), German (2055)');

define('MODULE_PAYMENT_YELLOWPAY_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_YELLOWPAY_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

$aLang['module_payment_yellowpay_text_title'] = 'PostFinance';
$aLang['module_payment_yellowpay_text_description'] = 'PostFinance';
?>