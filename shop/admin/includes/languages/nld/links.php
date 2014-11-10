<?php
/* ----------------------------------------------------------------------
   $Id: links.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links.php,v 1.00 2003/10/02 
   ----------------------------------------------------------------------
   Links Manager
   
   Contribution based on:
   
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Links');
define('HEADING_TITLE_SEARCH', 'Zoeken:');

define('TABLE_HEADING_TITLE', 'Titel');
define('TABLE_HEADING_URL', 'URL');
define('TABLE_HEADING_CLICKS', 'Kliks');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_INFO_HEADING_DELETE_LINK', 'Verwijder link');
define('TEXT_INFO_HEADING_CHECK_LINK', 'Controleer link');

define('TEXT_DELETE_INTRO', 'Weet u zeker dat u deze link wilt verwijderen?');

define('TEXT_INFO_LINK_CHECK_RESULT', 'Resultaat linkcontrole:');
define('TEXT_INFO_LINK_CHECK_FOUND', 'Gevonden');
define('TEXT_INFO_LINK_CHECK_NOT_FOUND', 'Niet gevonden');
define('TEXT_INFO_LINK_CHECK_ERROR', 'Fout bij lezen URL');


define('TEXT_INFO_LINK_STATUS', 'Status:');
define('TEXT_INFO_LINK_CATEGORY', 'Categorie:');
define('TEXT_INFO_LINK_CONTACT_NAME', 'Contactpersoon naam:');
define('TEXT_INFO_LINK_CONTACT_EMAIL', 'Contactpersoon email:');
define('TEXT_INFO_LINK_CLICK_COUNT', 'Kliks:');
define('TEXT_INFO_LINK_DESCRIPTION', 'Beschrijving:');
define('TEXT_DATE_LINK_CREATED', 'Link ingebracht:');
define('TEXT_DATE_LINK_LAST_MODIFIED', 'Laatste veerandering:');
define('TEXT_IMAGE_NONEXISTENT', 'GEEN AFBEELDING');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Linkstatus Update');
define('EMAIL_TEXT_STATUS_UPDATE', 'Beste %s,' . "\n\n" . 'De status van uw link op ' . STORE_NAME . ' is geupdated.' . "\n\n" . 'Nieuwe status: %s' . "\n\n" . 'Beantwoord deze email als u vragen hebt.' . "\n");

// VJ todo - move to common language file
define('CATEGORY_WEBSITE', 'Website details');
define('CATEGORY_RECIPROCAL', 'Wederzijdse website details');
define('CATEGORY_OPTIONS', 'Opties');

define('ENTRY_LINKS_TITLE', 'Site titel:');
define('ENTRY_LINKS_TITLE_ERROR', 'De linktitel moet minstens ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' karakters bevatten.');
define('ENTRY_LINKS_URL', 'URL:');
define('ENTRY_LINKS_URL_ERROR', 'De URL moet minstens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' karakters bevatten.');
define('ENTRY_LINKS_CATEGORY', 'Categorie:');
define('ENTRY_LINKS_DESCRIPTION', 'Beschrijving:');
define('ENTRY_LINKS_DESCRIPTION_ERROR', 'De beschrijving moet minstens ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' karakters bevatten.');
define('ENTRY_LINKS_IMAGE', 'Afbeelding URL:');
define('ENTRY_LINKS_CONTACT_NAME', 'Volledige naam:');
define('ENTRY_LINKS_CONTACT_NAME_ERROR', 'Uw volledige naam moet minstens ' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' karakters bevatten.');
define('ENTRY_LINKS_RECIPROCAL_URL', 'Wederzijdse website:');
define('ENTRY_LINKS_RECIPROCAL_URL_ERROR', 'Wederzijdse website moet minstens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' karakters bevatten.');
define('ENTRY_LINKS_STATUS', 'Status:');
define('ENTRY_LINKS_NOTIFY_CONTACT', 'Bericht contactpersoon:');
define('ENTRY_LINKS_RATING', 'Waardering:');
define('ENTRY_LINKS_RATING_ERROR', 'Waardering moet niet leeg zijn.');

define('TEXT_DISPLAY_NUMBER_OF_LINKS', 'Toon <b>%d</b> tot <b>%d</b> (van <b>%d</b> links)');

define('IMAGE_NEW_LINK', 'Nieuwe link');
define('IMAGE_CHECK_LINK', 'Controleer link');
?>
