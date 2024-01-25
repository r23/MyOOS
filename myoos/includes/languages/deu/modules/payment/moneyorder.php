<?php
/**
   ----------------------------------------------------------------------
   $Id: moneyorder.php,v 1.5 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: moneyorder.php,v 1.8 2003/02/16 01:12:22 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_PAYMENT_MONEYORDER_STATUS_TITLE', 'Vorkasse/Banküberweisung');
define('MODULE_PAYMENT_MONEYORDER_STATUS_DESC', 'Möchten Sie Zahlungen per Vorkasse/Banküberweisung akzeptieren?');

define('MODULE_PAYMENT_MONEYORDER_PAYTO_TITLE', 'Zahlbar an:');
define('MODULE_PAYMENT_MONEYORDER_PAYTO_DESC', 'An wen sollen Zahlungen erfolgen?');

define('MODULE_PAYMENT_MONEYORDER_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_MONEYORDER_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_MONEYORDER_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_MONEYORDER_ZONE_DESC', 'Wenn eine Zone ausgewählt ist, gilt die Zahlungsmethode nur für diese Zone.');

define('MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');

$aLang['module_payment_moneyorder_text_title'] = 'Vorkasse/Banküberweisung';
$aLang['module_payment_moneyorder_text_description'] = 'Zahlbar an:&nbsp;' . (defined('MODULE_PAYMENT_MONEYORDER_PAYTO') ? nl2br((string) MODULE_PAYMENT_MONEYORDER_PAYTO) : '') . '<br />Kontoinhaber:<br />' . nl2br((string) STORE_OWNER) . '<br /><br />' . 'Die Ware wird ausgeliefert wenn der Betrag auf unserem Konto eingegangen ist.';
$aLang['module_payment_moneyorder_text_email_footer'] = "Zahlbar an: ". (defined('MODULE_PAYMENT_MONEYORDER_PAYTO') ? nl2br((string) MODULE_PAYMENT_MONEYORDER_PAYTO) : '') . "\n\nKontoinhaber:\n" . STORE_OWNER . "\n\n" . 'Ihre Bestellung wird nach Geldeingang auf unser Konto an Sie versendet.';
