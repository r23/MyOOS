<?php
/* ----------------------------------------------------------------------
   $Id: file_manager.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: file_manager.php,v 1.17 2003/02/16 02:09:20 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Bestandsbeheerder');

define('TABLE_HEADING_FILENAME', 'Naam');
define('TABLE_HEADING_SIZE', 'Grootte');
define('TABLE_HEADING_PERMISSIONS', 'Toegangsrechten');
define('TABLE_HEADING_USER', 'Gebruiker');
define('TABLE_HEADING_GROUP', 'Groep');
define('TABLE_HEADING_LAST_MODIFIED', 'laatste verandering');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_INFO_HEADING_UPLOAD', 'Upload');
define('TEXT_FILE_NAME', 'Bestandsnaam:');
define('TEXT_FILE_SIZE', 'Grootte:');
define('TEXT_FILE_CONTENTS', 'Inhoud:');
define('TEXT_LAST_MODIFIED', 'laatste verandering:');
define('TEXT_NEW_FOLDER', 'Nieuwe map');
define('TEXT_NEW_FOLDER_INTRO', 'Voer de naam van de nieuwe map in:');
define('TEXT_DELETE_INTRO', 'Weet u zeker dat u dit bestand verwijderen wilt?');
define('TEXT_UPLOAD_INTRO', 'Zoek a.u.b. het betand selecteren, die geupload moet worden.');

define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Fout: De map is schrijfbeschermd. Corrigeer a.u.b. de toegangrechten voor: %s !');
define('ERROR_FILE_NOT_WRITEABLE', 'Fout: Het bestand is schrijfbeschermd. Corrigeer a.u.b. de toegangrechten voor: %s !');
define('ERROR_DIRECTORY_NOT_REMOVEABLE', 'Fout: De map kan niet gewist worden. Corrigeer a.u.b. de toegangrechten voor: %s !');
define('ERROR_FILE_NOT_REMOVEABLE', 'Fehler: Het bestand kan niet gewist worden. Corrigeer a.u.b. de toegangrechten voor: %s !');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Fehler: De map %s bestaat niet!');
?>
