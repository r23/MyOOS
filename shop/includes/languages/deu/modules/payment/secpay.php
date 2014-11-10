<?php
/* ----------------------------------------------------------------------
   $Id: secpay.php,v 1.5 2008/08/25 14:28:07 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: secpay.php,v 1.8 2002/11/01 22:19:28 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('MODULE_PAYMENT_SECPAY_STATUS_TITLE', 'SECpay Modul aktivieren');
define('MODULE_PAYMENT_SECPAY_STATUS_DESC', 'M&ouml;chten Sie Zahlungen per SECPay akzeptieren?');

define('MODULE_PAYMENT_SECPAY_MERCHANT_ID_TITLE', 'Merchant ID');
define('MODULE_PAYMENT_SECPAY_MERCHANT_ID_DESC', 'Merchant ID f&uuml;r den SECPay Service');

define('MODULE_PAYMENT_SECPAY_CURRENCY_TITLE', 'Transaktionsw&auml;hrung');
define('MODULE_PAYMENT_SECPAY_CURRENCY_DESC', 'Die W&auml;hrung, die f&uuml;r Kreditkartentransaktionen verwendet wird');

define('MODULE_PAYMENT_SECPAY_TEST_STATUS_TITLE', 'Transaktionsmodus');
define('MODULE_PAYMENT_SECPAY_TEST_STATUS_DESC', 'Transaktionsmodus, welcher f&uuml;r dieses Modul verwendet werden soll');

define('MODULE_PAYMENT_SECPAY_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_SECPAY_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_SECPAY_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_SECPAY_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_SECPAY_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_SECPAY_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


$aLang['module_payment_secpay_text_title'] = 'SECPay';
$aLang['module_payment_secpay_text_description'] = 'Kreditkarten Test Info:<br /><br />CC#: 4444333322221111<br />G&uuml;ltig bis: Any';
$aLang['module_payment_secpay_text_error'] = 'Fehler bei der &Uuml;berp&uuml;fung der Kreditkarte!';
$aLang['module_payment_secpay_text_error_message'] = 'Bei der &Uuml;berp&uuml;fung Ihrer Kreditkarte ist ein Fehler aufgetreten! Bitte versuchen Sie es nochmal.';

?>