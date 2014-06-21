<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  /includes/languages/german/modules/payment/sepabanktransfer_validation.php
  
  Sepabanktransfer(Lastschrft)

  Erstellt    19.10.2010    Version 0.9
  
*/
  // Allgemeine Texte.
  define('MODULE_PAYMENT_SEPABT_TEXT_TITLE', 'Lastschriftverfahren (SEPA) ');
  define('MODULE_PAYMENT_SEPABT_TEXT_DESCRIPTION', 'Lastschriftverfahren (SEPA)');
  define('MODULE_PAYMENT_SEPABT_TEXT_NOTE','Hinweis: ');
  define('MODULE_PAYMENT_SEPABT_TEXT_INFO', '');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK',  'Lastschrift');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_INFO', 'Hiermit erm&auml;chtige(n) ich/wir Sie widerruflich, die von mir/uns zu entrichtenden Zahlungen zu Lasten meines/unseres Kontos mit nachstehenden Daten durch Lastschrift einzuziehen. Wenn mein/unser Konto die erforderliche Deckung nicht aufweist, besteht seitens des kontof&uuml;hrenden Instituts keine Verpflichtung zur Einl&ouml;sung. Teileinl&ouml;sungen werden im Lastschriftverfahren nicht vorgenommen.');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER', 'Kontoinhaber:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN', 'IBAN:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_SWIFT', 'SWIFT-Code(BIC):');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_COUNTRY', 'Land der Bank:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME', 'Bankname:');

  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_ERROR','Fehler:');

  // Hinweistetxe aus Javascript.
  define('JS_SEPABT_SWIFT', 'Bitte gib die SWIFT-Nummer deiner Bank ein.\n');
  define('JS_SEPABT_SWIFT_ID', 'Bitte wähle das Land aus, in dem sich die Bank befindet.\n');
  define('JS_SEPABT_IBAN',  'Bitte gib die IBAN-Nummer deiner Bank ein.\n');
  define('JS_SEPABT_OWNER', 'Bitte gib den Namen des Kontoinhabers ein.\n');

?>
