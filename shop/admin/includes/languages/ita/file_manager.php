<?php
/* ----------------------------------------------------------------------
   $Id: file_manager.php,v 1.3 2007/06/13 16:39:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: file_manager.php,v 1.13 2002/08/19 01:45:58 hpdl
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

define('HEADING_TITLE', 'File Manager');

define('TABLE_HEADING_FILENAME', 'Nome');
define('TABLE_HEADING_SIZE', 'Dimensione');
define('TABLE_HEADING_PERMISSIONS', 'Permessi');
define('TABLE_HEADING_USER', 'Utente');
define('TABLE_HEADING_GROUP', 'Gruppo');
define('TABLE_HEADING_LAST_MODIFIED', 'Ultima modifica');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_INFO_HEADING_UPLOAD', 'Importa');
define('TEXT_FILE_NAME', 'Nome File:');
define('TEXT_FILE_SIZE', 'Dimensione:');
define('TEXT_FILE_CONTENTS', 'Contenuti:');
define('TEXT_LAST_MODIFIED', 'Ultima modifica:');
define('TEXT_NEW_FOLDER', 'Nuova Cartella');
define('TEXT_NEW_FOLDER_INTRO', 'Inserisci il nome della nuova Cartella:');
define('TEXT_DELETE_INTRO', 'Sicuro di voler cancellare questo File?');
define('TEXT_UPLOAD_INTRO', 'Seleziona il File da importare.');

define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Errore: Non posso scrivere in questa directory. Abilita i permessi di scrittura adeguati su: %s');
define('ERROR_FILE_NOT_WRITEABLE', 'Errore: Non posso scrivere in questo file. Abilita i permessi di scrittura adeguati su: %s');
define('ERROR_DIRECTORY_NOT_REMOVEABLE', 'Errore: Non posso rimuovere questa directory. Abilita i permessi di scrittura adeguati su: %s');
define('ERROR_FILE_NOT_REMOVEABLE', 'Errore: Non posso rimuovere questo file. Abilita i permessi di scrittura adeguati su: %s');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Errore:La directory è inesistente %s');
?>
