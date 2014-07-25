<?php
/* ----------------------------------------------------------------------
   $Id: $

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

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
   
/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */
   
define('MODULE_PAYMENT_SEPABT_STATUS_TITLE', 'Lastschriftverfahren (SEPA) ');
define('MODULE_PAYMENT_SEPABT_STATUS_DESC', 'Möchten Sie Lastschriftverfahren (SEPA) Zahlungen erlauben?');

define('MODULE_PAYMENT_SEPABT_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_SEPABT_ZONE_DESC', 'Wenn eine Zone ausgewählt ist, gilt die Zahlungsmethode nur für diese Zone.');

define('MODULE_PAYMENT_SEPABT_MAX_ORDER_TITLE', 'Lastschriftverfahren bis zum Bestellwert erlauben');
define('MODULE_PAYMENT_SEPABT_MAX_ORDER_DESC', 'Bis zu welchem Bestellwert möchten Sie Zahlungen per Lastschriftverfahren erlauben? ');

define('MODULE_PAYMENT_SEPABT_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_PAYMENT_SEPABT_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


define('MODULE_PAYMENT_SEPABT_PAYEE_TITLE', 'Zahlungsempfänger');	
define('MODULE_PAYMENT_SEPABT_PAYEE_DESC', 'Bitte geben Sie einen Zahlungsempfänger ein');

define('MODULE_PAYMENT_SEPABT_CREDITORID_TITLE', 'Gläubiger-Identifikationsnummer');	
define('MODULE_PAYMENT_SEPABT_CREDITORID_DESC', 'Bitte geben Sie Ihre Gläubiger-Identifikationsnummer ein');

$aLang['module_payment_sepabt_text_title'] = 'Lastschriftverfahren (SEPA)';
$aLang['module_payment_sepabt_text_description'] = 'Lastschriftverfahren (SEPA)';

  define('MODULE_PAYMENT_SEPABT_TEXT_NOTE','Hinweis: ');
  define('MODULE_PAYMENT_SEPABT_TEXT_INFO', '');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK',  'Lastschrift');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_INFO', 'Hiermit erm&auml;chtige(n) ich/wir Sie widerruflich, die von mir/uns zu entrichtenden Zahlungen zu Lasten meines/unseres Kontos mit nachstehenden Daten durch Lastschrift einzuziehen. Wenn mein/unser Konto die erforderliche Deckung nicht aufweist, besteht seitens des kontof&uuml;hrenden Instituts keine Verpflichtung zur Einl&ouml;sung. Teileinl&ouml;sungen werden im Lastschriftverfahren nicht vorgenommen.');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER', 'Kontoinhaber:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN', 'IBAN:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_BIC', 'SWIFT-Code(BIC):');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_COUNTRY', 'Land der Bank:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME', 'Bankname:');

  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_ERROR','Fehler:');


$aLang['js_sepabt_swift'] = 'Bitte geben Sie die SWIFT-Nummer deiner Bank ein.\n';
$aLang['js_sepabt_swift_id'] = 'Bitte wählen Sie das Land aus, in dem sich die Bank befindet.\n';
$aLang['js_sepabt_iban'] = 'Bitte geben Sie die IBAN-Nummer deiner Bank ein.\n';
$aLang['js_sepabt_owner'] = 'Bitte geben Sie den Namen des Kontoinhabers ein.\n';


