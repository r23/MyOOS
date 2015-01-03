<?php
/* ----------------------------------------------------------------------
   $Id: customers_status.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers_status.php,v 1.1 2002/09/30
   ----------------------------------------------------------------------
   For Customers Status v3.x

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Kundengruppen');

define('TABLE_HEADING_CUSTOMERS_STATUS', 'Kundengruppe');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_CUSTOMERS_QTY_DISCOUNTS', 'Staffelpreis');
define('TABLE_HEADING_AMOUNT', 'Bestellwert');

define('TEXT_INFO_EDIT_INTRO', 'Bitte nehmen Sie alle n&ouml;tigen Einstellungen vor');
define('TEXT_INFO_CUSTOMERS_STATUS_NAME', 'Kundengruppe:');
define('TEXT_INFO_CUSTOMERS_STATUS_IMAGE', 'Kundengruppe Bild:');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE', 'Rabatt (0 bis 100%):');
define('TEXT_INFO_INSERT_INTRO', 'Bitte erstellen Sie eine neue Kundengruppe mit den gew&uuml;nschten Einstellungen');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie diese Kundengruppe l&ouml;schen wollen?');
define('TEXT_INFO_HEADING_NEW_CUSTOMERS_STATUS', 'Neue Kundengruppe');
define('TEXT_INFO_HEADING_EDIT_CUSTOMERS_STATUS', 'Kundengruppe &auml;ndern');
define('TEXT_INFO_HEADING_DELETE_CUSTOMERS_STATUS', 'Kundengruppe l&ouml;schen');

define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO', 'Sie k&ouml;nnen einen Kundenrabatt einstellen. Dieser Rabatt wird auf die Gesamtrechnung gegeben und wirkt sich nicht auf angezeigte Preise aus.');
define('ENTRY_OT_XMEMBER', 'Kundenrabatt:');

define('TEXT_INFO_CUSTOMERS_STATUS_MINIMUM_AMOUNT_OT_XMEMBER_INTRO', 'Sie k&ouml;nnen einen Mindest-Bestellwert f&uuml;r diesen Kundenrabatt festlegen');
define('ENTRY_MINIMUM_AMOUNT_OT_XMEMBER', 'Mindest-Bestellwert');


define('TEXT_INFO_CUSTOMERS_STATUS_STAFFELPREIS_INTRO', 'Sie k&ouml;nnen Kunden dieser Gruppe erlauben zu Staffelpreisen einzukaufen. Ein gesetzter Kundenrabatt erg&auml;nzt sich dazu.');
define('ENTRY_STAFFELPREIS', 'Staffelpreis:');

define('TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO', 'Sie k&ouml;nnen die Informationen der Gruppe f&uuml;r den Kunden im Shop ver�fentlichen.  ');
define('ENTRY_CUSTOMERS_STATUS_PUBLIC', 'Ver&ouml;ffentlichen : ');

define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO', 'Sie k&ouml;nnen die Preise anzeigen oder vor dem Besucher verstecken. Sollen die Preise nicht angezeigt werden, ist eine Bestellung von Kunden dieser Gruppe nicht m&ouml;glich. ');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE', 'Preise anzeigen: ');

define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO', 'Preisinformationen f&uuml;r Kunden dieser Gruppe sind incl. oder excl. MwSt.. ');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX', 'Preise : ');

define('TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_INTRO', 'Legen Sie die Zahlungsarten dieser Kundengruppe fest.');
define('ENTRY_CUSTOMERS_STATUS_PAYMENT', 'Zahlungsweise : ');

define('ENTRY_YES','ja');
define('ENTRY_NO','nein');

define('ENTRY_TAX_YES', 'incl. MwSt.');
define('ENTRY_TAX_NO','excl. MwSt.');

define('ERROR_REMOVE_DEFAULT_CUSTOMER_STATUS', 'Fehler: Die Standardgruppe darf nicht gel&ouml;scht werden. Bitte definieren Sie eine neue Standardgruppe und wiederholen Sie den Vorgang.');
define('ERROR_STATUS_USED_IN_CUSTOMERS', 'Fehler: Diese Kundengruppe wird zur Zeit bei Kunden verwendet.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Fehler: Diese Kundengruppe wird zur Zeit in der Bestell&uuml;bersicht verwendet.');


