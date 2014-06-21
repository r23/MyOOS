<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  /includes/languages/english/modules/payment/sepabanktransfer_validation.php
  
  Sepabanktransfer(Lastschrft)

  Erstellt    19.10.2010    Version 0.9
*/  

  // Allgemeine Texte.
  define('MODULE_PAYMENT_SEPABT_TEXT_TITLE', 'Direct debit (SEPA)');
  define('MODULE_PAYMENT_SEPABT_TEXT_DESCRIPTION', 'Direct debit check (SEPA)');
  define('MODULE_PAYMENT_SEPABT_TEXT_NOTE','Note: ');
  define('MODULE_PAYMENT_SEPABT_TEXT_INFO', '');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK',  'Direct debit');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_INFO', 'herewith authorize you precarious.');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER', 'Account holder:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN', 'IBAN Code:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_SWIFT', 'SWIFT-Code(BIC):');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_COUNTRY', 'Country of Bank:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME', 'Bank name:');

  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_ERROR','Error:');

  // Hinweistetxe aus Javascript.
  define('JS_SEPABT_SWIFT', 'Please enter the SWIFT-Code from your bank.\n');
  define('JS_SEPABT_SWIFT_ID', 'Please choose the country, while the bank is located.\n');  
  define('JS_SEPABT_IBAN',  'Please enter the IBAN-Nummer from your bank.\n');
  define('JS_SEPABT_OWNER', 'Please enter the acount holder name.\n');

?>
