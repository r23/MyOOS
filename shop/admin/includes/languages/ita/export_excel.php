<?php
/* ----------------------------------------------------------------------
   $Id: export_excel.php,v 1.3 2007/06/13 16:39:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: backup.php,v 1.16 2002/03/16 21:30:02 hpdl
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

define('HEADING_TITLE', 'Database Backup Manager');

define('TABLE_HEADING_TITLE', 'Titolo');
define('TABLE_HEADING_FILE_DATE', 'Data');
define('TABLE_HEADING_FILE_SIZE', 'Dimensione');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'Nuovo Salvataggio');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Ripristina Locale');
define('TEXT_INFO_NEW_BACKUP', 'Non interrompere il processo di salvataggio, potrebbe durare diversi minuti.');
define('TEXT_INFO_UNPACK', '<br><br>(dopo aver scompattato il file dall\' archivio)');
define('TEXT_INFO_DATE', 'Data:');
define('TEXT_INFO_SIZE', 'Dimensione:');
define('TEXT_INFO_COMPRESSION', 'Compressione:');
define('TEXT_INFO_USE_GZIP', 'Usa GZIP');
define('TEXT_INFO_USE_ZIP', 'Usa ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'Non Compresso (solo SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Solo Download (non depositare lato server)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Meglio tramite connessione HTTPS');
define('TEXT_DELETE_INTRO', 'Sicuro di voler cancellare questo salvataggio?');
define('TEXT_NO_EXTENSION', 'Nessuna');
define('TEXT_EXPORT_DIRECTORY', 'Directory di salvataggio:');
define('TEXT_FORGET', '(<u>dimenticare</u>)');

define('ERROR_EXPORT_DIRECTORY_DOES_NOT_EXIST', 'Errore: La Directory di salvataggio non esiste. Imposta i parametri in configure.php.');
define('ERROR_EXPORT_DIRECTORY_NOT_WRITEABLE', 'Errore: La Directory di salvataggio non è scrivibile.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Errore: Il Link del Download non è accettabile.');

define('SUCCESS_DATABASE_SAVED', 'Operazione riuscita: Il Database è stato salvato.');
define('SUCCESS_DATABASE_RESTORED', 'Operazione riuscita: Il Database è stato ripristinato.');
define('SUCCESS_EXPORT_DELETED', 'Operazione riuscita: Il File di salvataggio è stato rimosso.');

?>
