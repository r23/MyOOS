<?php
/* ----------------------------------------------------------------------
   $Id: pm2checkout.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: pm2checkout.php,v 1.4 2002/11/01 22:19:27 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_2CHECKOUT_STATUS_TITLE', '2CheckOut Modul aktivieren');
define('MODULE_PAYMENT_2CHECKOUT_STATUS_DESC', 'M&ouml;chten Sie Zahlungen per 2CheckOut akzeptieren?');

define('MODULE_PAYMENT_2CHECKOUT_LOGIN_TITLE', 'Anmelde/Shop Nummer');
define('MODULE_PAYMENT_2CHECKOUT_LOGIN_DESC', 'Anmelde/Shop Nummer welche f&uuml;r 2CheckOut verwendet wird');

define('MODULE_PAYMENT_2CHECKOUT_TESTMODE_TITLE', 'Transaktions Modus');
define('MODULE_PAYMENT_2CHECKOUT_TESTMODE_DESC', 'Transaktions Modus, welcher f&uuml;r 2CheckOut verwendet wird');


define('MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT_TITLE', 'Merchant Benachrichtigungen');
define('MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT_DESC', 'Soll 2CheckOut eine Best&auml;tigungs-eMail an den Shop-Besitzer senden?');

define('MODULE_PAYMENT_2CHECKOUT_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_2CHECKOUT_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_2CHECKOUT_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_2CHECKOUT_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


$aLang['module_payment_2checkout_text_title'] = '2CheckOut';
$aLang['module_payment_2checkout_text_description'] = 'Kreditkarten Test Info:<br /><br />CC#: 4111111111111111<br />G&uuml;ltig bis: Any';
$aLang['module_payment_2checkout_text_type'] = 'Typ:';
$aLang['module_payment_2checkout_text_credit_card_owner'] = 'Kreditkarteninhaber:';
$aLang['module_payment_2checkout_text_credit_card_owner_first_name'] = 'Kreditkarteninhaber Vorname:';
$aLang['module_payment_2checkout_text_credit_card_owner_last_name'] = 'Kreditkarteninhaber Nachname:';
$aLang['module_payment_2checkout_text_credit_card_number'] = 'Kreditkarten-Nr.:';
$aLang['module_payment_2checkout_text_credit_card_expires'] = 'G&uuml;ltig bis:';
$aLang['module_payment_2checkout_text_credit_card_checknumber'] = 'Karten-Pr&uuml;fnummer:';
$aLang['module_payment_2checkout_text_credit_card_checknumber_location'] = '(Auf der Kartenr&uuml;ckseite im Unterschriftsfeld)';
$aLang['module_payment_2checkout_text_js_cc_number'] = '* Die \'Kreditkarten-Nr.\' muss mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n';
$aLang['module_payment_2checkout_text_error_message'] = 'Bei der &Uuml;berp&uuml;fung Ihrer Kreditkarte ist ein Fehler aufgetreten! Bitte versuchen Sie es nochmal.';
$aLang['module_payment_2checkout_text_error'] = 'Fehler bei der &Uuml;berp&uuml;fung der Kreditkarte!';

?>