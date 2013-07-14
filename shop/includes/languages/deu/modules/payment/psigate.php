<?php
/* ----------------------------------------------------------------------
   $Id: psigate.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: psigate.php,v 1.3 2002/11/12 12:51:42 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_PSIGATE_STATUS_TITLE', 'PSiGate Modul aktivieren');
define('MODULE_PAYMENT_PSIGATE_STATUS_DESC', 'M&ouml;chten Sie Zahlungen per PSiGate akzeptieren?');

define('MODULE_PAYMENT_PSIGATE_MERCHANT_ID_TITLE', 'Merchant ID');
define('MODULE_PAYMENT_PSIGATE_MERCHANT_ID_DESC', 'Merchant ID, welche f&uuml;r den PSiGate Service verwendet wird');

define('MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE_TITLE', 'Transaktions Modus');
define('MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE_DESC', 'Transaktions Modus, welcher f&uuml;r PSiGate verwendet wird');

define('MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE_TITLE', 'Transaktions Typ');
define('MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE_DESC', 'Transaction type to use for the PSiGate service');

define('MODULE_PAYMENT_PSIGATE_INPUT_MODE_TITLE', 'Kreditkarten Erfassung');
define('MODULE_PAYMENT_PSIGATE_INPUT_MODE_DESC', 'Sollen die Kreditkarten Details lokal erfasst werden, oder bei PSiGate?');

define('MODULE_PAYMENT_PSIGATE_CURRENCY_TITLE', 'Transaktionsw&auml;hrung');
define('MODULE_PAYMENT_PSIGATE_CURRENCY_DESC', 'W&auml;hrung, welche f&uuml;r Kreditkartentransaktionen verwendet wird');

define('MODULE_PAYMENT_PSIGATE_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_PSIGATE_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_PSIGATE_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_PSIGATE_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


$aLang['module_payment_psigate_text_title'] = 'PSiGate';
$aLang['module_payment_psigate_text_description'] = 'Kreditkarten Test Info:<br /><br />CC#: 4111111111111111<br />G&uuml;ltig bis: Any';
$aLang['module_payment_psigate_text_credit_card_owner'] = 'Kreditkarteninhaber:';
$aLang['module_payment_psigate_text_credit_card_number'] = 'Kreditkarten-Nr.:';
$aLang['module_payment_psigate_text_credit_card_expires'] = 'G&uuml;ltig bis:';
$aLang['module_payment_psigate_text_type'] = 'Typ:';
$aLang['module_payment_psigate_text_js_cc_number'] = '* Die \'Kreditkarten-Nr.\' muss mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n';
$aLang['module_payment_psigate_text_error_message'] = 'Bei der &Uuml;berp&uuml;fung Ihrer Kreditkarte ist ein Fehler aufgetreten! Bitte versuchen Sie es nochmal.';
$aLang['module_payment_psigate_text_error'] = 'Fehler bei der &Uuml;berp&uuml;fung der Kreditkarte!';

?>