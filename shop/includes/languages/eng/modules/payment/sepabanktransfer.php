<?php
/* ----------------------------------------------------------------------
   $Id: banktransfer.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   Sepabanktransfer(Lastschrft)

   Erstellt    19.10.2010    Version 0.9

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2007 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_SEPABT_STATUS_TITLE', 'Allow Direct debit (SEPA)');
define('MODULE_PAYMENT_SEPABT_STATUS_DESC', 'Do you want to accept Direct debit (SEPA) payments?');

define('MODULE_PAYMENT_SEPABT_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_SEPABT_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_SEPABT_MAX_ORDER_TITLE', 'Allow Banktranfer to Max Order');
define('MODULE_PAYMENT_SEPABT_MAX_ORDER_DESC', 'Do you want to accept banktransfer to Credit Limit?');

define('MODULE_PAYMENT_SEPABT_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_SEPABT_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');
 
define('MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

define('MODULE_PAYMENT_SEPABT_PAYEE_TITLE', 'Payee');	
define('MODULE_PAYMENT_SEPABT_PAYEE_DESC',  'Please enter a payee');

define('MODULE_PAYMENT_SEPABT_CREDITORID_TITLE', 'Creditor identification number');	
define('MODULE_PAYMENT_SEPABT_CREDITORID_DESC', 'Please enter your creditor identification number');


$aLang['module_payment_sepabt_text_title'] = 'Direct debiting (SEPA)';
$aLang['module_payment_sepabt_text_description'] = 'Direct debiting (SEPA)';



  define('MODULE_PAYMENT_SEPABT_TEXT_TITLE', 'Direct debit (SEPA)');
  define('MODULE_PAYMENT_SEPABT_TEXT_DESCRIPTION', 'Direct debit check (SEPA)');
  define('MODULE_PAYMENT_SEPABT_TEXT_NOTE','Note: ');
  define('MODULE_PAYMENT_SEPABT_TEXT_INFO', '');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK',  'Direct debit');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_INFO', 'herewith authorize you precarious.');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER', 'Account holder:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN', 'IBAN Code:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_BIC', 'SWIFT-Code(BIC):');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_COUNTRY', 'Country of Bank:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME', 'Bank name:');

  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_ERROR','Error:');


$aLang['js_sepabt_swift'] = 'Please enter the SWIFT-Code from your bank.\n';
$aLang['js_sepabt_swift_id'] = 'Please choose the country, while the bank is located.\n';
$aLang['js_sepabt_iban'] = 'Please enter the IBAN-Nummer from your bank.\n';
$aLang['js_sepabt_owner'] = 'Please enter the acount holder name.\n';

