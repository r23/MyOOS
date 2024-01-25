<?php
/**
   ----------------------------------------------------------------------
   $Id: eubanktransfer.php,v 1.3 2007/06/14 16:15:58 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: moneyorder.php,v 1.8 2003/02/16 01:12:22 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

if (!defined('MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME')) {
    define('MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME', '----');
    define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME', '----');
    define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM', '----');
    define('MODULE_PAYMENT_EU_BANKTRANSFER_IBAN', '----');
    define('MODULE_PAYMENT_EU_BANKTRANSFER_BIC', '----');
}


define('MODULE_PAYMENT_EU_BANKTRANSFER_STATUS_TITLE', 'Allow Bank Transfer Payment');
define('MODULE_PAYMENT_EU_BANKTRANSFER_STATUS_DESC', 'Do you want to accept bank transfers?');

define('MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME_TITLE', 'Bank Name');
define('MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME_DESC', 'Your full bank name');

define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME_TITLE', 'Account Name');
define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME_DESC', 'Name which is registered for the account.');

define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM_TITLE', 'Bank Account No.');
define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM_DESC', 'Your account number.');

define('MODULE_PAYMENT_EU_BANKTRANSFER_IBAN_TITLE', 'Bank Account IBAN');
define('MODULE_PAYMENT_EU_BANKTRANSFER_IBAN_DESC', 'International account id.<br />(ask your bank if you don\'t know it)');

define('MODULE_PAYMENT_EU_BANKTRANSFER_BIC_TITLE', 'Your account BIC / SWIFT code');
define('MODULE_PAYMENT_EU_BANKTRANSFER_BIC_DESC', 'International bank id.<br />(ask your bank if you don\'t know it)');

define('MODULE_PAYMENT_EU_BANKTRANSFER_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_EU_BANKTRANSFER_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_EU_BANKTRANSFER_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_EU_BANKTRANSFER_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');


define('MODULE_PAYMENT_EU_BANKTRANSFER_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_EU_BANKTRANSFER_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

$aLang['module_payment_eu_banktransfer_text_title'] = 'European Bank Transfer';

$aLang['module_payment_eu_banktransfer_text_description'] = '<br />Please use the following details to transfer your total order value:<br />' .
                                                            '<br />Bank Name: ' .  MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME .
                                                            '<br />Account Name: ' . MMODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME .
                                                            '<br />Account No.: ' .  MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM .
                                                            '<br />IBAN: ' . MODULE_PAYMENT_EU_BANKTRANSFER_IBAN .
                                                            '<br />BIC/SWIFT: ' . MODULE_PAYMENT_EU_BANKTRANSFER_BIC .
                                                            '<br /><br />Your order will not ship until we receive payment in the above account.<br />';


$aLang['module_payment_eu_banktransfer_email_footer'] = 'Please use the following details to transfer your total order value:' . "\n\n" .
                                                        'Bank Name: ' .  MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME . "\n" .
                                                        'Account Name: ' . MMODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME . "\n" .
                                                        'Account No.: ' .  MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM . "\n" .
                                                        'IBAN: ' . MODULE_PAYMENT_EU_BANKTRANSFER_IBAN . "\n" .
                                                        'BIC/SWIFT: ' . MODULE_PAYMENT_EU_BANKTRANSFER_BIC . "\n\n" .
                                                        'Your order will not ship until we receive payment in the above account.';
