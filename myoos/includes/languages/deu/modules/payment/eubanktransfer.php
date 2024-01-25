<?php
/**
   ----------------------------------------------------------------------
   $Id: eubanktransfer.php,v 1.4 2008/08/25 14:28:07 r23 Exp $

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

if (!defined('MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME')) {
    define('MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME', '----');
    define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME', '----');
    define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM', '----');
    define('MODULE_PAYMENT_EU_BANKTRANSFER_IBAN', '----');
    define('MODULE_PAYMENT_EU_BANKTRANSFER_BIC', '----');
}


define('MODULE_PAYMENT_EU_BANKTRANSFER_STATUS_TITLE', 'Allow Bank Transfer Payment');
define('MODULE_PAYMENT_EU_BANKTRANSFER_STATUS_DESC', 'Do you want to accept bank transfers?');

define('MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME_TITLE', 'Bank Name');
define('MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME_DESC', 'Your full bank name');

define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME_TITLE', 'Account Name');
define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME_DESC', 'Name which is registered for the account.');

define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM_TITLE', 'Bank Account No.');
define('MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM_DESC', 'Your account number.');

define('MODULE_PAYMENT_EU_BANKTRANSFER_IBAN_TITLE', 'Bank Account IBAN');
define('MODULE_PAYMENT_EU_BANKTRANSFER_IBAN_DESC', 'International account id.<br />(ask your bank if you don\'t know it)');

define('MODULE_PAYMENT_EU_BANKTRANSFER_BIC_TITLE', 'Your account BIC / SWIFT code');
define('MODULE_PAYMENT_EU_BANKTRANSFER_BIC_DESC', 'International bank id.<br />(ask your bank if you don\'t know it)');

define('MODULE_PAYMENT_EU_BANKTRANSFER_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_EU_BANKTRANSFER_ZONE_DESC', 'Wenn eine Zone ausgewählt ist, gilt die Zahlungsmethode nur für diese Zone.');

define('MODULE_PAYMENT_EU_BANKTRANSFER_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_EU_BANKTRANSFER_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_EU_BANKTRANSFER_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_EU_BANKTRANSFER_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


$aLang['module_payment_eu_banktransfer_text_title'] = 'European Bank Transfer';
$aLang['module_payment_eu_banktransfer_text_description'] = '<br />Die einfachste Zahlungsmethode innerhalb der EU ist die Überweisung mittels IBAN und BIC.' .
                                                            '<br />Bitte verwenden Sie folgende Daten für die Überweisung des Gesamtbetrages:<br />' .
                                                            '<br />Name der Bank: ' . MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME .
                                                            '<br />Kontoname: ' . MODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME .
                                                            '<br />Kontonummer: ' . MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM .
                                                            '<br />IBAN: ' . MODULE_PAYMENT_EU_BANKTRANSFER_IBAN .
                                                            '<br />BIC/SWIFT: ' . MODULE_PAYMENT_EU_BANKTRANSFER_BIC .
                                                            '<br /><br />Die Ware wird ausgeliefert wenn der Betrag auf unserem Konto eingegangen ist.<br />';


$aLang['module_payment_eu_banktransfer_email_footer'] = 'Die einfachste Zahlungsmethode innerhalb der EU ist die Überweisung mittels IBAN und BIC.' .
                                                        'Bitte verwenden Sie folgende Daten für die Überweisung des Gesamtbetrages:' . "\n\n" .
                                                        'Name der Bank: ' . MODULE_PAYMENT_EU_BANKTRANSFER_BANKNAME . "\n" .
                                                        'Kontoname: ' . MODULE_PAYMENT_EU_BANKTRANSFER_KONTONAME . "\n" .
                                                        'Kontonummer: ' . MODULE_PAYMENT_EU_BANKTRANSFER_KONTONUM . "\n" .
                                                        'IBAN: ' . MODULE_PAYMENT_EU_BANKTRANSFER_IBAN . "\n" .
                                                        'BIC/SWIFT: ' . MODULE_PAYMENT_EU_BANKTRANSFER_BIC . "\n\n" .
                                                        'Die Ware wird ausgeliefert wenn der Betrag auf unserem Konto eingegangen ist.';
