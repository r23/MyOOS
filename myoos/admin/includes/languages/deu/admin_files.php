<?php
/**
   ----------------------------------------------------------------------
   $Id: admin_files.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_categories.php,v 1.13 2002/08/19 01:45:58 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Redaktions "Bereich" Men');

define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_BOXES', 'Bereiche');
define('TABLE_HEADING_FILENAME', 'Dateinamen');
define('TABLE_HEADING_GROUPS', 'Gruppen');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_COUNT_BOXES', 'Bereiche: ');
define('TEXT_COUNT_FILES', 'Datei(en): ');

//categories access
define('TEXT_INFO_HEADING_DEFAULT_BOXES', 'Bereiche: ');

define('TEXT_INFO_DEFAULT_BOXES_INTRO', 'Einfach nur den grünen Button drücken um den Bereich zu aktivieren oder den roten Button um den Bereich zu deinstallieren.<br><br><b>WARNUNG:</b> Wenn Sie den Bereich deinstallieren, werden sämtliche darin befindliche Dateien mitgelöscht!');
define('TEXT_INFO_DEFAULT_BOXES_INSTALLED', ' installiert');
define('TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED', ' nicht installiert');

define('STATUS_BOX_INSTALLED', 'Installiert');
define('STATUS_BOX_NOT_INSTALLED', 'Nicht installiert');
define('STATUS_BOX_REMOVE', 'Entfernen');
define('STATUS_BOX_INSTALL', 'Installieren');

//files access
define('TEXT_INFO_HEADING_DEFAULT_FILE', 'Datei: ');
define('TEXT_INFO_HEADING_DELETE_FILE', 'Entferne Erlaubnis');
define('TEXT_INFO_HEADING_NEW_FILE', 'Store Files');

define('TEXT_INFO_DEFAULT_FILE_INTRO', 'Klicken Sie den <b>Dateien ablegen</b> Button um die neue Datei in dem Bereich abzulegen: ');
define('TEXT_INFO_DELETE_FILE_INTRO', 'Entferne <font color="red"><b>%s</b></font> von <b>%s</b> Bereich? ');
define('TEXT_INFO_NEW_FILE_INTRO', 'Überprüfen Sie im Menü auf der linken Seite, ob Sie die richtigen Dateien entfernt haben.');

define('TEXT_INFO_NEW_FILE_BOX', 'Momentaner Bereich: ');
