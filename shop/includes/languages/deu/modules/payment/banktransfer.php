<?php
/* ----------------------------------------------------------------------
   $Id: banktransfer.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banktransfer.php,v 1.9 2003/02/18 19:22:15 dogu
   ----------------------------------------------------------------------

   OSC German Banktransfer
   http://www.oscommerce.com/community/contributions,826

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

define('MODULE_PAYMENT_BANKTRANSFER_STATUS_TITLE', 'Banktranfer Zahlungen erlauben');
define('MODULE_PAYMENT_BANKTRANSFER_STATUS_DESC', 'M&ouml;chten Banktranfer Zahlungen erlauben?');

define('MODULE_PAYMENT_BANKTRANSFER_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_BANKTRANSFER_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER_TITLE', 'Lastschriftverfahren bis zum Bestellwert erlauben');
define('MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER_DESC', 'Bis zu welchem Bestellwert m&ouml;chten Sie Zahlungen per Lastschriftverfahren erlauben? ');


define('MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');

define('MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION_TITLE', 'Fax Best&auml;tigung erlauben');
define('MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION_DESC', 'M&ouml;chten Sie die Fax Best&auml;tigung erlauben?');

define('MODULE_PAYMENT_BANKTRANSFER_URL_NOTE_TITLE', 'Fax-Datei');
define('MODULE_PAYMENT_BANKTRANSFER_URL_NOTE_DESC', 'Die Fax-Best&auml;tigungsdatei. Diese muss im Catalog-Verzeichnis liegen');


$aLang['module_payment_banktransfer_text_title'] = 'Lastschriftverfahren';
$aLang['module_payment_banktransfer_text_description'] = 'Lastschriftverfahren';
$aLang['module_payment_banktransfer_text_bank'] = 'Bankeinzug';
$aLang['module_payment_banktransfer_text_email_footer'] = 'Hinweis: Sie k&ouml;nnen sich unser Faxformular unter ' . OOS_HTTP_SERVER . OOS_SHOP . MODULE_PAYMENT_BANKTRANSFER_URL_NOTE . ' herunterladen und es ausgef&uuml;llt an uns zur&uuml;cksenden.';
$aLang['module_payment_banktransfer_text_bank_info'] = 'Bitte beachten Sie, dass das Lastschriftverfahren <b>nur</b> von einem <b>deutschen Girokonto</b> aus m&ouml;glich ist';
$aLang['module_payment_banktransfer_text_bank_owner'] = 'Kontoinhaber:';
$aLang['module_payment_banktransfer_text_bank_number'] = 'Kontonummer:';
$aLang['module_payment_banktransfer_text_bank_blz'] = 'BLZ:';
$aLang['module_payment_banktransfer_text_bank_name'] = 'Bank:';
$aLang['module_payment_banktransfer_text_bank_fax'] = 'Einzugserm&auml;chtigung wird per Fax best&auml;tigt';

$aLang['module_payment_banktransfer_text_bank_error'] = '<font color="#FF0000">FEHLER: </font>';
$aLang['module_payment_banktransfer_text_bank_error_1'] = 'Kontonummer und BLZ stimmen nicht &uuml;berein!<br />Bitte &uuml;berpr&uuml;fen Sie Ihre Angaben nochmals.';
$aLang['module_payment_banktransfer_text_bank_error_2'] = 'F&uuml;r diese Kontonummer ist kein Pr&uuml;fziffernverfahren definiert!';
$aLang['module_payment_banktransfer_text_bank_error_3'] = 'Kontonummer nicht pr&uuml;fbar!';
$aLang['module_payment_banktransfer_text_bank_error_4'] = 'Kontonummer nicht pr&uuml;fbar!<br />Bitte &uuml;berpr&uuml;fen Sie Ihre Angaben nochmals.';
$aLang['module_payment_banktransfer_text_bank_error_5'] = 'Bankleitzahl nicht gefunden!<br />Bitte &uuml;berpr&uuml;fen Sie Ihre Angaben nochmals.';
$aLang['module_payment_banktransfer_text_bank_error_8'] = 'Fehler bei der Bankleitzahl oder keine Bankleitzahl angegeben!';
$aLang['module_payment_banktransfer_text_bank_error_9'] = 'Keine Kontonummer angegeben!';

$aLang['module_payment_banktransfer_text_note'] = 'Hinweis:';
$aLang['module_payment_banktransfer_text_note2'] = 'Wenn Sie aus Sicherheitsbedenken keine Bankdaten &uuml;ber das Internet<br />&uuml;bertragen wollen, k&ouml;nnen Sie sich unser ';
$aLang['module_payment_banktransfer_text_note3'] = 'Faxformular';
$aLang['module_payment_banktransfer_text_note4'] = ' herunterladen und uns ausgef&uuml;llt zusenden.';

$aLang['js_bank_blz'] = 'Bitte geben Sie die BLZ Ihrer Bank ein!\n';
$aLang['js_bank_name'] = 'Bitte geben Sie den Namen Ihrer Bank ein!\n';
$aLang['js_bank_number'] = 'Bitte geben Sie Ihre Kontonummer ein!\n';
$aLang['js_bank_owner'] = 'Bitte geben Sie den Namen des Kontobesitzers ein!\n';

?>