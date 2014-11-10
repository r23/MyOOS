<?php
/* ----------------------------------------------------------------------
   $Id: customers_status.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
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

define('HEADING_TITLE', 'Klantengroepen');

define('TABLE_HEADING_CUSTOMERS_STATUS', 'Klantengroep');
define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_CUSTOMERS_QTY_DISCOUNTS', 'Staffelprijs');
define('TABLE_HEADING_AMOUNT', 'Bestelwaarde');

define('TEXT_INFO_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke instelling in');
define('TEXT_INFO_CUSTOMERS_STATUS_NAME', 'Klantengroep:');
define('TEXT_INFO_CUSTOMERS_STATUS_IMAGE', 'Klantengroep plaatje:');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE_INTRO', 'Geef een korting aan tussen 0 und 100%, die voor elk produkt gebruikt wordt.');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE', 'Korting (0 bis 100%):');
define('TEXT_INFO_INSERT_INTRO', 'Maak een nieuwe klantengroep aan met de gewenste instellingen');
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze klantengroep wissen wilt?');
define('TEXT_INFO_HEADING_NEW_CUSTOMERS_STATUS', 'Nieuwe klantengroep');
define('TEXT_INFO_HEADING_EDIT_CUSTOMERS_STATUS', 'Klantengroep varanderen');
define('TEXT_INFO_HEADING_DELETE_CUSTOMERS_STATUS', 'Klantengroep wissen');

define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO', 'U kan een klantenkorting instellen. Deze korting wordt op het totaalbedrag gegeven en heeft geen invloed op de getoonde prijzen.');
define('ENTRY_OT_XMEMBER', 'Klantenkorting:');

define('TEXT_INFO_CUSTOMERS_STATUS_MINIMUM_AMOUNT_OT_XMEMBER_INTRO', 'U kan een minimaal bestelbedrag voor ddeze klantenkorting vastleggent');
define('ENTRY_MINIMUM_AMOUNT_OT_XMEMBER', 'Minimale bestelbedrag');


define('TEXT_INFO_CUSTOMERS_STATUS_STAFFELPREIS_INTRO', 'U kan klanten van deze groep toestaan tegen staffelprijzen te kopen. Het totale bestelbedrag komt daar voor in aanmerking.');
define('ENTRY_STAFFELPREIS', 'Staffelprijs:');

define('TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO', 'U kan de informatie van de groep voor de klant in de winkel publiceren.  ');
define('ENTRY_CUSTOMERS_STATUS_PUBLIC', 'Publiceren : ');

define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO', 'U kan de prijzen tonen of voor de bezoeker verbergen. Als de prijzen niet getoond moeten worden, is een  bestelling van klanten uit deze groep niet mogelijk. ');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE', 'Prijzen tonen: ');

define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO', 'Prijsinformatie voor klanten uit deze groep zijn  incl. of excl. B.T.W. ... ');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX', 'Prijzen : ');

define('TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_INTRO', 'Bepaal de betalingsmethoden voor deze klantengroep.');
define('ENTRY_CUSTOMERS_STATUS_PAYMENT', 'Betalingsmethode : ');

define('ENTRY_YES','ja');
define('ENTRY_NO','nee');

define('ENTRY_TAX_YES', 'incl. B.T.W.');
define('ENTRY_TAX_NO','excl. B.T.W.');

define('ERROR_REMOVE_DEFAULT_CUSTOMER_STATUS', 'Fout: De standaardgroep mag niet gewist worden. Definieer een nieuwe standaardgroep en herhaal het proces.');
define('ERROR_STATUS_USED_IN_CUSTOMERS', 'Fout: Deze klantegroep wordt momenteel bij klanten gebruikt.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Fout: Deze klantengroep wordt momenteel in het besteloverzicht gebruikt.');

?>
