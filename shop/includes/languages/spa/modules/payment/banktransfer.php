<?php
/* ----------------------------------------------------------------------
   $Id: banktransfer.php,v 1.2 2007/10/24 23:38:34 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banktransfer.php 126 2004-04-16 23:41:52Z dogu
   ----------------------------------------------------------------------

   OSC German Banktransfer
   (http://www.oscommerce.com/community/contributions,826)

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_BANKTRANSFER_STATUS_TITLE', 'Allow Banktranfer Payments');
define('MODULE_PAYMENT_BANKTRANSFER_STATUS_DESC', 'Do you want to accept banktransfer payments?');

define('MODULE_PAYMENT_BANKTRANSFER_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_BANKTRANSFER_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER_TITLE', 'Allow Banktranfer to Max Order');
define('MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER_DESC', 'Do you want to accept banktransfer to Credit Limit?');

define('MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

define('MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION_TITLE', 'Allow Fax Confirmation');
define('MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION_DESC', 'Do you want to allow fax confirmation?');

define('MODULE_PAYMENT_BANKTRANSFER_URL_NOTE_TITLE', 'Fax- File');
define('MODULE_PAYMENT_BANKTRANSFER_URL_NOTE_DESC', 'The fax-confirmation file. It must located in catalog-dir');


$aLang['module_payment_banktransfer_text_title'] = 'Direct debiting';
$aLang['module_payment_banktransfer_text_description'] = 'Direct debiting';
$aLang['module_payment_banktransfer_text_bank'] = 'Direct debiting';
$aLang['module_payment_banktransfer_text_email_footer'] = 'Note: You can download our fax form at ' . OOS_HTTP_SERVER . OOS_SHOP . MODULE_PAYMENT_BANKTRANSFER_URL_NOTE . ' and return it to us.';
$aLang['module_payment_banktransfer_text_bank_info'] = 'Please note that direct debiting requires a <b>German bank account.</b>';
$aLang['module_payment_banktransfer_text_bank_owner'] = 'Account holder:';
$aLang['module_payment_banktransfer_text_bank_number'] = 'Account number:';
$aLang['module_payment_banktransfer_text_bank_blz'] = 'Bank code number:';
$aLang['module_payment_banktransfer_text_bank_name'] = 'Bank:';
$aLang['module_payment_banktransfer_text_bank_fax'] = 'Direct debit authorization will be confirmed by fax';

$aLang['module_payment_banktransfer_text_bank_error'] = '<font color="#FF0000">ERROR: </font>';
$aLang['module_payment_banktransfer_text_bank_error_1'] = 'Account number and bank code number do not fit! Please check again.';
$aLang['module_payment_banktransfer_text_bank_error_2'] = 'No plausibility check method available for this bank code number!';
$aLang['module_payment_banktransfer_text_bank_error_3'] = 'Account number cannot be verified!';
$aLang['module_payment_banktransfer_text_bank_error_4'] = 'Account number cannot be verified! Please check again.';
$aLang['module_payment_banktransfer_text_bank_error_5'] = 'Bank code number not found! Please check again.';
$aLang['module_payment_banktransfer_text_bank_error_8'] = 'Incorrect bank code number or no bank code number entered!';
$aLang['module_payment_banktransfer_text_bank_error_9'] = 'No account number indicated!';

$aLang['module_payment_banktransfer_text_note'] = 'Note:';
$aLang['module_payment_banktransfer_text_note2'] = 'If you have security concerns to provide the bank details via internet, you may download our ';
$aLang['module_payment_banktransfer_text_note3'] = 'fax form';
$aLang['module_payment_banktransfer_text_note4'] = ' and return it completed to us.';

$aLang['js_bank_blz'] = 'Please enter the code number of your bank!\n';
$aLang['js_bank_name'] = 'Please enter the name of your bank!\n';
$aLang['js_bank_number'] = 'Please enter your account number!\n';
$aLang['js_bank_owner'] = 'Please enter the account holders name!\n';

?>
