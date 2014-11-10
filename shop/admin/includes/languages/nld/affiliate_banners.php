<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_banners.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_banners.php,v 1.3 2003/02/16 23:44:24 harley_vb  
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Partnerprogramma bannerbeheer');

define('TABLE_HEADING_BANNERS', 'Banner');
define('TABLE_HEADING_GROUPS', 'Groep');
define('TABLE_HEADING_ACTION', 'Aktie');
define('TABLE_HEADING_STATISTICS', 'Aangeven / Kliks');
define('TABLE_HEADING_PRODUCT_ID', 'Product ID');

define('TEXT_BANNERS_TITLE', 'Titel van de  banner:');
define('TEXT_BANNERS_GROUP', 'Bannergroep:');
define('TEXT_BANNERS_NEW_GROUP', ', of voer hieronder een nieuwe bannergroep in');
define('TEXT_BANNERS_IMAGE', 'Afbeelding (Bestand):');
define('TEXT_BANNERS_IMAGE_LOCAL', ', of voer hieronder het loale bestand inoder geben Sie unten die lokale Datei auf Ihrem Server an');
define('TEXT_BANNERS_IMAGE_TARGET', 'Afbeeldingdoel (Opslaan naar):');
define('TEXT_BANNERS_HTML_TEXT', 'HTML Tekst:');
define('TEXT_AFFILIATE_BANNERS_NOTE', '<b>Banner opmerking:</b><ul><li>U kan een Afbeelding of HTML-Text als banner gebruiken, beiden tegelijk kan niet.</li><li>Wanneer u beide bannersoorsten gelijktijdig gebruikt, wordt alleen de HTML-Tekst banner getoond.</li></ul>');

define('TEXT_BANNERS_LINKED_PRODUCT','Product ID');
define('TEXT_BANNERS_LINKED_PRODUCT_NOTE','Wanneer u de banner met een product linken wil, geef dan hier de Product ID ein. Wanneer u met de startpagina linken wil geef dan "0" in.');

define('TEXT_BANNERS_DATE_ADDED', 'toegevoegd op:');
define('TEXT_BANNERS_STATUS_CHANGE', 'Status veranderd op: %s');

define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze banner verwijderen wilt?');
define('TEXT_INFO_DELETE_IMAGE', 'Bannerafbeelding verwijderen');

define('SUCCESS_BANNER_INSERTED', 'Succes: De banner werd ingevoegd.');
define('SUCCESS_BANNER_UPDATED', 'Succes: De banner werd geactualiseerd.');
define('SUCCESS_BANNER_REMOVED', 'Succes: De banner werd verwijderd.');

define('ERROR_BANNER_TITLE_REQUIRED', 'Fout: Een bannertitel is nodig.');
define('ERROR_BANNER_GROUP_REQUIRED', 'Fout: Een bannergroep is nodig.');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fout: Doel bestaan niet %s.');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fout: Doel is niet beschrijfbaar: %s');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Fout: Afbeelding bestaat niet.');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Fout: Afbeelding kan niet verwijderd worden.');
?>
