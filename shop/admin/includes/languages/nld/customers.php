<?php
/* ----------------------------------------------------------------------
   $Id: customers.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers.php,v 1.13 2002/06/15 12:19:14 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Klant');
define('HEADING_TITLE_SEARCH', 'Zoeken:');

define('TABLE_HEADING_FIRSTNAME', 'Voornaam');
define('TABLE_HEADING_LASTNAME', 'Achternaam');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Rekening geopend op');
define('TABLE_HEADING_ACTION', 'Actie');
define('HEADING_TITLE_STATUS', 'Status:');
define('TEXT_ALL_CUSTOMERS', 'Alle klanten');
define('HEADING_TITLE_LOGIN', 'Inloggen');

define('TEXT_INFO_HEADING_STATUS_CUSTOMER', 'Klantenstatus veranderen');
define('TEXT_NO_CUSTOMER_HISTORY', 'Geene klantenstatus geschiedenis aanwezig');
define('TABLE_HEADING_NEW_VALUE', 'Nieuwe status');
define('TABLE_HEADING_OLD_VALUE', 'Oude status');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Klanten mededelen');
define('TABLE_HEADING_DATE_ADDED', 'Toegevoegd op:');

define('CATEGORY_MAX_ORDER', 'Max. bestelgedrag');
define('ENTRY_MAX_ORDER', 'Klantencrediet:');

define('ENTRY_VAT_ID_STATUS', 'Vat ID check');
define('ENTRY_VAT_ID_STATUS_YES', 'yes');
define('ENTRY_VAT_ID_STATUS_NO', 'no');

define('TEXT_DATE_ACCOUNT_CREATED', 'Rekening aangemaakt op:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'laatste verandering:');
define('TEXT_INFO_DATE_LAST_LOGON', 'laatste aanmelding:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Aantal van de aanmeldingen:');
define('TEXT_INFO_COUNTRY', 'Land:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Aantal artikelbeoordeling:');
define('TEXT_DELETE_INTRO', 'Wilt u deze klant werkelijk verwijderen?');
define('TEXT_DELETE_REVIEWS', '%s beoordeling(en) verwijderen');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Klanten verwijderen');
define('TYPE_BELOW', 'Hieronder invoeren');
define('PLEASE_SELECT', 'Selecteren');

define('EMAIL_SUBJECT', 'Welkom bij ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Geachte heer ');
define('EMAIL_GREET_MS', 'Geachte mevrouw ');
define('EMAIL_GREET_NONE', 'Geachte mijnheer,mevrouw,');
define('EMAIL_WELCOME', 'welkom bij <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'U kan nu gebruikmaken van onze <b>webwinkel</b>. Deze dienst biedt onder anderen:' . "\n\n" . '<li><b>Klantenwinkelwagen</b> - Ieder artikel blijft geregistreerd totdat u afrekent bij de kassa, of de produkten uit de winkelwagen verwijderd.' . "\n" . '<li><b>Adresboek</b> - Wij kunnen de produkten naar een - door u geselecteerd adres - versturen. De perfekte manier om een verjaardagcadeau te versturen.' . "\n" . '<li><b>Vorige bestellingen</b> - U kan op ieder moment uw vorige bestellingen bekijken.' . "\n" . '<li><b>Meningen over produkten</b> - Deel uw mening over onze produkten met andere klanten.' . "\n\n");
define('EMAIL_CONTACT', 'Indien u vragen hebt over onze klantenservice, wendt u zich tot onze bedrijfsleiding: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Attentie:</b> Dit emailadres werd ons door een klant doorgegeven. Indien u niet zich niet aangemeld hebt, stuur dan ewen email aan ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_PASSWORD_BODY', 'Uw wachtwoord is:' . "\n\n" . '   %s' . "\n\n");

define('EMAIL_GV_INCENTIVE_HEADER', 'Om u als nieuwe klant te kunnen begroeten, hebben wij u een tegoedbon van %s gestuurd.');
define('EMAIL_GV_REDEEM', 'De tegoedboncode is: %s. U deze bij het beeindigen van uw bestelling invoeren');
define('EMAIL_GV_LINK', 'Of gebruik de volgende link: ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Gefeliciteerd! Om het eerste bezoek in onze winkel attractiever te maken krijgt u deze tegoedbon!' . "\n" .
                                        'Er volgen verdere details over uw persoonlijke tegoedbon.' . "\n\n");
define('EMAIL_COUPON_REDEEM', 'Om een inkoop tegoedbon te geruiken voer a.u.b. de tegoedbon %s ' . "\n" .
                               'bij het beeindigen van op bestelling in!');
?>
