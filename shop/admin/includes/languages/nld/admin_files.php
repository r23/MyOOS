<?php
/* ----------------------------------------------------------------------
   $Id: admin_files.php,v 1.1 2007/06/13 16:39:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_categories.php,v 1.13 2002/08/19 01:45:58 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Beheerder "Veld" Menu');

define('TABLE_HEADING_ACTION', 'Aktie');
define('TABLE_HEADING_BOXES', 'Velden');
define('TABLE_HEADING_FILENAME', 'Bestandsnamen');
define('TABLE_HEADING_GROUPS', 'Groepen');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_COUNT_BOXES', 'Velden: ');
define('TEXT_COUNT_FILES', 'Bestand(en): ');

//categories access
define('TEXT_INFO_HEADING_DEFAULT_BOXES', 'Velden: ');

define('TEXT_INFO_DEFAULT_BOXES_INTRO', 'Eenvoudig de groene knop klikken om het veld te activeren of rode Knop om het veld te deinstalleren.<br /><br /><b>WAARSCHUWING:</b> Wanneer u het gebied deinstalleerd, worden alle bestanden daarin mee verwijderd!');
define('TEXT_INFO_DEFAULT_BOXES_INSTALLED', ' geinstalleerd');
define('TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED', ' niet geinstalleerd');

define('STATUS_BOX_INSTALLED', 'Geinstalleerd');
define('STATUS_BOX_NOT_INSTALLED', 'Niet geinstalleerd');
define('STATUS_BOX_REMOVE', 'Verwijderen');
define('STATUS_BOX_INSTALL', 'Installeren');

//files access
define('TEXT_INFO_HEADING_DEFAULT_FILE', 'Bestand: ');
define('TEXT_INFO_HEADING_DELETE_FILE', 'Verwijderen bevestigen');
define('TEXT_INFO_HEADING_NEW_FILE', 'Bestanden opslaan');

define('TEXT_INFO_DEFAULT_FILE_INTRO', 'Klik de <b>Bestand opslaan</b> Knop om het nieuwe bestand in het veld op te slaan: ');
define('TEXT_INFO_DELETE_FILE_INTRO', 'Verwijder <font color="red"><b>%s</b></font> uit <b>%s</b> veld? ');
define('TEXT_INFO_NEW_FILE_INTRO', 'Controleer het <font color="red"><b>linker Menu</b></font> om zeker te zijn dat u het juiste bestand verwijderd hebt.');

define('TEXT_INFO_NEW_FILE_BOX', 'Huidige veld: ');

?>
