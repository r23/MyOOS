<?php
/* ----------------------------------------------------------------------
   $Id: psigate.php,v 1.3 2007/08/04 04:51:11 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2005 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: psigate.php,v 1.3 2002/11/18 14:45:25 project3000 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_PSIGATE_STATUS_TITLE', 'Enable PSiGate Module');
define('MODULE_PAYMENT_PSIGATE_STATUS_DESC', 'Do you want to accept PSiGate payments?');

define('MODULE_PAYMENT_PSIGATE_MERCHANT_ID_TITLE', 'Merchant ID');
define('MODULE_PAYMENT_PSIGATE_MERCHANT_ID_DESC', 'Merchant ID used for the PSiGate service');

define('MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE_TITLE', 'Transaction Mode');
define('MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE_DESC', 'Transaction mode to use for the PSiGate service');

define('MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE_TITLE', 'Transaction Type');
define('MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE_DESC', 'Transaction type to use for the PSiGate service');

define('MODULE_PAYMENT_PSIGATE_INPUT_MODE_TITLE', 'Credit Card Collection');
define('MODULE_PAYMENT_PSIGATE_INPUT_MODE_DESC', 'Should the credit card details be collected locally or remotely at PSiGate?');

define('MODULE_PAYMENT_PSIGATE_CURRENCY_TITLE', 'Transaction Currency');
define('MODULE_PAYMENT_PSIGATE_CURRENCY_DESC', 'The currency to use for credit card transactions');

define('MODULE_PAYMENT_PSIGATE_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_PSIGATE_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_PSIGATE_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_PSIGATE_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');


$aLang['module_payment_psigate_text_title'] = 'PSiGate';
$aLang['module_payment_psigate_text_description'] = 'Информация для теста:<br><br>CC#: 4111111111111111<br>Expiry: Any';
$aLang['module_payment_psigate_text_credit_card_owner'] = 'Владелец:';
$aLang['module_payment_psigate_text_credit_card_number'] = 'Номер:';
$aLang['module_payment_psigate_text_credit_card_expires'] = 'Дата истечения:';
$aLang['module_payment_psigate_text_type'] = 'Тип:';
$aLang['module_payment_psigate_text_js_cc_number'] = '* Номер Кредитной карты должен содержать по крайней мере ' . CC_NUMBER_MIN_LENGTH . ' цифр.\n';
$aLang['module_payment_psigate_text_error_message'] = 'Имеется ошибка, обрабоыки Вашей кредитной карточки, пожалуйста попытайтесь снова.';
$aLang['module_payment_psigate_text_error'] = 'ОШИБКА ввода данных Кредитной карты!';

?>