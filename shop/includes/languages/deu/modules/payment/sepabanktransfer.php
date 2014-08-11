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
define('MODULE_PAYMENT_SEPABT_STATUS_DESC', 'M&ouml;chten Sie Lastschriftverfahren (SEPA) Zahlungen erlauben?');

define('MODULE_PAYMENT_SEPABT_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_SEPABT_ZONE_DESC', 'Wenn eine Zone ausgew&auml;ht ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_SEPABT_MAX_ORDER_TITLE', 'Lastschriftverfahren bis zum Bestellwert erlauben');
define('MODULE_PAYMENT_SEPABT_MAX_ORDER_DESC', 'Bis zu welchem Bestellwert m&ouml;chten Sie Zahlungen per Lastschriftverfahren erlauben? ');

define('MODULE_PAYMENT_SEPABT_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_PAYMENT_SEPABT_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');

define('MODULE_PAYMENT_SEPABT_TO_ADMIN_TITLE', 'Bankinfo per Mail');
define('MODULE_PAYMENT_SEPABT_TO_ADMIN_DESC', 'M&ouml;chten Sie die Bankdaten aus dem Lastschriftverfahren mit der E-Mail erhalten?');

define('MODULE_PAYMENT_SEPABT_PAYEE_TITLE', 'Zahlungsempf&auml;ngerr');	
define('MODULE_PAYMENT_SEPABT_PAYEE_DESC', 'Bitte geben Sie einen Zahlungsempf&auml;nger ein');

define('MODULE_PAYMENT_SEPABT_CREDITORID_TITLE', 'Gl&auml;ubiger-Identifikationsnummer');	
define('MODULE_PAYMENT_SEPABT_CREDITORID_DESC', 'Bitte geben Sie Ihre Gl&auml;ubiger-Identifikationsnummer ein');

$aLang['module_payment_sepabt_text_title'] = 'Lastschriftverfahren (SEPA)';
$aLang['module_payment_sepabt_text_description'] = 'Lastschriftverfahren (SEPA)';

  define('module_payment_sepabt_text_note','Hinweis: ');
  define('MODULE_PAYMENT_SEPABT_TEXT_INFO', '');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK',  'Lastschrift');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_INFO', 'Hiermit erm&auml;chtige(n) ich/wir Sie widerruflich, die von mir/uns zu entrichtenden Zahlungen zu Lasten meines/unseres Kontos mit nachstehenden Daten durch Lastschrift einzuziehen. Wenn mein/unser Konto die erforderliche Deckung nicht aufweist, besteht seitens des kontof&uuml;hrenden Instituts keine Verpflichtung zur Einl&ouml;sung. Teileinl&ouml;sungen werden im Lastschriftverfahren nicht vorgenommen.');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER', 'Kontoinhaber:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN', 'IBAN:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_BIC', 'SWIFT-Code(BIC):');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_COUNTRY', 'Land der Bank:');
  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME', 'Bankname:');

  define('MODULE_PAYMENT_SEPABT_TEXT_BANK_ERROR','Fehler:');

$aLang['sepabt_bic'] = 'Bitte geben Sie die BIC-Nummer Ihrer Bank ein.';
$aLang['js_sepabt_swift_id'] = 'Bitte w&auml;hen Sie das Land aus, in dem sich die Bank befindet.';
$aLang['sepabt_iban'] = 'Bitte geben Sie die IBAN-Nummer Ihrer Bank ein.';
$aLang['sepabt_owner'] = 'Bitte geben Sie den Namen des Kontoinhabers ein.';

$aLang['module_payment_sepabt_text_note'] = 'Damit wir die SEPA-Lastschrift von Ihrem Konto einziehen k&ouml;nnen, ben&ouml;tigen wir von Ihnen ein SEPA-Lastschriftmandat. Das Mandat wird bei uns als PDF zu Ihren Zahldaten hinterlegt. Sie erhalten das Mandat als PDF an die von Ihnen angegebene E-Mail-Adresse.';

$aLang['module_payment_sepabt_text_mandat'] = '<p>Zahlungsempf&auml;nger: %s<br/>
Gl&auml;ubiger-Identifikationsnummer: %s<br/>
Mandatsreferenz: %s</p>
<p>Ich erm&auml;chtige den Zahlungsempf&auml;nger, Zahlungen von meinem Konto mittels Lastschrift einzuziehen. Zugleich weise ich mein Kreditinstitut an, die von dem Zahlungsempf&auml;nger auf mein Konto gezogenen Lastschriften einzul&ouml;sen.</p>
<p>Hinweis: Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.</p>
<p></p>';
