<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_payment.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_payment.php,v 1.5 2003/02/17 14:18:30 harley_vb  
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Partnerprogramm Provisionszahlungen');
define('HEADING_TITLE_SEARCH', 'Suchen:');
define('HEADING_TITLE_STATUS','Status:');

define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_AFILIATE_NAME', 'Partner');
define('TABLE_HEADING_PAYMENT','Provision (inkl.)');
define('TABLE_HEADING_NET_PAYMENT','Provision (exkl.)');
define('TABLE_HEADING_DATE_BILLED','Datum abgerechnet');
define('TABLE_HEADING_NEW_VALUE', 'neuer Status');
define('TABLE_HEADING_OLD_VALUE', 'alter Status');
define('TABLE_HEADING_AFFILIATE_NOTIFIED', 'Partner benachrichtigen');
define('TABLE_HEADING_DATE_ADDED', 'hinzugef&uuml;gt am:');

define('TEXT_DATE_PAYMENT_BILLED', 'Verrechnet:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'letzte &Auml;nderung:');
define('TEXT_AFFILIATE_PAYMENT', 'Provision');
define('TEXT_AFFILIATE_BILLED', 'Abrechnungsdatum');
define('TEXT_AFFILIATE', 'Partner');

define('TEXT_INFO_HEADING_DELETE_PAYMENT', 'Abrechnung l&ouml;schen');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, das Sie diese Provisionszahlung l&ouml;schen m&ouml;chten?');

define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Provisionszahlungen)');

define('TEXT_AFFILIATE_PAYING_POSSIBILITIES', 'Auszahlungsm&ouml;glichkeiten:');
define('TEXT_AFFILIATE_PAYMENT_CHECK', 'per Check:');
define('TEXT_AFFILIATE_PAYMENT_CHECK_PAYEE', 'Empf&auml;nger des Schecks:');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL', 'per PayPal:');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL_EMAIL', 'PayPal Account eMail:');
define('TEXT_AFFILIATE_PAYMENT_BANK_TRANSFER', 'per Bankberweisung:');
define('TEXT_AFFILIATE_PAYMENT_BANK_NAME', 'Kreditinstitut:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME', 'Kontoinhaber:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER', 'Konto-Nr.:');
define('TEXT_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER', 'Bankleitzahl:');
define('TEXT_AFFILIATE_PAYMENT_BANK_SWIFT_CODE', 'SWIFT Code:');

define('IMAGE_AFFILIATE_BILLING', 'Start Billing Engine');

define('PAYMENT_STATUS', 'Abrechnungsstatus');
define('PAYMENT_NOTIFY_AFFILIATE', 'Partner benachrichtigen');

define('TEXT_ALL_PAYMENTS', 'Alle Zahlungen');
define('TEXT_NO_PAYMENT_HISTORY', 'Keine Zahlungshistorie verf&uuml;gbar!');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Status�derung Ihrer Provisionsabrechnung');
define('EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER', 'Provisionsabrechnung-Nr.:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailierte Provisionsabrechnung:');
define('EMAIL_TEXT_PAYMENT_BILLED', 'Abrechnungsdatum');
define('EMAIL_TEXT_STATUS_UPDATE', 'Der Status Ihrer Provisionsabrechnung wurde ge�dert.' . "\n\n" . 'Neuer Status: %s' . "\n\n" . 'Bei Fragen zu Ihrer Provisionsabrechnung antworten Sie bitte auf diese eMail.' . "\n\n" . 'Mit freundlichen Grssen' . "\n");
define('EMAIL_TEXT_NEW_PAYMENT', 'Eine neue Abrechnung ber Ihre Provisionen wurde erstellt.' . "\n");

define('SUCCESS_BILLING', 'Hinweis: Ihre Provisionen wurden erfolgreich abgerechnet!');
define('SUCCESS_PAYMENT_UPDATED', 'Hinweis: Der Status dieser Provisionsabrechnung wurde erfolgreich aktualisiert.');
define('ERROR_PAYMENT_DOES_NOT_EXIST', 'Fehler: Die Provisionsabrechnung existiert nicht!');
?>
