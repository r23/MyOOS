<?php
/* ----------------------------------------------------------------------
   $Id: worldpay.php,v 1.3 2007/06/12 17:30:00 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: worldpay.php,v MS1a 2003/04/06 21:30
   ----------------------------------------------------------------------
   Author : Graeme Conkie (graeme@conkie.net)
   Title:   WorldPay Payment Callback Module V4.0 Version 1.4
   NOTE: YOU MUST CHANGE THE CALLBACK URL IN WP ADMIN TO <wpdisplay item="MC_callback">

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_WORLDPAY_STATUS_TITLE', 'Enable WorldPay Module');
define('MODULE_PAYMENT_WORLDPAY_STATUS_DESC', 'Do you want to accept WorldPay payments?');

define('MODULE_PAYMENT_WORLDPAY_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_WORLDPAY_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_WORLDPAY_ID_TITLE', 'Worldpay Installation ID');
define('MODULE_PAYMENT_WORLDPAY_ID_DESC', 'Your WorldPay Select Junior ID');

define('MODULE_PAYMENT_WORLDPAY_MODE_TITLE', 'Mode');
define('MODULE_PAYMENT_WORLDPAY_MODE_DESC', 'The mode you are working in (100 = Test Mode Accept, 101 = Test Mode Decline, 0 = Live');

define('MODULE_PAYMENT_WORLDPAY_USEMD5_TITLE', 'Use MD5');
define('MODULE_PAYMENT_WORLDPAY_USEMD5_DESC', 'Use MD5 encyption for transactions? (1 = Yes, 0 = No)');

define('MODULE_PAYMENT_WORLDPAY_MD5KEY_TITLE', 'MD5 secret key');
define('MODULE_PAYMENT_WORLDPAY_MD5KEY_DESC', 'MD5 secret key. Must also be entered into Worldpay installation config');

define('MODULE_PAYMENT_WORLDPAY_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_WORLDPAY_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_WORLDPAY_USEPREAUTH_TITLE', 'Use Pre-Authorisation?');
define('MODULE_PAYMENT_WORLDPAY_USEPREAUTH_DESC', 'Do you want to pre-authorise payments? Default=False. You need to request this from WorldPay before using it.');

define('MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

define('MODULE_PAYMENT_WORLDPAY_PREAUTH_TITLE', 'Pre-Auth');
define('MODULE_PAYMENT_WORLDPAY_PREAUTH_DESC', 'The mode you are working in (A = Pay Now, E = Pre Auth). Ignored if Use PreAuth is False.');

$aLang['module_payment_worldpay_text_title'] = 'WorldPay';
$aLang['module_payment_worldpay_text_description'] = 'Worldpay Payment Module';
$aLang['module_payment_worldpay_text_error'] = '<font color="#FF0000">ERROR: </font>';
$aLang['module_payment_worldpay_text_error_1'] = '... Your payment has been cancelled!';

?>
