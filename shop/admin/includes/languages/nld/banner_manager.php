<?php
/* ----------------------------------------------------------------------
   $Id: banner_manager.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banner_manager.php,v 1.25 2003/02/16 02:09:20 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Banner beheer');

define('TABLE_HEADING_BANNERS', 'Banner');
define('TABLE_HEADING_GROUPS', 'Groep');
define('TABLE_HEADING_STATISTICS', 'Tonen / Kliks');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktie');

define('TEXT_BANNERS_TITLE', 'Titel van de banner:'); 
define('TEXT_BANNERS_URL', 'Banner-URL:'); 
define('TEXT_BANNERS_GROUP', 'Bannergroep:'); 
define('TEXT_BANNERS_NEW_GROUP', ', of geef hieronder een nieuwe bannergroep in'); 
define('TEXT_BANNERS_IMAGE', 'Afbeelding (Bestand):'); 
define('TEXT_BANNERS_IMAGE_LOCAL', ', of geef hier onder het lokale bestand op, op uw server'); 
define('TEXT_BANNERS_IMAGE_TARGET', 'Afbeeldingsmap (Opslaan in):'); 
define('TEXT_BANNERS_HTML_TEXT', 'HTML Tekst:');
define('TEXT_BANNERS_EXPIRES_ON', 'Geldig tot:');
define('TEXT_BANNERS_OR_AT', ', of op');
define('TEXT_BANNERS_IMPRESSIONS', 'Impressies/Tonen.');
define('TEXT_BANNERS_SCHEDULED_AT', 'Geldigheid vanaf:');
define('TEXT_BANNERS_BANNER_NOTE', '<b>Banner opmerking:</b><ul><li>U kan Beeld - of HTML-Tekstbanners gebruiken, beiden gelijktijdig is niet mogelijk.</li><li>Wanneer u beide bannersoorten gelijktijdig wilt gebruiken, wodt alleen de HTML-Tekstbanner getoont.</li></ul>');
define('TEXT_BANNERS_INSERT_NOTE', '<b>Opmerking:</b><ul><li>Voor de afbeeldingsmap moeten schrijfrechten bestaan!</li><li>Vul het veld \'Afbeeldingmap (Opslaan in)\' niet in, als u geen afbeeldingen op uw server copi&euml;eren wilt (b.v. als de afbeeldingen zich al op de server bevinden).</li><li>Het \'Afbeeldingsmap (Opslaan in)\' veld moet een reeds bestaande map met \'/\' aan het einde zijn (b.v. banners/).</li></ul>'); 
define('TEXT_BANNERS_EXPIRCY_NOTE', '<b>Geldigheid tot opmerking:</b><ul><li>Slechts &euml;&euml;n veld invullen!</li><li>Wanneer de banner onbeperk getoond moet worden, vul dan deze velden niet in.</li></ul>');
define('TEXT_BANNERS_SCHEDULE_NOTE', '<b>Geldigheid vanaf opmerking:</b><ul><li>Bij het gebruik van deze functie, wordt de banner pas vanaf de opgegeven datum getoond.</li><li>Alle banners met deze functie worden tot hun activering, als inactief getoond.</li></ul>');

define('TEXT_BANNERS_DATE_ADDED', 'toevoegen op:');
define('TEXT_BANNERS_SCHEDULED_AT_DATE', 'Geldigheid vanaf: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_DATE', 'Geldigheid tot en met: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_IMPRESSIONS', 'Geldigheid tot: <b>%s</b> impressiesn/tonen');
define('TEXT_BANNERS_STATUS_CHANGE', 'Status veranderd: %s');

define('TEXT_BANNERS_DATA', 'D<br />A<br />T<br />E<br />N');
define('TEXT_BANNERS_LAST_3_DAYS', 'laatste 3 dagen');
define('TEXT_BANNERS_BANNER_VIEWS', 'Bannervertonen');
define('TEXT_BANNERS_BANNER_CLICKS', 'Bannerkliks');

define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker, dat u deze banner verwijderen wilt?');
define('TEXT_INFO_DELETE_IMAGE', 'Bannerafbeelding verwijderen');

define('SUCCESS_BANNER_INSERTED', 'Succes: De banner werd igevoerd.');
define('SUCCESS_BANNER_UPDATED', 'Succes: De banner werd geactualiseerd.');
define('SUCCESS_BANNER_REMOVED', 'Succes: De banner werd verwijderd.');
define('SUCCESS_BANNER_STATUS_UPDATED', 'Succes: De status van de banners werd geactualiseerd.');

define('ERROR_BANNER_TITLE_REQUIRED', 'Fout: Een bannertitel is nodig.');
define('ERROR_BANNER_GROUP_REQUIRED', 'Fout: Een bannergroep is nodig.');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fout: De doelmap %s bestaat niet.');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fout: De doelmap %s is niet beschrijfbaar.');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Fout: Afbeelding bestaat niet.');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Fout: Afbeelding kan niet gewist worden.');
define('ERROR_UNKNOWN_STATUS_FLAG', 'Fout: Onbekende Status Flag.');

define('ERROR_GRAPHS_DIRECTORY_DOES_NOT_EXIST', 'Fout: De map \'graphs\' is niet aanwezig! Maak de map \'graphs\' aan in de map \'images\'.');
define('ERROR_GRAPHS_DIRECTORY_NOT_WRITEABLE', 'Fout: De map \'graphs\' is schrijfbeschermd!');
?>
