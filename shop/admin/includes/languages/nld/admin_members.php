<?php
/* ----------------------------------------------------------------------
   $Id: admin_members.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_members.php,v 1.13 2002/08/19 01:45:58 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if ($_GET['gID']) {
  define('HEADING_TITLE', 'Beheerdergroepen');
} elseif ($_GET['gPath']) {
  define('HEADING_TITLE', 'Groepen aanmaken');
} else {
  define('HEADING_TITLE', 'Beheerderleden');
}

define('TEXT_COUNT_GROUPS', 'Groepen: ');

define('TABLE_HEADING_NAME', 'Naam');
define('TABLE_HEADING_EMAIL', 'Emailadres');
define('TABLE_HEADING_PASSWORD', 'Wachtwoord');
define('TABLE_HEADING_CONFIRM', 'Wachtwoord bevestigen');
define('TABLE_HEADING_GROUPS', 'Groepsniveau');
define('TABLE_HEADING_CREATED', 'Rekening aangemaakt');
define('TABLE_HEADING_MODIFIED', 'Rekening aangepast');
define('TABLE_HEADING_LOGDATE', 'Laatste toegang');
define('TABLE_HEADING_LOGNUM', 'Aanmeldingsnummer');
define('TABLE_HEADING_LOG_NUM', 'Aanmeldingsnummer');
define('TABLE_HEADING_ACTION', 'Actie');

define('TABLE_HEADING_GROUPS_NAME', 'Groepsnaam');
define('TABLE_HEADING_GROUPS_DEFINE', 'Veld - en bestandsselectie');
define('TABLE_HEADING_GROUPS_GROUP', 'Niveau');
define('TABLE_HEADING_GROUPS_CATEGORIES', 'Categorietoestemming');


define('TEXT_INFO_HEADING_DEFAULT', 'Beheerderlid ');
define('TEXT_INFO_HEADING_DELETE', ' Toestemming voor wissen');
define('TEXT_INFO_HEADING_EDIT', 'Aanpassen categorie / ');
define('TEXT_INFO_HEADING_NEW', 'Nieuw beheerderlid ');

define('TEXT_INFO_DEFAULT_INTRO', 'Medewerkersgroep');
define('TEXT_INFO_DELETE_INTRO', 'Verwijder <nobr><b>%s</b></nobr> als <nobr>adminleden?</nobr>');
define('TEXT_INFO_DELETE_INTRO_NOT', 'U kan <nobr>%s groep niet wissen !</nobr>');
define('TEXT_INFO_EDIT_INTRO', 'Stel hier het toegangsniveau in: ');

define('TEXT_INFO_FULLNAME', 'Naam: ');
define('TEXT_INFO_FIRSTNAME', 'Voornaam: ');
define('TEXT_INFO_LASTNAME', 'Achternaam: ');
define('TEXT_INFO_EMAIL', 'Emailadres: ');
define('TEXT_INFO_PASSWORD', 'Wachtwoord: ');
define('TEXT_INFO_CONFIRM', 'Wachtwoord bevestigen: ');
define('TEXT_INFO_CREATED', 'Rekening aangemaakt: ');
define('TEXT_INFO_MODIFIED', 'Rekening veanderd: ');
define('TEXT_INFO_LOGDATE', 'Laatste toegang: ');
define('TEXT_INFO_LOGNUM', 'Toegangs-Nummer: ');
define('TEXT_INFO_GROUP', 'Groepsniveau: ');
define('TEXT_INFO_ERROR', '<font color="red">Emailadres wordt al gebruikt! Probeer het nogmaals.</font>');

define('JS_ALERT_FIRSTNAME', '- Verplicht: Voornaam \n');
define('JS_ALERT_LASTNAME', '- Verplicht: Achternaam \n');
define('JS_ALERT_EMAIL', '- Verplicht: Emailadres \n');
define('JS_ALERT_EMAIL_FORMAT', '- Emailadresformaat is niet geldig! \n');
define('JS_ALERT_EMAIL_USED', '- Emailadres wordt al gebruikt! \n');
define('JS_ALERT_LEVEL', '- Verplicht: Groepslid \n');

define('ADMIN_EMAIL_SUBJECT', 'Nieuw beheerderlid');
define('ADMIN_EMAIL_TEXT', 'Hallo %s,' . "\n\n" . 'U kan  op de beheerderveld met volgend wachtwoord ' . "\n" . 'toegang krijgen. als u eenmaal op het beheerderveld bent geweest,' . "\n" . ' verander dan a.u.b. uw wachtwoord!' . "\n\n" . 'Website : %s' . "\n" . 'Gebruikersnaam: %s' . "\n" . 'Wachtwoord: %s' . "\n\n" . 'Dank u!' . "\n" . '%s' . "\n\n" . 'Dit is een geautomatiseerde Email. A.u.b. niet beantwoorden.'); 

define('TEXT_INFO_HEADING_DEFAULT_GROUPS', 'Beheerdergroep ');
define('TEXT_INFO_HEADING_DELETE_GROUPS', 'Verwijder groep ');

define('TEXT_INFO_DEFAULT_GROUPS_INTRO', '<b>ATTENTIE:</b><li><b>bewerken:</b> bewerken groepsnaam.</li><li><b>Verwijderen:</b> Groep verwijderen.</li><li><b>Defini&euml;ren:</b> Defini&euml;r de groepsrechten.</li>');
define('TEXT_INFO_DELETE_GROUPS_INTRO', 'Er worden alle leden van deze groep verwijderd. Weet u zeker dat u deze <nobr><b>%s</b> groep wilt verwijderen?</nobr>');
define('TEXT_INFO_DELETE_GROUPS_INTRO_NOT', 'U kan deze groep niet verwijderen!');
define('TEXT_INFO_GROUPS_INTRO', 'Voer eenmalig een groepsnaam in. Klik verder voor bevestigen.');

define('TEXT_INFO_HEADING_GROUPS', 'Nieuwe groep');
define('TEXT_INFO_HEADING_EDIT_GROUP', 'Groepsnaam veranderen');
define('TEXT_INFO_EDIT_GROUP_INTRO', 'Voer de nieuwe groepsnaam in.');
define('TEXT_INFO_GROUPS_NAME', ' <b>Groepsnaam:</b><br />Voer eenmalig de groepsnaam in. Klik dan volgende voor bevestigen.<br />');
define('TEXT_INFO_GROUPS_NAME_FALSE', '<font color="red"><b>FOUT:</b> De groepsnaam moet uit minstens 5 karakters bestaan!</font>');
define('TEXT_INFO_GROUPS_NAME_USED', '<font color="red"><b>FOUT:</b> Groepsnaam wordt al gebruikt!</font>');
define('TEXT_INFO_GROUPS_LEVEL', 'Groepsnivo: ');
define('TEXT_INFO_GROUPS_BOXES', '<b>Niveautoegang:</b><br />toegang naar het gekozen niveau is bezet.');
define('TEXT_INFO_GROUPS_BOXES_INCLUDE', 'Houdt opgeslagen gegevens in: ');

define('TEXT_INFO_HEADING_DEFINE', 'Defini&euml;er groep');
if ($_GET['gPath'] == 1) {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br />U kan geen niveautoestemming voor deze groep uitgeven.<br /><br />');
} else {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br />Verander de niveautoestemming voor deze groep en daain bevindende bestanden, indien u de niveaus selecteerd. Klik <b>Opslaan</b> om de verandering te bevestiging.<br /><br />');
}
?>
