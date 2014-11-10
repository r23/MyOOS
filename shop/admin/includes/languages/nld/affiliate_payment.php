<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_payment.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

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


define('HEADING_TITLE', 'Partnerprogramma provisiebetalingen');
define('HEADING_TITLE_SEARCH', 'Zoeken:');
define('HEADING_TITLE_STATUS','Status:');

define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_AFILIATE_NAME', 'Partner');
define('TABLE_HEADING_PAYMENT','Provisie (incl.)');
define('TABLE_HEADING_NET_PAYMENT','Provisie (excl.)');
define('TABLE_HEADING_DATE_BILLED','Datum facuur');
define('TABLE_HEADING_NEW_VALUE', 'nieuwe status');
define('TABLE_HEADING_OLD_VALUE', 'oude status');
define('TABLE_HEADING_AFFILIATE_NOTIFIED', 'Partner berichten');
define('TABLE_HEADING_DATE_ADDED', 'toegevoegd op:');

define('TEXT_DATE_PAYMENT_BILLED', 'Verrekend:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'laatste verandering:');
define('TEXT_AFFILIATE_PAYMENT', 'Provisie');
define('TEXT_AFFILIATE_BILLED', 'Factuurdatum');
define('TEXT_AFFILIATE', 'Partner');

define('TEXT_INFO_HEADING_DELETE_PAYMENT', 'Factuur wissen');
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker, dat u de provisiebetaling wilt wissen?');

define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Getoond worden <b>%d</b> tot <b>%d</b> (van in totaal <b>%d</b> provisiebetalingen)');

define('TEXT_AFFILIATE_PAYING_POSSIBILITIES', 'Uitbetalings mogelijkheden:');
define('TEXT_AFFILIATE_PAYMENT_CHECK', 'per cheque:');
define('TEXT_AFFILIATE_PAYMENT_CHECK_PAYEE', 'Ontvanger van cheque:');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL', 'per PayPal:');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL_EMAIL', 'PayPal rekening email:');
define('TEXT_AFFILIATE_PAYMENT_BANK_TRANSFER', 'per bankoverschrijving:');
define('TEXT_AFFILIATE_PAYMENT_BANK_NAME', 'Credietorganisatie:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME', 'Rekeninghouder:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER', 'Rekening-Nr.:');
define('TEXT_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER', 'Banknummer:');
define('TEXT_AFFILIATE_PAYMENT_BANK_SWIFT_CODE', 'BIC Code:');

define('IMAGE_AFFILIATE_BILLING', 'Start factureringsprocedure');

define('PAYMENT_STATUS', 'Afrekenstatus');
define('PAYMENT_NOTIFY_AFFILIATE', 'Partner berichten');

define('TEXT_ALL_PAYMENTS', 'Alle betalingen');
define('TEXT_NO_PAYMENT_HISTORY', 'Geen betalingsgeschiedenis beschikbaar!');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Statusverandering uw provisieafrekening');
define('EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER', 'Provisieafrekings-Nr.:');
define('EMAIL_TEXT_INVOICE_URL', 'Gedetaileerde provisieafrekening:');
define('EMAIL_TEXT_PAYMENT_BILLED', 'Afrekendatum');
define('EMAIL_TEXT_STATUS_UPDATE', 'De status van uw provisieafrekening werd veranderd.' . "\n\n" . 'Nieuwe status: %s' . "\n\n" . 'Voor vragen over uw provisieafrekening beantwoord dan a.u.b. op deze email.' . "\n\n" . 'Met vriendelijke groeten' . "\n");
define('EMAIL_TEXT_NEW_PAYMENT', 'Een nieuwe afrekening inzake uw provisie werd uitgedraaid.' . "\n");

define('SUCCESS_BILLING', 'Notitie: Uw provisie werd succesvol afgerekend!');
define('SUCCESS_PAYMENT_UPDATED', 'Notitie: De status van deze provisieafrekening werd succesvol geactualiseerd.');
define('ERROR_PAYMENT_DOES_NOT_EXIST', 'Fout: De provisieafrekening bestaat niet!');
?>
