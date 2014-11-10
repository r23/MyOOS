<?php
/* ----------------------------------------------------------------------
   $Id: authorizenet.php,v 1.4 2008/08/13 16:40:45 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: authorizenet.php,v 1.15 2003/02/16 01:12:22 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_AUTHORIZENET_STATUS_TITLE', 'Authorize.net Modul aktivieren');
define('MODULE_PAYMENT_AUTHORIZENET_STATUS_DESC', 'M&ouml;chten Sie Zahlungen per Authorize.net akzeptieren?');

define('MODULE_PAYMENT_AUTHORIZENET_LOGIN_TITLE', 'Anmelde-Benutzernamename');
define('MODULE_PAYMENT_AUTHORIZENET_LOGIN_DESC', 'Anmelde-Benutzernamename, welcher f&uuml;r das Authorize.net Service verwendet wird');

define('MODULE_PAYMENT_AUTHORIZENET_TXNKEY_TITLE', 'Transaktionschl&uuml;ssel');
define('MODULE_PAYMENT_AUTHORIZENET_TXNKEY_DESC', 'Transaktionschl&uuml;ssel welcher zum Verschl&uuml;sseln von TP Daten verwendet wird');

define('MODULE_PAYMENT_AUTHORIZENET_TESTMODE_TITLE', 'Transaktionsmodus');
define('MODULE_PAYMENT_AUTHORIZENET_TESTMODE_DESC', 'Transaktionsmodus, welcher f&uuml;r dieses Modul verwendet werden soll');

define('MODULE_PAYMENT_AUTHORIZENET_METHOD_TITLE', 'Transaktions Methode');
define('MODULE_PAYMENT_AUTHORIZENET_METHOD_DESC', 'Transaktions Methode, welche f&uuml;r dieses Modul verwendet werden soll');

define('MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER_TITLE', 'Kundenbenachrichtigungen');
define('MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER_DESC', 'Soll Authorize.Net eine Best&auml;tigungs-eMail an den Kunden senden?');

define('MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_AUTHORIZENET_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_AUTHORIZENET_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


$aLang['module_payment_authorizenet_text_title'] = 'Authorize.net';
$aLang['module_payment_authorizenet_text_description'] = 'Kreditkarten Test Info:<br /><br />CC#: 4111111111111111<br />G&uuml;ltig bis: Any';
$aLang['module_payment_authorizenet_text_type'] = 'Typ:';
$aLang['module_payment_authorizenet_text_credit_card_owner'] = 'Kreditkarteninhaber:';
$aLang['module_payment_authorizenet_text_credit_card_number'] = 'Kreditkarten-Nr.:';
$aLang['module_payment_authorizenet_text_credit_card_expires'] = 'G&uuml;ltig bis:';
$aLang['module_payment_authorizenet_text_js_cc_owner'] = '* Der Name des Kreditkarteninhabers muss mindestens aus  ' . CC_OWNER_MIN_LENGTH . ' Zeichen bestehen.\n';
$aLang['module_payment_authorizenet_text_js_cc_number'] = '* Die \'Kreditkarten-Nr.\' muss mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n';
$aLang['module_payment_authorizenet_text_error_message'] = 'Bei der &Uuml;berp&uuml;fung Ihrer Kreditkarte ist ein Fehler aufgetreten! Bitte versuchen Sie es nochmal.';
$aLang['module_payment_authorizenet_text_declined_message'] = 'Ihre Kreditkarte wurde abgelehnt. Bitte versuchen Sie es mit einer anderen Karte oder kontaktieren Sie Ihre Bank f&uuml;r weitere Informationen.';
$aLang['module_payment_authorizenet_text_error'] = 'Fehler bei der &Uuml;berp&uuml;fung der Kreditkarte!';

?>
