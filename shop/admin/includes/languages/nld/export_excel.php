<?php
/* ----------------------------------------------------------------------
   $Id: export_excel.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: backup.php,v 1.21 2002/06/15 11:02:56 harley_vb 
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

define('HEADING_TITLE', 'Backup gegevensbestand'); 

define('TABLE_HEADING_TITLE', 'Titel');
define('TABLE_HEADING_FILE_DATE', 'Datum');
define('TABLE_HEADING_FILE_SIZE', 'Grootte');
define('TABLE_HEADING_ACTION', 'Aktie');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'Nieuwe backup');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Lokaal terugzetten');
define('TEXT_INFO_NEW_BACKUP', 'A.u.b. het backupproces in GEEN GEVAL onderbreken. Dit kan enige minuten duren.');
define('TEXT_INFO_UNPACK', '<br /><br />(nadat de bestanden uit het archief is uitgepakt)');
define('TEXT_INFO_DATE', 'Datum:');
define('TEXT_INFO_SIZE', 'Grootte:');
define('TEXT_INFO_COMPRESSION', 'Comprimeren:');
define('TEXT_INFO_USE_GZIP', 'Met GZIP');
define('TEXT_INFO_USE_ZIP', 'Met ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'Geen compressie (Raw SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Alleen downloaden (niet op de Server opslaan)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Veilige HTTPS verbinding gebruiken!');
define('TEXT_NO_EXTENSION', 'Geen');
define('TEXT_EXPORT_DIRECTORY', 'Backup map:');
define('TEXT_FORGET', '(<u> vergeten</u>)');
define('TEXT_DELETE_INTRO', 'Weet u zeker, dat u deze backup verwijderen wilt?');

define('ERROR_EXPORT_DIRECTORY_DOES_NOT_EXIST', 'Fout: De backup map bestaat niet.');
define('ERROR_EXPORT_DIRECTORY_NOT_WRITEABLE', 'Fout: De backup map is schrijfbeschermd.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Fout: Download link niet acceptabel.');

define('SUCCESS_DATABASE_SAVED', 'Succes: De databank werd gebackupd.');
define('SUCCESS_DATABASE_RESTORED', 'Succes: De databank werd teruggezet.');
define('SUCCESS_EXPORT_DELETED', 'Succes: Het backupbestand werd gewist.');
?>
