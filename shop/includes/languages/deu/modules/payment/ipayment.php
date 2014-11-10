<?php
/* ----------------------------------------------------------------------
   $Id: ipayment.php,v 1.5 2008/08/25 14:28:07 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 200 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ipayment.php,v 1.6 2002/11/01 22:19:27 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_IPAYMENT_STATUS_TITLE', 'iPayment Modul aktivieren');
define('MODULE_PAYMENT_IPAYMENT_STATUS_DESC', 'M&ouml;chten Sie Zahlungen per iPayment akzeptieren?');

define('MODULE_PAYMENT_IPAYMENT_ID_TITLE', 'Account Number');
define('MODULE_PAYMENT_IPAYMENT_ID_DESC', 'The account number used for the iPayment service');

define('MODULE_PAYMENT_IPAYMENT_USER_ID_TITLE', 'Benutzer ID');
define('MODULE_PAYMENT_IPAYMENT_USER_ID_DESC', 'Benutzer ID welche f&uuml;r iPayment verwendet wird');

define('MODULE_PAYMENT_IPAYMENT_PASSWORD_TITLE', 'Benutzer-Passwort');
define('MODULE_PAYMENT_IPAYMENT_PASSWORD_DESC', 'Benutzer-Passwort welches f&uuml;r iPayment verwendet wird');

define('MODULE_PAYMENT_IPAYMENT_CURRENCY_TITLE', 'Transaktionsw&auml;hrung');
define('MODULE_PAYMENT_IPAYMENT_CURRENCY_DESC', 'W&auml;hrung, welche f&uuml;r Kreditkartentransaktionen verwendet wird.');

define('MODULE_PAYMENT_IPAYMENT_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_IPAYMENT_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_IPAYMENT_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_IPAYMENT_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_IPAYMENT_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_IPAYMENT_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


$aLang['module_payment_ipayment_text_title'] = 'iPayment';
$aLang['module_payment_ipayment_text_description'] = 'Kreditkarten Test Info:<br /><br />CC#: 4111111111111111<br />G&uuml;ltig bis: Any';
$aLang['ipayment_error_heading'] = 'Folgender Fehler wurde von iPayment w&auml;hrend des Prozesses gemeldet:';
$aLang['ipayment_error_message'] = 'Bitte kontrollieren Sie die Daten Ihrer Kreditkarte!';
$aLang['module_payment_ipayment_text_credit_card_owner'] = 'Kreditkarteninhaber';
$aLang['module_payment_ipayment_text_credit_card_number'] = 'Kreditkarten-Nr.:';
$aLang['module_payment_ipayment_text_credit_card_expires'] = 'G&uuml;ltig bis:';
$aLang['module_payment_ipayment_text_credit_card_checknumber'] = 'Karten-Pr&uuml;fnummer';
$aLang['module_payment_ipayment_text_credit_card_checknumber_location'] = '(Auf der Kartenr&uuml;ckseite im Unterschriftsfeld)';

$aLang['module_payment_ipayment_text_js_cc_owner'] = '* Der Name des Kreditkarteninhabers muss mindestens aus  ' . CC_OWNER_MIN_LENGTH . ' Zeichen bestehen.\n';
$aLang['module_payment_ipayment_text_js_cc_number'] = '* Die \'Kreditkarten-Nr.\' muss mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n';

?>