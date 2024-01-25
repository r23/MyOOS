<?php
/**
   ----------------------------------------------------------------------
   $Id: newsletters.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: newsletters.php,v 1.7 2002/03/11 14:13:05 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Rundschreiben Verwaltung');

define('TABLE_HEADING_NEWSLETTERS', 'Rundschreiben');
define('TABLE_HEADING_SIZE', 'Grösse');
define('TABLE_HEADING_MODULE', 'Module');
define('TABLE_HEADING_SENT', 'Gesendet');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_NEWSLETTER_MODULE', 'Module:');
define('TEXT_NEWSLETTER_TITLE', 'Titel des Rundschreibens:');
define('TEXT_NEWSLETTER_CONTENT', 'Inhalt:');

define('TEXT_NEWSLETTER_DATE_ADDED', 'hinzugefügt am:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Datum gesendet:');

define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie dieses Rundschreiben löschen möchten?');

define('TEXT_PLEASE_WAIT', 'Bitte warten Sie .. eMails werden gesendet ..<br><br>Bitte unterbrechen Sie diesen Prozess nicht!');
define('TEXT_FINISHED_SENDING_EMAILS', 'eMails wurden versendet!');

define('ERROR_NEWSLETTER_TITLE', 'Fehler: Ein Titel für das Rundschreiben ist erforderlich.');
define('ERROR_NEWSLETTER_MODULE', 'Fehler: Das Newsletter Modul wird benötigt.');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Fehler: Bitte sperren Sie das Rundschreiben bevor Sie es löschen.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Fehler: Bitte sperren Sie das Rundschreiben bevor Sie es bearbeiten.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Fehler: Bitte sperren Sie das Rundschreiben bevor Sie es versenden.');
