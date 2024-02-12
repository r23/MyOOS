<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_mail.php,v 1.1.2.1 2003/05/15 23:10:55 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Reaktivierung von Warenkorbabbrüchen');

define('TABLE_HEADING_TITLE', 'Titel');
define('TABLE_HEADING_FILE_DATE', 'Datum');
define('TABLE_HEADING_FILE_SIZE', 'Größe');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_INFO_HEADING_NEW_EXPORT', 'Neuer Produktdaten Export');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Lokal wiederherstellen');
define('TEXT_INFO_NEW_EXPORT', 'Bitte den Exportprozess AUF KEINEN FALL unterbrechen. Dieser kann einige Minuten in Anspruch nehmen.');
define('TEXT_INFO_UNPACK', '<br><br>(nach dem die Dateien aus dem Archiv extrahiert wurden)');
define('TEXT_INFO_DATE', 'Datum:');
define('TEXT_INFO_SIZE', 'Größe:');
define('TEXT_INFO_COMPRESSION', 'Komprimieren:');
define('TEXT_INFO_USE_GZIP', 'Mit GZIP');
define('TEXT_INFO_USE_ZIP', 'Mit ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'Keine Komprimierung');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Nur herunterladen (nicht auf dem Server speichern)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Sichere HTTPS Verbindung verwenden!');
define('TEXT_NO_EXTENSION', 'Keine');
define('TEXT_EXPORT_DIRECTORY', 'Exportverzeichnis:');
define('TEXT_FORGET', '(<u> vergessen</u>)');
define('TEXT_DELETE_INTRO', 'Sind Sie sicher, dass Sie diese Exportdatei löschen möchten?');

define('ERROR_EXPORT_DIRECTORY_DOES_NOT_EXIST', '<strong>Fehler!</strong> Das Exportverzeichnis ist nicht vorhanden.');
define('ERROR_EXPORT_DIRECTORY_NOT_WRITEABLE', '<strong>Fehler!</strong> Das Exportverzeichnis ist schreibgeschützt.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', '<strong>Fehler!</strong> Download Link nicht akzeptabel.');

define('SUCCESS_DATABASE_SAVED', '<strong>Erfolg!</strong> Die Produktdaten wurden exportiert.');
define('SUCCESS_EXPORT_DELETED', '<strong>Erfolg!</strong> Die Exportdatei wurde gelöscht.');