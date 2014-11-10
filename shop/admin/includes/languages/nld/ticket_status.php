<?php
/* ----------------------------------------------------------------------
   $Id: ticket_status.php,v 1.1 2007/06/14 17:11:36 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_status.php,v 1.3 2003/04/25 21:37:11 hook
   ----------------------------------------------------------------------
   OSC-SupportTicketSystem
   Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Aanvraagstatus');

define('TABLE_HEADING_TEXT_STATUS', 'Aanvraagstatus');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_DEFAULT_REPLY','Standaard klantenantwoord status');
define('TEXT_DISPLAY_NUMBER_OF_TEXT_STATUS','Toon <b>%d</b> tot <b>%d</b> (van <b>%d</b> aanvraagstatus.)');

define('TEXT_INFO_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke veranderingen in.');
define('TEXT_INFO_TEXT_STATUS_NAME', 'Aanvraagstatus:');
define('TEXT_INFO_INSERT_INTRO', 'Voer a.u.b. de nieuwe status met alle relevante gegevens in.');
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze status wissen wilt?');
define('TEXT_INFO_HEADING_NEW_TEXT_STATUS', 'Nieuwe aanvraagstatus');
define('TEXT_INFO_HEADING_EDIT_TEXT_STATUS', 'Aanvraag bewerken');
define('TEXT_INFO_HEADING_DELETE_TEXT_STATUS', 'Aanvraag wissen');

define('TEXT_SET_DEFAULT_REPLY','Nieuwe status van de aanvraag als de klant antwoord.');

define('ERROR_REMOVE_DEFAULT_TEXT_STATUS', 'Fout: De standaard aanvraagstatus kan niet gewist worden. Voer a.u.b. een nieuwe standaard bestelstatus in en probeer het nog en keer.');
define('ERROR_STATUS_USED_IN_TICKET', 'Fout: Deze aanvraagstatus wordt op dit moment bij de aanvragen gebruikt.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Fout: Deze aanvraagstatus wordt op dit moment in de aanvraaggeschiedenis.');
?>
