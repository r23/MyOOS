<?php
/* ----------------------------------------------------------------------
   $Id: cc.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: cc.php,v 1.11 2003/02/16 01:12:22 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_CC_STATUS_TITLE', 'Enable Credit Card Module');
define('MODULE_PAYMENT_CC_STATUS_DESC', 'Do you want to accept credit card payments?');

define('MODULE_PAYMENT_CC_EMAIL_TITLE', 'Split Credit Card E-Mail Address');
define('MODULE_PAYMENT_CC_EMAIL_DESC', 'If an e-mail address is entered, the middle digits of the credit card number will be sent to the e-mail address (the outside digits are stored in the database with the middle digits censored)');

define('MODULE_PAYMENT_CC_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_CC_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_CC_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_CC_ZONE_DESC', 'Wenn eine Zone ausgewählt ist, gilt die Zahlungsmethode nur für diese Zone.');

define('MODULE_PAYMENT_CC_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_CC_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');

$aLang['module_payment_cc_text_title'] = 'Kreditkarte';
$aLang['module_payment_cc_text_description'] = 'Kreditkarten Test Info:<br /><br />CC#: 4111111111111111<br />Gültig bis: Any';
$aLang['module_payment_cc_text_credit_card_type'] = 'Typ:';
$aLang['module_payment_cc_text_credit_card_owner'] = 'Kreditkarteninhaber:';
$aLang['module_payment_cc_text_credit_card_number'] = 'Kreditkarten-Nr.:';
$aLang['module_payment_cc_text_credit_card_expires'] = 'Gültig bis:';
$aLang['module_payment_cc_text_js_cc_owner'] = '* Der \'Name des Inhabers\' muss mindestens aus ' . CC_OWNER_MIN_LENGTH . ' Buchstaben bestehen.\n';
$aLang['module_payment_cc_text_js_cc_number'] = '* Die \'Kreditkarten-Nr.\' muss mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n';
$aLang['module_payment_cc_text_error'] = 'Fehler bei der &Uuml;berpüfung der Kreditkarte!';

